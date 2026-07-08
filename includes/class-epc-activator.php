<?php
/**
 * Activation and deactivation hooks.
 *
 * @package EpassCard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin activation / deactivation.
 */
class EPC_Activator {

	/**
	 * Run on plugin activation.
	 *
	 * @return void
	 */
	public static function activate() {
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-db.php';
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-api-log.php';
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-connection.php';
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-pass-notifications.php';
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-frontend.php';
		EPC_DB::install();
		EPC_Api_Log::schedule_cron();
		EPC_Connection::schedule_cron();
		EPC_Pass_Notifications::schedule_cron();
		EPC_Frontend::register_wc_endpoint();
		flush_rewrite_rules();

		require_once EPC_PLUGIN_DIR . 'includes/class-epc-welcome.php';
		EPC_Welcome::flag_activation_redirect();
	}

	/**
	 * Run on plugin deactivation.
	 *
	 * @return void
	 */
	public static function deactivate() {
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-connection.php';
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-api-log.php';
		require_once EPC_PLUGIN_DIR . 'includes/class-epc-pass-notifications.php';
		EPC_Connection::clear_cron();
		EPC_Api_Log::clear_cron();
		EPC_Pass_Notifications::clear_cron();
	}
}
