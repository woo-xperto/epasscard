<div id="epasscard_pass_info">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">
        <?php if(isset($total_pass) && $total_pass > 0) { ?>
        <div class="epass-alert-box" role="alert">
        <div class="alert-icon">
            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="alert-message">
            You’ve already created <?php echo esc_html($total_pass); ?> pass using this template. Unfortunately, this template can no longer be updated or modified. If you need changes, consider creating a new template.
        </div>
        <button type="button" class="alert-close" aria-label="Close">
            <span class="sr-only">Dismiss</span>
            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
        </div>
        <?php
        }else{
          //Load epasscard default cards
        include EPASSCARD_PLUGIN_DIR . 'includes/templates/epasscard-default-cards.php';
        }
        ?>
        <div class="info-text">
            <strong>Template information section</strong> gives you a way to store Template Informations e.g. (name,
            description, maximum pass you can create with this template, etc).
        </div>
        <div class="epasscard-field-group">
            <div class="form-row">
                <div class="form-group">
                    <label class="required">Template Name</label>
                    <input type="text" class="template-name" placeholder="Enter template name"
                        value="<?php echo esc_attr($name ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Description</label>
                    <input type="text" class="template-description" placeholder="Enter description"
                        value="<?php echo esc_attr($description ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Organization Name</label>
                    <input type="text" class="organization-name" placeholder="Enter organization name"
                        value="<?php echo esc_attr($passOrgName ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="required">Certificate</label>

                    <select class="pass-type pass-certificates" required>
                        <option disabled value=""><?php echo esc_html__('Select Certificate', 'epasscard'); ?>
                        </option>

                        <?php
                            $passTypeId = $passTypeId ?? '';
                            $api_url    = 'https://api.epasscard.com/api/certificate/all-certificates/';
                            $api_key    = get_option('epasscard_api_key', '');

                            // Static option
                            $isSelected = ($passTypeId === 'pass.test.epasscard') ? ' selected' : '';
                            echo '<option value="' . esc_attr('pass.test.epasscard') . '"' . esc_attr($isSelected) . '>' . esc_html('pass.test.epasscard') . '</option>';

                            // Dynamic options from API
                            if (! empty($api_key)) {
                                $response = wp_remote_get($api_url, [
                                    'headers' => [
                                        'x-api-key' => $api_key,
                                    ],
                                    'timeout' => 15,
                                ]);

                                if (! is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                                    $body = wp_remote_retrieve_body($response);
                                    $data = json_decode($body, true);

                                    if (is_array($data) && json_last_error() === JSON_ERROR_NONE) {
                                        foreach ($data as $item) {
                                            if (! empty($item['certificate_name'])) {
                                                $name = sanitize_text_field($item['certificate_name']);
                                                echo '<option value="' . esc_attr($name) . '" ' . selected($passTypeId, $name, false) . '>' . esc_html($name) . '</option>';
                                            }
                                        }
                                    }
                                } else {

                                }
                            }
                        ?>
                    </select>
                </div>


                <!-- Add JavaScript to handle Select2 -->
                <!-- <script>
                jQuery(document).ready(function($) {
                    // Initialize Select2
                    $('.pass-certificates').select2();

                    // Clear any existing selection if passTypeId is empty
                    <?php if (empty($passTypeId)): ?>
                    $('.pass-certificates').val('').trigger('change');
                    <?php endif; ?>
                });
                </script> -->

                <?php
                    $api_key                 = get_option('epasscard_api_key', '');
                    $epasscard_account_email = get_option('epasscard_account_email', '');
                    $api_url                 = "https://api.epasscard.com/api/profile/" . $epasscard_account_email;

                    $args = [
                        'headers' => [
                            'x-api-key' => $api_key,
                        ],
                        'timeout' => 15,
                    ];

                    $response           = wp_remote_get($api_url, $args);
                    $available_pass     = 0;
                    $total_created_pass = 0;

                    if (! is_wp_error($response)) {
                        $body = wp_remote_retrieve_body($response);
                        $data = json_decode($body, true);

                        // Extract the data you need from API response
                        $available_pass     = $data['data']['num_of_pass'] ?? 0;
                        $total_created_pass = $data['data']['total_created_pass'] ?? 0;
                    }

                ?>


                <div class="form-group pass-limit-check" data-pass-stats="<?php echo esc_attr(json_encode([
                                                                                  'numberOfPass'     => $available_pass,
                                                                              'totalCreatedPass' => $total_created_pass,
                                                                          ])); ?>">
                    <label class="required">Maximum number of created passes <small>(Available pass:
                            <span id="availablePass"><?php echo esc_html($available_pass); ?></span>)</small></label>
                    <input type="number" class="created-passes" min="0" onkeypress="return event.charCode >= 48"
                        placeholder="Enter maximum number" value="<?php echo esc_attr($passLimit ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>
</div>