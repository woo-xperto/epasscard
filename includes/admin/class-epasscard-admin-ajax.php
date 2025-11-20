<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Epasscard_Ajax
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('wp_ajax_Epasscard_connect', [$this, 'Epasscard_connect']);

        //Get card list
        add_action('wp_ajax_Epasscard_templates_callback', [$this, 'Epasscard_templates_callback']);
        add_action('wp_ajax_nopriv_Epasscard_templates_callback', [$this, 'Epasscard_templates_callback']);

        // Create Pass template
        add_action('wp_ajax_Epasscard_create_pass_template', [$this, 'Epasscard_create_pass_template']);
        add_action('wp_ajax_nopriv_Epasscard_create_pass_template', [$this, 'Epasscard_create_pass_template']);

        //Get location
        add_action('wp_ajax_Epasscard_get_location', [$this, 'Epasscard_get_location']);
        add_action('wp_ajax_nopriv_Epasscard_get_location', [$this, 'Epasscard_get_location']);

        // Download and Activate required plugins
        add_action('wp_ajax_Epasscard_install_required_plugin', [$this, 'Epasscard_install_required_plugin']);
        add_action('wp_ajax_nopriv_Epasscard_install_required_plugin', [$this, 'Epasscard_install_required_plugin']);
    }

    /**
     * Handle the connect AJAX request
     */
    public function Epasscard_connect()
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

            // Save the settings
            $api_key_saved = update_option('epasscard_api_key', $api_key);

            if ($api_key_saved) {
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
    public function Epasscard_templates_callback()
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
    public function Epasscard_create_pass_template()
    {
        // Verify nonce for security
        check_ajax_referer('epasscard_ajax_nonce');

        // Collect all POST data into a single array
        $template_info = isset($_POST['template_info']) ? filter_input(INPUT_POST, 'template_info', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $barcode_data = isset($_POST['barcode_data']) ? filter_input(INPUT_POST, 'barcode_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $header_data = isset($_POST['header_data']) ? filter_input(INPUT_POST, 'header_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $primary_fields_data = isset($_POST['primary_fields_data']) ? filter_input(INPUT_POST, 'primary_fields_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $secondary_data = isset($_POST['secondary_data']) ? filter_input(INPUT_POST, 'secondary_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $back_field_data = isset($_POST['back_field_data']) ? filter_input(INPUT_POST, 'back_field_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $setting_data = isset($_POST['setting_data']) ? filter_input(INPUT_POST, 'setting_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $locations_data = filter_input(INPUT_POST, 'locations_data', FILTER_DEFAULT);
        $locations_setting_id = isset($_POST['locations_setting_id']) ? sanitize_text_field(wp_unslash($_POST['locations_setting_id'])) : "";
        $notification_radius = isset($_POST['notification_radius']) ? sanitize_text_field(wp_unslash($_POST['notification_radius'])) : "";
        $setting_values = isset($_POST['setting_values']) ? filter_input(INPUT_POST, 'setting_values', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        //$additional_properties = isset($_POST['additional_properties']) ? filter_input(INPUT_POST, 'additional_properties', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $additional_properties = filter_input(INPUT_POST, 'additional_properties', FILTER_DEFAULT);
        $auxiliary_properties = filter_input(INPUT_POST, 'auxiliary_properties', FILTER_DEFAULT);
        $recharge_field = filter_input(INPUT_POST, 'recharge_field', FILTER_DEFAULT);
        $transaction_values = filter_input(INPUT_POST, 'transaction_values', FILTER_DEFAULT);
        $pass_ids = isset($_POST['pass_ids']) ? filter_input(INPUT_POST, 'pass_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];
        $template_data = isset($_POST['template_data']) ? filter_input(INPUT_POST, 'template_data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY): [];


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
        $this->Epasscard_make_pass_template_request($data);

        wp_die();
    }

    // Pass create method
    public function Epasscard_make_pass_template_request(array $data)
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
    public function Epasscard_get_location()
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
    public function Epasscard_install_required_plugin()
    {
        require EPASSCARD_PLUGIN_DIR . 'includes/admin/ajax-files/install-required-plugins.php';
    }

}
