jQuery(document).ready(function ($) {
  // Epasscard location scripts
  let modalTarget = null;
  // Open modal
  $(document).on("click", ".search-btn", function () {
    modalTarget = $(this).closest(".location-block");
    modalTarget.find("#location-modal").fadeIn();
  });

  $(document).on("click", ".close-location-modal", function () {
    modalTarget = $(this).closest(".location-block");
    modalTarget.find("#location-modal").fadeOut();
  });
  // Add new location block
  $(".add-location-btn").on("click", function () {
    const locationBlockContainer = $(this)
      .closest("#epasscard-location")
      .find(".location-container");

    const block = `
        <div class="location-block epasscard-field-group">
        <!-- Modal -->
        <div id="location-modal" class="location-modal">
            <div class="modal-content">
                <span class="close close-location-modal" id="close-location-modal">&times;</span>
                <div class="pass-locations">
                    <h4>Search Address</h4>
                    <select class="epass-location-select"></select>
                </div>
                <div>
                  <iframe class="epass-location-map" loading="lazy" allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade">
                  </iframe>
                </div>
                <button class="location-search-btn epasscard-primary-btn">Use this information</button>
            </div>
        </div>
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
          <button class="remove-location-btn epasscard-remove-btn">Remove</button>
        </div>
      `;

    // Append the block
    const $newBlock = $(block);
    locationBlockContainer.append($newBlock);

    // Initialize Select2 on the newly added select
    $newBlock.find(".epass-location-select").select2({
      placeholder: "Search for a location",
      allowClear: true,
      ajax: {
        url: epasscard_admin.ajax_url,
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            action: "get_epasscard_location",
            search: params.term || "",
            _ajax_nonce: epasscard_admin.nonce,
          };
        },
        processResults: function (data) {
          if (
            data.success &&
            data.data &&
            Array.isArray(data.data.predictions)
          ) {
            return {
              results: data.data.predictions.map(function (item) {
                return {
                  id: item.place_id,
                  text: item.description,
                };
              }),
            };
          } else {
            return { results: [] };
          }
        },
        cache: true,
      },
    });
  });

  // Remove location block
  $(document).on("click", ".remove-location-btn", function () {
    $(this).closest(".location-block").remove();
  });

  // Show location details in location blocks
  $(document).on("click", ".location-search-btn", function () {
    let locationBlock = $(this).closest(".location-block");
    let locationModal = $(this).closest(".location-modal");
    //const selectedOption = $(".epass-location-select").select2("data")[0]; // Get selected item

    const selectedOption = locationModal
      .find(".epass-location-select")
      .select2("data")[0];
    let locationName = selectedOption.text;
    locationBlock.find(".location-title").val(locationName);
    // Later retrieve it
    let location = JSON.parse(locationModal.attr("data-location"));
    locationBlock.find(".location-latitude").val(location.lat);
    locationBlock.find(".location-longitude").val(location.lng);
    locationModal.fadeOut();
  });

  $(".epass-location-select").each(function () {
    $(this).select2({
      // $(".epass-location-select").select2({
      placeholder: "Search for a location",
      allowClear: true,
      ajax: {
        url: epasscard_admin.ajax_url, // Replace with your actual domain
        dataType: "json",
        delay: 250,
        data: function (params) {
          return {
            action: "get_epasscard_location",
            search: params.term || "", // search term
            _ajax_nonce: epasscard_admin.nonce,
          };
        },
        processResults: function (data) {
          console.log(data); // Optional: for debugging

          if (
            data.success &&
            data.data &&
            Array.isArray(data.data.predictions)
          ) {
            return {
              results: data.data.predictions.map(function (item) {
                return {
                  id: item.place_id, // Value for the <option>
                  text: item.description, // Text shown in the dropdown
                };
              }),
            };
          } else {
            return {
              results: [],
            };
          }
        },
        cache: true,
      },
    });
  });
  $(document).on("change", ".epass-location-select", function (e) {
    const selectedOption = $(this).select2("data")[0]; // Get selected item
    let placeId = selectedOption.id;
    let locationName = selectedOption.text;
    let locationModal = $(this).closest(".location-modal");
    let locationMap = locationModal.find(".epass-location-map");

    if (locationName) {
      locationMap.attr(
        "src",
        "https://www.google.com/maps?q=" + locationName + "&output=embed"
      );
      locationMap.fadeIn(); // Show map with a smooth transition
    } else {
      locationMap.fadeOut(); // Hide map if no location selected
    }

    if (placeId) {
      $.ajax({
        url: epasscard_admin.ajax_url,
        type: "POST",
        data: {
          action: "load_epasscard_templates",
          place_id: placeId,
          request_identifier: "epass-search-location-details",
          _ajax_nonce: epasscard_admin.nonce,
        },
        success: function (response) {
          if (response.success && response.data.results.length > 0) {
            // Extract latitude and longitude
            var lat = response.data.results[0].geometry.location.lat;
            var lng = response.data.results[0].geometry.location.lng;

            // Set as JSON string in one attribute
            locationModal.attr(
              "data-location",
              JSON.stringify({
                lat: lat,
                lng: lng,
              })
            );
          }
        },
        error: function (xhr, status, error) {
          console.error("Request failed:", error);
        },
      });
    }
  });
});
