<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard-template-wrap">
    <div id="epasscard-template-results">
        <?php
            // Nonce verify
            if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
                wp_die(esc_html__('Security check failed', 'epasscard'));
            }
            $per_page        = 5;
            $epasscard_current_page    = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
            $epasscard_api_key         = get_option('epasscard_api_key', '');
            $epasscard_nonce           = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
            $epasscard_api_url = EPASSCARD_API_URL.'get-pass-templates?page=' . $epasscard_current_page;

            $epasscard_response = wp_remote_get($epasscard_api_url, [
                'headers' => [
                    'x-api-key' => $epasscard_api_key,
                ],
            ]);

            if (is_wp_error($epasscard_response)) {
                echo '<div class="epasscard-notice">API Request Failed</div>';
            } else {
                $epasscard_data = json_decode(wp_remote_retrieve_body($epasscard_response), true);

                if (isset($epasscard_data) && ! empty($epasscard_data['templates']) && is_array($epasscard_data['templates'])) {
                    foreach ($epasscard_data['templates'] as $epasscard_template) {
                        $epasscard_edit_url = add_query_arg([
                            'page'     => 'epasscard',
                            'tab'      => 'update-template',
                            'pass_id'  => $epasscard_template['id'],
                            'pass_uid' => $epasscard_template['uid'],
                            '_wpnonce' => $epasscard_nonce,
                        ], admin_url('admin.php'));
                    ?>
        <div class="epasscard-template">
            <div class="top-part">
                <h4 class="epasscard-title"><?php echo esc_html($epasscard_template['name']); ?></h4>
                <div>
                    <div class="epasscard-dropdown-container">
                        <button class="dropdown-toggle">
                            <a href="<?php echo esc_url($epasscard_edit_url); ?>">Edit</a>
                            <img src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/icons/dropdown-icon.svg'); ?>"
                                alt="Dropdown Icon" />
                        </button>
                        <ul class="dropdown-menu epasscard-hidden">
                            <li class="danger">Delete template</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bottom-part">
                <div class="left-part">
                    <span>Active versions:
                        <span class="app-logo">
                            <img src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/icons/apple-icon.svg'); ?>"
                                class="w-6 h-6" alt="icon">
                            <img src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/icons/android-icon.svg'); ?>"
                                class="w-6 h-6" alt="icon">
                        </span>
                    </span>
                    <div class="epasscard-status">
                        <span>PASSES: <?php echo esc_html($epasscard_template['total_pass']); ?></span>
                        <span>ACTIVE: <?php echo esc_html($epasscard_template['active']); ?></span>
                        <span>INACTIVE: <?php echo esc_html($epasscard_template['in_active']); ?></span>
                    </div>
                    <div class="maximum-passes">Maximum number of passes:
                        <span><?php echo esc_html($epasscard_template['pass_limit']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
                    $epasscard_total_items = $epasscard_data['total']['total_templates'] ?? 0;
                    $epasscard_total_pages = ceil($epasscard_total_items / $per_page);

                    if ($epasscard_total_pages > 1) {
                        echo '<div class="epasscard-pagination">';
                        for ($epasscard_index = 1; $epasscard_index <= $epasscard_total_pages; $epasscard_index++) {
                            echo '<form method="get" style="display:inline-block;">';
                            echo '<input type="hidden" name="page" value="epasscard">';
                            echo '<input type="hidden" name="tab" value="templates">';
                            echo '<input type="hidden" name="_wpnonce" value="' . esc_attr($epasscard_nonce) . '">';
                            echo '<input type="hidden" name="paged" value="' . esc_attr($epasscard_index) . '">';
                            echo '<button type="submit" class="epasscard-page-link ' . ($epasscard_index === $epasscard_current_page ? ' active' : '') . '">' . esc_html($epasscard_index) . '</button>';
                            echo '</form>';
                        }
                        echo '</div>';
                    }

                } else {
                    echo '<div class="epasscard-notice">No templates found or API Request Failed</div>';
                }
            }
        ?>
    </div>
</div>