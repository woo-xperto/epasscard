<?php
    if (isset($_GET['pass_id'], $_GET['pass_uid'])) {
        if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
            wp_die(esc_html__('Security check failed', 'epasscard'));
        }

        $template_id = isset($_GET['pass_id']) ? sanitize_text_field(wp_unslash($_GET['pass_id'])) : '';
        $uid         = isset($_GET['pass_uid']) ? sanitize_text_field(wp_unslash($_GET['pass_uid'])) : '';
        $passIds     = (isset($template_id) && isset($uid)) ? json_encode(['id' => $template_id, 'uid' => $uid]) : '';
        $api_url     = 'https://api.epasscard.com/api/pass-template/template-details/' . $uid;
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

        $templateInfo = $data['templateInformation'];
        $name         = isset($templateInfo['name']) ? $templateInfo['name'] : '';
        $description  = isset($templateInfo['description']) ? $templateInfo['description'] : '';
        $passOrgName  = isset($templateInfo['pass_org_name']) ? $templateInfo['pass_org_name'] : '';
        $passLimit    = isset($templateInfo['pass_limit']) ? $templateInfo['pass_limit'] : '';

        //Header data
        $headerInfo = $data['template_design']['headerFields'];

        //Primary info
        $primaryInfo = $data['template_design']['primaryFields'];
        if (! empty($primaryInfo[0])) {
            $primaryLabel     = $primaryInfo[0]['label'];
            $primaryValue     = $primaryInfo[0]['value'];
            $primaryChangeMsg = $primaryInfo[0]['changeMsg'];
        }

        //Secondary info
        $secondaryInfo = $data['template_design']['secondaryFields'];

        //Barcode info
        $barcodeInfo = $data['template_design']['barcode'];

        //Back fields info
        $backFieldsInfo = $data['template_design']['backFields'];

        //colors info
        $colorsInfo   = $data['template_design']['primarySettings'];
        $bgColor      = isset($colorsInfo['backgroundColor']) ? $colorsInfo['backgroundColor'] : '';
        $fgColor      = isset($colorsInfo['forgroundColor']) ? $colorsInfo['forgroundColor'] : 'fff';
        $labelColor   = isset($colorsInfo['labelColor']) ? $colorsInfo['labelColor'] : 'fff';
        $passTypeId   = isset($colorsInfo['passTypeId']) ? $colorsInfo['passTypeId'] : '';
        $templateName = isset($colorsInfo['name']) ? $colorsInfo['name'] : '';

        //images info
        $imagesInfo = $data['template_design']['images'];
        $logo       = isset($imagesInfo['logo']) ? $imagesInfo['logo'] : '';
        $icon       = isset($imagesInfo['icon']) ? $imagesInfo['icon'] : '';
        $bgImage    = isset($imagesInfo['strip']) ? $imagesInfo['strip'] : '';
        $imageDir   = 'https://api.epasscard.com/';

        $bgImage = $bgImage ? (strpos($bgImage, 'https') === 0 ? $bgImage : $imageDir . $bgImage) : '#';
        $logo    = $logo ? (strpos($logo, 'https') === 0 ? $logo : $imageDir . $logo) : '#';
        $icon    = $icon ? (strpos($icon, 'https') === 0 ? $icon : $imageDir . $icon) : '#';

        //location info
        $locationSettings  = isset($data['locations']['settings']) ? $data['locations']['settings'] : [];
        $locationList      = isset($data['locations']['locations']) ? $data['locations']['locations'] : [];
        $locationSettingId = isset($data['locations']['settings']['id']) ? $data['locations']['settings']['id'] : '';
        //Additional properties info
        $additionalFields = $data['additionFields'];

        //Settings info
        $settingsId         = isset($data['settings']['id']) ? $data['settings']['id'] : "";
        $settingsInfo       = isset($data['settings']) ? $data['settings'] : [];
        $expireEnabled      = isset($settingsInfo['expire_settings']) && $settingsInfo['expire_settings'];
        $expireType         = isset($settingsInfo['expire_data_type']) ? $settingsInfo['expire_data_type'] : '';
        $expireValue        = isset($settingsInfo['expire_value']) ? $settingsInfo['expire_value'] : '';
        $rechargeable       = isset($settingsInfo['rechargeable']) && $settingsInfo['rechargeable'];
        $transactionLog     = isset($settingsInfo['transaction_log']) && $settingsInfo['transaction_log'];
        $rechargeFields     = isset($settingsInfo['recharge_field']) ? explode(',', $settingsInfo['recharge_field']) : [];
        $transectionActions = isset($settingsInfo['transection_action']) ? json_decode($settingsInfo['transection_action'], true) : [];

        $total_pass = $data["total_pass"] ?? "";

    }
    $sectionTitle = isset($_GET['pass_id']) ? 'Update Pass Template' : 'Create Pass Template';
?>
<div id="epass-content-tab">
    <h2><?php echo esc_html($sectionTitle); ?></h2>
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
            pass-ids="<?php echo isset($passIds) ? esc_attr($passIds) : ''; ?>">Save
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
    setting_id="<?php echo isset($settingsId) ? esc_attr($settingsId) : ''; ?>">
    <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/epasscard-setting.php'; ?>
</div>