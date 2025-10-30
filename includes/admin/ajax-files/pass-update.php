<?php
$api_url = EPASSCARD_API_URL."update-pass-template/$pass_uid";
$api_key = get_option('epasscard_api_key', '');

// Fix 1: Ensure settings block is properly included
$settingsBlock = '
    "settings": {
        "id": ' . (!empty($locations_setting_id) ? $locations_setting_id : 'null') . ',
        "is_active": 1,
        "initial_message": "",
        "notification_radius": "' . $notification_radius . '"
    },';


// Decode JSON to PHP array
$rechargeData = json_decode($rechargeField, true);

// Extract the 'name' values
$rechargeData = array_column($rechargeData, 'name');

// Create a comma-separated string
$rechargeField = implode(',', $rechargeData);

$data = '{
    "template": {
        "template_id": ' . $pass_id . ',
        "template_uid": "' . $pass_uid . '",
        "locations": {
            ' . $settingsBlock . '
            "locations": ' . $locationsDataJson . '
        },
        "beacons": {},
        "templateInformation": {
            "id": ' . $pass_id . ',
            "uid": "' . $pass_uid . '",
            "name": "' . $template_info['templateName'] . '",
            "slug": "sample-card",
            "template_data": "' . $primarySettings . '",
            "status": 1,
            "org_id": ' . $organization_id . ',
            "description": "' . $template_info['templateDescription'] . '",
            "pass_org_name": "' . $template_info['organizationName'] . '",
            "pass_limit": ' . $template_info['createdPasses'] . ',
            "nfc": null,
            "google_wallet_status": 0
        },
        "additionFields": ' . $additionalProperties . ',
        "settings": {
            "id": ' . $setting_data['setting_id'] . ',
            "template_id": ' . $pass_id . ',
            "expire_type": "date",
            "expire_data_type": "' . $setting_values['expire_type'] . '",
            "expire_value": "' . ($setting_values['fixed_dynamic_value'] ?? 'Model') . '",
            "audit_log": 1,
            "rechargeable": ' . (filter_var($setting_values['is_rechargeable'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0') . ',
            "transaction_log": "' . (filter_var($setting_values['transaction_log'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0') . '",
            "redeemable": "0",
            "reedem_field": "{}",
            "org_id": ' . $organization_id . ',
            "expire_settings": ' . (filter_var($setting_values['is_expire'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0') . ',
            "recharge_field": "' . $rechargeField . '",
            "card_type": null,
            "reciver_email": "{}",
            "batch_size": null,
            "serial_number_length": 15,
            "serial_number_prefix": null,
            "unique_code_length": 15,
            "unique_code_format": "",
            "unique_code_prefix": "",
            "unique_code_suffix": "",
            "transection_action": ' . $transactionValues . '
        },
        "cardType": "loyalty",
        "google_wallet_status": 0,
        "total_pass": 0
    },
    "design": {
        "primarySettings": {
             "name": "' . $templateName . '",
             "cardType": "' . $cardType . '",
             "organizationName": "",
             "description": "",
             "forgroundColor": "' . $setting_data['textColor'] . '",
             "backgroundColor": "' . $setting_data['bgColor'] . '",
             "labelColor": "' . $setting_data['labelColor'] . '",
             "stripColor": "",
             "expireType": "",
             "expireValue": "",
             "passTypeId": "' . $template_info['certificate'] . '",
             "dateTimeFormat": "' . $setting_values['date_time_format'] . '",
             "shareable": false,
             "googleWalletShareable": "' . $setting_values['sharing_option'] . '"
         },
        "headerFields": ' . $headerFieldsJson . ',
        "backFields": ' . $backFieldsJson . ',
        "auxiliaryFields": [],
         "images": {
             "logo": "' . $setting_data['headerLogo'] . '",
             "icon": "' . $setting_data['headerLogo'] . '",
             "thumbnail": "' . $setting_data['thumbnailUrl'] . '",
             "strip":  "' . $setting_data['thumbnailUrl'] . '",
             "footer": ""
         },
        "primaryFields": [
            {
                 "label": "' . $primary_fields_data['primaryName'] . '",
                 "value": "' . $primary_fields_data['primaryValue'] . '",
                 "valueType": "fixed",
                 "changeMsg": "' . $primary_fields_data['primaryMessage'] . '",
                 "staticValueType": ""
             }
        ],
        "barcode": {
             "format": "' . $barcode_data['barcodeFormat'] . '",
             "barcodeValueType": "",
             "value": "' . $barcode_data['barcodeValue'] . '",
             "altText": "' . $barcode_data['barcodeText'] . '",
             "showValue": "' . filter_var($barcode_data['isBarcodeChecked'], FILTER_VALIDATE_BOOLEAN) . '"
        },
        "secondaryFields": ' . $secondaryFieldsJson . ',
        "googlePassLayout": "Custom",
        "googleDefaultLayout": {
            "loyaltyBalance": "",
            "loyaltyBalanceLabel": "",
            "giftCardBalance": "",
            "giftCardCurrencyCode": ""
        },
        "emailSettings": {
            "subject": "",
            "emailTemplate": "",
            "recipientEmail": ""
        },
        "pushNotificationContent": {
            "bodyForUpdate": ""
        },
        "smsContent": {
            "receiver": "",
            "content": ""
        },
        "status": false
    }
}';

$response = wp_remote_request($api_url, [
    'method'  => 'PUT',
    'headers' => [
        'Content-Type' => 'application/json',
        'x-api-key'    => $api_key,
    ],
    'body'    => $data,
    'timeout' => 60,
]);

if (is_wp_error($response)) {
    wp_send_json_error($response->get_error_message());
} else {
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    wp_send_json_success($data);
}
