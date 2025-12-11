<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Security Alert</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Vollkorn' rel='stylesheet' type='text/css'>
    <style type="text/css">
        *{
            font-family: Vollkorn;
        }
    </style>
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Add your site or application content here -->

<!-- start template option -->
<div class="main" style="width: 600px;margin: 0 auto;">

    <!-- start header option -->
    <div class="top">

        <div class="header" style="padding-bottom: 82px;">

            <div class="headerleft" style="float:left;width:250px">
                <img src="http://oss-framework.eserve.org.bd/uploads/logo/govt_logo.png" alt="BIDA OSS" height="60" width="60" style="margin-top: 20px;">
            </div>


            <div class="headerright" style="float:right; margin-top:20px;width:300px;height:70px">

                <div style="width:240px;float:left;margin-top:20px;text-align:right">{!! \App\Libraries\CommonFunction::getUserFullName() !!}</div>
                <div style="width:42px;height:50px;float:right">

                    <?php
                    if (!empty(Auth::user()->user_pic)) {
                        $userPic = url() . '/users/upload/' . Auth::user()->user_pic;
                    } else {
                        $userPic = URL::to('/assets/images/avatar5.png');
                    }
                    ?>
                    <img src="{{ $userPic }}" alt="{!! \App\Libraries\CommonFunction::getUserFullName() !!}" height="40" width="40" style="margin-top: 10px;float:right;border-radius: 100%">
                </div>



            </div>


        </div>

    </div>
    <!-- end header option -->

    <!-- start body option -->
    <div class="mainbody" style="background: #FFFFFF;border: 4px solid #F5F5F5;">

        <div class="signin_title" style="text-align: center;">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="title" style="border-bottom:2px solid #EEEEEE;margin-bottom: 40px;margin-top: 30px;">
                        <h2 style="font-family: ver;">New device signed in to</h2>
                        <p style="margin-bottom: 45px;font-size: 16px;margin-top: -7px;">{{Auth::user()->user_email}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="heading">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="heading_title">
                        <p style="margin-left: 35px;margin-right: 50px;margin-bottom: 40px;font-weight: bold;">Your Account just signed in to from a new {{$os}} device.You're getting this email to make sure that it was you.</p>


                    </div>
                </div>
            </div>
        </div>

        <div class="link" style="margin-left:35px;">
            This is a system generated email. Please don't reply.<br><br><br>
                        Thanks<br>
                        BIDA OSS
        </div>


    </div>
    <!-- end body option -->

    <!-- start footer option -->
    <div class="footer">
        <h5 style="margin-left: 15px;margin-right: 30px;margin-top: 20px;margin-bottom: 20px;font-family: arial,sans-serif;opacity: 0.5;}">You received this email to let you know about important changes to your {{env("PROJECT_NAME")}} Account and Services ©{{date('Y')}} , Bangladesh </h5>
    </div>
    <!-- end footer option -->

</div>

<!-- end template option -->


<!-- End your site or application content here -->


</body>
</html>