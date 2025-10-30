<?php
    class Epasscard_Menu
    {
        /**
         * Constructor
         */
        public function __construct()
        {
            add_action('admin_menu', [$this, 'add_admin_menu']);
        }

        /**
         * Add admin menu
         */
        public function add_admin_menu()
        {
            add_menu_page(
                __('Epasscard', 'epasscard'),
                __('Epasscard', 'epasscard'),
                'manage_options',
                'epasscard',
                [$this, 'render_admin_page'],
                'dashicons-admin-network',
                80
            );
        }

        /**
         * Render admin page
         */

        public function render_admin_page()
        {
            
            $api_key     = sanitize_text_field(get_option('epasscard_api_key', ''));
            $account_email = get_option('epasscard_account_email', '');
            $active_tab_selector = ((isset($api_key) && $api_key != "") && (isset($account_email) && $account_email != "")) ? 'create-template' :'connection';

            // Get the active tab from the URL, default to 'create-template'
            $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : $active_tab_selector;
            $pass_id    = isset($_GET['pass_id']) ? sanitize_text_field(wp_unslash($_GET['pass_id'])) : '';

            // Generate base URL for tab links
            $base_url = admin_url('admin.php?page=epasscard');

            // Generate nonce for tab switching
            $nonce = wp_create_nonce('epasscard_tab_switch');

            //Nonce for template list
            wp_localize_script('epasscard-admin-js', 'templateEpassVars', [
                'nonce'           => $nonce,
                'templateBaseUrl' => admin_url('admin.php?page=epasscard'),
            ]);

            // Build tab URLs with nonce
            $connection_url      = add_query_arg(['tab' => 'connection', '_wpnonce' => $nonce], $base_url);
            $extensions_url      = add_query_arg(['tab' => 'extensions', '_wpnonce' => $nonce], $base_url);
            $create_template_url = add_query_arg(['tab' => 'create-template', '_wpnonce' => $nonce], $base_url);
            $templates_url       = add_query_arg(['tab' => 'templates', '_wpnonce' => $nonce], $base_url);

            // Verify nonce for sensitive tabs
            $sensitive_tabs = ['connection','extensions', 'create-template', 'update-template', 'templates'];
            if (in_array($active_tab, $sensitive_tabs, true)) {
                if (isset($_GET['_wpnonce'])) {
                    if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'epasscard_tab_switch')) {
                        wp_die(esc_html__('Security check failed', 'epasscard'));
                    }
                }

            }
        ?>

<div class="wrap epasscard-admin">
    <h1><?php esc_html_e('Epasscard', 'epasscard'); ?></h1>

    <!-- Navigation Tabs -->
    <div class="epasscard-main-tab nav-tab-wrapper">
        <a class="nav-tab                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php echo $active_tab === 'connection' ? 'nav-tab-active' : ''; ?>"
            href="<?php echo esc_url($connection_url); ?>">
            <?php esc_html_e('Connection', 'epasscard'); ?>
        </a>
        <a class="nav-tab                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php echo $active_tab === 'create-template' ? 'nav-tab-active' : ''; ?>"
            href="<?php echo esc_url($create_template_url); ?>">
            <?php esc_html_e('Create Pass Template', 'epasscard'); ?>
        </a>
        <a class="nav-tab                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php echo $active_tab === 'templates' ? 'nav-tab-active' : ''; ?>"
            href="<?php echo esc_url($templates_url); ?>">
            <?php esc_html_e('Templates', 'epasscard'); ?>
        </a>
        <a class="nav-tab                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php echo $active_tab === 'extensions' ? 'nav-tab-active' : ''; ?>"
            href="<?php echo esc_url($extensions_url); ?>">
            <?php esc_html_e('Extensions', 'epasscard'); ?>
        </a>
    </div>

    <!-- Tab Content -->
    <?php if ($active_tab === 'connection'): ?>
    <div class="epasscard-tabcontent">
        <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/setting/connection.php'; ?>
    </div>
    <?php elseif ($active_tab === 'extensions'): ?>
    <div class="epasscard-tabcontent">
        <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/setting/extension-setting.php'; ?>
    </div>
    <?php elseif ($active_tab === 'create-template' || $active_tab === 'update-template'): ?>
    <div class="epasscard-tabcontent" id="create-template">
        <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/epasscard-create-template.php'; ?>
    </div>
    <?php elseif ($active_tab === 'templates'): ?>
    <div class="epasscard-tabcontent">
        <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/admin/admin-display/pass-template-list.php'; ?>
    </div>
    <?php endif; ?>
</div>

<?php
    }

}