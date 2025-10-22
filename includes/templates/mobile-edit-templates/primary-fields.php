<?php ?>
<div class="mobile-edit-field epasscard-primary-fields epasscard-hidden">
    <div class="">
        <h3>Primary Fields</h3>
        <div class="description">
            This represents the fields that display information at the pass.
        </div>

        <div class="fields-container">
            <!-- One default field group -->
            <div class="epasscard-field-group">
                <label>label <span>*</span></label>
                <input type="text" class="primary-name" placeholder="Eg. Name"
                    value="<?php echo esc_attr($primaryLabel ?? ''); ?>">

                <label>Value <span>*</span></label>
                <input type="text" class="primary-value epasscard-watch-input"
                    value="<?php echo esc_attr($primaryValue ?? ''); ?>">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="primary-message" placeholder="Eg. Name changed to {name}"
                    value="<?php echo esc_attr($primaryChangeMsg ?? ''); ?>">
            </div>
        </div>
    </div>
</div>