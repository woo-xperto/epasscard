<?php
/**
 * Third-party plugin dependency helpers.
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether a plugin bootstrap file is active.
 *
 * @param string $plugin_file Plugin path relative to wp-content/plugins.
 * @return bool
 */
function epc_is_plugin_active( $plugin_file ) {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	return is_plugin_active( $plugin_file );
}

/**
 * Whether MemberPress is installed and active.
 *
 * @return bool
 */
function epc_is_memberpress_active() {
	if ( defined( 'MEPR_VERSION' ) || defined( 'MEPR_PLUGIN_NAME' ) ) {
		return true;
	}

	if ( class_exists( 'MeprProduct' ) || class_exists( 'MeprAppCtrl' ) ) {
		return true;
	}

	$plugin_files = array(
		'memberpress/memberpress.php',
		'memberpress-developer/memberpress.php',
	);

	foreach ( $plugin_files as $plugin_file ) {
		if ( epc_is_plugin_active( $plugin_file ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Whether WooCommerce Subscriptions is installed and active.
 *
 * @return bool
 */
function epc_is_woocommerce_subscriptions_active() {
	if ( class_exists( 'WC_Subscriptions' ) || function_exists( 'wcs_get_subscriptions' ) ) {
		return true;
	}

	$plugin_files = array(
		'woocommerce-subscriptions/woocommerce-subscriptions.php',
	);

	foreach ( $plugin_files as $plugin_file ) {
		if ( epc_is_plugin_active( $plugin_file ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Whether Paid Memberships Pro is installed and active.
 *
 * @return bool
 */
function epc_is_paid_memberships_pro_active() {
	if ( defined( 'PMPRO_VERSION' ) || defined( 'PMPRO_DIR' ) ) {
		return true;
	}

	if ( function_exists( 'pmpro_getAllLevels' ) || function_exists( 'pmpro_changeMembershipLevel' ) ) {
		return true;
	}

	$plugin_files = array(
		'paid-memberships-pro/paid-memberships-pro.php',
	);

	foreach ( $plugin_files as $plugin_file ) {
		if ( epc_is_plugin_active( $plugin_file ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Whether Ultimate Membership Pro (Indeed) is installed and active.
 *
 * @return bool
 */
function epc_is_ultimate_membership_pro_active() {
	if ( defined( 'IHC_PATH' ) || defined( 'IHC_URL' ) || defined( 'IHC_PLUGIN_VERSION' ) ) {
		return true;
	}

	if ( function_exists( 'ihc_get_level_by_id' ) || function_exists( 'ihc_handle_levels_assign' ) ) {
		return true;
	}

	$plugin_files = array(
		'indeed-membership-pro/indeed-membership-pro.php',
		'ultimate-membership-pro/indeed-membership-pro.php',
	);

	foreach ( $plugin_files as $plugin_file ) {
		if ( epc_is_plugin_active( $plugin_file ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Build a full name from first + last, with optional fallback.
 *
 * @param string $first_name First name.
 * @param string $last_name  Last name.
 * @param string $fallback   Used when both names are empty (e.g. display name).
 * @return string
 */
function epc_format_user_full_name( $first_name, $last_name, $fallback = '' ) {
	$full = trim( trim( (string) $first_name ) . ' ' . trim( (string) $last_name ) );

	if ( '' !== $full ) {
		return $full;
	}

	return trim( (string) $fallback );
}

/**
 * WordPress option name for EpassCard connection settings.
 *
 * @return string
 */
function epc_get_connection_option_name() {
	return EPC_Connection::OPTION;
}

/**
 * Get the decrypted EpassCard X-Api-Key for custom integrations.
 *
 * @return string Plain API key, or empty string when not connected.
 */
function epc_get_api_key() {
	if ( ! class_exists( 'EPC_Connection' ) ) {
		return '';
	}

	return EPC_Connection::get_api_key();
}

/**
 * Register a custom EpassCard integration module.
 *
 * Call from plugins_loaded (priority < 20) so the module is available before EpassCard boots.
 *
 * @param string $slug       Unique module slug (lowercase, hyphens).
 * @param string $file       Module PHP file name (relative to modules/) or absolute path.
 * @param string $class_name PHP class extending EPC_Module.
 * @return void
 */
function epc_register_module( $slug, $file, $class_name ) {
	$slug       = sanitize_key( (string) $slug );
	$file       = (string) $file;
	$class_name = (string) $class_name;

	if ( '' === $slug || '' === $file || '' === $class_name ) {
		return;
	}

	add_filter(
		'epc_module_files',
		static function ( $files ) use ( $slug, $file ) {
			if ( ! is_array( $files ) ) {
				$files = array();
			}
			$files[ $slug ] = $file;
			return $files;
		}
	);

	add_filter(
		'epc_module_classes',
		static function ( $map ) use ( $slug, $class_name ) {
			if ( ! is_array( $map ) ) {
				$map = array();
			}
			$map[ $slug ] = $class_name;
			return $map;
		}
	);

	if ( str_starts_with( $file, '/' ) || ( 1 < strlen( $file ) && ':' === $file[1] ) ) {
		add_filter(
			'epc_module_path',
			static function ( $path, $filter_slug, $filter_file ) use ( $slug, $file ) {
				if ( $filter_slug === $slug && $filter_file === $file ) {
					return $file;
				}
				return $path;
			},
			10,
			3
		);
	}
}
