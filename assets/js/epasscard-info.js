jQuery(document).ready(function ($) {
  $(".created-passes").on("input", function () {
    let maximumPass = $(this).val();
    //epass_get_profile(maximumPass);

    const passData = JSON.parse(
      $("#PassInfo .pass-limit-check").attr("data-pass-stats")
    );
    const numberOfPass = passData.numberOfPass;
    const totalCreatedPass = passData.totalCreatedPass;
    const availablePass = numberOfPass - totalCreatedPass;

    if (availablePass < maximumPass) {
      $(".info-notification-data").html(
        `Your current package features maximum ${numberOfPass} passes.<br>` +
          `Total pass active: ${totalCreatedPass}<br>` +
          `Available pass limit: ${availablePass}`
      );

      $(".epass-info-modal").css("display", "block");
      $(".created-passes").val(0);
    }
  });

  $(".epass-info-modal .close").on("click", function () {
    $(".epass-info-modal").css("display", "none");
  });
});
