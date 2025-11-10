<div id="epasscard-template-wrap">
    <div id="epasscard-template-results">
        <?php
            // Nonce verify
            if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
                wp_die(esc_html__('Security check failed', 'epasscard'));
            }

            $base_url        = admin_url('admin.php?page=epasscard&tab=templates');
            $per_page        = 5;
            $current_page    = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
            $offset          = ($current_page - 1) * $per_page;
            $api_key         = get_option('epasscard_api_key', '');
            $nonce           = sanitize_text_field(wp_unslash($_GET['_wpnonce']));
            $api_url = EPASSCARD_API_URL.'get-pass-templates?page=' . $current_page;

            $response = wp_remote_get($api_url, [
                'headers' => [
                    'x-api-key' => $api_key,
                ],
            ]);

            if (is_wp_error($response)) {
                echo '<div class="epasscard-notice">API Request Failed</div>';
            } else {
                $data = json_decode(wp_remote_retrieve_body($response), true);

                if (isset($data) && ! empty($data['templates']) && is_array($data['templates'])) {
                    foreach ($data['templates'] as $template) {
                        $edit_url = add_query_arg([
                            'page'     => 'epasscard',
                            'tab'      => 'update-template',
                            'pass_id'  => $template['id'],
                            'pass_uid' => $template['uid'],
                            '_wpnonce' => $nonce,
                        ], admin_url('admin.php'));
                    ?>
        <div class="epasscard-template">
            <div class="top-part">
                <h4 class="epasscard-title"><?php echo esc_html($template['name']); ?></h4>
                <div>
                    <div class="epasscard-dropdown-container">
                        <button class="dropdown-toggle">
                            <a href="<?php echo esc_url($edit_url); ?>">Edit</a>
                            <img src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/icons/dropdown-icon.svg'); ?>"
                                alt="Dropdown Icon" />
                        </button>
                        <ul class="dropdown-menu epasscard-hidden">
                            <!-- <li>Download Example CSV File (new)</li>
                            <li>Download passes as CSV</li>
                            <li>Create Bulk processing job</li> -->
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
                        <span>PASSES: <?php echo esc_html($template['total_pass']); ?></span>
                        <span>ACTIVE: <?php echo esc_html($template['active']); ?></span>
                        <span>INACTIVE: <?php echo esc_html($template['in_active']); ?></span>
                    </div>
                    <div class="maximum-passes">Maximum number of passes:
                        <span><?php echo esc_html($template['pass_limit']); ?></span>
                    </div>
                </div>
                <!-- <div class="right-part">
                    <div class="btn-area">
                        <div class="epasscard-actions">
                            <a href="#" class="epasscard-link">
                                <img src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/icons/plus-icon.svg'); ?>"
                                    alt="Create Icon" />
                                Create pass from template
                            </a>
                            <a href="#" class="epasscard-link">
                                <img src="<?php echo esc_url(EPASSCARD_PLUGIN_URL . 'assets/img/icons/plus-icon.svg'); ?>"
                                    alt="View Icon" />
                                Created passes
                            </a>
                        </div>
                    </div>
                    <div class="send-notification">
                        <a href="#">Send push notification</a>
                    </div>
                </div> -->
            </div>
        </div>
        <?php
            }
                    $total_items = $data['total']['total_templates'] ?? 0;
                    $total_pages = ceil($total_items / $per_page);

                    if ($total_pages > 1) {
                        echo '<div class="epasscard-pagination">';
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo '<form method="get" style="display:inline-block;">';
                            echo '<input type="hidden" name="page" value="epasscard">';
                            echo '<input type="hidden" name="tab" value="templates">';
                            echo '<input type="hidden" name="_wpnonce" value="' . esc_attr($nonce) . '">';
                            echo '<input type="hidden" name="paged" value="' . esc_attr($i) . '">';
                            echo '<button type="submit" class="epasscard-page-link ' . ($i === $current_page ? ' active' : '') . '">' . esc_html($i) . '</button>';
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

<!-- Single pass create -->
<?php require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/admin-display/create-single-pass.php'; ?>
<!-- Send push notification -->
<?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/send-push-notification.php'; ?>