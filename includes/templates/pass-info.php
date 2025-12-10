<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard_pass_info">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSC_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right">
        <?php if(isset($epassc_total_pass) && $epassc_total_pass > 0) { ?>
        <div class="epass-alert-box" role="alert">
        <div class="alert-icon">
            <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="alert-message">
            You’ve already created <?php echo esc_html($epassc_total_pass); ?> pass using this template. Unfortunately, this template can no longer be updated or modified. If you need changes, consider creating a new template.
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
        include EPASSC_PLUGIN_DIR . 'includes/templates/epasscard-default-cards.php';
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
                        value="<?php echo esc_attr($epassc_name ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Description</label>
                    <input type="text" class="template-description" placeholder="Enter description"
                        value="<?php echo esc_attr($epassc_description ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label class="required">Organization Name</label>
                    <input type="text" class="organization-name" placeholder="Enter organization name"
                        value="<?php echo esc_attr($epassc_org_name ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="required">Certificate</label>

                    <select class="pass-type pass-certificates" required>
                        <option disabled value=""><?php echo esc_html__('Select Certificate', 'epasscard'); ?>
                        </option>

                        <?php
                            $epassc_pass_type_id = $epassc_pass_type_id ?? '';
                            $EPASSC_API_URL    = EPASSC_API_CERTIFICATES;
                            $epassc_api_key    = EPASSC_API_KEY;

                            // Static option
                            $epassc_is_selected = ($epassc_pass_type_id === 'pass.test.epasscard') ? ' selected' : '';
                            echo '<option value="' . esc_attr('pass.test.epasscard') . '"' . esc_attr($epassc_is_selected) . '>' . esc_html('pass.test.epasscard') . '</option>';

                            // Dynamic options from API
                            if (! empty($epassc_api_key)) {
                                $epassc_response = wp_remote_get($EPASSC_API_URL, [
                                    'headers' => [
                                        'x-api-key' => $epassc_api_key,
                                    ],
                                    'timeout' => 15,
                                ]);

                                if (! is_wp_error($epassc_response) && wp_remote_retrieve_response_code($epassc_response) === 200) {
                                    $epassc_body = wp_remote_retrieve_body($epassc_response);
                                    $epassc_data = json_decode($epassc_body, true);

                                    if (is_array($epassc_data) && json_last_error() === JSON_ERROR_NONE) {
                                        foreach ($epassc_data as $epassc_item) {
                                            if (! empty($epassc_item['certificate_name'])) {
                                                $epassc_name = sanitize_text_field($epassc_item['certificate_name']);
                                                echo '<option value="' . esc_attr($epassc_name) . '" ' . selected($epassc_pass_type_id, $epassc_name, false) . '>' . esc_html($epassc_name) . '</option>';
                                            }
                                        }
                                    }
                                } else {

                                }
                            }
                        ?>
                    </select>
                </div>

                <?php
                    $epassc_api_key  = EPASSC_API_KEY;
                    $EPASSC_API_URL = EPASSC_API_URL."get-user-data";

                    $epassc_args = [
                        'headers' => [
                            'x-api-key' => $epassc_api_key,
                        ],
                        'timeout' => 15,
                    ];

                    $epassc_response           = wp_remote_get($EPASSC_API_URL, $epassc_args);
                    $epassc_total_pass     = 0;
                    $epassc_total_created_pass = 0;

                    if (! is_wp_error($epassc_response)) {
                        $epassc_body = wp_remote_retrieve_body($epassc_response);
                        $epassc_data = json_decode($epassc_body, true);

                        // Extract the data you need from API response
                        $epassc_total_pass     = $epassc_data['data']['num_of_pass'] ?? 0;
                        $epassc_total_created_pass = $epassc_data['data']['total_pass_active'] ?? 0;
                        $epassc_available_pass =  $epassc_total_pass - $epassc_total_created_pass;

                    }
                    
                ?>


                <div class="form-group pass-limit-check" data-pass-stats="<?php echo esc_attr(json_encode([
                                                                                  'numberOfPass'     => $epassc_total_pass,
                                                                              'totalCreatedPass' => $epassc_total_created_pass,
                                                                          ])); ?>">
                    <label class="required">Maximum number of created passes <small>(Available pass:
                            <span id="availablePass"><?php echo esc_html($epassc_available_pass); ?></span>)</small></label>
                    <input type="number" class="created-passes" min="0" onkeypress="return event.charCode >= 48"
                        placeholder="Enter maximum number" value="<?php echo esc_attr($epassc_pass_limit ?? ''); ?>">
                </div>
            </div>
        </div>
    </div>
</div>