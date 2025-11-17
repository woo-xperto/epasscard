<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
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
                                <?php if (isset($epasscard_back_fields_info) && ! empty($epasscard_back_fields_info)) {
                                        $epasscard_index         = 0;
                                        $epasscard_base_time_stamp = (int) round(microtime(true) * 1000);
                                        foreach ($epasscard_back_fields_info as $epasscard_field) {
                                        $epasscard_timestamp = $epasscard_base_time_stamp + $epasscard_index; ?>
                                <div class="coupon-field-display" data-id="<?php echo esc_attr($epasscard_timestamp); ?>">
                                    <div class="display-name">
                                        <?php echo isset($epasscard_field['label']) ? esc_html($epasscard_field['label']) : ''; ?></div>
                                    <div class="display-value">
                                        <?php echo isset($epasscard_field['label']) ? esc_html($epasscard_field['value']) : ''; ?></div>
                                </div>
                                <?php $epasscard_index++;
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
            <?php if (isset($epasscard_back_fields_info) && ! empty($epasscard_back_fields_info)) {
                    $epasscard_index         = 0;
                    $epasscard_base_time_stamp = (int) round(microtime(true) * 1000);
                    foreach ($epasscard_back_fields_info as $epasscard_field) {
                        $epasscard_timestamp = $epasscard_base_time_stamp + $epasscard_index;
                    ?>
            <div class="epasscard-field-group back-fields-wrap" data-id="<?php echo esc_attr($epasscard_timestamp); ?>">
                <label>Label (Name) <span>*</span></label>
                <input type="text" class="field-name" placeholder="Eg. Name"
                    value="<?php echo isset($epasscard_field['label']) ? esc_attr($epasscard_field['label']) : ''; ?>">

                <label>Value <span>*</span></label>
                <textarea class="field-value epasscard-watch-input" rows="4" cols="100%"
                    placeholder="Write your message here..."><?php echo isset($epasscard_field['value']) ? esc_html($epasscard_field['value']) : ''; ?></textarea>

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="change-message" placeholder="eg. Name changed to {name}"
                    value="<?php echo isset($epasscard_field['changeMsg']) ? esc_attr($epasscard_field['changeMsg']) : ''; ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php $epasscard_index++;
                    }
            }?>
        </div>
    </div>
</div>