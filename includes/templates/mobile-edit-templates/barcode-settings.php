<?php ?>
<div class="mobile-edit-field epasscard-barcode-fields epasscard-hidden">
    <div class="epasscard-field-group">
        <h3>Barcode Setting</h3>

        <label>Select Barcode format <span>*</span></label>
        <select class="scanner-code">
            <option value="" disabled
                <?php echo(isset($barcodeInfo['format']) && $barcodeInfo['format'] === '') ? 'selected' : ''; ?>>Please
                select one</option>
            <option value="qrCode"
                <?php echo(isset($barcodeInfo['format']) && $barcodeInfo['format'] === 'qrCode') ? 'selected' : ''; ?>>
                QR Code</option>
            <option value="CODE_128"
                <?php echo(isset($barcodeInfo['format']) && $barcodeInfo['format'] === 'CODE_128') ? 'selected' : ''; ?>>
                Code 128</option>
            <option value="PDF_417"
                <?php echo(isset($barcodeInfo['format']) && $barcodeInfo['format'] === 'PDF_417') ? 'selected' : ''; ?>>
                PDF 417</option>
            <option value="AZTEC"
                <?php echo(isset($barcodeInfo['format']) && $barcodeInfo['format'] === 'AZTEC') ? 'selected' : ''; ?>>
                AZTEC</option>
        </select>

        <label>Barcode Value <span>*</span></label>
        <select class="barcode-value">
            <option value="" disabled
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === '') ? 'selected' : ''; ?>>Please
                select one</option>
            <option value="template_name"
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === 'template_name') ? 'selected' : ''; ?>>
                Template name</option>
            <option value="organization_name"
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === 'organization_name') ? 'selected' : ''; ?>>
                Organization name</option>
            <option value="description"
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === 'description') ? 'selected' : ''; ?>>
                Description</option>
            <option value="systemId"
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === 'systemId') ? 'selected' : ''; ?>>
                System generated ID</option>
            <option value="{Product name}"
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === '{Product name}') ? 'selected' : ''; ?>>
                Product name</option>
            <option value="{Model}"
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === '{Model}') ? 'selected' : ''; ?>>
                Model</option>
            <option value="{Purchase Date}"
                <?php echo(isset($barcodeInfo['value']) && $barcodeInfo['value'] === '{Purchase Date}') ? 'selected' : ''; ?>>
                Purchase Date</option>
        </select>

        <div class="setting-group">
            <div class="toggle-label">
                <input type="checkbox" id="barcode-expire"
                    <?php echo(isset($barcodeInfo['showValue']) && $barcodeInfo['showValue']) ? 'checked' : ''; ?>>
                <span>Show barcode value</span>
            </div>
        </div>

        <label>Alternate Text : (this will show with value)</label>
        <input type="text" class="barcode-alternate-text" placeholder="Enter alternate text for barcode"
            value="<?php echo isset($barcodeInfo['altText']) ? esc_attr($barcodeInfo['altText']) : ''; ?>">
    </div>
</div>