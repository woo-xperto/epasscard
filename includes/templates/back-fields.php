<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="back_fields">
    <div class="left">
        <div class="mobile-preview mobile_portrait back-fields">
            <div>
                <div class="phone-pass-preview">
                    <img class="phone"
                        src="<?php echo esc_url(EPASSC_PLUGIN_URL . 'assets/img/images/mobile_portrait.png'); ?>"
                        alt="Back Preview">
                    <div class="pass-inner">
                        <div class="coupon-container">
                            <div class="coupon-item">
                                <img
                                    src="<?php echo esc_url(EPASSC_PLUGIN_URL . 'assets/img/images/backfieldSwitch.jpg'); ?>">
                            </div>
                            <div class="spacer"></div>
                            <div class="coupon-bottom">
                                <?php if (isset($epassc_back_fields_info) && ! empty($epassc_back_fields_info)) {
                                        $epassc_index         = 0;
                                        $epassc_base_time_stamp = (int) round(microtime(true) * 1000);
                                        foreach ($epassc_back_fields_info as $epassc_field) {
                                        $epassc_timestamp = $epassc_base_time_stamp + $epassc_index; ?>
                                <div class="coupon-field-display" data-id="<?php echo esc_attr($epassc_timestamp); ?>">
                                    <div class="display-name">
                                        <?php echo isset($epassc_field['label']) ? esc_html($epassc_field['label']) : ''; ?></div>
                                    <div class="display-value">
                                        <?php echo isset($epassc_field['label']) ? esc_html($epassc_field['value']) : ''; ?></div>
                                </div>
                                <?php $epassc_index++;
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
            <?php if (isset($epassc_back_fields_info) && ! empty($epassc_back_fields_info)) {
                    $epassc_index         = 0;
                    $epassc_base_time_stamp = (int) round(microtime(true) * 1000);
                    foreach ($epassc_back_fields_info as $epassc_field) {
                        $epassc_timestamp = $epassc_base_time_stamp + $epassc_index;
                    ?>
            <div class="epasscard-field-group back-fields-wrap" data-id="<?php echo esc_attr($epassc_timestamp); ?>">
                <label>Label (Name) <span>*</span></label>
                <input type="text" class="field-name" placeholder="Eg. Name"
                    value="<?php echo isset($epassc_field['label']) ? esc_attr($epassc_field['label']) : ''; ?>">

                <label>Value <span>*</span></label>
                <textarea class="field-value epasscard-watch-input" rows="4" cols="100%"
                    placeholder="Write your message here..."><?php echo isset($epassc_field['value']) ? esc_html($epassc_field['value']) : ''; ?></textarea>

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="change-message" placeholder="eg. Name changed to {name}"
                    value="<?php echo isset($epassc_field['changeMsg']) ? esc_attr($epassc_field['changeMsg']) : ''; ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php $epassc_index++;
                    }
            }?>
        </div>
    </div>
</div>