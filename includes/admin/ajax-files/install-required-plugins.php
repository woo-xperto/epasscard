<?php
 check_ajax_referer('epasscard_ajax_nonce');
 
if (!current_user_can('install_plugins')) {
        wp_send_json_error(['message' => 'Permission denied']);
    }

    $plugin_type = sanitize_text_field( wp_unslash( $_POST['plugin_type'] ?? '' ) );

    if(isset($plugin_type)){

    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/misc.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    //$plugin_url = esc_url_raw($_POST['plugin_url']);
     if($plugin_type == "giftcard"){
        $plugin_url = 'https://downloads.wordpress.org/plugin/gift-card-wooxperto-llc.1.0.0.zip';
        // Get plugin file path from installed plugin directory
        $plugin_dir = WP_PLUGIN_DIR . '/gift-card-wooxperto-llc';
        $plugin_file = $plugin_dir . '/gift-card-wooxperto-llc.php';
        $plugin_relative_path = 'gift-card-wooxperto-llc/gift-card-wooxperto-llc.php';
     }else if($plugin_type == "giftcard_extension"){
        $plugin_url = 'https://downloads.wordpress.org/plugin/epasscard-giftcard-extension.1.0.0.zip';
        // Get plugin file path from installed plugin directory
        $plugin_dir = WP_PLUGIN_DIR . '/epasscard-giftcard-extension';
        $plugin_file = $plugin_dir . '/epasscard-giftcard-extension.php';
        $plugin_relative_path = 'epasscard-giftcard-extension/epasscard-giftcard-extension.php';
     }

    
    $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
    $result = $upgrader->install($plugin_url);

    if (is_wp_error($result)) {
        wp_send_json_error(['message' => $result->get_error_message()]);
    }

    if (file_exists($plugin_file)) {
        $relative_path = $plugin_relative_path;
        $activate = activate_plugin($relative_path);

        if (is_wp_error($activate)) {
            wp_send_json_error(['message' => 'Installed but activation failed: ' . $activate->get_error_message()]);
        }

        wp_send_json_success(['message' => 'Plugin installed and activated successfully']);
    } else {
        wp_send_json_error(['message' => 'Plugin installed but file not found for activation']);
    }
}