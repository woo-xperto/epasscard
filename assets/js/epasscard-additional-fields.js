//  Additional properties scripts
jQuery(document).ready(function ($) {
  $(function () {
    function createFieldBlock() {
      const fieldBlock = $(`
    <div class="field-block epasscard-field-group">
      <label>Name</label>
      <input type="text" class="additional-field-name" placeholder="">

      <label>Field Type</label>
      <select class="field-type-select">
        <option value="text">Text</option>
        <option value="textarea">Textarea</option>
        <option value="file">File</option>
        <option value="datetime-local">Date Time</option>
        <option value="date">Date</option>
        <option value="number">Number</option>
        <option value="email">Email</option>
        <option value="randomUniqueCode">Random unique code</option>
        <option value="color">Color</option>
      </select>

      <div class="checkbox-group">
        <input type="checkbox" id="addition-required" checked>
        <label for="addition-required">Is This Field Required?</label>
      </div>

      <button type="button" class="remove-btn epasscard-remove-btn">Remove</button>
    </div>
  `);

      // Track previous selection to remove it later
      let previousValue = "";

      fieldBlock.find(".field-type-select").on("change", function () {
        const selectedValue = fieldBlock.find(".additional-field-name").val();
        const rechargeSelect = $(".recharge-select");

        // Remove previous option if it exists
        if (previousValue) {
          rechargeSelect.find(`option[value="${previousValue}"]`).remove();
        }

        // Add new option if valid
        if (selectedValue) {
          rechargeSelect.append(
            `<option value="${selectedValue}">${selectedValue}</option>`
          );
          previousValue = selectedValue;
        }

        rechargeSelect.trigger("change"); // Refresh Select2 if used
      });

      return fieldBlock;
    }

    // Add field block
    $(".epasscard_additional_properties .add-field-btn").on(
      "click",
      function () {
        const newBlock = createFieldBlock();
        const wrapper = $(this).closest(".epasscard_additional_properties");
        const container = wrapper.find(".fields-container");
        container.append(newBlock);
      }
    );

    // Remove field block and its option
    $(document).on(
      "click",
      ".epasscard_additional_properties .remove-btn",
      function () {
        const block = $(this).closest(".field-block");
        const fieldName = block.find(".additional-field-name").val().trim();
        const rechargeSelect = $(".recharge-select");
        const settingDynamicSelect = $(".setting-dynamic-data");

        if (fieldName) {
          rechargeSelect.find(`option[value="${fieldName}"]`).remove();
          rechargeSelect.trigger("change"); // Refresh Select2 if used
          settingDynamicSelect.find(`option[value="${fieldName}"]`).remove();
          settingDynamicSelect.trigger("change"); // Refresh Select2 if used
        }
        block.attr("status", false);
        block.css("display", "none");
      }
    );
  });


});
