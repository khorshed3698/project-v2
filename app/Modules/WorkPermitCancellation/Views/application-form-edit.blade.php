<?php
$accessMode = ACL::getAccsessRight('WorkPermitCancellation');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .form-group{
        margin-bottom: 2px;
    }
    textarea{
        resize: vertical;
    }
    .wizard > .steps > ul > li{
        width: 20% !important;
    }
    .wizard > .steps > ul > li a {
        padding: 0.5em 0.5em !important;
    }
    .wizard > .actions {
        top: -15px;
    }
    .wizard {
        overflow: visible;
    }
    .wizard > .content {
        overflow: visible;
    }
    .img-thumbnail {
        height: 120px;
        width: 120px;
    }
    .blink_me {
        animation: blinker 5s linear infinite;
    }

    @keyframes blinker {
        50% { opacity: .5; }
    }
</style>

<section class="content" id="applicationForm">
    @include('ProcessPath::remarks-modal')
    <div class="col-md-12">
        <div class="box"  id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><b>Application for Work Permit Cancellation</b></h5>
                        </div>
                        <div class="pull-right">
                            @if (isset($appInfo) && $appInfo->status_id == -1)
                                <a href="{{ asset('assets/images/SampleForm/work_permit_cancellation.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                    <i class="fas fa-file-pdf"></i>
                                    Download Sample Form
                                </a>
                            @endif
                            @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                                <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info"
                                   title="Download Approval Copy" target="_blank" rel="noopener"> <i class="fa  fa-file-pdf-o"></i> Download Approval Copy</a>
                            @endif

                            @if(!in_array($appInfo->status_id,[-1,5,6,22]))
                                <a href="/work-permit-cancellation/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank"
                                   class="btn btn-danger btn-md">
                                    <i class="fa fa-download"></i> Application Download as PDF
                                </a>
                            @endif

                            @if(in_array($appInfo->status_id,[5,6,17,22]))
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">

                        @if ($viewMode == 'on')
                            <section class="content-header">
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                    <li><strong> Date of Submission : </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }} </li>
                                    <li><strong>Current Status : </strong>
                                        @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                        @else {!! $appInfo->status_name !!}
                                        @endif
                                    </li>
                                    <li>
                                        @if($appInfo->desk_id != 0) <strong>Current Desk :</strong>
                                        {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                        @else
                                            <strong>Current Desk :</strong> Applicant
                                        @endif
                                    </li>
                                </ol>
                            </section>
                        @endif

                        {!! Form::open(array('url' => 'work-permit-cancellation/store','method' => 'post','id' => 'WorkPermitCancelationForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <input type="hidden" name="ref_app_approve_date" value="{{ (!empty($appInfo->ref_app_approve_date) ? date('d-M-Y', strtotime($appInfo->ref_app_approve_date)) : '') }}">

                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

                        @if((in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505' && $viewMode == 'on') || (in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404']) && $viewMode == 'on'))
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Cancellation Effect Date</legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('approved_effect_date','Start Date',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <div class="datepicker input-group date">
                                                    {!! Form::text('approved_effect_date', (!empty($appInfo->approved_effect_date) ? date('d-M-Y', strtotime($appInfo->approved_effect_date)) : ''), ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                    <span class="input-group-addon"><span
                                                                class="fa fa-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        @endif

                        @if(!empty($metingInformation) && $viewMode == 'on')
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Meeting Info</legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('','Meeting No',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('','Meeting Date',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        @endif
                        <h3 class="stepHeader">Basic Instructions</h3>
                        <fieldset>
                            <legend class="d-none">Basic Instructions</legend>
                            @if($appInfo->status_id == 5 && (!empty($appInfo->resend_deadline)))
                                <div class="form-group blink_me">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert btn-danger" role="alert">
                                                You must re-submit the application before <strong>{{ date("d-M-Y", strtotime($appInfo->resend_deadline)) }}</strong>, otherwise, it will be automatically rejected.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic Instructions</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('last_work_permit') ? 'has-error': ''}}">
                                                {!! Form::label('last_work_permit','Did you receive last work-permit through online OSS?',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    @if($appInfo->last_work_permit == 'yes')
                                                    <label class="radio-inline">{!! Form::radio('last_work_permit','yes', ($appInfo->last_work_permit == 'yes') ? true : false, ['class'=>' cusReadonly helpTextRadio required', 'id'=>'last_work_permit_yes', 'onclick' => 'lastWorkPermit(this.value)']) !!}  Yes</label>
                                                    @endif
                                                    @if($appInfo->last_work_permit == 'no')
                                                    <label class="radio-inline">{!! Form::radio('last_work_permit', 'no', ($appInfo->last_work_permit == 'no') ? true : false, ['class'=>' cusReadonly required', 'id'=>'last_work_permit_no', 'onclick' => 'lastWorkPermit(this.value)']) !!}  No</label>
                                                    @endif

                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            @if($appInfo->last_work_permit == 'yes')
                                            <div id="ref_app_tracking_no_div" class="col-md-12 required {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                {!! Form::label('ref_app_tracking_no','Please give your approved work permit reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        {!! Form::hidden('ref_app_tracking_no', $appInfo->ref_app_tracking_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm', 'readonly']) !!}
                                                        <span class="label label-success" style="font-size: 15px">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>

                                                    @if ($appInfo->last_work_permit == 'yes')
                                                            &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                                        @endif
                                                        <br/>

                                                        @if($viewMode != 'on')
                                                            <small class="text-danger">N.B.: Once you save or submit the application, the Work permit tracking no cannot be changed anymore.</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($appInfo->last_work_permit == 'no')
                                                <div id="manually_approved_no_div" class="col-md-12 required {{$errors->has('manually_approved_wp_no') ? 'has-error': ''}} ">
                                                {!! Form::label('manually_approved_wp_no','Please give your manually approved work permit reference  No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('manually_approved_wp_no', $appInfo->manually_approved_wp_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('manually_approved_wp_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div id="applicant_name_div" class="col-md-6 hidden {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                {!! Form::label('applicant_name','Name',['class'=>'text-left required-star col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_name', $appInfo->applicant_name, ['class' => 'form-control required cusReadonly textOnly input-md']) !!}
                                                    {!! $errors->first('applicant_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="applicant_nationality_div" class="col-md-6 hidden {{$errors->has('applicant_nationality') ? 'has-error': ''}}">
                                                {!! Form::label('applicant_nationality','Nationality',['class'=>'text-left required-star col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('applicant_nationality', $nationality, $appInfo->applicant_nationality, ['class' => 'form-control required cusReadonly input-md readonly-select','placeholder' => 'Select one']) !!}
                                                    {!! $errors->first('applicant_nationality','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div id="applicant_pass_no_div" class="col-md-6 hidden {{$errors->has('applicant_pass_no') ? 'has-error': ''}}">
                                                {!! Form::label('applicant_pass_no','Passport Number',['class'=>'text-left required-star col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_pass_no', $appInfo->applicant_pass_no, ['class' => 'form-control required cusReadonly input-md']) !!}
                                                    {!! $errors->first('applicant_pass_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="applicant_position_div" class="col-md-6 hidden {{$errors->has('applicant_position') ? 'has-error': ''}}">
                                                {!! Form::label('applicant_position','Position/ Designation',['class'=>'text-left required-star col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_position', $appInfo->applicant_position, ['class' => 'form-control required cusReadonly input-md']) !!}
                                                    {!! $errors->first('applicant_position','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div id="issue_date_of_last_div" class="col-md-6 hidden {{$errors->has('issue_date_of_last_wp') ? 'has-error': ''}}">
                                                {!! Form::label('issue_date_of_last_wp','Issue date of last Work Permit',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('issue_date_of_last_wp', (!empty($appInfo->issue_date_of_last_wp) ? date('d-M-Y', strtotime($appInfo->issue_date_of_last_wp)) : ''), ['class' => 'form-control cusReadonly input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('issue_date_of_last_wp','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            {{--Show only commercial department--}}
                                            @if($appInfo->department_id == 1 || $appInfo->department_id == '1')
                                                <div id="expiry_date_of_op_div" class="col-md-6 hidden {{$errors->has('expiry_date_of_op') ? 'has-error': ''}}">
                                                    {!! Form::label('expiry_date_of_op','Expiry Date of Office Permission',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="datepicker input-group date">
                                                            {!! Form::text('expiry_date_of_op', (!empty($appInfo->expiry_date_of_op) ? date('d-M-Y', strtotime($appInfo->expiry_date_of_op)) : ''), ['class' => 'form-control cusReadonly input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('expiry_date_of_op','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div id="date_of_cancellation_div" class="col-md-6 hidden {{$errors->has('date_of_cancellation') ? 'has-error': ''}}">
                                                {!! Form::label('date_of_cancellation','Date Of cancellation',['class'=>'text-left  col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div id="date_of_cancellation" class="input-group date">
                                                        {!! Form::text('date_of_cancellation', (!empty($appInfo->date_of_cancellation) ? date('d-M-Y', strtotime($appInfo->date_of_cancellation)) : ''), ['class' => 'form-control input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('date_of_cancellation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="applicant_remarks_div" class="col-md-6 hidden {{$errors->has('applicant_remarks') ? 'has-error': ''}}">
                                                {!! Form::label('applicant_remarks','Remarks',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('applicant_remarks', $appInfo->applicant_remarks, ['data-charcount-maxlength' => '200', 'data-rule-maxlength'=>'200', 'placeholder'=>'Remarks', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                    'size'=>'5x2', 'placeholder' => 'Maximum 200 characters']) !!}
                                                    {!! $errors->first('applicant_remarks','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Applicant Details</h3>
                        <fieldset>
                            {{-- Basic Information--}}
                            @include('WorkPermitCancellation::basic-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Applicant Details</strong></div>
                                <div class="panel-body">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Contact address of the expatriate in Bangladesh:</legend>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_division_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_division_id','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ex_office_division_id', $divisions, $appInfo->ex_office_division_id, ['class' => 'form-control cusReadonly input-md required readonly-select', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id', ". $appInfo->ex_office_district_id .")"]) !!}
                                                            {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ex_office_district_id', $district_eng, $appInfo->ex_office_district_id, ['class' => 'form-control cusReadonly input-md required readonly-select', 'placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id', ". $appInfo->ex_office_thana_id .")"]) !!}
                                                            {!! $errors->first('ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ex_office_thana_id', [], $appInfo->ex_office_thana_id, ['class' => 'form-control cusReadonly input-md required readonly-select', 'placeholder' => 'Select district first']) !!}
                                                            {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_post_office') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_post_office', $appInfo->ex_office_post_office, ['class' => 'form-control input-md cusReadonly']) !!}
                                                            {!! $errors->first('ex_office_post_office','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_post_code', $appInfo->ex_office_post_code, ['class' => 'form-control cusReadonly input-md post_code_bd required']) !!}
                                                            {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_address') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_address', $appInfo->ex_office_address, ['maxlength'=>'150', 'class' => 'form-control cusReadonly input-md required']) !!}
                                                            {!! $errors->first('ex_office_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_telephone_no', $appInfo->ex_office_telephone_no, ['maxlength'=>'20','class' => 'form-control cusReadonly input-md phone_or_mobile']) !!}
                                                            {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_mobile_no', 'Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_mobile_no',$appInfo->ex_office_mobile_no, ['class' => 'form-control helpText15 cusReadonly input-md required' ,'id' => 'ex_office_mobile_no']) !!}
                                                            {!! $errors->first('ex_office_mobile_no','<span class="help-block">:message</span>') !!}
                                                            <span id="valid-msg" class="hidden text-success"
                                                                  style="font-size: 12px"><i class="fa fa-check"
                                                                                             aria-hidden="true"></i> Valid</span>
                                                            <span id="error-msg" class="hidden text-danger"
                                                                  style="font-size: 12px"><i class="fa fa-times"
                                                                                             aria-hidden="true"></i> Invalid</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_fax_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_fax_no', $appInfo->ex_office_fax_no, ['class' => 'form-control cusReadonly input-md']) !!}
                                                            {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_email') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_email', $appInfo->ex_office_email, ['class' => 'form-control cusReadonly email input-md required']) !!}
                                                            {!! $errors->first('ex_office_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Compensation and Benefit</legend>
                                        <div class="table-responsive">
                                            <table  class="table table-striped table-bordered" aria-label="Detailed Compensation and Benefit Report" width="100%">
                                                <thead class="alert alert-warning">
                                                <tr>
                                                    <th class="text-center" style="vertical-align: middle"><strong>Salary structure</strong></th>
                                                    <th class="text-center">Payment</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Currency</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class=" helpTextCom" id="basic_local_amount_label">a. Basic salary/ Honorarium</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_payment_type_id', $paymentMethods, $appInfo->basic_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('basic_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('basic_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->basic_local_amount) : $appInfo->basic_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md basic_salary_amount numberNoNegative cb_req_field cusReadonly ', 'step' => '0.01', 'id' => 'basic_local_amount']) !!}
                                                            {!! $errors->first('basic_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_local_currency_id', $currencies, $appInfo->basic_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control required input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('basic_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="helpTextCom" id="overseas_local_amount_label">b. Overseas allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_payment_type_id', $paymentMethods, $appInfo->overseas_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md readonly-select']) !!}
                                                            {!! $errors->first('overseas_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('overseas_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->overseas_local_amount) : $appInfo->overseas_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative cb_req_field cusReadonly ', 'step' => '0.01']) !!}
                                                            {!! $errors->first('overseas_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_local_currency_id', $currencies, $appInfo->overseas_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md readonly-select']) !!}
                                                            {!! $errors->first('overseas_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="house_local_amount_label">c. House rent</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('house_payment_type_id', $paymentMethods, $appInfo->house_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('house_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('house_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->house_local_amount) : $appInfo->house_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative cb_req_field cusReadonly ', 'step' => '0.01']) !!}
                                                            {!! $errors->first('house_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('house_local_currency_id', $currencies, $appInfo->house_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('house_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="conveyance_local_amount_label">d. Conveyance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_payment_type_id', $paymentMethods, $appInfo->conveyance_payment_type_id, ['data-rule-maxlength'=>'40',
                                                            'class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('conveyance_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('conveyance_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount( $appInfo->conveyance_local_amount) :  $appInfo->conveyance_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative cb_req_field cusReadonly', 'step' => '0.01']) !!}
                                                            {!! $errors->first('conveyance_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_local_currency_id', $currencies, $appInfo->conveyance_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('conveyance_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="medical_local_amount_label">e. Medical allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_payment_type_id', $paymentMethods, $appInfo->medical_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('medical_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('medical_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->medical_local_amount) : $appInfo->medical_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative cb_req_field cusReadonly', 'step' => '0.01']) !!}
                                                            {!! $errors->first('medical_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_local_currency_id', $currencies, $appInfo->medical_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('medical_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="ent_local_amount_label">f. Entertainment allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_payment_type_id', $paymentMethods, $appInfo->ent_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('ent_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('ent_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->ent_local_amount) : $appInfo->ent_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative cb_req_field cusReadonly', 'step' => '0.01']) !!}
                                                            {!! $errors->first('ent_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_local_currency_id', $currencies, $appInfo->ent_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('ent_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="bonus_local_amount_label">g. Annual Bonus</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_payment_type_id', $paymentMethods, $appInfo->bonus_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('bonus_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('bonus_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->bonus_local_amount) : $appInfo->bonus_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative cb_req_field cusReadonly', 'step' => '0.01']) !!}
                                                            {!! $errors->first('bonus_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_local_currency_id', $currencies, $appInfo->bonus_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field readonly-select']) !!}
                                                            {!! $errors->first('bonus_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="other_benefits_label">h. Other fringe benefits (if any)</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="5">
                                                        <div class="{{ $errors->has('other_benefits')?'has-error':'' }}">
                                                            {!! Form::textarea('other_benefits', $appInfo->other_benefits, ['class' => 'form-control input-md bigInputField', 'size' =>'5x1','data-rule-maxlength'=>'250', 'placeholder' => 'Maximum 250 characters', 'data-charcount-maxlength'=>'250', 'id' => 'other_benefits']) !!}
                                                            {!! $errors->first('other_benefits','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="text-center stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv">
                                @include('WorkPermitCancellation::documents')
                            </div>
                            @if($viewMode != 'off')
                                @include('WorkPermitCancellation::doc-tab')
                            @endif
                        </fieldset>

                        <h3 class="stepHeader">Declaration</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading" style="padding-bottom: 4px;">
                                    <strong>Declaration and undertaking</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ol type="a">
                                                    <li>
                                                        <p>I do hereby declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement given</p>
                                                    </li>
                                                    <li>
                                                        <p>I do hereby undertake full responsibility of the expatriate for whom visa recommendation is sought during their stay in Bangladesh. </p>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Authorized person of the organization</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_full_name') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_full_name','Full Name',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_full_name', $appInfo->auth_full_name, ['class' => 'form-control required input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', $appInfo->auth_designation, ['class' => 'form-control required input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'form-control required input-sm phone_or_mobile', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', $appInfo->auth_email, ['class' => 'form-control required input-sm email', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail img-user"
                                                                 src="{{ (!empty($appInfo->auth_image) ? url('users/upload/'.$appInfo->auth_image) : url('users/upload/'.Auth::user()->user_pic)) }}"
                                                                 alt="User Photo">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="auth_image" value="{{ (!empty($appInfo->auth_image) ? $appInfo->auth_image : Auth::user()->user_pic) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('accept_terms',1, ($appInfo->accept_terms == 1) ? true : false, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                                I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Payment & Submit</h3>
                        <fieldset>
                            <legend class="d-none">Payment & Submit</legend>
                            @if($viewMode != 'on')
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <strong>Service Fee Payment</strong>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_contact_name', $appInfo->sfp_contact_name, ['class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md  email required']) !!}
                                                        {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control input-md required sfp_contact_phone phone_or_mobile']) !!}
                                                        {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_contact_address', $appInfo->sfp_contact_address, ['class' => 'bigInputField form-control input-md required']) !!}
                                                        {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_pay_amount','Pay amount',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_pay_amount', $appInfo->sfp_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_vat_on_pay_amount', $appInfo->sfp_vat_on_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::label('sfp_total_amount','Total Amount',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_total_amount', number_format($appInfo->sfp_total_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_status','Payment Status',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        @if($appInfo->sfp_payment_status == 0)
                                                            <span class="label label-warning">Pending</span>
                                                        @elseif($appInfo->sfp_payment_status == -1)
                                                            <span class="label label-info">In-Progress</span>
                                                        @elseif($appInfo->sfp_payment_status == 1)
                                                            <span class="label label-success">Paid</span>
                                                        @elseif($appInfo->sfp_payment_status == 2)
                                                            <span class="label label-danger">-Exception</span>
                                                        @elseif($appInfo->sfp_payment_status == 3)
                                                            <span class="label label-warning">Waiting for Payment Confirmation</span>
                                                        @else
                                                            <span class="label label-warning">invalid status</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($appInfo->sfp_payment_status != 1)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="alert alert-danger" role="alert">
                                                            <b>Vat/ Tax</b> and <b>Transaction charge</b> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </fieldset>

                        @if(ACL::getAccsessRight('WorkPermitCancellation','-E-') && $viewMode != "on" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                            
                            @if(!in_array($appInfo->status_id,[5,22]))
                                <div class="pull-left">
                                    <button type="submit" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn" id="save_as_draft">Save as Draft
                                    </button>
                                </div>
                                <div class="pull-left" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md"
                                            value="submit" name="actionBtn">Payment & Submit
                                        <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title="" data-original-title="After clicking this button will take you in the payment portal for providing the required payment. After payment, the application will be automatically submitted and you will get a confirmation email with necessary info." aria-describedby="tooltip"></i>
                                    </button>
                                </div>
                            @endif

                            @if(in_array($appInfo->status_id,[5,22]))
                                <div class="pull-left">
                                    <span style="display: block; height: 34px">&nbsp;</span>
                                </div>
                                <div class="pull-left" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-info btn-md"
                                            value="resubmit" name="actionBtn">Re-submit
                                    </button>
                                </div>
                            @endif

                        @else
                            <style>
                                .wizard > .actions{
                                    top: -15px !important;
                                }
                            </style>
                        @endif

                    {!! Form::close() !!}<!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script>

    function uploadDocument(targets, id, vField, isRequired) {
        var file_id = document.getElementById(id);
        var file = file_id.files;
        if (file && file[0]) {
            if (!(file[0].type == 'application/pdf')) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'The file format is not valid! Please upload in pdf format.'
                });
                file_id.value = '';
                return false;
            }

            var file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
            if (!(file_size <= 2)) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Max file size 2MB. You have uploaded ' + file_size + 'MB'
                });
                file_id.value = '';
                return false;
            }
        }

        var inputFile =  $("#" + id).val();
        if(inputFile == ''){
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="'+vField+'" name="'+vField+'">';
            if ($('#label_' + id).length) $('#label_' + id).remove();
            return false;
        }

        try{
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{url('/work-permit-cancellation/upload-document')}}";

            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url:action,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response){
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = parseInt(id.substring(4));
                    var newInput = $('<label class="saved_file_'+doc_id+'" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                        ' <a href="javascript:void(0)" onclick="EmptyFile('+ doc_id
                        +', '+ isRequired +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field != '') {
                        $("#"+id).removeClass('required');
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    function lastWorkPermit(value) {
        if (value == 'yes') {
            $("#ref_app_tracking_no_div").removeClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
            $("#manually_approved_wp_no").removeClass('required');
            $("#applicant_remarks_div").removeClass('hidden');
            $("#expiry_date_of_op_div").removeClass('hidden');
            $("#issue_date_of_last_div").removeClass('hidden');
            $("#applicant_name_div").removeClass('hidden');
            $("#applicant_nationality_div").removeClass('hidden');
            $("#applicant_pass_no_div").removeClass('hidden');
            $("#applicant_position_div").removeClass('hidden');
            $("#date_of_cancellation_div").removeClass('hidden');
        } else if(value == 'no') {
            $("#manually_approved_no_div").removeClass('hidden');
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#manually_approved_wp_no").addClass('required');
            $("#applicant_name_div").removeClass('hidden');
            $("#applicant_nationality_div").removeClass('hidden');
            $("#applicant_pass_no_div").removeClass('hidden');
            $("#applicant_position_div").removeClass('hidden');
            $("#date_of_cancellation_div").removeClass('hidden');
            $("#applicant_remarks_div").removeClass('hidden');
            $("#expiry_date_of_op_div").removeClass('hidden');
            $("#issue_date_of_last_div").removeClass('hidden');
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
            $("#applicant_name_div").addClass('hidden');
            $("#manually_approved_wp_no").removeClass('required');
            $("#applicant_nationality_div").addClass('hidden');
            $("#applicant_pass_no_div").addClass('hidden');
            $("#applicant_position_div").addClass('hidden');
            $("#date_of_cancellation_div").addClass('hidden');
            $("#applicant_remarks_div").addClass('hidden');
            $("#expiry_date_of_op_div").addClass('hidden');
            $("#issue_date_of_last_div").addClass('hidden');
        }
    }

    var sessionLastWP = '{{ $appInfo->last_work_permit }}';
    if(sessionLastWP == 'yes') {
        lastWorkPermit(sessionLastWP);
        $("#ref_app_tracking_no").prop('readonly', true);

        $(".cusReadonly").prop('readonly', true);
//        $(".cusReadonly option:not(:selected)").prop('disabled', true);
//        $(".cusReadonly:radio:not(:checked)").attr('disabled', true);
//        $(".cusReadonlyPhoto").attr('disabled', true);
    } else {
        $("#ex_office_division_id").trigger('change');
        $("#ex_office_district_id").trigger('change');
    }

    $(document).ready(function(){
        getDistrictByDivisionId('ex_office_division_id', {{ $appInfo->ex_office_division_id }}, 'ex_office_district_id',  {{ $appInfo->ex_office_district_id }})

        getThanaByDistrictId('ex_office_district_id', {{ $appInfo->ex_office_district_id }}, 'ex_office_thana_id', {{ $appInfo->ex_office_thana_id }})

        @if ($viewMode != 'on')
        var form = $("#WorkPermitCancelationForm").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if(newIndex == 1){}

                if(newIndex == 2){
                    jQuery.validator.addClassRules("basic_salary_amount", {
                        required: true,
                        min: 0.01
                    });
                }

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex){
                    return true;
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18)
                {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                // form.validate({
                //     rules: {
                //         basic_local_amount: {
                //             min: 0.01
                //         }
                //     }
                // }).settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex != 0) {
                    form.find('#save_as_draft').css('display','block');
                    form.find('.actions').css('top','-42px');
                } else {
                    form.find('#save_as_draft').css('display','none');
                    form.find('.actions').css('top','-15px');
                }

                if(currentIndex == 4) {
                    form.find('#submitForm').css('display','block');

                    $('#submitForm').on('click', function (e) {
                        // form.validate({
                        //     rules: {
                        //         basic_local_amount: {
                        //             min: 0.01
                        //         }
                        //     }
                        // }).settings.ignore = ":disabled";
                        //console.log(form.validate().errors()); // show hidden errors in last step
                        return form.valid();
                    });
                } else {
                    form.find('#submitForm').css('display','none');
                }
            },
            onFinishing: function (event, currentIndex) {
                // form.validate({
                //     rules: {
                //         basic_local_amount: {
                //             min: 0.01
                //         }
                //     }
                // }).settings.ignore = ":disabled";
                // console.log(form.validate());
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        });

        $('#submitForm, #save_as_draft').on('click', function (e) {
            let $submitButton = $(this);
            let buttonId = $submitButton.attr('id');
            if (buttonId == 'submitForm' && !form.valid()) {
                alert('All inputs are not valid! Please fill in all the required fields.');
                return false;
            }
            // Check if the button was already clicked
            if ($submitButton.attr('data-clicked') === 'true') {
                e.preventDefault(); // Prevent double submission
                return false;
            }
            // Mark the button as clicked by setting an attribute
            $submitButton.attr('data-clicked', 'true');
            // Allow form submission to continue
            return true;
        });
        @endif

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/work-permit-cancellation/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        // Bootstrap Tooltip initialize
        $('[data-toggle="tooltip"]').tooltip();

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 150),
            minDate: '01/01/' + (yyyy - 150)
        });

        $('#date_of_cancellation').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 150),
            minDate: '01/01/' + (yyyy - 150)
        });

//        $('.expiry_date_of_op').datetimepicker({
//            viewMode: 'years',
//            format: 'DD-MMM-YYYY',
//            minDate: 'now'
//        });

        $('input[name=last_work_permit]:checked').trigger('click');

        $('input[name="last_work_permit"]').on('change', function() {
            var isOnline = $(this).val() === 'yes';
            var selectFields = $('.readonly-select');
            
            if (isOnline) {
                selectFields.each(function() {
                    var $select = $(this);
                    $select.data('original-value', $select.val());
                    
                    $select.on('mousedown change', function(e) {
                        e.preventDefault();
                        return false;
                    }).css({
                        'background-color': '#eee',
                        'cursor': 'not-allowed'
                    });
                });
            } else {
                selectFields.each(function() {
                    var $select = $(this);
                    $select.off('mousedown change')
                        .css({
                            'background-color': '',
                            'cursor': ''
                        });
                });
            }
        });

        if ($('input[name="last_work_permit"]:checked').val() === 'yes') {
            $('input[name="last_work_permit"]:checked').trigger('change');
        }

    });

    @if ($viewMode == 'on')
    $('#WorkPermitCancelationForm .stepHeader').hide();
    $('#WorkPermitCancelationForm :input').attr('disabled', true);
    $('#WorkPermitCancelationForm').find('.MoreInfo').attr('disabled', false);
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        //console.log($(this)[0]['localName']);
        if($(this)[0]['localName'] == 'select'){
            //var text = $(this).find('option:selected').text();
            //var val = jQuery(this).val();
            //$(this).find('option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");

            // This style will not work in mozila firefox, it's bug in firefox, maybe they will update it in next version
            $(this).attr('style', '-webkit-appearance: button; -moz-appearance: button; -webkit-user-select: none; -moz-user-select: none; text-overflow: ellipsis; white-space: pre-wrap; height: auto;');
        }
        else {
            $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">'+this.value+'</span>');
        }
    });
    $('#WorkPermitCancelationForm :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on
</script>

{{--initail -input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
{{--//textarea count down--}}
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" src="" type="text/javascript"></script>
<script>
    $(function () {
        //max text count down
        $('.maxTextCountDown, #other_benefits').characterCounter();

        $("#ex_office_mobile_no").intlTelInput({
            hiddenInput: ["ex_office_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#ex_office_telephone_no").intlTelInput({
            hiddenInput: ["ex_office_telephone_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#auth_mobile_no").intlTelInput({
            hiddenInput: ["auth_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $(".sfp_contact_phone").intlTelInput({
            hiddenInput: ["sfp_contact_phone"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});
        $("#ex_office_mobile_no").change(function()
        {
            var telInput = $("#ex_office_mobile_no");
            if ($.trim(telInput.val()))
            {
                if (telInput.intlTelInput("isValidNumber"))
                {
                    // console.log(telInput.intlTelInput("getNumber"));
                    $('#valid-msg').removeClass('hidden');
                    $('#error-msg').addClass('hidden');
                }
                else
                {
                    // console.log(telInput.intlTelInput("getValidationError"));
                    $('#error-msg').removeClass('hidden');
                    $('#valid-msg').addClass('hidden');
                }
            }
        });
    });
</script>
{{--initail -input plugin script end--}}


