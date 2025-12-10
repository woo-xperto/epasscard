<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="mobile-edit-field epasscard-barcode-fields epasscard-hidden">
    <div class="epasscard-field-group">
        <h3>Barcode Setting</h3> 

        <label>Select Barcode format <span>*</span></label>
        <select class="scanner-code">
            <option value="" disabled
                <?php echo(isset($epassc_barcode_info['format']) && $epassc_barcode_info['format'] === '') ? 'selected' : ''; ?>>Please
                select one</option>
            <option value="qrCode"
                <?php echo(isset($epassc_barcode_info['format']) && $epassc_barcode_info['format'] === 'qrCode') ? 'selected' : ''; ?>>
                QR Code</option>
            <option value="CODE_128"
                <?php echo(isset($epassc_barcode_info['format']) && $epassc_barcode_info['format'] === 'CODE_128') ? 'selected' : ''; ?>>
                Code 128</option>
            <option value="PDF_417"
                <?php echo(isset($epassc_barcode_info['format']) && $epassc_barcode_info['format'] === 'PDF_417') ? 'selected' : ''; ?>>
                PDF 417</option>
            <option value="AZTEC"
                <?php echo(isset($epassc_barcode_info['format']) && $epassc_barcode_info['format'] === 'AZTEC') ? 'selected' : ''; ?>>
                AZTEC</option>
        </select>

        <label>Barcode Value <span>*</span></label>
        <select class="barcode-value">
            <option value="" disabled
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === '') ? 'selected' : ''; ?>>Please
                select one</option>
            <option value="template_name"
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === 'template_name') ? 'selected' : ''; ?>>
                Template name</option>
            <option value="organization_name"
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === 'organization_name') ? 'selected' : ''; ?>>
                Organization name</option>
            <option value="description"
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === 'description') ? 'selected' : ''; ?>>
                Description</option>
            <option value="systemId"
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === 'systemId') ? 'selected' : ''; ?>>
                System generated ID</option>
            <option value="{Product name}"
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === '{Product name}') ? 'selected' : ''; ?>>
                Product name</option>
            <option value="{Model}"
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === '{Model}') ? 'selected' : ''; ?>>
                Model</option>
            <option value="{Purchase Date}"
                <?php echo(isset($epassc_barcode_info['value']) && $epassc_barcode_info['value'] === '{Purchase Date}') ? 'selected' : ''; ?>>
                Purchase Date</option>
        </select>

        <div class="setting-group">
            <div class="toggle-label">
                <input type="checkbox" id="barcode-expire"
                    <?php echo(isset($epassc_barcode_info['showValue']) && $epassc_barcode_info['showValue']) ? 'checked' : ''; ?>>
                <span>Show barcode value</span>
            </div>
        </div>

        <label>Alternate Text : (this will show with value)</label>
        <input type="text" class="barcode-alternate-text" placeholder="Enter alternate text for barcode"
            value="<?php echo isset($epassc_barcode_info['altText']) ? esc_attr($epassc_barcode_info['altText']) : ''; ?>">
    </div>
</div>