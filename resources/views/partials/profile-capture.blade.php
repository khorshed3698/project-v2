<style>
    #my_camera, video {
        margin: 0 auto;
        display: block !important;
    }

</style>

<div class="modal fade" id="profileCaptureModal" tabindex="-1" role="dialog" aria-labelledby="profileCaptureModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="profileCaptureModalLabel">Camera</h4>
            </div>
            <div class="modal-body">
                <div id="my_camera"></div>
            </div>

            <div class="modal-footer">
                <div id="pre_take_buttons">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" onclick="preview_snapshot()">Take Snapshot</button>
                </div>
                <div id="post_take_buttons" style="display:none">
                    <button class="btn btn-danger" onclick="cancel_preview()">Take Another</button>
                    <button class="btn btn-success" onclick="save_photo()">Save Photo</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/webcam/webcam.min.js') }}"></script>
<script>

    $(function () {
        let profile_capture_btn = $("#captureProfilePicture");
        let error_msg = 'Something wrong!';

        Webcam.on('error', function (err) {
            console.log(err.message);
            if (err.message !== '') {
                document.querySelector("#captureProfilePicture").dataset.profileCapture = 'no';
                error_msg = err.message;
            }
        });

        Webcam.set({
            // live preview size (4:3)
            width: 320, // 320
            height: 240, // 240

            // device capture size
            dest_width: 320,
            dest_height: 240,

            // final cropped size
            crop_width: 320,
            crop_height: 240,

            image_format: 'jpeg',
            jpeg_quality: 100
        });
        Webcam.attach('#my_camera');



        profile_capture_btn.on('click', function (e) {
            if (document.querySelector("#captureProfilePicture").getAttribute('data-profile-capture') === 'no') {
                //$('#profileCaptureModal').modal('show');
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: error_msg,
                });

                return false;
            } else {
                // open modal
                $('#profileCaptureModal').modal('show');
            }
        });
    });

    function preview_snapshot() {
        // freeze camera so user can preview pic
        Webcam.freeze();

        // swap button sets
        document.getElementById('pre_take_buttons').style.display = 'none';
        document.getElementById('post_take_buttons').style.display = '';
    }

    function cancel_preview() {
        // cancel preview freeze and return to live camera feed
        Webcam.unfreeze();

        // swap buttons back
        document.getElementById('pre_take_buttons').style.display = '';
        document.getElementById('post_take_buttons').style.display = 'none';
    }

    function save_photo() {
        // actually snap photo (from preview freeze) and display it
        Webcam.snap( function(data_uri) {

            // Configure dynamic data
            let img_preview_div_id = 'applicant_photo_preview';
            let base64_value_field_id = 'applicant_photo_base64';
            let input_id = 'applicant_photo';

            // display results in page
            document.getElementById(img_preview_div_id).setAttribute('src', data_uri);
            document.getElementById(base64_value_field_id).value = data_uri;

            //remove previous reset button
            var resetButton = document.getElementById('reset_image_'.concat(input_id));
            if (resetButton) {
                resetButton.remove();
            }

            // Create reset button
            var preview_parent_div = document.getElementById(img_preview_div_id).parentNode.parentNode;
            var btn_elem = document.createElement("button");
            btn_elem.type = 'button';
            btn_elem.id = 'reset_image_'.concat(input_id);
            btn_elem.innerHTML = 'Reset image';
            btn_elem.className = 'btn btn-warning btn-xs reset-image';
            btn_elem.value = [input_id, base64_value_field_id, img_preview_div_id];
            btn_elem.onclick = function () {
                resetImage(btn_elem.value, this);
            };
            preview_parent_div.parentNode.insertBefore(btn_elem, preview_parent_div.nextSibling);

            // swap buttons back
            document.getElementById('pre_take_buttons').style.display = '';
            document.getElementById('post_take_buttons').style.display = 'none';

            $("#profileCaptureModal").modal('hide');
        } );
    }
</script>