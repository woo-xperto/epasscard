<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard-template-wrap">
    <div id="epasscard-template-results">
        <?php
            // Nonce verify
            if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
                wp_die(esc_html__('Security check failed', 'epasscard'));
            }
            $per_page        = 5;
            $epassc_current_page    = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
            $epassc_nonce           = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
            $EPASSC_API_URL = EPASSC_API_URL.'get-pass-templates?page=' . $epassc_current_page;

            $epassc_response = wp_remote_get($EPASSC_API_URL, [
                'headers' => [
                    'x-api-key' => EPASSC_API_KEY,
                ],
            ]);

            if (is_wp_error($epassc_response)) {
                echo '<div class="epasscard-notice">API Request Failed</div>';
            } else {
                $epassc_data = json_decode(wp_remote_retrieve_body($epassc_response), true);

                if (isset($epassc_data) && ! empty($epassc_data['templates']) && is_array($epassc_data['templates'])) {
                    foreach ($epassc_data['templates'] as $epassc_template) {
                        $epassc_edit_url = add_query_arg([
                            'page'     => 'epasscard',
                            'tab'      => 'update-template',
                            'pass_id'  => $epassc_template['id'],
                            'pass_uid' => $epassc_template['uid'],
                            '_wpnonce' => $epassc_nonce,
                        ], admin_url('admin.php'));
                    ?>
        <div class="epasscard-template">
            <div class="top-part">
                <h4 class="epasscard-title"><?php echo esc_html($epassc_template['name']); ?></h4>
                <div>
                    <div class="epasscard-dropdown-container">
                        <button class="dropdown-toggle">
                            <a href="<?php echo esc_url($epassc_edit_url); ?>">Edit</a>
                            <img src="<?php echo esc_url(EPASSC_PLUGIN_URL . 'assets/img/icons/dropdown-icon.svg'); ?>"
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
                            <img src="<?php echo esc_url(EPASSC_PLUGIN_URL . 'assets/img/icons/apple-icon.svg'); ?>"
                                class="w-6 h-6" alt="icon">
                            <img src="<?php echo esc_url(EPASSC_PLUGIN_URL . 'assets/img/icons/android-icon.svg'); ?>"
                                class="w-6 h-6" alt="icon">
                        </span>
                    </span>
                    <div class="epasscard-status">
                        <span>PASSES: <?php echo esc_html($epassc_template['total_pass']); ?></span>
                        <span>ACTIVE: <?php echo esc_html($epassc_template['active']); ?></span>
                        <span>INACTIVE: <?php echo esc_html($epassc_template['in_active']); ?></span>
                    </div>
                    <div class="maximum-passes">Maximum number of passes:
                        <span><?php echo esc_html($epassc_template['pass_limit']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
                    $epassc_total_items = $epassc_data['total']['total_templates'] ?? 0;
                    $epassc_total_pages = ceil($epassc_total_items / $per_page);

                    if ($epassc_total_pages > 1) {
                        echo '<div class="epasscard-pagination">';
                        for ($epassc_index = 1; $epassc_index <= $epassc_total_pages; $epassc_index++) {
                            echo '<form method="get" style="display:inline-block;">';
                            echo '<input type="hidden" name="page" value="epasscard">';
                            echo '<input type="hidden" name="tab" value="templates">';
                            echo '<input type="hidden" name="_wpnonce" value="' . esc_attr($epassc_nonce) . '">';
                            echo '<input type="hidden" name="paged" value="' . esc_attr($epassc_index) . '">';
                            echo '<button type="submit" class="epasscard-page-link ' . ($epassc_index === $epassc_current_page ? ' active' : '') . '">' . esc_html($epassc_index) . '</button>';
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