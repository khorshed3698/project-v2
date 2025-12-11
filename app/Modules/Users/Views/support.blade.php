@extends('layouts.front')

@section("content")
    <style type="text/css">

        a {
            text-decoration: none;
            color: #000;
        }

        html {
            scroll-behavior: smooth;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .q-support {
            padding: 20px 20px;
            text-align: left;
            /*height: 500px;*/
            overflow: hidden;
        }

        .item-s {
            float: left;
            width: 100%;
        }

        .q-support p a:hover {
            text-decoration: underline;
            color: #039;
        }

        .q-support h4 {
            color: #0a6829;
            padding-bottom: 3px;
            margin-bottom: 6px;
            border-bottom: 1px solid #e1dede;
            text-shadow: 0px 1px 0px #999;
        }

        .list_style {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .green_color {
            color: #008000;;
        }

        .fbold {
            font-weight: bold;
        }

        #fb-root > div.fb_dialog.fb_dialog_advanced.fb_customer_chat_bubble_animated_no_badge.fb_customer_chat_bubble_pop_in {
            right: initial !important;
            left: 18pt;
            z-index: 9999999 !important;
        }

        .fb-customerchat.fb_invisible_flow.fb_iframe_widget iframe {
            right: initial !important;
            left: 18pt !important;
        }

        .well {
            overflow: hidden;
            display: block;
            width: 100%;
        }

        .panel {
            background-color: rgba(255,255,255,0);
        }

        .helpdesk-oper-hours a {
            padding-top: 5px;
            display: block;
            text-decoration: underline;
        }

        .contact_panel {
            border-radius: 0;
            margin: 5px;
        }

        .contact_panel .panel-heading {
            border-radius: 0;
            background-color: rgba(231, 247, 234, 1);
            font-size: 19px;
            text-align: center;
            padding: 15px;
        }

        .contact_panel .panel-body {
            background-color: rgba(231, 247, 234, 1);
        }

        .contact_box_outer {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .contact_box {
            background-color: #fff;
            border: 1px solid #008000;
            min-height: 116px;
            overflow: hidden;
            padding: 10px;
        }

        .contact_image {
            text-align: center;
        }

        .contact_content {
            display: flex;
            justify-content: center;
            flex-direction: column;
            width: 100%;
            min-height: 84px;
            padding-left: 20px;
        }

        .contact_content_title {
            margin: 0 0 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .contact_content_list {
            font-size: 13px;
        }

        @media screen and (max-width: 1199px) and (min-width: 992px) {

        }

        /* On screens that are 991 or less Extra small devices (phones, 600px and down) Small devices (portrait tablets and large phones, 600px and up) */
        @media screen and (max-width: 991px) {
            .fb_dialog {
                left: 18pt !important;
                z-index: 99999999999 !important;
                right: initial !important;
            }

            .helpdesk-oper-hours a {
                padding-top: 0px;
                padding-bottom: 10px;
                display: block;
            }
        }

        @media screen and (max-width: 768px) {
            .contact_content {
                padding-left: 0px;
            }
        }

    </style>
    @include('articles.top-navbar')
    <div class="row">
        <div class="col-md-12">
            <div class="box-div">
                <div class="row">
{{--                    <br/>--}}
{{--                    <div style="margin-bottom: 0px;border: none;border-radius: 0" class="helpdesk-oper-hours well">--}}
{{--                        <div class="col-md-8 col-sm-12">--}}
{{--                                                        <a href="#technical_support">Need help? Contact Information</a>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-4 col-sm-12">--}}
{{--                            <p style="text-transform: uppercase;">Help Desk Operating Hours</p>--}}
{{--                            <ul class="list_style">--}}
{{--                                <li>Sunday-Thursday: 9AM-5PM</li>--}}
{{--                                <li>Friday & Saturday: Closed</li>--}}
{{--                                <li>All Govt Holiday: Closed</li>--}}
{{--                                <li><a href="#technical_support">More Information</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="clearfix"></div>--}}

                    {{--service delivery from home start--}}
                    <div class="panel contact_panel">
                        <div class="panel-heading">
                            To introduce smooth services to the investors we have taken the following
                            action for better </br> service delivery from home.
                        </div>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <img src="{{ url('assets/images/need_help/call.png') }}" alt="Call Center">
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <p class="contact_content_title">
                                                            Please contact to Call Center
                                                        </p>
                                                        <ul class="list_style">
                                                            <li class="green_color">
                                                                +8809678771353
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <img src="{{ url('assets/images/need_help/email.png') }}" alt="Need help">
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <p class="contact_content_title">
                                                            Email to
                                                        </p>
                                                        <ul class="list_style">
                                                            <li class="green_color">
                                                                ossbida@bidaquickserv.org
                                                            </li>
                                                            <li class="green_color">
                                                                support@ba-systems.com
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <a href="https://download.anydesk.com/AnyDesk.exe">
                                                    <div class="col-xs-3">
                                                        <div class="contact_image">
                                                            <img src="{{ url('assets/images/need_help/anydesk.png') }}" alt="Support from anydesk">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <div class="contact_content">
                                                            <ul class="list_style">
                                                                <li class="contact_content_list">
                                                                    Use Anydesk Software to show your actual problem to
                                                                    help desk officer
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <img src="{{ url('assets/images/need_help/phone.png') }}" alt="Support related complaint number">
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <p class="contact_content_title">
                                                            Support related complaint number
                                                        </p>
                                                        <ul class="list_style">
                                                            <li class="green_color">
                                                                +8801755676712
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <img src="{{ url('assets/images/need_help/home.png') }}" alt="Support from home will be ensure">
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <ul class="list_style">
                                                            <li class="contact_content_list">
                                                                Support from home will be ensure
                                                            </li>
                                                            <li class="contact_content_list">
                                                                Sunday to Thursday: 9:00am-5:00pm
                                                            </li>
                                                            <li class="contact_content_list">
                                                                Friday & Saturday: Closed
                                                            </li>
                                                            <li class="contact_content_list">
                                                                All Govt. Holiday: Closed
                                                            </li>
                                                            <li class="contact_content_list">
                                                                <a class="btn btn-info btn-xs" href="#technical_support">More Information</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="contact_box_outer">
                                            <div class="contact_box">
                                                <div class="col-xs-3">
                                                    <div class="contact_image">
                                                        <a href="#technical_support">
                                                            <img src="{{ url('assets/images/need_help/oss_help_desk.png') }}" alt="Oss Help Desk">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-xs-9">
                                                    <div class="contact_content">
                                                        <ul class="list_style">
                                                            <li>
                                                                <strong> Oss Help Desk</strong>
                                                            </li>
                                                            <li class="contact_content_list">
                                                                Hi! We're here to answer any questions you may have.
                                                            </li>
                                                            <li class="contact_content_list">
                                                                <a target="_blank" rel="noopener" class="btn btn-info btn-xs" href="https://support.ba-systems.com">Submit a ticket</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div style="padding: 15px 0;">
                                        <label class="radio-inline">Is this article helpful?</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('yes', 4)">
                                            Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('no', 4)">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--service delivery from home end--}}
                    <div class="col-sm-12">

                        {!! Session::has('success') ? '
                <div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                        {!! Session::has('error') ? '
                        <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                        ' : '' !!}


                        <div class="q-support">
                            <div class="item-s">
                                <p style="padding-bottom: 0px;">
                                    The One Stop Service (OSS) is an online facilitation mechanism that seeks to
                                    bring
                                    relevant government
                                    agencies together, coordinated and streamlined, to provide efficient and
                                    transparent
                                    services to domestic and
                                    foreign investors. Many countries around the world have established similar
                                    centres
                                    to centralize the execution
                                    of a number of regulatory, compliance, and value-added services through a single
                                    physical or virtual location.
                                    The purpose of the OSS is to provide investors with a single place for applying
                                    and
                                    obtaining necessary
                                    approvals for different licenses and permits required for investment in the
                                    country.
                                </p>
                                <p style="padding-bottom: 0px;">
                                    These electronic investor’s services shall entirely be automated, paperless, and
                                    cashless. Specifically, all
                                    services shall be requested and rendered electronically via an online platform;
                                    all
                                    required supporting
                                    documents shall be transmitted, stored, and processed in electronic format; all
                                    applications shall be signed
                                    electronically; and all required official payments shall be made electronically
                                    in
                                    real time. The OSS shall be
                                    owned, operated, and regulated by BIDA. Its implementation shall adopt a
                                    service-oriented architecture that
                                    allows it to interface with already existing independent electronic systems of
                                    other
                                    participating agencies.
                                    The OSS shall ultimately serve as a single window and the only point of contact
                                    between the Government of
                                    Bangladesh (GoB) and investors. It shall comprise the following components:
                                </p>

                                <ol>
                                    <li>
                                        A single website comprised of two sub-components. First, an online platform
                                        for
                                        delivering investor services
                                        to registered users. The platform will enable investors to view, request,
                                        and
                                        receive services and will enable
                                        the concerned government agencies to deliver those services. Second, an
                                        information portal for presenting all
                                        investment news, statistics, reports, primary and secondary laws, guidelines
                                        and
                                        instructions, and any other
                                        investment-related information.
                                    </li>
                                    <li>
                                        An internal workflow management system that enables BIDA to process
                                        applications
                                        in the back office. The
                                        workflow management system shall automatically orchestrate electronic
                                        service
                                        delivery through the
                                        concerned participating government agencies as well as the flow of
                                        information
                                        among them.
                                    </li>
                                    <li>
                                        An internal document management and archiving system that enables BIDA to
                                        manage
                                        all electronic documents transmitted via its portal.
                                    </li>
                                    <li>
                                        A centralized backend data storage system with data redundancy, which
                                        enables
                                        BIDA to store and manage all transaction, user, and system data.
                                    </li>
                                    <li>
                                        An internal reporting engine and data discovery and visualization tools.
                                    </li>
                                </ol>

                                <h3>How can I get a verification link again?</h3>
                                <p>
                                    Please enter your registered email address and submit.
                                </p>
                            </div>

                            <div class="text-center ">
                                {!! Form::open(array('url' => 'users/resend-email-verification','method' => 'post','enctype'=>'multipart/form-data', 'id' => 'verification', 'role'=>'form', 'class'=>'navbar-form navbar-left well')) !!}
                                {!! Form::label('email','Resend Verification Email',['class'=>'text-left required-star']) !!}
                                {!! Form::text('email', '', ['class'=>'form-control required', 'id'=>"email", 'placeholder' => 'Email']) !!}

                                <div class="form-group {{$errors->has('g-recaptcha-response') ? 'has-error' : ''}}">
                                    {!! Recaptcha::render() !!}
                                    {!! $errors->first('g-recaptcha-response','<span class="help-block">:message</span>') !!}
                                </div>

                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md" value="Submit" name="actionBtn">Submit
                                </button>
                                {!! Form::close() !!}
                            </div>

                            <div class="item-s">
                                <h3 id="">Why I need to Sign-up to submit application?</h3>
                                <p>
                                    The One Stop Service (OSS) system is web-based application for investors. This
                                    service is entirely automated, paperless and cashless. Specifically, this
                                    service
                                    shall be requested and rendered electronically via the OSS online platform; all
                                    required supporting documents shall be transmitted, stored, and processed in
                                    electronic format; all applications shall be signed electronically; and all
                                    required
                                    official payments shall be made electronically in real time in that cases the
                                    sign
                                    up mandatory.
                                </p>

                                <p style="margin-bottom: 0; padding-bottom: 5px;">
                                    <span>Video tutorial for using the system :</span>
                                    <a href="https://youtu.be/jsdi3MO__hU">https://youtu.be/jsdi3MO__hU</a>
                                </p>
                            </div>

                            <div class="item-s">
                                <h3 id="">How can I get updates on the application status?</h3>
                                <p>
                                    After successfully submission of any application, user can get the current
                                    status of
                                    duly submitted application. This action can be seen from the status option:
                                    <br/><span>Submitted: </span> The stage means that you have submitted
                                    successfully.
                                    <br/><span>Verified:</span> The stage belongs that your application has been
                                    successfully reviewed by BIDA.
                                    <br/><span>Approved:</span> The stage belongs that you submission has been
                                    already
                                    got the approval. You will get an auto generated e‐mail to your authorize
                                    person’s
                                    e‐mail address also it to be send to concern stockholder with approval copy.
                                    <br/><span>Shortfall:</span> The stage means that you have submitted your
                                    application with required information and BIDA have already reviewed the
                                    application
                                    but considered the application as shortfall due to some information or documents
                                    mismatch or not uploaded correctly. In this stage you will also get an e‐mail
                                    from
                                    BIDA as shortfall. You can see the remarks of shortfall the top side of the
                                    application. If it is information mismatch you are able to edit the application
                                    and
                                    re-submit.
                                    <br/><span>Discard:</span> The stage belongs that you have submitted your
                                    application with such type of information that cannot be considered as realistic
                                    or
                                    have not existence of your provided information or completed your application
                                    with
                                    garbage data. BIDA has right to drop your submission request if found any thing
                                    seems like that.
                                    <br/><span>Rejected:</span> The stage belongs that you have submitted your
                                    application with such type of information that cannot be considered as
                                    realistic.
                                    BIDA has right to reject your application for any false submission.
                                </p>
                            </div>

                            <div class="item-s">
                                <h3 id="">To whom I should contact for Registration process related support?</h3>
                                <p>
                                    You can contact directly to the respective officer where your application is
                                    currently under process. You can see the serving desk on your user there is
                                    showing
                                    the current desk officer name and designation. For general purpose information,
                                    you
                                    can contact the below during office hour: </br>
                                    {{--<span><span class="fbold">Phone:</span>  +8809602666707</span></br>--}}
                                    <span><span>Call center no.:</span> +8809678771353</span></br>
                                    <span><span>Email:</span> ossbida@bidaquickserv.org </span></br>
                                    For more information please visit: <a href="http://www.bida.gov.bd">http://www.bida.gov.bd</a>
                                </p>

                                <p style="text-transform: uppercase;">Help Desk Operating Hours</p>
                                <ul class="list_style">
                                    <li>Sunday-Thursday: 9AM-5PM</li>
                                    <li>Friday & Saturday: Closed</li>
                                    <li>All Govt Holiday: Closed</li>
                                </ul>
                            </div>

                            <div class="item-s">
                                <h3 id="technical_support">To whom should I contact for technical support?</h3>
                                <p>
                                    <strong>Business Automation Ltd.</strong> provides technical support for this
                                    project.
                                    You can contact with the respective officer for your necessary technical support
                                    during office hour. </br>
                                    {{--<span><span class="fbold">Phone:</span> +88-02-9587353, Ext.1802.</span></br>--}}
                                    {{--<span><span class="fbold">Fax:</span> +880-2-914-3656</span></br>--}}
                                    <span><span>Call center no.:</span> +8809678771353</span></br>
                                    <span><span>Email:</span> support@ba-systems.com </span></br>
                                    <span>Online Support portal:</span>
                                    <a href="https://support.ba-systems.com">https://support.ba-systems.com</a>
                                </p>
                            </div>
                            <div class="item-s">
                                <h3 id="supervising_officer">Supervising Officer of One Stop Service (OSS) System
                                </h3>

                                    <span>Jibon Krishna Saha Roy</span></br>
                                    <span>Director</span></br>
                                    <span>One Stop Service (OSS)</span></br>
                                    <span>Bangladesh Investment Development Authority</span></br>
                                    <span><span>Phone :</span> +880255007217</span></br>
                                    <span><span>Mobile :</span> +8801846740822</span></br>
                                    <span><span>Email :</span> dir5.osss@bida.gov.bd</span></br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(\Illuminate\Support\Facades\Auth::guest())
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
            (function () {
                var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = 'https://embed.tawk.to/5c43f89bab5284048d0db313/default';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
        <!--End of Tawk.to Script-->
    @endif

    @if(\Illuminate\Support\Facades\Auth::guest())
        <!-- Load Facebook SDK for JavaScript -->
        <div id="fb-root"></div>
        <script>
            window.fbAsyncInit = function () {
                FB.init({
                    appId: 'your-app-id',
                    autoLogAppEvents: true,
                    xfbml: true,
                    version: 'v3.2'
                });
            };
            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>


        <!-- Your customer chat code -->
        <div class="fb-customerchat"
             attribution=setup_tool
             page_id="<?php echo env('facebook_page_id', '375712622979368');?>"
             theme_color="<?php echo env('facebook_theme_color', '#3AA8D8');?>">
        </div>
        <!-- End Facebook SDK for JavaScript -->
    @endif

@endsection
@section('footer-script')
    <script src="{{ asset("assets/scripts/jquery.validate.js") }}"></script>
    <script>
        $(document).ready(function () {
            $("#verification").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        //this function for is helpful article
        function isHelpFulArticle(is_helpful_status, slug) {

            $.ajax({
                url: "<?php echo url('/web/is-helpful-article'); ?>",
                type: 'GET',
                data:{
                    is_helpful:is_helpful_status,
                    slug:slug
                },
                async:false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    toastr.success("Thanks for your feedback");
                    return false;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    </script>
@endsection