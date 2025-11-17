<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$epasscard_is_webcartisan_giftcard_active = 0;
 if (class_exists('WODGC_Initialize_Giftcard_Class')) {
   $epasscard_is_webcartisan_giftcard_active = 1;
 }

 $epasscard_is_webcartisan_giftcard_extension_active = 0;
 if (class_exists('Epasscard_Giftcard_Extension')) {
   $epasscard_is_webcartisan_giftcard_extension_active = 1;
 }

// Check is Yith gift card active
 $epasscard_is_webcartisan_yith_giftcard_active = 0;
 if (class_exists('YITH_YWGC_Gift_Card')) {
   $epasscard_is_webcartisan_yith_giftcard_active = 1;
 }

$epasscard_is_webcartisan_yith_extension_active = 0;
 if (class_exists('EY_Giftcard_Extension')) {
   $epasscard_is_webcartisan_yith_extension_active = 1;
 }

 // Check is Ultimate gift card active
 $epasscard_is_webcartisan_ultimate_giftcard_active = 0;
 if (class_exists('Woocommerce_Gift_Cards_Lite')) {
   $epasscard_is_webcartisan_ultimate_giftcard_active = 1;
 }

$epasscard_is_webcartisan_ultimate_extension_active = 0;
 if (class_exists('WODGC_Gift_Cards_Lite_Extension')) {
   $epasscard_is_webcartisan_ultimate_extension_active = 1;
 }

 
?>
<div class="wrap">
    <h2>Setup Extensions:</h2>
    <div class="epasscard-extension">
        <div class="extension-item">
            <h3>Gift Card for WooCommerce</h3>
            <?php if($epasscard_is_webcartisan_giftcard_active == 1) { 
                if($epasscard_is_webcartisan_giftcard_extension_active == 1) {?>
                    <button class="extension-button epass-button-disabled" disabled>Activated</button>
                <?php }else{ ?>
                   <button class="extension-button epass-button-install" plugin-type="giftcard_extension">Get<span class="epasscard-spinner"></span></button> 
                <?php } ?>
               
            <?php }else{ ?>
                <button class="extension-button epass-button-disabled" disabled>Get</button>
                <div>
                    <a href="#" class="epass-button-install" plugin-type="giftcard">Please Install Gift Card for WooCommerce<span class="epasscard-spinner"></span></a>
                 </div>
            <?php }?>  
        </div>
        <!-- Yith Gift card Setting -->
        <div class="extension-item">
            <h3>YITH WooCommerce Gift Cards</h3>
            <?php if($epasscard_is_webcartisan_yith_giftcard_active == 1) { 
                if($epasscard_is_webcartisan_yith_extension_active == 1) {?>
                    <button class="extension-button epass-button-disabled" disabled>Activated</button>
                <?php }else{ ?>
                   <button class="extension-button epass-button-install" plugin-type="yith_extension">Get<span class="epasscard-spinner"></span></button> 
                <?php } ?>
               
            <?php }else{ ?>
                <button class="extension-button epass-button-disabled" disabled>Get</button>
                <div>
                    <a href="#" class="epass-button-install" plugin-type="yith_giftcard">Please Install YITH WooCommerce Gift Cards<span class="epasscard-spinner"></span></a>
                 </div>
            <?php }?>  
        </div>
        
        <!-- Ultimate Gift Cards For WooCommerce Setting -->
        <div class="extension-item">
            <h3>Ultimate Gift Cards For WooCommerce</h3>
            <?php if($epasscard_is_webcartisan_ultimate_giftcard_active == 1) { 
                if($epasscard_is_webcartisan_ultimate_extension_active == 1) {?>
                    <button class="extension-button epass-button-disabled" disabled>Activated</button>
                <?php }else{ ?>
                   <button class="extension-button epass-button-install" plugin-type="ultimate_extension">Get<span class="epasscard-spinner"></span></button> 
                <?php } ?>
               
            <?php }else{ ?>
                <button class="extension-button epass-button-disabled" disabled>Get</button>
                <div>
                    <a href="#" class="epass-button-install" plugin-type="ultimate_giftcard">Please Install Ultimate Gift Cards For WooCommerce<span class="epasscard-spinner"></span></a>
                 </div>
            <?php }?>  
        </div>

    </div>
</div>