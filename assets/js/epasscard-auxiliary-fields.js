jQuery(document).ready(function ($) {
const wrapper = $(".epasscard-auxiliary-fields");
const container = wrapper.find(".fields-container");


// Secondary fields add/remove
    function epc_add_auxiliary_block(index) {
      const timestamp = Date.now() + index;
      const fieldGroup = $(`
    <div class="epasscard-field-group" data-id="${timestamp}">
        <label>Label <span>*</span></label>
        <input type="text" class="auxiliary-label" placeholder="Eg. Name">

        <label>Value <span>*</span></label>
        <input type="text" class="auxiliary-value epasscard-watch-input" placeholder="">

        <label>Change message</label>
        <input type="text" class="auxiliary-msg" placeholder="Eg. Name changed to {name}">

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

      $(".mobile-preview .auxiliary-items-container").append(mobilePreview);

      return fieldGroup;
    }

    let containerLength = 1;
    wrapper.find(".add-auxiliary-field-btn").on("click", function () {
    
      if (containerLength >= 1) {
        $(this).addClass("epasscard-hidden");
      }

      const newField = epc_add_auxiliary_block(container.children().length);
      container.append(newField);
      containerLength++;
    });

    // Update mobile preview on keyup for first two inputs
    container.on("keyup", ".auxiliary-label, .auxiliary-value", function () {
      const fieldGroup = $(this).closest(".epasscard-field-group");
      const dataId = fieldGroup.attr("data-id");
      const labelValue = fieldGroup.find(".auxiliary-label").val();
      const fieldValue = fieldGroup.find(".auxiliary-value").val();

      // Update mobile preview
      const mobileItem = $(`.mobile-preview .auxiliary-items-container .detail-item[data-id="${dataId}"]`);
      mobileItem.find(".detail-label").text(labelValue || "New Label");
      mobileItem.find(".detail-value").text(fieldValue || "New Value");
    });

    container.on("click", ".remove-field-btn", function () {
      const fieldGroup = $(this).closest(".epasscard-field-group");
      const dataId = fieldGroup.attr("data-id");
      // Remove both field group and mobile preview
      fieldGroup.remove();

      $(
        `.mobile-preview .auxiliary-items-container .detail-item[data-id="${dataId}"]`
      ).remove();

      wrapper.find(".add-auxiliary-field-btn").removeClass("epasscard-hidden");
      containerLength--;
    });

});