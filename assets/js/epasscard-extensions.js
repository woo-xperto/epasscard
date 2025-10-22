// Required plugins/extensions install
jQuery(document).ready(function($) {
    $('.epass-button-install').on('click', function() {
        const $button = $(this); // store reference
        $button.find(".epasscard-spinner").css("display", "block");
        const modal = $(".epass-info-modal");

        const pluginType = $button.attr("plugin-type");
        
        $.ajax({
            url: epasscard_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'epass_install_required_plugin',
                plugin_type: pluginType,
                _ajax_nonce: epasscard_admin.nonce,
            },
            success: function(response) {
                modal.find(".info-notification-data").html(response.data.message);
                modal.css("display", "block");
                $button.find(".epasscard-spinner").css("display", "none");
            },
            error: function() {
                modal.find(".info-notification-data").text('Plugin installation failed.');
                modal.css("display", "block");
                $button.find(".epasscard-spinner").css("display", "none");
            }
        });
    });
});