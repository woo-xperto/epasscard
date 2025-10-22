// Back fields script
jQuery(document).ready(function ($) {
  $(function () {
    // Back field scripts
    function createBackFieldGroup(index) {
      if (index < 11) {
        const timestamp = Date.now() + index;
        const fieldGroup =
          $(`<div class="epasscard-field-group back-fields-wrap" data-id="${timestamp}">
            <label>Label (Name) <span>*</span></label>
            <input type="text" class="field-name" placeholder="Eg. Name">

            <label>Value <span>*</span></label>
            <textarea class="field-value epasscard-watch-input" rows="4" cols="100%" placeholder="Write your message here..."></textarea>

            <label>Change message (This message will be displayed when value is changed)</label>
            <input type="text" class="change-message" placeholder="eg. Name changed to {name}">

            <button type="button" class="remove-field-btn epasscard-remove-btn">Remove</button>
        </div>`);

        // Create corresponding display divs in coupon-bottom
        const displayDiv =
          $(`<div class="coupon-field-display" data-id="${timestamp}">
            <div class="display-name"></div>
            <div class="display-value"></div>
        </div>`);
        $(".coupon-bottom").append(displayDiv);
        return fieldGroup;
      } else {
        $(
          "#back_fields .right button.add-field-btn.epasscard-primary-btn"
        ).addClass("epasscard-hidden");
      }
    }

    // Add new field group
    $("#BackFields .add-field-btn").on("click", function () {
      const container = $(this)
        .closest("#back_fields .right")
        .find(".fields-container");
      const newField = createBackFieldGroup(container.children().length);
      container.append(newField);
    });

    // Remove field group and its display
    $(document).on("click", "#back_fields .remove-field-btn", function () {
      if (
        $(
          "#back_fields .right button.add-field-btn.epasscard-primary-btn"
        ).hasClass("epasscard-hidden")
      ) {
        $(
          "#back_fields .right button.add-field-btn.epasscard-primary-btn"
        ).removeClass("epasscard-hidden");
      }

      const fieldGroup = $(this).closest(".epasscard-field-group");
      const id = fieldGroup.data("id");
      fieldGroup.remove();
      $(`.coupon-field-display[data-id="${id}"]`).remove();
    });
  });

  // Update mobile back side data
  $(document).on(
    "keyup",
    "#back_fields .field-name, #back_fields .field-value",
    function () {
      const block = $(this).closest(".epasscard-field-group");
      const id = block.data("id");
      const name = block.find(".field-name").val();
      const value = block.find(".field-value").val();

      $(`.coupon-field-display[data-id="${id}"] .display-name`).text(name);
      $(`.coupon-field-display[data-id="${id}"] .display-value`).text(value);
    }
  );
});
