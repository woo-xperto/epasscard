<?php
$is_webcartisan_giftcard_active = 0;
 if (class_exists('WODGC_Initialize_Giftcard_Class')) {
   $is_webcartisan_giftcard_active = 1;
 }

 $is_webcartisan_giftcard_extension_active = 0;
 if (class_exists('Epasscard_Giftcard_Extension')) {
   $is_webcartisan_giftcard_extension_active = 1;
 }
?>
<div class="wrap">
    <h2>Setup Extensions:</h2>
    <div class="epasscard-extension">
        <div class="extension-item">
            <h3>Gift Card</h3>
            <?php if($is_webcartisan_giftcard_active == 1) { 
                if($is_webcartisan_giftcard_extension_active == 1) {?>
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
    </div>
</div>