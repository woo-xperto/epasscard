<?php
class EPASSC_Assets
{
    /**
     * Constructor
     */
    public function __construct()
    {
        if (is_admin()) {
            // Load admin assets
            add_action('admin_enqueue_scripts', [$this, 'epassc_enqueue_admin_assets']);
        }

    }

    /**
     * Enqueue admin assets
     */
    public function epassc_enqueue_admin_assets($hook)
    {

        if ('toplevel_page_epasscard' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'epasscard-admin-css',
            EPASSC_PLUGIN_URL . 'assets/css/epasscard-admin.css',
            [],
            EPASSC_VERSION
        );

        wp_enqueue_style(
            'epasscard-admin-responsive-css',
            EPASSC_PLUGIN_URL . 'assets/css/epasscard-admin-responsive.css',
            [],
            EPASSC_VERSION
        );

        wp_enqueue_style(
            'select-2-css',
            EPASSC_PLUGIN_URL . 'assets/css/select2.min.css',
            [],
            EPASSC_VERSION
        );

        wp_enqueue_style(
            'cropper-css',
            EPASSC_PLUGIN_URL . 'assets/css/croppie.css',
            [],
            EPASSC_VERSION
        );

        wp_enqueue_style(
            'jquery-ui',
            EPASSC_PLUGIN_URL . 'assets/css/jquery-ui.css',
            [],
            EPASSC_VERSION
        );

        wp_enqueue_style(
            'color-picker-css',
            EPASSC_PLUGIN_URL . 'assets/css/evol-colorpicker.min.css',
            [],
            EPASSC_VERSION
        );

        wp_enqueue_script('jquery');

        wp_enqueue_script(
            'epasscard-admin-js',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-admin.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'lockscreen-js',
            EPASSC_PLUGIN_URL . 'assets/js/lockscreen.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'setting-js',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-setting.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-admin-javascript',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-admin-javascript.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-info',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-info.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-backfields-script',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-back-fields-script.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-additional-script',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-additional-fields.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-auxiliary-script',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-auxiliary-fields.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-image-script',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-image-script.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'elect-2-js',
            EPASSC_PLUGIN_URL . 'assets/js/select2.min.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

         wp_enqueue_script(
            'cropper-js',
            EPASSC_PLUGIN_URL . 'assets/js/croppie.min.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_script(
            'color-picker-js',
            EPASSC_PLUGIN_URL . 'assets/js/evol-colorpicker.min.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-extensions-js',
            EPASSC_PLUGIN_URL . 'assets/js/epasscard-connection.js',
            ['jquery'],
            EPASSC_VERSION,
            true
        );


        $api_key         = get_option('epassc_api_key', '');

        $localization_data = [
            'ajax_url'           => admin_url('admin-ajax.php'),
            'nonce'              => wp_create_nonce('epasscard_ajax_nonce'),
            'error_required'     => __('This field is required', 'epasscard'),
            'epasscardPluginUrl' => plugins_url('', __DIR__),
            'api_key'            => $api_key,
        ];

        // Localize for first script
        wp_localize_script(
            'epasscard-admin-js',
            'epasscard_admin',
            $localization_data
        );

        // Localize for second script
        wp_localize_script(
            'epasscard-admin-javascript',
            'epasscard_admin', // Same object name if you want identical access in JS
            $localization_data
        );

        // Localize for location script
        wp_localize_script(
            'lockscreen-js',
            'epasscard_admin', // Same object name if you want identical access in JS
            $localization_data
        );

        // Localize for info script
        wp_localize_script(
            'epasscard-info',
            'epasscard_admin', // Same object name if you want identical access in JS
            $localization_data
        );

        // Localize for epasscard update script
        wp_localize_script(
            'epasscard-update',
            'epasscard_admin', // Same object name if you want identical access in JS
            $localization_data
        );

        // Localize for epasscard extension script
        wp_localize_script(
            'epasscard-extensions-js',
            'epasscard_admin', // Same object name if you want identical access in JS
            $localization_data
        );

    }

}
