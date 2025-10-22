<?php
class Epasscard_Load_Assets
{
    /**
     * Constructor
     */
    public function __construct()
    {
        if (is_admin()) {
            // Load admin assets
            add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        }

    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook)
    {

        if ('toplevel_page_epasscard' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'epasscard-admin-css',
            EPASSCARD_PLUGIN_URL . 'assets/css/epasscard-admin.css',
            [],
            EPASSCARD_VERSION
        );

        wp_enqueue_style(
            'epasscard-admin-responsive-css',
            EPASSCARD_PLUGIN_URL . 'assets/css/epasscard-admin-responsive.css',
            [],
            EPASSCARD_VERSION
        );

        wp_enqueue_style(
            'select-2-css',
            EPASSCARD_PLUGIN_URL . 'assets/css/select2.min.css',
            [],
            EPASSCARD_VERSION
        );

        wp_enqueue_style(
            'cropper-css',
            EPASSCARD_PLUGIN_URL . 'assets/css/cropper.min.css',
            [],
            EPASSCARD_VERSION
        );

        wp_enqueue_style(
            'jquery-ui',
            EPASSCARD_PLUGIN_URL . 'assets/css/jquery-ui.css',
            [],
            EPASSCARD_VERSION
        );

        wp_enqueue_style(
            'color-picker-css',
            EPASSCARD_PLUGIN_URL . 'assets/css/evol-colorpicker.min.css',
            [],
            EPASSCARD_VERSION
        );

        wp_enqueue_script('jquery');

        wp_enqueue_script(
            'epasscard-admin-js',
            EPASSCARD_PLUGIN_URL . 'assets/js/epasscard-admin.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'lockscreen-js',
            EPASSCARD_PLUGIN_URL . 'assets/js/lockscreen.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'setting-js',
            EPASSCARD_PLUGIN_URL . 'assets/js/epasscard-setting.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-admin-javascript',
            EPASSCARD_PLUGIN_URL . 'assets/js/epasscard-admin-javascript.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-info',
            EPASSCARD_PLUGIN_URL . 'assets/js/epasscard-info.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-backfields-script',
            EPASSCARD_PLUGIN_URL . 'assets/js/epasscard-back-fields-script.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-additional-script',
            EPASSCARD_PLUGIN_URL . 'assets/js/epasscard-additional-fields.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'elect-2-js',
            EPASSCARD_PLUGIN_URL . 'assets/js/select2.min.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'cropper-js',
            EPASSCARD_PLUGIN_URL . 'assets/js/cropper.min.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_script(
            'color-picker-js',
            EPASSCARD_PLUGIN_URL . 'assets/js/evol-colorpicker.min.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        wp_enqueue_script(
            'epasscard-extensions-js',
            EPASSCARD_PLUGIN_URL . 'assets/js/epasscard-extensions.js',
            ['jquery'],
            EPASSCARD_VERSION,
            true
        );

        $organization_id = get_option('epasscard_organization_id', '');
        $api_key         = get_option('epasscard_api_key', '');

        $localization_data = [
            'ajax_url'           => admin_url('admin-ajax.php'),
            'nonce'              => wp_create_nonce('epasscard_ajax_nonce'),
            'error_required'     => __('This field is required', 'epasscard'),
            'epasscardPluginUrl' => plugins_url('', __DIR__),
            'organization_id'    => $organization_id,
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
