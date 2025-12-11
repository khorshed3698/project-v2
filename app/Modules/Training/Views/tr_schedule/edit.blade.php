@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')
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

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading" style="padding:13px 10px;">
                <b> Edit Training Schedule</b>
            </div>

            <div class="panel-body">
                {!! Form::open([
                    'url' => '/training/update-schedule/' . $id,
                    'method' => 'post',
                    'class' => 'form-horizontal smart-form',
                    'id' => 'scheduleForm',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                    'role' => 'form',
                ]) !!}
    
                <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($tr_data->id) }}"/>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('course_id', 'Course Name', ['class' => 'col-md-2 required-star']) !!}
                                <div class="col-md-10 {{ $errors->has('course_id') ? 'has-error' : '' }}">
                                    {!! Form::select('course_id', $trCourse, $tr_data->course_id, [
                                        'class' => 'form-control required imput-md',
                                        'id' => 'tr_course_id',
                                        'onchange' => 'getCourseImagePath(value)',
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
                                <div class="col-md-5 {{ $errors->has('duration') ? 'has-error' : '' }}">
                                    {!! Form::select(
                                        'duration',
                                        ['' => 'Select one'] + array_combine(range(1, 31), range(1, 31)),
                                        $tr_data->duration,
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
                                        $tr_data->duration_unit,
                                        ['class' => 'form-control input-md required', 'id' => 'duration_unit']
                                    ) !!}
                                    {!! $errors->first('duration_unit', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6" style="height: 35px; margin-bottom: 30px;">
                                {!! Form::label('fees_type', 'Course Fee Type', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8  {{ $errors->has('fees_type') ? 'has-error' : '' }}">
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('fees_type', 'paid', $tr_data->fees_type == 'paid' ? true : false, [
                                            'class' => 'form-check-input fees_type'
                                        ]) !!}
                                        Paid
                                    </label>
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('fees_type', 'free', $tr_data->fees_type == 'free' ? true : false, [
                                            'class' => 'form-check-input fees_type'
                                        ]) !!}
                                        Free
                                    </label>
                                    {!! $errors->first('fees_type', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6" id="amountDiv">
                                {!! Form::label('amount', 'Course Fee (Second Payment)', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8 {{ $errors->has('amount') ? 'has-error' : '' }}">
                                    {!! Form::text('amount', $tr_data->amount, ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div><!--.form-group-->

                    <div class="form-group" >
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('category_id', 'Course Category', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8 {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                    {!! Form::select(
                                        '',
                                        $trCategory,
                                        $tr_data->category_id,
                                        ['class' => 'form-control input-md', 'id' => 'category_id', 'disabled' => 'disabled']
                                    ) !!}
                                    {!! Form::hidden(
                                        'category_id',
                                        $tr_data->category_id,
                                        ['class' => 'form-control input-md', 'id' => 'category_id_main', 'readonly' => 'readonly']
                                    ) !!}
                                    {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('enroll_deadline', 'Enrolment End Date
                                ', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('enroll_deadline') ? 'has-error' : '' }}">
                                    <div class="datepicker input-group date">
                                        {!! Form::text('enroll_deadline', $tr_data->enroll_deadline, [
                                            'class' => 'form-control input_ban required engOnly',
                                            'id' => 'enroll_deadline',
                                        ]) !!}
                                        {!! $errors->first('enroll_deadline', '<span class="help-block">:message</span>') !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            {{-- <div class="col-md-6">
                                {!! Form::label('course_duration_start', 'Course Start and End Date', [
                                    'class' => 'col-md-4 control-label required-star',
                                ]) !!}
                                <div class="col-md-8 {{ $errors->has('course_duration_start') ? 'has-error' : '' }}">
                                    {!! Form::text('course_duration_start',$tr_data->course_duration_start, [
                                        'class' => 'form-control bnEng input_ban required',
                                        'id' => 'course_duration_start',
                                        'readonly' => 'readonly',
                                        'style' => 'background:white',
                                    ]) !!}
                                    
                                </div>
                                {!! $errors->first('course_duration_start', '<span class="help-block">:message</span>') !!}
                            </div> --}}

                            <div class="col-md-6">
                                {!! Form::label('course_duration_start', 'Course Start Date', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('course_duration_start') ? 'has-error' : '' }}">
                                    <div class="input-group date" id="course_duration_start">
                                        {!! Form::text('course_duration_start', $tr_data->course_duration_start, [
                                            'class' => 'form-control input_ban required engOnly'
                                        ]) !!}
                                        <span class="input-group-addon"> 
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('course_duration_start', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="col-md-6 mt-10 {{ $errors->has('course_duration_end') ? 'has-error' : '' }}">
                                {!! Form::label('course_duration_end', 'Course End Date', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8">
                                    <div class="input-group date">
                                        {!! Form::text('course_duration_end', $tr_data->course_duration_end, [
                                            'class' => 'form-control input_ban required engOnly',
                                            'id' => 'course_duration_end',
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
                            <div class="col-md-6">
                                {!! Form::label('no_of_class', 'Total Class', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('no_of_class') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'no_of_class', $tr_data->no_of_class, [
                                        'class' => 'form-control input-md required input_ban onlyNumber engOnly',
                                        'id' => 'no_of_class'
                                    ]) !!}
                                    {!! $errors->first('no_of_class', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('total_hours', 'Total Hour', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('total_hours') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'total_hours', $tr_data->total_hours, [
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
                                    {!! Form::input('text', 'venue', $tr_data->venue, [
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
                                    {!! Form::hidden('batch', $tr_data->batch_id) !!}
                                    {!! Form::input('text', 'batch_id', $tr_data->batch->batch_name, [
                                        'class' => 'form-control input-md required bnEng ui-autocomplete-input',
                                        'id' => 'batch_id',
                                        'placeholder' => 'Batch Name',
                                        'autocomplete' => 'off',
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
                                <div class="col-md-8 {{ $errors->has('training_office') ? 'has-error' : '' }}">
                                    {!! Form::text('training_office', $tr_data->training_office, [
                                        'class' => 'form-control input-md required',
                                        'id' => 'training_office',
                                    ]) !!}
                                    {!! $errors->first('training_office', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('training_center', 'Course Center', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('training_center') ? 'has-error' : '' }}">
                                    {!! Form::text('training_center', $tr_data->training_center, [
                                        'class' => 'form-control input-md required',
                                        'id' => 'training_center',
                                    ]) !!}
                                    {!! $errors->first('training_center', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6" style="height: 34px; margin-bottom: 30px;">
                                {!! Form::label('course_evaluation', 'Course Marking', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('course_evaluation') ? 'has-error' : '' }}">
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('course_evaluation', 'yes', $tr_data->course_evaluation == 'yes', [
                                            'class' => 'course_evaluation',
                                        ]) !!}
                                        Yes
                                    </label>
                                    <label class="form-check-label radio-inline">
                                        {!! Form::radio('course_evaluation', 'no', $tr_data->course_evaluation == 'no', [
                                            'class' => 'course_evaluation',
                                        ]) !!}
                                        No
                                    </label>
                                    {!! $errors->first('course_evaluation', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6" id="pass_marks_div" >
                                {!! Form::label('pass_marks', 'Total Marks', ['class' => 'col-md-4 control-label required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('pass_marks') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'pass_marks', $tr_data->pass_marks, [
                                        'class' => 'form-control input-md required bnEng input_ban onlyNumber',
                                        'id' => 'pass_marks',
                                        'placeholder' => 'পাশ নম্বর লিখুন',
                                    ]) !!}
                                    {!! $errors->first('pass_marks', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="overflow-scroll">
                            <table aria-label="Detailed Report Data Table" id="courseDetailTable" class="table table-bordered dt-responsive" cellspacing="0"
                        width="100%">
                        <thead style="background-color: #3379b77e">
                            <tr>
                                <th class="text-center">Session Name</th>
                                <th class="text-center">Session Time</th>
                                <th class="text-center width-20">Day</th>
                                <th class="text-center"  width="15%">Application</th>
                                <th class="text-center"  width="15%">Total Applicant </th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($trSessionData)
                            @foreach ($trSessionData as $key => $scheduleData)
                                <tr id="courseDetailRow{{ $key }}" data-number="{{ $key }}">
                                    {!! Form::hidden("tr_session_id[$key]", $scheduleData->id) !!}

                                    <td>
                                        {!! Form::text("session_name[$key]", $scheduleData->session_name, [
                                            'class' => 'form-control input-md session_name text-center input_ban required engOnly',
                                        ]) !!}
                                    </td>
                                    <td>
                                        <div class="row" style="padding-left: 5px">
                                            <div class="timepicker input-group date"
                                                style="float: left; margin-right: 2px; width: 48%">
                                                {!! Form::text("session_start_time[$key]", date('h:i A', strtotime($scheduleData->session_start_time)), [
                                                    'class' => 'form-control bnEng required',
                                                    'placeholder' => '-- -- --',
                                                ]) !!}
                                                <span class="input-group-addon date">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                            </div>
                                            <div class="timepicker input-group date" style="width: 50%">
                                                {!! Form::text("session_end_time[$key]", date('h:i A', strtotime($scheduleData->session_end_time)), [
                                                    'class' => 'form-control bnEng required',
                                                    'placeholder' => '-- -- --',
                                                ]) !!}
                                                <span class="input-group-addon date">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="width-20" style="width: 20%">
                                        <?php
                                        $days_arr = explode(',', $scheduleData->session_days);
                                        ?>
                                        {!! Form::select(
                                            "day[$key][]",
                                            [
                                                'Saturday' => 'Saturday',
                                                'Sunday' => 'Sunday',
                                                'Monday' => 'Monday',
                                                'Tuesday' => 'Tuesday',
                                                'Wednesday' => 'Wednesday',
                                                'Thursday' => 'Thursday',
                                                'Friday' => 'Friday',
                                            ],
                                            $days_arr,
                                            ['class' => 'form-control input-md days required', 'multiple' => 'multiple']
                                        ) !!}
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                {!! Form::checkbox("applicant_limit[$key]", 'limit', $scheduleData->applicant_limit == 'limit', [
                                                    'class' => 'form-check-input applicant_limit',
                                                ]) !!}
                                                Limited
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                {!! Form::checkbox("applicant_limit[$key]", 'unlimit', $scheduleData->applicant_limit == 'unlimit', [
                                                    'class' => 'form-check-input applicant_limit',
                                                ]) !!}
                                                Unlimited
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        {!! Form::text("seat_capacity[$key]", $scheduleData->seat_capacity, [
                                            'class' =>
                                                'form-control input-md input_ban seat_capacity text-center required ' .
                                                ($scheduleData->applicant_limit == 'unlimit' ? 'hidden' : ''),
                                            'placeholder' => '0000',
                                        ]) !!}
                                    </td>
                                    <td class="text-center">
                                        @if ($key == 0)
                                            <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                onclick="addTableRow('courseDetailTable', 'courseDetailRow0');">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        @else
                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger removeRow"
                                                onclick="removeTableRow('courseDetailTable', 'courseDetailRow{{ $key }}');">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @endif
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
                                <div
                                    class="col-md-10 {{ $errors->has('necessary_qualification_experience') ? 'has-error' : '' }}">
                                    {!! Form::textarea('necessary_qualification_experience', $tr_data->necessary_qualification_experience, [
                                        'class' => 'form-control bnEng required',
                                        'placeholder' => 'Necessary Qualification',
                                        'id' => 'necessary_qualification_experience',
                                        'cols' => '50',
                                        'rows' => '10',
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
                                <div class="col-md-10 {{ $errors->has('objectives') ? 'has-error' : '' }}">
                                    {!! Form::input('text', 'objectives', $tr_data->objectives, [
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
                                <div class="col-md-10 {{ $errors->has('course_contents') ? 'has-error' : '' }}">
                                    {!! Form::textarea('course_contents', $tr_data->course_contents, [
                                        'class' => 'form-control  bnEng input-md required',
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
                                        {!! Form::checkbox('course_thumbnail_base64', '1', $tr_data->course_image_no == 1 ? 'checked' : null, [
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
                                        {!! Form::checkbox('course_thumbnail_base642', '1', $tr_data->course_image_no == 2 ? 'checked' : null, [
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
                                        {!! Form::checkbox('course_thumbnail_base643', '1', $tr_data->course_image_no == 3 ? 'checked' : null, [
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
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                {!! Form::label('is_active', 'Active Status: ', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('is_active') ? 'has-error' : '' }}">
                                    <label>{!! Form::radio('is_active', '1', $tr_data->is_active == 1 ? 'checked' : '', [
                                        'class' => 'required',
                                        'id' => 'yes',
                                    ]) !!} Active</label>
                                    <label>{!! Form::radio('is_active', '0', $tr_data->is_active == 0 ? 'checked' : '', [
                                        'class' => 'required',
                                        'id' => 'no',
                                    ]) !!} Inactive</label>
    
                                    {!! $errors->first('is_active', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-md-6 mt-10">
                                {!! Form::label('is_featured', 'Slider Featured: ', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{ $errors->has('is_featured') ? 'has-error' : '' }}">
                                    <label>{!! Form::radio('is_featured', '1', $tr_data->is_featured == 1 ? 'checked' : '', [
                                        'class' => 'required',
                                        'id' => 'yes',
                                    ]) !!} Active</label>
                                    <label>{!! Form::radio('is_featured', '0', $tr_data->is_featured == 0 ? 'checked' : '', [
                                        'class' => 'required',
                                        'id' => 'no',
                                    ]) !!} Inactive</label>
    
                                    {!! $errors->first('is_featured', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 {{ $errors->has('status') ? 'has-error' : '' }}">
                                {!! Form::label('status', 'Course Status', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">
                                    {!! Form::select(
                                        'status',
                                        ['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed'],
                                        $tr_data->status,
                                        ['class' => 'form-control input-md', 'id' => 'status']
                                    ) !!}
                                    {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
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
                                <i class="fa fa-chevron-circle-right"></i> 
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('footer-script')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
    <script src="{{ asset('assets/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            var fees_type = document.querySelector('input[name="fees_type"]:checked').value;
            if (fees_type == 'free') {
                $("#amountDiv").addClass("d-none");
            }
            else{
                $("#amountDiv").removeClass("d-none");
            }

            var course_evaluation = "{{ $tr_data->course_evaluation }}";
            console.log(course_evaluation);
            if(course_evaluation == 'no'){
                $('#pass_marks_div').hide();
                $('#pass_marks').val(0);
            }
            else {
                $('#pass_marks_div').show();
            }

        }, 5000);
        $(document).on('change', '.fees_type', function() {
            if ($(this).val() == 'paid') {
                $("#amountDiv").removeClass("d-none");
                $("#amountDiv").addClass("d-block");
            } else {
                $("#amountDiv").addClass("d-none");
                $("#amountDiv").removeClass("d-block");
            }
        });

        $(document).on('change', '.applicant_limit', function() {
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

        $(document).ready(function() {
            $('.days').select2();
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
            
        });
        $('.course_evaluation').click(function() {
            var course_evaluation = document.querySelector('input[name="course_evaluation"]:checked').value;
            if (course_evaluation == 'no') {
                $('#pass_marks_div').hide();
                $('#pass_marks').val(0);
            }
            else{
                $('#pass_marks_div').show();
            }

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

            // Clear the hidden tr_session_id input value
            var attrHidden = $("#" + tableID).find('#' + idText).find('input[type=hidden]');
            for (var i = 0; i < attrHidden.length; i++) {
                if (attrHidden[i].name.startsWith("tr_session_id")) {
                    var repText = attrHidden[i].name.replace('[0]', '[' + nameRo + ']');
                    attrHidden[i].name = repText;
                    attrHidden[i].value = '';
                }
            }

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

            $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function(e) {
                //period decimal
                if ((e.which >= 48 && e.which <= 57)
                    //numpad decimal
                    ||
                    (e.which >= 96 && e.which <= 105)
                    // Allow: backspace, delete, tab, escape, enter and .
                    ||
                    $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                    // Allow: Ctrl+A
                    ||
                    (e.keyCode == 65 && e.ctrlKey === true)
                    // Allow: Ctrl+C
                    ||
                    (e.keyCode == 67 && e.ctrlKey === true)
                    // Allow: Ctrl+V
                    ||
                    (e.keyCode == 86 && e.ctrlKey === true)
                    // Allow: Ctrl+X
                    ||
                    (e.keyCode == 88 && e.ctrlKey === true)
                    // Allow: home, end, left, right
                    ||
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    var $this = $(this);
                    setTimeout(function() {
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
            }).on('paste', function(e) {
                var $this = $(this);
                setTimeout(function() {
                    $this.val($this.val().replace(/[^.0-9]/g, ''));
                }, 4);
            });
            // Datepicker initialize of the new row
            $("#" + tableID).find('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MM-YYYY',
                extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
                maxDate: 'now',
                minDate: '01/01/1905'
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
                    // beforeSend: function() {
                    //     submitBtn.innerHTML = loadingIcon + ' ' + submitText;
                    //     $("#submit").prop("disabled", true);
                    // },
                    success: function(response) {
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
                    error: function(xhr, status, error) {
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
                    // complete: function() {
                    //     submitBtn.innerHTML = submitText;
                    //     $(".course-checkbox").prop("checked", false);
                    //     $("#submit").prop("disabled", false);
                    // }
                }); // end ajax
            }
        } // end -:- getCourseImagePath()


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
            var thumbneil = "{{ $tr_data->course_image_no }}";
            console.log(thumbneil);
            if(thumbneil == 1){
                $('#course_thumbnail_base64').prop('checked', true);
            }
            else if(thumbneil == 2){
                $('#course_thumbnail_base642').prop('checked', true);
            }
            else if(thumbneil == 3){
                $('#course_thumbnail_base643').prop('checked', true);
            }
        });

        
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                var selectElement = document.getElementById('tr_course_id');
                if (selectElement) {
                    var selectedValue = selectElement.value;
                    getCourseImagePath(selectedValue);
                }

                $('#course_thumbnail_base64').trigger('change');
                $('#course_thumbnail_base642').trigger('change');
                $('#course_thumbnail_base643').trigger('change');
            }, 500);
        });
    </script>
    <script>
        tinymce.init({
            selector: '#necessary_qualification_experience',
            height: 150,
            theme: 'modern',
            plugins: [
                'autosave advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            content_css: [
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
        tinymce.init({
            selector: '#course_contents',
            height: 150,
            theme: 'modern',
            plugins: [
                'autosave advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            content_css: [
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
    </script>
@endsection
