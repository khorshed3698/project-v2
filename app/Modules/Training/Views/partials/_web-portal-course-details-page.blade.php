@extends('public_home.front')

@section('meta_title')
    {{ !empty($courseDetails->course_title)?$courseDetails->course_title:'' }}
@endsection
@section('meta_description')
    {{ !empty($courseDetails->course_title)?$courseDetails->course_title:'' }}
@endsection


@section('header-resources')
    <style>
        .home-wrapper {
            margin-top: 0;
            font-family: Kalpurush;
        }

        #trainingWebCourseDetailsSec {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .web_back_button a {
            color: #48296E;
            border: 1px solid #48296E;
        }
        #trainingWebCourseDetailsSec .course_title_sec{
            background-color: #F9F9F9;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        #trainingWebCourseDetailsSec .course_title {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
            margin: 0;
            padding-top: 10px;
        }
        #trainingWebCourseDetailsSec .course_description_sec{
            background-color: #F9F9F9;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            margin-top: 15px;
            padding: 10px 15px;
            border-radius: 5px;
            overflow: auto;
        }
        .load_upcoming_course_in_office_btn button {
            background-color: #472A6D;
            color: #FFFFFF;
            padding: 5px 10px;
            border-radius: 10px;
        }
    </style>
@endsection


@section ('body')
    <section id="trainingWebCourseDetailsSec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="web_back_button">
                        <a href="{{ url('/training-list') }}" class="btn">
                            <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
                        </a>
                    </div><!--./web_back_button-->
                </div><!--./col-md-12-->
            </div><!--./row-->
            <div class="course_title_sec">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="course_title">কোর্সের নাম</h3>
                        <h3 class="course_title">{{ $courseDetails->course_title }}</h3>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./course_title_sec-->
            <div class="course_description_sec">
                <div class="row">
                    <div class="col-md-12">
                        @if(!empty($courseDetails->course_description))
                            {!!html_entity_decode($courseDetails->course_description)!!}
                        @else
                            <h3 class="text-danger text-center">কোর্সের বিবরণ পাওয়া যাই নি !</h3>
                        @endif
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./course_description_sec-->
        </div><!--./container-->

        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="other_location_program_title">
                        @if($totalScheduleCourse > 0)
                            <h2>{{ mb_substr($courseDetails->course_title, 0, 45, 'UTF-8') }}</h2>
                        @else
                            <div class="course_title_sec">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h3 class="course_title" style="text-align: center;color: red">এই মূহুর্তে এই কোর্স টি আসন্ন নেই</h3>
                                    </div>
                                </div>
                            </div>

                        @endif
                    </div>
                </div>
            </div><!--./row-->
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12" style="padding: 0;">
                    <div id="loadUpcomingCourseInOffice"></div>
                </div>
            </div>
        </div>
        @if($totalScheduleCourse > 0)
            <div class="container">
                <div class="row">
                    <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 text-center">
                        <div class="load_upcoming_course_in_office_btn">
                            <button id="loadUpcomingCourseInOfficeBtn"
                                    onclick="loadUpcomingCourseInOffice()">{!! trans('messages.industry_registered_by_sector.see_more') !!}
                                &nbsp;<i class="fa fa-arrow-circle-down"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </section><!--#trainingWebCourseDetailsSec-->
@endsection


@section('footer-script')
    <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            loadUpcomingCourseInOffice();
        });// end -:- document ready

        let pageSizeCourse = 3;
        let isLoadingCourse = false;
        let currentPageCourse = 1;

        function loadUpcomingCourseInOffice() {
            if (isLoadingCourse) {
                return; // Prevent multiple simultaneous requests
            }
            let loadBtn = document.getElementById("loadUpcomingCourseInOfficeBtn");
            let buttonText = loadBtn.innerText;
            let loadingIcon = '...<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({
                type: "GET",
                url: "{{ url('/training/load-all-course-in-schedule') }}",
                dataType: "json",
                data: {
                    course_id: "{{Encryption::encodeId($courseDetails->id)}}",
                    page: currentPageCourse,
                    pageSize: pageSizeCourse
                },
                beforeSend: function () {
                    isLoadingCourse = true;
                    loadBtn.disabled = true;
                    loadBtn.innerHTML = buttonText + loadingIcon;
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        let pageLimitCourse = (response.total_count / pageSizeCourse);
                        $('#loadUpcomingCourseInOffice').append(response.html);
                        //currentPageCourse++;
                        console.log('Course =>' + response.total_count, pageSizeCourse, currentPageCourse, pageLimitCourse);
                        if (pageLimitCourse <= currentPageCourse) {
                            loadBtn.style.display = "none";
                        }
                        if (response.total_count > pageSizeCourse) {
                            currentPageCourse++;
                        }
                    } else {
                        loadBtn.style.display = "none";
                        console.log(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    isLoadingCourse = false;
                    loadBtn.disabled = false;
                    loadBtn.innerHTML = buttonText + '<i class="fa fa-arrow-circle-down"></i>';
                    console.log('Error : ' + errorThrown);
                },
                complete: function () {
                    isLoadingCourse = false;
                    loadBtn.disabled = false;
                    loadBtn.innerHTML = buttonText + '<i class="fa fa-arrow-circle-down"></i>';
                }
            });
        }// end -:- loadUpcomingCourseInOffice()
    </script>

@endsection