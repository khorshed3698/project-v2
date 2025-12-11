@extends('layouts.admin')
@section('content')
    <style>
        #loading {
            text-align: center;
            width: 100%;
            height: 100%;
            top: 0px;
            left: 200px;
            position: fixed;
            display: block;
            /*opacity: .9;*/
            background-color: #fff;
            z-index: 99;
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
            <div id="loading">
                <div class="panel panel-info" id="loading-message">

                </div>
                <img id="loading-image" src="/assets/images/loading-ttcredesign.gif" alt="Loading..."/>
            </div>
            <div class="box">
                <div id="paymentPanel" class="panel panel-default" style="display: none;">
                    <div class="panel-body">
                        <h4>Click the payment button</h4>
                        {!! Form::open(array('url' => '/new-reg/new-reg/payment','method' => 'post','id' => 'NewReg','role'=>'form','enctype'=>'multipart/form-data')) !!}
                        <div class="form-group">
                            <input hidden="" name="enc_app_id" id="enc_app_id" type="text" value="{{ isset($app_id) ? $app_id : '' }}">
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

@endsection

@section('footer-script')

    <script>
        function callajaxfunction() {
            var applicationid='{{$app_id}}';
            $.ajax({
                type: "get",
                url:'/new-reg/check-rjsc-application-status',
                data: {
                    appid: applicationid
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        if (response.status == 0 || response.status == -1) {
                            //console.log(response[0].status_id)
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp;'+'Waiting For New Registration request response from RJSC');
                            //alert('Waiting For New Registration request response from RJSC');
                            myVar = setTimeout(callajaxfunction, 5000);
                        }
                        else if (response.status == 1) {
                            // alert('Waiting For New Registration request response from RJSC');
                            checkdocstatus(applicationid);
                        }else if(response.status == -2 || response.status == -3 || response.status == -4 ){
                            setTimeout(function () {
                                alert('NR request response error !');
                            },200);

                            $('#loading').hide();
                            $('#refresh').show();
                        }
                        else{
                            $('#loading').hide();
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>nbsp'+'Waiting For New Registration request response from RJSC');
                            $('#refresh').show();
                        }
                    }else {
                        alert('Whoops there was some problem please contact with system admin.');
                        window.location.reload();
                    }
                }
            });

        }

        function checkdocstatus(applicationid) {

            $.ajax({
                type: "get",
                url:'/new-reg/check-rjsc-doc-status',
                data: {
                    appid: applicationid
                },
                success: function (response) {
                    if (response.count>0){
                        if(response['status'][0].status==-10) {
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Waiting for document upload');
                            //alert('Waiting for document upload.');
                            myVar = setTimeout(checkdocstatus, 5000,applicationid);
                        }else if(response['status'][0].status==1){
                            // console.log('docstatus');
                            // alert(11);
                            // alert('Waiting for payment response from RJSC.');
                            paymentstatus(applicationid);

                        }else if(response['status'][0].status== -2 || response['status'][0].status==-3 || response['status'][0].status== -4) {
                            $('#loding-message').empty();
                            setTimeout(function () {
                                alert('Document upload error !');
                            }, 200);
                            $('#loading').hide();
                            $('#refresh').show();
                        } else{
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Waiting for document upload response from RJSC');
                            //alert('Waiting for document upload response from RJSC.');
                            setTimeout(function () {
                                $('#loading').hide();
                            },5000);
                            $('#refresh').show();
                        }

                    }else{
                        $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Waiting for document upload response from RJSC');
                        setTimeout(function () {
                            $('#loading').hide();
                        },5000);
                        $('#refresh').show();
                    }

                }
            });

        }

        function paymentstatus(applicationid) {

            $.ajax({
                type: "get",
                url:'/new-reg/check-rjsc-payment-status',
                data: {
                    appid: applicationid
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        if (response.status == 0 || response.status == -1) {
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Waiting for payment');
                           // alert('Waiting for payment');
                            myVar = setTimeout(paymentstatus, 5000,applicationid);
                        }else if (response.status == 1) {
                            // console.log('docstatus');
                            $('#loading').hide();
                            $('#paymentPanel').show();
                        }else if(response.status == -2 || response.status == -3 || response.status == -4 ){
                            $('#loding-message').empty();
//                            setTimeout(function () {
//                                console.log('Payment record error!');
//                            }, 200);
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Waiting for payment');
                            // alert('Waiting for payment');
                            myVar = setTimeout(paymentstatus, 5000,applicationid);
                        } else{
                            setTimeout(function () {
                                $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Waiting for payment response from RJSC');
                            },5000);
                           // alert('Waiting for payment response from RJSC.');
                            $('#loading').hide();
                            $('#refresh').show();
                        }
                    } else {
                        setTimeout(function () {
                            $('#loading-message').html('<i class="fa fa-spinner fa-spin"></i>&nbsp'+'Waiting for payment response from RJSC');
                        },5000);

                        // alert('Waiting for payment response from RJSC.');
                        $('#loading').hide();
                        $('#refresh').show();
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
