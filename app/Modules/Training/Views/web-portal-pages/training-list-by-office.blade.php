@extends('public_home.front')
@section('header-resources')
    @include('public_home.web-page-sections.social-icon-top', ['title' => !empty($office_name->district_office_name)?$office_name->district_office_name:'', 'description' => !empty($office_name->district_office_name)?$office_name->district_office_name:'', 'image' => !empty($officeSliders[0])?asset('/uploads/training/'.$officeSliders[0]):''])
    <style>
        .home-wrapper {
            margin-top: 0;
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

        .load_upcoming_course_in_office_btn button {
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

        .padding_top_20 {
            padding-top: 20px;
        }
    </style>
    <style>
        /** start -:- training_course_coordinator_sec **/
        .training_course_coordinator_sec {

        }

        .training_course_coordinator_sec h5 {
            margin: 0;
            font-size: 16px;
        }

        .training_course_coordinator_sec .panel {
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .training_course_coordinator_sec .panel-default {
            border-color: #5FC5E0;
        }

        .training_course_coordinator_sec .panel-body {
            padding: 0;
            height: 320px;
        }

        .training_course_coordinator_sec .course_coordinator_img_div {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .training_course_coordinator_sec .course_coordinator_img {
            height: 120px;
            width: 120px;
        }

        #coordinatorCarousel .carousel-indicators li{
            background-color: grey;
        }

        #coordinatorCarousel .carousel-indicators .active {
            background-color: #32B1D7;
        }
        @media screen and (min-width: 768px){
            #coordinatorCarousel .carousel-indicators {
                bottom: -20px;
            }
        }
        /** end -:- training_course_coordinator_sec **/
        .course_director .panel-default {
            border-color: #5FC5E0;
        }
        .course_director .panel-body {
            padding: 35px 0 0 50px;
            height: 320px;
        }
        .course_director_office{
            position: relative;
        }
        .course_director_office h3{
            font-weight: bold;
        }
        .course_director_office:after {
            content: "";
            background: #d7cfcf;
            position: absolute;
            bottom: -10px;
            left: 0px;
            height: 5%;
            width: 80%;
        }
        .other_location_program_title h2{
            color: #249954;
        }
        .carousel-inner > .item > img, .carousel-inner > .item > a > img {
            height:400px
        }
    </style>
    <style>
        .industry_city_list_sec .industry_city_list_h3 h3{
            margin: 0;
            padding: 5px 0 5px 45px;
            font-weight: bold;
        }
    </style>
@endsection


@section ('body')
    @include('public_home.web-page-sections.social-icon-data')
    <section id="trainingWebCourseDetailsSec">
        <div class="container">
            <div class="course_title_sec">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center course_title"> অফিসের নাম</h3>
                        <h3 class="text-center course_title">{{ $office_name->district_office_name }}</h3>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div><!--./course_title_sec-->
            <div class="course_slider_sec">
                <div class="row">
                    <div class="col-md-12">
                        <div id="courseCarousel" class="carousel slide" data-ride="carousel">
                        @if(count($officeSliders) > 0)
                            <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    @foreach($officeSliders as $key => $course)
                                        @if($key == 0)
                                            <li data-target="#courseCarousel" data-slide-to="{{ $key }}"
                                                class="active"></li>
                                        @else
                                            <li data-target="#courseCarousel" data-slide-to="{{ $key }}"></li>
                                        @endif
                                    @endforeach
                                </ol>
                                <div class="carousel-inner">
                                    @foreach($officeSliders as $key => $value)
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
                        </div><!--/#courseCarousel-->
                    </div>
                </div>
            </div><!--/.course_slider_sec-->
        </div><!--./container-->

        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div id="coordinatorCarousel" class="carousel slide" data-ride="carousel">
                    @if(count($trainingCoordinators) > 0)
                    <?php $sn = 0; ?>
                    <!-- Indicators -->
                        <ol class="carousel-indicators">
                            @foreach($trainingCoordinators as $coordinator)
                                <li data-target="#coordinatorCarousel" data-slide-to="{{ $sn }}"
                                    class="{{ $sn== 0 ? 'active' : '' }}"></li>
                                <?php $sn = ($sn + 1); ?>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            <?php $sn = 0; ?>
                            @foreach($trainingCoordinators as $coordinator)
                                <div class="item {{ $sn== 0 ? 'active' : '' }}">
                                    <div class="training_course_coordinator_sec">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="padding_top_20"></div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h5><strong>কোর্স কোর্ডিনেটর</strong></h5>
                                                    </div>
                                                </div>
                                                <div class="padding_top_20"></div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center course_coordinator_img_div">
                                                        <figure>
                                                            <img src="{{ !empty($coordinator->user_pic)?asset($coordinator->user_pic):asset('assets/images/user_profile.jpg') }}"
                                                                 class="img-responsive img-circle course_coordinator_img"
                                                                 onerror="this.src=`{{asset('assets/images/user_profile.jpg')}}`"/>
                                                        </figure>
                                                    </div>
                                                </div>
                                                <div class="padding_top_20"></div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h5>
                                                            <b>{{ !empty($coordinator->user_first_name)?$coordinator->user_first_name:'' }}</b>
                                                        </h5>
                                                        <h5>{{ !empty($coordinator->designation)?$coordinator->designation:'' }}</h5>
                                                        <h5>{{ !empty($office_name->district_office_name)?$office_name->district_office_name:'' }}</h5>
                                                    </div>
                                                </div>
                                                <div class="padding_top_5"></div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h5>{{ !empty($coordinator->district_office_name)?$coordinator->district_office_name:'' }}</h5>
                                                    </div>
                                                </div>
                                                <div class="padding_top_5"></div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h5><span>মোবাইল : </span><span
                                                                    class="input_ban">{{ !empty($coordinator->user_mobile)?$coordinator->user_mobile:'' }}</span>
                                                        </h5>
                                                        <h5>
                                                            <span>ইমেইল : </span><span>{{ !empty($coordinator->user_email)?$coordinator->user_email:'' }}</span>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="padding_top_25"></div>
                                            </div>
                                        </div>
                                    </div><!--./training_course_details_sec-->
                                </div>
                                <?php $sn = ($sn + 1); ?>
                            @endforeach
                        </div><!--/.carousel-inner-->
                        @else
                            <div class="carousel-inner">
                                <div class="item active">
                                    <div class="training_course_coordinator_sec">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="padding_top_20"></div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        <h4 class="text-danger"><strong>কোর্ডিনেটরের কোনো তথ্য পাওয়া যাই নি !</strong></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div><!--/#coordinatorCarousel-->
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <div class="course_director">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="course_director_office">
                                    <h3>কার্যালয়ের তথ্য</h3>
                                </div>
                                <div class="course_director_info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4><strong>কার্যালয়ের প্রধানের নাম :</strong></h4>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>{{ !empty($trainingDirector->user_first_name)?$trainingDirector->user_first_name:null }}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <h4><strong>কার্যালয়ের প্রধানের মোবাইল :</strong></h4>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>{{ !empty($trainingDirector->user_mobile)?$trainingDirector->user_mobile:null }}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <h4><strong>কার্যালয়ের প্রধানের ইমেইল :</strong></h4></div>
                                        <div class="col-md-6">
                                            <h4>{{ !empty($trainingDirector->user_email)?$trainingDirector->user_email:null }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- course director-->
            </div><!--/.row-->
        </div><!--/.container-->

        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="other_location_program_title">
                        @if(count($officeWiseCourseDataCount) > 0)
                            <h2> {{ mb_substr($office_name->district_office_name, 0, 45, 'UTF-8') }} যে সব ট্রেনিং চলমান
                                আছে</h2>
                        @else
                            <div class="course_title_sec">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <h3 class="course_title" style="text-align: center;color: red">এই মূহুর্তে কোন
                                            কোর্স আসন্ন নেই</h3>
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
                    <div id="loadUpcomingTrainingByOfficeDiv"></div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- start Training -->
            <div class="box box-solid box-primary">
                <div class="box-header text-center">
                    <h3 class="box-title">১ জুলাই ২০২৩ হতে বিসিক জেলা কার্যালয় নিবন্ধিত প্রশিক্ষণ তথ্য</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body ">
                    <table id="courseTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="text-center"
                                width="50%">{!! trans('messages.training.training_title') !!}</th>
                            <th class="text-center"
                                width="25%">{!! trans('messages.training.training_registration') !!}</th>
                            <th class="text-center"
                                width="25%">{!! trans('messages.training.training_participant') !!}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(count($trainingCourseList) > 0)
                            @foreach($trainingCourseList as $row)
                                <tr>
                                    <th>{!! $row->course_title !!}</th>
                                    <td class="text-center input_ban">{!! $row->TOTAL_PARTICIPANT !!}</td>
                                    <td class="text-center input_ban">{!! $row->COMPLETED !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center">
                                    কোন তথ্য পাওয়া যায়নি
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- end Training -->
        </div>
        <!-- Industry City List-->
        @if(count($industryCityList) > 0)
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="industry_city_list_sec">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="industry_city_list_h3">
                                    <h3>আরো দেখুন</h3>
                                </div>
                            </div>
                        </div>
                        @foreach($industryCityList as $city)
                            <p class="industry_city_list_p"><a href="{{url('bscic-industrial-city/'.\App\Libraries\Encryption::encodeId($city->id).'/'.\App\Libraries\Encryption::encode($city->area_nm))}}" class="text-info"><u>{{ $city->name }}</u></a></p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- Industry City List-->
    </section><!--#trainingWebCourseDetailsSec-->
@endsection


@section('footer-script')
    <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('#courseCarousel').carousel();
            $('#coordinatorCarousel').carousel();
            loadUpcomingTrainingByOffice();
        });// end -:- document ready

        let isLoadingCourse = false;
        let pageSizeCourse = 3;
        let currentPageCourse = 1;

        function loadUpcomingTrainingByOffice() {
            if (isLoadingCourse) {
                return; // Prevent multiple simultaneous requests
            }
            $.ajax({
                type: "GET",
                url: "{{ url('/training/load-upcoming-training-by-office') }}",
                dataType: "json",
                data: {
                    office_id: "{{Encryption::encodeId($office_name->office_id)}}",
                    page: currentPageCourse,
                    pageSize: pageSizeCourse
                },
                beforeSend: function () {
                    isLoadingCourse = true;
                    $('#loadUpcomingTrainingByOfficeDiv').html('<h3 class="text-center text-info">Loading...<i class="fa fa-spinner fa-spin"></i></h3>');
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        $('#loadUpcomingTrainingByOfficeDiv').html(response.html);
                    } else {
                        $('#loadUpcomingTrainingByOfficeDiv').html('<h3 class="text-center text-danger">' + response.message + '</h3>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#loadUpcomingTrainingByOfficeDiv').html('<h3 class="text-center text-danger">' + errorThrown + '</h3>');
                },
                complete: function () {
                    isLoadingCourse = false;
                }
            });// end -:- Ajax
        }// end -:- loadUpcomingTrainingByOffice()
    </script>
    @include('public_home.web-page-sections.social-icon-bottom')
@endsection