<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="epasscard_color_image">
    <div class="left-part">
        <!-- Mobile preview -->
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>
    <div class="right-part">
        <div class="colors">
            <div class="color-row">
                <div class="color-picker-group">
                    <label for="background-color">Background Color:</label>
                    <div style="display: inline-block; width: 128px;">
                        <input style="width: 100px;" id="background-color" class="background-color"
                            value="<?php echo esc_attr($epasscard_bg_color ?? ''); ?>" />
                    </div>
                </div>

                <div class="color-picker-group">
                    <label for="label-color">Label Color:</label>
                    <div style="display: inline-block; width: 128px;">
                        <input style="width: 100px;" id="label-color" class="label-color"
                            value="<?php echo esc_attr($epasscard_label_color ?? '#fff'); ?>" />
                    </div>
                </div>

                <div class="color-picker-group">
                    <label for="foreground-color">Foreground Color:</label>
                    <div style="display: inline-block; width: 128px;">
                        <input style="width: 100px;" id="foreground-color" class="foreground-color"
                            value="<?php echo esc_attr($epasscard_fg_color ?? '#fff'); ?>" />
                    </div>
                </div>
            </div>
        </div>

        <div class="images">
            <div class="image-row">
                <div class="epasscard-background"><label for="background-color">Background Image:</label>
                    <div class="image-upload-wrapper">
                        <input type="file" id="background-image" class="background-image" accept="image/*">
                        <img id="preview-background" class="preview preview-background"
                            src="<?php echo esc_attr($epasscard_bg_image ?? '#'); ?>" alt="">
                        <div class="overlay">Choose Image </div>
                    </div>
                </div>

                <div class="epasscard-logo"><label for="background-color">Icon:</label>
                    <div class="image-upload-wrapper">
                        <input type="file" id="icon" class="icon" accept="image/*">
                        <img id="preview-icon" class="preview preview-icon" src="<?php echo esc_attr($epasscard_icon ?? '#'); ?>"
                            alt="">
                        <div class="overlay">Choose Image</div>
                    </div>
                </div>

                <div class="epasscard-icon"><label for="background-color">Logo:</label>
                    <div class="image-upload-wrapper">
                        <input type="file" id="logo" class="logo" accept="image/*">
                        <img id="preview-logo" class="preview preview-logo" src="<?php echo esc_attr($epasscard_logo ?? '#'); ?>"
                            alt="">
                        <div class="overlay">Choose Image</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>