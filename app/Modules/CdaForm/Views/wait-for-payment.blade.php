<?php
$accessMode = ACL::getAccsessRight('BccCDA');
?>
@extends('layouts.admin')
@section('content')
    <style>
        #loading-payment {
            text-align: center;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 200px;
            position: fixed;
            background-color: #fff;
            z-index: 99;
            text-align: center;
        }

        .node:active {
            fill: #fffa90;
        }


        #loading-image {
            position: absolute;
            top: 150px;
            left: 400px;
            z-index: 600;
        }
        #loading-message {
            position: relative;
            top: 100px;
            display: none;
            padding: 5px 10px 5px 10px;
            z-index: 600;
            width: auto;
            /* left: 315px; */
            font-size: 16px;
            font-weight: bold;
            color: green;
            width: 50%;
            margin: 0 auto;
            right: 80px;
        }

        /*Style for SVG*/
        svg {
            /*border: 1px solid #ccc;*/
            overflow: hidden;
            cursor: pointer;
            margin: 0 auto;
        }
        .node rect {
            stroke: #333;
            fill: #fff;
        }
        .edgePath path {
            stroke: #333;
            fill: #333;
            stroke-width: 1.5px;
        }
    </style>
    <section class="content">

        <div class="col-md-12">
            <div id="loading-payment">
                <div class="panel panel-info" id="loading-message">

                </div>
                <img id="loading-image" src="/assets/images/loading-ttcredesign.gif" alt="Loading..."/>
            </div>
            <div class="box">
                <div id="paymentPanel" class="panel panel-default" style="display: none;">
                    <div class="panel-body">
                        <h4>Click the payment button</h4>
                        {!! Form::open(array('url' => '/cda-form/payment','method' => 'post','id' => 'NewReg','role'=>'form','enctype'=>'multipart/form-data')) !!}
                        <div class="form-group">
                            <input hidden="" name="enc_app_id" id="enc_app_id" type="text" value="{{ isset($applicationId) ? $applicationId : '' }}">
                            <input hidden="" name="enc_payment_id" type="text" value="{{ isset($paymentId) ? $paymentId : '' }}">
                        </div>
                        <input type="submit" class="btn btn-primary" name="actionBtn" value="Payment">
                        {!! Form::close() !!}
                    </div>
                </div>
                <div id="refresh" class="panel panel-default" style="display: none;">
                    <div class="panel-body">
                        <h4>Click the Refresh button</h4>
                        <input type="button" class="btn btn-primary" id="refreshbutton" name="refreshbutton" value="Refresh">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function callajaxfunction() {
            var applicationid='{{$applicationId}}';
            $.ajax({
                type: "get",
                url:'/cda-form/check-cda-application-status',
                data: {
                    appid: applicationid
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        if (response.status == 0 ) {
                            //console.log(response[0].status_id)
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;'+'Waiting response from CDA');
                            //alert('Waiting For New Registration request response from RJSC');
                            myVar = setTimeout(callajaxfunction, 5000);
                        }
                        else if (response.status == 1) {
                            $('#loading-payment').hide();
                            $('#paymentPanel').show();
                        }else if(response.status == -1){
                            setTimeout(function () {
                                alert('CDA request response error !['+response.cdaresponse+']');
                            },200);

                            $('#loading-payment').hide();
                            $('#refresh').show();
                        }
                        else{
                            $('#loading-payment').hide();
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>nbsp'+'Waiting For  response from CDA');
                            $('#refresh').show();
                        }
                    }else {
                        alert('Whoops there was some problem please contact with system admin.');
                        // window.location.reload();
                    }
                }
            });

        }

        $('#refreshbutton').on('click',function () {
            location.reload();
        })
        $(document).ready(function(){

            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Application submitted successfully');
            $('#loading-message').show();
            setTimeout(function () {
               callajaxfunction();
           },3000) ;
        });

    </script>
@endsection