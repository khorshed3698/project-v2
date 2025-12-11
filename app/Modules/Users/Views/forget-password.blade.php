@extends('layouts.front')

@section("content")

    <style>
        .g-recaptcha {
            transform: scale(1.11);
            transform-origin: 0 0;
        }
    </style>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-md-5 col-md-offset-4 col-sm-12">
                    <div class="box-div">
                        {!!session()->has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'.session('success') .'</div>' : '' !!}
                        {!!session()->has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('error') .'</div>' : '' !!}
                        <span class="responseMessage"></span>

                        {{--                {!! Form::open(array('url' => 'users/reset-forgotten-password','method' => 'patch', 'class' => '', 'id' => 'forgetPassForm')) !!}--}}

                        <fieldset>
                            <legend class="d-none">Forget Password</legend>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <h3 class="text-center text-primary">{!! trans('messages.forget_password_title') !!}</h3>
                                    <br/>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <label>Email Address:</label>
                                    {!! Form::email('user_email', $value = null, $attributes = array('class'=>'form-control required email',
                                    'placeholder'=>'Enter your Email Address','id'=>"user_email")) !!}
                                    <span id="email"></span>
                                </div>
                            </div>

                            <div class="clearfix">&nbsp;</div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Recaptcha::render() !!}
                                    {!! $errors->first('g-recaptcha-response','<span class="help-block">:message</span>') !!}
                                    <span id="recaptcha"></span>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="col-md-12">
                                    <button type="submit" value="forgentpassword" name="forgentpassword"
                                            class="btn  btn-primary btn-block submit "><b>Submit</b></button>
                                    <br/>
                                </div>
                                <div class="clearfix">&nbsp;</div>

                                <span class="col-md-12">
                            <b>Go back to login page {!! link_to('/', 'Login', array("class" => "")) !!}</b>
                        </span>
                                <span class="col-md-12">
                            <b>Don't have an account? {!! link_to('signup', 'Sign up', array("class" => " ")) !!}</b>
                        </span>
                            </div>
                        </fieldset>
                        {{--                {!! Form::close() !!}--}}

                    </div>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </div>

@endsection
<script src="{{ asset("assets/scripts/jquery.min.js") }}" src="" type="text/javascript"></script>
<script src="{{ asset("assets/scripts/jquery.validate.js") }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        $("#forgetPassForm").validate({
            errorPlacement: function () {
                return false;
            }
        });

        $('.submit').click(function () {
            var email = $('.email').val();
            var g_captcha_response = $("#g-recaptcha-response").val();
            if (email == '') {
                $('#email').html("<span style='color: darkred' '>The email field is required.</span>")
                return false;
            }
            if (g_captcha_response == '') {
                $('#recaptcha').html("<span style='color: darkred' '>The g-recaptcha-response field is required.</span>")
                return false;
            }

            var submitButton = $(this);
            submitButton.html("Sending...");
            submitButton.prop('disabled',true);
            <?php $userPic = URL::to('/assets/images/loadWait.gif'); ?>
            $(submitButton).after('  <img id="loading-image" style="width:70%" src="{{$userPic}}" alt="Loading..."/>');

            $.ajax({
                url: "/users/reset-forgotten-password",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "user_email": email,
                    "g_captcha_response": g_captcha_response
                }
            }).done(function (response) {
                if (response.id != '') {
                    sessionStorage.setItem("queue_id",response.id);
                    setInterval(function() {
                        $.ajax({
                            url: "/users/checking-email-queue",
                            method: 'post',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": sessionStorage.getItem("queue_id")
                            }
                        }).done(function (response) {
                            if (response.responseCode == 1) {
                                $('.responseMessage').html(response.messages);
                                submitButton.prop('disabled', false);
                                submitButton.html("Submit");
                                $(submitButton).next().hide();
                            } else {
                            }

                        });
                    }, 10000);

                } else {
                    $('.responseMessage').html(response.messages);
                    submitButton.prop('disabled',false);
                    submitButton.html("Submit");
                    $(submitButton).next().hide();
                }
            });
        })
    });
</script>