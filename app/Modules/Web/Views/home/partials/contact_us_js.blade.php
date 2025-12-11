{{-- <script src="{{ asset('assets/scripts/jquery.validate.min.js') }}"></script> --}}
<script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript" defer></script>

<script>
        $("#form_id").validate({
            errorPlacement: function () {
                return true;
            }
        });

        var textField = $('input[type="text"]');
        textField.on('keydown input', function() {
            var parentNode = $(this).closest('.form-group').find('.required-text')
            if($(this).val() != '' )
            {
                parentNode.hide();
            }
            else
            {
                parentNode.show();
            }
            
        });

        var textField = $('.required-text');
        textField.on('click', function(){
            var parentNode = $(this).closest('.form-group').find('input[type="text"]')
            parentNode.click();
            parentNode.focus();
        });

        /**
         * number field
        */
        var textField = $('input[type="number"]');
        textField.on('keydown input', function() {
            var parentNode = $(this).closest('.form-group').find('.required-text')
            if($(this).val() != '' )
            {
                parentNode.hide();
            }
            else
            {
                parentNode.show();
            }
            
        });

        var textField = $('.required-text');
        textField.on('click', function(){
            var parentNode = $(this).closest('.form-group').find('input[type="number"]')
            parentNode.click();
            parentNode.focus();
        });


        var textField = $('textarea'); 
        textField.on('keydown input', function() {
            var parentNode = $(this).closest('.form-group').find('.required-text')
            if($(this).val() != '')
            {
                parentNode.hide();
            }
            else
            {
                parentNode.show();
            }
        })

        var textField = $('.required-text');
        textField.on('click', function(){
            var parentNode = $(this).closest('.form-group').find('textarea')
            parentNode.click();
            parentNode.focus();
        })


    $(document).ready(function () {
        // Move to Step 2
        $('#nextStep').click(function () {
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
            if (!isValidPhoneNumber){
                swal("Error", "Please fill in all required fields and check the phone number", "error");
                return false;
            }
            // make ajax call for step 2 html
            $.ajax({
                url: '{{ route("contact.next_step_html") }}',
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    $('#step2Div').html(response);
                    // disable modal hide when click outside
                    $('#step2Modal').modal({ backdrop: 'static', keyboard: false });
                    // show modal
                    $('#step2Modal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            })
        });

    });
</script>

