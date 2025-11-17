<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 check_ajax_referer('epasscard_ajax_nonce');
 
if (!current_user_can('install_plugins')) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    $epasscard_plugin_type = sanitize_text_field( wp_unslash( $_POST['plugin_type'] ?? '' ) );

    if(isset($epasscard_plugin_type)){

    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/misc.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    //$epasscard_plugin_url = esc_url_raw($_POST['plugin_url']);
     if($epasscard_plugin_type == "giftcard"){
        $epasscard_plugin_url = 'https://downloads.wordpress.org/plugin/gift-card-wooxperto-llc.1.0.0.zip';
        // Get plugin file path from installed plugin directory
        $epasscard_plugin_dir = WP_PLUGIN_DIR . '/gift-card-wooxperto-llc';
        $epasscard_plugin_file = $epasscard_plugin_dir . '/gift-card-wooxperto-llc.php';
        $epasscard_plugin_relative_path = 'gift-card-wooxperto-llc/gift-card-wooxperto-llc.php';
     }else if($epasscard_plugin_type == "giftcard_extension"){
        $epasscard_plugin_url = 'https://downloads.wordpress.org/plugin/epasscard-giftcard-extension.1.0.0.zip';
        // Get plugin file path from installed plugin directory
        $epasscard_plugin_dir = WP_PLUGIN_DIR . '/epasscard-giftcard-extension';
        $epasscard_plugin_file = $epasscard_plugin_dir . '/epasscard-giftcard-extension.php';
        $epasscard_plugin_relative_path = 'epasscard-giftcard-extension/epasscard-giftcard-extension.php';
     }else if($epasscard_plugin_type == "yith_giftcard"){
        $epasscard_plugin_url = 'https://downloads.wordpress.org/plugin/yith-woocommerce-gift-cards.4.29.0.zip';
        // Get plugin file path from installed plugin directory
        $epasscard_plugin_dir = WP_PLUGIN_DIR . '/yith-woocommerce-gift-cards';
        $epasscard_plugin_file = $epasscard_plugin_dir . '/init.php';
        $epasscard_plugin_relative_path = 'yith-woocommerce-gift-cards/init.php';
     }else if($epasscard_plugin_type == "yith_extension"){
        $epasscard_plugin_url = 'https://downloads.wordpress.org/plugin/epasscard-yith-extension.1.0.0.zip';
        // Get plugin file path from installed plugin directory
        $epasscard_plugin_dir = WP_PLUGIN_DIR . '/epasscard-yith-extension';
        $epasscard_plugin_file = $epasscard_plugin_dir . '/epasscard-yith-extension.php';
        $epasscard_plugin_relative_path = 'epasscard-yith-extension/epasscard-yith-extension.php';
     }else if($epasscard_plugin_type == "ultimate_giftcard"){
        $epasscard_plugin_url = 'https://downloads.wordpress.org/plugin/woo-gift-cards-lite.3.2.2.zip';
        // Get plugin file path from installed plugin directory
        $epasscard_plugin_dir = WP_PLUGIN_DIR . '/woo-gift-cards-lite';
        $epasscard_plugin_file = $epasscard_plugin_dir . '/woocommerce_gift_cards_lite.php';
        $epasscard_plugin_relative_path = 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php';
     }else if($epasscard_plugin_type == "ultimate_extension"){
        $epasscard_plugin_url = 'https://downloads.wordpress.org/plugin/epasscard-woo-gift-cards-lite-extension.1.0.0.zip';
        // Get plugin file path from installed plugin directory
        $epasscard_plugin_dir = WP_PLUGIN_DIR . '/epasscard-woo-gift-cards-lite-extension';
        $epasscard_plugin_file = $epasscard_plugin_dir . '/epasscard-gift-cards-lite-extension.php';
        $epasscard_plugin_relative_path = 'epasscard-woo-gift-cards-lite-extension/epasscard-gift-cards-lite-extension.php';
     }
    
    $epasscard_upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
    $epasscard_result = $epasscard_upgrader->install($epasscard_plugin_url);

    if (is_wp_error($epasscard_result)) {
        wp_send_json_error(['message' => $epasscard_result->get_error_message()]);
    }

    if (file_exists($epasscard_plugin_file)) {
        $epasscard_relative_path = $epasscard_plugin_relative_path;
        $epasscard_activate = activate_plugin($epasscard_relative_path);

        if (is_wp_error($epasscard_activate)) {
            wp_send_json_error(['message' => 'Installed but activation failed: ' . $epasscard_activate->get_error_message()]);
        }

        wp_send_json_success(['message' => 'Plugin installed and activated successfully']);
    } else {
        wp_send_json_error(['message' => 'Plugin installed but file not found for activation']);
    }
}