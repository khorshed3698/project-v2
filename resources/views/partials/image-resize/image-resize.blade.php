<script src="{{ asset("assets/plugins/croppie-2.6.2/croppie.min.js") }}"></script>
<script src="{{ asset("assets/plugins/facedetection.js") }}" type="text/javascript"></script>
<link rel="stylesheet" href="{{ asset("assets/plugins/croppie-2.6.2/croppie.min.css") }}">

<script>
    function resetImage(input, additionalClass) {
        var imgSrc = input.getAttribute('data-src');
        var html = '<img src="' + imgSrc + '" class="img-thumbnail" alt="Profile Picture" id="applicantPhotoViewer" alt="applicantPhotoViewer"/>';
        $('#applicant_photo_base64').val('');
        $('#applicantPhotoViewerDiv').prepend(html);
        $("#applicantPhotoUploadBtn").removeClass('hidden');
        $("#cropImageBtn").remove();
        $("."+additionalClass).remove();
        $('#applicantPhotoUploadBtn').val('');
        $('#applicantPhotoResetBtn').addClass('hidden');
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
        if (input.files && input.files[0]) {
            $("#applicantPhotoUploadError").html('');

            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                // $("#applicantPhotoUploadError").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Image format is not valid. Please upload in jpg,jpeg or png format',
                });
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#applicantPhotoViewer').attr('src', e.target.result);

                $('#applicant_photo_base64').val(e.target.result);

                $("#applicantPhotoUploadBtn").addClass('hidden').after("<img id='waitBtn' style='height: 40px;width: 120px' src='/assets/images/loadWait.gif'>");
                $('#update_info_btn').prop('disabled', true); // Submit or save btn
            };
            reader.readAsDataURL(input.files[0]);

            uploadCrop = $('#applicantPhotoViewer');
            setTimeout(function () {
                $('#applicantPhotoViewer').faceDetection({
                    complete: function (faces) {
                        if (faces.length > 0) {
                            // $('.panel-heading').html('Face is detected');
                            uploadCrop.croppie({
                                viewport: {
                                    width: 100,
                                    height: 100,
                                    type: 'square'
                                },
                                boundary: {
                                    width: 105,
                                    height: 105
                                }

                                // enableResize: true,
                            });
                            toastr.warning("Please click 'Save Image' after cropping");
                            $('#applicantPhotoResetBtn').removeClass('hidden');
                            console.log($('.croppie-container').last().addClass(input.id));
                            $('#applicantPhotoResetBtn').after(' <button type="button" id="cropImageBtn" class="btn btn-success btn-sm" onclick="cropImageAndSetValue(\'applicant_photo_base64\')">Save Image</button>');
                            $('#waitBtn').remove();
                            $('#applicant_photo_name').val(input.files[0].name);
                            $('#update_info_btn').prop('disabled', false); // Submit or save btn
                        } else {
                            toastr.error(" ", 'Given image is not valid! (Can\'t recognize any face)', {
                                positionClass: "toast-top-center",
                            });
                            $('#applicantPhotoViewer').remove();
                            $('#waitBtn').remove();
                            $("#applicantPhotoResetBtn").click();
                        }
                    }
                });

            }, 3000);

            // $("#image_name").val(data);
            // $('.ajax-file-upload-statusbar').remove();
        }
    }



    // upload signature without face detection

    function resetSignature(input, additionalClass) {
        var imgSrc = input.getAttribute('data-src');
        var html = '<img src="' + imgSrc + '" class="img-thumbnail" alt="Signature" id="applicantSignatureViewer" alt="applicantSignatureViewer"/>';
        $('#applicant_signature_base64').val('');
        $('#applicantSignatureViewerDiv').prepend(html);
        $("#applicantSignatureUploadBtn").removeClass('hidden');
        $("#cropSignatureBtn").remove();
        $("."+additionalClass).remove();
        $('#applicantSignatureUploadBtn').val('');
        $('#applicantSignatureResetBtn').addClass('hidden');
    }

    function cropSignatureAndSetValue(fieldName) {
        uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'original'
        }).then(function (resp) {
            $('#'+fieldName).val(resp);
            toastr.success('Image Cropped & Set');
        });
    }

    function readURLUserSignature(input) {
        if (input.files && input.files[0]) {
            $("#applicantSignatureUploadError").html('');

            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                // $("#applicantSignatureUploadError").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Image format is not valid. Please upload in jpg,jpeg or png format',
                });
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#applicantSignatureViewer').attr('src', e.target.result);
                $('#applicant_signature_base64').val(e.target.result);
                $("#applicantSignatureUploadBtn").addClass('hidden').after("<img id='waitBtn' style='height: 40px;width: 120px' src='/assets/images/loadWait.gif'>");
                $('#update_info_btn').prop('disabled', true); // Submit or save btn
            };
            reader.readAsDataURL(input.files[0]);

            uploadCrop = $('#applicantSignatureViewer');
            setTimeout(function () {
                $('#applicantSignatureViewer').faceDetection({
                    complete: function (faces) {
                        // $('.panel-heading').html('Face is detected');
                        uploadCrop.croppie({
                            viewport: {
                                width: 150,
                                height: 40,
                                type: 'square'
                            },
                            boundary: {
                                width: 155,
                                height: 45
                            }

                            // enableResize: true,
                        });
                        toastr.warning("Please click 'Save Image' after cropping");
                        $('#applicantSignatureResetBtn').removeClass('hidden');
                        $('.croppie-container').last().addClass(input.id);
                        $('#applicantSignatureResetBtn').after(' <button type="button" id="cropSignatureBtn" class="btn btn-success btn-sm" onclick="cropSignatureAndSetValue(\'applicant_signature_base64\')">Save Image</button>');
                        $('#waitBtn').remove();
                        $('#applicant_signature').val(input.files[0].name);
                        $('#update_info_btn').prop('disabled', false); // Submit or save btn
                    }
                });

            }, 3000);

            // $("#image_name").val(data);
            // $('.ajax-file-upload-statusbar').remove();
        }
    }



    // upload company logo without face detection

    function resetCompanyLogo(input, additionalClass) {
        var imgSrc = input.getAttribute('data-src');
        var html = '<img src="' + imgSrc + '" class="img-thumbnail" alt="Company Logo" id="companyLogoViewer" alt="companyLogoViewer"/>';
        $('#company_logo_base64').val('');
        $('#companyLogoViewerDiv').prepend(html);
        $("#companyLogoUploadBtn").removeClass('hidden');
        $("#cropCompanyLogoBtn").remove();
        $("."+additionalClass).remove();
        $('#companyLogoUploadBtn').val('');
        $('#companyLogoResetBtn').addClass('hidden');
    }

    function cropCompanyLogoAndSetValue(fieldName) {
        uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'original'
        }).then(function (resp) {
            $('#'+fieldName).val(resp);
            toastr.success('Image Cropped & Set');
        });
    }

    function readURLCompanyLogo(input) {
        if (input.files && input.files[0]) {
            $("#companyLogoUploadError").html('');

            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                // $("#companyLogoUploadError").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Image format is not valid. Please upload in jpg,jpeg or png format',
                });
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#companyLogoViewer').attr('src', e.target.result);
                $('#company_logo_base64').val(e.target.result);
                $("#companyLogoUploadBtn").addClass('hidden').after("<img id='waitBtn' style='height: 40px;width: 120px' src='/assets/images/loadWait.gif'>");
                $('#update_info_btn').prop('disabled', true); // Submit or save btn
            };
            reader.readAsDataURL(input.files[0]);

            uploadCrop = $('#companyLogoViewer');
            setTimeout(function () {
                $('#companyLogoViewer').faceDetection({
                    complete: function (faces) {
                        // $('.panel-heading').html('Face is detected');
                        uploadCrop.croppie({
                            viewport: {
                                width: 90,
                                height: 90,
                                type: 'square'
                            },
                            boundary: {
                                width: 95,
                                height: 95
                            }

                            // enableResize: true,
                        });
                        toastr.warning("Please click 'Save Image' after cropping");
                        $('#companyLogoResetBtn').removeClass('hidden');
                        $('.croppie-container').last().addClass(input.id);
                        $('#companyLogoResetBtn').after(' <button type="button" id="cropCompanyLogoBtn" class="btn btn-success btn-sm" onclick="cropCompanyLogoAndSetValue(\'company_logo_base64\')">Save Image</button>');
                        $('#waitBtn').remove();
                        $('#company_logo').val(input.files[0].name);
                        $('#update_info_btn').prop('disabled', false); // Submit or save btn
                    }
                });

            }, 3000);

            // $("#image_name").val(data);
            // $('.ajax-file-upload-statusbar').remove();
        }
    }


    // upload incumbent photo with face detection
    function resetIncumbentPhoto(input, additionalClass) {
        var imgSrc = input.getAttribute('data-src');
        var html = '<img src="' + imgSrc + '" class="img-thumbnail" alt="Incumbent Picture" id="incumbentPhotoViewer" alt="incumbentPhotoViewer"/>';
        $('#incumbent_photo_base64').val('');
        $('#incumbentPhotoViewerDiv').prepend(html);
        $("#incumbentPhotoUploadBtn").removeClass('hidden');
        $("#cropIncumbentPhotoBtn").remove();
        $("."+additionalClass).remove();
        $('#incumbentPhotoUploadBtn').val('');
        $('#incumbentPhotoResetBtn').addClass('hidden');
    }

    function cropIncumbentImageAndSetValue(fieldName) {
        uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'original'
        }).then(function (resp) {
            $('#'+fieldName).val(resp);
            toastr.success('Image Cropped & Set');
        });
    }


    function readURLUserIncumbentPhoto(input) {
        if (input.files && input.files[0]) {
            $("#incumbentPhotoUploadError").html('');

            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                // $("#incumbentPhotoUploadError").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Image format is not valid. Please upload in jpg,jpeg or png format',
                });
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#incumbentPhotoViewer').attr('src', e.target.result);

                $('#incumbent_photo_base64').val(e.target.result);

                $("#incumbentPhotoUploadBtn").addClass('hidden').after("<img id='waitBtn' style='height: 40px;width: 120px' src='/assets/images/loadWait.gif'>");
                $('#update_info_btn').prop('disabled', true); // Submit or save btn
            };
            reader.readAsDataURL(input.files[0]);

            uploadCrop = $('#incumbentPhotoViewer');
            setTimeout(function () {
                $('#incumbentPhotoViewer').faceDetection({
                    complete: function (faces) {
                        if (faces.length > 0) {
                            // $('.panel-heading').html('Face is detected');
                            uploadCrop.croppie({
                                viewport: {
                                    width: 100,
                                    height: 100,
                                    type: 'square'
                                },
                                boundary: {
                                    width: 105,
                                    height: 105
                                }

                                // enableResize: true,
                            });
                            toastr.warning("Please click 'Save Image' after cropping");
                            $('#incumbentPhotoResetBtn').removeClass('hidden');
                            $('.croppie-container').last().addClass(input.id);
                            $('#incumbentPhotoResetBtn').after(' <button type="button" id="cropIncumbentPhotoBtn" class="btn btn-success btn-sm" onclick="cropIncumbentImageAndSetValue(\'incumbent_photo_base64\')">Save Image</button>');
                            $('#waitBtn').remove();
                            $('#incumbent_photo_name').val(input.files[0].name);
                            $('#update_info_btn').prop('disabled', false); // Submit or save btn
                        } else {
                            toastr.error(" ", 'Given image is not valid! (Can\'t recognize any face)', {
                                positionClass: "toast-top-center",
                            });
                            $('#incumbentPhotoViewer').remove();
                            $('#waitBtn').remove();
                            $("#incumbentPhotoResetBtn").click();
                        }
                    }
                });

            }, 3000);

            // $("#image_name").val(data);
            // $('.ajax-file-upload-statusbar').remove();
        }
    }


    // upload incumbent signature without face detection

    function resetIncumbentSignature(input, additionalClass) {
        var imgSrc = input.getAttribute('data-src');
        var html = '<img src="' + imgSrc + '" class="img-thumbnail" alt="Incumbent Signature" id="incumbentSignatureViewer" alt="incumbentSignatureViewer"/>';
        $('#incumbent_signature_base64').val('');
        $('#incumbentSignatureViewerDiv').prepend(html);
        $("#incumbentSignatureUploadBtn").removeClass('hidden');
        $("#cropIncumbentSignatureBtn").remove();
        $("."+additionalClass).remove();
        $('#incumbentSignatureUploadBtn').val('');
        $('#incumbentSignatureResetBtn').addClass('hidden');
    }

    function cropIncumbentSignatureAndSetValue(fieldName) {
        uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'original'
        }).then(function (resp) {
            $('#'+fieldName).val(resp);
            toastr.success('Image Cropped & Set');
        });
    }

    function readURLUserIncumbentSignature(input) {
        if (input.files && input.files[0]) {
            $("#incumbentSignatureUploadError").html('');

            // Validate Image type
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                // $("#incumbentSignatureUploadError").html("Image format is not valid. Only PNG or JPEG or JPG type images are allowed.");
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Image format is not valid. Please upload in jpg,jpeg or png format',
                });
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#incumbentSignatureViewer').attr('src', e.target.result);
                $('#incumbent_signature_base64').val(e.target.result);
                $("#incumbentSignatureUploadBtn").addClass('hidden').after("<img id='waitBtn' style='height: 40px;width: 120px' src='/assets/images/loadWait.gif'>");
                $('#update_info_btn').prop('disabled', true); // Submit or save btn
            };
            reader.readAsDataURL(input.files[0]);

            uploadCrop = $('#incumbentSignatureViewer');
            setTimeout(function () {
                $('#incumbentSignatureViewer').faceDetection({
                    complete: function (faces) {
                        // $('.panel-heading').html('Face is detected');
                        uploadCrop.croppie({
                            viewport: {
                                width: 150,
                                height: 40,
                                type: 'square'
                            },
                            boundary: {
                                width: 155,
                                height: 45
                            }

                            // enableResize: true,
                        });
                        toastr.warning("Please click 'Save Image' after cropping");
                        $('#incumbentSignatureResetBtn').removeClass('hidden');
                        $('.croppie-container').last().addClass(input.id);
                        $('#incumbentSignatureResetBtn').after(' <button type="button" id="cropIncumbentSignatureBtn" class="btn btn-success btn-sm" onclick="cropIncumbentSignatureAndSetValue(\'incumbent_signature_base64\')">Save Image</button>');
                        $('#waitBtn').remove();
                        $('#incumbent_signature').val(input.files[0].name);
                        $('#update_info_btn').prop('disabled', false); // Submit or save btn
                    }
                });

            }, 3000);

            // $("#image_name").val(data);
            // $('.ajax-file-upload-statusbar').remove();
        }
    }

</script>