<?php
class EpasscardAdminFooter
{

    public function __construct()
    {
        add_action('admin_footer', [$this, 'epasscard_load_in_footer']);
    }

    public function epasscard_load_in_footer()
    {
        /* Load epasscard more cards*/
        require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/admin-modal/more-cards-modal.php';

        /* Load epasscard info modal*/
        require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/admin-modal/info-modal.php';

        require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/admin-modal/cropper-modal.php';
    }

}
