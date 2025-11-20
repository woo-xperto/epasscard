<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="epasscard-form-container">
    <?php 
    $epasscard_api_key     = sanitize_text_field(get_option('epasscard_api_key', '')); ?>
    <form id="epasscard-connection-form" method="post">
        <?php wp_nonce_field('epasscard_connect', 'epasscard_nonce'); ?>
        <div class="form-group">
            <label for="epasscard-api-key"><?php esc_html_e('API Key', 'epasscard'); ?></label>
            <input type="text" id="epasscard-api-key" name="epasscard_api_key" class="regular-text"
                value="<?php echo esc_attr($epasscard_api_key); ?>" required>
            <span class="validation-error" id="api-key-error"></span>
        </div>

        <div class="form-group">
            <button type="submit" id="epasscard-connect-btn" class="button button-primary">
                <span class="btn-text"><?php esc_html_e('Connect', 'epasscard'); ?></span>
                <span class="btn-spinner">
                    <svg class="spinner-svg" viewBox="0 0 50 50">
                        <circle class="spinner-circle" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                    </svg>
                </span>
            </button>
        </div>
        <a href="https://app.epasscard.com/api-keys" target="_blank">Get your API key</a>
        <div id="epasscard-response"></div>
    </form>
</div>