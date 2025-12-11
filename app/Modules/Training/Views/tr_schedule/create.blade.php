<?php
if (!ACL::getAccsessRight('Training-Desk', '-A-')) {
    die('You have no access right! Please contact system administration for more information');
}
?>
@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')
{{--Datepicker css--}}
<link rel="stylesheet" href="{{ asset("vendor/datepicker/datepicker.min.css") }}">
    <style>
        /*.bootstrap-datetimepicker-widget{*/
        /*    position: relative !important;*/
        /*    top:0 !important;*/
        /*}*/
        .pe-none {
            pointer-events: none;
        }

        .course_image_thumbnail {
            height: 150px;
            width: 150px;
        }

        ul.image_checkbox_design {
            list-style-type: none;
        }

        ul.image_checkbox_design li {
            display: inline-block;
        }

        ul.image_checkbox_design li input[type="checkbox"][id^="course_thumbnail_base64"] {
            display: none;
        }

        ul.image_checkbox_design li label {
            border: 1px solid #fff;
            padding: 10px;
            display: block;
            position: relative;
            margin: 10px;
            cursor: pointer;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        ul.image_checkbox_design li label::before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -5px;
            left: -5px;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        ul.image_checkbox_design li label img {
            height: 100px;
            width: 100px;
            transition-duration: 0.2s;
            transform-origin: 50% 50%;
        }

        ul.image_checkbox_design li :checked+label {
            border-color: #ddd;
        }

        ul.image_checkbox_design li :checked+label::before {
            content: "✓";
            background-color: grey;
            transform: scale(1);
        }

        ul.image_checkbox_design li :checked+label img {
            transform: scale(0.9);
            box-shadow: 0 0 5px #333;
            z-index: -1;
        }
        .form-horizontal .control-label {
            margin-bottom: 0;
            padding-top: 7px;
            text-align: left !important;
        }
        .overflow-scroll{
            overflow: visible;
        }

        @media (max-width: 992px) {
            #duration_unit{
                margin-top: 10px;
            }
            .mt-10{
                margin-top: 10px;
            }
            .mt-20{
                margin-top: 40px;
            }
            .overflow-scroll{
                overflow: scroll; 
            }

        }
        @media (max-width: 768px) {
            .select2 {
                width: 70px !important;
            }
            .bootstrap-datetimepicker-widget {
                width: 180px !important;
            }
            .date{
                position: static;
            }
        }
    </style>
    {{-- start application form with wizard --}}
    @include('partials.messages')
    <div class="col-lg-12">
        
        <div class="panel panel-primary">
            <div class="panel-heading" style="padding:13px 10px;">
                <b>Create Training Schedule</b>
            </div>

            <div class="panel-body">
                {!! Form::open([
                    'url' => '/training/store-schedule',
                    'method' => 'post',
                    'class' => 'form-horizontal smart-form',
                    'id' => 'currency-form',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                    'role' => 'form',
                ]) !!}

                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('course_id', 'Course Name', ['class' => 'col-md-2 required-star control-label']) !!}
                                <div class="col-md-10 {{ $errors->has('course_id') ? 'has-error' : '' }}">
                                    {!! Form::select('course_id', $trCourse, '', [
                                        'class' => 'form-control required imput-md',
                                        'id' => 'course_id','onchange' => 'getCourseImagePath(value)'
                                    ]) !!}
                                    {!! $errors->first('course_id', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('duration', 'Course Duration', ['class' => 'col-md-2 control-label required-star']) !!}
                                <div class="col-md-5  {{ $errors->has('duration') ? 'has-error' : '' }}">
                                    {!! Form::select(
                                        'duration',
                                        ['' => 'Select one'] + array_combine(range(1, 31), range(1, 31)),
                                        '',
                                        ['class' => 'form-control input-md required', 'id' => 'duration']
                                    ) !!}
                                    
                                    {!! $errors->first('duration', '<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-5  {{ $errors->has('duration_unit') ? 'has-error' : '' }}">
                                    {!! Form::select(
                                        'duration_unit',
                                        [
                                            '' => 'Select one',
                                            'day' => 'Day',
                                            'month' => 'Month',
                                        ],
                                        '',
                                        ['class' => 'form-control input-md required', 'id' => 'duration_unit']
                                    ) !!}
                                    {!! $errors->first('duration_unit', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('category_id', 'Course Category', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                    {!! Form::select(
                                        '',
                                        $trCategory,
                                        null,
                                        ['class' => 'form-control input-md', 'id' => 'category_id', 'disabled' => 'disabled']
                                    ) !!}
                                    {!! Form::hidden(
                                        'category_id',
                                        null,
                                        ['class' => 'form-control input-md', 'id' => 'category_id_main', 'readonly' => 'readonly']
                                    ) !!}
                                    {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            
                            <div class="col-md-6 mt-10">
                                {!! Form::label('enroll_deadline', 'Enrolment End Date', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('enroll_deadline') ? 'has-error' : '' }}">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('enroll_deadline', '', [
                                            'class' => 'form-control input_ban required engOnly',
                                            'id' => 'enroll_deadline',
                                        ]) !!}
                                        <span class="input-group-addon"> 
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('enroll_deadline', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            {{-- <div class="col-md-6 {{ $errors->has('course_duration_start') ? 'has-error' : '' }}">
                                {!! Form::label('course_duration_start', 'Course Start Date', [
                                    'class' => 'col-md-4 control-label required-star',
                                ]) !!}
                                <div class="col-md-8">
                                    {!! Form::text('course_duration_start', '', [
                                        'class' => 'form-control bnEng input_ban required',
                                        'id' => 'course_duration_start',
                                        'readonly' => 'readonly',
                                        'style' => 'background:white',
                                    ]) !!}
                                    {!! $errors->first('course_duration_start', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                {!! Form::label('course_duration_start', 'Course Start Date', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('course_duration_start') ? 'has-error' : '' }}">
                                    <div class="input-group date" id="course_duration_start">
                                        {!! Form::text('course_duration_start', '', [
                                            'class' => 'form-control input_ban required engOnly'
                                        ]) !!}
                                        <span class="input-group-addon"> 
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('course_duration_start', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('course_duration_end', 'Course End Date', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('course_duration_end') ? 'has-error' : '' }}">
                                    <div class="input-group date" id="course_duration_end">
                                        {!! Form::text('course_duration_end', '', [
                                            'class' => 'form-control input_ban required engOnly'
                                        ]) !!}
                                        <span class="input-group-addon"> 
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('course_duration_end', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6" style="height: 35px; margin-bottom: 30px;">
                                {!! Form::label('fees_type', 'Course Fee Type', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('fees_type') ? 'has-error' : '' }}">
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('fees_type', 'paid', true, [
                                            'class' => 'form-check-input fees_type',
                                        ]) !!}
                                        Paid
                                    </label>
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('fees_type', 'free', false, [
                                            'class' => 'form-check-input fees_type',
                                        ]) !!}
                                        Free
                                    </label>
                                    {!! $errors->first('fees_type', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6"  id="amountDiv">
                                {!! Form::label('amount', 'Course Fee (Second Payment)', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8 {{ $errors->has('amount') ? 'has-error' : '' }}">
                                    {!! Form::text('amount', '', ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('no_of_class', 'Total Class', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8  {{ $errors->has('no_of_class') ? 'has-error' : '' }}">
                                    {!! Form::text('no_of_class', '', [
                                        'class' => 'form-control input-md required input_ban onlyNumber engOnly',
                                        'id' => 'no_of_class',
                                        'placeholder' => 'Totall Class',
                                    ]) !!}
                                    {!! $errors->first('no_of_class', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('total_hours', 'Total Hour', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('total_hours') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'total_hours', '', [
                                        'class' => 'form-control input-md required input_ban onlyNumber engOnly',
                                        'id' => 'total_hours',
                                        'placeholder' => 'Total Hour',
                                    ]) !!}
                                    {!! $errors->first('total_hours', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('venue', 'Venue', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('venue') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'venue', null, [
                                        'class' => 'form-control input-md required bnEng',
                                        'id' => 'venue',
                                        'placeholder' => 'Venue',
                                    ]) !!}
                                    {!! $errors->first('venue', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('batch_id', 'Batch Name', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('batch_id') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'batch_id', null, [
                                        'class' => 'form-control input-md required bnEng ui-autocomplete-input',
                                        'id' => 'batch_id',
                                        'placeholder' => 'Batch Name. e.g. WB-01',
                                        'autocomplete' => 'off','onchange' => 'checkBatchName(this.value, course_id.value)'
                                    ]) !!}
                                    <small class="text-danger">Batch Name should be unique and without space.</small>
                                    {!! $errors->first('batch_id', '<span class="help-block">:message</span>') !!}
                                    <span class="help-block text-danger" id="tag_error" style="color: red"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('training_office', "Course's Office", ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8  {{ $errors->has('training_office') ? 'has-error' : '' }}">
                                    {!! Form::text(
                                        'training_office',
                                        null,
                                        ['class' => 'form-control input-md required', 'id' => 'training_office',]
                                    ) !!}
                                    {!! $errors->first('training_office', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('training_center', 'Course Center', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8  {{ $errors->has('training_center') ? 'has-error' : '' }}">
                                    {!! Form::text(
                                        'training_center',
                                        null,
                                        ['class' => 'form-control input-md required', 'id' => 'training_center']
                                    ) !!}
                                    {!! $errors->first('training_center', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{ $errors->has('course_evaluation') ? 'has-error' : '' }}" style="height: 34px; margin-bottom: 30px;">
                                {!! Form::label('course_evaluation', 'Course Marking', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8">
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('course_evaluation', 'yes', false, ['class' => 'form-check-input course_evaluation']) !!}
                                        Yes
                                    </label>
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('course_evaluation', 'no', true, ['class' => 'form-check-input course_evaluation']) !!}
                                        No
                                    </label>
                                    {!! $errors->first('course_evaluation', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 hidden {{ $errors->has('pass_marks') ? 'has-error' : '' }}" id="pass_marks_div">
                                {!! Form::label('pass_marks', 'Total Marks', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8">
                                    {!! Form::input('text', 'pass_marks', '100', [
                                        'class' => 'form-control input-md required bnEng input_ban onlyNumber',
                                        'id' => 'pass_marks',
                                        'readonly' => 'readonly',
                                        'placeholder' => 'Please Enter your pass marks',
                                    ]) !!}
                                    {!! $errors->first('pass_marks', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="overflow-scroll">
                            <table aria-label="Detailed Report Data Table" id="courseDetailTable" class="table table-bordered table-striped" cellspacing="0" width="100%" style="z-index: 999;">
                                <thead style="background-color: #3379b77e">
                                    <tr>
                                        <th class="text-center">Session Name</th>
                                        <th class="text-center">Session Time</th>
                                        <th class="text-center width-20">Day</th>
                                        <th class="text-center" width="15%">Application</th>
                                        <th class="text-center" width="15%">Total Applicant </th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="courseDetailRow" data-number="0">
                                        <td>
                                            {!! Form::text('session_name[0]', null, [
                                                'class' => 'form-control input-md session_name text-center input_ban required engOnly', 'required' => 'required'
                                            ]) !!}
                                        </td>
                                        <td>
                                            <div class="row" style="padding-left: 5px; width: 190px;">
                                                <div class="timepicker input-group date"
                                                    style="float: left; margin-right: 2px; width: 48%">
                                                    {!! Form::text('session_start_time[0]', null, [
                                                        'class' => 'form-control bnEng required',
                                                        'placeholder' => '-- -- --', 'required' => 'required'
                                                    ]) !!}
                                                    <span class="input-group-addon date">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                </div>
                                                <div class="timepicker input-group date" style="width: 50%">
                                                    {!! Form::text('session_end_time[0]', null, [
                                                        'class' => 'form-control bnEng required',
                                                        'placeholder' => '-- -- --', 'required' => 'required'
                                                    ]) !!}
                                                    <span class="input-group-addon date">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="width-20" style="width: 20%">
                                            {!! Form::select(
                                                'day[0][]',
                                                [
                                                    'Saturday' => 'Saturday',
                                                    'Sunday' => 'Sunday',
                                                    'Monday' => 'Monday',
                                                    'Tuesday' => 'Tuesday',
                                                    'Wednesday' => 'Wednesday',
                                                    'Thursday' => 'Thursday',
                                                    'Friday' => 'Friday',
                                                ],
                                                null,
                                                ['class' => 'form-control input-md days', 'multiple' => 'multiple', 'id' => 'select2_day', 'required' => 'required']
                                            ) !!}
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    {!! Form::checkbox('applicant_limit[0][]', 'limit', true, ['class' => 'form-check-input applicant_limit checked']) !!}
                                                    Limited
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    {!! Form::checkbox('applicant_limit[0][]', 'unlimit', false, ['class' => 'form-check-input applicant_limit']) !!}
                                                    Unlimited
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            {!! Form::text('seat_capacity[0]', null, [
                                                'class' => 'form-control input-md seat_capacity onlyNumber text-center input_ban required engOnly',
                                                'placeholder' => '0000',
                                            ]) !!}
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                onclick="addTableRow('courseDetailTable', 'courseDetailRow');"><i
                                                    class="fa fa-plus"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('necessary_qualification_experience', 'Necessary Qualification', [
                                    'class' => 'col-md-2 control-label required-star',
                                ]) !!}
                                <div class="col-md-10  {{ $errors->has('necessary_qualification_experience') ? 'has-error' : '' }}">
                                    {!! Form::textarea('necessary_qualification_experience', null, [
                                        'class' => 'form-control input-xs bnEng',
                                        'placeholder' => 'Necessary Qualification',
                                        'id' => 'necessary_qualification_experience'
                                    ]) !!}
                                    {!! $errors->first('necessary_qualification_experience', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('objectives', 'Course Goal', ['class' => 'col-md-2 control-label required-star']) !!}
                                <div class="col-md-10  {{ $errors->has('objectives') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'objectives', null, [
                                        'class' => 'form-control bnEng input-md required',
                                        'placeholder' => 'Course Goal',
                                        'id' => 'objectives',
                                    ]) !!}
                                    {!! $errors->first('objectives', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('course_contents', 'Course Outline', ['class' => 'col-md-2 control-label required-star']) !!}
                                <div class="col-md-10  {{ $errors->has('course_contents') ? 'has-error' : '' }}">
                                    {!! Form::textarea('course_contents', null, [
                                        'class' => 'form-control input-xs bnEng',
                                        'placeholder' => 'Course Outline',
                                        'id' => 'course_contents',
                                        'cols' => '50',
                                        'rows' => '10',
                                    ]) !!}
                                    {!! $errors->first('course_contents', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    {!! Form::label('course_thumbnail_path', 'Course Thumbnail', [
                                        'class' => 'text-left control-label required-star',
                                    ]) !!}
                                </div>
                                <ul class="image_checkbox_design">
                                    <li id="course_thumbnail_base64_li">
                                        {!! Form::checkbox('course_thumbnail_base64', '1', null, [
                                            'class' => 'course-checkbox',
                                            'id' => 'course_thumbnail_base64',
                                        ]) !!}
                                        <label for="course_thumbnail_base64">
                                            <img src="{{ asset('/assets/images/photo_default.png') }}" alt="photo_default.png"
                                                class="img-responsive img-thumbnail course_image_thumbnail"
                                                id="course_thumbnail_preview"
                                                onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                                        </label>
                                    </li>
                                    <li id="course_thumbnail_base642_li" style="display: none;">
                                        {!! Form::checkbox('course_thumbnail_base642', '1', null, [
                                            'class' => 'course-checkbox',
                                            'id' => 'course_thumbnail_base642',
                                        ]) !!}
                                        <label for="course_thumbnail_base642">
                                            <img src="{{ asset('/assets/images/photo_default.png') }}" alt="photo_default.png"
                                                class="img-responsive img-thumbnail course_image_thumbnail"
                                                id="course_thumbnail_preview2"
                                                onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                                        </label>
                                    </li>
                                    <li id="course_thumbnail_base643_li" style="display: none;">
                                        {!! Form::checkbox('course_thumbnail_base643', '1', null, [
                                            'class' => 'course-checkbox',
                                            'id' => 'course_thumbnail_base643',
                                        ]) !!}
                                        <label for="course_thumbnail_base643">
                                            <img src="{{ asset('/assets/images/photo_default.png') }}" alt="photo_default.png"
                                                class="img-responsive img-thumbnail course_image_thumbnail"
                                                id="course_thumbnail_preview3"
                                                onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                                        </label>
                                    </li>
                                </ul>
                                {!! $errors->first('course_thumbnail_base64', '<span class="help-block col-md-10 col-md-offset-2" style="color: #a94442; padding-left: 10px;">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('is_active', 'Active Status: ', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('is_active') ? 'has-error' : '' }}">
                                    <label>{!! Form::radio('is_active', '1', [
                                        'class' => 'required',
                                        'id' => 'yes',
                                    ]) !!} Active</label>
                                    <label>{!! Form::radio('is_active', '0', [
                                        'class' => 'required',
                                        'id' => 'no',
                                    ]) !!} Inactive</label>
    
                                    {!! $errors->first('is_active', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('is_featured', 'Slider Featured: ', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('is_featured') ? 'has-error' : '' }}">
                                    <label>{!! Form::radio('is_featured', '1', [
                                        'class' => 'required',
                                        'id' => 'yes',
                                    ]) !!} Active</label>
                                    <label>{!! Form::radio('is_featured', '0', [
                                        'class' => 'required',
                                        'id' => 'no',
                                    ]) !!} Inactive</label>
    
                                    {!! $errors->first('is_featured', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{ $errors->has('status') ? 'has-error' : '' }}">
                                {!! Form::label('status', 'Course Status', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select(
                                        'status',
                                        ['upcoming' => 'Upcoming', 'ongoing' => 'Running', 'completed' => 'Completed'],
                                        null,
                                        ['class' => 'form-control input-md', 'id' => 'status']
                                    ) !!}
                                    {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <a href="{{ url('/training/schedule/list') }}">
                                {!! Form::button('<i class="fa fa-times"></i> Close', ['type' => 'button', 'class' => 'btn btn-default']) !!}
                            </a>
                        </div>

                        <div class="col-md-6">
                            <button type="submit" id="submit" class="btn btn-primary pull-right">
                                <i class="fa fa-chevron-circle-right"></i> Save</button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
    <script src="{{ asset('assets/plugins/select2.min.js') }}"></script>
    <script src="{{ asset("vendor/datepicker/datepicker.min.js") }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    <script>

        $(document).ready(function () {
            // Trigger change event when the page loads to ensure correct initial state
            $('.fees_type:checked').change();
        });
    
        $(document).on('change', '.fees_type', function () {
            // Display or hide amountDiv based on the selected value
            if ($(this).val() === 'paid') {
                $("#amountDiv").removeClass("d-none");
            } else {
                $("#amountDiv").addClass("d-none");
            }
        });
        $(document).on('change', '.applicant_limit', function () {
            var val = $(this).val();
            var inputField = $(this).closest("tr").find("input.seat_capacity");
            if (val == 'unlimit') {
                inputField.addClass('hidden');
            } else {
                inputField.removeClass('hidden')
            }
            $(this).closest("tr").find(".applicant_limit").prop('checked', false);
            $(this).prop('checked', true);
        })
        $(document).on('change', '.course_evaluation', function () {
            var val = $(this).val();
            if (val == 'yes') {
                $('#pass_marks_div').removeClass('hidden')
            } else {
                $('#pass_marks_div').addClass('hidden');
            }
            $(this).closest("tr").find(".applicant_limit").prop('checked', false);
            $(this).prop('checked', true);
        });
        $(document).ready(function() {
            $("#select2_day").select2();
            $("#speaker_id").select2();
            $('.datepicker').datetimepicker({
                format: 'yyyy-MM-DD',
            });

            $('#course_duration_start').datetimepicker({
            format: 'yyyy-MM-DD',
            }).on('dp.change', function(e) {
                $('#course_duration_end').data("DateTimePicker").minDate(e.date);
            });

            $('#course_duration_end').datetimepicker({
                format: 'yyyy-MM-DD',
            }).on('dp.change', function(e) {
                $('#start_date').data("DateTimePicker").maxDate(e.date);
            });
            
            $(".timepicker").datetimepicker({
                format: 'hh:mm A',
            });
            $('.course_evaluation').click(function(){
                var conditionMet = $(this).val() === 'yes';
                if(conditionMet) {
                    $('#pass_marks').removeAttr('readonly');
                }
                else{
                    $('#pass_marks').attr('readonly', 'readonly');
                }
            });
        });

        // Add table Row script
        function addTableRow(tableID, templateRow) {
            $('.days').select2('destroy');


            var x = document.getElementById(templateRow).cloneNode(true);
            x.id = "";
            x.style.display = "";
            var table = document.getElementById(tableID);
            var rowCount = $('#' + tableID).find('tr').length;

            //var rowCount = table.rows.length;
            //Increment id
            var rowCo = rowCount + 2;
            var rowCoo = rowCount + 1;
            var nameRo = rowCount;
            var idText = 'courseDetailRow' + rowCoo;
            x.id = idText;
            $("#" + tableID).append(x);
            //get select box elements
            var attrSel = $("#" + tableID).find('#' + idText).find('select');


            //edited by ishrat to solve select box id auto increment related bug
            for (var i = 0; i < attrSel.length; i++) {
                var nameAtt = attrSel[i].name;
                var selectId = attrSel[i].id;
                var repText = nameAtt.replace('[0]', '[' + nameRo + ']'); //increment all array element name
                var ret = selectId.replace('_1', '');
                var repTextId = ret + '_' + rowCoo;
                attrSel[i].id = repTextId;
                attrSel[i].name = repText;
            }
            attrSel.val(''); //value reset
            // end of  solving issue related select box id auto increment related bug by ishrat

            //get input elements
            var attrInput = $("#" + tableID).find('#' + idText).find('input[type=text]');
            for (var i = 0; i < attrInput.length; i++) {
                var nameAtt = attrInput[i].name;
                var inputId = attrInput[i].id;
                var repText = nameAtt.replace('[0]', '[' + nameRo + ']'); //increment all array element name
                var ret = inputId.replace('_1', '');
                var repTextId = ret + '_' + rowCoo;
                attrInput[i].id = repTextId;
                attrInput[i].name = repText;
            }
            attrInput.val(''); //value reset

            //get input elements
            var attrSpan = $("#" + tableID).find('#' + idText).find('span');
            for (var i = 0; i < attrSpan.length; i++) {
                var spanId = attrSpan[i].id;
                var ret = spanId.replace('1', '');
                var repTextId = rowCo;
                attrSpan[i].id = repTextId;
            }
            attrSpan.val(''); //value reset

            //edited by ishrat to solve textarea id auto increment related bug
            //get textarea elements
            var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
            for (var i = 0; i < attrTextarea.length; i++) {
                var nameAtt = attrTextarea[i].name;
                //increment all array element name
                var repText = nameAtt.replace('[0]', '[' + nameRo + ']');
                attrTextarea[i].name = repText;
                $('#' + idText).find('.readonlyClass').prop('readonly', true);
            }
            attrTextarea.val(''); //value reset


            // For checkbox
            var attrRadio = $("#" + tableID).find('#' + idText).find(':checkbox');
            for (var i = 0; i < attrRadio.length; i++) {
                var nameAtt = attrRadio[i].name;
                var repText = nameAtt.replace('[0]', '[' + nameRo + ']'); //increment all array element name
                attrRadio[i].name = repText;
            }

            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            $('#' + tableID).find('tr').last().attr('data-number', rowCoo);


            $('.days').select2();
            $("#" + tableID).find('#' + idText).find(attrSel).val('');

            $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
                //period decimal
                if ((e.which >= 48 && e.which <= 57)
                    //numpad decimal
                    || (e.which >= 96 && e.which <= 105)
                    // Allow: backspace, delete, tab, escape, enter and .
                    || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                    // Allow: Ctrl+A
                    || (e.keyCode == 65 && e.ctrlKey === true)
                    // Allow: Ctrl+C
                    || (e.keyCode == 67 && e.ctrlKey === true)
                    // Allow: Ctrl+V
                    || (e.keyCode == 86 && e.ctrlKey === true)
                    // Allow: Ctrl+X
                    || (e.keyCode == 88 && e.ctrlKey === true)
                    // Allow: home, end, left, right
                    || (e.keyCode >= 35 && e.keyCode <= 39)) {
                    var $this = $(this);
                    setTimeout(function () {
                        $this.val($this.val().replace(/[^0-9.]/g, ''));
                    }, 4);
                    var thisVal = $(this).val();
                    if (thisVal.indexOf(".") != -1 && e.key == '.') {
                        return false;
                    }
                    $(this).removeClass('error');
                    return true;
                } else {
                    $(this).addClass('error');
                    return false;
                }
            }).on('paste', function (e) {
                var $this = $(this);
                setTimeout(function () {
                    $this.val($this.val().replace(/[^.0-9]/g, ''));
                }, 4);
            });
            // Datepicker initialize of the new row
            $("#" + tableID).find('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MM-YYYY',
                extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
                maxDate: 'now',
                minDate: '01/01/1905',
            });


            // Datepicker initialize of the new row
            $("#" + tableID).find('.YearPicker').datetimepicker({
                viewMode: 'years',
                format: 'YYYY',
                extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
                // maxDate: 'now',
                minDate: '01/01/1905'
            });

            $("#" + tableID).find(".timepicker").datetimepicker({
                format: 'hh:mm A',
            });

        } // end of addTableRow() function


        // Remove Table row script
        function removeTableRow(tableID, removeNum) {
            $('#' + tableID).find('#' + removeNum).remove();
            let current_total_row = $('#' + tableID).find('tbody tr').length;
            if (current_total_row <= 3) {
                const tableFooter = document.getElementById('autoFooter');
                if (tableFooter) {
                    tableFooter.remove();
                }
            }
        }

        function checkBatchName(value, course_id){
            $.ajax({
                type: "GET",
                url: "{{ url('training/schedule/check-batch-name') }}",
                data: {
                    batch_id: value,
                    course_id: course_id,
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        $('#tag_error').text(response.messages);
                        $('#batch_id').val('');

                    } else {
                        $('#tag_error').text('');
                    }
                },
                error: function (xhr, status, error) {
                    alert("An error occurred during the AJAX request.");
                }
            });// end ajax
        }

        function getCourseImagePath(id) {
            let course_id = id;
            let submitBtn = document.getElementById("submit");
            let submitText = submitBtn.innerText;
            let loadingIcon = '<i class="fa fa-spinner fa-spin"></i>';
            if (!course_id !== '') {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/training/category-list/get-image-and-category') }}",
                    data: {
                        course_id: course_id,
                    },
                    // beforeSend: function () {
                    //     submitBtn.innerHTML = loadingIcon + ' ' + submitText;
                    //     $("#submit").prop("disabled", true);
                    // },
                    success: function (response) {
                        if (response.responseCode == 1) {
                            if (response.img_value != '') {
                                $('#course_thumbnail_base64_li').show();
                                $('#course_thumbnail_preview').attr('src', response.img_path);
                                $('#course_thumbnail_base64').val(response.img_value);
                            } else {
                                $('#course_thumbnail_base64').val('');
                                $('#course_thumbnail_base64_li').hide();
                            }
                            if (response.img_value2 != '') {
                                $('#course_thumbnail_base642_li').show();
                                $('#course_thumbnail_preview2').attr('src', response.img_path2);
                                $('#course_thumbnail_base642').val(response.img_value2);
                            } else {
                                $('#course_thumbnail_base642').val('');
                                $('#course_thumbnail_base642_li').hide();
                            }
                            if (response.img_value3 != '') {
                                $('#course_thumbnail_base643_li').show();
                                $('#course_thumbnail_preview3').attr('src', response.img_path3);
                                $('#course_thumbnail_base643').val(response.img_value3);
                            } else {
                                $('#course_thumbnail_base643').val('');
                                $('#course_thumbnail_base643_li').hide();
                            }
                            $('#category_id').val(response.category_id);
                            $('#category_id_main').val(response.category_id);
                        } else {
                            $('#course_thumbnail_preview').attr('src', response.img_path);
                            $('#course_thumbnail_base64').val('');
                            $('#course_thumbnail_preview2').attr('src', response.img_path);
                            $('#course_thumbnail_base642').val('');
                            $('#course_thumbnail_preview3').attr('src', response.img_path);
                            $('#course_thumbnail_base643').val('');
                            $('#course_thumbnail_base642_li').hide();
                            $('#course_thumbnail_base643_li').hide();
                            $('#category_id').val('');
                            $('#category_id_main').val('');
                            alert(response.messages);
                        }
                    },
                    error: function (xhr, status, error) {
                        $('#course_thumbnail_preview').attr('src', response.img_path);
                        $('#course_thumbnail_base64').val('');
                        $('#course_thumbnail_preview2').attr('src', response.img_path);
                        $('#course_thumbnail_base642').val('');
                        $('#course_thumbnail_preview3').attr('src', response.img_path);
                        $('#course_thumbnail_base643').val('');
                        $('#course_thumbnail_base642_li').hide();
                        $('#course_thumbnail_base643_li').hide();
                        $('#category_id').val('');
                        $('#category_id_main').val('');
                        alert("An error occurred during the AJAX request.");
                    },
                    // complete: function () {
                    //     submitBtn.innerHTML = submitText;
                    //     $(".course-checkbox").prop("checked", false);
                    //     $("#submit").prop("disabled", false);
                    // }
                });// end ajax
            }
        }// end -:- getCourseImagePath()

        // check course_thumbnail id is checked or not
        $(document).ready(function() {
            $('#course_thumbnail_base64').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#course_thumbnail_base642').prop('checked', false);
                    $('#course_thumbnail_base643').prop('checked', false);
                }
            });
            $('#course_thumbnail_base642').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#course_thumbnail_base64').prop('checked', false);
                    $('#course_thumbnail_base643').prop('checked', false);
                }
            });
            $('#course_thumbnail_base643').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#course_thumbnail_base64').prop('checked', false);
                    $('#course_thumbnail_base642').prop('checked', false);
                }
            });
        });
    </script>
    <script>
        tinymce.init({
            selector: '#necessary_qualification_experience'
        });
        tinymce.init({
            selector: '#course_contents'
        });
    </script>

@endsection
