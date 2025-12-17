<?php
/**
Plugin Name:       EpassCard
Plugin URI:        https://webcartisan.com/plugins/epasscard
Description:       Manage digital wallet passes for Apple Wallet, Google Wallet, and EpassCard.
Version:           1.0.0
Author:            WebCartisan
Author URI:        https://webcartisan.com/
License:           GPL v2 or later
License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain:       epasscard
Requires at least: 5.6
Requires PHP:      7.2
*/
defined('ABSPATH') || exit;

// Define plugin constants
define('EPASSC_VERSION', '1.0.0');
define('EPASSC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EPASSC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EPASSC_API_URL', 'https://api.epasscard.com/api/public/v1/');
define('EPASSC_API_CERTIFICATES', 'https://api.epasscard.com/api/certificate/all-certificates/');
define('EPASSC_API_KEY_VALID_URL', 'https://api.epasscard.com/api/public/v1/validate-api-key');
define('EPASSC_API_KEY_REFRESH_URL', 'https://api.epasscard.com/api/public/v1/refresh-api-key');
define('EPASSC_LOCATION_API_URL', 'https://api.epasscard.com/api/google/geocode/');
define('EPASSC_PLACE_API_URL', 'https://api.epasscard.com/api/google/places/');
define('EPASSC_API_KEY_LINK', 'https://app.epasscard.com/api-keys');
define('EPASSC_API_KEY', get_option('epassc_api_key', ''));

// Manually require all class files
require_once EPASSC_PLUGIN_DIR . 'includes/class-epasscard-assets.php';
require_once EPASSC_PLUGIN_DIR . 'includes/class-epasscard.php';
require_once EPASSC_PLUGIN_DIR . 'includes/admin/class-epasscard-admin.php';
require_once EPASSC_PLUGIN_DIR . 'includes/admin/class-epasscard-menu.php';
require_once EPASSC_PLUGIN_DIR . 'includes/admin/class-epasscard-admin-ajax.php';
require_once EPASSC_PLUGIN_DIR . 'includes/admin/class-epasscard-footer.php';

// Initialize the plugin
add_action('plugins_loaded', function () {
    if (class_exists('Epasscard')) {
        $epasscard = new Epasscard();
        $epasscard->epassc_init();
    }
});
