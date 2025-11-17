<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Epasscard
{
    /**
     * Initialize the plugin
     */
    public function Epasscard_init()
    {
        if (is_admin()) {
            // Load assets
            new Epasscard_Load_Assets();

            // Load admin functionality
            new Epasscard_Admin();

            // Load contents in admin footer
            new Epasscard_Admin_Footer();
        }
    }

}


