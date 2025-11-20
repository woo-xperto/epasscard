<?php
/**
 * Plugin Name: EpassCard
 * Description: Manage digital wallet passes for Apple Wallet, Google Wallet, and EpassCard.
 * Version: 1.0.0
 * Author: Your Name
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: epasscard
 */

defined('ABSPATH') || exit;

// Define plugin constants
define('EPASSCARD_VERSION', '1.0.0');
define('EPASSCARD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EPASSCARD_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EPASSCARD_API_URL', 'https://api.epasscard.com/api/public/v1/');
define('EPASSCARD_API_CERTIFICATES', 'https://api.epasscard.com/api/certificate/all-certificates/');


// Manually require all class files
require_once EPASSCARD_PLUGIN_DIR . 'includes/class-epasscard-assets.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/class-epasscard.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-admin.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-menu.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-admin-ajax.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-footer.php';

// Initialize the plugin
add_action('plugins_loaded', function () {
    if (class_exists('Epasscard')) {
        $epasscard = new Epasscard();
        $epasscard->Epasscard_init();
    }
});
