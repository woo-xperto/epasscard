<?php
// Load the JSON data
$json_data = file_get_contents(EPASSCARD_PLUGIN_DIR . 'includes/JSON/epasscard-more-templates.json');
$templates = json_decode($json_data, true)['customTemplates'];

if (isset($_GET['pass_id'], $_GET['pass_uid'])) {
    if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
        wp_die(esc_html__('Security check failed', 'epasscard'));
    }

    $template_id = isset($_GET['pass_id']) ? sanitize_text_field(wp_unslash($_GET['pass_id'])) : '';
    $uid         = isset($_GET['pass_uid']) ? sanitize_text_field(wp_unslash($_GET['pass_uid'])) : '';
    $passIds     = (isset($template_id) && isset($uid)) ? json_encode(['id' => $template_id, 'uid' => $uid]) : '';
    $api_url     = EPASSCARD_API_URL.'template-details/' . $uid;
    $api_key     = get_option('epasscard_api_key', '');

    $response = wp_remote_get($api_url, [
        'headers' => [
            'x-api-key' => $api_key,
        ],
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error('API Request Failed');
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    //colors info
    $templateName = $data['template_design']['primarySettings']['name'] ?? '';

}

// Modal HTML
echo '
<div id="epasscard_more_cards_modal" class="epasscard-modal" style="display: none;">
    <div class="epasscard-modal-content">
        <span class="epasscard-close-modal">&times;</span>
        <h2>Remaining Templates </h2>
            <div class="epasscard-card-carousel">';
foreach ($templates as $template) {
    $name         = $template['designObj']['primarySettings']['name'];
    $previewImage = $template['previewImage'];
    $bgColor      = $template['designObj']['primarySettings']['backgroundColor'];
    $templateData = htmlspecialchars(json_encode($template), ENT_QUOTES, 'UTF-8');
    $templateName = $templateName ?? "";
    $activeClass  = $templateName === $name ? 'epasscard-active-card' : '';
    echo '
    <div class="epasscard-card-wrapper">
        <div class="epasscard-card-name">' . esc_html($name) . '</div>
        <div class="epasscard-card-item ' . esc_attr($activeClass) . '" data-template="' . esc_attr($templateData) . '">
            <div class="epasscard-card-image-container">
                <img src="' . esc_url($previewImage) . '" alt="' . esc_attr($name) . '" class="epasscard-card-image">
            </div>
        </div>
    </div>';

}

echo '</div>
    </div>
</div>';