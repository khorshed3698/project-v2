@include('partials.datatable-css')
<link rel="stylesheet" href="{{ asset('assets/plugins/swiper-bundle.css') }}"/>
    <style>
        .home-wrapper {
            margin-top: 0;
            font-family: Kalpurush;
        }

        .trainingWebCourseDetailsSec {
            padding-top: 50px;
        }

        .web_back_button a {
            color: #48296E;
            border: 1px solid #48296E;
        }
        .trainingWebCourseDetailsSec .course_title_sec{
            background-color: #F9F9F9;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .trainingWebCourseDetailsSec .course_title {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
            margin: 0;
            padding-top: 10px;
        }
        .trainingWebCourseDetailsSec .course_description_sec{
            background-color: #F9F9F9;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            /*margin-top:15px;*/
            padding: 10px 15px;
            border-radius: 5px;
            overflow: auto;
        }


        .service_details_panel_apply{
            margin-bottom: 15px;
        }
        .service_details_panel_apply a{
            background-color: #009852;
            color: #FFFFFF;
            padding: 6px 69px;
        }
        .service_details_panel_apply a:hover{
            background-color: #009852;
            color: #FFFFFF;
        }
        .thumbnail img {
            width: 100%;
        }

        .thumbnail .play_btn {
            background-color: transparent;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 66px;
            height: 66px;
            border: 1px solid transparent;
            padding: 5px;
            border-radius: 50%;
            transition: all 0.5s;
        }

        .thumbnail .play_btn:hover {
            border: 1px solid #fff;
            transition: all 0.5s;
            background-color: rgba(255, 255, 255, 0.6);
        }
        .thumbnail .play_btn {
            background-color: transparent;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 66px;
            height: 66px;
            border: 1px solid transparent;
            padding: 5px;
            border-radius: 50%;
            transition: all 0.5s;
        }

        .thumbnail .play_btn:hover {
            border: 1px solid #fff;
            transition: all 0.5s;
            background-color: rgba(255, 255, 255, 0.6);
        }
        .service_details_panel_download_btn{
            background-color: #FFEA96;
            color: #9C7C00;
            padding: 10px 15px;
            text-decoration: none;
            margin-top: 8px;
        }
        .help_widget {
            height: 360px;
            width: 100%;
            background: inherit;
            background-color: rgba(255, 255, 255, 1);
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.117647058823529);
            position: relative;
            margin-bottom: 10px;
        }

        .help_widget:hover {
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
        }

        .help_widget_header {
            padding: 10px;
        }

        .help_widget_header img {
            width: 100%;
            border-radius: 10px;
            height: 200px;
            padding-top: 0 !important;
            background-position: center;
            background-size: cover;
        }

        .help_widget_content {
            padding: 0 15px;
        }

        .help_widget_content h3 {
            font-weight: 600;
            overflow: hidden; /* make sure it hides the content that overflows */
            white-space: normal; /* allow multiple lines of text */
            display: -webkit-box;
            -webkit-line-clamp: 2; /* show 2 lines of text */
            -webkit-box-orient: vertical;
            text-overflow: ellipsis; /* give the beautiful '...' effect */
            height: 3em; /* adjust the height to control the number of lines shown */
            line-height: 1.5em;
            font-size: 22px;
        }

        .training_course_heading3 h3 {
            margin-left: 35px;
            font-weight: bold;
            font-size: 22px;
        }

        .training_course_button {
            padding-top: 5px;
        }

        .training_course_button a {
            background-color: #00a65a;
            padding: 3px 30px;
            border-radius: 5px;
            font-size: 15px;
            color: white;
        }

        .help_widget_content p {
            font-size: 14px;
        }

        .footerElement {
            padding: 5px 10px;
            width: 95%;
            position: absolute;
            bottom: 10px;
        }

        @media screen and (min-width: 1900px) {
            .help_widget {
                /*height: 520px;*/
            }

            /** end -:- (Screen Greater Than 1200px) **/
        }
    </style>


{{--    <section class="trainingWebCourseDetailsSec">--}}
{{--        <div class="container">--}}
{{--            <div class="course_title_sec">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <h3 class="course_title">--}}
{{--                            উদ্যোগ ও সুবিধা--}}
{{--                        </h3>--}}
{{--                    </div><!--./col-md-12-->--}}
{{--                </div><!--./row-->--}}
{{--            </div><!--./course_title_sec-->--}}
{{--            <div class="course_description_sec">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        এতদিনে অনেকেই ডকার নামটা শুনেছি। কিন্তু এটা কি জিনিস সেটা দশ জনকে জিজ্ঞেস করলে হয়তো বারো রকমের উত্তর পাওয়া যাবে! অনেকেই “ডকার কি জিনিস বা এটা কেন দরকার" যদিও কিছুটা বুঝি, অন্য কাউকে বোঝাতে গেলে আটকে যাই। তাই কথা খুব বেশি না বাড়িয়ে, অন্তত চায়ের টং-এ বা কফির দোকানে গল্প-আড্ডায় ডকার শব্দটা চলে আসলে কি বলতে হবে সেটা জেনে নেই চলুন!--}}

{{--                        ডকার কি ও কেন ?--}}
{{--                        মাইক্রোসার্ভিস ডেভেলপার বা ডেভ-অপস যারা আছেন তাদের কাছে ডকার নামটা বেশ পরিচিত।একটু গভীরে গিয়ে ডকারের ব্যাপারে জানতে হলে অপারেটিং সিস্টেম লেভেল ভার্চুয়ালাইজেসন (Operating System Level Virtualization) কি জিনিস সেটা বুঝতে হবে। আপাতত নিচের সংজ্ঞাটা দেখে নেই, বিস্তারিত পরে আসছে।--}}

{{--                        একটু খুলে বললে আধুনিক অপারেটিং সিস্টেমগুলোতে আমাদের অ্যাপ্লিকেশনগুলো ভার্চুয়াল মেমোরি (Virtual Memory) নামক একটা মেমোরি এড্রেস স্পেইসে চলে। এই ব্যাপারটা আমরা অনেকেই জানি। এভাবে ভার্চুয়াল মেমোরি ব্যবহার করার পিছনে অনেকগুলো কারনের মধ্যে অন্যতম একটি হচ্ছে মেমোরি প্রটেকশন (Memory Protection), মানে রান টাইমে একটি প্রসেস যাতে অন্য আরেকটি প্রসেসের মেমোরি স্পেইসে (Memory Space) প্রবেশ করতে না পারে সেটা নিশ্চিত করা।--}}

{{--                        অপারেটিং সিস্টেমে কিছু প্রসেস আছে যাদের সিস্টেম রিসোর্সগুলো (System Resource) সরাসরি অ্যাক্সেস করার প্রিভিলেজ (Privilege) থেকে থাকে। এই প্রসেসগুলো সাধারণত কার্নেল (Kernel) এবং ডিভাইস ড্রাইভার (Device Drivers) হয়ে থাকে। এসমস্ত প্রিভিলেজসম্পন্ন প্রসেসসমুহের মেমোরি প্রটেকশনের জন্যে ভার্চুয়াল মেমোরি স্পেইসের একাংশকে dedicate করে দেয়া হয়। এর কারনে ভার্চুয়াল মেমোরি দ্বিখণ্ডিত হয়। একটি খণ্ডে চলে কার্নেল আর ডিভাইস ড্রাইভারদের মত প্রিভিলেজসম্পন্ন প্রসেসসমুহ আর এই খণ্ডটা কার্নেল স্পেইস (Kernel Space) নামে পরিচিত। আরেকটি খণ্ডে চলে বাকি সব সাধারণ প্রসেস যেমন ব্রাউসার, টেক্সট এডিটর, গেমস, ইত্যাদি আর এই খণ্ডটা ইউসার স্পেইস (User Space) নামে পরিচিত।--}}

{{--                        প্রিভিলেজের এরূপ বৈষম্যের কারনে ইউসার স্পেইসের প্রসেসগুলো (বা ইউসার প্রসেস) সিস্টেম রিসোর্সগুলোকে সরাসরি এক্সেস করতে পারে না, বরং তারা কার্নেল ও ডিভাইস ড্রাইভারদের মাধ্যমে সিস্টেম রিসোর্সগুলো এক্সেস করে থাকে। আর এটা করতে ইউসার প্রসেস কার্নেলের সাথে সিস্টেম কল (System Call) এর মাধ্যমে communicate করে।--}}

{{--                    </div><!--./col-md-12-->--}}
{{--                </div><!--./row-->--}}
{{--            </div><!--./course_description_sec-->--}}
{{--        </div><!--./container-->--}}
{{--    </section>--}}


<div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
                <h4 id="local_machine_modal_head" class="modal-title" style="color: #452A73; font-size: 14px">
                    Youtube Video
                </h4>
            </div>

            <div class="modal-body">
                <div class="bscic_video">
                    <iframe title="video" allow="fullscreen"
                            src="https://www.youtube.com/embed/daU8TCNWP7M"></iframe>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm close_modal" data-dismiss="modal"
                        style="float: right;">Close
                </button>
            </div>
        </div>
    </div>
</div>






@if($trainingCourses->isEmpty())
    <div class="row" id="content">
        <div class="col-md-12">
            <h3 class="text-center">কোর্স পাওয়া যায়নি</h3>
        </div>
    </div>
@else

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="training_course_heading3">
                    <h3 class="text-start">প্রশিক্ষণ কোর্স সমূহের তালিকা</h3>
                </div>
            </div>
        </div>
        <div class="row">

            @foreach($trainingCourses as $course)
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                    <div class="help_widget">
                        <div class="help_widget_header text-center">
                            <img alt='...' style=" border: 1px solid #00a65a;"
                                 src="{{ asset('/uploads/training/'.$course->course_image) }}"
                                 onerror="this.src=`{{asset('/assets/images/no-image.png')}}`"/>
                        </div>
                        <div class="help_widget_content text-left">
                            <h3 title="{{$course->course_title}}">{{ $course->course_title }}</h3>
                            <div class="row footerElement training_course_button">
                                <div class="pull-right ">
                                    <a href="{{ url('/tr/'.$course->course_slug) }}"
                                       class="btn">বিস্তারিত</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>



    <script src="{{ asset('assets/plugins/swiper-bundle.js') }}"></script>
    @include('partials.datatable-js')
    <script language="javascript">
        $(document).ready(function () {
            $('#myTable').DataTable();
            $(".read-more").click(function () {
                $(this).remove();
                $("#showDetails").show();
            });
        });

        // Youtube video on click
        $('#video_modal').on('shown.bs.modal', function (e) {
            if (!$('.bscic_video').hasClass('has_video')) {
                $('.bscic_video').addClass('has_video');
            }
        });
        $('#video_modal').on('hidden.bs.modal', function (e) {

        });
    </script>
@endif

<section class="trainingWebCourseDetailsSec">
    <div class="container">
        <div class="course_title_sec">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="course_title">
                        ব্যবহার পদ্ধতি
                    </h3>
                </div><!--./col-md-12-->
            </div><!--./row-->
        </div><!--./course_title_sec-->
        <div class="course_description_sec">
            {{--                <div class="row">--}}


            <div class="row">
                <div class="col-lg-10 col-md-10 col-md-12 col-xs-12">
                    <h3 class="text-center"><b>বিসিক ব্যবহারকারী সাইন ইন এবং সাইন আপ</b></h3>
                    <p>ধাপ-১: ওএসএস সিস্টেমে লগইনকরার জন্য <a href="">https://ossbscic.gov.bd</a> লিংকে প্রবেশ করতে হবে। লিংকে প্রবেশ করার পর ইউজার থাকলে লগইন ক্লিক করে লগইন করতে হবে এবং যদি ইউজার না থাকে তাহলে নিবন্ধন ক্লিক করে Sign Up করতে হবে।</p>
                    <p>ধাপ-২: নিবন্ধন বাটনে ক্লিক করলে সাইন আপ পেইজটি দেখাবে। প্রথমে ইংরেজীতে নাম এবং ইমেইল লিখতে হবে। Gender থেকে Gender সিলেক্ট করবে এবং ফোন নাম্বার লিখতে হবে। Get password via থেকে Email অথবা SMS সিলেক্ট করতে হবে। I’m not a robot এর চেক বক্সে ক্লিক করে Sign Up বাটনে ক্লিক করতে হবে। Sign Up বাটনে ক্লিক করার পর যে মেইল ব্যবহার করে ইউজার তৈরি করা হয়েছে সেখানে একটি ভেরিফিকেশন লিংক যাবে।<p/>
                    <p>ধাপ-৩: যে মেইল আইডি দিয়েছেন সেই মেইলটি ওপেন করলে verify লিংক সহ একটি ইমেইল পাওয়া যাবে এবং Verify your email account এ ক্লিক করে ভেরিফাই করতে হবে।<p/>
                    <p>ধাপ-৪: ভেরিফাই করার পর ২য় একটি ইমেইল যাবে এবং ইমেইলটি অপেন করলে পাসওয়ার্ড পাওয়া যাবে। ইমেইল আইডিটি  ইউজার আইডি হিসেবে ব্যাবহার করে আইডি পাসওয়ার্ড দিয়ে লগইন করতে হবে।<p/>
                    <p>ধাপ-৫: যদি কারো ইমেইল না থাকে তাহলে I have no email address এর চেক বক্সে ক্লিক করতে হবে, সেক্ষেত্রে পাসওয়ার্ডটি শুধুমাত্র SMS এপাওয়া যাবে। প্রয়োজনীয় তথ্য দিয়ে Sign Up বাটনে ক্লিক করতে হবে।<p/>
                    <p>ধাপ-৬: Sign Up বাটনে ক্লিক করার পর মোবাইল নম্বর ইনপুট দেয়ার একটি অপশন আসবে এবং  Sign Up পেইজে যেই ফোন নাম্বার দিয়েছেন সেই নাম্বারটি ইনপুট দিয়ে Submit বাটনে ক্লিক করতে হবে। Submit দেয়ার পর SMS এর মাধ্যমে আপনার ফোন নাম্বারে login email address এবং account key পাওয়া যাবে।<p/>
                    <p>ধাপ-৭: Submit বাটনে ক্লিক করার পর account key দেয়ার একটি অপশন আসবে এবং account key দিয়ে Submit বাটনে ক্লিক করতে হবে।<p/>
                    <p>ধাপ-৮: Submit বাটনে ক্লিক করার পর পাসওয়ার্ড লিখার অপশন পাওয়া যাবে এবং পাসওয়ার্ড তৈরী করে নিতে হবে ,পাসওয়ার্ডটি অবশ্যই 6 characters হতে হবে এবং 6 characters এর মধ্যে 1 alphabet, 1 number, 1 special character থাকতে হবে।<p/>
                    ধাপ-৯: OSSPID একাউন্ট তৈরী করার পর <a href="https://ossbscic.gov.bd">https://ossbscic.gov.bd</a> লিংকে প্রবেশ করে User ID (Email) এবং Password দিয়ে লগইন করবে।<p/>
                </div><!--./col-md-9-->
                <div class="col-lg-2 col-md-2 col-md-12 col-xs-12 tutorial">

                    <p style="font-size: 18px; color: #452A73;">টিউটোরিয়াল</p>

                    <div class="embed-responsive embed-responsive-16by9 thumbnail service_details_panel_video">
                        <img alt='...' class="img"
                             src="https://img.youtube.com/vi/9zQdG_EaNgw/maxresdefault.jpg">
                        <img alt='...' class="play_btn" src="{{ asset('assets/images/youtube.png') }}"
                             data-toggle="modal"
                             data-target="#video_modal" style="cursor: pointer">
                    </div>


                    <div class="panel" style="border-radius: 10px;">
                        <div class="panel-heading" style="background: #F0F1F2">
                            <p style="font-size: 14px; font-weight: 400; padding: 8px; padding-bottom: 0">
                                ব্যবহারবিধি ডাউনলোড করুন</p>
                        </div>
                        <div class="panel-body" style="background: #F8F9FA; padding: 15px">
                            <a href="{{ asset('assets/landing_pages/pdf/BSCIC_Log_In.pdf') }}" class="btn service_details_panel_download_btn" target="_blank">ডাউনলোড&nbsp;<i class="fa fa-download"></i></a>
                            <br>
                        </div>
                    </div>
                </div><!--./col-md-3 (tutorial)-->
            </div><!--./row-->

            {{--                </div><!--./row-->--}}
        </div><!--./course_description_sec-->
    </div><!--./container-->
</section>
