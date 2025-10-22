<?php ?>
<div class="mobile-edit-field epasscard-secondary-fields epasscard-hidden">
    <div class="">
        <h3>Secondary Fields</h3>
        <div class="description">
            This represents the fields that display information at the pass.
        </div>
        <button class="add-secondary-field-btn epasscard-primary-btn">Add Field</button>
        <div class="fields-container">
            <!-- One default field group -->
            <?php if (! empty($secondaryInfo) && is_array($secondaryInfo)) {
                    $index = 0;
                    //$baseTimestamp = (int) round(microtime(true) * 1000);
                    foreach ($secondaryInfo as $field) {
                    //$timestamp = $baseTimestamp + $index; ?>
            <div class="epasscard-field-group" data-id="<?php echo esc_attr($index); ?>">
                <label>Label <span>*</span></label>
                <input type="text" class="secondary-label" value="<?php echo esc_attr($field['label'] ?? ''); ?>">

                <label>Value <span>*</span></label>
                <input type="text" class="secondary-value epasscard-watch-input"
                    value="<?php echo esc_attr($field['value'] ?? ''); ?>">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="change-message-secondary" placeholder="Eg. Value changed to {new}"
                    value="<?php echo esc_attr($field['changeMsg'] ?? ''); ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php $index++;
                    }
                } else {

                for ($index = 0; $index < 2; $index++): ?>
            <div class="epasscard-field-group" data-id="<?php echo esc_attr($index); ?>">
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