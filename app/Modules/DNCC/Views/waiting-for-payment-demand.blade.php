<?php
$accessMode = ACL::getAccsessRight('DNCC');
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
                        <h4>Click the payment button</h4>
                        {!! Form::open(array('url' => '/dncc/payment','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
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

        function checkNESCOStatus() {
            var app_id = $('#enc_app_id').val();

            $.ajax({
                url: '/dncc/check-payment-info',
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
                            $('#paymentPanel').show();
                            $('#loading').hide();
                            // alert(response.message);
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

        $(document).ready(function () {
            checkNESCOStatus();
            countDownTimer('Connecting to DNCC Server', 'Please wait to connect...', '2:02');
        });
    </script>

@endsection