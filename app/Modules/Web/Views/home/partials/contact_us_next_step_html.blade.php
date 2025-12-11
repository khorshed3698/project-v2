<!-- Step 2: Details and Captcha in Modal -->
<div class="modal" id="step2Modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Your Message</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="contact-form">
                    {!! Form::open([ 'method' => 'post', 'id' => 'form_step2', 'enctype' => 'multipart/form-data', ]) !!}
                    <div class="form-group">
                        {!! Form::textarea('details', null, ['placeholder' => 'Details*', 'class' => 'form-control details', 'size' => '5x3']) !!}
                        {!! $errors->first('details', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.public_key') }}"></div>
                    </div>
                    <button class="btn" type="submit" id="submit">Submit</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" defer></script>

<script>
    $(document).ready(function () {

        $('#submit').click(function (event) {
            if (grecaptcha.getResponse() == '') {
                event.preventDefault(); // Prevent form submission
                $('#recaptchaDiv').addClass('has-error');
                Swal.fire({
                    title: "Error!",
                    text: "Please Complete the reCAPTCHA",
                });
            }
            // check details textarea is not empty
            if ($('.details').val() == '') {
                event.preventDefault(); // Prevent form submission
                $('.details').addClass('has-error');
                Swal.fire({
                    title: "Error!",
                    text: "Please Enter Details",
                });
            }
        });

        $('#form_step2').on('submit', function(event) {
            event.preventDefault();

            // Check if the form is valid
            var isValid = true;
            var isValidPhoneNumber = false;

            $('.required').each(function() {
                if ($(this).val().trim() === '') {
                    isValid = false;
                    $(this).closest('.form-group').addClass('has-error');
                } else {
                    $(this).closest('.form-group').removeClass('has-error');
                }
            });

            if (!isValid) {
                swal("Error", "Please fill in all required fields", "error");
                return false;
            }
    
            var pattern = /^(01|008801|8801|\+8801)[3-9]{1}\d{8}$/;
            if (pattern.test(document.getElementById('contactPhoneNumber').value)) {
                isValidPhoneNumber = true;
            }

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (isValidPhoneNumber) {
                $.ajax({
                    url: '{{ route("contact.store") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: $('#form_id, #form_step2').serialize(),
                    success: function(response) {
                        if(response.success == false) {
                            swal("Error", response.message, "error");
                            return false;
                        }
                        swal("Success", response.message, "success");
                        $('#form_id, #form_step2')[0].reset();
                        $('#step2Modal').modal('hide');
                        grecaptcha.reset();
                        $('.required-text').show(); // Show all required-text elements
                    },
                    error: function(xhr, status, error) {
                        // Handle error if needed
                        swal("Error", 'An Error Occure', "error");
                    }
                });
            } else {
                swal("Error", "Please fill in all required fields and check the phone number", "error");
                return false;
            }
        });
    });
</script>