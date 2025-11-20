
jQuery(document).ready(function ($) {
    // images scripts
    let epasscardCropper;
    let epasscardCurrentPreviewSelector;
    let epasscardOriginalImageData;

    function closeEpasscardModal() {
        $("#epasscard-cropper-modal").hide();
        if (epasscardCropper) {
            epasscardCropper.destroy();
        }
    }

    function openEpasscardCropperModal(imageSrc, previewSelector) {
        $("#epasscard-cropper-image").attr("src", imageSrc);
        $("#epasscard-cropper-modal").show();
        $("#epasscard-cropper-modal").attr("selected-image", previewSelector);
        epasscardCurrentPreviewSelector = previewSelector;
        epasscardOriginalImageData = imageSrc;

        if (epasscardCropper) epasscardCropper.destroy();

        epasscardCropper = new Cropper(
            document.getElementById("epasscard-cropper-image"),
            {
                aspectRatio: NaN,
                viewMode: 1,
                autoCropArea: 1,
            }
        );
    }

    function handleEpasscardImageCrop(inputSelector, previewSelector) {
        $(inputSelector).on("change", function () {
            const input = this;
            const previewsec = $(this)
                .closest(".image-upload-wrapper")
                .find(".preview");
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
        const canvas = epasscardCropper.getCroppedCanvas();
        const croppedImage = canvas.toDataURL("image/png");
        const selectedImage = $("#epasscard-cropper-modal").attr("selected-image");

        $(epasscardCurrentPreviewSelector).attr("src", croppedImage);

        if (selectedImage == ".preview-background") {
            $(".coupon-strip")
                .css("background-image", "url('" + croppedImage + "')");
        } else if (selectedImage == ".preview-logo") {
            $(".logo-img").attr("src", croppedImage);
        }

        //Show image for card Generic Pass
        jQuery(".mobile-preview .epc-image-block > img").attr("src", epasscardOriginalImageData);


        closeEpasscardModal();
    });

    $("#epasscard-use-original-btn").on("click", function () {
        const selectedImage = $("#epasscard-cropper-modal").attr("selected-image");

        if (selectedImage === ".preview-background") {
            // Update background and preview image
            $(".coupon-strip")
                .css("background-image", "url('" + epasscardOriginalImageData + "')");
            $(".preview-background").attr("src", epasscardOriginalImageData).show();
        } else if (selectedImage === ".preview-logo") {
            // Update logo preview and logo-img
            $(".preview-logo").attr("src", epasscardOriginalImageData).show();
            $(".logo-img").attr("src", epasscardOriginalImageData).show();
        } else {
            // Default case for other previews
            $(epasscardCurrentPreviewSelector)
                .attr("src", epasscardOriginalImageData);
        }

        //Show image for card Generic Pass
        jQuery(".mobile-preview .epc-image-block > img").attr("src", epasscardOriginalImageData);

        closeEpasscardModal();
    });

    $(".epasscard-cropper-close").on("click", closeEpasscardModal);

    // Click outside to close
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