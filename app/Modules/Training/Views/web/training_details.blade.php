@extends('layouts.front')
<style>
    .training_course_details_sec {}

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
        color: black;
    }

    .training_course_details_sec .training_course_details_office {
        padding: 10px 0 10px 0;
    }

    .training_course_details_sec .training_course_details_office h5 {
        font-size: 15px;
        font-weight: bold;
    }

    .training_course_details_sec .training_course_details_office h5 span {
        color: black;
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

    .training_course_details_sec .training_course_details_apply,  .training_course_details_sec .training_course_details_apply:hover {
        color: #FFFFFF !important;
        background-color: #249954;
    }

    .training_course_details_apply_div a:hover{
        color: #FFFFFF !important;
        background-color: #25b669 !important;
    }

    /* width */
    .training_course_description_sec ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    .training_course_description_sec ::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    
    /* Handle */
    .training_course_description_sec ::-webkit-scrollbar-thumb {
        border-radius: 5px;
        background: #b0b0b0; 
    }

    /* Handle on hover */
    .training_course_description_sec ::-webkit-scrollbar-thumb:hover {
        background: #6d6d6d; 
    }

    /** end -:- training_course_details_sec **/

    /** start -:- training_course_coordinator_sec **/
    .training_course_coordinator_sec {}

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
    .training_course_venue_sec {}

    .training_course_venue_sec .panel {
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        background-color: #FEFEFE;
    }

    .training_course_venue_sec .panel-default {
        border-color: #5FC5E0;
    }

    .training_course_venue_sec .panel-body {}

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

    .training_course_venue_sec .training_course_center_image img {
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

    .padding_top_20 {
        padding-top: 20px;
    }

    .padding_top_10 {
        padding-top: 10px;
    }

    .padding_top_25 {
        padding-top: 25px !important;
    }

    .course_suggestion_section {
        padding-top: 15px;
    }

    .course_suggestion_section .panel {
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .course_suggestion_section .panel-default {
        border-color: #5FC5E0;
    }

    .course_suggestion_section .panel-body {
        padding: 0;
    }

    .course_suggestion_section .course_suggestion_image {
        padding-top: 15px;
        padding-left: 15px;
        padding-right: 15px;
    }

    .course_suggestion_section .course_suggestion_image img {
        width: 100%;
        height: 25vh;
        border-radius: 10px;
    }

    .course_suggestion_section .course_suggestion_panel_data {
        padding-left: 15px;
    }

    .course_suggestion_section .course_suggestion_title {
        height: 80px;
    }

    .course_suggestion_section .course_suggestion_title h2 {
        font-size: 18px;
        margin: 0;
        padding: 20px 0 0 0
    }

    .course_suggestion_section .course_suggestion_title h3 {
        font-size: 18px;
        margin: 0;
        padding: 10px 0 0 0
    }

    .course_suggestion_section .course_suggestion_seat {
        padding: 15px 0 0 0;
        /*background-color: #34A569;*/
    }

    .course_suggestion_section .course_suggestion_seat span {
        font-size: 16px;
        color: #34A569;
        font-weight: bold;
    }

    .course_suggestion_section .course_suggestion_office span {
        font-size: 16px;
    }

    .course_suggestion_section .course_suggestion_reg_date span {
        font-size: 16px;
    }

    .course_suggestion_section .course_suggestion_reg_date {
        padding-bottom: 20px;
    }

    .course_suggestion_section .course_suggestion_apply_btn {
        padding-bottom: 20px;
    }

    .course_suggestion_section .course_suggestion_apply_btn a {
        background-color: #34A569;
        color: #FFFFFF;
    }

    .course_suggestion_section .course_suggestion_apply_btn a:hover {
        background-color: #25b669;
        color: #FFFFFF;
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

    a,
    a:visited,
    a:hover,
    a:active {
        color: inherit;
    }
</style>
@section('content')
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
                                                <img src="{{ asset('uploads/training/course/' . $course->course_thumbnail_path) }}"
                                                    class="img-responsive img-rounded" alt="..."
                                                    onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                                            </figure>
                                        </div>
                                        <div class="training_course_details_data">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="#" title="SM-202404000030"
                                                        class="training_course_details_title"><u>{{ $course->course->course_title }}</u></a>
                                                    <h3 class="training_course_details_title"></h3>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="training_course_details_office">
                                                        <h5>
                                                            <a class="training_course_details_office_a" href="#">
                                                                <span>Course Venue :
                                                                </span><span><u>{{ $course->venue }}</u></span>
                                                            </a>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="training_course_details_text">
                                                        <span>Class Start:</span>
                                                        <span>{{ date('d M Y', strtotime($course->course_duration_start)) }}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="training_course_details_text">
                                                        <?php
                                                            $enroll_deadline = strtotime($course->enroll_deadline);
                                                            $current_date = strtotime(date('Y-m-d'));
                                                        ?>
                                                        @if($enroll_deadline >= $current_date)
                                                            <span>Last date of registration:</span>
                                                            <span>{{ date('d M Y', strtotime($course->enroll_deadline)) }}</span>
                                                        @else
                                                            <span class="text-danger">Registration Closed</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="padding_top_20"></div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {{-- <h5 class="training_course_details_text">
                                                        <span class="text-success"><b>Course Fee </b>:</span>
                                                        <span class="text-success input_ban"><b>{{ $course->fees_type == 'paid' ? $course->amount . ' Taka' : 'FREE' }} </b></span>
                                                    </h5> --}}
                                                </div>
                                            </div>
                                            <div class="padding_top_20"></div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="training_course_details_apply_div">
                                                        <a href="{{ $redirect_url }}" type="button"
                                                        class="btn pull-right training_course_details_apply">
                                                        @if ($course->enroll_deadline >= date('Y-m-d') && $course->status == 'upcoming' && $course->is_publish == 1 && $course->is_active == 1)
                                                            Apply Now
                                                        @else
                                                            View Details
                                                        @endif
                                                        </a>
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
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    {{-- <div class="row">
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
                                                    <h5>{{ $course->training_center }}</h5>
                                                </div>
                                            </div>
                                        </div><!--/.row-->
                                    </div><!--/.panel-body-->
                                    <div class="training_course_center_image">
                                        <img src="https://ossbscic.gov.bd/uploads/training/BSCIC_TR-65473463336a56.12695556.jpeg" alt="BSCIC_TR.png">
                                    </div><!--/.training_course_center_image-->
                                </div><!--/.panel-->
                            </div><!--./training_course_venue_sec-->
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="training_course_description_sec">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3><strong>Necessary Qualification</strong></h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p>{!! $course->necessary_qualification_experience ? $course->necessary_qualification_experience : 'Not Needed' !!}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="training_course_description_border"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3><strong>Course Goal</strong></h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! $course->objectives !!}
                                            </div>
                                        </div>
                                        <div class="training_course_description_border"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3><strong>Course Description</strong></h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! $course->course->course_description !!}
                                            </div>
                                        </div>
                                         <div class="training_course_description_border"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3><strong>Course Outline</strong></h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! $course->course_contents !!}
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
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="training_course_schedule_title">
                                                    <h3>
                                                        <strong>Training Sessions</strong>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="training_course_schedule_border"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table aria-label="Detailed Training Sessions" class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 35%;" class="text-center">Time</th>
                                                                <th style="width: 35%;" class="text-center">Day</th>
                                                                <th style="width: 10%;" class="text-center">Training Period</th>
                                                                <th style="width: 15%;" class="text-center">Seats</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($scheduleSession as $row)
                                                                <tr>
                                                                    <td style="width: 25%" class="text-center">
                                                                        <span>{{ date('h:i a', strtotime($row->session_start_time)) }}
                                                                            -
                                                                            {{ date('h:i a', strtotime($row->session_end_time)) }}</span>
                                                                    </td>
                                                                    <td style="width: 25%" class="text-center">
                                                                        <span>{{ $row->session_days }}</span>
                                                                    </td>
                                                                    <td style="width: 25%;" class="text-center input_ban">
                                                                        {{ $course->duration }}
                                                                        {{ $course->duration_unit ? $course->duration_unit : '' }}
                                                                    </td>
                                                                    <td style="width: 25%;" class="text-center input_ban">
                                                                        {{ $row->seat_capacity == 0 ? 'Unlimited' : $row->seat_capacity }}
                                                                    </td>
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
                        <h2>More training on {{ $course->category->category_name }}</h2>
                    </div>
                </div>
            </div><!--./row-->

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
                    <div id="loadUpcomingCourseInDistrict">
                        @foreach ($courseList as $row)
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <div class="course_suggestion_section">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="course_suggestion_image">
                                                <img src="{{ asset('/uploads/training/course/' . $row->course_thumbnail_path) }}"
                                                    class="img-responsive" alt="{{ $row->course->course_title }}"
                                                    onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`"
                                                    title="{{ $row->course->course_title }}">
                                            </div>
                                            <div class="course_suggestion_panel_data">
                                                <div class="course_suggestion_title">
                                                    <h2 title="SM-202404000030"><strong>{{ $row->course->course_title }}</strong></h2>
                                                </div>
                                                {{-- <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="course_suggestion_seat">
                                                            <span>আসন :</span>
                                                            <span>অনির্ধারিত</span>
                                                            <span></span>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="course_suggestion_office">
                                                            <span class="training_course_details_title">Course Venue :</span>
                                                            <span>{{ mb_substr($row->venue, 0, 40, 'UTF-8') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="course_suggestion_reg_date" style="padding-bottom: 5px;">
                                                            <span>Class Start Date:</span>
                                                            <span>{{ date('d F Y', strtotime($row->course_duration_start)) }}</span>
                                                        </div>
                                                    </div>

                                                    <?php
                                                        $enroll_deadline = strtotime($row->enroll_deadline);
                                                        $current_date = strtotime(date('Y-m-d'));
                                                    ?>
                                                    <div class="col-md-12">
                                                        <div class="course_suggestion_reg_date">
                                                            @if($enroll_deadline >= $current_date)
                                                            <span>Last date of registration:</span>
                                                            <span>{{ $row->enroll_deadline }}</span>
                                                            @else
                                                            <span class="text-danger">Registration Closed</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($row->enroll_deadline >= date('Y-m-d') && $row->status == 'upcoming' && $row->is_publish == 1 && $row->is_active == 1)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="course_suggestion_apply_btn text-center">
                                                            <a href="{{ $redirect_url }}" class="btn">Apply now</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="course_suggestion_apply_btn text-center" style="height: 53px;">
                                                            <a href="{{ $redirect_url }}" class="btn">View Details</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div><!--./course_suggestion_panel_data-->
                                        </div><!--./panel-body-->
                                    </div><!--./panel-->
                                </div><!--./course_suggestion_section-->
                            </div><!--./col-->
                        @endforeach
                    </div>
                </div>
            </div><!--./row-->

            {{-- <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="load_upcoming_course_in_office_btn">
                        <button id="loadUpcomingCourseInDistrictBtn" onclick="loadUpComingCourseDistrict()"
                            style="display: none;">See More &nbsp;<i class="fa fa-arrow-circle-down"></i></button>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="other_location_program_title">
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
                        <button id="loadUpcomingCourseInOfficeBtn" onclick="loadUpcomingCourseInOffice()"
                            style="display: none;">আরো দেখুন &nbsp;<i class="fa fa-arrow-circle-down"></i></button>
                    </div>
                </div>
            </div>
            <div class="row" id="loadUpcomingCourseCategory"></div> --}}
            <!-- end -:- Suggestion Course -->
        </div><!-- ./container (main)-->
    </section>
@endsection
