<?php
class Epasscard_Ajax
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('wp_ajax_epasscard_connect', [$this, 'handle_connect']);

        //Get card list
        add_action('wp_ajax_load_epasscard_templates', [$this, 'load_epasscard_templates_callback']);
        add_action('wp_ajax_nopriv_load_epasscard_templates', [$this, 'load_epasscard_templates_callback']);

        // Create Pass template
        add_action('wp_ajax_create_pass_template', [$this, 'create_pass_template']);
        add_action('wp_ajax_nopriv_create_pass_template', [$this, 'create_pass_template']);

        //Get location
        add_action('wp_ajax_get_epasscard_location', [$this, 'get_epasscard_location']);
        add_action('wp_ajax_nopriv_get_epasscard_location', [$this, 'get_epasscard_location']);

        // Download and Activate required plugins
        add_action('wp_ajax_epass_install_required_plugin', [$this, 'epass_install_required_plugin']);
        add_action('wp_ajax_nopriv_epass_install_required_plugin', [$this, 'epass_install_required_plugin']);
    }

    /**
     * Handle the connect AJAX request
     */
    public function handle_connect()
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
        $account_email = isset($_POST['account_email']) ? sanitize_email(wp_unslash($_POST['account_email'])) : '';

        // Validate fields
        $errors = [];

        if (empty($api_key)) {
            $errors['api_key'] = __('API key is required', 'epasscard');
        }
        if (empty($account_email) || !is_email($account_email)) {
            $errors['account_email'] = __('A valid email address is required', 'epasscard');
        }

        if (!empty($errors)) {
            $response['errors'] = $errors;
            wp_send_json($response, 400);
            return;
        }

        try {

            // Save the settings
            $api_key_saved = update_option('epasscard_api_key', $api_key);
            $account_email_saved = update_option('epasscard_account_email', $account_email);

            if ($api_key_saved || $account_email_saved) {
                $response['success'] = true;
                $response['message'] = __('Settings saved successfully!', 'epasscard');
            } else {
                $response['message'] = __('Settings unchanged. The new values are identical to the existing ones.', 'epasscard');
            }
        } catch (Exception $e) {
            $response['message'] = __('An error occurred while saving the settings: ', 'epasscard') . esc_html($e->getMessage());
            wp_send_json($response, 500);
            return;
        }

        wp_send_json($response);
    }

    // Get pass card list
    public function load_epasscard_templates_callback()
    {
        check_ajax_referer('epasscard_ajax_nonce');
        if (isset($_POST['request_identifier'])) {

            if ($_POST['request_identifier'] == 'epass-search-location-details') {

                check_ajax_referer('epasscard_ajax_nonce');

                $place_id = isset($_POST['place_id']) ? sanitize_text_field(wp_unslash($_POST['place_id'])) : '';

                $api_key = get_option('epasscard_api_key', '');

                $api_url = 'https://api.epasscard.com/api/google/geocode/' . urlencode($place_id);
                $args = [
                    'headers' => [
                        'x-api-key' => $api_key,
                    ],
                    'timeout' => 15, // Added timeout for better practice
                ];

                $response = wp_remote_get($api_url, $args);

                // Check for errors
                if (is_wp_error($response)) {
                    // Handle error
                    $error_message = $response->get_error_message();
                    // Log or display error
                } else {
                    $body = wp_remote_retrieve_body($response);
                    $data = json_decode($body, true);
                    wp_send_json_success($data);
                }

            }

        }
        wp_die();
    }

    // Create pass template
    public function create_pass_template()
    {
        // Verify nonce for security
        check_ajax_referer('epasscard_ajax_nonce');

        // Collect all POST data into a single array
        // phpcs:ignore
        $template_info = isset($_POST['template_info']) ? $_POST['template_info'] : [];

        // phpcs:ignore
        $barcode_data = isset($_POST['barcode_data']) ? $_POST['barcode_data'] : [];

        // phpcs:ignore
        $header_data = isset($_POST['header_data']) ? $_POST['header_data'] : [];

        // phpcs:ignore
        $primary_fields_data = isset($_POST['primary_fields_data']) ? $_POST['primary_fields_data'] : [];

        // phpcs:ignore
        $secondary_data = isset($_POST['secondary_data']) ? $_POST['secondary_data'] : [];

        // phpcs:ignore
        $back_field_data = isset($_POST['back_field_data']) ? $_POST['back_field_data'] : [];

        // phpcs:ignore
        $setting_data = isset($_POST['setting_data']) ? $_POST['setting_data'] : [];

        // phpcs:ignore
        $locations_data = isset($_POST['locations_data']) ? $_POST['locations_data'] : [];

        // phpcs:ignore
        $locations_setting_id = isset($_POST['locations_setting_id']) ? $_POST['locations_setting_id'] : "";

        // phpcs:ignore
        $notification_radius = isset($_POST['notification_radius']) ? $_POST['notification_radius'] : "";

        // phpcs:ignore
        $setting_values = isset($_POST['setting_values']) ? $_POST['setting_values'] : "";

        // phpcs:ignore
        $additional_properties = isset($_POST['additional_properties']) ? $_POST['additional_properties'] : [];

        // phpcs:ignore
        $recharge_field = isset($_POST['recharge_field']) ? $_POST['recharge_field'] : [];

        // phpcs:ignore
        $transaction_values = isset($_POST['transaction_values']) ? $_POST['transaction_values'] : [];

        // phpcs:ignore
        $pass_ids = isset($_POST['pass_ids']) ? $_POST['pass_ids'] : [];

        // phpcs:ignore
        $template_data = isset($_POST['template_data']) ? $_POST['template_data'] : [];

        $data = [
            'template_info' => $template_info,
            'barcode_data' => $barcode_data,
            'header_data' => $header_data,
            'primary_fields_data' => $primary_fields_data,
            'secondary_data' => $secondary_data,
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
        if (!empty($data['secondary_data']['secondaryLabels']) && !empty($data['secondary_data']['secondaryValues'])) {
            $count = min(count($data['secondary_data']['secondaryLabels']), count($data['secondary_data']['secondaryValues']));
            for ($i = 0; $i < $count; $i++) {
                $secondaryFields[] = [
                    "label" => $data['secondary_data']['secondaryLabels'][$i],
                    "value" => $data['secondary_data']['secondaryValues'][$i],
                    "valueType" => "fixed",
                    "changeMsg" => $data['secondary_data']['secondaryLabels'][$i] . " is updated",
                    "staticValueType" => "text",
                ];
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

        // Add processed fields to the data array
        $data['headerFields'] = $headerFields;
        $data['secondaryFields'] = $secondaryFields;
        $data['backFields'] = $backFields;

        // Call the template creation function with the full data array
        $result = $this->make_pass_template_request($data);

        if ($result === false) {
            wp_send_json_error([
                'message' => 'Something went wrong, please try again.',
            ]);
        } else {
            wp_send_json_success([
                'data' => $result,
            ]);
        }

        wp_die();
    }

    // Pass create method
    public function make_pass_template_request(array $data)
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
        //$locationsDataJson   = json_encode($data['locations_data'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $locationsDataJson = stripslashes($data['locations_data']);

        // Clean up stringified JSON fields
        $additionalProperties = str_replace('\\', '', $data['additional_properties']);

        $rechargeField = str_replace('\\', '', $data['recharge_field']);
        $transactionValues = str_replace('\\', '', $data['transaction_values']);

        // Extract IDs
        $pass_id = $data['pass_ids']['id'];
        $pass_uid = $data['pass_ids']['uid'];
        $organization_id = $data['pass_ids']['org_id'];

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
            require EPASSCARD_PLUGIN_DIR . 'includes/admin/ajax-files/pass-update.php';
        } else {
            require EPASSCARD_PLUGIN_DIR . 'includes/admin/ajax-files/pass-create.php';
        }
    }
    
    //Get location
    public function get_epasscard_location()
    {
        check_ajax_referer('epasscard_ajax_nonce');

        $place_name = isset($_GET['search']) ? sanitize_text_field(wp_unslash($_GET['search'])) : '';
        $api_key = get_option('epasscard_api_key', '');

        if (empty($place_name)) {
            wp_send_json_error('Place name is required');
        }

        // Use the dynamic place name in the API URL
        $api_url = 'https://api.epasscard.com/api/google/places/' . urlencode($place_name);

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

    // Install required plugin
    public function epass_install_required_plugin()
    {
        require EPASSCARD_PLUGIN_DIR . 'includes/admin/ajax-files/install-required-plugins.php';
    }

}
