<?php
$accessMode = ACL::getAccsessRight('NewConectionBPDB');
?>
@extends('layouts.admin')
@section('content')
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

            <div id="loading">
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
                        {!! Form::open(array('url' => '/new-connection-breb/payment-demand','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
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

        function checkWzpdclStatus() {
            var app_id = $('#enc_app_id').val();

            $.ajax({
                url: '/new-connection-breb/check-payment-info-demand',
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
                            myVar = setTimeout(checkWzpdclStatus, 5000);
                        } else if (response.status == -1) {
                            $('#loding-msg-text').text(response.message)
                            myVar = setTimeout(checkWzpdclStatus, 5000);
                        } else if (response.status == 1) {
                            var paymentInformation = response.paymentInformation;
                            $('#paymentInformation').html(paymentInformation);
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
            checkWzpdclStatus();
            countDownTimer('Connecting to BREB Server', 'Please wait to connect...', '2:02');
        });

    </script>

@endsection