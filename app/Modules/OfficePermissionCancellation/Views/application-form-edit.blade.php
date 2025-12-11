<?php
$accessMode = ACL::getAccsessRight('OfficePermissionCancellation');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">

<style>
    .form-group{
        margin-bottom: 2px;
    }
    .img-thumbnail{
        height: 80px;
        width: 100px;
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
    .marBott{
        margin-bottom: 100px;
    }
    .headding{
        background: #90D2DE;
        height: 30px;
        width: 800px;
    }
    .panelBorderNone{
        border: none;
    }
    .legfieldPad{
        padding: 10px;
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
                            <h5><b>Application for Office Permission Cancellation</b></h5>
                        </div>
                        <div class="pull-right">
                            @if (isset($appInfo) && $appInfo->status_id == -1)
                                <a href="{{ asset('assets/images/SampleForm/office_permission_cancellation.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                    <i class="fas fa-file-pdf"></i>
                                    Download Sample Form
                                </a>
                            @endif
                            @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                                <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info"
                                   title="Download Approval Copy" target="_blank" rel="noopener"> <i class="fa  fa-file-pdf-o"></i> Download Approval Copy</a>
                            @endif
                            &nbsp;
                            @if(!in_array($appInfo->status_id,[-1,5,6,22]))
                                <a href="/office-permission-cancellation/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank" rel="noopener"
                                   class="btn btn-danger btn-md pull-right">
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

                        {!! Form::open(array('url' => 'office-permission-cancellation/store','method' => 'post','id' => 'OfficePermissionCancelForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />

                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        <input type="hidden" name="ref_app_approve_date" value="{{ !empty($appInfo->ref_app_approve_date) ? date('d-M-Y', strtotime($appInfo->ref_app_approve_date)) : '' }}">

                        <h3 class="stepHeader">Application Information</h3>
                        @if((in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505' && $viewMode == 'on') || (in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404']) && $viewMode == 'on'))
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Cancellation Effect Date</legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('approved_effect_date','Start Date',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <div class="datepicker input-group date">
                                                    {!! Form::text('approved_effect_date', !empty($appInfo->approved_effect_date) ? date('d-M-Y', strtotime($appInfo->approved_effect_date)) : '', ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
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
                        <fieldset>
                            <legend class="d-none">You must re-submit the application</legend>
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
                            <div class="panel panel-info" id="basic_instruction_review">
                                <div class="panel-heading"><strong>Basic instructions</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                {!! Form::label('is_approval_online','Did you receive your Office Permission approval from the online OSS?',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    
                                                    @if($appInfo->is_approval_online == 'yes')
                                                    <label class="radio-inline">{!! Form::radio('is_approval_online','yes', ($appInfo->is_approval_online == 'yes' ? true :false), ['class'=>'cusReadonly required helpTextRadio', 'id' => 'yes', 'onclick' => 'isApprovalOnline(this.value)']) !!}  Yes
                                                    </label>
                                                    @endif

                                                    @if($appInfo->is_approval_online == 'no')
                                                    <label class="radio-inline">{!! Form::radio('is_approval_online', 'no', ($appInfo->is_approval_online == 'no' ? true :false), ['class'=>'cusReadonly required', 'id' => 'no', 'onclick' => 'isApprovalOnline(this.value)']) !!}  No</label>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="row">
                                            @if($appInfo->is_approval_online == 'yes')
                                            <div id="ref_app_tracking_no_div" class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                {!! Form::label('ref_app_tracking_no','Please give your approved office permission reference No.',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        {!! Form::hidden('ref_app_tracking_no', $appInfo->ref_app_tracking_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm', 'readonly']) !!}
                                                        <span class="label label-success" style="font-size: 15px">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                                    @if ($appInfo->is_approval_online == 'yes')
                                                            &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                                        @endif
                                                        <br/>

                                                        @if($viewMode != 'on')
                                                            <small class="text-danger">N.B.: Once you save or submit the application, the Office Permission tracking no cannot be changed anymore.</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @if($appInfo->is_approval_online == 'no')
                                            <div id="manually_approved_no_div" class="col-md-12 hidden {{$errors->has('manually_approved_op_no') ? 'has-error': ''}} ">
                                                {!! Form::label('manually_approved_op_no','Please give your manually approved office permission reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('manually_approved_op_no', $appInfo->manually_approved_op_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('manually_approved_op_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('date_of_office_permission') ? 'has-error': ''}}">
                                                {!! Form::label('date_of_office_permission', 'Date of office permission', ['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date ">
                                                        {!! Form::text('date_of_office_permission', !empty($appInfo->date_of_office_permission) ? date('d-M-Y', strtotime($appInfo->date_of_office_permission)) : '', ['class' => 'required form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'date_of_office_permission']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('date_of_office_permission','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('effect_date') ? 'has-error': ''}}">
                                                {!! Form::label('effect_date', 'Date of cancellation', ['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date ">
                                                        {!! Form::text('effect_date', !empty($appInfo->effect_date) ? date('d-M-Y', strtotime($appInfo->effect_date)) : '', ['class' => 'required form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'effect_date']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('effect_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div id="applicant_remarks_div" class="col-md-12 {{$errors->has('applicant_remarks') ? 'has-error': ''}}">
                                                {!! Form::label('applicant_remarks','Remarks',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('applicant_remarks',$appInfo->applicant_remarks, ['data-rule-maxlength'=>'240', 'placeholder'=>'Remarks', 'class' => 'form-control maxTextCountDown',
                                                    'size'=>'50x2','data-charcount-maxlength' => '200']) !!}
                                                    {!! $errors->first('applicant_remarks','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Application Details</h3>
                        <fieldset>
                            {{--start basic information section--}}
                            @include('OfficePermissionCancellation::basic-info')

                            <div class="panel panel-info" id="office_type_review">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Office Type</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_type') ? 'has-error': ''}}">
                                                    {!! Form::label('office_type','Office Type', ['class' => 'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_type', $officeType, $appInfo->office_type, ['placeholder' => 'Select One',
                                                        'class' => 'form-control input-md cusReadonly readonly-select', 'id' => 'office_type', 'onchange' => "CategoryWiseDocLoad(this.value)"]) !!}
                                                        {!! $errors->first('office_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-heading"><strong>Information about the principal company</strong></div>
                                <div class="panel-body">
                                    <div id="company_info_review">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Company information</legend>

                                            {{-- Company name --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('c_company_name') ? 'has-error': ''}}">
                                                        {!! Form::label('c_company_name','Name of the principal company:',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_company_name', $appInfo->c_company_name, ['class'=>'form-control input-md cusReadonly bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"c_company_name"]) !!}
                                                            {!! $errors->first('c_company_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    {{-- Country & House/ Plot/ Holding No --}}
                                                    <div class="col-md-6 {{$errors->has('c_origin_country_id') ? 'has-error': ''}}">
                                                        {!! Form::label('c_origin_country_id','Country of origin of principal office',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('c_origin_country_id', $countries, $appInfo->c_origin_country_id, ['placeholder' => 'Select One',
                                                            'class' => 'form-control input-md cusReadonly readonly-select']) !!}
                                                            {!! $errors->first('c_origin_country_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Country & House/ Plot/ Holding No --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    {{--<div class="col-md-6 {{$errors->has('c_country_id') ? 'has-error': ''}}">--}}
                                                    {{--{!! Form::label('c_country_id','Country',['class'=>'col-md-5 text-left required-star']) !!}--}}
                                                    {{--<div class="col-md-7">--}}
                                                    {{--{!! Form::select('c_country_id', $countries, $appInfo->c_country_id, ['placeholder' => 'Select One',--}}
                                                    {{--'class' => 'form-control input-md required cusReadonly', 'id' => 'c_country_id']) !!}--}}
                                                    {{--{!! $errors->first('c_country_id','<span class="help-block">:message</span>') !!}--}}
                                                    {{--</div>--}}
                                                    {{--</div>--}}
                                                    <div class="col-md-6 {{$errors->has('c_org_type') ? 'has-error': ''}}">
                                                        {!! Form::label('c_org_type','Type of the organization',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('c_org_type', $organizationTypes, $appInfo->c_org_type,['class' => 'form-control input-md readonly-select','placeholder' => 'Select One', 'id' => 'c_org_type']) !!}
                                                            {!! $errors->first('c_org_type','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 has-feedback {{ $errors->has('c_flat_apart_floor') ? 'has-error' : ''}}">
                                                        {!! Form::label('c_flat_apart_floor','Flat/ Apartment/ Floor no.',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_flat_apart_floor', $appInfo->c_flat_apart_floor, ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'40', 'id'=>"c_flat_apart_floor"]) !!}
                                                            {!! $errors->first('c_flat_apart_floor','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Flat &  Street --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('c_house_plot_holding') ? 'has-error': ''}}">
                                                        {!! Form::label('c_house_plot_holding','House/ Plot/ Holding no.',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_house_plot_holding', $appInfo->c_house_plot_holding, ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'40', 'id'=>"c_house_plot_holding"]) !!}
                                                            {!! $errors->first('c_house_plot_holding','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6  {{$errors->has('c_post_zip_code') ? 'has-error': ''}}">
                                                        {!! Form::label('c_post_zip_code','Post/ Zip Code',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_post_zip_code', $appInfo->c_post_zip_code, ['data-rule-maxlength'=>'80',
                                                            'class' => 'form-control input-md cusReadonly', 'id' => 'c_post_zip_code']) !!}
                                                            {!! $errors->first('c_post_zip_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Telephone and fax --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 has-feedback {{ $errors->has('c_street') ? 'has-error' : ''}}">
                                                        {!! Form::label('c_street','Street Name/ Street no.',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_street', $appInfo->c_street, ['class'=>'form-control input-md cusReadonly','data-rule-maxlength'=>'40', 'id' => 'c_street']) !!}
                                                            {!! $errors->first('c_street','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('c_email') ? 'has-error': ''}}">
                                                        {!! Form::label('c_email','Email',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_email', $appInfo->c_email, ['class' => 'form-control input-md email cusReadonly', 'id' => 'c_email']) !!}
                                                            {!! $errors->first('c_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- State and city --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('c_city') ? 'has-error': ''}}">
                                                        {!! Form::label('c_city','City',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_city', $appInfo->c_city,['class' => 'form-control input-md cusReadonly', 'id' => 'c_city']) !!}
                                                            {!! $errors->first('c_city','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6  {{$errors->has('c_telephone') ? 'has-error': ''}}">
                                                        {!! Form::label('c_telephone','Telephone no.',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_telephone', $appInfo->c_telephone,['data-rule-maxlength'=>'20',  'class' => 'form-control input-md cusReadonly', 'id' => 'c_telephone']) !!}
                                                            {!! $errors->first('c_telephone','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Email and Post --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('c_state_province') ? 'has-error': ''}}">
                                                        {!! Form::label('c_state_province','State/ Province',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_state_province', $appInfo->c_state_province,['class' => 'form-control input-md cusReadonly', 'id' => 'c_state_province']) !!}
                                                            {!! $errors->first('c_state_province','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('c_fax') ? 'has-error': ''}}">
                                                        {!! Form::label('c_fax','Fax No.',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('c_fax', $appInfo->c_fax, ['class' => 'form-control input-md cusReadonly', 'id' => 'c_fax']) !!}
                                                            {!! $errors->first('c_fax','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Major Activities --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('c_major_activity_brief') ? 'has-error': ''}}">
                                                        <div class="col-md-3" style="width: 20.2%">
                                                            {!! Form::label('c_major_activity_brief','Major activities in brief',['class'=>'text-left']) !!}
                                                        </div>
                                                        <div class="col-md-9" style="width: 79.8%">
                                                            {!! Form::textarea('c_major_activity_brief', $appInfo->c_major_activity_brief,['class' => 'bigInputField form-control input-md cusReadonly maxTextCountDown', 'size'=>'1x2', 'id' => 'c_major_activity_brief', 'data-charcount-maxlength'=>'200']) !!}
                                                            {!! $errors->first('c_major_activity_brief','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    {{-- Capital of principal company --}}
                                    <div id="capital_of_company_review">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">The capital of the principal company (in US $)</legend>
                                            {{-- Authorized and paid capital --}}
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('authorized_capital') ? 'has-error': ''}}">
                                                        {!! Form::label('authorized_capital','(i) Authorized capital',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('authorized_capital', $appInfo->authorized_capital,['class' => 'form-control number input-md cusReadonly', 'id'=>'authorized_capital']) !!}
                                                            {!! $errors->first('authorized_capital','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('paid_up_capital') ? 'has-error': ''}}">
                                                        {!! Form::label('paid_up_capital','(ii) Paid up capital',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('paid_up_capital', $appInfo->paid_up_capital,['class' => 'form-control number input-md cusReadonly', 'id' => 'paid_up_capital']) !!}
                                                            {!! $errors->first('paid_up_capital','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Information about the proposed branch/ liaison/ representative office</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('local_company_name') ? 'has-error': ''}}">
                                                {!! Form::label('local_company_name','Name of the Local company:',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('local_company_name', $appInfo->local_company_name, ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name"]) !!}
                                                    {!! $errors->first('local_company_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                    <div class="form-group">--}}
                                    {{--                                        <div class="row">--}}
                                    {{--                                            <div class="col-md-12 {{$errors->has('local_company_name_bn') ? 'has-error': ''}}">--}}
                                    {{--                                                {!! Form::label('local_company_name_bn','Name of the Local company(Bangla):',['class'=>'text-left  col-md-5']) !!}--}}
                                    {{--                                                <div class="col-md-7">--}}
                                    {{--                                                    {!! Form::text('local_company_name_bn', $appInfo->local_company_name_bn, ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name_bn"]) !!}--}}
                                    {{--                                                    {!! $errors->first('local_company_name_bn','<span class="help-block">:message</span>') !!}--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                    <div id="local_address_review">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Local address of the principal company: (Bangladesh only)</legend>
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
                                                            {!! Form::select('ex_office_district_id', $district_eng, $appInfo->ex_office_district_id, ['class' => 'form-control cusReadonly input-md required readonly-select','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id', ". $appInfo->ex_office_thana_id .")"]) !!}
                                                            {!! $errors->first('ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_thana_id','Police station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ex_office_thana_id', $thana_eng, $appInfo->ex_office_thana_id, ['class' => 'form-control cusReadonly input-md required readonly-select']) !!}
                                                            {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_post_office') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_post_office','Post office',['class'=>'col-md-5 text-left']) !!}
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
                                                        {!! Form::label('ex_office_post_code','Post code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_post_code', $appInfo->ex_office_post_code, ['class' => 'form-control cusReadonly input-md post_code_bd required']) !!}
                                                            {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_address') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_address', $appInfo->ex_office_address, ['maxlength'=>'150','class' => 'form-control cusReadonly input-md required']) !!}
                                                            {!! $errors->first('ex_office_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_telephone_no','Telephone no.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_telephone_no', $appInfo->ex_office_telephone_no, ['maxlength'=>'20','class' => 'form-control cusReadonly input-md phone_or_mobile']) !!}
                                                            {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_mobile_no', 'Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_mobile_no',$appInfo->ex_office_mobile_no, ['class' => 'form-control cusReadonly input-md helpText15 required' ,'id' => 'ex_office_mobile_no']) !!}
                                                            {!! $errors->first('ex_office_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ex_office_fax_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_fax_no','Fax no. ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_fax_no', $appInfo->ex_office_fax_no, ['class' => 'form-control cusReadonly input-md']) !!}
                                                            {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ex_office_email') ? 'has-error': ''}}">
                                                        {!! Form::label('ex_office_email','Email ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ex_office_email', $appInfo->ex_office_email, ['class' => 'form-control cusReadonly email input-md']) !!}
                                                            {!! $errors->first('ex_office_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div id="activities_in_bd_review">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Activities in Bangladesh</legend>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('activities_in_bd') ? 'has-error': ''}}" id="courtesy_service_reason_div">
                                                        {!! Form::label('activities_in_bd','Activities in Bangladesh through the proposed branch/ liaison/ representative office (Max. 250 characters )',['class'=>'col-md-6 text-left']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::textarea('activities_in_bd', $appInfo->activities_in_bd, ['data-rule-maxlength'=>'250', 'id' => 'activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control bigInputField input-md cusReadonly maxTextCountDown',
                                                            'size'=>'10x3', 'data-charcount-maxlength'=>'250']) !!}
                                                            {!! $errors->first('activities_in_bd','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('first_commencement_date') ? 'has-error': ''}}">
                                                        {!! Form::label('first_commencement_date','Date of first commencement:',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            <div class="datepicker input-group date ">
                                                                {!! Form::text('first_commencement_date', !empty($appInfo->first_commencement_date) ? date('d-M-Y', strtotime($appInfo->first_commencement_date)) : '', ['class' => 'form-control input-md cusReadonly date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'first_commencement_date']) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('first_commencement_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('operation_target_date') ? 'has-error': ''}}">
                                                        {!! Form::label('operation_target_date','Target date of operation of the proposed office:',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            <div class="datepicker input-group date ">
                                                                {!! Form::text('operation_target_date', !empty($appInfo->operation_target_date)  ? date('d-M-Y', strtotime($appInfo->operation_target_date)) : '', ['class' => 'form-control input-md cusReadonly date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'operation_target_date']) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('operation_target_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div id="period_of_permission_review">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Period for which permission is sought for</legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4 {{$errors->has('period_start_date') ? 'has-error': ''}}">
                                                        {!! Form::label('period_start_date','Start and effective date:',['class'=>'text-left col-md-12']) !!}
                                                        <div class="col-md-12">
                                                            <div class="period_start_date datepicker input-group date ">
                                                                {!! Form::text('period_start_date', !empty($appInfo->period_start_date) ? date('d-M-Y', strtotime($appInfo->period_start_date)) : '', ['class' => 'form-control input-md cusReadonly date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'period_start_date']) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('period_start_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 {{$errors->has('period_end_date') ? 'has-error': ''}}">
                                                        {!! Form::label('period_end_date','End date',['class'=>'text-left col-md-12']) !!}
                                                        <div class="col-md-12">
                                                            <div class="period_end_date datepicker input-group date ">
                                                                {!! Form::text('period_end_date', !empty($appInfo->period_end_date) ? date('d-M-Y', strtotime($appInfo->period_end_date)) : '', ['class' => 'form-control input-md cusReadonly date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'period_end_date']) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('period_end_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 {{$errors->has('period_validity') ? 'has-error': ''}}">
                                                        {!! Form::label('period_validity','Period of validity',['class'=>'col-md-12 text-left']) !!}
                                                        <div class="col-md-12">
                                                            {!! Form::text('period_validity', $appInfo->period_validity, ['class' => 'form-control input-md cusReadonly', 'id' => 'period_validity']) !!}
                                                            {!! $errors->first('period_validity','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="col-md-12" style="padding:0">
                                            <div class="table-responsive" id="organizational_set_up_review">
                                                <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead class="alert alert-info">
                                                    <tr>
                                                        <th scope="col" class="text-center text-title required-star" colspan="9">Proposed organizational set up of the office with expatriate and local man power ratio</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="alert alert-info text-center" colspan="3">Local (a)</th>
                                                        <th scope="col" class="alert alert-info text-center" colspan="3">Foreign (b)</th>
                                                        <th scope="col" class="alert alert-info text-center" colspan="1">Grand total</th>
                                                        <th scope="col" class="alert alert-info text-center" colspan="2">Ratio</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="manpower">
                                                    <tr>
                                                        <th scope="col" class="alert alert-info text-center">Executive</th>
                                                        <th scope="col" class="alert alert-info text-center">Supporting staff</th>
                                                        <th scope="col" class="alert alert-info text-center">Total</th>
                                                        <th scope="col" class="alert alert-info text-center">Executive</th>
                                                        <th scope="col" class="alert alert-info text-center">Supporting staff</th>
                                                        <th scope="col" class="alert alert-info text-center">Total</th>
                                                        <th scope="col" class="alert alert-info text-center"> (a+b)</th>
                                                        <th scope="col" class="alert alert-info text-center">Local</th>
                                                        <th scope="col" class="alert alert-info text-center">Foreign</th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            {!! Form::text('local_executive', $appInfo->local_executive, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number','id'=>'local_executive']) !!}
                                                            {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_stuff', $appInfo->local_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number','id'=>'local_stuff']) !!}
                                                            {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_total', $appInfo->local_total, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative','id'=>'local_total','readonly']) !!}
                                                            {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_executive', $appInfo->foreign_executive, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number','id'=>'foreign_executive']) !!}
                                                            {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_stuff', $appInfo->foreign_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number','id'=>'foreign_stuff']) !!}
                                                            {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_total', $appInfo->foreign_total, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative','id'=>'foreign_total','readonly']) !!}
                                                            {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_total', $appInfo->manpower_total, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number','id'=>'mp_total','readonly']) !!}
                                                            {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_local_ratio', $appInfo->manpower_local_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number','id'=>'mp_ratio_local','readonly']) !!}
                                                            {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_foreign_ratio', $appInfo->manpower_foreign_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number','id'=>'mp_ratio_foreign','readonly']) !!}
                                                            {!! $errors->first('manpower_foreign_ratio','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="expenses_review">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Establishment expenses and operational expenses of the office (in US Dollar)</legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('est_initial_expenses') ? 'has-error': ''}}">
                                                        {!! Form::label('est_initial_expenses','(a) Estimated initial expenses :',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('est_initial_expenses', $appInfo->est_initial_expenses,['class' => 'form-control input-md number cusReadonly', 'id' => 'est_initial_expenses']) !!}
                                                            {!! $errors->first('est_initial_expenses','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('est_monthly_expenses') ? 'has-error': ''}}">
                                                        {!! Form::label('est_monthly_expenses','(b) Estimated monthly expenses:',['class'=>'text-left col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('est_monthly_expenses', $appInfo->est_monthly_expenses,['class' => 'form-control input-md number cusReadonly', 'id' => 'est_monthly_expenses']) !!}
                                                            {!! $errors->first('est_monthly_expenses','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="text-center stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv">
                            </div>
                            @if($viewMode != 'off')
                                @include('OfficePermissionCancellation::doc-tab')
                            @endif
                        </fieldset>

                        <h3 class="stepHeader">Declaration</h3>
                        <fieldset>
                            <div class="panel panel-info" id="declaration_review">
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
                                                            {!! Form::text('auth_full_name', $appInfo->auth_full_name, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', $appInfo->auth_designation, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'form-control input-sm phone_or_mobile required', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', $appInfo->auth_email, ['class' => 'form-control input-sm email required', 'readonly']) !!}
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
                                                {!! Form::checkbox('accept_terms', 1, ($appInfo->accept_terms == 1) ? true : false, array('id'=>'accept_terms', 'class'=>'required')) !!}
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
                                                        {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md email required']) !!}
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
                                                        {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control input-md sfp_contact_phone phone_or_mobile required']) !!}
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
                                                    {!! Form::label('sfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_total_amount', number_format($appInfo->sfp_total_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
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
                                                            <strong>Vat/ Tax</strong> and <strong>Transaction charge</strong> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </fieldset>

                        @if(ACL::getAccsessRight('OfficePermissionCancellation','-E-') && $viewMode != "on" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                            
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

                            @if(in_array($appInfo->status_id,[5,22])) {{--22 = Observation by MC --}}
                                <div class="pull-left">
                                    <span style="display: block; height: 34px">&nbsp;</span>
                                </div>
                                <div class="pull-left">
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
    //This function use for section wise shortfall
    // function applicationSectionReadonly(shortfall_readonly_sections) {
    //     shortfall_readonly_sections = JSON.parse(shortfall_readonly_sections);
    //     if (shortfall_readonly_sections.length > 0) {
    //         for (let i = 0; i < shortfall_readonly_sections.length; i++) {
    //             $('#'+ shortfall_readonly_sections[i] +' :input').attr('readonly', true);
    //             $('#'+ shortfall_readonly_sections[i] +' :input[type="file"]').css('pointer-events', 'none');
    //             $('#'+ shortfall_readonly_sections[i] +' select').css('pointer-events', 'none');
    //             $('#'+ shortfall_readonly_sections[i] +' .checkbox').css('pointer-events', 'none');
    //             $('#'+ shortfall_readonly_sections[i] +' .checkbox-inline').css('pointer-events', 'none');
    //             $('#'+ shortfall_readonly_sections[i] +' :radio:not(:checked)').attr('disabled', true);
    //
    //             if (shortfall_readonly_sections[i] == 'attachment_review') {
    //                 $('#'+ shortfall_readonly_sections[i] +' :input[type="file"]').remove();
    //                 $('#'+ shortfall_readonly_sections[i] +' .recent_attachment_btn').remove();
    //                 $('#'+ shortfall_readonly_sections[i] +' .attachment_remove_btn').remove();
    //             } else {
    //                 $('#'+ shortfall_readonly_sections[i] +' a').css('pointer-events', 'none');
    //                 $('#'+ shortfall_readonly_sections[i] +' .btn').addClass('disabled');
    //             }
    //         }
    //     }
    // }

    function CategoryWiseDocLoad(office_type) {

        var attachment_key = "opc_";
        if (office_type == 1) {
            attachment_key += "branch";
        } else if (office_type == 2) {
            attachment_key += "liaison";
        } else {
            attachment_key += "representative";
        }

        if(office_type != 0 && office_type != ''){
            var _token = $('input[name="_token"]').val();
            var app_id = $("#app_id").val();
            var viewMode = $("#viewMode").val();

            $.ajax({
                type: "POST",
                url: '/office-permission-cancellation/getDocList',
                dataType: "json",
                data: {_token : _token, attachment_key : attachment_key, app_id:app_id, viewMode:viewMode},
                success: function(result) {
                    if (result.html != undefined) {
                        $('#docListDiv').html(result.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //console.log(errorThrown);
                    alert('Unknown error occured. Please, try again after reload');
                },
            });
        }else{
            //console.log('Unknown Visa Type');
        }
    }

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
            var action = "{{url('/office-permission-cancellation/upload-document')}}";

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

    function isApprovalOnline(value) {
        if (value == 'yes') {
            $("#ref_app_tracking_no_div").removeClass('hidden');
            $("#ref_app_tracking_no").addClass('required');
            $("#manually_approved_no_div").addClass('hidden');
            $("#manually_approved_op_no").removeClass('required');
        } else if(value == 'no') {
            $("#manually_approved_no_div").removeClass('hidden');
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
            $("#manually_approved_op_no").addClass('required');
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
            $("#manually_approved_op_no").removeClass('required');
        }
    }

    var sessionLastOPN = '{{ $appInfo->is_approval_online }}';
    if(sessionLastOPN == 'yes') {
        isApprovalOnline(sessionLastOPN);
        $("#ref_app_tracking_no").prop('readonly', true);

        $(".cusReadonly").prop('readonly', true);
//        $(".cusReadonly option:not(:selected)").prop('disabled', true);
//        $(".cusReadonly:radio:not(:checked)").attr('disabled', true);
    } else {
        $("#ex_office_division_id").trigger('change');
        $("#ex_office_district_id").trigger('change');
    }

    $(document).ready(function(){

        $('#office_type').trigger('change');

        @if ($viewMode != 'on')
        var form = $("#OfficePermissionCancelForm").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                if(newIndex == 1){}

                if(newIndex == 2){}

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
                form.validate().settings.ignore = ":disabled,:hidden";
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
                        form.validate().settings.ignore = ":disabled";
                        //console.log(form.validate().errors()); // show hidden errors in last step
                        return form.valid();
                    });
                } else {
                    form.find('#submitForm').css('display','none');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
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
                popupWindow = window.open('<?php echo URL::to('/office-permission-cancellation/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

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

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/'+(yyyy-47),
            maxDate: 'now'
        });

        $('#manpower').find('input').keyup(function(){
            var local_executive = $('#local_executive').val()?parseFloat($('#local_executive').val()):0;
            var local_stuff = $('#local_stuff').val()?parseFloat($('#local_stuff').val()):0;
            var local_total = parseInt(local_executive+local_stuff);
            $('#local_total').val(local_total);


            var foreign_executive = $('#foreign_executive').val()?parseFloat($('#foreign_executive').val()):0;
            var foreign_stuff = $('#foreign_stuff').val()?parseFloat($('#foreign_stuff').val()):0;
            var foreign_total = parseInt(foreign_executive+foreign_stuff);
            $('#foreign_total').val(foreign_total);

            var mp_total = parseInt(local_total+foreign_total);
            $('#mp_total').val(mp_total);

            var mp_ratio_local = parseFloat(local_total/mp_total);
            var mp_ratio_foreign = parseFloat(foreign_total/mp_total);

//            mp_ratio_local = Number((mp_ratio_local).toFixed(3));
//            mp_ratio_foreign = Number((mp_ratio_foreign).toFixed(3));

            //---------- code from bida old
            mp_ratio_local = ((local_total/mp_total)*100).toFixed(2);
            mp_ratio_foreign = ((foreign_total/mp_total)*100).toFixed(2);
            if (foreign_total == 0) {
                mp_ratio_local = local_total;
            } else {
                mp_ratio_local = Math.round(parseFloat(local_total / foreign_total)*100)/100;
            }
            mp_ratio_foreign = (foreign_total != 0) ? 1 : 0;
            // End of code from bida old -------------

            $('#mp_ratio_local').val(mp_ratio_local);
            $('#mp_ratio_foreign').val(mp_ratio_foreign);
        });

        // Trigger
        $('input[name=is_approval_online]:checked').trigger('click');
        $("#local_c_city_district").trigger('change');

        // Handler for is_approval_online radio change
        $('input[name="is_approval_online"]').on('change', function() {
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

        if ($('input[name="is_approval_online"]:checked').val() === 'yes') {
            $('input[name="is_approval_online"]:checked').trigger('change');
        }


    });

    @if ($viewMode == 'on')
    $('#OfficePermissionCancelForm .stepHeader').hide();
    $('#OfficePermissionCancelForm :input').attr('disabled', true);
    $('#OfficePermissionCancelForm').find('.MoreInfo').attr('disabled', false);
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
    $('#OfficePermissionCancelForm :input[type=file]').hide();
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
        $('.maxTextCountDown').characterCounter();

        $("#ex_office_mobile_no").intlTelInput({
            hiddenInput: ["ex_office_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#ex_office_telephone_no").intlTelInput({
            hiddenInput: ["ex_office_telephone_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#c_telephone").intlTelInput({
            hiddenInput: "c_telephone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#auth_mobile_no").intlTelInput({
            hiddenInput: "auth_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $(".sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});
    });

    // Office type will not be changed when application is resubmitted
    var status_id = '{{ $appInfo->status_id }}';
    if (status_id == 5){ // 5 = shortfall
        $('#office_type').attr("readonly", "readonly");
        $('#office_type option:not(:selected)').remove();
    }
</script>
{{--initail -input plugin script end--}}