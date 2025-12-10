<?php if (!defined('ABSPATH'))
    exit; // Exit if accessed directly 
 $epassc_display_mode = isset($epassc_card_type) && ($epassc_card_type == "Generic pass" || $epassc_card_type == "Generic") ? "none": "flex" ;
 $epassc_qrcode_margin = isset($epassc_card_type) && ($epassc_card_type == "Generic pass" || $epassc_card_type == "Generic") ? "10px": "60px";
 if(isset($epassc_card_type) && ($epassc_card_type == "Generic pass" || $epassc_card_type == "Generic")){
    $epassc_display_mode_all_cards = "flex";
    $epassc_bg_image = isset($epassc_images_info['thumbnail']) ? $epassc_images_info['thumbnail'] : '';
 }else if(isset($epassc_card_type) && ($epassc_card_type != "Generic pass" || $epassc_card_type != "Generic") && !empty($epassc_card_type)){
    $epassc_display_mode_all_cards = "none";
 }else{
    $epassc_display_mode_all_cards = "";
 }
 
 ?>
<div class="mobile-preview mobile_portrait relative">
    <div>
        <div class="phone-pass-preview" id="phone-pass-preview">
            <img class="phone-img" id="phone"
                src="<?php echo esc_url(EPASSC_PLUGIN_URL . 'assets/img/images/mobile_portrait.png'); ?>"
                alt="image">
            <div id="pass-inner" class="coupon"
                style="background-color:<?php echo esc_attr($epassc_bg_color ?? ''); ?>;">
                <div class="coupon-container">
                    <div>
                        <div class="coupon-header">
                            <div class="header-logo">
                                <img class="logo-img"
                                    src="<?php echo esc_attr($epassc_logo ?? EPASSC_PLUGIN_URL . 'assets/img/card-images/warrenty-logo.png'); ?>">
                            </div>
                            <div class="header-content">
                                <?php if (!empty($epassc_header_info)) {
                                    foreach ($epassc_header_info as $epassc_header) { ?>
                                        <div class="content-text" data-field-id="default-field">
                                            <p class="product-name"
                                                style="color:<?php echo esc_attr($epassc_label_color ?? ''); ?>">
                                                <?php echo esc_html($epassc_header['label'] ?? ''); ?>
                                            </p>
                                            <p class="product-value"
                                                style="color:<?php echo esc_attr($epassc_fg_color ?? ''); ?>">
                                                <?php echo esc_html($epassc_header['value'] ?? ''); ?>
                                            </p>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="content-text" data-field-id="default-field">
                                        <p class="product-name">Product name</p>
                                        <p class="product-value">{Product name}</p>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn mobile-preview-active-btn"
                                    data-epasscard-edit="header-fields">Edit</button>
                            </div>
                        </div>
                        <!-- Banner Image -->
                        <div class="coupon-strip"
                            style="background-image: url(<?php echo esc_url($epassc_bg_image ?? EPASSC_PLUGIN_URL . 'assets/img/card-images/warrenty-strip.png'); ?>); display:<?php echo esc_attr($epassc_display_mode); ?>">
                            <div class="strip-content">
                                <p class="strip-text">
                                    <?php echo isset($epassc_primary_label) ? esc_html($epassc_primary_label) : ''; ?>
                                </p>
                                <p class="strip-text-uppercase">
                                    <?php echo isset($epassc_primary_value) ? esc_html($epassc_primary_value) : ''; ?>
                                </p>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn" data-epasscard-edit="primary-fields">Edit</button>
                            </div>
                        </div>

                        <!-- Banner Image for card Generic pass-->
                        <div class="epc-mobile-hero-section" style="display:<?php echo esc_attr($epassc_display_mode_all_cards); ?>">
                            <div class="epc-mobile-hero">
                                <div class="epc-text-block">
                                    <p class="epc-primary-label"><?php echo isset($epassc_primary_label) ? esc_html($epassc_primary_label) : ''; ?></p>
                                    <p class="epc-primary-value"><?php echo isset($epassc_primary_value) ? esc_html($epassc_primary_value) : ''; ?></p>
                                </div>

                                <div class="epc-image-block">
                                    <img src="<?php echo esc_url($epassc_bg_image ?? EPASSC_PLUGIN_URL . 'assets/img/card-images/warrenty-strip.png'); ?>">
                                </div>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn" data-epasscard-edit="primary-fields">Edit</button>
                            </div>
                        </div>

                        <div class="coupon-image" style="display: none;">
                            <div class="image-container">
                                <div class="image-wrapper">
                                    <img src="" class="product-image">
                                </div>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn">Edit</button>
                            </div>
                        </div>
                        <div class="coupon-details">
                            <div class="details-container secondary-items-container">
                                <?php if (!empty($epassc_secondary_info) && is_array($epassc_secondary_info)) {
                                    $epassc_index = 0;
                                    foreach ($epassc_secondary_info as $epassc_field) { ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epassc_index); ?>">
                                            <p class="detail-label"
                                                style="color:<?php echo esc_attr($epassc_label_color ?? ''); ?>">
                                                <?php echo esc_html($epassc_field['label'] ?? ''); ?>
                                            </p>
                                            <p class="detail-value"
                                                style="color:<?php echo esc_attr($epassc_fg_color ?? ''); ?>">
                                                <?php echo esc_html($epassc_field['value'] ?? ''); ?>
                                            </p>
                                        </div>
                                        <?php $epassc_index++;
                                    }
                                } else {

                                    for ($epassc_index = 0; $epassc_index < 2; $epassc_index++): ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epassc_index); ?>">
                                            <p class="detail-label"></p>
                                            <p class="detail-value"></p>
                                        </div>
                                    <?php endfor; ?>
                                <?php } ?>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn" data-epasscard-edit="secondary-fields">Edit</button>
                            </div>
                        </div>

                        <!-- Auxiliary fields -->
                        <div class="coupon-details auxiliary-items-wrap" style="display:<?php echo esc_attr($epassc_display_mode_all_cards); ?>">
                            <div class="details-container auxiliary-items-container">
                                <?php if (!empty($epassc_auxiliary_info) && is_array($epassc_auxiliary_info)) {
                                    $epassc_index = 0;
                                    foreach ($epassc_auxiliary_info as $epassc_field) { ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epassc_index); ?>">
                                            <p class="detail-label"
                                                style="color:<?php echo esc_attr($epassc_label_color ?? ''); ?>">
                                                <?php echo esc_html($epassc_field['label'] ?? ''); ?>
                                            </p>
                                            <p class="detail-value"
                                                style="color:<?php echo esc_attr($epassc_fg_color ?? ''); ?>">
                                                <?php echo esc_html($epassc_field['value'] ?? ''); ?>
                                            </p>
                                        </div>
                                        <?php $epassc_index++;
                                    }
                                } else {

                                    for ($epassc_index = 0; $epassc_index < 2; $epassc_index++): ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epassc_index); ?>">
                                            <p class="detail-label"></p>
                                            <p class="detail-value"></p>
                                        </div>
                                    <?php endfor; ?>
                                <?php } ?>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn" data-epasscard-edit="auxiliary-fields">Edit</button>
                            </div>
                        </div>

                        <div class="coupon-qrcode" style="margin-top:<?php echo esc_attr($epassc_qrcode_margin); ?>">
                            <div class="qrcode-container">
                                <?php if (isset($epassc_barcode_info['format'])) {
                                    $epassc_barcode_type = $epassc_barcode_info['format'];
                                    if ($epassc_barcode_type === 'qrCode') {
                                        $epassc_barcode_img = EPASSC_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                    } elseif ($epassc_barcode_type === 'CODE_128') {
                                        $epassc_barcode_img = EPASSC_PLUGIN_URL . 'assets/img/images/barcode.png';
                                    } elseif ($epassc_barcode_type === 'PDF_417') {
                                        $epassc_barcode_img = EPASSC_PLUGIN_URL . 'assets/img/images/barcode_pdf417.png';
                                    } elseif ($epassc_barcode_type === 'AZTEC') {
                                        $epassc_barcode_img = EPASSC_PLUGIN_URL . 'assets/img/images/aztec.png';
                                    } else {
                                        $epassc_barcode_img = EPASSC_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                    }
                                } else {
                                    $epassc_barcode_img = EPASSC_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                }
                                ?>
                                <img class="qrcode-img" src="<?php echo esc_url($epassc_barcode_img); ?>">
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn" data-epasscard-edit="barcode-fields">Edit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>