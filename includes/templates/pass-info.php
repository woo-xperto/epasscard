<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard_pass_info">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">
        <?php if(isset($epasscard_total_pass) && $epasscard_total_pass > 0) { ?>
        <div class="epass-alert-box" role="alert">
        <div class="alert-icon">
            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="alert-message">
            You’ve already created <?php echo esc_html($epasscard_total_pass); ?> pass using this template. Unfortunately, this template can no longer be updated or modified. If you need changes, consider creating a new template.
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
                        value="<?php echo esc_attr($epasscard_name ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Description</label>
                    <input type="text" class="template-description" placeholder="Enter description"
                        value="<?php echo esc_attr($epasscard_description ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Organization Name</label>
                    <input type="text" class="organization-name" placeholder="Enter organization name"
                        value="<?php echo esc_attr($epasscard_org_name ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="required">Certificate</label>

                    <select class="pass-type pass-certificates" required>
                        <option disabled value=""><?php echo esc_html__('Select Certificate', 'epasscard'); ?>
                        </option>

                        <?php
                            $epasscard_pass_type_id = $epasscard_pass_type_id ?? '';
                            $epasscard_api_url    = EPASSCARD_API_CERTIFICATES;
                            $epasscard_api_key    = get_option('epasscard_api_key', '');

                            // Static option
                            $epasscard_is_selected = ($epasscard_pass_type_id === 'pass.test.epasscard') ? ' selected' : '';
                            echo '<option value="' . esc_attr('pass.test.epasscard') . '"' . esc_attr($epasscard_is_selected) . '>' . esc_html('pass.test.epasscard') . '</option>';

                            // Dynamic options from API
                            if (! empty($epasscard_api_key)) {
                                $epasscard_response = wp_remote_get($epasscard_api_url, [
                                    'headers' => [
                                        'x-api-key' => $epasscard_api_key,
                                    ],
                                    'timeout' => 15,
                                ]);

                                if (! is_wp_error($epasscard_response) && wp_remote_retrieve_response_code($epasscard_response) === 200) {
                                    $epasscard_body = wp_remote_retrieve_body($epasscard_response);
                                    $epasscard_data = json_decode($epasscard_body, true);

                                    if (is_array($epasscard_data) && json_last_error() === JSON_ERROR_NONE) {
                                        foreach ($epasscard_data as $epasscard_item) {
                                            if (! empty($epasscard_item['certificate_name'])) {
                                                $epasscard_name = sanitize_text_field($epasscard_item['certificate_name']);
                                                echo '<option value="' . esc_attr($epasscard_name) . '" ' . selected($epasscard_pass_type_id, $epasscard_name, false) . '>' . esc_html($epasscard_name) . '</option>';
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
                    <?php if (empty($epasscard_pass_type_id)): ?>
                    $('.pass-certificates').val('').trigger('change');
                    <?php endif; ?>
                });
                </script> -->

                <?php
                    $epasscard_api_key  = get_option('epasscard_api_key', '');
                    $epasscard_api_url = EPASSCARD_API_URL."get-user-data";

                    $epasscard_args = [
                        'headers' => [
                            'x-api-key' => $epasscard_api_key,
                        ],
                        'timeout' => 15,
                    ];

                    $epasscard_response           = wp_remote_get($epasscard_api_url, $epasscard_args);
                    $epasscard_total_pass     = 0;
                    $epasscard_total_created_pass = 0;

                    if (! is_wp_error($epasscard_response)) {
                        $epasscard_body = wp_remote_retrieve_body($epasscard_response);
                        $epasscard_data = json_decode($epasscard_body, true);

                        // Extract the data you need from API response
                        $epasscard_total_pass     = $epasscard_data['data']['num_of_pass'] ?? 0;
                        $epasscard_total_created_pass = $epasscard_data['data']['total_pass_active'] ?? 0;
                        $epasscard_available_pass =  $epasscard_total_pass - $epasscard_total_created_pass;

                    }
                    
                ?>


                <div class="form-group pass-limit-check" data-pass-stats="<?php echo esc_attr(json_encode([
                                                                                  'numberOfPass'     => $epasscard_total_pass,
                                                                              'totalCreatedPass' => $epasscard_total_created_pass,
                                                                          ])); ?>">
                    <label class="required">Maximum number of created passes <small>(Available pass:
                            <span id="availablePass"><?php echo esc_html($epasscard_available_pass); ?></span>)</small></label>
                    <input type="number" class="created-passes" min="0" onkeypress="return event.charCode >= 48"
                        placeholder="Enter maximum number" value="<?php echo esc_attr($epasscard_pass_limit ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>
</div>