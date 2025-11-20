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
            <?php if (! empty($epasscard_auxiliary_info) && is_array( $epasscard_auxiliary_info)) {
                    $epasscard_index = 0;
                    foreach ( $epasscard_auxiliary_info as $epasscard_field) { ?>
            <div class="epasscard-field-group" data-id="<?php echo esc_attr($epasscard_index); ?>">
                <label>Label <span>*</span></label>
                <input type="text" class="auxiliary-label" value="<?php echo esc_attr($epasscard_field['label'] ?? ''); ?>">

                <label>Value <span>*</span></label>
                <input type="text" class="auxiliary-value epasscard-watch-input"
                    value="<?php echo esc_attr($epasscard_field['value'] ?? ''); ?>">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="auxiliary-msg" placeholder="Eg. Value changed to {new}"
                    value="<?php echo esc_attr($epasscard_field['changeMsg'] ?? ''); ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php $epasscard_index++;
                    }
                } else {

                for ($epasscard_index = 0; $epasscard_index < 2; $epasscard_index++): ?>
            <div class="epasscard-field-group" data-id="<?php echo esc_attr($epasscard_index); ?>">
                <label>label <span>*</span></label>
                <input type="text" class="auxiliary-label">
                <label>Value <span>*</span></label>
                <input type="text" class="auxiliary-value epasscard-watch-input">

                <label>Change message</label>
                <input type="text" class="auxiliary-msg" placeholder="Model is updated to">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php endfor; ?>
            <?php }?>
        </div>
    </div>
</div>