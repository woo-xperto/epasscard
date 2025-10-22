<?php
class Epasscard
{
    /**
     * Initialize the plugin
     */
    public function init()
    {
        if (is_admin()) {
            // Load assets
            new Epasscard_Load_Assets();

            // Load admin functionality
            new Epasscard_Admin();

            // Load contents in admin footer
            new EpasscardAdminFooter();
        }

        // Load text domain
        //load_plugin_textdomain('epasscard', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Activation hook
     */
    public static function activate()
    {
        // Activation code if needed
    }

    /**
     * Deactivation hook
     */
    public static function deactivate()
    {
        // Deactivation code if needed
    }
}