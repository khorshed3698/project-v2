<?php
$accessMode = ACL::getAccsessRight('NameClearance');
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
        z-index: 99;
        text-align: center;
        background-color: rgba(192,192,192,0.3);
    }

    #loding-msg{

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


    .title{
        font-weight: 800;
        font-size: medium;
        display: block;
    }
    .textSmall{
        font-size: smaller;
    }
    .noBorder{
        border:none;
    }
    .redTextSmall{
        color:red;
        font-size: 14px;
    }
    .form-group{
        margin-bottom: 2px;
    }
    .img-thumbnail{
        height: 80px;
        width: 100px;
    }
</style>
<section class="content">

    <div class="col-md-12">

        <div id="loading">
            <?php $userPic = URL::to('/assets/images/loading.gif'); ?>
                <Span class="alert alert-success"  id="loding-msg"><i class="fa fa-spinner fa-spin"></i>  <span id="loding-msg-text">Connecting to RJSC server.</span></Span>
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
                    {!! Form::open(array('url' => '/licence-applications/name-clearance/payment','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                        <div class="form-group">
                            <input hidden="" name="enc_app_id" id="enc_app_id" type="text" value="{{ isset($applicationId) ? $applicationId : '' }}">
                            <input hidden="" name="enc_payment_id" type="text" value="{{ isset($paymentId) ? $paymentId : '' }}">
                        </div>
                        <input type="submit" class="btn btn-primary" name="actionBtn" value="Payment">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>

{{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.js"></script>--}}
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.css">--}}



<script  type="text/javascript">

    function checkRjscStatus()
    {
        var app_id = $('#enc_app_id').val();

        $.ajax({
            url: '/licence-applications/name-clearance/check-rjsc-status',
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
                    if(response.status == -2 || response.status == -3) {
                        // $('#loading').hide();
                        // alert(response.message);
                    }else if (response.status == 0 || response.status == -1) {
                        myVar = setTimeout(checkRjscStatus, 5000);
                    }else if (response.status == 1) {
                        var paymentInformation = response.paymentInformation;
                        $('#paymentInformation').html(paymentInformation);
                        $('#paymentPanel').show();
                        $('#loading').hide();
                    }
                    else{
                        alert('Whoops there was some problem please contact with system admin.');
                        window.location.reload();
                    }
                } else {
                    alert('Whoops there was some problem please contact with system admin.');
                    window.location.reload();
                }
            },error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
                console.log(errorThrown);
            },
            beforeSend: function(xhr) {

            }
        });
        return false; // keeps the page from not refreshing
    }

    $(document).ready(function(){
        checkRjscStatus();
    });

</script>

@endsection