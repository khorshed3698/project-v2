@extends('public_home.front')
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
                        <h3 class="course_title"> অফিসের নাম</h3>
                        <h3 class="course_title">{{ $office_name->district_office_name }}</h3>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./course_title_sec-->

        </div><!--./container-->

        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="other_location_program_title">
                        @if(count($officeWiseCourseDataCount) > 0)
                            <h2> {{ mb_substr($office_name->district_office_name, 0, 45, 'UTF-8') }} যে সব ট্রেনিং চলমান আছে</h2>
                        @else
                            <div class="course_title_sec">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h3 class="course_title" style="text-align: center;color: red">এই মূহুর্তে কোন কোর্স আসন্ন নেই</h3>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                    <div id="loadUpcomingOfficeWiseCourseInOffice"></div>
                </div>
            </div>
        </div>
        @if($officeWiseCourseDataCount > 0)
            <div class="container">
                <div class="row">
                    <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 text-center">
                        <div class="load_upcoming_course_in_office_btn">
                            <button id="loadUpcomingCourseOfficeWiseBtn"
                                    onclick="loadUpcomingCourseOfficeWise()">{!! trans('messages.industry_registered_by_sector.see_more') !!}
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
            loadUpcomingCourseOfficeWise();
        });// end -:- document ready

        let pageSizeCourse = 4;
        let currentPageCourse = 1;
        let isLoadingCourse = false;

        function loadUpcomingCourseOfficeWise() {
            if (isLoadingCourse) {
                return; // Prevent multiple simultaneous requests
            }
            let loadBtn = document.getElementById("loadUpcomingCourseOfficeWiseBtn");
            let buttonText = loadBtn.innerText;
            let loadingIcon = '...<i class="fa fa-spinner fa-spin"></i>';
            $.ajax({
                type: "GET",
                url: "{{ url('/training/load-all-office-course-in-schedule') }}",
                dataType: "json",
                data: {
                    office_id: "{{Encryption::encodeId($office_name->office_id)}}",
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
                        $('#loadUpcomingOfficeWiseCourseInOffice').append(response.html);
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
        }// end -:- loadUpcomingCourseOfficeWise()
    </script>

@endsection