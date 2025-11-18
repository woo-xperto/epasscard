<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard_setting">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">
        <div class="epasscard-field-group">

            <?php  
                if (isset($epasscard_additional_fields) && is_array($epasscard_additional_fields)) {
                    $epasscard_filtered_additional_fields = array_filter($epasscard_additional_fields, function ($epasscard_field) {
                        return isset($epasscard_field['field_name']) && ! empty($epasscard_field['field_name']);
                    });

                    $epasscard_filtered_field_names = array_column($epasscard_filtered_additional_fields, 'field_name');

                    $epasscard_expire_value = trim($epasscard_expire_value, '{}');
                }

            ?>
            <!-- Expire Card -->
            <div class="setting-group">
                <div class="toggle-label">
                    <input type="checkbox" class="expire-card" id="expire-card"
                        <?php echo(isset($epasscard_expire_enabled) && $epasscard_expire_enabled) ? 'checked' : ''; ?>>
                    <span>Expire Card (Enable if you want to add expiry option to this card)</span>
                </div>
                <div class="expire-options conditional-section                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo $epasscard_expire_enabled ? '' : 'epasscard-hidden'; ?>"
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
                            <?php echo isset($epasscard_expire_type) && $epasscard_expire_type === 'fixed' ? 'checked' : ''; ?>>
                        Fixed Value
                    </label>
                    <label>
                        <input type="radio" id="expire-type-dynamic" name="expire_type" value="dynamic"
                            <?php echo isset($epasscard_expire_type) && $epasscard_expire_type === 'dynamic' ? 'checked' : ''; ?>>
                        Dynamic Value
                    </label>

                    <div>
                        <div
                            class="expire-input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php echo $epasscard_expire_type === 'fixed' ? '' : 'epasscard-hidden'; ?>">
                            <input type="datetime-local" id="setting-fixed-value"
                                value="<?php echo esc_attr(gmdate('Y-m-d\TH:i', strtotime(isset($epasscard_expire_value) ? $epasscard_expire_value : ''))); ?>">
                        </div>
                        <div
                            class="dynamic-input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php echo $epasscard_expire_type === 'dynamic' ? '' : 'epasscard-hidden'; ?>">
                            <select class="setting-dynamic-data">
                                <?php
                                    if (isset($epasscard_filtered_field_names) && is_array($epasscard_filtered_field_names)) {
                                        foreach ($epasscard_filtered_field_names as $epasscard_field) {
                                            printf(
                                                '<option value="{%s}"%s>%s</option>',
                                                esc_attr($epasscard_field),
                                                selected($epasscard_field, $epasscard_expire_value, false),
                                                esc_html($epasscard_field)
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
            <?php if (isset($epasscard_filtered_field_names) && isset($epasscard_recharge_fields)): $epasscard_merged_transaction_fields = array_unique(array_merge($epasscard_filtered_field_names, $epasscard_recharge_fields));endif; ?>
            <div class="setting-group">
                <div class="toggle-label">
                    <input type="checkbox" class="rechargeable-card"
                        <?php echo(isset($epasscard_rechargeable) && $epasscard_rechargeable) ? 'checked' : ''; ?>>
                    <span>Rechargeable Card (Enable if this card is rechargeable)</span>
                </div>
                <div class="conditional-section recharge-options                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php echo isset($epasscard_rechargeable) && $epasscard_rechargeable ? '' : 'epasscard-hidden'; ?>">
                    <label>Select Transectional Fields</label>
                    <select class="recharge-select" multiple>
                        <?php
                            foreach ($epasscard_merged_transaction_fields as $epasscard_field) {
                                $epasscard_field      = trim($epasscard_field);
                                $epasscard_is_selected = in_array($epasscard_field, $epasscard_recharge_fields) ? ' selected' : '';
                                echo '<option value="' . esc_attr($epasscard_field) . '"' . esc_attr($epasscard_is_selected) . '>' . esc_html($epasscard_field) . '</option>';
                            }
                        ?>

                    </select>

                    <table id="recharge-table"
                        class="recharge-table                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           <?php echo ! empty($epasscard_transection_actions) ? '' : 'epasscard-hidden'; ?>">
                        <thead>
                            <tr>
                                <th>Field Name</th>
                                <th>Action Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (isset($epasscard_transection_actions) && is_array($epasscard_transection_actions)) {
                                    foreach ($epasscard_transection_actions as $action) {
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
                            <?php echo isset($epasscard_transaction_log) && $epasscard_transaction_log ? 'checked' : ''; ?>>
                        <span>Transection Log</span>
                    </div>
                </div>
            </div>

            <!-- Date Time Format -->
            <div class="setting-group">
                <label for="datetime-format">Select Date Time Format</label>
                <?php
                    $epasscard_date_time_format = isset($epasscard_colors_info['dateTimeFormat']) ? $epasscard_colors_info['dateTimeFormat'] : '';
                    $epasscard_formats        = [
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
                    <?php foreach ($epasscard_formats as $epasscard_value => $epasscard_label): ?>
                    <option value="<?php echo esc_attr($epasscard_value); ?>"
                        <?php echo($epasscard_date_time_format === $epasscard_value) ? 'selected' : ''; ?>>
                        <?php echo esc_html($epasscard_label); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

           <!-- Unicode block -->
            <div class="unicode-wrap epasscard-hidden">
            <div class="unicode-input">
                <div>
                <label for="unique-name">Random unique code prefix</label>
                <input class="input-field" type="text" id="unique-name">
                </div>
                <div>
                <label>Random unique code length</label>
                <input class="input-field" label="Unique number length" type="number">
                <p id="unique_code_length_error"></p>
                </div>
                <div>
                <label for="unique-name">Random unique code suffix</label>
                <input class="input-field" type="text" id="unique-name">
                </div>
            </div>
            <label>Random unique code format</label>
            <div class="unicode-type">
                <div>
                <div>
                    <input id="ingredient1" type="radio" value="Numeric">
                    <div>
                    <div class="p-radiobutton-icon" data-pc-section="icon"></div>
                    </div>
                </div>
                <label for="ingredient1"> Numeric </label>
                </div>
                <div>
                <div>
                    <input id="ingredient1" type="radio" value="alphaNumeric">
                    <div class="p-radiobutton-box" data-pc-section="box" bis_skin_checked="1">
                    <div class="p-radiobutton-icon" data-pc-section="icon" bis_skin_checked="1"></div>
                    </div>
                </div>
                <label for="alphaNumeric">Alpha numeric </label>
                </div>
            </div>
            </div>

            <!-- Sharing Option -->
            <?php $epasscard_google_wallet_shareable = isset($epasscard_colors_info['googleWalletShareable']) ? $epasscard_colors_info['googleWalletShareable'] : ''; ?>
            <div class="setting-group">
                <label>Select sharing option for this template</label>
                <select class="epass-sharing-option">
                    <option disabled value="" <?php echo($epasscard_google_wallet_shareable === '') ? 'selected' : ''; ?>>Please
                        select one</option>
                    <option value="STATUS_UNSPECIFIED"
                        <?php echo($epasscard_google_wallet_shareable === 'STATUS_UNSPECIFIED') ? 'selected' : ''; ?>>Default
                    </option>
                    <option value="MULTIPLE_HOLDERS"
                        <?php echo($epasscard_google_wallet_shareable === 'MULTIPLE_HOLDERS') ? 'selected' : ''; ?>>Allow for all
                    </option>
                    <option value="ONE_USER_ALL_DEVICES"
                        <?php echo($epasscard_google_wallet_shareable === 'ONE_USER_ALL_DEVICES') ? 'selected' : ''; ?>>Allow
                        multiple device (same account)</option>
                    <option value="ONE_USER_ONE_DEVICE"
                        <?php echo($epasscard_google_wallet_shareable === 'ONE_USER_ONE_DEVICE') ? 'selected' : ''; ?>>Allow single
                        device</option>
                </select>
            </div>
        </div>
    </div>
</div>