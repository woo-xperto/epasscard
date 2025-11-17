<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (isset($_GET['pass_id'], $_GET['pass_uid'])) {
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
        wp_die(esc_html__('Security check failed', 'epasscard'));
    }

    $epasscard_template_id = isset($_GET['pass_id']) ? sanitize_text_field(wp_unslash($_GET['pass_id'])) : '';
    $epasscard_uid = isset($_GET['pass_uid']) ? sanitize_text_field(wp_unslash($_GET['pass_uid'])) : '';
    $epasscard_pass_ids = (isset($epasscard_template_id) && isset($epasscard_uid)) ? json_encode(['id' => $epasscard_template_id, 'uid' => $epasscard_uid]) : '';
    $epasscard_api_url = EPASSCARD_API_URL . 'template-details/' . $epasscard_uid;
    $epasscard_api_key = get_option('epasscard_api_key', '');

    $epasscard_response = wp_remote_get($epasscard_api_url, [
        'headers' => [
            'x-api-key' => $epasscard_api_key,
        ],
    ]);

    if (is_wp_error($epasscard_response)) {
        wp_send_json_error('API Request Failed');
    }

    $epasscard_data = json_decode(wp_remote_retrieve_body($epasscard_response), true);

    $epasscard_template_info = $epasscard_data['templateInformation'];

    $epasscard_name = isset($epasscard_template_info['name']) ? $epasscard_template_info['name'] : '';
    $epasscard_description = isset($epasscard_template_info['description']) ? $epasscard_template_info['description'] : '';
    $epasscard_org_name = isset($epasscard_template_info['pass_org_name']) ? $epasscard_template_info['pass_org_name'] : '';
    $epasscard_pass_limit = isset($epasscard_template_info['pass_limit']) ? $epasscard_template_info['pass_limit'] : '';
    $epasscard_organization_id = isset($epasscard_template_info['org_id']) ? $epasscard_template_info['org_id'] : '';

    $epasscard_pass_ids = (isset($epasscard_template_id) && isset($epasscard_uid))
        ? json_encode([
            'id' => $epasscard_template_id,
            'uid' => $epasscard_uid,
            'org_id' => $epasscard_organization_id
        ])
        : '';

    //Header data
    $epasscard_header_info = $epasscard_data['template_design']['headerFields'];

    //Primary info
    $epasscard_primary_info = $epasscard_data['template_design']['primaryFields'];
    if (!empty($epasscard_primary_info[0])) {
        $epasscard_primary_label = $epasscard_primary_info[0]['label'];
        $epasscard_primary_value = $epasscard_primary_info[0]['value'];
        $epasscard_primary_change_msg = $epasscard_primary_info[0]['changeMsg'];
    }

    //Secondary info
    $epasscard_secondary_info = $epasscard_data['template_design']['secondaryFields'];

    //Barcode info
    $epasscard_barcode_info = $epasscard_data['template_design']['barcode'];

    //Back fields info
    $epasscard_back_fields_info = $epasscard_data['template_design']['backFields'];

    //colors info
    $epasscard_colors_info = $epasscard_data['template_design']['primarySettings'];
    $epasscard_bg_color = isset($epasscard_colors_info['backgroundColor']) ? $epasscard_colors_info['backgroundColor'] : '';
    $epasscard_fg_color = isset($epasscard_colors_info['forgroundColor']) ? $epasscard_colors_info['forgroundColor'] : 'fff';
    $epasscard_label_color = isset($epasscard_colors_info['labelColor']) ? $epasscard_colors_info['labelColor'] : 'fff';
    $epasscard_pass_type_id = isset($epasscard_colors_info['passTypeId']) ? $epasscard_colors_info['passTypeId'] : '';
    $epasscard_template_name = isset($epasscard_colors_info['name']) ? $epasscard_colors_info['name'] : '';

    //images info
    $epasscard_images_info = $epasscard_data['template_design']['images'];
    $epasscard_logo = isset($epasscard_images_info['logo']) ? $epasscard_images_info['logo'] : '';
    $epasscard_icon = isset($epasscard_images_info['icon']) ? $epasscard_images_info['icon'] : '';
    $epasscard_bg_image = isset($epasscard_images_info['strip']) ? $epasscard_images_info['strip'] : '';
    $epasscard_image_dir = 'https://api.epasscard.com/';

    $epasscard_bg_image = $epasscard_bg_image ? (strpos($epasscard_bg_image, 'https') === 0 ? $epasscard_bg_image : $epasscard_image_dir . $epasscard_bg_image) : '#';
    $epasscard_logo = $epasscard_logo ? (strpos($epasscard_logo, 'https') === 0 ? $epasscard_logo : $epasscard_image_dir . $epasscard_logo) : '#';
    $epasscard_icon = $epasscard_icon ? (strpos($epasscard_icon, 'https') === 0 ? $epasscard_icon : $epasscard_image_dir . $epasscard_icon) : '#';

    //location info
    $epasscard_location_settings = isset($epasscard_data['locations']['settings']) ? $epasscard_data['locations']['settings'] : [];
    $epasscard_location_list = isset($epasscard_data['locations']['locations']) ? $epasscard_data['locations']['locations'] : [];
    $epasscard_location_setting_id = isset($epasscard_data['locations']['settings']['id']) ? $epasscard_data['locations']['settings']['id'] : '';
    //Additional properties info
    $epasscard_additional_fields = $epasscard_data['additionFields'];

    //Settings info
    $epasscard_settings_id = isset($epasscard_data['settings']['id']) ? $epasscard_data['settings']['id'] : "";
    $epasscard_settings_info = isset($epasscard_data['settings']) ? $epasscard_data['settings'] : [];
    $epasscard_expire_enabled = isset($epasscard_settings_info['expire_settings']) && $epasscard_settings_info['expire_settings'];
    $epasscard_expire_type = isset($epasscard_settings_info['expire_data_type']) ? $epasscard_settings_info['expire_data_type'] : '';
    $epasscard_expire_value = isset($epasscard_settings_info['expire_value']) ? $epasscard_settings_info['expire_value'] : '';
    $epasscard_rechargeable = isset($epasscard_settings_info['rechargeable']) && $epasscard_settings_info['rechargeable'];
    $epasscard_transaction_log = isset($epasscard_settings_info['transaction_log']) && $epasscard_settings_info['transaction_log'];
    $epasscard_recharge_fields = isset($epasscard_settings_info['recharge_field']) ? explode(',', $epasscard_settings_info['recharge_field']) : [];
    $epasscard_transection_actions = isset($epasscard_settings_info['transection_action']) ? json_decode($epasscard_settings_info['transection_action'], true) : [];

    $epasscard_total_pass = $epasscard_data["total_pass"] ?? "";

}
$epasscard_section_title = isset($_GET['pass_id']) ? 'Update Pass Template' : 'Create Pass Template';

?>
<div id="epass-content-tab">
    <h2><?php echo esc_html($epasscard_section_title); ?></h2>
    <div class="epasscard-tab-bar">
        <button class="epasscard-tab-item tab-button active" data-epasscard-tab="PassInfo">Pass Info</button>
        <button class="epasscard-tab-item tab-button front-fields-button" data-epasscard-tab="FrontFields">Front
            Fields</button>
        <button class="epasscard-tab-item tab-button" data-epasscard-tab="BackFields">Back Fields</button>
        <button class="epasscard-tab-item tab-button" data-epasscard-tab="ColorImages">Color and Images</button>
        <button class="epasscard-tab-item tab-button" data-epasscard-tab="Lockscreen">Lockscreen</button>
        <button class="epasscard-tab-item tab-button" data-epasscard-tab="AdditionalProperties">Additional
            Properties</button>
        <button class="epasscard-tab-item tab-button" data-epasscard-tab="Settings">Settings</button>
    </div>
    <div><button class="create-pass-template epasscard-primary-btn"
            pass-ids="<?php echo isset($epasscard_pass_ids) ? esc_attr($epasscard_pass_ids) : ''; ?>">Save
            Template <span class="epasscard-spinner is-active"></span></button></div>
</div>
<div id="PassInfo" class="epasscard-tab-content default-show">
    <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/pass-info.php'; ?>
</div>

<div id="FrontFields" class="epasscard-tab-content">
    <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/front-field.php'; ?>
</div>

<div id="BackFields" class="epasscard-tab-content">
    <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/back-fields.php'; ?>
</div>

<div id="ColorImages" class="epasscard-tab-content color-images">
    <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/colors-images.php'; ?>
</div>

<div id="Lockscreen" class="epasscard-tab-content">
    <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/locations.php'; ?>
</div>

<div id="AdditionalProperties" class="epasscard-tab-content">
    <div class="create-properties">
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/additional-properties.php'; ?>
    </div>
</div>

<div id="Settings" class="epasscard-tab-content"
    setting_id="<?php echo isset($epasscard_settings_id) ? esc_attr($epasscard_settings_id) : ''; ?>">
    <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/epasscard-setting.php'; ?>
</div>