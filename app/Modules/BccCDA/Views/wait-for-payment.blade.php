<?php
$accessMode = ACL::getAccsessRight('BccCDA');
?>
@extends('layouts.admin')
@section('content')
    <style>
        .form-group {
            margin-bottom: 2px;
        }

    </style>
    <section class="content">

        <div class="col-md-12">
            <div id="loading">
            </div>

            <div class="box">
                <div id="paymentPanel" class="panel panel-default" style="display: none;">
                    <div class="panel-body">
                        {!! Form::open(array('url' => '/cda-bcc/payment','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
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

    <script>
        var flagsUrl = '{{ URL::to('/assets/images/loading.gif') }}';
    </script>
    <script src="{{ asset("assets/scripts/count_down.js") }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            checkBCCStatus();
            countDownTimer('Connecting to CDA Server', 'Please wait to connect...', '2:01');
        });

        function checkBCCStatus() {
            var app_id = $('#enc_app_id').val();

            $.ajax({
                url: '/cda-bcc/check-payment-info',
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
                        if (response.status == -2) {
                            var msg = '';
                            if (response.message.data.resonse && (response.message.data.resonse.status == 104)) {
                                msg = response.message.data.resonse.result.message;
                            }
                            if (response.message.data.error && (response.message.data.error.status == 103)) {
                                msg = response.message.data.error.message;
                            }
                            alert(msg);
                            location.replace("/cda-bcc/list/" + '{{\App\Libraries\Encryption::encodeId(121)}}');
                        } else if (response.status == 0) {
                            myVar = setTimeout(checkBCCStatus, 5000);
                        } else if (response.status == -1) {
                            $('#loding-msg-text').text(response.message)
                            myVar = setTimeout(checkBCCStatus, 5000);
                        } else if (response.status == 1) {
                            var paymentInformation = response.paymentInformation;
                            $('#paymentInformation').html(paymentInformation);
                            $('#paymentPanel').show();
                            $('#loading').hide();
                            // alert(response.message);
                        } else if (response.status == -4 || response.status == -5) {
                            alert(response.message);
                            location.replace("/process/cda-bcc/view/" + app_id + '/{{\App\Libraries\Encryption::encodeId(116)}}');
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
                    console.log(errorThrown);
                },
                beforeSend: function (xhr) {

                }
            });
            return false; // keeps the page from not refreshing
        }
    </script>

@endsection