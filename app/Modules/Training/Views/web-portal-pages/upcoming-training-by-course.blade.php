<style>
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
        color: #FFFFFF;
    }
    .course_suggestion_section .training_course_details_title{
        overflow: hidden;
        white-space: normal;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
        height: 3em;
        line-height: 1.5em;
    }
</style>
@foreach($upcomingCourseData as $course)
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="course_suggestion_section">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="course_suggestion_image">
                        <img src="{{ asset('/uploads/training/'.$course->training_center_image) }}"
                             class="img-responsive" alt="{{ $course->course_title }}"
                             onerror="this.src=`{{asset('/assets/images/no-image.png')}}`" title="{{ $course->course_title }}"/>
                    </div>
                    <div class="course_suggestion_panel_data">
                        <div class="course_suggestion_title">
                            <h2>প্রশিক্ষণের স্থান</h2>
                            <h3 title="{{ !empty($course->master_tracking_no)?$course->master_tracking_no:'' }}">{{ $course->venue }}</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="course_suggestion_seat">
                                    <span>আসন :</span>
                                    @if(!empty($course->total_seat_capacity))
                                        <span class="input_ban">{{ $course->total_seat_capacity }}</span>
                                        <span>টি</span>
                                    @else
                                        <span>অনির্ধারিত</span>
                                        <span></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="course_suggestion_office">
{{--                                    <span></span>--}}
                                    <span class="training_course_details_title">অফিসের নাম : {{ $course->district_office_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="course_suggestion_reg_date" style="padding-bottom: 0;">
                                    <span>ক্লাস শুরুর তারিখ:</span>
                                    @php
                                        $course_duration_start = date("d M", strtotime($course->course_duration_start));
                                        $course_duration_start = \App\Libraries\CommonFunction::convertDate2Bangla($course_duration_start);
                                    @endphp
                                    <span>{{ $course_duration_start }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="course_suggestion_reg_date">
                                    <span>{{ trans('Training::messages.reg_ends') }}:</span>
                                    @php
                                        $enroll_deadline = date("d M", strtotime($course->enroll_deadline));
                                        $enroll_deadline = \App\Libraries\CommonFunction::convertDate2Bangla($enroll_deadline);
                                    @endphp
                                    <span>{{ $enroll_deadline }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="course_suggestion_apply_btn">
                                    <a href="{{ url('/training-details/'.\App\Libraries\Encryption::encodeId($course->tr_schedule_master_id)) }}"
                                       class="btn">{{ trans('Training::messages.apply') }}</a>
                                </div>
                            </div>
                        </div>
                    </div><!--./course_suggestion_panel_data-->
                </div><!--./panel-body-->
            </div><!--./panel-->
        </div><!--./course_suggestion_section-->
    </div><!--./col-->
@endforeach

