<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Load the JSON data
$epassc_json_data = file_get_contents(EPASSC_PLUGIN_DIR . 'includes/JSON/epasscard-more-templates.json');
$epassc_templates = json_decode($epassc_json_data, true)['customTemplates'];

if (isset($_GET['pass_id'], $_GET['pass_uid'])) {
    if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
        wp_die(esc_html__('Security check failed', 'epasscard'));
    }

    $epassc_template_id = isset($_GET['pass_id']) ? sanitize_text_field(wp_unslash($_GET['pass_id'])) : '';
    $epassc_uid         = isset($_GET['pass_uid']) ? sanitize_text_field(wp_unslash($_GET['pass_uid'])) : '';
    //$passIds     = (isset($epassc_template_id) && isset($epassc_uid)) ? json_encode(['id' => $epassc_template_id, 'uid' => $epassc_uid]) : '';
    $EPASSC_API_URL     = EPASSC_API_URL.'template-details/' . $epassc_uid;
    $epassc_api_key     = EPASSC_API_KEY;

    $epassc_response = wp_remote_get($EPASSC_API_URL, [
        'headers' => [
            'x-api-key' => $epassc_api_key,
        ],
    ]);

    if (is_wp_error($epassc_response)) {
        wp_send_json_error('API Request Failed');
    }

    $epassc_data = json_decode(wp_remote_retrieve_body($epassc_response), true);

    //colors info
    $epassc_template_name = $epassc_data['template_design']['primarySettings']['name'] ?? '';

}

// Modal HTML
echo '
<div id="epasscard_more_cards_modal" class="epasscard-modal" style="display: none;">
    <div class="epasscard-modal-content">
        <span class="epasscard-close-modal">&times;</span>
        <h2>Remaining Templates </h2>
            <div class="epasscard-card-carousel">';
foreach ($epassc_templates as $epassc_template) {
    $epassc_name         = $epassc_template['designObj']['primarySettings']['name'];
    $epassc_preview_image = $epassc_template['previewImage'];
    //$bgColor      = $epassc_template['designObj']['primarySettings']['backgroundColor'];
    $epassc_template_data = htmlspecialchars(json_encode($epassc_template), ENT_QUOTES, 'UTF-8');
    $epassc_template_name = $epassc_template_name ?? "";
    $epassc_active_class  = $epassc_template_name === $epassc_name ? 'epasscard-active-card' : '';
    echo '
    <div class="epasscard-card-wrapper">
        <div class="epasscard-card-name">' . esc_html($epassc_name) . '</div>
        <div class="epasscard-card-item ' . esc_attr($epassc_active_class) . '" data-template="' . esc_attr($epassc_template_data) . '">
            <div class="epasscard-card-image-container">
                <img src="' . esc_url($epassc_preview_image) . '" alt="' . esc_attr($epassc_name) . '" class="epasscard-card-image">
            </div>
        </div>
    </div>';

}

echo '</div>
    </div>
</div>';