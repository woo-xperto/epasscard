<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Load the JSON data
$epasscard_json_data = file_get_contents(EPASSCARD_PLUGIN_DIR . 'includes/JSON/epasscard-more-templates.json');
$epasscard_templates = json_decode($epasscard_json_data, true)['customTemplates'];

if (isset($_GET['pass_id'], $_GET['pass_uid'])) {
    if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
        wp_die(esc_html__('Security check failed', 'epasscard'));
    }

    $epasscard_template_id = isset($_GET['pass_id']) ? sanitize_text_field(wp_unslash($_GET['pass_id'])) : '';
    $epasscard_uid         = isset($_GET['pass_uid']) ? sanitize_text_field(wp_unslash($_GET['pass_uid'])) : '';
    //$passIds     = (isset($epasscard_template_id) && isset($epasscard_uid)) ? json_encode(['id' => $epasscard_template_id, 'uid' => $epasscard_uid]) : '';
    $epasscard_api_url     = EPASSCARD_API_URL.'template-details/' . $epasscard_uid;
    $epasscard_api_key     = get_option('epasscard_api_key', '');

    $epasscard_response = wp_remote_get($epasscard_api_url, [
        'headers' => [
            'x-api-key' => $epasscard_api_key,
        ],
    ]);

    if (is_wp_error($epasscard_response)) {
        wp_send_json_error('API Request Failed');
    }

    $epasscard_data = json_decode(wp_remote_retrieve_body($epasscard_response), true);

    //colors info
    $epasscard_template_name = $epasscard_data['template_design']['primarySettings']['name'] ?? '';

}

// Modal HTML
echo '
<div id="epasscard_more_cards_modal" class="epasscard-modal" style="display: none;">
    <div class="epasscard-modal-content">
        <span class="epasscard-close-modal">&times;</span>
        <h2>Remaining Templates </h2>
            <div class="epasscard-card-carousel">';
foreach ($epasscard_templates as $epasscard_template) {
    $epasscard_name         = $epasscard_template['designObj']['primarySettings']['name'];
    $epasscard_preview_image = $epasscard_template['previewImage'];
    //$bgColor      = $epasscard_template['designObj']['primarySettings']['backgroundColor'];
    $epasscard_template_data = htmlspecialchars(json_encode($epasscard_template), ENT_QUOTES, 'UTF-8');
    $epasscard_template_name = $epasscard_template_name ?? "";
    $epasscard_active_class  = $epasscard_template_name === $epasscard_name ? 'epasscard-active-card' : '';
    echo '
    <div class="epasscard-card-wrapper">
        <div class="epasscard-card-name">' . esc_html($epasscard_name) . '</div>
        <div class="epasscard-card-item ' . esc_attr($epasscard_active_class) . '" data-template="' . esc_attr($epasscard_template_data) . '">
            <div class="epasscard-card-image-container">
                <img src="' . esc_url($epasscard_preview_image) . '" alt="' . esc_attr($epasscard_name) . '" class="epasscard-card-image">
            </div>
        </div>
    </div>';

}

echo '</div>
    </div>
</div>';