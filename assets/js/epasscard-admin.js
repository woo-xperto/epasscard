jQuery(document).ready(function ($) {
  var pluginUrl = epasscard_admin.epasscardPluginUrl;
  //var organization_id = epasscard_admin.organization_id;
  var dynamicValues = [];
  var apiKey = epasscard_admin.api_key;
  var epassCardApiUrl = "https://api.epasscard.com/api/public/v1/";
  var epassCardUrl = "https://api.epasscard.com/";

  var passIdUrlParams = new URLSearchParams(window.location.search);
  var pass_id = passIdUrlParams.get("pass_id") ?? "";

  //init select2 for certificate
  $("select.pass-certificates").select2({
    placeholder: "Search for a certificate",
    allowClear: true,
  });

  // Epasscard admin tab scripts

  $(".epasscard-tab-bar button").click(function () {
    // Remove active class from all buttons
    $(".epasscard-tab-bar button").removeClass("active");
    // Add active class to clicked button
    $(this).addClass("active");

    // Hide all tab-content divs
    $(this) // The clicked button
      .closest(".epasscard-tabcontent")
      .find(".epasscard-tab-content")
      .removeClass("default-show")
      .hide();

    // Show the selected tab-content div
    $(this) // The clicked button
      .closest(".epasscard-tabcontent") // Find the nearest parent container
      .find("#" + $(this).data("epasscard-tab")) // Find the tab inside it
      .addClass("default-show")
      .show();
  });

  // Handle close button click for notices
  $(document).on("click", ".notice-dismiss", function () {
    $(this)
      .closest(".notice")
      .fadeOut(200, function () {
        $(this).remove();
      });
  });

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
        action: "Epasscard_connect",
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
        action: "epasscard_update_api_key_manually",
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



  // Front field script
  $(function () {
    // const wrapper = $("#epasscard_front_field");
    // const container = wrapper.find(".fields-container");
    // Header fields add/remove
    $(document).on("keyup", ".header-label-name", function () {
      const id = $(this)
        .closest(".epasscard-field-group")
        .attr("data-field-id");

      $(`[data-field-id="${id}"]`).find(".product-name").text($(this).val());
    });

    $(document).on("keyup", ".product-name-label", function () {
      const id = $(this)
        .closest(".epasscard-field-group")
        .attr("data-field-id");
      $(`[data-field-id="${id}"]`).find(".product-value").text($(this).val());
    });

    let mobileHeaderForm = $(".epasscard-header-fields .fields-container");
    let headerContentContainer = $(".header-content");
    let headerLengthCheck = 1; // Starts at 1 for the default field

    $(".add-header-field-btn").on("click", function () {
      if (headerLengthCheck >= 2) {
        $(this).addClass("epasscard-hidden");
        //return;
      }

      // Create unique ID for this field group
      const fieldId = "field-" + Date.now();

      // Create new field group with updated class names
      const headerFields = $(`
        <div class="epasscard-field-group" data-field-id="${fieldId}">
            <label>label <span>*</span></label>
            <input type="text" class="header-label-name" placeholder="Eg. Name">

            <label>Value <span>*</span></label>
            <input type="text" class="product-name-label epasscard-watch-input" placeholder="">

            <label>Change message (This message will be displayed when value is changed)</label>
            <input type="text" class="change-message-header" placeholder="Eg. Name changed to {name}">

            <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
        </div>
    `);

      // Create corresponding content block for new fields
      const contentBlock = $(`
        <div class="content-text" data-field-id="${fieldId}">
            <p class="product-name">New Field</p>
            <p class="product-value">{New Field}</p>
        </div>
    `);

      // Append both
      mobileHeaderForm.append(headerFields);
      headerContentContainer.append(contentBlock);
      headerLengthCheck++;

      // Set up event handlers with updated class names
      headerFields.find(".header-label-name").on("keyup", function () {
        const fieldId = $(this)
          .closest(".epasscard-field-group")
          .data("field-id");
        const value = $(this).val();
        $(`.content-text[data-field-id="${fieldId}"] .product-name`).text(
          value || "New Field"
        );
      });

      headerFields.find(".product-name-label").on("keyup", function () {
        const fieldId = $(this)
          .closest(".epasscard-field-group")
          .data("field-id");
        const value = $(this).val();
        $(`.content-text[data-field-id="${fieldId}"] .product-value`).text(
          value ? `{${value}}` : "{New Field}"
        );
      });
    });

    mobileHeaderForm.on("click", ".remove-field-btn", function () {
      const fieldId = $(this)
        .closest(".epasscard-field-group")
        .data("field-id");

      // Remove both the field group and its corresponding content block
      $(`.epasscard-field-group[data-field-id="${fieldId}"]`).remove();
      $(`.content-text[data-field-id="${fieldId}"]`).remove();

      $(".add-header-field-btn").removeClass("epasscard-hidden");
      headerLengthCheck--;
    });

    // Initialize event handlers for the DEFAULT field with updated class names
    const defaultField = mobileHeaderForm
      .find(".epasscard-field-group")
      .first();
    if (defaultField.length) {
      const defaultFieldId = "default-field";
      defaultField.attr("data-field-id", defaultFieldId);

      defaultField.find(".header-label-name").on("keyup", function () {
        const value = $(this).val();
        $(".header-content .content-text:first .product-name").text(
          value || "Product name"
        );
      });

      defaultField.find(".product-name-label").on("keyup", function () {
        const value = $(this).val();
        $(".header-content .content-text:first .product-value").text(
          value ? `{${value}}` : "{Product name}"
        );
      });
    }

    // Secondary fields add/remove
    const wrapper = $(".epasscard-secondary-fields");
    const container = wrapper.find(".fields-container");

    function createFieldGroup(index) {
      const timestamp = Date.now() + index;
      const fieldGroup = $(`
    <div class="epasscard-field-group" data-id="${timestamp}">
        <label>Label <span>*</span></label>
        <input type="text" class="secondary-label" placeholder="Eg. Name">

        <label>Value <span>*</span></label>
        <input type="text" class="secondary-value epasscard-watch-input" placeholder="">

        <label>Change message (This message will be displayed when value is changed)</label>
        <input type="text" class="change-message-secondary" placeholder="Eg. Name changed to {name}">

        <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
    </div>
  `);

      // Also create corresponding mobile preview
      const mobilePreview = $(`
    <div class="detail-item" data-id="${timestamp}">
      <p class="detail-label">New Label</p>
      <p class="detail-value">New Value</p>
    </div>
  `);

      $(".mobile-preview .secondary-items-container").append(mobilePreview);

      return fieldGroup;
    }

    let containerLength = 1;
    wrapper.find(".add-secondary-field-btn").on("click", function () {
      if (containerLength >= 1) {
        $(this).addClass("epasscard-hidden");
      }

      const newField = createFieldGroup(container.children().length);
      container.append(newField);
      containerLength++;
    });

    // Update mobile preview on keyup for first two inputs
    container.on("keyup", ".secondary-label, .secondary-value", function () {
      const fieldGroup = $(this).closest(".epasscard-field-group");
      const dataId = fieldGroup.attr("data-id");
      const labelValue = fieldGroup.find(".secondary-label").val();
      const fieldValue = fieldGroup.find(".secondary-value").val();

      // Update mobile preview
      const mobileItem = $(`.mobile-preview .secondary-items-container .detail-item[data-id="${dataId}"]`);
      mobileItem.find(".detail-label").text(labelValue || "New Label");
      mobileItem.find(".detail-value").text(fieldValue || "New Value");
    });

    container.on("click", ".remove-field-btn", function () {
      const fieldGroup = $(this).closest(".epasscard-field-group");
      const dataId = fieldGroup.attr("data-id");

      // Remove both field group and mobile preview
      fieldGroup.remove();
      $(
        `.mobile-preview .details-container .detail-item[data-id="${dataId}"]`
      ).remove();

      wrapper.find(".add-secondary-field-btn").removeClass("epasscard-hidden");
      containerLength--;
    });
  });

  ///images

  // More epasscard modal script
  // Open modal
  $(".epasscard-show-templates").click(function () {
    $("#epasscard_more_cards_modal").fadeIn();
  });

  // Close modal
  $(".epasscard-close-modal").click(function () {
    $("#epasscard_more_cards_modal").fadeOut();
  });

  // Template selection
  $(".epasscard-template-card").click(function () {
    const templateId = $(this).data("template-id");
    // Handle template selection here
    alert("Selected template: " + templateId);
    $("#epasscard_more_cards_modal").fadeOut();
  });

  // Close when clicking outside
  $(window).click(function (event) {
    if (event.target.id === "epasscard_more_cards_modal") {
      $("#epasscard_more_cards_modal").fadeOut();
    }
  });

  //  Color and images tab scripts
  // Handle background color change
  $(".color-images .background-color").on("change", function () {
    var bgColor = $(this).val();
    $(this)
      .closest(".color-images")
      .find(".coupon")
      .css("background-color", bgColor);
  });

  // Handle label color change
  $(".color-images .label-color").on("change", function () {
    var labelColor = $(this).val();
    $(this)
      .closest(".color-images")
      .find(".product-name, .detail-label")
      .css("color", labelColor);
  });

  // Handle foreground color change
  $(".color-images .foreground-color").on("change", function () {
    var fgColor = $(this).val();
    $(this)
      .closest(".color-images")
      .find(".detail-value, .product-value")
      .css("color", fgColor);
  });

  //  Mobile preview edit btns
  $(".edit-btn").click(function () {
    $(".front-fields-button").trigger("click");

    // Remove 'active' class from all buttons
    $(".edit-btn").removeClass("mobile-preview-active-btn");
    // Add 'active' class to the clicked button
    const selectEditBtn = $(this).attr("data-epasscard-edit");
    $(`[data-epasscard-edit="${selectEditBtn}"]`).addClass(
      "mobile-preview-active-btn"
    );
    const formFieldsTrack = $(this).attr("data-epasscard-edit");

    $(".mobile-edit-field").addClass("epasscard-hidden");
    if (formFieldsTrack == "header-fields") {
      $(".epasscard-header-fields").removeClass("epasscard-hidden");
    } else if (formFieldsTrack == "primary-fields") {
      $(".epasscard-primary-fields").removeClass("epasscard-hidden");
    } else if (formFieldsTrack == "secondary-fields") {
      $(".epasscard-secondary-fields").removeClass("epasscard-hidden");
    } else if (formFieldsTrack == "auxiliary-fields") {
      $(".epasscard-auxiliary-fields").removeClass("epasscard-hidden");
    } else if (formFieldsTrack == "barcode-fields") {
      $(".epasscard-barcode-fields").removeClass("epasscard-hidden");
    }
    $(".epasscard-right-content").addClass("epasscard-hidden");
  });

  // Initialize all color pickers
  function initColorPicker(selector, previewId) {
    $(selector).colorpicker({
      showOn: "both",
      change: function (color) {
        const hexColor = color ? color.toString() : "#ffffff";
        $(previewId).css("background-color", hexColor);
        //console.log(selector + " changed to: " + hexColor);
      },
    });
  }

  // Initialize each color picker
  if ($(".background-color") && $("#primary-preview")) {
    initColorPicker(".background-color", "#primary-preview");
  }
  if ($(".label-color") && $("#secondary-preview")) {
    initColorPicker(".label-color", "#secondary-preview");
  }
  if ($(".foreground-color") && $("#accent-preview")) {
    initColorPicker(".foreground-color", "#accent-preview");
  }

  // Single pass scripts

  $(document).on("click", ".epasscard-link", function (event) {
    event.preventDefault();

    $("#epasscard-template-wrap").addClass("epasscard-hidden");
    $(".single-pass-wrap").removeClass("epasscard-hidden");
  });

  $(".single-pass-back-btn").click(function (event) {
    event.preventDefault();
    $(".single-pass-wrap").addClass("epasscard-hidden");
    $(".epasscard-push-notification").addClass("epasscard-hidden");
    $("#epasscard-template-wrap").removeClass("epasscard-hidden");
  });

  // Primary fields to mobile preview data pass
  $(".primary-name, .primary-value").on("keyup", function () {
    $(".strip-text").text($(".primary-name").val());
    $(".strip-text-uppercase").text($(".primary-value").val().toUpperCase());
    jQuery(".mobile-preview .epc-primary-label").text($(".primary-name").val());
    jQuery(".mobile-preview .epc-primary-value").text($(".primary-value").val());
  });

  //  Secondary fields to mobile preview data pass
  // $(".model-label-name").on("keyup", function () {
  //   $(".model-label").text($(".model-label-name").val());
  // });

  // $(".model-value").on("keyup", function () {
  //   $(".model-value-show").text($(".model-value").val());
  // });

  // $(".purchase-label-name").on("keyup", function () {
  //   $(".purchase-label-show").text($(".purchase-label-name").val());
  // });

  // $(".purchase-value").on("keyup", function () {
  //   $(".purchase-value-show").text($(".purchase-value").val());
  // });

  // $(document).on("keyup", ".label-name-data", function () {
  //   $(".name-label-show").text($(this).val());
  // });

  // $(document).on("keyup", ".label-name-value", function () {
  //   $(".name-value-show").text($(this).val());
  // });

  //Scanning selection scripts
  $(".scanner-code").on("change", function () {
    const selectedValue = $(this).val();
    changeBarcodeImage(selectedValue);
  });

  // Push notification scripts
  $(document).on("click", ".send-notification > a", function (event) {
    event.preventDefault();
    $("#epasscard-template-wrap").addClass("epasscard-hidden");
    $(".epasscard-push-notification").removeClass("epasscard-hidden");
  });

  //Mobile preview color on tab change
  $(document).on("click", ".epasscard-tab-item", function (event) {
    event.preventDefault();
    $(".mobile-preview .coupon").css(
      "background-color",
      $('#create-template #background-color').siblings('.evo-pointer').css('background-color')
    );

    $(".mobile-preview .detail-label").css(
      "color",
      $("#create-template .color-images .label-color").siblings('.evo-pointer').css('background-color')
    );

    $(".mobile-preview .detail-value,.mobile-preview .product-value").css(
      "color",
      $("#create-template .color-images .foreground-color").siblings('.evo-pointer').css('background-color')
    );
  });

  //Create pass template
  $(".create-pass-template").click(function (event) {
    event.preventDefault();

    // Retrieve pass ids
    let dataIds = $(this).attr("pass-ids")
      ? JSON.parse($(this).attr("pass-ids"))
      : [];

    // Setting data
    let settingData = {};
    const settingId = $("#create-template #Settings").attr("setting_id");
    if (settingId) {
      settingData.setting_id = settingId;
    }
    // Colors
    // settingData.bgColor = $("#create-template .color-images .background-color")
    //   .siblings(".evo-pointer")
    //   .css("background-color");
    // settingData.labelColor = $("#create-template .color-images .label-color")
    //   .siblings(".evo-pointer")
    //   .css("background-color");
    // settingData.textColor = $(
    //   "#create-template .color-images .foreground-color"
    // )
    //   .siblings(".evo-pointer")
    //   .css("background-color");

    function rgbToHex(color) {
      if (color.startsWith("#")) return color; // already hex

      const rgb = color.match(/\d+/g);
      if (!rgb || rgb.length < 3) return "#000000"; // fallback

      return (
        "#" +
        rgb
          .slice(0, 3)
          .map((val) => {
            const hex = parseInt(val).toString(16);
            return hex.length === 1 ? "0" + hex : hex;
          })
          .join("")
      );
    }

    // Apply conversion to each color
    settingData.bgColor = rgbToHex(
      $("#create-template .color-images .background-color")
        .siblings(".evo-pointer")
        .css("background-color")
    );

    settingData.labelColor = rgbToHex(
      $("#create-template .color-images .label-color")
        .siblings(".evo-pointer")
        .css("background-color")
    );

    settingData.textColor = rgbToHex(
      $("#create-template .color-images .foreground-color")
        .siblings(".evo-pointer")
        .css("background-color")
    );

    // Get the header logo src
    var headerLogoSrc = $("#create-template .header-logo .logo-img").attr(
      "src"
    );

    // Check if the logo URL contains the expected domain
    if (headerLogoSrc && headerLogoSrc.includes(epassCardUrl)) {
      // Split only if it doesn't contain assets path
      if (headerLogoSrc.includes("/assets/")) {
        settingData.headerLogo = headerLogoSrc; // Keep full URL for assets
      } else {
        settingData.headerLogo = headerLogoSrc.split(epassCardUrl)[1]; // Split non-assets
      }
    } else {
      settingData.headerLogo = headerLogoSrc;
    }

    var thumbUrl = $("#create-template .coupon-strip").css("background-image");
    var bgThumbnail = thumbUrl.replace(/^url\(["']?|["']?\)$/g, "");

    if (bgThumbnail.includes(epassCardUrl)) {
      // Split only if it doesn't contain assets path
      if (bgThumbnail.includes("/assets/")) {
        settingData.thumbnailUrl = bgThumbnail; // Keep full URL for assets
      } else {
        settingData.thumbnailUrl = bgThumbnail.split(epassCardUrl)[1]; // Split non-assets
      }
    } else {
      settingData.thumbnailUrl = bgThumbnail;
    }

    // Validate required fields
    let templateInfo = {};
    let passInfo = true;
    if ($("#create-template .template-name").val().trim() === "") {
      $("#create-template .template-name").addClass("epasscard-input-error");
      passInfo = false;
    } else {
      templateInfo.templateName = $("#create-template .template-name").val();
      $("#create-template .template-name").removeClass("epasscard-input-error");
    }

    if ($("#create-template .template-description").val().trim() === "") {
      $("#create-template .template-description").addClass(
        "epasscard-input-error"
      );
      passInfo = false;
    } else {
      templateInfo.templateDescription = $(
        "#create-template .template-description"
      ).val();
      $("#create-template .template-description").removeClass(
        "epasscard-input-error"
      );
    }

    if ($("#create-template .organization-name").val().trim() === "") {
      $("#create-template .organization-name").addClass(
        "epasscard-input-error"
      );
      passInfo = false;
    } else {
      //organizationName = $(".organization-name").val();
      templateInfo.organizationName = $(
        "#create-template .organization-name"
      ).val();
      $("#create-template .organization-name").removeClass(
        "epasscard-input-error"
      );
    }

    if ($("#create-template .created-passes").val().trim() === "") {
      passInfo = false;
      $("#create-template .created-passes").addClass("epasscard-input-error");
    } else {
      //createdPasses = $(".created-passes").val();
      templateInfo.createdPasses = $("#create-template .created-passes").val();
      $("#create-template .created-passes").removeClass(
        "epasscard-input-error"
      );
    }

    let passTypeIdentifier = $(
      "#create-template .epasscard-field-group select.pass-certificates"
    ).val();

    if (passTypeIdentifier == null) {
      passInfo = false;
      $(
        "#PassInfo .selection span.select2-selection.select2-selection--single"
      ).addClass("epasscard-input-error");
    } else {
      templateInfo.certificate = passTypeIdentifier;
      $(
        "#PassInfo .selection span.select2-selection.select2-selection--single"
      ).removeClass("epasscard-input-error");
    }

    if (!passInfo) {
      $('[data-epasscard-tab="PassInfo"]').trigger("click");
      return;
    }

    // Header fields
    let headerInfo = true;
    let headerData = {
      headerLabels: [],
      productNames: [],
      headerChangeMessages: [],
    };
    $(" .epasscard-header-fields .header-label-name").each(function () {
      let value = $(this).val().trim();
      if (value === "") {
        $(this).addClass("epasscard-input-error");
        headerInfo = false;
      } else {
        $(this).removeClass("epasscard-input-error");
        headerData.headerLabels.push(value);
      }
    });

    $(".epasscard-header-fields .product-name-label").each(function () {
      let value = $(this).val().trim();
      if (value === "") {
        $(this).addClass("epasscard-input-error");
        headerInfo = false;
      } else {
        $(this).removeClass("epasscard-input-error");
        headerData.productNames.push(value);
      }
    });

    $(" .epasscard-header-fields .change-message-header").each(function () {
      let value = $(this).val().trim();
      headerData.headerChangeMessages.push(value);
    });

    if (!headerInfo) {
      $('[data-epasscard-edit="header-fields"]').trigger("click");
      $(".edit-btn").removeClass("mobile-preview-active-btn");
      $('[data-epasscard-edit="header-fields"]').addClass(
        "mobile-preview-active-btn"
      );
      return;
    }

    // Primary fields
    let primaryName, primaryValue;
    let primaryInfo = true;
    let primaryFieldsData = {};

    if ($("#create-template .primary-name").val().trim() === "") {
      primaryInfo = false;
      primaryFieldsData.primaryName = "";
    } else {
      primaryFieldsData.primaryName = $("#create-template .primary-name").val();
    }

    if ($("#create-template .primary-value").val().trim() === "") {
      primaryInfo = false;
      primaryFieldsData.primaryValue = "";
    } else {
      primaryFieldsData.primaryValue = $(
        "#create-template .primary-value"
      ).val();
    }
    primaryFieldsData.primaryMessage = $(
      "#create-template .primary-message"
    ).val();

    //Secondary fields
    let secondaryIsValid = true;
    let secondaryData = {
      secondaryLabels: [],
      secondaryValues: [],
    };

    // if (
    //   $(`${selectedScreenId} .epasscard-secondary-fields .model-label-name`)
    //     .val()
    //     .trim() === ""
    // ) {
    //   $(
    //     `${selectedScreenId} .epasscard-secondary-fields .model-label-name`
    //   ).addClass("epasscard-input-error");
    //   secondaryIsValid = false;
    // } else {
    //   $(
    //     `${selectedScreenId} .epasscard-secondary-fields .model-label-name`
    //   ).removeClass("epasscard-input-error");

    //   secondaryData.secondaryLabels.push(
    //     $(
    //       `${selectedScreenId} .epasscard-secondary-fields .model-label-name`
    //     ).val()
    //   );
    // }

    // if (
    //   $(`${selectedScreenId} .epasscard-secondary-fields .model-value`)
    //     .val()
    //     .trim() === ""
    // ) {
    //   $(".epasscard-secondary-fields .model-value").addClass(
    //     "epasscard-input-error"
    //   );
    //   secondaryIsValid = false;
    // } else {
    //   $(
    //     `${selectedScreenId} .epasscard-secondary-fields .model-value`
    //   ).removeClass("epasscard-input-error");

    //   secondaryData.secondaryValues.push(
    //     $(`${selectedScreenId} .epasscard-secondary-fields .model-value`).val()
    //   );
    // }

    // if (
    //   $(`${selectedScreenId} .epasscard-secondary-fields .purchase-label-name`)
    //     .val()
    //     .trim() === ""
    // ) {
    //   $(
    //     `${selectedScreenId} .epasscard-secondary-fields .purchase-label-name`
    //   ).addClass("epasscard-input-error");
    //   secondaryIsValid = false;
    // } else {
    //   $(
    //     `${selectedScreenId} .epasscard-secondary-fields .purchase-label-name`
    //   ).removeClass("epasscard-input-error");
    //   secondaryData.secondaryLabels.push(
    //     $(
    //       `${selectedScreenId} .epasscard-secondary-fields .purchase-label-name`
    //     ).val()
    //   );
    // }

    // if (
    //   $(`${selectedScreenId} .epasscard-secondary-fields .purchase-value`)
    //     .val()
    //     .trim() === ""
    // ) {
    //   $(
    //     `${selectedScreenId} .epasscard-secondary-fields .purchase-value`
    //   ).addClass("epasscard-input-error");
    //   secondaryIsValid = false;
    // } else {
    //   $(
    //     `${selectedScreenId} .epasscard-secondary-fields .purchase-value`
    //   ).removeClass("epasscard-input-error");
    //   secondaryData.secondaryValues.push(
    //     $(
    //       `${selectedScreenId} .epasscard-secondary-fields .purchase-value`
    //     ).val()
    //   );
    // }

    // $(`${selectedScreenId} .epasscard-secondary-fields .label-name-data`).each(
    //   function () {
    //     if ($(this).val().trim() === "") {
    //       $(this).addClass("epasscard-input-error");
    //       secondaryIsValid = false;
    //     } else {
    //       $(this).removeClass("epasscard-input-error");
    //       secondaryData.secondaryLabels.push(
    //         $(
    //           `${selectedScreenId} .epasscard-secondary-fields .label-name-data`
    //         ).val()
    //       );
    //     }
    //   }
    // );

    // $(`${selectedScreenId} .epasscard-secondary-fields .label-name-value`).each(
    //   function () {
    //     if ($(this).val().trim() === "") {
    //       $(this).addClass("epasscard-input-error");
    //       secondaryIsValid = false;
    //     } else {
    //       $(this).removeClass("epasscard-input-error");
    //       secondaryData.secondaryValues.push(
    //         $(
    //           `${selectedScreenId} .epasscard-secondary-fields .label-name-value`
    //         ).val()
    //       );
    //     }
    //   }
    // );

    $(".epasscard-secondary-fields .secondary-label").each(function () {
      if ($(this).val().trim() === "") {
        $(this).addClass("epasscard-input-error");
        secondaryIsValid = false;
      } else {
        $(this).removeClass("epasscard-input-error");
        secondaryData.secondaryLabels.push($(this).val());
      }
    });

    $(" .epasscard-secondary-fields .secondary-value").each(function () {
      if ($(this).val().trim() === "") {
        $(this).addClass("epasscard-input-error");
        secondaryIsValid = false;
      } else {
        $(this).removeClass("epasscard-input-error");
        secondaryData.secondaryValues.push($(this).val());
      }
    });

    if (!secondaryIsValid) {
      $('[data-epasscard-edit="secondary-fields"]').trigger("click");
      $(".edit-btn").removeClass("mobile-preview-active-btn");
      $('[data-epasscard-edit="secondary-fields"]').addClass(
        "mobile-preview-active-btn"
      );
      return;
    }

    //Auxiliary fileds data
    let auxiliaryData = {
      auxiliaryLabels: [],
      auxiliaryValues: [],
      auxiliaryMsgs: [],
    };

    jQuery(".epasscard-auxiliary-fields .auxiliary-label").each(function () {
      if (jQuery(this).val().trim() !== "") {
        auxiliaryData.auxiliaryLabels.push(jQuery(this).val());
      }
    });

    jQuery(" .epasscard-auxiliary-fields .auxiliary-value").each(function () {
      if (jQuery(this).val().trim() !== "") {
        auxiliaryData.auxiliaryValues.push(jQuery(this).val());
      } 
    });

    jQuery(" .epasscard-auxiliary-fields .auxiliary-msg").each(function () {
      if (jQuery(this).val().trim() !== "") {
        auxiliaryData.auxiliaryMsgs.push(jQuery(this).val());
      } 
    });

    // Barcode data
    let barcodeFormat = $("#create-template .scanner-code").val();
    let barcodeValue = $("#create-template .barcode-value").val();
    let barcodeAlternateText = $(
      "#create-template .barcode-alternate-text"
    ).val();
    let barcodeInfo = true;
    let barcodeData = {};
    if (barcodeFormat == "" || barcodeFormat == null) {
      barcodeInfo = false;
      $(
        ".epasscard-barcode-fields .scanner-code + .select2 > .selection span.select2-selection.select2-selection--single"
      ).addClass("epasscard-input-error");
    } else {
      barcodeData.barcodeFormat = barcodeFormat;
      $(
        ".epasscard-barcode-fields .scanner-code + .select2 > .selection span.select2-selection.select2-selection--single"
      ).removeClass("epasscard-input-error");
    }

    if (barcodeValue == "" || barcodeValue == null) {
      barcodeInfo = false;
      $(
        ".epasscard-barcode-fields .barcode-value + .select2 > .selection span.select2-selection.select2-selection--single"
      ).addClass("epasscard-input-error");
    } else {
      barcodeData.barcodeValue = barcodeValue;
      $(
        ".epasscard-barcode-fields .barcode-value + .select2 > .selection span.select2-selection.select2-selection--single"
      ).removeClass("epasscard-input-error");
    }

    if (barcodeAlternateText) {
      barcodeData.barcodeText = barcodeAlternateText;
    }

    barcodeData.isBarcodeChecked = $("#barcode-expire").is(":checked");

    if (!barcodeInfo) {
      $('[data-epasscard-edit="barcode-fields"]').trigger("click");
      $(".edit-btn").removeClass("mobile-preview-active-btn");
      $('[data-epasscard-edit="barcode-fields"]').addClass(
        "mobile-preview-active-btn"
      );
      return;
    }

    //Back fields
    let backFieldData = {
      backFieldLabels: [],
      backFieldValues: [],
    };

    if (
      $("#create-template #back_fields .right div").hasClass("back-fields-wrap")
    ) {
      let backFieldMessage = [];
      let backFieldIsValid = true;

      $("#create-template .back-fields-wrap .field-name").each(function () {
        if ($(this).val().trim() === "") {
          $(this).addClass("epasscard-input-error");
          backFieldIsValid = false;
        } else {
          backFieldData.backFieldLabels.push($(this).val().trim());
          $(this).removeClass("epasscard-input-error");
        }
      });

      $("#create-template .back-fields-wrap .field-value").each(function () {
        if ($(this).val().trim() === "") {
          $(this).addClass("epasscard-input-error");
          backFieldIsValid = false;
        } else {
          backFieldData.backFieldValues.push($(this).val().trim());
          $(this).removeClass("epasscard-input-error");
        }
      });

      if (!backFieldIsValid) {
        return;
      }

      //Locations
      var allLocations = [];
      var notificationRadius = $("#create-template #notification-radius").val();
      var locationSettingId = $("#create-template .location-container").attr(
        "location-settings-id"
      );
      $("#create-template .location-block").each(function () {
        const latitude = $(this).find(".location-latitude").val();
        const longitude = $(this).find(".location-longitude").val();
        const altitude = $(this).find(".location-altitude").val();
        const releventText = $(this).find(".location-notify").val();
        const titleValue = $(this).find(".location-title").val(); // Get the value once

        if (pass_id) {
          const rawUid = $(this).attr("location-uid");
          const uid = rawUid && rawUid.trim() !== "" ? rawUid : null;

          var locationData = {
            uid,
            title: titleValue, // Use consistent property name
            latitude,
            longitude,
            altitude,
            releventText,
          };
        } else {
          var locationData = {
            name: titleValue, // Use consistent property name
            latitude,
            longitude,
            altitude,
            releventText,
          };
        }

        allLocations.push(locationData);
      });

      // Setting data
      var settingValues = {};
      if ($("#create-template #expire-card").is(":checked")) {
        settingValues.is_expire = true;

        if ($("#create-template #expire-type-fixed").is(":checked")) {
          settingValues.expire_type = $(
            "#create-template #expire-type-fixed"
          ).val();
        } else if ($("#create-template #expire-type-dynamic").is(":checked")) {
          settingValues.expire_type = $(
            "#create-template #expire-type-dynamic"
          ).val();
        } else {
          settingValues.expire_type = " ";
        }
      } else {
        settingValues.is_expire = false;
      }

      if ($("#create-template .rechargeable-card").is(":checked")) {
        settingValues.is_rechargeable = true;
      } else {
        settingValues.is_rechargeable = false;
      }

      if ($("#create-template #expire-type-fixed").is(":checked")) {
        settingValues.fixed_dynamic_value = $(
          "#create-template #setting-fixed-value"
        ).val();
      } else if ($("#create-template #expire-type-dynamic").is(":checked")) {
        settingValues.fixed_dynamic_value = $(
          "#create-template .setting-dynamic-data"
        ).val();
      } else {
        settingValues.fixed_dynamic_value = " ";
      }

      if ($("#create-template #transaction-log").is(":checked")) {
        settingValues.transaction_log = true;
      } else {
        settingValues.transaction_log = false;
      }

      if ($("#create-template #datetime-format")) {
        settingValues.date_time_format = $(
          "#create-template #datetime-format"
        ).val();
      }

      if ($("#create-template .epass-sharing-option")) {
        settingValues.sharing_option = $(
          "#create-template .epass-sharing-option"
        ).val();
      }

      // Additional properties
      var additionalProperties = [];

      $("#create-template .field-block").each(function () {
        const id = $(this).attr("additional-id");
        const blockStatus = $(this).attr("status");
        const name = $(this).find('input[type="text"]').val();
        const type = $(this).find("select").val();
        let required = $(this).find('input[type="checkbox"]').is(":checked");
        const uid = $(this).find(".addition-uid").val();
        required = required == true ? (required = 1) : 0;

        if (pass_id && id) {
          if (blockStatus) {
            additionalProperties.push({
              id: Number(id),
              pass_id: Number(pass_id),
              uid: uid,
              field_name: name,
              field_type: type,
              required: required,
              //org_id: Number(organization_id),
              status: false,
            });

          } else {
            additionalProperties.push({
              id: Number(id),
              pass_id: Number(pass_id),
              uid: uid,
              field_name: name,
              field_type: type,
              required: required,
              //org_id: Number(organization_id),
            });
          }


        } else if (pass_id && !id) {

          additionalProperties.push({
            uid: "",
            field_name: name,
            field_type: type,
            required: required,
          });

        } else {
          additionalProperties.push({
            name: name,
            type: type,
            isRequired: required,
            isProtected: false,
            protectedBy: "",
          });

        }
      });

      // Setting rechargeField/Transaction dropdown values
      var rechargeField = [];
      var transactionValues = [];
      $("#create-template #recharge-table tbody tr").each(function () {
        const name = $(this).find("td:first").text().trim();
        const action = $(this).find("select.action-select").val();
        if (pass_id) {
          rechargeField.push({ name });
        } else {
          rechargeField.push({ name: name, value: name, action: action });
        }
        transactionValues.push({ field: name, action: action });
      });
    } else {
      $('#create-template [data-epasscard-tab="BackFields"]').trigger("click");
      $(".info-notification-data").text("Minimum one back field required");
      $(".epass-info-modal").css("display", "block");

      return;
    }

    const templateData = $(".epasscard-card-item.epasscard-active-card").attr(
      "data-template"
    );
    const parsedTemplateData = templateData ? JSON.parse(templateData) : {};

    // Create pass template
    var $button = $(this);
    $button.prop("disabled", true);
    $button.find(".epasscard-spinner").css("display", "block");

    $.ajax({
      url: epasscard_admin.ajax_url,
      type: "POST",
      data: {
        action: "Epasscard_create_pass_template",
        template_info: templateInfo,
        barcode_data: barcodeData,
        header_data: headerData,
        primary_fields_data: primaryFieldsData,
        secondary_data: secondaryData,
        back_field_data: backFieldData,
        setting_data: settingData,
        locations_data: JSON.stringify(allLocations),
        //locations_data: allLocations,
        locations_setting_id: locationSettingId,
        notification_radius: notificationRadius,
        setting_values: settingValues,
        additional_properties: JSON.stringify(additionalProperties),
        auxiliary_properties: JSON.stringify(auxiliaryData),
        recharge_field: JSON.stringify(rechargeField),
        transaction_values: JSON.stringify(transactionValues),
        pass_ids: dataIds,
        template_data: parsedTemplateData,
        _ajax_nonce: epasscard_admin.nonce,
      },
      success: function (response) {
        let message;
        if (response.data && response.data.message) {
          message = response.data.message;
        } else {
          message = "Something wrong. Please try again later";
        }

        $(".info-notification-data").html(message);
        $(".epass-info-modal").css("display", "block");

        if (message == "Template Created successfully" || message == "Templated updated successfully") {

          const baseUrl = window.location.origin + window.location.pathname;

          setTimeout(function () {
            const redirectUrl = `${templateEpassVars.templateBaseUrl}&tab=templates&_wpnonce=${templateEpassVars.nonce}`;
            window.location.href = redirectUrl;
          }, 3000);

        }
        $button.find(".epasscard-spinner").css("display", "none");
      },
      error: function (xhr, status, error) { },
      complete: function () {
        $button.prop("disabled", false);
      },
    });
  });

  //Default template scripts
  function renderCardData($card, $card_check) {
    if (!pass_id && $card_check == 1) {
      $(".epasscard-card-item").removeClass("epasscard-active-card");
      $card.addClass("epasscard-active-card");
    }

    const rawData = $card.attr("data-template");
    if (!rawData) return;

    const parsedData = JSON.parse(rawData);
    const designObj = parsedData.designObj || {};
    const headerFields = parsedData.designObj.headerFields || [];
    const secondaryFields = parsedData.designObj.secondaryFields || [];
    const auxiliaryFields = parsedData.designObj.auxiliaryFields || [];
    const primaryFields = parsedData.designObj.primaryFields || [];

    const primarySettings = designObj.primarySettings || {};
    const cardType = primarySettings.cardType || "";
    const images = designObj.images || {};
    const barCodeData = designObj.barcode || {};


    //Mobile screen color content change for pass edit mode
    if (pass_id) {
      if ($card_check != 1) {
        //Background Color
        $(".mobile-preview .coupon").css(
          "background-color",
          primarySettings.backgroundColor
        );
        //Barcode image change in mobile preview
        const barcodeFormat = barCodeData.format;
        changeBarcodeImage(barcodeFormat);

        $("#background-color")
          .siblings(".evo-pointer")
          .css("background-color", primarySettings.backgroundColor);
        $("#label-color")
          .siblings(".evo-pointer")
          .css("background-color", primarySettings.labelColor);

        $("#foreground-color")
          .siblings(".evo-pointer")
          .css("background-color", primarySettings.forgroundColor);
      }
    } else {
      //Mobile screen color content change for new pass create mode
      $(".mobile-preview .coupon").css(
        "background-color",
        primarySettings.backgroundColor
      );
      $("#ColorImages #background-color").val(primarySettings.backgroundColor);
      $("#ColorImages #background-color")
        .siblings(".evo-pointer")
        .css("background-color", primarySettings.backgroundColor);
      $("#ColorImages #label-color")
        .siblings(".evo-pointer")
        .css("background-color", primarySettings.labelColor);

      $("#ColorImages #foreground-color")
        .siblings(".evo-pointer")
        .css("background-color", primarySettings.forgroundColor);

      // Logo
      $(".header-logo img.logo-img").attr("src", images.logo);
      $("#preview-background").attr("src", images.strip);
      $("#preview-logo").attr("src", images.logo);
      $("#preview-icon").attr("src", images.logo);

      //Card image
       jQuery(".mobile-preview .epc-image-block > img").attr("src", images.strip);
       jQuery(".phone-pass-preview .coupon-strip").css(
          "background-image",
          `url("${images.strip}")`
        );
      if (cardType == "Generic pass") {
        jQuery(".mobile-preview .epc-primary-label").text(primaryFields[0].label);
        jQuery(".mobile-preview .epc-primary-value").text(primaryFields[0].value);
        jQuery(".epasscard-primary-fields .primary-name").val(primaryFields[0].label);
        jQuery(".epasscard-primary-fields .primary-value").val(primaryFields[0].value);
        jQuery('.mobile-preview .coupon-strip').css('display', 'none');
        jQuery(".mobile-preview .epc-mobile-hero").css('display', 'flex');
        jQuery('.mobile-preview .auxiliary-items-wrap').css('display', 'flex');
        jQuery(".mobile-preview .coupon-qrcode").css("margin-top", "0px");
      } else {
        jQuery(".epasscard-primary-fields .primary-name").val("");
        jQuery(".epasscard-primary-fields .primary-value").val("");
        jQuery('.mobile-preview .coupon-strip').css('display', 'flex');
        jQuery(".mobile-preview .epc-mobile-hero").css('display', 'none');
        jQuery('.mobile-preview .auxiliary-items-wrap').css('display', 'none');
        jQuery(".mobile-preview .coupon-qrcode").css("margin-top", "60px");
      }

      // Header fields
      if (headerFields && headerFields.length > 0) {
        const header = headerFields[0];
        $(".header-content .product-name").text(header.label);
        $(".header-content .product-value").text(header.value);
        $(".epasscard-header-fields .header-label-name").val(header.label);
        dynamicValues.push(header.value);
      }

      // Secondary fields
      if (secondaryFields && secondaryFields.length >= 2) {
        $(".secondary-items-container .detail-item:nth-child(1) .detail-label").text(
          secondaryFields[0].label
        );
        $(
          ".epasscard-secondary-fields .epasscard-field-group:nth-child(1) > input.model-label-name"
        ).val(secondaryFields[0].label);
        $(".secondary-items-container .detail-item:nth-child(1) .detail-value").text(
          secondaryFields[0].value
        );
        $(
          ".epasscard-secondary-fields .epasscard-field-group:nth-child(1) > input.model-value.epasscard-watch-input"
        ).val(secondaryFields[0].value);
        $(".secondary-items-container .detail-item:nth-child(2) .detail-label").text(
          secondaryFields[1].label
        );
        $(
          ".epasscard-secondary-fields .epasscard-field-group:nth-child(2) > input.purchase-label-name"
        ).val(secondaryFields[1].label);
        $(".secondary-items-container .detail-item:nth-child(2) .detail-value").text(
          secondaryFields[1].value
        );
        $(
          ".epasscard-secondary-fields .epasscard-field-group:nth-child(2) > input.purchase-value.epasscard-watch-input"
        ).val(secondaryFields[1].value);

        // Select all field groups
        const fieldGroups = document.querySelectorAll(
          ".epasscard-secondary-fields .epasscard-field-group"
        );
        // Loop through each field group and populate values
        secondaryFields.forEach((field, index) => {
          const group = fieldGroups[index];
          if (group) {
            group.querySelector(".secondary-label").value = field.label;
            group.querySelector(".secondary-value").value = field.value;
            group.querySelector("input[placeholder]").value = field.changeMsg;
            dynamicValues.push(field.value);
          }
        });
      }

      if (auxiliaryFields && auxiliaryFields.length > 0) {
        //Show default auxiliary data in auxiliary blocks
        const auxiliaryFieldGroups = document.querySelectorAll(
          ".epasscard-auxiliary-fields .epasscard-field-group"
        );
        // Loop through each field group and populate values
        auxiliaryFields.forEach((field, index) => {
          const group = auxiliaryFieldGroups[index];
          if (group) {
            group.querySelector(".auxiliary-label").value = field.label;
            group.querySelector(".auxiliary-value").value = field.value;
            group.querySelector("input[placeholder]").value = field.changeMsg;
            dynamicValues.push(field.value);
          }
        });

        //Auxiliary mobile preview value
        $(".auxiliary-items-container .detail-item:nth-child(1) .detail-label").text(
          auxiliaryFields[0].label
        );

        $(".auxiliary-items-container .detail-item:nth-child(1) .detail-value").text(
          auxiliaryFields[0].value
        );

        $(".auxiliary-items-container .detail-item:nth-child(2) .detail-label").text(
          auxiliaryFields[1].label
        );

        $(".auxiliary-items-container .detail-item:nth-child(2) .detail-value").text(
          auxiliaryFields[1].value
        );
      }

      //Setting dropdown
      const settingDropdown = `
        <option value="${headerFields[0].label}">${headerFields[0].label}</option>
        <option value="${secondaryFields[0].label}">${secondaryFields[0].label}</option>
        <option value="${secondaryFields[1].label}">${secondaryFields[1].label}</option>
      `;
      $(".recharge-select").html(settingDropdown);

      //Setting rechargeable dropdown
      const settingRechargeableDropdown = `
        <option value="{${headerFields[0].label}}">${headerFields[0].label}</option>
        <option value="{${secondaryFields[0].label}}">${secondaryFields[0].label}</option>
        <option value="{${secondaryFields[1].label}}">${secondaryFields[1].label}</option>
      `;
      $(".setting-dynamic-data").html(settingRechargeableDropdown);

      // Additional properties
      const allFields = headerFields.concat(secondaryFields);

      // Select all field blocks
      const fieldBlocks = document.querySelectorAll(
        ".epasscard_additional_properties .field-block.epasscard-field-group"
      );

      // Loop through and assign label values
      allFields.forEach((field, index) => {
        const block = fieldBlocks[index];
        if (block) {
          const input = block.querySelector(".additional-field-name");
          if (input) {
            input.value = field.label;
          }
        }
      });

      //Barcode image change in mobile preview
      const barcodeFormat = barCodeData.format;
      changeBarcodeImage(barcodeFormat);
    }

    // Initialize values
    dynamicValues.push("{pass_link}");
    let activeInput = null;
    let suppressSuggestions = false;

    const $suggestionBox = $(
      '<ul class="epasscard-suggestion-box"></ul>'
    ).appendTo("body");

    // Track focused input
    $(document).on("focus", ".epasscard-watch-input", function () {
      activeInput = $(this);
    });

    // Keyup handler: show suggestions based on brace logic
    $(document).on("keyup", ".epasscard-watch-input", function () {
      if (suppressSuggestions) return;

      const val = $(this).val();
      const offset = $(this).offset();

      // Clean single brace trigger — e.g. "{"
      const braceTrigger = /^\s*\{\s*$/;

      // Check for explicit trigger like "{" or " {"
      if (braceTrigger.test(val)) {
        $suggestionBox.html(
          dynamicValues.map((item) => `<li>${item}</li>`).join("")
        );
        $suggestionBox.css({
          top: offset.top + $(this).outerHeight(),
          left: offset.left,
          width: $(this).outerWidth(),
          display: "block",
        });
      } else if (val.includes("{")) {
        // Smart check: brace opened but not closed
        const openIndex = val.lastIndexOf("{");
        const closeIndex = val.lastIndexOf("}");

        if (openIndex > closeIndex) {
          // Only trigger suggestions for unfinished token
          const keyword = val
            .slice(openIndex + 1)
            .trim()
            .toLowerCase();

          const filtered = dynamicValues.filter((item) =>
            item.toLowerCase().includes(keyword)
          );

          $suggestionBox.html(
            filtered.length
              ? filtered.map((item) => `<li>${item}</li>`).join("")
              : "<li>No matches found</li>"
          );
          $suggestionBox.css({
            top: offset.top + $(this).outerHeight(),
            left: offset.left,
            width: $(this).outerWidth(),
            display: "block",
          });
        } else {
          // No active brace — hide box
          $suggestionBox.hide();
        }
      } else {
        $suggestionBox.hide();
      }

      activeInput = $(this);
    });

    // Insert selected suggestion and suppress re-trigger
    $(document).on("click", ".epasscard-suggestion-box li", function () {
      if (activeInput) {
        suppressSuggestions = true;

        const currentVal = activeInput.val();
        const braceIndex = currentVal.lastIndexOf("{");

        const updatedValue =
          braceIndex >= 0 ? currentVal.slice(0, braceIndex) : currentVal;

        const selected = $(this).text();
        const newVal = updatedValue + selected;

        activeInput.val(newVal).focus();
        $suggestionBox.hide();

        // Delay reactivation to ignore immediate keyup
        setTimeout(() => {
          suppressSuggestions = false;
        }, 300);
      }

      // Trigger keyup for show data in mobile preview
      $(".epasscard-header-fields .product-name-label").trigger("keyup");
      $(".epasscard-secondary-fields .model-value").trigger("keyup");
      $(".epasscard-secondary-fields .purchase-value").trigger("keyup");
      $(".back-fields-wrap textarea.epasscard-watch-input").trigger("keyup");
    });

    // Hide suggestion box on blur
    $(document).on("blur", ".epasscard-watch-input", function () {
      setTimeout(() => $suggestionBox.hide(), 200);
    });
  }

  //Render first card on page load
  const $firstCard = $(".epasscard-card-item").first();
  if ($firstCard.length) {
    renderCardData($firstCard, 1);
  }

  //Bind click handler
  $(".epasscard-card-item").click(function (e) {
    e.preventDefault();
    const index = $(".epasscard-card-item").index(this);
    // Skip the fourth item (index 3)
    if (index !== 6) {
      $(".epasscard-card-item").removeClass("epasscard-active-card");
      $(this).addClass("epasscard-active-card");
    }

    renderCardData($(this), 2);
  });

  // Card list scripts
  $(document).on(
    "click",
    ".epasscard-dropdown-container .dropdown-toggle img",
    function (e) {
      e.stopPropagation(); // Prevent event bubbling to window
      $(this)
        .closest(".epasscard-dropdown-container")
        .find(".dropdown-menu")
        .removeClass("epasscard-hidden");
    }
  );

  // Close dropdown when clicking outside
  $(window).on("click", function (e) {
    if (!$(e.target).closest(".epasscard-dropdown-container").length) {
      $(".dropdown-menu").addClass("epasscard-hidden");
    }
  });

  function changeBarcodeImage(selectedValue) {
    let scanImageName;
    if (selectedValue == "qrCode") {
      scanImageName = "qrcode.png";
    } else if (selectedValue == "CODE_128") {
      scanImageName = "barcode.png";
    } else if (selectedValue == "PDF_417") {
      scanImageName = "barcode_pdf417.png";
    } else if (selectedValue == "AZTEC") {
      scanImageName = "aztec.png";
    } else {
      scanImageName = "qrcode.png";
    }
    const scanningImagePath = pluginUrl + "/assets/img/images/" + scanImageName;
    $(".mobile-preview .qrcode-container img.qrcode-img").attr(
      "src",
      scanningImagePath
    );
  }

  //Delete Template
  $(".epasscard-template .dropdown-menu .danger").on("click", function (e) {
    e.preventDefault();

    // Get pass_id from the Edit link inside the same dropdown-toggle
    const passId = $(this)
      .closest(".dropdown-menu")
      .prev(".dropdown-toggle")
      .find("a")
      .attr("href")
      .match(/pass_id=(\d+)/)[1];

    // Show modal and inject Yes/No buttons
    const modal = $(".epass-info-modal");
    const modalContent = modal.find(".info-notification-data");
    modalContent.html(`
    <p>Are you sure you want to delete this template?</p>
    <button id="confirm-delete" class="btn btn-danger">Yes</button>
    <button id="cancel-delete" class="btn btn-secondary">No</button>
  `);
    modal.show();

    // Handle Yes click
    $(".epass-info-modal #confirm-delete").on("click", function () {
      const apiUrl = `${epassCardApiUrl}/delete-pass-template/${passId}`;
      $.ajax({
        url: apiUrl,
        method: "DELETE",
        headers: {
          "x-api-key": apiKey,
        },
        success: function (response) {
          modalContent.html(
            `<p class="text-success">✅ Template deleted successfully. Reloading...</p>`
          );
          setTimeout(() => location.reload(), 3000);
        },
        error: function () {
          modalContent.html(
            `<p class="text-danger">❌ Failed to delete template. Please try again.</p>`
          );
          setTimeout(() => modal.hide(), 3000);
        },
      });
    });

    // Handle No click
    $(".epass-info-modal #cancel-delete").on("click", function () {
      modal.hide();
    });
  });
});

// Pass limit alert box
jQuery(document).ready(function ($) {
  $('.epass-alert-box .alert-close').on('click', function () {
    $(this).closest('.epass-alert-box').remove();
  });
});

