<div id="back_fields">
    <div class="left">
        <div class="mobile-preview mobile_portrait back-fields">
            <div>
                <div class="phone-pass-preview">
                    <img class="phone"
                        src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/images/mobile_portrait.png'); ?>"
                        alt="Back Preview">
                    <div class="pass-inner">
                        <div class="coupon-container">
                            <div class="coupon-item">
                                <img
                                    src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/images/backfieldSwitch.jpg'); ?>">
                            </div>
                            <div class="spacer"></div>
                            <div class="coupon-bottom">
                                <?php if (isset($backFieldsInfo) && ! empty($backFieldsInfo)) {
                                        $index         = 0;
                                        $baseTimestamp = (int) round(microtime(true) * 1000);
                                        foreach ($backFieldsInfo as $field) {
                                        $timestamp = $baseTimestamp + $index; ?>
                                <div class="coupon-field-display" data-id="<?php echo esc_attr($timestamp); ?>">
                                    <div class="display-name">
                                        <?php echo isset($field['label']) ? esc_html($field['label']) : ''; ?></div>
                                    <div class="display-value">
                                        <?php echo isset($field['label']) ? esc_html($field['value']) : ''; ?></div>
                                </div>
                                <?php $index++;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right">
        <h3>Back Fields</h3>
        <div class="description">
            These fields appear on the back of the pass and can include additional information.
        </div>
        <button class="add-field-btn epasscard-primary-btn">Add Field</button>
        <div class="fields-container">
            <!-- Default field group -->
            <?php if (isset($backFieldsInfo) && ! empty($backFieldsInfo)) {
                    $index         = 0;
                    $baseTimestamp = (int) round(microtime(true) * 1000);
                    foreach ($backFieldsInfo as $field) {
                        $timestamp = $baseTimestamp + $index;
                    ?>
            <div class="epasscard-field-group back-fields-wrap" data-id="<?php echo esc_attr($timestamp); ?>">
                <label>Label (Name) <span>*</span></label>
                <input type="text" class="field-name" placeholder="Eg. Name"
                    value="<?php echo isset($field['label']) ? esc_attr($field['label']) : ''; ?>">

                <label>Value <span>*</span></label>
                <textarea class="field-value epasscard-watch-input" rows="4" cols="100%"
                    placeholder="Write your message here..."><?php echo isset($field['value']) ? esc_html($field['value']) : ''; ?></textarea>

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="change-message" placeholder="eg. Name changed to {name}"
                    value="<?php echo isset($field['changeMsg']) ? esc_attr($field['changeMsg']) : ''; ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php $index++;
                    }
            }?>
        </div>
    </div>
</div>