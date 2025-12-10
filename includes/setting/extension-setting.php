<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$epassc_is_webcartisan_giftcard_active = 0;
 if (class_exists('WODGC_Initialize_Giftcard_Class')) {
   $epassc_is_webcartisan_giftcard_active = 1;
   $epassc_gift_card_wooxperto_llc_url = "";
 }else{ 
    $epassc_gift_card_wooxperto_llc_url = admin_url('plugin-install.php?s=gift-card-wooxperto-llc&tab=search&type=term');
 }

 $epassc_is_webcartisan_giftcard_extension_active = 0;
 if (class_exists('EGE_Giftcard_Extension')) {
   $epassc_is_webcartisan_giftcard_extension_active = 1;
   $epassc_gift_card_wooxperto_llc_extension_url = "";
 }else{ 
    $epassc_gift_card_wooxperto_llc_extension_url = admin_url('plugin-install.php?s=epasscard-giftcard-extension&tab=search&type=term');
 }

// Check is Yith gift card active
$epassc_is_webcartisan_yith_giftcard_active = 0;
 if (class_exists('YITH_YWGC_Gift_Card')) {
  $epassc_is_webcartisan_yith_giftcard_active = 1;
   $epassc_yith_woocommerce_gift_cards_url = "";
 }else{ 
    $epassc_yith_woocommerce_gift_cards_url = admin_url('plugin-install.php?s=yith-woocommerce-gift-cards&tab=search&type=term');
 }

$epassc_is_webcartisan_yith_extension_active = 0;
 if (class_exists('EYE_Giftcard_Extension')) {
   $epassc_is_webcartisan_yith_extension_active = 1;
   $epassc_yith_woocommerce_gift_cards_extension_url = "";
 }else{ 
    $epassc_yith_woocommerce_gift_cards_extension_url = admin_url('plugin-install.php?s=epasscard-yith-extension&tab=search&type=term');
 }

 // Check is Ultimate gift card active
$epassc_is_webcartisan_ultimate_giftcard_active = 0;
 if (class_exists('Woocommerce_Gift_Cards_Lite')) {
  $epassc_is_webcartisan_ultimate_giftcard_active = 1;
   $epassc_ultimate_giftcard_url = "";
 }else{ 
    $epassc_ultimate_giftcard_url = admin_url('plugin-install.php?s=woo-gift-cards-lite&tab=search&type=term');
 }

$epassc_is_webcartisan_ultimate_extension_active = 0;
 if (class_exists('GCLE_Gift_Cards_Lite_Extension')) {
   $epassc_is_webcartisan_ultimate_extension_active = 1;
   $epassc_ultimate_giftcard_extension_url = "";
 }else{ 
    $epassc_ultimate_giftcard_extension_url = admin_url('plugin-install.php?s=epasscard-woo-gift-cards-lite-extension&tab=search&type=term');
 }

 
?>
<div class="wrap">
    <h2>Setup Extensions:</h2>
    <div class="epasscard-extension">
        <div class="extension-item">
            <h3>Gift Card for WooCommerce</h3>
            <?php if($epassc_is_webcartisan_giftcard_active == 1) { 
                if($epassc_is_webcartisan_giftcard_extension_active == 1) {?>
                    <button class="extension-button epass-button-disabled" disabled>Activated</button>
                <?php }else{ ?>
                    <a href="<?php echo esc_url($epassc_gift_card_wooxperto_llc_extension_url); ?>" class="epass-button-install">Get Extension</a>
                <?php } ?>
               
            <?php }else{ ?>
                <button class="extension-button epass-button-disabled" disabled>Get</button>
                <div>
                    <a href="<?php echo esc_url($epassc_gift_card_wooxperto_llc_url); ?>" class="epass-button-install">Please Install Gift Card for WooCommerce</a>
                 </div>
            <?php }?>  
        </div>
        <!-- Yith Gift card Setting -->
        <div class="extension-item">
            <h3>YITH WooCommerce Gift Cards</h3>
            <?php if($epassc_is_webcartisan_yith_giftcard_active == 1) { 
                if($epassc_is_webcartisan_yith_extension_active == 1) {?>
                    <button class="extension-button epass-button-disabled" disabled>Activated</button>
                <?php }else{ ?>
                   <a href="<?php echo esc_url($epassc_yith_woocommerce_gift_cards_extension_url); ?>" class="epass-button-install">Get Extension</a>
                <?php } ?>
               
            <?php }else{ ?>
                <button class="extension-button epass-button-disabled" disabled>Get</button>
                <div>
                    <a href="<?php echo esc_url($epassc_yith_woocommerce_gift_cards_url); ?>" class="epass-button-install">Please Install YITH WooCommerce Gift Cards<span class="epasscard-spinner"></span></a>
                 </div>
            <?php }?>  
        </div>
        
        <!-- Ultimate Gift Cards For WooCommerce Setting -->
        <div class="extension-item">
            <h3>Ultimate Gift Cards For WooCommerce</h3>
            <?php if($epassc_is_webcartisan_ultimate_giftcard_active == 1) { 
                if($epassc_is_webcartisan_ultimate_extension_active == 1) {?>
                    <button class="extension-button epass-button-disabled" disabled>Activated</button>
                <?php }else{ ?> 
                   <a href="<?php echo esc_url($epassc_ultimate_giftcard_extension_url); ?>" class="epass-button-install">Get Extension</a>
                <?php } ?>
               
            <?php }else{ ?>
                <button class="extension-button epass-button-disabled" disabled>Get</button>
                <div>
                    <a href="<?php echo esc_url($epassc_ultimate_giftcard_url); ?>" class="epass-button-install">Please Install Ultimate Gift Cards For WooCommerce</a>
                 </div>
            <?php }?>  
        </div>

    </div>
</div>