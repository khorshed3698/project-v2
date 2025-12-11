<style>
    .training_card_grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Adjust minmax values as needed */
        gap: 20px; /* Adjust the gap between grid items */
    }
    .training_card_grid .panel {
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
    .training_card_grid .panel-default {
        border-color: #5FC5E0;
    }
    .training_card_grid .panel-body {
        padding: 0;
    }
    .training_card_grid .training_card_top_image {
        padding-top: 15px;
        padding-left: 15px;
        padding-right: 15px;
    }
    .training_card_grid .training_card_top_image img {
        width: 100%;
        height: 25vh;
        border-radius: 10px;
    }
    .training_card_grid .training_card_panel_data {
        padding-left: 15px;
    }
    .training_card_grid .course_suggestion_title {
        /*height: 80px;*/
    }
    .training_card_grid .training_card_title h2 {
        font-size: 18px;
        margin: 0;
        padding: 20px 0 0 0
    }
    .training_card_grid .training_card_title h3 {
        font-size: 18px;
        margin: 0;
        padding: 10px 0 0 0;
        overflow: hidden;
        white-space: normal;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
        height: 3em;
        line-height: 1.5em;
    }
    .training_card_grid .training_card_seat {
        padding: 15px 0 0 0;
    }
    .training_card_grid .training_card_seat span {
        font-size: 16px;
        color: #34A569;
        font-weight: bold;
    }
    .training_card_grid .training_card_office span {
        font-size: 16px;
    }
    .training_card_grid .training_card_reg_date {
        padding-bottom: 20px;
    }
    .training_card_grid .training_card_reg_date span {
        font-size: 16px;
    }
    .training_card_grid .training_card_apply_btn {
        padding-bottom: 20px;
    }
    .training_card_grid .training_card_apply_btn a {
        background-color: #34A569;
        color: #FFFFFF;
    }
    .training_card_grid .training_card_apply_btn a:hover {
        color: #FFFFFF;
    }
</style>
@foreach($upcomingCourseData as $course)
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="training_card_top_image">
                <img src="{{ asset('/uploads/training/'.$course->training_center_image) }}"
                     class="img-responsive" alt="{{ $course->course_title }}"
                     onerror="this.src=`{{asset('/assets/images/no-image.png')}}`" title="{{ $course->course_title }}"/>
            </div>
            <div class="training_card_panel_data">
                <div class="training_card_title">
                    <h2>প্রশিক্ষণের স্থান</h2>
                    <h3 title="{{ !empty($course->master_tracking_no)?$course->master_tracking_no:'' }}">{{ $course->venue }}</h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="training_card_seat">
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
                        <div class="training_card_office">
                            <span>অফিসের নাম :</span>
                            <span class="training_card_office_title">{{ mb_substr($course->district_office_name,0,45) }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="training_card_reg_date" style="padding-bottom: 0;">
                            <span>ক্লাস শুরুর তারিখ:</span>
                            @php
                                $course_duration_start = date("d M", strtotime($course->course_duration_start));
                                $course_duration_start = \App\Libraries\CommonFunction::convertDate2Bangla($course_duration_start);
                            @endphp
                            <span>{{ $course_duration_start }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="training_card_reg_date">
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
                        <div class="training_card_apply_btn">
                            <a href="{{ url('/training-details/'.\App\Libraries\Encryption::encodeId($course->tr_schedule_master_id)) }}"
                               class="btn">{{ trans('Training::messages.apply') }}</a>
                        </div>
                    </div>
                </div>
            </div><!--./course_suggestion_panel_data-->
        </div><!--./panel-body-->
    </div><!--./panel-->
@endforeach