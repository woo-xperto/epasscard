<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
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


        //Corn schedule check
        add_action('init', [$this, 'epasscard_corn_event_schedule_check']);

        // Refresh for new API key
        $epasscard_ajax = new Epasscard_Ajax();
        add_action('epasscard_refresh_event', [$epasscard_ajax, 'epasscard_refresh_api_key']);


    }



    // Hook into init to check if the event is scheduled 
    public function epasscard_corn_event_schedule_check()
    {

        // Check if the event is already scheduled for API key
        if (!wp_next_scheduled('epasscard_refresh_event')) {
            // Get the stored refresh time
            $next_refresh = get_option('epass_next_refresh');

            //Get next refresh calculated time
            $epasscard_ajax = new Epasscard_Ajax();
            $next_refresh_time = $epasscard_ajax->epasscard_calculate_api_key_next_refresh_time($next_refresh);

            if (!empty($next_refresh)) {
                // Schedule the event again if missing
                wp_schedule_single_event($next_refresh_time, 'epasscard_refresh_event');
            }
        }

    }

}
