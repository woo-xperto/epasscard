<div id="epasscard_setting">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">
        <div class="epasscard-field-group">

            <?php
                if (isset($additionalFields) && is_array($additionalFields)) {
                    $filteredAdditionalFields = array_filter($additionalFields, function ($field) {
                        return isset($field['field_name']) && ! empty($field['field_name']);
                    });

                    $filteredFieldNames = array_column($filteredAdditionalFields, 'field_name');

                    $expireValue = trim($expireValue, '{}');
                }

            ?>
            <!-- Expire Card -->
            <div class="setting-group">
                <div class="toggle-label">
                    <input type="checkbox" class="expire-card" id="expire-card"
                        <?php echo(isset($expireEnabled) && $expireEnabled) ? 'checked' : ''; ?>>
                    <span>Expire Card (Enable if you want to add expiry option to this card)</span>
                </div>
                <div class="expire-options conditional-section                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php echo $expireEnabled ? '' : 'epasscard-hidden'; ?>"
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
                            <?php echo isset($expireType) && $expireType === 'fixed' ? 'checked' : ''; ?>>
                        Fixed Value
                    </label>
                    <label>
                        <input type="radio" id="expire-type-dynamic" name="expire_type" value="dynamic"
                            <?php echo isset($expireType) && $expireType === 'dynamic' ? 'checked' : ''; ?>>
                        Dynamic Value
                    </label>

                    <div>
                        <div
                            class="expire-input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php echo $expireType === 'fixed' ? '' : 'epasscard-hidden'; ?>">
                            <input type="datetime-local" id="setting-fixed-value"
                                value="<?php echo esc_attr(gmdate('Y-m-d\TH:i', strtotime(isset($expireValue) ? $expireValue : ''))); ?>">
                        </div>
                        <div
                            class="dynamic-input                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <?php echo $expireType === 'dynamic' ? '' : 'epasscard-hidden'; ?>">
                            <select class="setting-dynamic-data">
                                <?php
                                    if (isset($filteredFieldNames) && is_array($filteredFieldNames)) {
                                        foreach ($filteredFieldNames as $field) {
                                            printf(
                                                '<option value="{%s}"%s>%s</option>',
                                                esc_attr($field),
                                                selected($field, $expireValue, false),
                                                esc_html($field)
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
            <?php if (isset($filteredFieldNames) && isset($rechargeFields)): $mergedTransactionFields = array_unique(array_merge($filteredFieldNames, $rechargeFields));endif; ?>
            <div class="setting-group">
                <div class="toggle-label">
                    <input type="checkbox" class="rechargeable-card"
                        <?php echo(isset($rechargeable) && $rechargeable) ? 'checked' : ''; ?>>
                    <span>Rechargeable Card (Enable if this card is rechargeable)</span>
                </div>
                <div class="conditional-section recharge-options                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php echo isset($rechargeable) && $rechargeable ? '' : 'epasscard-hidden'; ?>
">
                    <label>Select Transectional Fields</label>
                    <select class="recharge-select" multiple>
                        <?php
                            foreach ($mergedTransactionFields as $field) {
                                $field      = trim($field);
                                $isSelected = in_array($field, $rechargeFields) ? ' selected' : '';
                                echo '<option value="' . esc_attr($field) . '"' . esc_attr($isSelected) . '>' . esc_html($field) . '</option>';
                            }
                        ?>

                    </select>

                    <table id="recharge-table"
                        class="recharge-table                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php echo ! empty($transectionActions) ? '' : 'epasscard-hidden'; ?>">
                        <thead>
                            <tr>
                                <th>Field Name</th>
                                <th>Action Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if (isset($transectionActions) && is_array($transectionActions)) {
                                    foreach ($transectionActions as $action) {
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
                            <?php echo isset($transactionLog) && $transactionLog ? 'checked' : ''; ?>>
                        <span>Transection Log</span>
                    </div>
                </div>
            </div>

            <!-- Date Time Format -->
            <div class="setting-group">
                <label for="datetime-format">Select Date Time Format</label>
                <?php
                    $dateTimeFormat = isset($colorsInfo['dateTimeFormat']) ? $colorsInfo['dateTimeFormat'] : '';
                    $formats        = [
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
                    <?php foreach ($formats as $value => $label): ?>
                    <option value="<?php echo esc_attr($value); ?>"
                        <?php echo($dateTimeFormat === $value) ? 'selected' : ''; ?>>
                        <?php echo esc_html($label); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sharing Option -->
            <?php $googleWalletShareable = isset($colorsInfo['googleWalletShareable']) ? $colorsInfo['googleWalletShareable'] : ''; ?>
            <div class="setting-group">
                <label>Select sharing option for this template</label>
                <select class="epass-sharing-option">
                    <option disabled value=""                                              <?php echo($googleWalletShareable === '') ? 'selected' : ''; ?>>Please
                        select one</option>
                    <option value="STATUS_UNSPECIFIED"
                        <?php echo($googleWalletShareable === 'STATUS_UNSPECIFIED') ? 'selected' : ''; ?>>Default
                    </option>
                    <option value="MULTIPLE_HOLDERS"
                        <?php echo($googleWalletShareable === 'MULTIPLE_HOLDERS') ? 'selected' : ''; ?>>Allow for all
                    </option>
                    <option value="ONE_USER_ALL_DEVICES"
                        <?php echo($googleWalletShareable === 'ONE_USER_ALL_DEVICES') ? 'selected' : ''; ?>>Allow
                        multiple device (same account)</option>
                    <option value="ONE_USER_ONE_DEVICE"
                        <?php echo($googleWalletShareable === 'ONE_USER_ONE_DEVICE') ? 'selected' : ''; ?>>Allow single
                        device</option>
                </select>
            </div>
        </div>
    </div>
</div>