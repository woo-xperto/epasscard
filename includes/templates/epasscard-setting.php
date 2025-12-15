<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard_setting">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSC_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">
        <div class="epasscard-field-group">

            <?php  
                if (isset($epassc_additional_fields) && is_array($epassc_additional_fields)) {
                    $epassc_filtered_additional_fields = array_filter($epassc_additional_fields, function ($epassc_field) {
                        return isset($epassc_field['field_name']) && ! empty($epassc_field['field_name']);
                    });

                    $epassc_filtered_field_names = array_column($epassc_filtered_additional_fields, 'field_name');

                    $epassc_expire_value = trim($epassc_expire_value, '{}');
                }

            ?>
            <!-- Expire Card -->
            <div class="setting-group">
                <div class="toggle-label">
                    <input type="checkbox" class="expire-card" id="expire-card"
                        <?php echo(isset($epassc_expire_enabled) && $epassc_expire_enabled) ? 'checked' : ''; ?>>
                    <span>Expire Card (Enable if you want to add expiry option to this card)</span>
                </div>
                <div class="expire-options conditional-section                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo $epassc_expire_enabled ? '' : 'epasscard-hidden'; ?>"
                    id="expire-options">

                    <div class="expiration-type">
                        <label class="">Type (Select the expiration type)</label>
                        <select>
                            <option disabled>Please select one</option>
                            <option value="date"
                                <?php echo(isset($settings['expire_type']) && $settings['expire_type'] === 'date') ? 'selected' : ''; ?>>
                                Date</option>
                        </select>
                    </div>

                    <label>
                        <input type="radio" id="expire-type-fixed" name="expire_type" value="fixed"
                            <?php echo isset($epassc_expire_type) && $epassc_expire_type === 'fixed' ? 'checked' : ''; ?>>
                        Fixed Value
                    </label>
                    <label>
                        <input type="radio" id="expire-type-dynamic" name="expire_type" value="dynamic"
                            <?php echo isset($epassc_expire_type) && $epassc_expire_type === 'dynamic' ? 'checked' : ''; ?>>
                        Dynamic Value
                    </label>

                    <div>
                        <div
                            class="expire-input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php echo $epassc_expire_type === 'fixed' ? '' : 'epasscard-hidden'; ?>">
                            <input type="datetime-local" id="setting-fixed-value"
                                value="<?php echo esc_attr(gmdate('Y-m-d\TH:i', strtotime(isset($epassc_expire_value) ? $epassc_expire_value : ''))); ?>">
                        </div>
                        <div
                            class="dynamic-input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php echo $epassc_expire_type === 'dynamic' ? '' : 'epasscard-hidden'; ?>">
                            <select class="setting-dynamic-data">
                                <?php
                                    if (isset($epassc_filtered_field_names) && is_array($epassc_filtered_field_names)) {
                                        foreach ($epassc_filtered_field_names as $epassc_field) {
                                            printf(
                                                '<option value="{%s}"%s>%s</option>',
                                                esc_attr($epassc_field),
                                                selected($epassc_field, $epassc_expire_value, false),
                                                esc_html($epassc_field)
                                            );
                                            
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
           
            <!-- Rechargeable Card -->
            <?php if (isset($epassc_filtered_field_names) && isset($epassc_recharge_fields)): $epassc_merged_transaction_fields = array_unique(array_merge($epassc_filtered_field_names, $epassc_recharge_fields));endif; ?>
            <div class="setting-group">
                <div class="toggle-label">
                    <input type="checkbox" class="rechargeable-card"
                        <?php echo(isset($epassc_rechargeable) && $epassc_rechargeable) ? 'checked' : ''; ?>>
                    <span>Rechargeable Card (Enable if this card is rechargeable)</span>
                </div>
                <div class="conditional-section recharge-options                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php echo isset($epassc_rechargeable) && $epassc_rechargeable ? '' : 'epasscard-hidden'; ?>">
                    <label>Select Transectional Fields</label>
                    <select class="recharge-select" multiple>
                        <?php
                            foreach ($epassc_merged_transaction_fields as $epassc_field) {
                                $epassc_field      = trim($epassc_field);
                                $epassc_is_selected = in_array($epassc_field, $epassc_recharge_fields) ? ' selected' : '';
                                echo '<option value="' . esc_attr($epassc_field) . '"' . esc_attr($epassc_is_selected) . '>' . esc_html($epassc_field) . '</option>';
                            }
                        ?>

                    </select>

                    <table id="recharge-table"
                        class="recharge-table                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php echo ! empty($epassc_transection_actions) ? '' : 'epasscard-hidden'; ?>">
                        <thead>
                            <tr>
                                <th>Field Name</th>
                                <th>Action Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (isset($epassc_transection_actions) && is_array($epassc_transection_actions)) {
                                    foreach ($epassc_transection_actions as $action) {
                                        if (isset($action['field']) && isset($action['action'])) {
                                            echo '<tr><td>' . esc_html($action['field']) . '</td> <td>
                                            <select class="action-select">
                                                <option disabled value=""' . ($action['action'] === '' ? ' selected' : '') . '>Please select one</option>
                                                <option value="add_deduct"' . ($action['action'] === 'add_deduct' ? ' selected' : '') . '>Add/Deduct</option>
                                                <option value="replace"' . ($action['action'] === 'replace' ? ' selected' : '') . '>Replace</option>
                                            </select></td></tr>';
                                        }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>

                    <div class="toggle-label transaction-log-wrap">
                        <input type="checkbox" id="transaction-log"
                            <?php echo isset($epassc_transaction_log) && $epassc_transaction_log ? 'checked' : ''; ?>>
                        <span>Transection Log</span>
                    </div>
                </div>
            </div>

            <!-- Date Time Format -->
            <div class="setting-group">
                <label for="datetime-format">Select Date Time Format</label>
                <?php
                    $epassc_date_time_format = isset($epassc_colors_info['dateTimeFormat']) ? $epassc_colors_info['dateTimeFormat'] : '';
                    $epassc_formats        = [
                        ""                      => "Choose format",
                        "YYYY-MM-DD"            => "2025-02-12",
                        "DD-MM-YYYY"            => "12-02-2025",
                        "MM-DD-YYYY"            => "02-12-2025",
                        "YYYY/MM/DD"            => "2025/02/12",
                        "DD/MM/YYYY"            => "12/02/2025",
                        "MM/DD/YYYY"            => "02/12/2025",
                        "MMMM DD, YYYY"         => "February 12, 2025",
                        "DD MMMM YYYY"          => "12 February 2025",
                        "YYYY.MM.DD"            => "2025.02.12",
                        "DD.MM.YYYY"            => "12.02.2025",
                        "MM.DD.YYYY"            => "02.12.2025",
                        "YYYYMMDD"              => "20250212",
                        "DDMMYYYY"              => "12022025",
                        "EEEE, DD MMMM YYYY"    => "Wednesday, 12 February 2025",
                        "Do MMMM YYYY"          => "12th February 2025",
                        "YYYY-MM-DD HH:mm:ss"   => "2025-05-12 12:28:36",
                        "YYYY-MM-DD HH:mm"      => "2025-05-12 12:28",
                        "YYYY-MM-DD HH:mm A"    => "2025-05-12 12:28 PM",
                        "YYYY-MM-DD HH:mm:ss A" => "2025-05-12 12:28:36 PM",
                        "YYYY-MM-DD HH:mm:ss Z" => "2025-05-12 12:28:36 +0000",
                        "YYYY-MM-DDTHH:mm:ssZ"  => "2025-05-12T12:28:36Z",
                    ];
                ?>

                <select id="datetime-format">
                    <?php foreach ($epassc_formats as $epassc_value => $epassc_label): ?>
                    <option value="<?php echo esc_attr($epassc_value); ?>"
                        <?php echo($epassc_date_time_format === $epassc_value) ? 'selected' : ''; ?>>
                        <?php echo esc_html($epassc_label); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sharing Option -->
            <?php $epassc_google_wallet_shareable = isset($epassc_colors_info['googleWalletShareable']) ? $epassc_colors_info['googleWalletShareable'] : ''; ?>
            <div class="setting-group">
                <label>Select sharing option for this template</label>
                <select class="epass-sharing-option">
                    <option disabled value="" <?php echo($epassc_google_wallet_shareable === '') ? 'selected' : ''; ?>>Please
                        select one</option>
                    <option value="STATUS_UNSPECIFIED"
                        <?php echo($epassc_google_wallet_shareable === 'STATUS_UNSPECIFIED') ? 'selected' : ''; ?>>Shareable with multiple users
                    </option>
                    <option value="ONE_USER_ONE_DEVICE"
                        <?php echo($epassc_google_wallet_shareable === 'ONE_USER_ONE_DEVICE') ? 'selected' : ''; ?>>Not shareable</option>
                </select>
            </div>
        </div>
    </div>
</div>