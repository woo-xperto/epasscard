<?php
class Epasscard_Admin
{
    /**
     * Constructor
     */
    public function __construct()
    {

        new Epasscard_Menu();
        // Load admin AJAX handlers
        new Epasscard_Ajax();

    }
}
