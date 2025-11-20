<?php if (!defined('ABSPATH'))
    exit; // Exit if accessed directly 
 $epasscard_display_mode = isset($epasscard_card_type) && ($epasscard_card_type == "Generic pass" || $epasscard_card_type == "Generic") ? "none": "flex" ;
 $epasscard_qrcode_margin = isset($epasscard_card_type) && ($epasscard_card_type == "Generic pass" || $epasscard_card_type == "Generic") ? "10px": "60px";
 if(isset($epasscard_card_type) && ($epasscard_card_type == "Generic pass" || $epasscard_card_type == "Generic")){
    $epasscard_display_mode_all_cards = "flex";
 }else if(isset($epasscard_card_type) && ($epasscard_card_type != "Generic pass" || $epasscard_card_type != "Generic") && !empty($epasscard_card_type)){
    $epasscard_display_mode_all_cards = "none";
 }else{
    $epasscard_display_mode_all_cards = "";
 }
 
 ?>
<div class="mobile-preview mobile_portrait relative">
    <div>
        <div class="phone-pass-preview" id="phone-pass-preview">
            <img class="phone-img" id="phone"
                src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/images/mobile_portrait.png'); ?>"
                alt="image">
            <div id="pass-inner" class="coupon"
                style="background-color:<?php echo esc_attr($epasscard_bg_color ?? ''); ?>;">
                <div class="coupon-container">
                    <div>
                        <div class="coupon-header">
                            <div class="header-logo">
                                <img class="logo-img"
                                    src="<?php echo esc_attr($epasscard_logo ?? 'https://app.epasscard.com/assets/img/predefined-templates/assets/warrenty-logo.png'); ?>">
                            </div>
                            <div class="header-content">
                                <?php if (!empty($epasscard_header_info)) {
                                    foreach ($epasscard_header_info as $epasscard_header) { ?>
                                        <div class="content-text" data-field-id="default-field">
                                            <p class="product-name"
                                                style="color:<?php echo esc_attr($epasscard_label_color ?? ''); ?>">
                                                <?php echo esc_html($epasscard_header['label'] ?? ''); ?>
                                            </p>
                                            <p class="product-value"
                                                style="color:<?php echo esc_attr($epasscard_fg_color ?? ''); ?>">
                                                <?php echo esc_html($epasscard_header['value'] ?? ''); ?>
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
                            style="background-image: url(<?php echo esc_url($epasscard_bg_image ?? 'https://app.epasscard.com/assets/img/predefined-templates/assets/warrenty-strip.png'); ?>); display:<?php echo esc_attr($epasscard_display_mode); ?>">
                            <div class="strip-content">
                                <p class="strip-text">
                                    <?php echo isset($epasscard_primary_label) ? esc_html($epasscard_primary_label) : ''; ?>
                                </p>
                                <p class="strip-text-uppercase">
                                    <?php echo isset($epasscard_primary_value) ? esc_html($epasscard_primary_value) : ''; ?>
                                </p>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn" data-epasscard-edit="primary-fields">Edit</button>
                            </div>
                        </div>

                        <!-- Banner Image for card Generic pass-->
                        <div class="epc-mobile-hero-section" style="display:<?php echo esc_attr($epasscard_display_mode_all_cards); ?>">
                            <div class="epc-mobile-hero">
                                <div class="epc-text-block">
                                    <p class="epc-primary-label"><?php echo isset($epasscard_primary_label) ? esc_html($epasscard_primary_label) : ''; ?></p>
                                    <p class="epc-primary-value"><?php echo isset($epasscard_primary_value) ? esc_html($epasscard_primary_value) : ''; ?></p>
                                </div>

                                <div class="epc-image-block">
                                    <img src="<?php echo esc_url($epasscard_bg_image ?? 'https://app.epasscard.com/assets/img/predefined-templates/assets/warrenty-strip.png'); ?>">
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
                                <?php if (!empty($epasscard_secondary_info) && is_array($epasscard_secondary_info)) {
                                    $epasscard_index = 0;
                                    foreach ($epasscard_secondary_info as $epasscard_field) { ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epasscard_index); ?>">
                                            <p class="detail-label"
                                                style="color:<?php echo esc_attr($epasscard_label_color ?? ''); ?>">
                                                <?php echo esc_html($epasscard_field['label'] ?? ''); ?>
                                            </p>
                                            <p class="detail-value"
                                                style="color:<?php echo esc_attr($epasscard_fg_color ?? ''); ?>">
                                                <?php echo esc_html($epasscard_field['value'] ?? ''); ?>
                                            </p>
                                        </div>
                                        <?php $epasscard_index++;
                                    }
                                } else {

                                    for ($epasscard_index = 0; $epasscard_index < 2; $epasscard_index++): ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epasscard_index); ?>">
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
                        <div class="coupon-details auxiliary-items-wrap" style="display:<?php echo esc_attr($epasscard_display_mode_all_cards); ?>">
                            <div class="details-container auxiliary-items-container">
                                <?php if (!empty($epasscard_auxiliary_info) && is_array($epasscard_auxiliary_info)) {
                                    $epasscard_index = 0;
                                    foreach ($epasscard_auxiliary_info as $epasscard_field) { ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epasscard_index); ?>">
                                            <p class="detail-label"
                                                style="color:<?php echo esc_attr($epasscard_label_color ?? ''); ?>">
                                                <?php echo esc_html($epasscard_field['label'] ?? ''); ?>
                                            </p>
                                            <p class="detail-value"
                                                style="color:<?php echo esc_attr($epasscard_fg_color ?? ''); ?>">
                                                <?php echo esc_html($epasscard_field['value'] ?? ''); ?>
                                            </p>
                                        </div>
                                        <?php $epasscard_index++;
                                    }
                                } else {

                                    for ($epasscard_index = 0; $epasscard_index < 2; $epasscard_index++): ?>
                                        <div class="detail-item" data-id="<?php echo esc_attr($epasscard_index); ?>">
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

                        <div class="coupon-qrcode" style="margin-top:<?php echo esc_attr($epasscard_qrcode_margin); ?>">
                            <div class="qrcode-container">
                                <?php if (isset($epasscard_barcode_info['format'])) {
                                    $epasscard_barcode_type = $epasscard_barcode_info['format'];
                                    if ($epasscard_barcode_type === 'qrCode') {
                                        $epasscard_barcode_img = EPASSCARD_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                    } elseif ($epasscard_barcode_type === 'CODE_128') {
                                        $epasscard_barcode_img = EPASSCARD_PLUGIN_URL . 'assets/img/images/barcode.png';
                                    } elseif ($epasscard_barcode_type === 'PDF_417') {
                                        $epasscard_barcode_img = EPASSCARD_PLUGIN_URL . 'assets/img/images/barcode_pdf417.png';
                                    } elseif ($epasscard_barcode_type === 'AZTEC') {
                                        $epasscard_barcode_img = EPASSCARD_PLUGIN_URL . 'assets/img/images/aztec.png';
                                    } else {
                                        $epasscard_barcode_img = EPASSCARD_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                    }
                                } else {
                                    $epasscard_barcode_img = EPASSCARD_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                }
                                ?>
                                <img class="qrcode-img" src="<?php echo esc_url($epasscard_barcode_img); ?>">
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