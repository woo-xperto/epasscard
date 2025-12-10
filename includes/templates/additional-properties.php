<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard_additional_properties" class="epasscard_additional_properties">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSC_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">
        <div class="info-text">
            Additional properties give you a way to store personalised data. You can but you don't have to show this
            data on the pass. E.g. you can ask for the users Email address and have it available later without showing
            it on the pass. All additional properties can be inserted by using them as placeholder anywhere on your pass
            (labels, change messages, field values, barcode etc.). E.g. if your additional property has the Name "First
            name", you can insert the first name in another field by typing <code>{First name}</code>. On pass creation,
            the system will automatically replace the placeholder with the user's data.
        </div>

        <button class="add-field-btn epasscard-primary-btn">Add Field</button>

        <div class="fields-container">
            <!-- Dynamic field blocks will be added here -->
            <?php if (! empty($epassc_additional_fields)) {
                foreach ($epassc_additional_fields as $epassc_field) {?>
            <div class="field-block epasscard-field-group additional-block"
                additional-id="<?php echo isset($epassc_field['id']) ? esc_attr($epassc_field['id']) : ''; ?>">
                <label>Name</label>
                <input type="text" class="field-name-input additional-field-name" placeholder="Eg. Field name"
                    value="<?php echo isset($epassc_field['field_name']) ? esc_attr($epassc_field['field_name']) : ''; ?>">

                <div id="field-type-container">
                    <div>
                        <label>Field Type</label>
                        <select class="field-type-select">
                            <option value="text"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'text') ? 'selected' : ''; ?>>
                                Text</option>
                            <option value="textarea"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'textarea') ? 'selected' : ''; ?>>
                                Textarea</option>
                            <option value="file"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'file') ? 'selected' : ''; ?>>
                                File</option>
                            <option value="datetime-local"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'datetime-local') ? 'selected' : ''; ?>>
                                Date Time</option>
                            <option value="date"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'date') ? 'selected' : ''; ?>>
                                Date</option>
                            <option value="number"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'number') ? 'selected' : ''; ?>>
                                Number</option>
                            <option value="email"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'email') ? 'selected' : ''; ?>>
                                Email</option>
                            <option value="randomUniqueCode"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'randomUniqueCode') ? 'selected' : ''; ?>>
                                Random unique code</option>
                            <option value="color"
                                <?php echo(isset($epassc_field['field_type']) && $epassc_field['field_type'] === 'color') ? 'selected' : ''; ?>>
                                Color</option>
                        </select>
                    </div>
                    <input type="text" class="addition-uid" value="<?php echo esc_attr($epassc_field['uid']); ?>" />
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="addition-required-<?php echo esc_attr($epassc_field['uid']); ?>"
                        <?php echo(isset($epassc_field['required']) && $epassc_field['required']) ? 'checked' : ''; ?>>
                    <label for="addition-required-<?php echo esc_attr($epassc_field['uid']); ?>">Is This Field
                        Required?</label>
                </div>

                <button type="button" class="remove-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php }
                } else if(!isset($template_id)) {
                for ($epassc_index = 0; $epassc_index < 3; $epassc_index++): ?>
            <div class="field-block epasscard-field-group">
                <label>Name</label>
                <input type="text" class="additional-field-name">
                <label>Field Type</label>
                <select>
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                    <option value="file">File</option>
                    <option value="datetime-local">Date Time</option>
                    <option value="date">Date</option>
                    <option value="number">Number</option>
                    <option value="email">Email</option>
                    <option value="randomUniqueCode">Random unique code</option>
                    <option value="color">Color</option>
                </select>
                <div class="checkbox-group">
                    <input type="checkbox" id="required" checked>
                    <label for="required">Is This Field Required?</label>
                </div>
                <button type="button" class="remove-btn epasscard-remove-btn">Remove</button>
            </div>
            <?php endfor;
            }?>
        </div>
    </div>
</div>