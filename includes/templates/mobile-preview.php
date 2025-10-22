<div class="mobile-preview mobile_portrait relative">
    <div>
        <div class="phone-pass-preview" id="phone-pass-preview">
            <img class="phone-img" id="phone"
                src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/images/mobile_portrait.png'); ?>"
                alt="image">
            <div id="pass-inner" class="coupon" style="background-color:<?php echo esc_attr($bgColor ?? ''); ?>;">
                <div class="coupon-container">
                    <div>
                        <div class="coupon-header">
                            <div class="header-logo">
                                <img class="logo-img"
                                    src="<?php echo esc_attr($logo ?? 'https://app.epasscard.com/assets/img/predefined-templates/assets/warrenty-logo.png'); ?>">
                            </div>
                            <div class="header-content">
                                <?php if (! empty($headerInfo)) {
                                    foreach ($headerInfo as $header) {?>
                                <div class="content-text" data-field-id="default-field">
                                    <p class="product-name" style="color:<?php echo esc_attr($labelColor ?? ''); ?>">
                                        <?php echo esc_html($header['label'] ?? ''); ?></p>
                                    <p class="product-value" style="color:<?php echo esc_attr($fgColor ?? ''); ?>">
                                        <?php echo esc_html($header['value'] ?? ''); ?></p>
                                </div>
                                <?php }
                                } else {?>
                                <div class="content-text" data-field-id="default-field">
                                    <p class="product-name">Product name</p>
                                    <p class="product-value">{Product name}</p>
                                </div>
                                <?php }?>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn mobile-preview-active-btn"
                                    data-epasscard-edit="header-fields">Edit</button>
                            </div>
                        </div>
                        <div class="coupon-strip"
                            style="background-image: url(<?php echo esc_url($bgImage ?? 'https://app.epasscard.com/assets/img/predefined-templates/assets/warrenty-strip.png'); ?>);">
                            <div class="strip-content">
                                <p class="strip-text"><?php echo isset($primaryLabel) ? esc_html($primaryLabel) : ''; ?></p>
                                <p class="strip-text-uppercase"><?php echo isset($primaryValue) ? esc_html($primaryValue) : ''; ?></p>
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
                            <div class="details-container">
                                <?php if (! empty($secondaryInfo) && is_array($secondaryInfo)) {
                                        $index = 0;
                                        //$baseTimestamp = (int) round(microtime(true) * 1000);
                                        foreach ($secondaryInfo as $field) {
                                        //$timestamp = $baseTimestamp + $index; ?>
                                <div class="detail-item" data-id="<?php echo esc_attr($index); ?>">
                                    <p class="detail-label" style="color:<?php echo esc_attr($labelColor ?? ''); ?>">
                                        <?php echo esc_html($field['label'] ?? ''); ?>
                                    </p>
                                    <p class="detail-value" style="color:<?php echo esc_attr($fgColor ?? ''); ?>">
                                        <?php echo esc_html($field['value'] ?? ''); ?></p>
                                </div>
                                <?php $index++;
                                        }
                                    } else {

                                    for ($index = 0; $index < 2; $index++): ?>
                                <div class="detail-item" data-id="<?php echo esc_attr($index); ?>">
                                    <p class="detail-label"></p>
                                    <p class="detail-value"></p>
                                </div>
                                <?php endfor; ?>
                                <?php }?>
                            </div>
                            <div class="edit-button">
                                <button class="edit-btn" data-epasscard-edit="secondary-fields">Edit</button>
                            </div>
                        </div>
                        <div class="coupon-qrcode">
                            <div class="qrcode-container">
                                <?php if (isset($barcodeInfo['format'])) {
                                        $barcodeType = $barcodeInfo['format'];
                                        if ($barcodeType === 'qrCode') {
                                            $barcodeImg = EPASSCARD_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                        } elseif ($barcodeType === 'CODE_128') {
                                            $barcodeImg = EPASSCARD_PLUGIN_URL . 'assets/img/images/barcode.png';
                                        } elseif ($barcodeType === 'PDF_417') {
                                            $barcodeImg = EPASSCARD_PLUGIN_URL . 'assets/img/images/barcode_pdf417.png';
                                        } elseif ($barcodeType === 'AZTEC') {
                                            $barcodeImg = EPASSCARD_PLUGIN_URL . 'assets/img/images/aztec.png';
                                        } else {
                                            $barcodeImg = EPASSCARD_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                        }
                                    } else {
                                        $barcodeImg = EPASSCARD_PLUGIN_URL . 'assets/img/images/qrcode.png';
                                    }
                                ?>
                                <img class="qrcode-img" src="<?php echo esc_url($barcodeImg); ?>">
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