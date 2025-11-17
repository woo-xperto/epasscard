<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="mobile-edit-field epasscard-header-fields">
    <div class="">
        <h3>Header Fields</h3>
        <div class="description">
            This represents the fields that display information at the pass.
        </div>
        <button class="add-header-field-btn epasscard-primary-btn">Add Field</button>
        <div class="fields-container">
            <!-- One default field group -->
            <?php if (! empty($epasscard_header_info)) {
                foreach ($epasscard_header_info as $epasscard_header) {?>
            <div class="epasscard-field-group">
                <label>Label <span>*</span></label>
                <input type="text" class="header-label-name" value="<?php echo esc_attr($epasscard_header['label'] ?? ''); ?>">
                <label>Value <span>*</span></label>
                <input type="text" class="product-name-label epasscard-watch-input"
                    value="<?php echo esc_attr($epasscard_header['value'] ?? ''); ?>">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="change-message-header" placeholder="Eg. Name changed to {name}"
                    value="<?php echo esc_attr($epasscard_header['changeMsg'] ?? ''); ?>">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php }
            } else {?>
            <div class="epasscard-field-group">
                <label>label <span>*</span></label>
                <input type="text" class="header-label-name">

                <label>Value <span>*</span></label>
                <input type="text" class="product-name-label epasscard-watch-input" value="{Product name}">

                <label>Change message (This message will be displayed when value is changed)</label>
                <input type="text" class="change-message-header" placeholder="Eg. Name changed to {name}">

                <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
            </div>

            <?php }?>
        </div>
    </div>
</div>