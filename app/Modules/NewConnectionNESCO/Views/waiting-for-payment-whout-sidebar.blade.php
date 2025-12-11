<?php
$accessMode = ACL::getAccsessRight('NewConnectionNESCO');
?>
<style>
    #loading1 {
        width: 100%;
        height: 100%;
        top: 0px;
        left: 200px;
        position: fixed;
        display: none;
        /*opacity: .9;*/
        background-color: #fff;
        z-index: 99;
        text-align: center;
        background-color: rgba(192, 192, 192, 0.3);
    }

    #loding-msg {

        position: absolute;
        margin-top: 10%;
        left: 340px;
        font-size: 24px;
        font-weight: bold;
        width: 35%;
        z-index: 600;
        padding: 20px 10px 20px 10px;
    }

    .node:active {
        fill: #fffa90;
    }


    .title {
        font-weight: 800;
        font-size: medium;
        display: block;
    }

    .textSmall {
        font-size: smaller;
    }

    .noBorder {
        border: none;
    }

    .redTextSmall {
        color: red;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 2px;
    }

    .img-thumbnail {
        height: 80px;
        width: 100px;
    }
</style>
<section class="content">

    <div class="col-md-12">

        <div id="loading_wating">

        </div>


        <div class="box">
            <div id="paymentPanel" class="panel panel-default" style="display: none;">
                <div class="panel-body">
                    <div class="panel panel-primary">
                        <div class="panel-heading panel-primary"><strong>Government & Service Fee payment</strong>
                        </div>
                        <div class="panel-body">
                            <div class="form-group clearfix">
                                <div class="box">
                                    <div id="paymentInformation">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::open(array('url' => '/new-connection-nesco/payment','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                    <div class="form-group">
                        <input hidden="" name="enc_app_id" id="enc_app_id" type="text"
                               value="{{ isset($applicationId) ? $applicationId : '' }}">
                        <input hidden="" name="enc_payment_id" type="text"
                               value="{{ isset($paymentId) ? $paymentId : '' }}">
                    </div>
                    <input type="submit" class="btn btn-primary" name="actionBtn" value="Payment">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">

    function checkNESCOStatus() {
        var app_id = $('#enc_app_id').val();

        $.ajax({
            url: '/new-connection-nesco/check-payment-info',
            type: "POST",
            data: {
                enc_app_id: app_id
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.responseCode == 1) {
                    if (response.status == -2 || response.status == -3) {
                        // $('#loading').hide();
                        // alert(response.message);
                    } else if (response.status == 0) {
                        myVar = setTimeout(checkNESCOStatus, 5000);
                    } else if (response.status == -1) {
                        $('#loding-msg-text').text(response.message)
                        myVar = setTimeout(checkNESCOStatus, 5000);
                    } else if (response.status == 1) {
                        var paymentInformation = response.paymentInformation;
                        $('#paymentInformation').html(paymentInformation);
                        $('#paymentPanel').show();
                        $('#loading_wating').hide();
                        // alert(response.message);
                    } else if (response.status == -4 || response.status == -5) {
                        alert(response.message);
                        location.replace("/process/new-connection-nesco/view/" + app_id + '/{{\App\Libraries\Encryption::encodeId(116)}}');
                    } else {
                        alert('Whoops there was some problem please contact with system admin.');
                        window.location.reload();
                    }
                } else {
                    alert('Whoops there was some problem please contact with system admin.');
                    window.location.reload();
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
                location.replace("/new-connection-nesco/list/" + '{{\App\Libraries\Encryption::encodeId(116)}}');
            },
            beforeSend: function (xhr) {

            }
        });
        return false; // keeps the page from not refreshing
    }

    $(document).ready(function () {
        checkNESCOStatus();
        countDownTimer('Connecting to NESCO Server', 'Please wait to connect...', '2:01');
    });

    function countDownTimer(connect_msg, msg, time) {
        var flagsUrl = '{{ URL::to('/assets/images/loading.gif') }}';
        $('#loading_wating').html(
            '<Span class="alert alert-success" id="loding-msg"><i class="fa fa-spinner fa-spin"></i>' +
            '<span id="loding-msg-text">' + connect_msg + '</span></Span>' +
            '<div id="loding-time">' +
            '<div class="countdown"></div>' +
            '</div>'
        );

        $("#loding-time").css({
            "position": "absolute",
            "color": "#f0ad4e",
            "margin-top": "16%",
            "left": "340px",
            "font-size": "24px",
            "font-weight": "bold",
            "width": "35%",
            "z-index": "600",
            "padding": "20px 10px",
            "text-align": "center",
            "background-color": " #dff0d8",
            "border": " 1px solid transparent",
            "border-radius": "4px",
        });
        $(".countdown").css({
            "border": "2px dashed #f0ad4e",
            "border-radius": "4px",
        });
        $("#loding-msg").css({
            "position": "absolute",
            "margin-top": "10%",
            "left": "340px",
            "font-size": "24px",
            "font-weight": "bold",
            "width": "35%",
            "z-index": "600",
            "padding": "20px 10px 20px 10px",
        });

        var timer2 = time;
        var t = timer2.split(':');
        //convert to micro second
        var sec = (parseInt(t[0]) * 60) + parseInt(t[1]) + '000';

        var interval = setInterval(function () {
            var timer = timer2.split(':');
            //by parsing integer, I avoid all extra string processing
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) clearInterval(interval);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            if (minutes == 0) {
                $('.countdown').html(msg + '<br/>' + seconds + ' Seconds');
            } else {
                $('.countdown').html(msg + '<br/>' + minutes + ' Minutes' + ' ' + seconds + ' Seconds');
            }
            timer2 = minutes + ':' + seconds;
            console.log(timer2);
        }, 1000);
        setTimeout(function () {
            location.reload();
        }, sec);

    }

</script>
