<!-- Modal -->
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="location-modal" class="location-modal">
    <div class="modal-content">
        <span class="close close-location-modal" id="close-location-modal">&times;</span>
        <div class="pass-locations">
            <h4>Search Address</h4>
            <select class="epass-location-select"></select>
        </div>
        <div id="epass-location-map-wrap">
            <iframe class="epass-location-map" loading="lazy" allowfullscreen
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        <button class="location-search-btn epasscard-primary-btn">Use this information</button>
    </div>
</div>