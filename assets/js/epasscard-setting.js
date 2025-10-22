jQuery(document).ready(function ($) {
  //  Epasscard setting template script

  $(document).on("click", ".expire-card", function () {
    $(this)
      .closest(".epasscard-field-group")
      .find(".expire-options")
      .toggleClass("epasscard-hidden");
  });

  // $(".recharge-select").on("change", function () {
  //   const values = $(this).val();
  //   const table = $(this).siblings(".recharge-table");
  //   const tbody = table.find("tbody");
  //   tbody.empty();

  //   if (values && values.length > 0) {
  //     values.forEach((val) => {
  //       const dropdown = `
  //       <select class="action-select">
  //         <option disabled selected value="">Please select one</option>
  //         <option value="add_deduct">Add/Deduct</option>
  //         <option value="replace" selected>Replace</option>
  //       </select>
  //     `;

  //       const row = `
  //       <tr>
  //         <td>${val}</td>
  //         <td>${dropdown}</td>
  //       </tr>
  //     `;
  //       tbody.append(row);
  //     });
  //     table.show();
  //   } else {
  //     table.hide();
  //   }
  // });

  // $(".recharge-select").each(function () {
  //   $(this).select2({
  //     placeholder: "Select recharge options",
  //     width: "100%",
  //   });
  // });

  $(".recharge-select").on("change", function () {
    const values = $(this).val() || [];
    const table = $(this).siblings(".recharge-table");
    const tbody = table.find("tbody");

    // Get current selections before clearing
    const currentSelections = {};
    tbody.find("tr").each(function () {
      const field = $(this).find("td:first").text();
      const action = $(this).find(".action-select").val();
      currentSelections[field] = action;
    });

    tbody.empty();

    if (values && values.length > 0) {
      values.forEach((val) => {
        // Use existing selection if available, otherwise default to "replace"
        const selectedAction = currentSelections[val] || "replace";

        const dropdown = `
            <select class="action-select">
                <option disabled value="">Please select one</option>
                <option value="add_deduct" ${
                  selectedAction === "add_deduct" ? "selected" : ""
                }>Add/Deduct</option>
                <option value="replace" ${
                  selectedAction === "replace" ? "selected" : ""
                }>Replace</option>
            </select>
            `;

        const row = `
            <tr>
                <td>${val}</td>
                <td>${dropdown}</td>
            </tr>
            `;
        tbody.append(row);
      });
      table.show();
    } else {
      table.hide();
    }
  });

  $(".recharge-select").each(function () {
    $(this).select2({
      placeholder: "Select recharge options",
      width: "100%",
    });
  });

  $("#expire-card").on("change", function () {
    $("#expire-options").toggle(this.checked);
  });

  $(".rechargeable-card").on("change", function () {
    $(this)
      .closest(".setting-group")
      .find(".recharge-options")
      .toggle(this.checked);
    //$(".recharge-options").toggle(this.checked);
  });

  $('input[name="expire_type"]').on("change", function () {
    const type = $(this).val();
    const container = $("#expire-options .expire-input");
    if (type === "fixed") {
      $(".expire-input").removeClass("epasscard-hidden");
      $(".dynamic-input").addClass("epasscard-hidden");
    } else {
      $(".expire-input").addClass("epasscard-hidden");
      $(".dynamic-input").removeClass("epasscard-hidden");
    }
  });
});
