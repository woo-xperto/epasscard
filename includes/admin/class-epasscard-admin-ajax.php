<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
class EPASSC_Ajax
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('wp_ajax_epassc_connect', [$this, 'epassc_connect']);

        //Get card list
        add_action('wp_ajax_epassc_templates_callback', [$this, 'epassc_templates_callback']);
        add_action('wp_ajax_nopriv_epassc_templates_callback', [$this, 'epassc_templates_callback']);

        // Create Pass template
        add_action('wp_ajax_epassc_create_pass_template', [$this, 'epassc_create_pass_template']);
        add_action('wp_ajax_nopriv_epassc_create_pass_template', [$this, 'epassc_create_pass_template']);

        //Get location
        add_action('wp_ajax_epassc_get_location', [$this, 'epassc_get_location']);
        add_action('wp_ajax_nopriv_epassc_get_location', [$this, 'epassc_get_location']);

        //Update API key manually
        add_action('wp_ajax_epassc_update_api_key_manually', [$this, 'epassc_update_api_key_manually']);

    }

    /**
     * Handle the connect AJAX request
     */
    public function epassc_connect()
    {
        check_ajax_referer('epasscard_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('You do not have permission to perform this action.', 'epasscard')], 403);
        }

        $response = [
            'success' => false,
            'message' => '',
        ];

        // Sanitize and validate input
        $api_key = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';

        // Validate fields
        $errors = [];

        if (empty($api_key)) {
            $errors['api_key'] = __('API key is required', 'epasscard');
        }

        if (!empty($errors)) {
            $response['errors'] = $errors;
            wp_send_json($response, 400);
            return;
        }


        try {
            $response = wp_remote_post(EPASSC_API_KEY_VALID_URL, [
                'body' => json_encode([
                    'apiKey' => $api_key,
                ]),
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $data = json_decode(wp_remote_retrieve_body($response), true);


            if ($data['status'] == 200 && $data['data']['valid'] && $data['data']['activeStatus']) {

                // Save the settings
                $api_key_saved = update_option('epassc_api_key', $data['data']['api_key']);
                update_option('epassc_org_id', $data['data']['org_id']);
                update_option('epassc_next_refresh', $data['data']['next_refresh']);


                // First, clear any existing scheduled event
                $next_schedule = wp_next_scheduled('epassc_refresh_event');
                if ($next_schedule) {
                    wp_unschedule_event($next_schedule, 'epassc_refresh_event');
                }

                //Get next refresh calculated time
                $next_refresh_time = $this->epassc_calculate_api_key_next_refresh_time($data['data']['next_refresh']);
                wp_schedule_single_event($next_refresh_time, 'epassc_refresh_event');

                if ($api_key_saved) {
                    $response['success'] = true;
                    $response['message'] = __('Settings saved successfully!', 'epasscard');
                } else {
                    $response['message'] = __('Settings unchanged. The new values are identical to the existing ones.', 'epasscard');
                }
            } else {
                $response['message'] = $data['message'];
            }
        } catch (Exception $e) {
            $response['message'] = __('An error occurred while saving the settings: ', 'epasscard') . esc_html($e->getMessage());
            wp_send_json($response, 500);
            return;
        }

        wp_send_json($response);

    }


    /**
     * Refresh API key
     */

    public function epassc_refresh_api_key()
    {
        $apiKey = EPASSC_API_KEY;
        $orgId = get_option('epassc_org_id');

        try {
            $api_response = wp_remote_post(EPASSC_API_KEY_REFRESH_URL, [
                'body' => json_encode([
                    'apiKey' => $apiKey,
                    'orgId' => $orgId
                ]),
                'headers' => ['Content-Type' => 'application/json']
            ]);

            if (is_wp_error($api_response)) {
                wp_send_json([
                    'message' => __('Failed to contact API server.', 'epasscard')
                ], 500);
                return;
            }

            $data = json_decode(wp_remote_retrieve_body($api_response), true);

            if (!empty($data['data']['api_key'])) {
                update_option('epassc_api_key', $data['data']['api_key']);
                update_option('epassc_next_refresh', $data['data']['next_refresh']);


                // First, clear any existing scheduled event
                $next_schedule = wp_next_scheduled('epassc_refresh_event');
                if ($next_schedule) {
                    wp_unschedule_event($next_schedule, 'epassc_refresh_event');
                }

                //Get next refresh calculated time
                $next_refresh_time = $this->epassc_calculate_api_key_next_refresh_time($data['data']['next_refresh']);

                wp_schedule_single_event($next_refresh_time, 'epassc_refresh_event');

                // Success response
                $response['success'] = true;

            } else {
                //Failure response
                $response['success'] = false;
            }

            $response['message'] = $data['message'];

        } catch (Exception $e) {
            wp_send_json([
                'message' => __('An error occurred: ', 'epasscard') . esc_html($e->getMessage())
            ], 500);
        }

        wp_send_json($response);
    }

    //Next Refresh time calculate by current time zone
    public function epassc_calculate_api_key_next_refresh_time($next_refresh_time)
    {

        // Create a DateTime object in UTC
        $dt = new DateTime($next_refresh_time, new DateTimeZone('UTC'));

        // Get WordPress timezone setting
        $wp_timezone = wp_timezone(); // returns a DateTimeZone object

        // Convert to WordPress timezone
        $dt->setTimezone($wp_timezone);

        // Subtract 5 minutes
        $dt->modify('-5 minutes');

        // Return Unix timestamp in WP timezone
        return strtotime($dt->format('Y-m-d H:i:s'));
    }


    // Update API key manually
    public function epassc_update_api_key_manually()
    {
        check_ajax_referer('epasscard_ajax_nonce', 'nonce');
        $this->epassc_refresh_api_key();
    }

    // Get pass card list
    public function epassc_templates_callback()
    {
        check_ajax_referer('epasscard_ajax_nonce');
        if (isset($_POST['request_identifier'])) {

            if ($_POST['request_identifier'] == 'epass-search-location-details') {

                check_ajax_referer('epasscard_ajax_nonce');

                $place_id = isset($_POST['place_id']) ? sanitize_text_field(wp_unslash($_POST['place_id'])) : '';

                $api_key = EPASSC_API_KEY;

                $api_url = EPASSC_LOCATION_API_URL . urlencode($place_id);
                $args = [
                    'headers' => [
                        'x-api-key' => $api_key,
                    ],
                    'timeout' => 15, // Added timeout for better practice
                ];

                $response = wp_remote_get($api_url, $args);

                // Check for errors
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                } else {
                    $body = wp_remote_retrieve_body($response);
                    $data = json_decode($body, true);
                    wp_send_json_success($data);
                }

            }

        }
        wp_die();
    }


    //Check Epasscard API key valid or not
    public function epassc_check_api_key()
    {
        $response = [
            'success' => false,
            'message' => '',
        ];

        $api_key = EPASSC_API_KEY;

        if (empty($api_key)) {
            $response['errors']['api_key'] = __('API key is required', 'epasscard');
            return $response; // return instead of wp_send_json
        }

        try {
            $remote = wp_remote_post(EPASSC_API_KEY_VALID_URL, [
                'body' => json_encode(['apiKey' => $api_key]),
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            $data = json_decode(wp_remote_retrieve_body($remote), true);

            return $data;
        } catch (Exception $e) {
            $response['message'] = __('An error occurred while saving the settings: ', 'epasscard') . esc_html($e->getMessage());
        }

        return $response; // return instead of wp_send_json
    }



    // Create pass template
    public function epassc_create_pass_template()
    {
        // Verify nonce for security
        check_ajax_referer('epasscard_ajax_nonce');

        // Collect all POST data into a single array
        $template_info_raw = isset($_POST['template_info']) ? sanitize_textarea_field(wp_unslash($_POST['template_info'])) : "";
        $template_info = [];
        if (!empty($template_info_raw)) {
            $template_info = json_decode($template_info_raw, true);
        }

        $barcode_data_raw = isset($_POST['barcode_data']) ? sanitize_textarea_field(wp_unslash($_POST['barcode_data'])) : "";
        $barcode_data = [];
        if (!empty($barcode_data_raw)) {
            $barcode_data = json_decode($barcode_data_raw, true);
        }

        $header_data_raw = isset($_POST['header_data']) ? sanitize_textarea_field(wp_unslash($_POST['header_data'])) : "";
        $header_data = [];
        if (!empty($header_data_raw)) {
            $header_data = json_decode($header_data_raw, true);
        }
        
        $primary_fields_data_raw = isset($_POST['primary_fields_data']) ? sanitize_textarea_field(wp_unslash($_POST['primary_fields_data'])) : "";
        $primary_fields_data = [];
        if (!empty($primary_fields_data_raw)) {
            $primary_fields_data = json_decode($primary_fields_data_raw, true);
        }
        
        $secondary_data = isset($_POST['secondary_data']) ? sanitize_textarea_field(wp_unslash($_POST['secondary_data'])) : "";

        $back_field_data_raw = isset($_POST['back_field_data']) ? sanitize_textarea_field(wp_unslash($_POST['back_field_data'])) : "";
        $back_field_data = [];
        if (!empty($back_field_data_raw)) {
            $back_field_data = json_decode($back_field_data_raw, true);
        }

        $setting_data_raw = isset($_POST['setting_data']) ? sanitize_textarea_field(wp_unslash($_POST['setting_data'])) : "";
        $setting_data = [];
        if (!empty($setting_data_raw)) {
            $setting_data = json_decode($setting_data_raw, true);
        }

        $locations_data = isset($_POST['locations_data']) ? sanitize_textarea_field(wp_unslash($_POST['locations_data'])) : "";
        $locations_setting_id = isset($_POST['locations_setting_id']) ? sanitize_text_field(wp_unslash($_POST['locations_setting_id'])) : "";
        $notification_radius = isset($_POST['notification_radius']) ? sanitize_text_field(wp_unslash($_POST['notification_radius'])) : "";

        $setting_values_raw = isset($_POST['setting_values']) ? sanitize_textarea_field(wp_unslash($_POST['setting_values'])) : "";
        $setting_values = [];
        if (!empty($setting_values_raw)) {
            $setting_values = json_decode($setting_values_raw, true);
        }

        $additional_properties = isset($_POST['additional_properties']) ? sanitize_textarea_field(wp_unslash($_POST['additional_properties'])) : "";
        $auxiliary_properties = isset($_POST['auxiliary_properties']) ? sanitize_textarea_field(wp_unslash($_POST['auxiliary_properties'])) : "";
        $recharge_field = isset($_POST['recharge_field']) ? sanitize_textarea_field(wp_unslash($_POST['recharge_field'])) : "";
        $transaction_values = isset($_POST['transaction_values']) ? sanitize_textarea_field(wp_unslash($_POST['transaction_values'])) : "";
        $pass_ids = isset($_POST['pass_ids']) ? sanitize_textarea_field(wp_unslash($_POST['pass_ids'])) : "";
        $template_data_raw = isset($_POST['template_data']) ? sanitize_textarea_field(wp_unslash($_POST['template_data'])) : "";

        $template_data = [];
        if (!empty($template_data_raw)) {
            $template_data = json_decode($template_data_raw, true);

        }


        $data = [
            'template_info' => $template_info,
            'barcode_data' => $barcode_data,
            'header_data' => $header_data,
            'primary_fields_data' => $primary_fields_data,
            //'secondary_data' => $secondary_data,
            'back_field_data' => $back_field_data,
            'setting_data' => $setting_data,
            'locations_data' => $locations_data,
            'locations_setting_id' => $locations_setting_id,
            'notification_radius' => $notification_radius,
            'setting_values' => $setting_values,
            'additional_properties' => $additional_properties,
            'recharge_field' => $recharge_field,
            'transaction_values' => $transaction_values,
            'pass_ids' => $pass_ids,
            'template_data' => $template_data,
        ];

        // Process header fields
        $headerFields = [];
        if (!empty($data['header_data']['headerLabels'])) {
            for ($i = 0; $i < count($data['header_data']['headerLabels']); $i++) {
                $headerFields[] = [
                    "label" => $data['header_data']['headerLabels'][$i],
                    "value" => $data['header_data']['productNames'][$i],
                    "valueType" => "fixed",
                    "changeMsg" => $data['header_data']['headerChangeMessages'][$i],
                    "staticValueType" => "",
                ];
            }
        }

        // Process secondary fields
        $secondaryFields = [];
        if (!empty($secondary_data)) {
            $decoded = json_decode($secondary_data, true);

            if (is_array($decoded)) {
                foreach ($decoded as $field) {
                    $secondaryFields[] = [
                        "label" => sanitize_textarea_field($field['label']),
                        "value" => sanitize_textarea_field($field['value']),
                        "valueType" => "fixed",
                        "changeMsg" => sanitize_textarea_field($field['changeMsg']),
                        "staticValueType" => "text",
                    ];
                }
            }
        }


        // Process back fields
        $backFields = [];
        if (!empty($data['back_field_data']['backFieldLabels']) && !empty($data['back_field_data']['backFieldValues'])) {
            $count = min(count($data['back_field_data']['backFieldLabels']), count($data['back_field_data']['backFieldValues']));
            for ($i = 0; $i < $count; $i++) {
                $backFields[] = [
                    "label" => $data['back_field_data']['backFieldLabels'][$i],
                    "value" => $data['back_field_data']['backFieldValues'][$i],
                    "valueType" => "fixed",
                    "changeMsg" => "",
                    "staticValueType" => "textarea",
                ];
            }
        }

        //Auxiliary data
        $auxiliaryFields = [];
        $auxiliary_properties = json_decode($auxiliary_properties, true);
        if (!empty($auxiliary_properties['auxiliaryLabels']) && !empty($auxiliary_properties['auxiliaryValues'])) {
            foreach ($auxiliary_properties['auxiliaryLabels'] as $index => $label) {
                $value = $auxiliary_properties['auxiliaryValues'][$index] ?? '';
                $message = $auxiliary_properties['auxiliaryMsgs'][$index] ?? '';
                // Build the desired structure
                $auxiliaryFields[] = [
                    'label' => $label,
                    'value' => $value,
                    'valueType' => 'fixed',
                    'changeMsg' => $message,
                    'staticValueType' => 'text'
                ];
            }
        }

        // Add processed fields to the data array
        $data['headerFields'] = $headerFields;
        $data['secondaryFields'] = $secondaryFields;
        $data['backFields'] = $backFields;
        $data['auxiliary_properties'] = $auxiliaryFields;


        // Call the template creation function with the full data array
        $this->epassc_make_pass_template_request($data);

        wp_die();
    }

    // Pass create method
    public function epassc_make_pass_template_request(array $data)
    {


        $primarySettings = $data['template_data']['designObj']['primarySettings'] ?? [];

        $templateName = isset($primarySettings['name'])
            ? $primarySettings['name']
            : '';

        $cardType = isset($primarySettings['cardType'])
            ? $primarySettings['cardType']
            : '';

        // Encode field arrays
        $headerFieldsJson = json_encode($data['headerFields'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $backFieldsJson = json_encode($data['backFields'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $secondaryFieldsJson = json_encode($data['secondaryFields'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $locationsDataJson = stripslashes($data['locations_data']);
        $additionalProperties = $data['additional_properties'];
        $auxiliarylProperties = json_encode($data['auxiliary_properties'], JSON_PRETTY_PRINT);
        $rechargeField = $data['recharge_field'];
        $transactionValues = $data['transaction_values'];

        // Extract IDs
        $decoded_ids = json_decode($data['pass_ids'], true);
        $pass_id = sanitize_textarea_field($decoded_ids['id']);
        $pass_uid = sanitize_textarea_field($decoded_ids['uid']);
        $organization_id = sanitize_textarea_field($decoded_ids['org_id']);

        // Make variables accessible to included file
        $template_info = $data['template_info'];
        $setting_data = $data['setting_data'];
        $barcode_data = $data['barcode_data'];
        $primary_fields_data = $data['primary_fields_data'];
        $setting_values = $data['setting_values'];
        $locations_setting_id = $data['locations_setting_id'];
        $notification_radius = $data['notification_radius'];

        // Include appropriate file
        if ($pass_id && $pass_uid) {
            require EPASSC_PLUGIN_DIR . 'includes/admin/ajax-files/pass-update.php';
        } else {
            require EPASSC_PLUGIN_DIR . 'includes/admin/ajax-files/pass-create.php';
        }
    }

    //Get location
    public function epassc_get_location()
    {
        check_ajax_referer('epasscard_ajax_nonce');

        $place_name = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
        $api_key = EPASSC_API_KEY;

        if (empty($place_name)) {
            wp_send_json_error('Place name is required');
        }

        // Use the dynamic place name in the API URL
        $api_url = EPASSC_PLACE_API_URL . urlencode($place_name);

        $args = [
            'headers' => [
                'x-api-key' => $api_key,
            ],
            'timeout' => 15,
        ];

        $response = wp_remote_get($api_url, $args);

        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        } else {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            wp_send_json_success($data);
        }
    }

}
