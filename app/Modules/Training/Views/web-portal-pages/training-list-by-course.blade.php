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
            font-family: kalpurushregular, 'Helvetica Neue', Arial, sans-serif;
        }

        #trainingWebCourseDetailsSec {
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .web_back_button a {
            color: #48296E;
            border: 1px solid #48296E;
        }

        #trainingWebCourseDetailsSec .course_title_sec {
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

        #trainingWebCourseDetailsSec .course_description_sec {
            background-color: #F9F9F9;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            margin-top: 15px;
            padding: 10px 15px;
            border-radius: 5px;
            overflow: auto;
        }

        .load_upcoming_course_by_course_btn button {
            background-color: #472A6D;
            color: #FFFFFF;
            padding: 5px 10px;
            border-radius: 10px;
        }

        .course_slider_sec {
            padding: 20px 0;
        }

        .course_slider_sec .carousel-inner {
            border: 1px solid #1C61B0;
            border-radius: 10px;
        }

        .course_slider_sec .carousel-indicators .active {
            background-color: #1C61B0;
        }

        .course_slider_sec .course_slider_image {
            width: 100%;
            display: block;
        }
        .other_location_program_title h2{
            color: #249954;
        }
        .carousel-inner > .item > img, .carousel-inner > .item > a > img {
            height:400px
        }
    </style>

@endsection


@section ('body')
    <section id="trainingWebCourseDetailsSec">
        <div class="container">
            <div class="course_title_sec">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center course_title">কোর্সের নাম</h3>
                        <h3 class="text-center course_title">{{ $courseDetails->course_title }}</h3>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./course_title_sec-->
            <div class="course_slider_sec">
                <div class="row">
                    <div class="col-md-12">
                        <div id="courseCarousel" class="carousel slide" data-ride="carousel">
                        @if(count($courseSliders) > 0)
                            <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    @foreach($courseSliders as $key => $course)
                                        @if($key == 0)
                                            <li data-target="#courseCarousel" data-slide-to="{{ $key }}"
                                                class="active"></li>
                                        @else
                                            <li data-target="#courseCarousel" data-slide-to="{{ $key }}"></li>
                                        @endif
                                    @endforeach
                                </ol>
                                <div class="carousel-inner">
                                    @foreach($courseSliders as $key => $value)
                                        @if($key == 0)
                                            <div class="item active">
                                                <img class="course_slider_image"
                                                     src="{{ asset('/uploads/training/'.$value) }}" alt="..."
                                                     onerror="this.src=`{{asset('/assets/images/no-image.png')}}`"/>
                                            </div>
                                        @else
                                            <div class="item">
                                                <img class="course_slider_image"
                                                     src="{{ asset('/uploads/training/'.$value) }}" alt="..."
                                                     onerror="this.src=`{{asset('/assets/images/no-image.png')}}`"/>
                                            </div>
                                        @endif
                                    @endforeach
                                </div><!--/.carousel-inner-->
                            @else
                                <div class="carousel-inner">
                                    <div class="item active">
                                        <img class="course_slider_image" src="{{asset('/assets/images/no-image.png')}}"
                                             alt="..."/>
                                    </div>
                                </div><!--/.carousel-inner-->
                            @endif
                        </div><!--/.courseCarousel-->
                    </div>
                </div>
            </div><!--/.course_slider_sec-->
            <div class="course_description_sec">
                <div class="row">
                    <div class="col-md-12">

                        <div class="">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ trans('Training::messages.necessity') }}</strong></h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(!empty($courseDetails->qualifications_exp))
                                            {!!html_entity_decode($courseDetails->qualifications_exp)!!}
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><strong>{{ trans('Training::messages.details') }}</strong></h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(!empty($courseDetails->course_contents))
                                            {!!html_entity_decode($courseDetails->course_contents)!!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./course_description_sec-->

        </div><!--./container-->

        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="other_location_program_title">
                        @if($totalScheduleCourse > 0)
                            <h2>
                                {{ !empty($courseDetails->course_title_bn)?$courseDetails->course_title_bn:$courseDetails->course_title }}
                                যে সকল স্থানে চলমান আছে</h2>
                        @else
                            <div class="course_title_sec">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h3 class="course_title" style="text-align: center;color: red">এই মূহুর্তে এই
                                            কোর্স টি আসন্ন নেই</h3>
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
                    <div id="loadUpcomingTrainingByCourseDiv"></div>
                </div>
            </div>
        </div>
        {{--        @if($totalScheduleCourse > 0)--}}
        {{--            <div class="container">--}}
        {{--                <div class="row">--}}
        {{--                    <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 text-center">--}}
        {{--                        <div class="load_upcoming_course_by_course_btn">--}}
        {{--                            <button id="loadUpcomingTrainingByCourseBtn"--}}
        {{--                                    onclick="loadUpcomingTrainingByCourse()">{!! trans('messages.industry_registered_by_sector.see_more') !!}--}}
        {{--                                &nbsp;<i class="fa fa-arrow-circle-down"></i></button>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        @endif--}}

    </section><!--#trainingWebCourseDetailsSec-->
@endsection


@section('footer-script')
    <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            loadUpcomingTrainingByCourse();
        });// end -:- document ready

        var isLoadingCourse = false;
        var pageSizeCourse = 3;
        var currentPageCourse = 1;

        function loadUpcomingTrainingByCourse() {
            if (isLoadingCourse) {
                return; // Prevent multiple simultaneous requests
            }
            $.ajax({
                type: "GET",
                url: "{{ url('/training/load-upcoming-training-by-course') }}",
                dataType: "json",
                data: {
                    course_id: "{{Encryption::encodeId($courseDetails->id)}}",
                    page: currentPageCourse,
                    pageSize: pageSizeCourse
                },
                beforeSend: function () {
                    isLoadingCourse = true;
                    $('#loadUpcomingTrainingByCourseDiv').html('<h3 class="text-center text-info">Loading...<i class="fa fa-spinner fa-spin"></i></h3>');
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        $('#loadUpcomingTrainingByCourseDiv').html(response.html);
                    } else {
                        $('#loadUpcomingTrainingByCourseDiv').html(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#loadUpcomingTrainingByCourseDiv').html(errorThrown);
                },
                complete: function () {
                    isLoadingCourse = false;
                }
            });// end -:- ajax
        }// end -:- loadUpcomingTrainingByCourse()
    </script>

@endsection