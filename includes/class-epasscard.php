<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
class Epasscard
{
    /**
     * Initialize the plugin
     */
    public function epassc_init()
    {
        if (is_admin()) {
            // Load assets
            new EPASSC_Assets();

            // Load admin functionality
            new EPASSC_Admin();

            // Load contents in admin footer
            new EPASSC_Admin_Footer();
        }


        //Corn schedule check
        add_action('init', [$this, 'epasscard_corn_event_schedule_check']);

        // Refresh for new API key
        $epassc_ajax = new EPASSC_Ajax();
        add_action('epassc_refresh_event', [$epassc_ajax, 'epassc_refresh_api_key']);


    }



    // Hook into init to check if the event is scheduled 
    public function epasscard_corn_event_schedule_check()
    {

        // Check if the event is already scheduled for API key
        if (!wp_next_scheduled('epassc_refresh_event')) {
            // Get the stored refresh time
            $next_refresh = get_option('epass_next_refresh');

            //Get next refresh calculated time
            $epassc_ajax = new EPASSC_Ajax();
            $next_refresh_time = $epassc_ajax->epassc_calculate_api_key_next_refresh_time($next_refresh);

            if (!empty($next_refresh)) {
                // Schedule the event again if missing
                wp_schedule_single_event($next_refresh_time, 'epassc_refresh_event');
            }
        }

    }

}
