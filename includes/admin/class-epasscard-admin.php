<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class EPASSC_Admin
{
    /**
     * Constructor
     */
    public function __construct()
    {

        new EPASSC_Menu();
        // Load admin AJAX handlers
        new EPASSC_Ajax();

    }
}
