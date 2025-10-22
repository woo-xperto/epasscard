<?php

// var_dump($setting_data);
// die;
$api_url = 'https://api.epasscard.com/api/pass-template/create';
$api_key = get_option('epasscard_api_key', '');
$body    = '{
    "passSettings": {
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
            "showValue": ' . (filter_var($barcode_data['isBarcodeChecked'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false') . '
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
    },
    "templateInfo": {
        "name": "' . $template_info['templateName'] . '",
        "description": "' . $template_info['templateDescription'] . '",
        "organization": "' . $template_info['organizationName'] . '",
        "numOfPass": "' . $template_info['createdPasses'] . '" ,
        "passTypeId": "' . $template_info['certificate'] . '"
    },
    "locations": {
        "locationStatus": false,
        "locations": ' . $locationsDataJson . ',
        "initialMsg": "",
        "locationRadius": "' . $notification_radius . '"
    },
    "additionalFields":' . $additionalProperties . ',
    "advancedSettings": {
        "isExpire": ' . (filter_var($setting_values['is_expire'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false') . ',
        "expireType": "date",
        "expireValueType": "' . $setting_values['expire_type'] . '",
        "expireValue": "' . ($setting_values['fixed_dynamic_value'] ?? 'Model') . '",
        "auditLog": true,
        "isRechargeable": ' . (filter_var($setting_values['is_rechargeable'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false') . ',
        "transectionLog": ' . (filter_var($setting_values['transaction_log'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false') . ',
        "isRedeemAble": false,
        "redeemField": "{}",
        "rechargeField": ' . $rechargeField . ',
        "recipentEmail": "{}",
        "uniqueCodeLength": 15,
        "uniqueCodePrefix": "",
        "uniqueCodeSuffix": "",
        "uniqueCodeFormat": "",
        "transectionActions": ' . $transactionValues . '
    },
    "org_id": null
}';

$response = wp_remote_post($api_url, [
    'headers' => [
        'Content-Type' => 'application/json',
        'x-api-key'    => $api_key,
    ],
    'body'    => $body,
    'timeout' => 30,
]);

if (is_wp_error($response)) {
    wp_send_json_error($response->get_error_message());
} else {
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    wp_send_json_success($data);
}