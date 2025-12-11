<script src="{{ asset("assets/plugins/croppie-2.6.2/croppie.min.js") }}"></script>
<script src="{{ asset("assets/plugins/facedetection.js") }}" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script>
    function resetImage(input) {
        var imgSrc = input.getAttribute('data-src');
        var html = '<img src="' + imgSrc + '" class="img-thumbnail" alt="Profile Picture" id="investorPhotoViewer" alt="investorPhotoViewer"/>';
        $('#investor_photo_base64').val('');
        $('#investorPhotoViewerDiv').prepend(html);
        $("#investorPhotoUploadBtn").removeClass('hidden');
        $("#cropImageBtn").remove();
        $('div.croppie-container').remove();
        $('#investorPhotoUploadBtn').val('');
        $('#investorPhotoResetBtn').addClass('hidden');
    }

    function cropImageAndSetValue(fieldName) {
        uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'original'
        }).then(function (resp) {
            $('#'+fieldName).val(resp);
            toastr.success('Image Cropped & Set');
        });
    }

    function readURLUser(input) {
        var photo_size_kb = parseFloat((input.files[0].size) / 1024).toFixed(2); //KB Calculation
        var max_size = 1024; //maximum size
        if (input.files && input.files[0]) {
            $("#investorPhotoUploadError").html('');

            // validate image size
            if (max_size <= photo_size_kb){
                toastr.error(" ", 'Max Photo size 1024 KB. You have uploaded ' + photo_size_kb + ' KB', {
                    positionClass: "toast-top-center",
                });
                $('#investorPhotoViewer').remove();
                $("#investorPhotoResetBtn").click();
            }

            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                $("#investorPhotoUploadError").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#investorPhotoViewer').attr('src', e.target.result);

                $('#investor_photo_base64').val(e.target.result);

                $("#investorPhotoUploadBtn").addClass('hidden').after("<img id='waitBtn' style='height: 40px;width: 120px' src='/assets/images/loadWait.gif'>");
                $('#update_info_btn').prop('disabled', true); // Submit or save btn
            };
            reader.readAsDataURL(input.files[0]);

            uploadCrop = $('#investorPhotoViewer');
            setTimeout(function () {
                $('#investorPhotoViewer').faceDetection({
                    complete: function (faces) {
                        if (faces.length > 0) {
                            // $('.panel-heading').html('Face is detected');
                            uploadCrop.croppie({
                                viewport: {
                                    width: 180,
                                    height: 180,
                                    type: 'square'
                                },
                                boundary: {
                                    width: 180,
                                    height: 180
                                }

                                // enableResize: true,
                            });
                            toastr.warning("Please click 'Save Image' after cropping");
                            $('#investorPhotoResetBtn').removeClass('hidden');
                            $('#investorPhotoResetBtn').after(' <button type="button" id="cropImageBtn" class="btn btn-success btn-sm" onclick="cropImageAndSetValue(\'investor_photo_base64\')">Save Image</button>');
                            $('#waitBtn').remove();
                            $('#investor_photo_name').val(input.files[0].name);
                            $('#update_info_btn').prop('disabled', false); // Submit or save btn
                        } else {
                            toastr.error(" ", 'Given image is not valid! (Can\'t recognize any face)', {
                                positionClass: "toast-top-center",
                            });
                            $('#investorPhotoViewer').remove();
                            $('#waitBtn').remove();
                            $("#investorPhotoResetBtn").click();
                        }
                    }
                });

            }, 3000);

            // $("#image_name").val(data);
            // $('.ajax-file-upload-statusbar').remove();
        }
    }
</script>