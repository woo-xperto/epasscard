// Required plugins/extensions install
jQuery(document).ready(function ($) {
    
     $("#epasscard-connect-btn").on("click", function (e) {
    e.preventDefault();

    // Get button and spinner elements
    var $btn = $("#epasscard-connect-btn");
    var $spinner = $btn.find(".epass-btn-spinner");

    // Reset UI
    $(".validation-error").text("");
    $("#epasscard-response").html("");

    // Add loading state
    $btn.css("padding-right", "40px");
    $btn.addClass("is-loading").prop("disabled", true);
    var api_key = $("#epasscard-api-key").val();

    // AJAX request
    $.ajax({
      url: epasscard_admin.ajax_url,
      type: "POST",
      data: {
        action: "epassc_connect",
        nonce: epasscard_admin.nonce,
        api_key: api_key,
      },
      success: function (response) {
        // Remove loading state
        $btn.removeClass("is-loading").prop("disabled", false);
        $btn.css("padding-right", "10px");
        if (response.errors) {
          // Handle validation errors
          $.each(response.errors, function (field, message) {
            $("#epasscard-" + field).css("border-color", "#d63638");
            $("#" + field + "-error").text(message);
          });
        } else if (response.success) {
          // Success message with close button
          $("#epasscard-response").html(
            '<div class="notice notice-success is-dismissible">' +
            "<p>" +
            response.message +
            "</p>" +
            '<button type="button" class="notice-dismiss">' +
            '<span class="screen-reader-text">Dismiss this notice.</span>' +
            "</button>" +
            "</div>"
          );

          // Auto-dismiss after 3 seconds
          setTimeout(function () {
            $("#epasscard-response .notice").fadeOut(200, function () {
              $(this).remove();
            });
          }, 3000);
        } else {
          // Error message with close button
          $("#epasscard-response").html(
            '<div class="notice notice-error is-dismissible">' +
            "<p>" +
            response.message +
            "</p>" +
            '<button type="button" class="notice-dismiss">' +
            '<span class="screen-reader-text">Dismiss this notice.</span>' +
            "</button>" +
            "</div>"
          );
        }
      },
      error: function (xhr, status, error) {
        // Remove loading state
        $btn.removeClass("is-loading").prop("disabled", false);

        // Error message with close button
        $("#epasscard-response").html(
          '<div class="notice notice-error is-dismissible">' +
          "<p>" +
          error +
          "</p>" +
          '<button type="button" class="notice-dismiss">' +
          '<span class="screen-reader-text">Dismiss this notice.</span>' +
          "</button>" +
          "</div>"
        );
      },
    });
  });


  //Update api key
   $("#epass-update-api-key").on("click", function (e) {
    e.preventDefault();
    let $btn = $(this);
    var $spinner = $btn.find(".epass-btn-spinner");

    $("#epasscard-response").html("");

    // Add loading state
    $btn.css("padding-right", "40px");
    $btn.addClass("is-loading").prop("disabled", true);

     // AJAX request
    $.ajax({
      url: epasscard_admin.ajax_url,
      type: "POST",
      data: {
        action: "epassc_update_api_key_manually",
        nonce: epasscard_admin.nonce,
      },
      success: function (response) {
        // Remove loading state
        $btn.removeClass("is-loading").prop("disabled", false);
        $btn.css("padding-right", "10px");
        if (response.errors) {
          // Handle validation errors
          $.each(response.errors, function (field, message) {
            $("#epasscard-" + field).css("border-color", "#d63638");
            $("#" + field + "-error").text(message);
          });
        } else if (response.success) {
          // Success message with close button
          $("#epasscard-response").html(
            '<div class="notice notice-success is-dismissible">' +
            "<p>" +
            response.message +
            "</p>" +
            '<button type="button" class="notice-dismiss">' +
            '<span class="screen-reader-text">Dismiss this notice.</span>' +
            "</button>" +
            "</div>"
          );

          // Auto-dismiss after 3 seconds
          setTimeout(function () {
            $("#epasscard-response .notice").fadeOut(200, function () {
              $(this).remove();
            });
          }, 3000);
   
          location.reload();

        } else {
          // Error message with close button
          $("#epasscard-response").html(
            '<div class="notice notice-error is-dismissible">' +
            "<p>" +
            response.message +
            "</p>" +
            '<button type="button" class="notice-dismiss">' +
            '<span class="screen-reader-text">Dismiss this notice.</span>' +
            "</button>" +
            "</div>"
          );
        }
      },
      error: function (xhr, status, error) {
        // Remove loading state
        $btn.removeClass("is-loading").prop("disabled", false);

        // Error message with close button
        $("#epasscard-response").html(
          '<div class="notice notice-error is-dismissible">' +
          "<p>" +
          error +
          "</p>" +
          '<button type="button" class="notice-dismiss">' +
          '<span class="screen-reader-text">Dismiss this notice.</span>' +
          "</button>" +
          "</div>"
        );
      },
    });
});

    
});