<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="mobile-edit-field epasscard-secondary-fields epasscard-hidden">
    <div class="">
        <h3>Secondary Fields</h3>
        <div class="description">
            This represents the fields that display information at the pass.
        </div>
        <button class="add-secondary-field-btn epasscard-primary-btn">Add Field</button>
        <div class="fields-container">
            <!-- One default field group -->
            <?php if (! empty($epasscard_secondary_info) && is_array($epasscard_secondary_info)) {
                    $epasscard_index = 0;
                    //$baseTimestamp = (int) round(microtime(true) * 1000);
                    foreach ($epasscard_secondary_info as $epasscard_field) {
                    //$timestamp = $baseTimestamp + $epasscard_index; ?>
            <div class="epasscard-field-group" data-id="<?php echo esc_attr($epasscard_index); ?>">
                <label>Label <span>*</span></label>
                <input type="text" class="secondary-label" value="<?php echo esc_attr($epasscard_field['label'] ?? ''); ?>">

                <label>Value <span>*</span></label>
                <input type="text" class="secondary-value epasscard-watch-input"
                    value="<?php echo esc_attr($epasscard_field['value'] ?? ''); ?>">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="change-message-secondary" placeholder="Eg. Value changed to {new}"
                    value="<?php echo esc_attr($epasscard_field['changeMsg'] ?? ''); ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php $epasscard_index++;
                    }
                } else {

                for ($epasscard_index = 0; $epasscard_index < 2; $epasscard_index++): ?>
            <div class="epasscard-field-group" data-id="<?php echo esc_attr($epasscard_index); ?>">
                <label>label <span>*</span></label>
                <input type="text" class="secondary-label">

                <label>Value <span>*</span></label>
                <input type="text" class="secondary-value epasscard-watch-input">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" placeholder="Model is updated to">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php endfor; ?>
            <?php }?>
        </div>
    </div>
</div>