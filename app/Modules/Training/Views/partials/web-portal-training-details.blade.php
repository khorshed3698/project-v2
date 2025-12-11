@extends('public_home.front')

@section('meta_keywords')
{{ !empty($courseDetails->district_office_name_en)?$courseDetails->district_office_name_en:'' }}
@endsection
@section('meta_title')
{{ !empty($courseDetails->course_title)?$courseDetails->course_title:'' }}
@endsection
@section('meta_description')
{{ !empty($courseDetails->course_title)?$courseDetails->course_title:'' }}{{ !empty($courseDetails->district_office_name_en)?','.$courseDetails->district_office_name_en:'' }}
@endsection

@section('header-resources')
    @include('public_home.web-page-sections.social-icon-top', ['title' => !empty($courseDetails->course_title)?$courseDetails->course_title:'', 'description' => !empty($courseDetails->course_title)?$courseDetails->course_title:'', 'image' => !empty($courseDetails->course_thumbnail_path)?asset('/uploads/training/'.$courseDetails->course_thumbnail_path):''])
    <style>
        body, h1, h2, h3, h4, h5, h6, html {
            font-family: kalpurushregular, NikoshBAN, SolaimanLipi, 'Helvetica Neue', Arial, sans-serif;
        }

        .home-wrapper {
            margin-top: 0;
        }

        .padding_top_5 {
            padding-top: 5px;
        }

        .padding_top_10 {
            padding-top: 10px;
        }

        .padding_top_15 {
            padding-top: 15px;
        }

        .padding_top_20 {
            padding-top: 20px;
        }

        .padding_top_25 {
            padding-top: 25px;
        }

        .padding_top_30 {
            padding-top: 30px;
        }

        .web_back_button a {
            color: #48296E;
            border: 1px solid #48296E;
        }

        #webPortalTrainingDetailsSec {
            padding-top: 30px;
        }

        @media screen and (max-width: 774px) {
            .web_back_button {
                padding-bottom: 15px;
            }

            /** end -:- (Screen Less Than 374) **/
        }

        @media screen and (max-width: 474px) {

            /** end -:- (Screen Less Than 374) **/
        }

        @media screen and (max-width: 374px) {

            /** end -:- (Screen Less Than 374) **/
        }

        /** start -:- training_course_details_sec **/
        .training_course_details_sec {

        }

        .training_course_details_sec h5 {
            margin: 0;
        }

        .training_course_details_sec .panel {
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .training_course_details_sec .panel-default {
            border-color: #5FC5E0;
        }

        .training_course_details_sec .panel-body {
            padding: 0;
        }

        .training_course_details_sec .training_course_details_title {
            font-weight: bold;
            font-size: 20px;
            overflow: hidden;
            white-space: normal;
            -webkit-line-clamp: 2;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
            height: 3em;
            line-height: 1.5em;
        }

        .training_course_details_sec .training_course_details_office {
            padding: 10px 0 10px 0;
        }

        .training_course_details_sec .training_course_details_office h5 {
            font-size: 15px;
            font-weight: bold;
        }

        .training_course_details_sec .training_course_details_img {
            padding: 10px 12px 0 12px;
        }

        .training_course_details_sec .training_course_details_img img {
            width: 100%;
            height: 150px;
        }

        .training_course_details_sec .training_course_details_text {
            font-size: 17px;
        }

        .training_course_details_sec .training_course_details_data {
            padding: 0 0 0 12px;
        }

        .training_course_details_sec .training_course_details_apply_div {
            padding-right: 12px;
        }

        .training_course_details_sec .training_course_details_apply {
            color: #FFFFFF;
            background-color: #249954;
        }

        /** end -:- training_course_details_sec **/

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
            height: 330px;
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

        /** end -:- training_course_coordinator_sec **/

        /** start -:- training_course_venue_sec **/
        .training_course_venue_sec {

        }

        .training_course_venue_sec .panel{
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background-color: #FEFEFE;
        }

        .training_course_venue_sec .panel-default {
            border-color: #5FC5E0;
        }

        .training_course_venue_sec .panel-body {

        }

        .training_course_venue_sec .training_course_venue_title {
            padding: 10px 0 10px 30px;
        }

        .training_course_venue_sec .training_course_venue_title h3 {
            margin: 0;
        }

        .training_course_venue_sec .training_course_venue {
            padding: 10px 0 10px 30px;
        }

        .training_course_venue_sec .training_course_venue h5 {
            font-size: 17px;
            margin: 0;
        }
        .training_course_venue_sec .training_course_center_image img{
            width: 100%;
            height: 220px;
            background-position: center;
            background-size: 100% 100%;
        }

        /** end -:- training_course_venue_sec **/

        /** start -:- training_course_description_sec **/
        .training_course_description_sec {
            width: 100%;
        }

        .training_course_description_sec .panel {
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background-color: #FEFEFE;
        }

        .training_course_description_sec .panel-default {
            border-color: #5FC5E0;
        }

        .training_course_description_sec .panel-body {
            padding-left: 30px;
            padding-right: 30px;
            height: 320px;
            overflow: auto;
        }

        .training_course_description_sec .training_course_description_border {
            border-top: 1px solid #ddd;
            margin-top: 15px;
        }

        /** end -:- training_course_description_sec **/


        /** start -:- training_course_schedule_sec **/
        .training_course_schedule_sec {
            width: 100%;
        }

        .training_course_schedule_sec .panel {
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        .training_course_schedule_sec .panel-default {
            border-color: #5FC5E0;
        }

        .training_course_schedule_sec .panel-body {
            /*height: 235px;*/
        }


        .training_course_schedule_sec .training_course_schedule_title {
            padding-left: 30px;
        }

        .training_course_schedule_sec .training_course_schedule_border {
            border-top: 1px solid #1329B0;
            margin-left: 30px;
            margin-right: 30px;
        }

        .training_course_schedule_sec .table-responsive {
            padding: 15px 30px 0;
        }

        /** end -:- training_course_schedule_sec **/
        .other_location_program_title {

            padding: 15px 0 15px 0;
        }

        .other_location_program_title h2 {
            color: #249954;
            font-size: 28px;
            margin: 0;
            font-weight: bold;
        }

        .load_upcoming_course_in_office_btn {
            padding-bottom: 15px;
        }

        .load_upcoming_course_in_office_btn button {
            background-color: #472A6D;
            color: #FFFFFF;
            padding: 5px 10px;
            border-radius: 10px;
        }

        @media screen and (max-width: 474px) {
            .other_location_program_title h2 {
                text-align: center;
            }

            /** end -:- (Screen Less Than 374) **/
        }

        @media screen and (max-width: 374px) {
            .other_location_program_title h2 {
                text-align: center;
            }

            /** end -:- (Screen Less Than 374) **/
        }

        #myModal .modal-dialog-centered {
            margin-top: 10%;
        }

        #myModal .modal-body {
            padding: 100px 0 100px 0;
        }

        #myModal .training_details_modal_context p {
            margin: 0;
        }

        #myModal .training_details_modal_context {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            gap: 15px;
        }

        #myModal .modal_login_btn {
            background-color: #30A31F;
            color: #FFFFFF;
            padding: 3px 35px;
        }

        #myModal .training_details_modal_create a {
            color: blue;
            font-weight: bold;
        }

        a, a:visited, a:hover, a:active {
            color: inherit;
        }
    </style>
@endsection

@section ('body')
    @include('public_home.web-page-sections.social-icon-data')
    <section id="webPortalTrainingDetailsSec">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="training_course_details_sec">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="training_course_details_img">
                                            <figure>
                                                <img src="{{ asset('/uploads/training/'.$courseDetails->course_thumbnail_path) }}"
                                                     class="img-responsive img-rounded" alt="..."
                                                     onerror="this.src=`{{asset('/assets/images/no-image.png')}}`"/>
                                            </figure>
                                        </div>
                                        <div class="training_course_details_data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="{{ url('/tr/'.$courseDetails->course_slug) }}" title="{{ !empty($courseDetails->master_tracking_no)?$courseDetails->master_tracking_no:'' }}"
                                                       class="training_course_details_title"><u>{{ $courseDetails->course_title }}</u></a>
                                                    <h3 class="training_course_details_title">{{ !empty($courseDetails->course_title_bn)?$courseDetails->course_title_bn:'' }}</h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="training_course_details_office">
                                                        <h5>
                                                            <a class="training_course_details_office_a"
                                                               href="{{ url('/training/training-list-by-office/'.\App\Libraries\Encryption::encodeId($courseDetails->district_office_id)) }}">
                                                                <span>অফিসের নাম : </span><span><u>{{ !empty($courseDetails->district_office_name)?$courseDetails->district_office_name:'' }}</u></span>
                                                            </a>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="training_course_details_text">
                                                        <span>{{ trans('Training::messages.class_start') }}:</span>
                                                        @php
                                                            $course_duration_start = date("d M", strtotime($courseDetails->course_duration_start));
                                                            $course_duration_start = \App\Libraries\CommonFunction::convertDate2Bangla($course_duration_start);
                                                        @endphp
                                                        <span>{{$course_duration_start}}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="training_course_details_text">
                                                        <span>{{ trans('Training::messages.reg_ends') }}:</span>
                                                        @php
                                                            $enroll_deadline = date("d M", strtotime($courseDetails->enroll_deadline));
                                                            $enroll_deadline = \App\Libraries\CommonFunction::convertDate2Bangla($enroll_deadline);
                                                        @endphp
                                                        <span>{{$enroll_deadline}}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="padding_top_20"></div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="training_course_details_text">
                                                        <span class="text-success"><b>{{ trans('Training::messages.price') }} </b>:</span>
                                                        @if($courseDetails->fees_type == 'paid')
                                                        <span class="text-success input_ban"><b>{{ round($courseDetails->amount) }} টাকা</b></span>
                                                        @else
                                                        <span class="text-success"><b>{{ trans('Training::messages.free') }} </b></span>
                                                        @endif
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="training_course_details_text">
                                                        <span>{{ trans('Training::messages.service_fee') }} :</span>
                                                        <span class="input_ban"> {{$fixedServiceFeeAmount}} টাকা</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="padding_top_20"></div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="training_course_details_apply_div">
                                                        <button type="button"
                                                                class="btn pull-right training_course_details_apply"
                                                                onclick="openModal()">
                                                            {{ trans('Training::messages.apply') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="padding_top_20"></div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./training_course_details_sec-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
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
                                                    <img src="{{ !empty($courseCoordinator->user_pic)?asset($courseCoordinator->user_pic):asset('assets/images/user_profile.jpg') }}"
                                                         class="img-responsive img-circle course_coordinator_img"
                                                         onerror="this.src=`{{asset('assets/images/user_profile.jpg')}}`"/>
                                                </figure>
                                            </div>
                                        </div>
                                        <div class="padding_top_20"></div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <h5>
                                                    <b>{{ !empty($courseCoordinator->user_first_name)?$courseCoordinator->user_first_name:'' }}</b>
                                                </h5>
                                                <h5>{{ !empty($courseCoordinator->designation)?$courseCoordinator->designation:'' }}</h5>
                                            </div>
                                        </div>
                                        <div class="padding_top_5"></div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <h5>{{ !empty($courseCoordinator->district_office_name)?$courseCoordinator->district_office_name:'' }}</h5>
                                            </div>
                                        </div>
                                        <div class="padding_top_5"></div>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <h5><span>মোবাইল : </span><span
                                                            class="input_ban">{{ !empty($courseCoordinator->user_mobile)?$courseCoordinator->user_mobile:'' }}</span>
                                                </h5>
                                                <h5>
                                                    <span>ইমেইল : </span><span>{{ !empty($courseCoordinator->user_email)?$courseCoordinator->user_email:'' }}</span>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="padding_top_25"></div>
                                    </div>
                                </div>
                            </div><!--./training_course_details_sec-->
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="training_course_venue_sec">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="training_course_venue_title">
                                                    <h3><strong>প্রশিক্ষণ স্থান</strong></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="training_course_venue">
                                                    <h5>{{ $courseDetails->venue }}</h5>
                                                </div>
                                            </div>
                                        </div><!--/.row-->
                                    </div><!--/.panel-body-->
                                    <div class="training_course_center_image">
                                        <img src="{{ !empty($courseDetails->training_center_image)?asset('/uploads/training/'.$courseDetails->training_center_image):asset('assets/images/no-image.png') }}"
                                             onerror="this.src=`{{asset('assets/images/no-image.png')}}`"
                                        />
                                    </div><!--/.training_course_center_image-->
                                </div><!--/.panel-->
                            </div><!--./training_course_venue_sec-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="training_course_description_sec">
                                <div class="panel panel-default">
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
                                        <div class="training_course_description_border"></div>
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
                            </div><!--./training_course_description_sec-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="training_course_schedule_sec">
                                <div class="panel panel-default">
                                    <div class='panel-body'>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="training_course_schedule_title">
                                                    <h3>
                                                        <strong>{{ trans('Training::messages.training_schedule') }}</strong>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="training_course_schedule_border"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <th style="width: 50%;"
                                                            class="text-left">{{ trans('Training::messages.day') }}
                                                            ও {{ trans('Training::messages.time') }}</th>
                                                        <th style="width: 25%;"
                                                            class="text-left">{{ trans('Training::messages.expiry_date') }}</th>
                                                        <th style="width: 25%;"
                                                            class="text-right">{{ trans('Training::messages.seat') }}</th>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($scheduleSession as $row)
                                                            <tr>
                                                                <td style="width: 50%" class="text-left">
                                                                <span>{{date("h:i a", strtotime($row->session_start_time))}}
                                                                    - {{date("h:i a", strtotime($row->session_end_time))}}</span><br/>
                                                                    <span>{{$row->session_days}}</span>
                                                                </td>
                                                                <td style="width: 25%;"
                                                                    class="text-left input_ban"> {{$courseDetails->duration}} {{ !empty($expirationUnit[$courseDetails->duration_unit])?$expirationUnit[$courseDetails->duration_unit]:'' }} </td>
                                                                <td style="width: 25%;"
                                                                    class="text-right input_ban">{{$row->seat_capacity==0?'অনির্ধারিত':$row->seat_capacity}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./training_course_schedule_sec-->
                        </div><!--./col-md-12 (Course Schedule)-->
                    </div><!--./row (Course Schedule)-->
                </div><!-- ./col-lg-8 col-md-8 col-sm-12 col-xs-12 -->
            </div><!-- ./row (main)-->
            <!-- start -:- Suggestion Course -->

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="other_location_program_title">
                        @if($officeWiseCourseDataCount > 0)
                            <h2>{{ mb_substr($courseDetails->district_office_name, 0, 200, 'UTF-8') }} যে সকল ট্রেনিং
                                চলমান
                                আছে</h2>
                        @endif
                    </div>
                </div>
            </div><!--./row-->

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                    <div id="loadUpcomingCourseInDistrict"></div>
                </div>
            </div><!--./row-->

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="load_upcoming_course_in_office_btn">
                        <button id="loadUpcomingCourseInDistrictBtn"
                                onclick="loadUpComingCourseDistrict()">{!! trans('messages.industry_registered_by_sector.see_more') !!}
                            &nbsp;<i class="fa fa-arrow-circle-down"></i></button>
                    </div>
                </div>
            </div><!--./row-->

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="other_location_program_title">
                        @if($upcomingCourseDataCount > 0)
                            <h2>{{ mb_substr($courseDetails->course_title, 0, 200, 'UTF-8') }} যে সকল স্থানে চলমান
                                আছে</h2>
                        @endif
                    </div>
                </div>
            </div><!--./row-->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                    <div id="loadUpcomingCourseInOffice" class="training_card_grid"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="load_upcoming_course_in_office_btn">
                        <button id="loadUpcomingCourseInOfficeBtn"
                                onclick="loadUpcomingCourseInOffice()">{!! trans('messages.industry_registered_by_sector.see_more') !!}
                            &nbsp;<i class="fa fa-arrow-circle-down"></i></button>
                    </div>
                </div>
            </div>
            <div class="row" id="loadUpcomingCourseCategory"></div>
            <!-- end -:- Suggestion Course -->
        </div><!-- ./container (main)-->
    </section><!-- ./webPortalTrainingDetailsSec-->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="training_details_modal_context">
                        <p><span>আপনি যদি এই সিস্টেমের ব্যবহারকারী হয়ে থাকেন,</span><br/><span>অনুগ্রহ করে লগইন বাটন ক্লিক করুন</span>
                        </p>
                        <p><a href="{{$redirect_url}}" class="btn modal_login_btn">লগইন</a></p>
                        <p class="training_details_modal_create"><a href="https://osspid.org/user/create" class="btn"
                                                                    target="_blank">নুতন একাউন্ট তৈরি
                                করুন </a></p>
                    </div>
                </div><!--./modal-body-->
            </div>
        </div><!-- end -:- myModal -->
        @stop

        @section('footer-script')
            <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript"></script>
            <script>
                $(document).ready(function () {
                    loadUpcomingCourseInOffice();
                    loadUpComingCourseDistrict();
                });// end -:- document ready

                var districtPageSizeCourse = 3;
                var districtCurrentPageCourse = 1;
                var districtIsLoadingCourse = false;

                function loadUpComingCourseDistrict() {
                    if (districtIsLoadingCourse) {
                        return;
                    }
                    let loadBtn = document.getElementById("loadUpcomingCourseInDistrictBtn");
                    let buttonText = loadBtn.innerText;
                    let loadingIcon = '...<i class="fa fa-spinner fa-spin"></i>';
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/training/load-all-courses-by-office-in-training') }}",
                        dataType: "json",
                        data: {
                            office_id: "{{Encryption::encodeId($courseDetails->district_office_id)}}",
                            page: districtCurrentPageCourse,
                            pageSize: districtPageSizeCourse
                        },
                        beforeSend: function () {
                            districtIsLoadingCourse = true;
                            loadBtn.disabled = true;
                            loadBtn.innerHTML = buttonText + loadingIcon;
                        },
                        success: function (response) {
                            if (response.responseCode == 1) {
                                $('#loadUpcomingCourseInDistrict').append(response.html);
                                districtCurrentPageCourse++;
                                if (response.total_count <= districtPageSizeCourse) {
                                    loadBtn.style.display = "none";
                                }
                                if (districtCurrentPageCourse >= 3) {
                                    loadBtn.style.display = "none";
                                }
                            } else {
                                loadBtn.style.display = "none";
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            districtIsLoadingCourse = false;
                            loadBtn.disabled = false;
                            loadBtn.innerHTML = buttonText + '<i class="fa fa-arrow-circle-down"></i>';
                        },
                        complete: function () {
                            districtIsLoadingCourse = false;
                            loadBtn.disabled = false;
                            loadBtn.innerHTML = buttonText + '<i class="fa fa-arrow-circle-down"></i>';
                        }
                    });// end -:- Ajax
                }// end -:- loadUpComingCourseDistrict()

                var pageSizeCourse = 3;
                var currentPageCourse = 1;
                var isLoadingCourse = false;

                function loadUpcomingCourseInOffice() {
                    if (isLoadingCourse) {
                        return;
                    }
                    let loadBtn = document.getElementById("loadUpcomingCourseInOfficeBtn");
                    let buttonText = loadBtn.innerText;
                    let loadingIcon = '...<i class="fa fa-spinner fa-spin"></i>';
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/training/load-all-course-in-schedule') }}",
                        dataType: "json",
                        data: {
                            course_id: "{{Encryption::encodeId($courseDetails->tr_course_id)}}",
                            master_id: "{{Encryption::encodeId($courseDetails->id)}}",
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
                                $('#loadUpcomingCourseInOffice').append(response.html);
                                currentPageCourse++;
                                if (response.total_count <= pageSizeCourse) {
                                    loadBtn.style.display = "none";
                                }
                                if (currentPageCourse >= 3) {
                                    loadBtn.style.display = "none";
                                }
                            } else {
                                loadBtn.style.display = "none";
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            isLoadingCourse = false;
                            loadBtn.disabled = false;
                            loadBtn.innerHTML = buttonText + '<i class="fa fa-arrow-circle-down"></i>';
                        },
                        complete: function () {
                            isLoadingCourse = false;
                            loadBtn.disabled = false;
                            loadBtn.innerHTML = buttonText + '<i class="fa fa-arrow-circle-down"></i>';
                        }
                    });
                }// end -:- loadUpcomingCourseInOffice()
                function openModal() {
                    var enroll_deadline = '{{ $courseDetails->enroll_deadline }}';
                    var unix_timestamp = new Date(enroll_deadline).getTime() / 1000;
                    if (unix_timestamp > Date.now() / 1000) {
                        $('#myModal').modal('show');
                    } else {
                        Swal.fire({
                            title: 'course registration has been expired',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        });
                    }
                }// end -:- openModal()
            </script>
    @include('public_home.web-page-sections.social-icon-bottom')
@endsection
