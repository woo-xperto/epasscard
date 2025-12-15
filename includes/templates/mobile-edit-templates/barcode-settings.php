<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$epassc_barcode_value = $epassc_barcode_info['value'] ?? "systemId";
$epassc_barcode_hide = ($epassc_barcode_value == "systemId" || $epassc_barcode_value == "") ? "epasscard-hidden": "";
$epassc_barcode_check = ($epassc_barcode_value == "systemId" || $epassc_barcode_value == "") ? "checked": "";
?>
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

        <!-- Barcode value -->
        <div class="epassc-barcode-value-wrap <?php echo esc_attr($epassc_barcode_hide); ?>">
            <label>Barcode Value <span>*</span></label>
            <input type="text" class="barcode-value epasscard-watch-input" value="<?php echo esc_attr($epassc_barcode_value); ?>" placeholder="<?php echo esc_attr($epassc_barcode_value); ?>">
        </div>
  
        <!-- Use system generated Pass ID -->
        <div class="setting-group">
            <div class="toggle-label">
                <input type="checkbox" id="epassc-system-generated-id" <?php echo esc_attr($epassc_barcode_check); ?>>
                <span>Use system generated Pass ID</span>
            </div>
        </div>

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