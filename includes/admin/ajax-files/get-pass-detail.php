<?php

check_ajax_referer('epasscard_ajax_nonce');

$uid     = isset($_POST['pass_uid']) ? sanitize_text_field(wp_unslash($_POST['pass_uid'])) : '';
$api_url = 'https://api.epasscard.com/api/pass-template/template-details/' . $uid;
$api_key = get_option('epasscard_api_key', '');

$response = wp_remote_get($api_url, [
    'headers' => [
        'x-api-key' => $api_key,
    ],
]);

if (is_wp_error($response)) {
    wp_send_json_error('API Request Failed');
}

$data = json_decode(wp_remote_retrieve_body($response), true);

if (! empty($data)) {
    wp_send_json_success($data);
} else {
    wp_send_json_error('No template found');
}
