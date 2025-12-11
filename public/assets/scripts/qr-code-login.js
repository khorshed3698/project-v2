$(document).ready(function () {
    var previousLogStatus = '';
    var qrCodeShowInterval = '';

    function qrCodeShow() {

        var qrCodeShowObj = $("#qrCodeShow");
        qrCodeShowObj.html("<img src=" + base_url_qr + "/assets/images/dual-ring-loader.gif" + ">");


        $.ajax({
            type: "GET",
            url: base_url_qr + "/qr-code/show",
            data: {
                _token : token,
            },
            dataType: "json",
            success: function (response) {
                if (response.responseCode === 1) {
                    qrCodeShowObj.html(response.qrCode);
                    $('#qr_code_text').show();
                } else {
                    $('#qr_code_text').hide();
                    qrCodeShowObj.html(response.message);
                }
                qrCodeShowInterval = setTimeout(qrCodeShow, 180000);
            }
        });
    }


    function qrLoginCheck() {

        $.ajax({
            type: "GET",
            url: base_url_qr + "/qr-login-check",
            data: {
                _token : token
            },
            dataType: "json",
            success: function (response) {
                var currentLogStatus = response.loggedIn;
                if ((response.responseCode === 1) && (response.loggedIn === 1)) {
                    clearTimeout(qrCodeShowInterval);
                    $('#qrCodeShow').html('');
                    $('#qr_code_text').hide();
                    $('#loginStatus #last_login_info').html('Last login '+response.last_login);
                    $('#loginStatus').show();
                    $('#logInOut').html('<div class="col-md-6"> <button class="btn btn-success" type="button"><i class="fa fa-check" aria-hidden="true"></i> Logged In</button> </div> <div class="col-md-6"> <button type="button" class="btn btn-danger" id="qrLogOutBtn"><i class="fa fa-sign-out" aria-hidden="true"></i> Log out</button> </div>');
                } else {
                    $('#loginStatus').hide();
                    $('#logInOut').html('');
                    if (currentLogStatus !== previousLogStatus) {
                        qrCodeShow();
                    }
                }
                previousLogStatus = response.loggedIn;
                setTimeout(qrLoginCheck, 5000);
            }
        });

    }

    qrLoginCheck();


    $(document).on('click', '#qrLogOutBtn', function () {

        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
        btn.prop('disabled', true);

        $.ajax({
            type: "GET",
            url: base_url_qr + "/qr-log-out",
            data: {
                _token : token
            },
            success: function (response) {
            }
        });

    });

    $(document).on('click','#reload_icon',function () {
        window.location.reload();
    })
});