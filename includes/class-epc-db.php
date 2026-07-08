<?php
/**
 * Custom database table for issued passes.
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pass records persistence.
 */
class EPC_DB {

	/**
	 * Table name without prefix.
	 */
	public const TABLE = 'epc_passes';

	/**
	 * Install or upgrade schema.
	 *
	 * @return void
	 */
	public static function install() {
		global $wpdb;

		$table           = self::table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			module varchar(32) NOT NULL DEFAULT '',
			source_id bigint(20) unsigned NOT NULL DEFAULT 0,
			entity_id bigint(20) unsigned NOT NULL DEFAULT 0,
			user_id bigint(20) unsigned NOT NULL DEFAULT 0,
			pass_uid varchar(36) NOT NULL DEFAULT '',
			pass_link text NOT NULL,
			template_uid varchar(36) NOT NULL DEFAULT '',
			status varchar(32) NOT NULL DEFAULT 'active',
			meta longtext NULL,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY module_source (module, source_id),
			KEY user_id (user_id),
			KEY pass_uid (pass_uid)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		require_once EPC_PLUGIN_DIR . 'includes/class-epc-api-log.php';
		EPC_Api_Log::install();

		update_option( 'epc_db_version', EPC_DB_VERSION );
	}

	/**
	 * Run schema upgrades when the plugin DB version changes.
	 *
	 * @return void
	 */
	public static function maybe_upgrade() {
		$installed = (string) get_option( 'epc_db_version', '0' );
		if ( version_compare( $installed, EPC_DB_VERSION, '<' ) ) {
			self::install();
		}
	}

	/**
	 * Full table name.
	 *
	 * @return string
	 */
	public static function table_name() {
		global $wpdb;
		return $wpdb->prefix . self::TABLE;
	}

	/**
	 * Insert or update a pass row.
	 *
	 * @param array<string, mixed> $data Row data.
	 * @return int|false Row ID or false.
	 */
	public static function upsert_pass( array $data ) {
		global $wpdb;

		$table  = self::table_name();
		$module = sanitize_key( (string) ( $data['module'] ?? '' ) );
		$source = absint( $data['source_id'] ?? 0 );

		if ( '' === $module || $source <= 0 ) {
			return false;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table upsert lookup.
		$existing = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT id FROM %i WHERE module = %s AND source_id = %d LIMIT 1',
				$table,
				$module,
				$source
			)
		);

		$row = array(
			'module'        => $module,
			'source_id'     => $source,
			'entity_id'     => absint( $data['entity_id'] ?? 0 ),
			'user_id'       => absint( $data['user_id'] ?? 0 ),
			'pass_uid'      => sanitize_text_field( (string) ( $data['pass_uid'] ?? '' ) ),
			'pass_link'     => esc_url_raw( (string) ( $data['pass_link'] ?? '' ) ),
			'template_uid'  => sanitize_text_field( (string) ( $data['template_uid'] ?? '' ) ),
			'status'        => sanitize_key( (string) ( $data['status'] ?? 'active' ) ),
			'meta'          => isset( $data['meta'] ) ? wp_json_encode( $data['meta'] ) : null,
		);

		if ( $existing ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table write.
			$wpdb->update(
				$table,
				$row,
				array( 'id' => (int) $existing ),
				array( '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);
			return (int) $existing;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery -- Custom table write.
		$wpdb->insert(
			$table,
			$row,
			array( '%s', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		return $wpdb->insert_id ? (int) $wpdb->insert_id : false;
	}

	/**
	 * Get pass by module + source id.
	 *
	 * @param string $module    Module slug.
	 * @param int    $source_id Source record id.
	 * @return object|null
	 */
	public static function get_pass( $module, $source_id ) {
		global $wpdb;

		$table = self::table_name();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table read.
		$row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE module = %s AND source_id = %d LIMIT 1',
				$table,
				sanitize_key( $module ),
				absint( $source_id )
			)
		);

		return $row ? $row : null;
	}

	/**
	 * Resolve a pass row from a pass UUID, internal id, or source record id.
	 *
	 * @param string $module     Module slug.
	 * @param string $identifier Pass UUID, internal row id, or source id.
	 * @return object|null
	 */
	public static function resolve_pass_for_module( $module, $identifier ) {
		global $wpdb;

		$module     = sanitize_key( (string) $module );
		$identifier = trim( (string) $identifier );

		if ( '' === $module || '' === $identifier ) {
			return null;
		}

		$table = self::table_name();
		$san   = EPC_Api_Client::sanitize_uid( $identifier );

		if ( false !== $san ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table read.
			$row = $wpdb->get_row(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND pass_uid = %s LIMIT 1',
					$table,
					$module,
					$san
				)
			);

			return $row ? $row : null;
		}

		if ( ! ctype_digit( $identifier ) ) {
			return null;
		}

		$numeric_id = absint( $identifier );
		if ( $numeric_id <= 0 ) {
			return null;
		}

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table read.
		$row = $wpdb->get_row(
			$wpdb->prepare(
				'SELECT * FROM %i WHERE module = %s AND id = %d LIMIT 1',
				$table,
				$module,
				$numeric_id
			)
		);
		if ( $row ) {
			return $row;
		}

		return self::get_pass( $module, $numeric_id );
	}

	/**
	 * Query passes for list table.
	 *
	 * @param array<string, mixed> $args Query args.
	 * @return array{items: array<int, object>, total: int}
	 */
	public static function query_passes( array $args ) {
		global $wpdb;

		$table      = self::table_name();
		$module     = sanitize_key( (string) ( $args['module'] ?? '' ) );
		$search     = sanitize_text_field( (string) ( $args['search'] ?? '' ) );
		$status     = sanitize_key( (string) ( $args['status'] ?? '' ) );
		$entity_id  = absint( $args['entity_id'] ?? 0 );
		$page       = max( 1, absint( $args['page'] ?? 1 ) );
		$limit      = max( 1, min( 100, absint( $args['per_page'] ?? 20 ) ) );
		$count_only = ! empty( $args['count_only'] );
		$offset     = ( $page - 1 ) * $limit;

		$has_status = '' !== $status;
		$has_entity = $entity_id > 0;
		$has_search = '' !== $search;
		$like       = $has_search ? '%' . $wpdb->esc_like( $search ) . '%' : '';

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table admin list.
		if ( ! $has_status && ! $has_entity && ! $has_search ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s',
					$table,
					$module
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$limit,
					$offset
				)
			);
		} elseif ( $has_status && ! $has_entity && ! $has_search ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s AND status = %s',
					$table,
					$module,
					$status
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND status = %s ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$status,
					$limit,
					$offset
				)
			);
		} elseif ( ! $has_status && $has_entity && ! $has_search ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s AND entity_id = %d',
					$table,
					$module,
					$entity_id
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND entity_id = %d ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$entity_id,
					$limit,
					$offset
				)
			);
		} elseif ( ! $has_status && ! $has_entity && $has_search ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s)',
					$table,
					$module,
					$like,
					$like,
					$like,
					$like
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s) ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$like,
					$like,
					$like,
					$like,
					$limit,
					$offset
				)
			);
		} elseif ( $has_status && $has_entity && ! $has_search ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s AND status = %s AND entity_id = %d',
					$table,
					$module,
					$status,
					$entity_id
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND status = %s AND entity_id = %d ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$status,
					$entity_id,
					$limit,
					$offset
				)
			);
		} elseif ( $has_status && ! $has_entity && $has_search ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s AND status = %s AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s)',
					$table,
					$module,
					$status,
					$like,
					$like,
					$like,
					$like
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND status = %s AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s) ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$status,
					$like,
					$like,
					$like,
					$like,
					$limit,
					$offset
				)
			);
		} elseif ( ! $has_status && $has_entity && $has_search ) {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s AND entity_id = %d AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s)',
					$table,
					$module,
					$entity_id,
					$like,
					$like,
					$like,
					$like
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND entity_id = %d AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s) ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$entity_id,
					$like,
					$like,
					$like,
					$like,
					$limit,
					$offset
				)
			);
		} else {
			$total = (int) $wpdb->get_var(
				$wpdb->prepare(
					'SELECT COUNT(*) FROM %i WHERE module = %s AND status = %s AND entity_id = %d AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s)',
					$table,
					$module,
					$status,
					$entity_id,
					$like,
					$like,
					$like,
					$like
				)
			);
			$items = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM %i WHERE module = %s AND status = %s AND entity_id = %d AND (pass_uid LIKE %s OR pass_link LIKE %s OR CAST(source_id AS CHAR) LIKE %s OR CAST(user_id AS CHAR) LIKE %s) ORDER BY updated_at DESC LIMIT %d OFFSET %d',
					$table,
					$module,
					$status,
					$entity_id,
					$like,
					$like,
					$like,
					$like,
					$limit,
					$offset
				)
			);
		}

		if ( $count_only ) {
			return array(
				'items' => array(),
				'total' => $total,
			);
		}

		return array(
			'items' => is_array( $items ) ? $items : array(),
			'total' => $total,
		);
	}

	/**
	 * Active passes for a WordPress user.
	 *
	 * @param int $user_id User id.
	 * @return array<int, object>
	 */
	public static function get_active_passes_for_user( $user_id ) {
		global $wpdb;

		$user_id = absint( $user_id );
		if ( $user_id <= 0 ) {
			return array();
		}

		$table = self::table_name();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table read.
		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM %i WHERE user_id = %d AND status = %s AND pass_uid <> '' ORDER BY updated_at DESC",
				$table,
				$user_id,
				'active'
			)
		);

		return is_array( $items ) ? $items : array();
	}

	/**
	 * Active passes for a module.
	 *
	 * @param string $module Module slug.
	 * @return array<int, object>
	 */
	public static function get_active_passes_for_module( $module ) {
		global $wpdb;

		$module = sanitize_key( (string) $module );
		if ( '' === $module ) {
			return array();
		}

		$table = self::table_name();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table read.
		$items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM %i WHERE module = %s AND status = %s AND pass_uid <> '' ORDER BY updated_at DESC",
				$table,
				$module,
				'active'
			)
		);

		return is_array( $items ) ? $items : array();
	}

	/**
	 * Decode pass meta JSON from a row.
	 *
	 * @param object $pass_row Pass row.
	 * @return array<string, mixed>
	 */
	public static function get_pass_meta( $pass_row ) {
		if ( ! is_object( $pass_row ) || empty( $pass_row->meta ) ) {
			return array();
		}

		$decoded = json_decode( (string) $pass_row->meta, true );
		return is_array( $decoded ) ? $decoded : array();
	}

	/**
	 * Persist pass meta JSON on a row.
	 *
	 * @param object               $pass_row Pass row.
	 * @param array<string, mixed> $meta     Meta array.
	 * @return bool
	 */
	public static function update_pass_meta( $pass_row, array $meta ) {
		global $wpdb;

		if ( ! is_object( $pass_row ) || empty( $pass_row->id ) ) {
			return false;
		}

		$table = self::table_name();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom table write.
		$updated = $wpdb->update(
			$table,
			array( 'meta' => wp_json_encode( $meta ) ),
			array( 'id' => (int) $pass_row->id ),
			array( '%s' ),
			array( '%d' )
		);

		return false !== $updated;
	}
}
