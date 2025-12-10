<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (isset($_GET['pass_id'], $_GET['pass_uid'])) {
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
        wp_die(esc_html__('Security check failed', 'epasscard'));
    }

    $epassc_template_id = isset($_GET['pass_id']) ? sanitize_text_field(wp_unslash($_GET['pass_id'])) : '';
    $epassc_uid = isset($_GET['pass_uid']) ? sanitize_text_field(wp_unslash($_GET['pass_uid'])) : '';
    $epassc_pass_ids = (isset($epassc_template_id) && isset($epassc_uid)) ? json_encode(['id' => $epassc_template_id, 'uid' => $epassc_uid]) : '';
    $EPASSC_API_URL = EPASSC_API_URL . 'template-details/' . $epassc_uid;
    $epassc_api_key = EPASSC_API_KEY;

    $epassc_response = wp_remote_get($EPASSC_API_URL, [
        'headers' => [
            'x-api-key' => $epassc_api_key,
        ],
    ]);

    if (is_wp_error($epassc_response)) {
        wp_send_json_error('API Request Failed');
    }

    $epassc_data = json_decode(wp_remote_retrieve_body($epassc_response), true);
    $epassc_template_info = $epassc_data['templateInformation'];
    $epassc_name = isset($epassc_template_info['name']) ? $epassc_template_info['name'] : '';
    $epassc_description = isset($epassc_template_info['description']) ? $epassc_template_info['description'] : '';
    $epassc_org_name = isset($epassc_template_info['pass_org_name']) ? $epassc_template_info['pass_org_name'] : '';
    $epassc_pass_limit = isset($epassc_template_info['pass_limit']) ? $epassc_template_info['pass_limit'] : '';
    $epassc_organization_id = isset($epassc_template_info['org_id']) ? $epassc_template_info['org_id'] : '';

    $epassc_pass_ids = (isset($epassc_template_id) && isset($epassc_uid))
        ? json_encode([
            'id' => $epassc_template_id,
            'uid' => $epassc_uid,
            'org_id' => $epassc_organization_id
        ])
        : '';

    //Header data
    $epassc_header_info = $epassc_data['template_design']['headerFields'];

    //Primary info
    $epassc_primary_info = $epassc_data['template_design']['primaryFields'];
    if (!empty($epassc_primary_info[0])) {
        $epassc_primary_label = $epassc_primary_info[0]['label'];
        $epassc_primary_value = $epassc_primary_info[0]['value'];
        $epassc_primary_change_msg = $epassc_primary_info[0]['changeMsg'];
    }

    //Secondary info
    $epassc_secondary_info = $epassc_data['template_design']['secondaryFields'];

    //Barcode info
    $epassc_barcode_info = $epassc_data['template_design']['barcode'];

    //Back fields info
    $epassc_back_fields_info = $epassc_data['template_design']['backFields'];

    //Auxiliary info
    $epassc_auxiliary_info = $epassc_data['template_design']['auxiliaryFields'];

    //colors info
    $epassc_colors_info = $epassc_data['template_design']['primarySettings'];
    $epassc_bg_color = isset($epassc_colors_info['backgroundColor']) ? $epassc_colors_info['backgroundColor'] : '';
    $epassc_fg_color = isset($epassc_colors_info['forgroundColor']) ? $epassc_colors_info['forgroundColor'] : 'fff';
    $epassc_label_color = isset($epassc_colors_info['labelColor']) ? $epassc_colors_info['labelColor'] : 'fff';
    $epassc_pass_type_id = isset($epassc_colors_info['passTypeId']) ? $epassc_colors_info['passTypeId'] : '';
    $epassc_template_name = isset($epassc_colors_info['name']) ? $epassc_colors_info['name'] : '';
    $epassc_card_type = isset($epassc_colors_info['cardType']) ? $epassc_colors_info['cardType'] : '';

    //images info
    $epassc_images_info = $epassc_data['template_design']['images'];
    $epassc_logo = isset($epassc_images_info['logo']) ? $epassc_images_info['logo'] : '';
    $epassc_icon = isset($epassc_images_info['icon']) ? $epassc_images_info['icon'] : '';
    $epassc_bg_image = isset($epassc_images_info['strip']) ? $epassc_images_info['strip'] : '';
    $epassc_image_dir = 'https://api.epasscard.com/';

    $epassc_bg_image = $epassc_bg_image ? (strpos($epassc_bg_image, 'https') === 0 ? $epassc_bg_image : $epassc_image_dir . $epassc_bg_image) : '#';
    $epassc_logo = $epassc_logo ? (strpos($epassc_logo, 'https') === 0 ? $epassc_logo : $epassc_image_dir . $epassc_logo) : '#';
    $epassc_icon = $epassc_icon ? (strpos($epassc_icon, 'https') === 0 ? $epassc_icon : $epassc_image_dir . $epassc_icon) : '#';

    //location info
    $epassc_location_settings = isset($epassc_data['locations']['settings']) ? $epassc_data['locations']['settings'] : [];
    $epassc_location_list = isset($epassc_data['locations']['locations']) ? $epassc_data['locations']['locations'] : [];
    $epassc_location_setting_id = isset($epassc_data['locations']['settings']['id']) ? $epassc_data['locations']['settings']['id'] : '';
    //Additional properties info
    $epassc_additional_fields = $epassc_data['additionFields'];

    //Settings info
    $epassc_settings_id = isset($epassc_data['settings']['id']) ? $epassc_data['settings']['id'] : "";
    $epassc_settings_info = isset($epassc_data['settings']) ? $epassc_data['settings'] : [];
    $epassc_expire_enabled = isset($epassc_settings_info['expire_settings']) && $epassc_settings_info['expire_settings'];
    $epassc_expire_type = isset($epassc_settings_info['expire_data_type']) ? $epassc_settings_info['expire_data_type'] : '';
    $epassc_expire_value = isset($epassc_settings_info['expire_value']) ? $epassc_settings_info['expire_value'] : '';
    $epassc_rechargeable = isset($epassc_settings_info['rechargeable']) && $epassc_settings_info['rechargeable'];
    $epassc_transaction_log = isset($epassc_settings_info['transaction_log']) && $epassc_settings_info['transaction_log'];
    $epassc_recharge_fields = isset($epassc_settings_info['recharge_field']) ? explode(',', $epassc_settings_info['recharge_field']) : [];
    $epassc_transection_actions = isset($epassc_settings_info['transection_action']) ? json_decode($epassc_settings_info['transection_action'], true) : [];

    $epassc_total_pass = $epassc_data["total_pass"] ?? "";

}
$epassc_section_title = isset($_GET['pass_id']) ? 'Update Pass Template' : 'Create Pass Template';

?>
<div id="epass-content-tab">
    <h2><?php echo esc_html($epassc_section_title); ?></h2>
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
            pass-ids="<?php echo isset($epassc_pass_ids) ? esc_attr($epassc_pass_ids) : ''; ?>">Save
            Template <span class="epasscard-spinner is-active"></span></button></div>
</div>
<div id="PassInfo" class="epasscard-tab-content default-show">
    <?php require_once EPASSC_PLUGIN_DIR . 'includes/templates/pass-info.php'; ?>
</div>

<div id="FrontFields" class="epasscard-tab-content">
    <?php require_once EPASSC_PLUGIN_DIR . 'includes/templates/front-field.php'; ?>
</div>

<div id="BackFields" class="epasscard-tab-content">
    <?php require_once EPASSC_PLUGIN_DIR . 'includes/templates/back-fields.php'; ?>
</div>

<div id="ColorImages" class="epasscard-tab-content color-images">
    <?php include EPASSC_PLUGIN_DIR . 'includes/templates/colors-images.php'; ?>
</div>

<div id="Lockscreen" class="epasscard-tab-content">
    <?php include EPASSC_PLUGIN_DIR . 'includes/templates/locations.php'; ?>
</div>

<div id="AdditionalProperties" class="epasscard-tab-content">
    <div class="create-properties">
        <?php include EPASSC_PLUGIN_DIR . 'includes/templates/additional-properties.php'; ?>
    </div>
</div>

<div id="Settings" class="epasscard-tab-content"
    setting_id="<?php echo isset($epassc_settings_id) ? esc_attr($epassc_settings_id) : ''; ?>">
    <?php include EPASSC_PLUGIN_DIR . 'includes/templates/epasscard-setting.php'; ?>
</div>