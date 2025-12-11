<div class="col-md-12">
    <a href="<?php echo $redirect_url;?>" class="btn btn-danger btn-block btn-lg login-cred-btn" style="background-color: #EC1D23"><b><i class="fa fa-chevron-circle-right"></i> Login</b></a>

    <a target="_blank" href="{{ url(env('osspid_base_url').'/user/create') }}" class="btn btn-success btn-block btn-lg login-cred-btn" style="background-color: #126C38"><b><i class="fa fa-user-plus"></i> Create OSSPID account</b></a>
    <div class="box-div box-div-img" style="border-radius: 0 0 4px 4px">
        <div class="text-center">
            {{--<h4>{!! trans('messages.login_panel_title') !!}</h4>--}}

        </div>
        {{--<div class="form-group">--}}
        {{--{!! Form::button('Login with OTP', array('type' => 'button', 'class' => 'form-control btn btn-info btn-block otp-login-btn')) !!}--}}
        {{--<span class="form-control-feedback"><span class="fa fa-mobile-phone"></span></span>--}}
        {{--</div>--}}
        {{--@if(env('server_type') == 'local')--}}
        {{--<div class="form-group">--}}
        {{--{!! Form::button('Login / Sign-up', array('type' => 'button', 'class' => 'form-control btn btn-primary btn-block login-cred-btn', 'style'=>'font-weight:bold')) !!}--}}
        {{--</div>--}}
        {{--@endif--}}

        @if (count($errors))
            <ul class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <li>{!!$error!!}</li>
                @endforeach
            </ul>
        @endif
        {!!session()->has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'.session('success') .'</div>' : '' !!}
        {!!session()->has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('error') .'</div>' : '' !!}
        {!! Form::open(array('url' => 'login/check','method' => 'post', 'class' => '')) !!}
        <fieldset>
        <!--
            <div class="form-group">
                <input class="form-control" placeholder="E-mail" required name="email" value="{{old('email')}}" type="email" autofocus>
            </div>
            <div class="form-group">
                <input class="form-control" placeholder="Password" required name="password" type="password">
            </div>
            <?php if (Session::get('hit') >= 3) { ?>
                <div class="form-group">
                    <span id="rowCaptcha"><?php echo Captcha::img(); ?></span> <img onclick="changeCaptcha();" src="assets/images/refresh.png" class="reload" alt="Reload" />
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <input class="form-control required" required placeholder="Enter captcha code" name="captcha" type="text">
                    </div>
            <?php } ?>

                <div class="col-md-12" style="padding: 0px !important; white-space: nowrap">
                    <button type="submit" class="btn btn-primary pull-right"><b>Login</b></button>
                </div>
-->
            <div class="form-group clearfix">
                {{--<span class="pull-right">--}}
                {{--                     {!! link_to('forget-password',trans('messages.forget_password'), array("class" => "text-right color-class")) !!}--}}
                {{--</span>--}}
                {{--<br/>--}}
                {{--<span class="pull-right">--}}
                {{--                         {!! trans('messages.new_user?') !!} {!! link_to('signup',trans('messages.signup'), array("class" => "color-class")) !!}--}}
                {{--</span>--}}
                {{--<br/>--}}
                {{--<div class="pull-left">--}}
                    {{--{!! link_to('users/support', 'Create account', array("class" => "text-right color-class","target"=>"-blank")) !!}--}}
                {{--</div>--}}
                <div class="pull-right text-right">
                    {{--{!! link_to('users/support', 'সাহায্য  প্রয়োজন?', array("class" => "text-right")) !!}--}}
                    {{--{!! link_to(Session::get('help_link'),--}}
                    {{--'সাহায্য  প্রয়োজন?', array("class" => "text-right","target"=>"-blank")) !!}--}}
                    {{--<i>{!! link_to(env('osspid_base_url').'/user/create', 'Create OSSPID account', array("class" => "text-right color-class","target"=>"-blank")) !!}</i>--}}
                    {{--<br/>--}}
                    {{--<i>{!! link_to(env('osspid_base_url').'/user/forget-password', 'Forgot password?', array("class" => "text-right color-class","target"=>"-blank")) !!}</i>--}}
                    <br/>
{{--                    <i>{!! link_to('users/support', trans('messages.need_help?'), array("class" => "text-right color-class","target"=>"-blank")) !!}</i>--}}
                    {{--<i class="fa fa-question-circle"></i>--}}
                    <strong><i><a href="{{url('users/support')}}" target="_blank"> {!! trans('messages.need_help?') !!}</a></i></strong>
                    <div style="margin-top: 5px">
                        <a href="{{url('users/support')}}"><img style="background: none;" src="{{url('/assets/images/bida-help-icon.png')}}"></a>
                        <a href="{{url('users/support')}}"><img style="background: none;" src="{{url('/assets/images/bida-msg-icon.png')}}"></a>
                        <a href="{{url('users/support')}}"><img style="background: none;" src="{{url('/assets/images/bida-messenger-icon.png')}}"></a>
                    </div>
                </div>
            </div>

            <br/>
            {{--<br/>--}}
            <div class="text-right">
                <span style="font-size: smaller">{{trans('messages.manage_by')}}</span>
                <br/>
                {!!  Html::image('assets/images/business_automation.png','BAT logo',['width'=>'100']) !!} <br/><br/>
            </div>
        </fieldset>
        {!! Form::close() !!}
    </div>

    <div class="panel  panel-info">
        <div class="panel-heading">
            <h5><strong>{!! trans('messages.whats_new') !!}?</strong></h5>
        </div>
        {{--<div class="panel-body">--}}
        <div class="panel-body" style="height: 210px; width: 100%">
            <div id="myCarousel1" class="carousel slide carousel-fade" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php for($j = 0; $j < count($whatsNew); $j++){
                    if($j == '0'){
                    ?>
                    <li data-target="#myCarousel1" data-slide-to="0" class="active"></li>
                    <?php }else{  ?>
                    <li data-target="#myCarousel1" data-slide-to="<?php echo $j; ?>"></li>
                    <?php } } ?>
                </ol>
                <div class="carousel-inner">
                    <?php
                    $i = 0;
                    ?>
                    @foreach($whatsNew as $whatsData)
                        @if($i == '0')
                            <div class="item active">
                                <img src="{{url($whatsData->image)}}" alt="{{ $whatsData->title }}" style="width:350px; height: 200px;">
                            </div>
                        @else
                            <div class="item">
                                <img src="{{url($whatsData->image)}}" alt="{{ $whatsData->title }}" style="width:350px; height: 200px;">
                            </div>
                        @endif
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{--<div class="col-md-12">--}}
{{--<div class="panel  panel-green">--}}
{{--<div class="panel-heading">--}}
{{--What's New?--}}
{{--</div>--}}
{{--<div class="panel-body">--}}

{{--<style>--}}

{{--.jssorl-009-spin img {--}}
{{--animation-name: jssorl-009-spin;--}}
{{--animation-duration: 1.6s;--}}
{{--animation-iteration-count: infinite;--}}
{{--animation-timing-function: linear;--}}
{{--}--}}

{{--@keyframes jssorl-009-spin {--}}
{{--from {--}}
{{--transform: rotate(0deg);--}}
{{--}--}}

{{--to {--}}
{{--transform: rotate(360deg);--}}
{{--}--}}
{{--}--}}
{{--</style>--}}


{{--<div class="container" >--}}

{{--<div id="slider1_container"--}}
{{--style="visibility: hidden; position: relative; margin: 0 auto;  height: 210px; overflow: hidden;">--}}

{{--<div data-u="slides" style="position: absolute; left: 0px; top: 0px; width: 340px; height: 200px;--}}
{{--overflow: hidden;">--}}




{{--@foreach($whatsNew as $whatsData)--}}
{{--<li style="text-decoration: none;list-style: none"><a href="" style="text-decoration: none;list-style: none">--}}
{{--<h4 style="text-align: center">{{$whatsData->title}}</h4>--}}
{{--<img src="{{url($whatsData->image)}}" alt=""></a>--}}
{{--</li>--}}
{{--@endforeach--}}
{{--</div>--}}


{{--<div data-u="navigator" class="jssorb031" style="position:absolute;bottom:12px;right:12px;"--}}
{{--data-autocenter="1" data-scale="0.5" data-scale-bottom="0.75">--}}
{{--<div data-u="prototype" class="i" style="width:16px;height:16px;">--}}
{{--<svg viewBox="0 0 16000 16000"--}}
{{--style="position:absolute;top:0;right:410px; width:100%;height:100%;">--}}
{{--<circle class="b" cx="8000" cy="8000" r="5800"></circle>--}}
{{--</svg>--}}
{{--</div>--}}
{{--</div>--}}


{{--</div>--}}

{{--</div>--}}

{{--<style>--}}
{{--.jssorb031 {--}}
{{--position: absolute;--}}
{{--}--}}

{{--.jssorb031 .i {--}}
{{--position: absolute;--}}
{{--cursor: pointer;--}}
{{--}--}}

{{--.jssorb031 .i .b {--}}
{{--fill: #000;--}}
{{--fill-opacity: 0.5;--}}
{{--stroke: #fff;--}}
{{--stroke-width: 1200;--}}
{{--stroke-miterlimit: 10;--}}
{{--stroke-opacity: 0.3;--}}
{{--}--}}

{{--.jssorb031 .i:hover .b {--}}
{{--fill: #fff;--}}
{{--fill-opacity: .7;--}}
{{--stroke: #000;--}}
{{--stroke-opacity: .5;--}}
{{--}--}}

{{--.jssorb031 .iav .b {--}}
{{--fill: #fff;--}}
{{--stroke: #000;--}}
{{--fill-opacity: 1;--}}
{{--}--}}

{{--.jssorb031 .i.idn {--}}
{{--opacity: .3;--}}
{{--}--}}
{{--</style>--}}

{{--<style>--}}
{{--.jssora051 {--}}
{{--display: block;--}}
{{--position: absolute;--}}
{{--cursor: pointer;--}}
{{--}--}}

{{--.jssora051 .a {--}}
{{--fill: none;--}}
{{--stroke: #fff;--}}
{{--stroke-width: 360;--}}
{{--stroke-miterlimit: 10;--}}
{{--}--}}

{{--.jssora051:hover {--}}
{{--opacity: .8;--}}
{{--}--}}

{{--.jssora051.jssora051dn {--}}
{{--opacity: .5;--}}
{{--}--}}

{{--.jssora051.jssora051ds {--}}
{{--opacity: .3;--}}
{{--pointer-events: none;--}}
{{--}--}}
{{--</style>--}}


{{--</div>--}}

{{--<!-- Slideshow 1 -->--}}
{{--<ul class="rslides" id="slider1">--}}

{{----}}

{{--@foreach($whatsNew as $whatsData)--}}
{{--<li><a href="" style="text-decoration: none">--}}
{{--<h4 style="text-align: center">{{$whatsData->title}}</h4>--}}
{{--<img src="{{url($whatsData->image)}}" alt=""></a>--}}
{{--</li>--}}
{{--@endforeach--}}




{{--</ul>--}}





{{--<!-- Slideshow 2 -->--}}
{{--<ul class="rslides" id="slider2">--}}
{{--<li><a href="#"><img src="images/1.jpg" alt=""></a></li>--}}
{{--<li><a href="#"><img src="images/2.jpg" alt=""></a></li>--}}
{{--<li><a href="#"><img src="images/3.jpg" alt=""></a></li>--}}
{{--</ul>--}}



{{--<!-- Slideshow 3 -->--}}
{{--<ul class="rslides" id="slider3">--}}
{{--<li><img src="images/1.jpg" alt=""></li>--}}
{{--<li><img src="images/2.jpg" alt=""></li>--}}
{{--<li><img src="images/3.jpg" alt=""></li>--}}
{{--</ul>--}}

{{--<!-- Slideshow 3 Pager -->--}}
{{--<ul id="slider3-pager">--}}
{{--<li><a href="#"><img src="images/1_thumb.jpg" alt=""></a></li>--}}
{{--<li><a href="#"><img src="images/2_thumb.jpg" alt=""></a></li>--}}
{{--<li><a href="#"><img src="images/3_thumb.jpg" alt=""></a></li>--}}
{{--</ul>--}}



{{--<!-- Slideshow 4 -->--}}
{{--<div class="callbacks_container">--}}
{{--<ul class="rslides" id="slider4">--}}
{{--<li>--}}
{{--<img src="images/1.jpg" alt="">--}}
{{--<p class="caption">This is a caption</p>--}}
{{--</li>--}}
{{--<li>--}}
{{--<img src="images/2.jpg" alt="">--}}
{{--<p class="caption">This is another caption</p>--}}
{{--</li>--}}
{{--<li>--}}
{{--<img src="images/3.jpg" alt="">--}}
{{--<p class="caption">The third caption</p>--}}
{{--</li>--}}
{{--</ul>--}}
{{--</div>--}}

{{--<!-- This is here just to demonstrate the callbacks -->--}}
{{--<ul class="events">--}}
{{--<li><h3>Example 4 callback events</h3></li>--}}
{{--</ul>--}}


{{--</div>--}}


{{--</div>--}}



{{--<script>--}}

{{--jQuery(document).ready(function ($) {--}}
{{--var options = {--}}
{{--$AutoPlay: 1,--}}
{{--$AutoPlaySteps: 1,--}}
{{--$Idle: 2000,--}}
{{--$PauseOnHover: 1,--}}

{{--$ArrowKeyNavigation: 1,--}}
{{--$SlideEasing: $Jease$.$OutQuint,--}}
{{--$SlideDuration: 800,--}}
{{--$MinDragOffsetToSlide: 20,--}}
{{--$SlideSpacing: 0,--}}
{{--$UISearchMode: 1,--}}
{{--$PlayOrientation: 1,--}}
{{--$DragOrientation: 1,--}}

{{--$ArrowNavigatorOptions: {--}}
{{--$Class: $JssorArrowNavigator$,--}}
{{--$ChanceToShow: 2,--}}
{{--$Steps: 1--}}
{{--},--}}

{{--$BulletNavigatorOptions: {--}}
{{--$Class: $JssorBulletNavigator$,--}}
{{--$ChanceToShow: 2,--}}
{{--$Steps: 1,--}}
{{--$SpacingX: 12,--}}
{{--$Orientation: 1--}}
{{--}--}}
{{--};--}}

{{--var jssor_slider1 = new $JssorSlider$("slider1_container", options);--}}


{{--function ScaleSlider() {--}}
{{--var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;--}}
{{--if (parentWidth) {--}}
{{--jssor_slider1.$ScaleWidth(parentWidth - 30);--}}
{{--}--}}
{{--else--}}
{{--window.setTimeout(ScaleSlider, 30);--}}
{{--}--}}

{{--ScaleSlider();--}}

{{--$(window).bind("load", ScaleSlider);--}}
{{--$(window).bind("resize", ScaleSlider);--}}
{{--$(window).bind("orientationchange", ScaleSlider);--}}

{{--});--}}
{{--</script>--}}


<div id="otp_modal" class="modal fade" role="dialog">
    <div class="modal-dialog user-login-modal-container">

        <!-- Modal content for OTP Login-->
        <div class="modal-content user-login-modal-body">
            <div class="modal-header user-login-modal-title">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title">Login with OTP</div>
            </div>
            <div class="modal-body login-otp user-login-modal-content">
                ..................
            </div>
            <div class="modal-footer user-login-modal-footer">

            </div>
        </div>

    </div>
</div>

<div id="user_login_modal" class="modal fade" role="dialog">
    <div class="modal-dialog user-login-modal-container">

        <!-- Modal content for OTP Login-->
        <div class="modal-content user-login-modal-body">
            <div class="modal-header user-login-modal-title">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-title">{{trans('messages.login_modal_title')}}</div>
            </div>
            <div class="modal-body login-info-box user-login-modal-content">
                ......................
            </div>
            <div class="modal-footer user-login-modal-footer">
            </div>
        </div>

    </div>
</div>


<link rel="stylesheet" href="{{ asset("assets/plugins/responsiveslides.css") }}"/>
<script src="{{ asset("assets/plugins/responsiveslides.min.js") }}"></script>
<script>
    $(function () {

        // Slideshow 1
        $("#slider1").responsiveSlides({
            maxwidth: 800,
            speed: 800,
            timeout: 8000,
        });



        // Slideshow 2
        $("#slider2").responsiveSlides({
            auto: false,
            pager: true,
            speed: 300,
            maxwidth: 540
        });

        // Slideshow 3
        $("#slider3").responsiveSlides({
            manualControls: '#slider3-pager',
            maxwidth: 540
        });

        // Slideshow 4
        $("#slider4").responsiveSlides({
            auto: false,
            pager: false,
            nav: true,
            speed: 500,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });

    });

    function createComplain() {
        $('#myModal .modal-title').html("অভিযোগ ও পরামর্শ");
        $("#myModal #body-content").load("{{URL::to('users/complain')}}");
        $('#myModal .modal-dialog').removeClass().addClass('modal-dialog' + " " + "modal-md" + " " + "success-modal");
        $('#myModal .modal-footer').empty().append('<button type="button" class="btn btn-default" data-dismiss="modal">Close</button> <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>');
    }
</script>