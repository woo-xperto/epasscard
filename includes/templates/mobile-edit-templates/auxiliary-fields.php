<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="mobile-edit-field epasscard-auxiliary-fields epasscard-hidden">
    <div class="">
        <h3>Auxiliary Fields</h3>
        <div class="description">
            This represents the fields that display information at the pass.
        </div>
        <button class="add-auxiliary-field-btn epasscard-primary-btn">Add Field</button>
        <div class="fields-container">
            <!-- One default field group -->
            <?php if (! empty($epassc_auxiliary_info) && is_array( $epassc_auxiliary_info)) {
                    $epassc_index = 0;
                    foreach ( $epassc_auxiliary_info as $epassc_field) { ?>
            <div class="epasscard-field-group" data-id="<?php echo esc_attr($epassc_index); ?>">
                <label>Label <span>*</span></label>
                <input type="text" class="auxiliary-label" value="<?php echo esc_attr($epassc_field['label'] ?? ''); ?>">

                <label>Value <span>*</span></label>
                <input type="text" class="auxiliary-value epasscard-watch-input"
                    value="<?php echo esc_attr($epassc_field['value'] ?? ''); ?>">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="auxiliary-msg" placeholder="Eg. Value changed to {new}"
                    value="<?php echo esc_attr($epassc_field['changeMsg'] ?? ''); ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php $epassc_index++;
                    }
                } ?>
        </div>
    </div>
</div>