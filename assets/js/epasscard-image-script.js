jQuery(document).ready(function ($) {
    let epasscardCroppie;
    let epasscardCurrentPreviewSelector;
    let epasscardOriginalImageData;

    function closeEpasscardModal() {
        $("#epasscard-cropper-modal").hide();
        if (epasscardCroppie) {
            epasscardCroppie.destroy();
            epasscardCroppie = null;
        }
    }

    function openEpasscardCropperModal(imageSrc, previewSelector) {
        $("#epasscard-cropper-image").attr("src", imageSrc);
        $("#epasscard-cropper-modal").show();
        $("#epasscard-cropper-modal").attr("selected-image", previewSelector);
        epasscardCurrentPreviewSelector = previewSelector;
        epasscardOriginalImageData = imageSrc;

        if (epasscardCroppie) epasscardCroppie.destroy();

        epasscardCroppie = new Croppie(document.getElementById("epasscard-cropper-image"), {
            viewport: { width: 250, height: 250, type: 'square' },
            boundary: { width: 300, height: 300 },
            showZoomer: true
        });

        epasscardCroppie.bind({ url: imageSrc });
    }

    function handleEpasscardImageCrop(inputSelector, previewSelector) {
        $(inputSelector).on("change", function () {
            const input = this;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    openEpasscardCropperModal(e.target.result, previewSelector);
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    }

    $("#epasscard-crop-btn").on("click", function () {
        epasscardCroppie.result({ type: 'base64', format: 'png' }).then(function (croppedImage) {
            const selectedImage = $("#epasscard-cropper-modal").attr("selected-image");

            $(epasscardCurrentPreviewSelector).attr("src", croppedImage);

            if (selectedImage == ".preview-background") {
                $(".coupon-strip").css("background-image", "url('" + croppedImage + "')");
            } else if (selectedImage == ".preview-logo") {
                $(".logo-img").attr("src", croppedImage);
            }

            // Show image for card Generic Pass
            $(".mobile-preview .epc-image-block > img").attr("src", croppedImage);

            closeEpasscardModal();
        });
    });

    $(".epasscard-cropper-close").on("click", closeEpasscardModal);

    $("#epasscard-cropper-modal").on("click", function (e) {
        if ($(e.target).closest(".epasscard-cropper-container").length === 0) {
            closeEpasscardModal();
        }
    });

    // Bind to your image inputs
    handleEpasscardImageCrop(".background-image", ".preview-background");
    handleEpasscardImageCrop(".icon", ".preview-icon");
    handleEpasscardImageCrop(".logo", ".preview-logo");
});
