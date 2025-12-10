<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Load the JSON data
$epassc_json_data  = file_get_contents(EPASSC_PLUGIN_DIR . 'includes/JSON/epasscard-templates.json');
$epassc_templates = json_decode($epassc_json_data, true)['predefinedTemplates'] ?? [];

// Only declare the function if it doesn't already exist
if (! function_exists('Epasscard_generate_card')) {
    function Epasscard_generate_card($template, $index, $templateName)
    {
        $name         = isset($template['designObj']['primarySettings']['name']) ? esc_html($template['designObj']['primarySettings']['name']) : '';
        $previewImage = isset($template['previewImage']) ? esc_url($template['previewImage']) : '';
        $templateData = esc_attr(json_encode($template, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT));
        $activeClass  = ($index === 0) ? 'epasscard-active-card' : '';
        $indexAttr    = esc_attr($index);
        // Determine active class based on index and name match
        $activeClass = $templateName === $name ? 'epasscard-active-card' : '';
        return '
        <div class="epasscard-card-wrapper">
            <div class="epasscard-card-name">' . $name . '</div>
            <div class="epasscard-card-item ' . esc_attr($activeClass) . '" data-index="' . $indexAttr . '" data-template="' . $templateData . '">
                <div class="epasscard-card-image-container">
                    <img src="' . $previewImage . '" alt="' . esc_attr($name) . '" class="epasscard-card-image">
                </div>
            </div>
        </div>';
    }
}
// Generate the carousel
echo '<div class="epasscard-card-carousel">';

foreach ($epassc_templates as $epassc_index => $epassc_template) {
    $epassc_template_name = $epassc_template_name ?? "";
    print wp_kses_post(Epasscard_generate_card($epassc_template, $epassc_index, $epassc_template_name));
}

// Add "More cards" item
echo '
<div class="epasscard-card-wrapper epasscard-show-templates">
    <div class="epasscard-card-name">' . esc_html__('More cards', 'epasscard') . '</div>
    <div class="epasscard-card-item">
        <div class="epasscard-card-image-container epasscard-more-cards">
            <img src="' . esc_url(EPASSC_PLUGIN_URL . 'assets/img/icons/plus.svg') . '" alt="' . esc_attr__('More cards', 'epasscard') . '">
        </div>
    </div>
</div>';

echo '</div>';