@extends('layouts.plane')

@section('style')
    <style>
        #page-wrapper {
            background-color: #F1F1FF !important;
        }
        #helpDiv {
            position: fixed;
            /*height: 30px;*/
            bottom: 5px;
            /*width: 100%;*/
            width: auto;
            right: 10px;
        }
        .help-button {
            display: inline-block;
            width: 35px;
            height: 35px;

            text-align: center;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            border: 2px solid #637282;
            border-radius: 50%;
            font-size: 16px;
            /*font-family: AtlasGrotesk, sans-serif;*/
            font-weight: 500;
            color: #637282;
            background-color: white;
            text-decoration: none;
        }
        .help-button:active, .help-button:hover, .help-button:focus {
            border-color: #0070E0;
            color: #0070E0;
            text-decoration: none;
        }
        input[type="radio"] {
            -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
            -moz-appearance: checkbox;    /* Firefox */
            -ms-appearance: checkbox;     /* not currently supported */
        }
        .identity_hover, .identity_type{
            cursor: pointer;
        }
        .text-limit{
            float: right;
            margin-right: 5px;
            padding: 5px;
            font-size: 14px;
        }
        .highttext{
            color: #eea236;
        }

        @keyframes bounceIn {
            0%, 50%, 100% {
                transform: translateY(-0px);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-15px);
            }
        }
        .bounceScrollBoll {
            animation: bounceIn 3s infinite;
            -webkit-animation: bounceIn 3s infinite;
        }
        .bounceScrollBoll:hover{
            animation: none;
            -webkit-animation: none;
        }

        .dataTables_processing {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
        }


    </style>
@endsection
@section('body')
    <div id="wrapper">
        @include ('navigation.nav')
        {{-- <div id="page-wrapper"> --}}
        @if (Auth::user()->user_type == '5x505')
            <div style="margin: 0 0 0 0; padding: 10px 25px;" id="page-wrapper">
                @else
                    <div id="page-wrapper">
                        @endif
                        {{-- Forcefully user business class update --}}
                        @include('message.bida-reg-business-class')

                        {{-- <div class="row"> --}}
                        {{-- <div class="col-md-10 col-lg-10"> --}}
                        {{-- <h3 class="page-header">@yield('page_heading')</h3> --}}
                        {{-- </div> --}}
                        {{-- <div class="col-md-2 col-lg-2 text-right"> --}}
                        {{-- <a href="{{url('support/help/'.Request::segment(1))}}"><h5><span style="color: green"> --}}
                        {{-- Need Help <i class="fa fa-question-circle"></i> --}}
                        {{-- </span></h5></a> --}}
                        {{-- </div> --}}
                        <!-- /.col-lg-12 -->
                        {{-- </div> --}}
                        <div class="row">
                            @yield('content')
                        </div>

                        {{-- <div class="row"> --}}
                        {{-- <div class="col-sm-12"> --}}
                        {{-- <p>Copyright © Your Website 2018</p> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                    </div>
            </div>

            @if (Auth::user()->user_type == '5x505')
                <div id="footer" class="container-fluid row" style="box-shadow: rgba(33, 35, 38, 0.1) 0px -10px 10px -10px; padding: 5px;">
                    <div class="img_container col-md-1" style="text-align: center">
                        {{-- <a href="{{ config('app.managed_by') }}">
                            <img src="{{ asset('assets/images/business_automation_sm.png') }}" alt="" style="margin-top: 15px; padding-left: 13px;" class="foot_img">
                        </a> --}}
                    </div>

                    <p class="company-info col-md-6 col-md-offset-6" style="color: #6D7580; font-style:italic; font-size: 16px; text-align: center;">Managed by 
                        <a style="color:#2E4053; font-weight: bold;" href="http://bida.gov.bd" target="_blank" rel="noopener">Bangladesh
                            Investment Development Authority (BIDA)</a>
                    </p>
                    {{-- <p class="company-info col-md-8 col-md-offset-3" style="color: #6D7580; font-style:italic; font-size: 16px; text-align: center;">
                        Managed by <a style="color:#2E4053; font-weight: bold;" href="{{ config('app.managed_by_url') }}"
                                      target="_blank" rel="noopener">{{ config('app.managed_by') }}</a>
                        On behalf of <a style="color:#2E4053; font-weight: bold;" href="http://bida.gov.bd" target="_blank" rel="noopener">Bangladesh
                            Investment Development Authority (BIDA)</a>
                    </p> --}}
                </div>
            @else
                <div id="footer" class="sticky-footer">
                    <hr class="less-padding"/>
                    <p class="company-info" style="color:#000;">
                        Managed by <a style="color:#2E4053; font-weight: bold;" href="http://bida.gov.bd" target="_blank" rel="noopener">Bangladesh Investment Development Authority (BIDA)</a>
                    </p>
                    {{-- <p class="company-info" style="color:#000;">
                        Managed by <a style="color:#2E4053; font-weight: bold;" href="{{ config('app.managed_by_url') }}" target="_blank" rel="noopener">{{ config('app.managed_by') }}</a>
                        On behalf of <a style="color:#2E4053; font-weight: bold;" href="http://bida.gov.bd" target="_blank" rel="noopener">Bangladesh Investment Development Authority (BIDA)</a>
                    </p> --}}
                </div>
            @endif

            <script>
                $(document).ready(function() {

                    //feedbackmessage();
                    function feedbackmessage() {
                        $.ajax({
                            url: '{{ url('settings/fMsgShow') }}',
                            type: "get",
                            data: {},
                            success: function(data) {
                                if (data.id == 2) {
                                    $("#msgtost2").css('display', 'block');
                                    $("#feature_text2").html(data.feature_text);
                                }

                            }
                        })
                    }


                    // for OSSPID
                    var social_login = '<?php echo Auth::user()->social_login; ?>';

                    if (social_login == 2) {
                        var oauth_token = '<?php echo Session::get('oauth_token'); ?>'
                        var logged_email = '<?php echo base64_encode(Auth::user()->user_email); ?>'
                        localStorage.setItem('BASIS_Auth_Token', oauth_token);
                        localStorage.setItem('BASIS_Logged_Email', logged_email);
                    }


                    $(".feedbackbtn2").click(function() {
                        var featurId = $("#msg2").val();
                        var value = $(this).val();
                        $("#msgtost2").remove();
                        $.ajax({
                            url: '{{ url('settings/feedback') }}',
                            type: "get",
                            data: {
                                value: value,
                                featurId: featurId
                            },
                            success: function(data) {}
                        })
                    })

                })
            </script>


            <div id="helpDiv">
                {{-- @if ((Auth::user()->user_type == '4x404' || Auth::user()->user_type == '1x101') && Auth::user()->is_approved == 1) --}}
                {{-- <div id="container"> --}}
                {{-- <div id="2"> --}}
                {{-- <div class="content" id="msgtost2" style="display: none;"> --}}
                {{-- <blockquote class="oval-thought-border"> --}}
                {{-- <p style="font-size: 20px"><h3 id="feature_text2"></h3> <button class="btn btn-success btn-sm feedbackbtn2" value="ok" id="yesbtn2">OK</button> --}}
                {{-- <input type="hidden" value="2" id="msg2"> --}}
                {{-- </p> --}}
                {{-- </blockquote> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- </div> --}}
                {{-- @endif --}}
                {{-- <div class="col-sm-9"></div> --}}
                {{-- <div class="col-sm-3 msgtost2"> --}}
                <div class="msgtost2">
                    @if (Request::segment(3) === 'view-app')
                        <div class="pull-right" style="padding: 5px">
                            <a href="#end" data-toggle="tooltip" data-placement="top" title=""
                               class="btn btn-default help-button bounceScrollBoll" id="animateScrollBtn">
                                <i class="fas fa-angle-down"></i>
                            </a>
                        </div>
                    @endif

                    <div class="tooltip-demo pull-right" style="padding: 5px">
                        @if (Auth::user()->user_type == '4x404' && Auth::user()->is_approved == 1 && Auth::user()->desk_id != 23)
                                <?php
                                $PendingYourApplication = \App\Libraries\CommonFunction::pendingApplication();
                                ?>
                            <a href="{{ '/process/list' }}" class="btn  btn-info btn-xs"
                               style="padding: 4px;background:#5ca99b">Pending process in your desk:
                                ({{ $PendingYourApplication }})</a>
                        @endif

                        <?php
                        $help_text_uri = Request::segment(1) == 'process' ? Request::segment(2) : Request::segment(1);
                        ?>
                        <a href="{{ url('support/help/' . $help_text_uri) }}" target="_blank" rel="noopener" data-toggle="tooltip"
                           data-placement="top" title="Help" class="btn btn-default help-button">
                            <i class=" fa fa-question"></i>
                        </a>

                    </div>

                </div>
            </div>

@endsection
