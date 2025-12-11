function checkTrackingNoExists(input) {
    var _token = $('input[name="_token"]').val();
    // Remove special characters, dots, and spaces from the beginning and end of the string
    // var manually_approved_br_no = input.value.trim().replace(/^[^a-zA-Z0-9]*|[^a-zA-Z0-9]*$/g, '');
    var manually_approved_br_no = input.value.trim().replace(/(^[^a-zA-Z0-9]*)|([^a-zA-Z0-9]*$)/g, '');

    // Remove spaces from within the string
    manually_approved_br_no = manually_approved_br_no.replace(/\s/g, '');
    // Check if the input is not empty before sending the AJAX request
    if (manually_approved_br_no !== '') {
        // Perform an AJAX request to check manually_approved_br_no in the database
        $.ajax({
            type: "GET",
            url: "/bida-registration-amendment/check-tracking-no-exists",
            data: {
                _token: _token,
                manually_approved_br_no: manually_approved_br_no
            },
            success: function(response) {
                if (response.responseCode == 1 && response.exists) {
                    swal({ type: 'error', text: "You've already taken application online using this tracking number." });
                    input.value = ''; // Clear the input if it exists in the database
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                swal({ type: 'error', text: "Unknown error occured. Please, try again after reload." });
            }
        });
    }
  }