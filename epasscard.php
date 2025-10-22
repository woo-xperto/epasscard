<?php
/**
 * Plugin Name: Epasscard
 * Description: A plugin for managing Epasscard connections.
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

// Manually require all class files
require_once EPASSCARD_PLUGIN_DIR . 'includes/class-epasscard-assets.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/class-epasscard.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-admin.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-menu.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-admin-ajax.php';
require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/class-epasscard-footer.php';

// Register activation and deactivation hooks
register_activation_hook(__FILE__, ['Epasscard', 'activate']);
register_deactivation_hook(__FILE__, ['Epasscard', 'deactivate']);

// Initialize the plugin
add_action('plugins_loaded', function () {
    if (class_exists('Epasscard')) {
        $epasscard = new Epasscard();
        $epasscard->init();
    }
});

add_action('wp_ajax_get_epasscard_location', 'get_epasscard_location');
add_action('wp_ajax_nopriv_get_epasscard_location', 'get_epasscard_location');

function get_epasscard_location()
{
    check_ajax_referer('epasscard_ajax_nonce');

    $place_name = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
    $api_key    = get_option('epasscard_api_key', '');

    if (empty($place_name)) {
        wp_send_json_error('Place name is required');
    }

    // Use the dynamic place name in the API URL
    $api_url = 'https://api.epasscard.com/api/google/places/' . urlencode($place_name);

    $args = [
        'headers' => [
            'x-api-key' => $api_key,
        ],
        'timeout' => 15,
    ];

    $response = wp_remote_get($api_url, $args);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
    } else {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        wp_send_json_success($data);
    }
}


