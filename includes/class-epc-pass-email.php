<?php
/**
 * Pass link email delivery.
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sends wallet pass links to members via wp_mail.
 */
class EPC_Pass_Email {

	public const OPTION = 'epc_pass_email_settings';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_ajax_epc_save_pass_email_settings', array( __CLASS__, 'ajax_save_settings' ) );

		add_action( 'woocommerce_email_after_order_table', array( __CLASS__, 'render_wc_order_email_passes' ), 15, 4 );
	}

	/**
	 * Default email settings.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_default_settings() {
		return array(
			'auto_on_create'        => true,
			'include_on_wc_order'   => true,
			'subject'               => __( 'Your wallet pass for {site_name}', 'epasscard' ),
			'body'                  => __( "Hi {user_first_name},\n\nYour digital wallet pass is ready. Add it to Apple Wallet or Google Wallet using the link below:\n\n{pass_link}\n\nThanks,\n{site_name}", 'epasscard' ),
		);
	}

	/**
	 * Saved email settings merged with defaults.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_settings() {
		$saved = get_option( self::OPTION, array() );
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return wp_parse_args( $saved, self::get_default_settings() );
	}

	/**
	 * Save pass email settings from POST data.
	 *
	 * @return true|\WP_Error
	 */
	public static function save_settings_from_request() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'epc_forbidden', __( 'Permission denied.', 'epasscard' ) );
		}

		$settings = array(
			// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified in ajax_save_settings().
			'auto_on_create'      => ! empty( $_POST['epc_email_auto_on_create'] ),
			'include_on_wc_order' => ! empty( $_POST['epc_email_include_on_wc_order'] ),
			'subject'             => isset( $_POST['epc_email_subject'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['epc_email_subject'] ) ) : '',
			'body'                => isset( $_POST['epc_email_body'] ) ? sanitize_textarea_field( wp_unslash( (string) $_POST['epc_email_body'] ) ) : '',
			// phpcs:enable WordPress.Security.NonceVerification.Missing
		);

		update_option( self::OPTION, $settings );

		return true;
	}

	/**
	 * AJAX: save pass email settings.
	 *
	 * @return void
	 */
	public static function ajax_save_settings() {
		check_ajax_referer( 'epc_admin', 'nonce' );

		$result = self::save_settings_from_request();
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
		}

		wp_send_json_success(
			array(
				'message' => __( 'Pass email settings saved.', 'epasscard' ),
			)
		);
	}

	/**
	 * Whether to email automatically after pass creation.
	 *
	 * @param string $mode sync|create|update.
	 * @return bool
	 */
	public static function should_auto_send( $mode ) {
		$settings = self::get_settings();
		if ( empty( $settings['auto_on_create'] ) ) {
			return false;
		}

		$mode = sanitize_key( (string) $mode );

		/**
		 * Filter whether a pass link email should be sent automatically.
		 *
		 * @param bool   $send     Default send flag.
		 * @param string $mode     Pass sync mode.
		 * @param object $pass_row Pass row (may be partial before insert).
		 */
		return (bool) apply_filters( 'epc_pass_email_auto_send', 'create' === $mode || 'sync' === $mode, $mode, null );
	}

	/**
	 * Send pass link email for a pass row.
	 *
	 * @param object               $pass_row Pass DB row.
	 * @param array<string, mixed> $args     Optional overrides (to, subject, body, module_label).
	 * @return true|\WP_Error
	 */
	public static function send_for_pass_row( $pass_row, array $args = array() ) {
		if ( ! is_object( $pass_row ) || empty( $pass_row->pass_link ) ) {
			return new WP_Error( 'epc_no_pass_link', __( 'This pass does not have a wallet link yet.', 'epasscard' ) );
		}

		$user_id = isset( $pass_row->user_id ) ? absint( $pass_row->user_id ) : 0;
		$user    = $user_id > 0 ? get_userdata( $user_id ) : false;

		$to = isset( $args['to'] ) ? sanitize_email( (string) $args['to'] ) : '';
		if ( '' === $to && $user ) {
			$to = sanitize_email( (string) $user->user_email );
		}

		if ( '' === $to || ! is_email( $to ) ) {
			return new WP_Error( 'epc_no_recipient', __( 'No valid email address for this member.', 'epasscard' ) );
		}

		$settings = self::get_settings();
		$module   = isset( $pass_row->module ) ? sanitize_key( (string) $pass_row->module ) : '';

		$replacements = self::build_replacements( $pass_row, $user, $args );

		$subject = isset( $args['subject'] ) ? (string) $args['subject'] : (string) $settings['subject'];
		$body    = isset( $args['body'] ) ? (string) $args['body'] : (string) $settings['body'];

		$subject = self::replace_tags( $subject, $replacements );
		$body    = self::replace_tags( $body, $replacements );

		/**
		 * Filter pass link email subject.
		 *
		 * @param string $subject      Email subject.
		 * @param object $pass_row     Pass row.
		 * @param array  $replacements Placeholder map.
		 * @param array  $args         Send args.
		 */
		$subject = (string) apply_filters( 'epc_pass_email_subject', $subject, $pass_row, $replacements, $args );

		/**
		 * Filter pass link email body (plain text).
		 *
		 * @param string $body         Email body.
		 * @param object $pass_row     Pass row.
		 * @param array  $replacements Placeholder map.
		 * @param array  $args         Send args.
		 */
		$body = (string) apply_filters( 'epc_pass_email_body', $body, $pass_row, $replacements, $args );

		$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

		/**
		 * Filter pass link email headers.
		 *
		 * @param array  $headers  Email headers.
		 * @param object $pass_row Pass row.
		 * @param array  $args     Send args.
		 */
		$headers = (array) apply_filters( 'epc_pass_email_headers', $headers, $pass_row, $args );

		/**
		 * Fires before a pass link email is sent.
		 *
		 * @param string $to       Recipient.
		 * @param string $subject  Subject.
		 * @param string $body     Body.
		 * @param object $pass_row Pass row.
		 */
		do_action( 'epc_before_pass_email_send', $to, $subject, $body, $pass_row );

		$sent = wp_mail( $to, $subject, $body, $headers );

		if ( ! $sent ) {
			return new WP_Error( 'epc_mail_failed', __( 'WordPress could not send the email. Check your mail configuration.', 'epasscard' ) );
		}

		/**
		 * Fires after a pass link email is sent successfully.
		 *
		 * @param string $to       Recipient.
		 * @param object $pass_row Pass row.
		 * @param string $subject  Subject used.
		 */
		do_action( 'epc_pass_email_sent', $to, $pass_row, $subject );

		return true;
	}

	/**
	 * Send by module + source id.
	 *
	 * @param string               $module    Module slug.
	 * @param int                  $source_id Source record id.
	 * @param array<string, mixed> $args      Optional overrides.
	 * @return true|\WP_Error
	 */
	public static function send_for_source( $module, $source_id, array $args = array() ) {
		$pass = EPC_DB::get_pass( sanitize_key( (string) $module ), absint( $source_id ) );
		if ( ! $pass ) {
			return new WP_Error( 'epc_pass_not_found', __( 'Pass not found.', 'epasscard' ) );
		}

		return self::send_for_pass_row( $pass, $args );
	}

	/**
	 * Maybe send after pass sync when enabled.
	 *
	 * @param string $module    Module slug.
	 * @param int    $source_id Source id.
	 * @param string $mode      Sync mode.
	 * @param bool   $created   Whether a new pass was created.
	 * @return void
	 */
	public static function maybe_send_after_sync( $module, $source_id, $mode, $created ) {
		if ( ! $created && 'create' !== $mode ) {
			return;
		}

		if ( ! self::should_auto_send( $created ? 'create' : $mode ) ) {
			return;
		}

		$pass = EPC_DB::get_pass( sanitize_key( (string) $module ), absint( $source_id ) );
		if ( ! $pass ) {
			return;
		}

		self::send_for_pass_row( $pass );
	}

	/**
	 * Append pass links to WooCommerce order emails.
	 *
	 * @param WC_Order $order         Order.
	 * @param bool     $sent_to_admin Admin email.
	 * @param bool     $plain_text    Plain text mode.
	 * @param WC_Email $email         Email object.
	 * @return void
	 */
	public static function render_wc_order_email_passes( $order, $sent_to_admin, $plain_text, $email ) {
		if ( $sent_to_admin || ! $order instanceof WC_Order ) {
			return;
		}

		$settings = self::get_settings();
		if ( empty( $settings['include_on_wc_order'] ) ) {
			return;
		}

		/**
		 * Filter whether to include pass links in a WooCommerce order email.
		 *
		 * @param bool     $include  Include flag.
		 * @param WC_Order $order    Order.
		 * @param WC_Email $email    Email instance.
		 */
		if ( ! apply_filters( 'epc_pass_email_include_on_wc_order', true, $order, $email ) ) {
			return;
		}

		$passes = self::get_passes_for_order( $order );
		if ( empty( $passes ) ) {
			return;
		}

		$lines = array( __( 'Your wallet passes:', 'epasscard' ) );
		foreach ( $passes as $pass ) {
			$lines[] = (string) $pass->pass_link;
		}

		$text = implode( $plain_text ? "\n" : '<br />', $lines );

		/**
		 * Filter WooCommerce order email pass block HTML/text.
		 *
		 * @param string        $text   Output.
		 * @param array<object> $passes Pass rows.
		 * @param WC_Order      $order  Order.
		 */
		$text = (string) apply_filters( 'epc_pass_email_wc_order_block', $text, $passes, $order );

		if ( $plain_text ) {
			echo "\n\n" . esc_html( wp_strip_all_tags( $text ) ) . "\n";
			return;
		}

		echo '<h2>' . esc_html__( 'Wallet passes', 'epasscard' ) . '</h2>';
		echo '<p>' . wp_kses_post( $text ) . '</p>';
	}

	/**
	 * Find pass rows related to a WooCommerce order.
	 *
	 * @param WC_Order $order Order.
	 * @return array<int, object>
	 */
	public static function get_passes_for_order( $order ) {
		$passes  = array();
		$user_id = (int) $order->get_user_id();

		if ( function_exists( 'wcs_get_subscriptions_for_order' ) && epc_is_woocommerce_subscriptions_active() ) {
			$subscriptions = wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'any' ) );
			foreach ( $subscriptions as $subscription ) {
				if ( ! is_object( $subscription ) || ! method_exists( $subscription, 'get_id' ) ) {
					continue;
				}
				$pass = EPC_DB::get_pass( 'woocommerce-subscriptions', (int) $subscription->get_id() );
				if ( $pass && ! empty( $pass->pass_link ) && 'active' === (string) $pass->status ) {
					$passes[ (int) $pass->id ] = $pass;
				}
			}
		}

		if ( $user_id > 0 ) {
			foreach ( EPC_DB::get_active_passes_for_user( $user_id ) as $pass ) {
				if ( ! empty( $pass->pass_link ) ) {
					$passes[ (int) $pass->id ] = $pass;
				}
			}
		}

		return array_values( $passes );
	}

	/**
	 * Build placeholder map for email templates.
	 *
	 * @param object          $pass_row Pass row.
	 * @param WP_User|false   $user     User object.
	 * @param array           $args     Extra args.
	 * @return array<string, string>
	 */
	private static function build_replacements( $pass_row, $user, array $args ) {
		$module_label = isset( $args['module_label'] ) ? (string) $args['module_label'] : '';
		if ( '' === $module_label && ! empty( $pass_row->module ) && function_exists( 'epc_plugin' ) ) {
			$mod = epc_plugin()->get_module( (string) $pass_row->module );
			if ( $mod ) {
				$module_label = $mod->get_label();
			}
		}

		$entity_label = '';
		if ( ! empty( $pass_row->module ) && ! empty( $pass_row->entity_id ) && function_exists( 'epc_plugin' ) ) {
			$mod = epc_plugin()->get_module( (string) $pass_row->module );
			if ( $mod ) {
				$entity_label = $mod->get_entity_label( (int) $pass_row->entity_id );
			}
		}

		return array(
			'user_first_name'   => $user ? (string) get_user_meta( $user->ID, 'first_name', true ) : '',
			'user_last_name'    => $user ? (string) get_user_meta( $user->ID, 'last_name', true ) : '',
			'user_display_name' => $user ? (string) $user->display_name : '',
			'user_email'        => $user ? (string) $user->user_email : '',
			'pass_link'         => (string) $pass_row->pass_link,
			'pass_uid'          => (string) $pass_row->pass_uid,
			'site_name'         => (string) get_bloginfo( 'name' ),
			'membership_title'  => $entity_label,
			'module_label'      => $module_label,
		);
	}

	/**
	 * Replace {tag} placeholders.
	 *
	 * @param string              $text         Template text.
	 * @param array<string,string> $replacements Values.
	 * @return string
	 */
	public static function replace_tags( $text, array $replacements ) {
		$out = (string) $text;
		foreach ( $replacements as $key => $value ) {
			$out = str_replace( '{' . $key . '}', (string) $value, $out );
		}
		return $out;
	}
}
