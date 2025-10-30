<div id="epasscard-location">
    <div class="left">
        <!-- Mobile preview -->
        <?php include EPASSCARD_PLUGIN_DIR . 'includes/templates/mobile-preview.php'; ?>
    </div>


    <div class="right">
        <div class="description">
            Based on this data a Wallet pass is shown on the user's lockscreen as soon as he's near a defined location
            or the relevant date is reached. The pass can then be opened right on the lockscreen.
        </div>

        <div class="form-group">
            <label for="notification-radius">Notification Radius (meters)</label>
            <input type="number" id="notification-radius" placeholder="Enter radius"
                value="<?php echo isset($locationSettings['notification_radius']) ? esc_attr($locationSettings['notification_radius']) : ''; ?>">
        </div>

        <button class="add-location-btn epasscard-primary-btn">Add Location</button>

        <div class="location-container"
            locationSettingId="<?php echo isset($locationSettingId) ? esc_attr($locationSettingId) : ''; ?>">
            <?php if (! empty($locationList)) {
                foreach ($locationList as $location) {?>
            <div class="location-block epasscard-field-group"
                location-uid="<?php echo isset($location['uid']) ? esc_attr($location['uid']) : ''; ?>">
                <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/admin-modal/location-modal.php'; ?>
                <button class="search-btn epasscard-primary-btn">Search Location</button>
                <div class="form-row">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="location-title" placeholder="Eg. My Location"
                            value="<?php echo isset($location['title']) ? esc_attr($location['title']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Latitude<span>*</span></label>
                        <input type="text" class="location-latitude" placeholder="Eg. 37.7749"
                            value="<?php echo isset($location['latitude']) ? esc_attr($location['latitude']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Longitude<span>*</span></label>
                        <input type="text" class="location-longitude" placeholder="Eg. -122.4194"
                            value="<?php echo isset($location['longitude']) ? esc_attr($location['longitude']) : ''; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Altitude</label>
                        <input type="text" class="location-altitude" placeholder="Eg. 30"
                            value="<?php echo isset($location['altitude']) ? esc_attr($location['altitude']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Notification Text</label>
                        <input type="text" class="location-notify" placeholder="Eg. You are near ..."
                            value="<?php echo isset($locationSettings['initial_message']) ? esc_attr($locationSettings['initial_message']) : ''; ?>">
                    </div>
                </div>
            </div>
            <?php }
            } else {?>

            <!-- Location blocks will be appended here -->
            <div class="location-block epasscard-field-group">
                <?php require_once EPASSCARD_PLUGIN_DIR . 'includes/templates/admin-modal/location-modal.php'; ?>
                <button class="search-btn epasscard-primary-btn">Search Location</button>
                <div class="form-row">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="location-title" placeholder="Eg. My Location">
                    </div>
                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" class="location-latitude" placeholder="Eg. 37.7749">
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" class="location-longitude" placeholder="Eg. -122.4194">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Altitude</label>
                        <input type="text" class="location-altitude" placeholder="Eg. 30">
                    </div>
                    <div class="form-group">
                        <label>Notification Text</label>
                        <input type="text" class="location-notify" placeholder="Eg. You are near ...">
                    </div>
                </div>
            </div>

            <?php }?>
        </div>
    </div>
</div>