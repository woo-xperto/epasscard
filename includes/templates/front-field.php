<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard_front_field">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">

        <!-- Mobile view editable fields -->
        <div class="epasscard-mobile-edit-content">
            <!-- Header fields -->
            <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-edit-templates/header-fields.php'; ?>
            <!-- Primary fields -->
            <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-edit-templates/primary-fields.php'; ?>
            <!-- Secondary fields -->
            <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-edit-templates/secondary-fields.php'; ?>
            <!-- Barcode setting fields -->
            <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-edit-templates/barcode-settings.php'; ?>
        </div>
    </div>
</div>