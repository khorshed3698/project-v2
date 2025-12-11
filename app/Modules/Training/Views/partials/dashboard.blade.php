<?php

use Illuminate\Support\Facades\Auth;

$user_type = Auth::user()->user_type;
?>
<style>
    .small-box {
        box-shadow: 0px 1px 0px 2px rgb(0 0 0 / 10%);
        background: #f5f4f4;
        border-radius: 10px;
        padding: 15px;
    }
</style>


<?php
if ($user_type == traineeUserType() || $user_type == '5x505' || $user_type == '4x404' || $user_type == '1x101') {
    $allCourseUrl = '/course/list';
} else {
    $allCourseUrl = '/training/schedule';
}
?>

@if($user_type == traineeUserType())
<div class="col-md-12">
    <div class="pull-right">
        <button class="btn" onclick="openNidAndPassportModal()"><i class="fa fa-toggle-on"></i>
            <span>{!!trans('Training::messages.switch_user')!!}</span></button>
    </div>
</div>
@endif

<div class="col-md-12">
    <div class="row">

        <div class="form-group col-lg-3 col-md-3 col-xs-6">
            <a href="{{url($allCourseUrl)}}">
                <div class="small-box">
                    <div class="row">
                        <div class="col-md-8 col-xs-6">
                            <p class="input_ban"
                               style="color: #452A73; font-size: 34px; font-weight: 600">{{trainingDashboard()->total_course}}</p>
                            <p style="color: #452A73; font-size: 16px; font-weight: 600">{{trans('Training::messages.all_course')}}</p>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="small-box"
                                 style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #7C5CF5, #9B8BF7); border-radius: 10px; padding: 15px; height: 100%;">
                                <img src="/training/images/notebook.svg" alt="" height="50%">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="form-group col-lg-3 col-md-3 col-xs-6">

            <a href="{{url('/training/ongoing-course')}}">
                <div class="small-box">
                    <div class="row">
                        <div class="col-md-8 col-xs-6">
                            <p class="input_ban"
                               style="color: #452A73; font-size: 34px; font-weight: 600">{{trainingDashboard()->on_going}}</p>
                            <p style="color: #452A73; font-size: 16px; font-weight: 600">{{trans('Training::messages.ongoing_course')}}</p>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="small-box"
                                 style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #69D4D4, #6CD2D5); border-radius: 10px; padding: 15px; height: 100%;">
                                <img src="/training/images/process.svg" alt="" height="50%">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="form-group col-lg-3 col-md-3 col-xs-6">
            <a href="{{url('/training/upcoming-course')}}">
                <div class="small-box">
                    <div class="row">
                        <div class="col-md-8 col-xs-6">
                            <p class="input_ban"
                               style="color: #452A73; font-size: 34px; font-weight: 600">{{trainingDashboard()->upcoming_course}}</p>
                            <p style="color: #452A73; font-size: 16px; font-weight: 600">{{trans('Training::messages.upcoming_course')}}</p>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="small-box"
                                 style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #5373DF, #458DDD); border-radius: 10px; padding: 15px; height: 100%;">
                                <img src="/training/images/approval.svg" alt="" height="50%">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="form-group col-lg-3 col-md-3 col-xs-6">
            <a href="{{url('/training/completed-course')}}">
                <div class="small-box">
                    <div class="row">
                        <div class="col-md-8 col-xs-6">
                            <p class="input_ban"
                               style="color: #452A73; font-size: 34px; font-weight: 600">{{trainingDashboard()->completed_course}}</p>
                            <p style="color: #452A73; font-size: 16px; font-weight: 600">{{trans('Training::messages.closed_course')}}</p>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="small-box"
                                 style="align-items: center; justify-content: center; background-image: linear-gradient(to right, #EC6060, #FC8170); border-radius: 10px; padding: 15px; height: 100%;">
                                <img src="/training/images/list-text.svg" alt="" height="50%">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div><!--/.row-->
</div><!--/.col-md-12(Main)-->


