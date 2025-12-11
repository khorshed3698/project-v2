<?php
$accessMode = ACL::getAccsessRight('WaiverCondition8');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}


?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ asset("vendor/datepicker/datepicker.min.css") }}">

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
    .blink_me {
        animation: blinker 5s linear infinite;
    }

    .width_14_percent{
        width: 14%;
    }

    .cusReadonly{
        pointer-events: none;
    }

    #office_type{
        pointer-events: none;
    }

    .usd-def{
        pointer-events: none;
    }

    .font_12{
        font-size: 12px;
    }

    .float-left{
        float: left;
    }
    .margin_left_110{
        margin-left: 110px !important;
    }
    .margin_left_10{
        margin-left: 10px !important;
    }
    .margin_left_40{
        margin-left: 40px !important;
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
                            <h5><b>Application for Waiver</b></h5>
                        </div>
                        <div class="pull-right">
                            @if(in_array($appInfo->status_id,[5,6]))
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        
                        {!! Form::open(array('url' => 'waiver-condition-8/store','method' => 'post','id' => 'WaiverForm8','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />

                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        {{-- <input type="hidden" name="ref_app_approve_date" value="{{ empty($appInfo->ref_app_approve_date) ? '' : date('d-M-Y', strtotime($appInfo->ref_app_approve_date)) }}"> --}}

                        <h3 class="stepHeader">Application Info.</h3>
                        <fieldset>
                            <legend class="d-none">Application Info.</legend>
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
                                <div class="panel-heading"><strong>Basic information </strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">

                                            <div class="form-group">
                                                <div class="row">

                                                    <div id="ref_app_tracking_no_div" class="col-md-12  {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ref_app_tracking_no','Please provide your Waiver Condition 7 permission tracking no :',['class'=>'col-md-6 text-left required-star']) !!}
                                                        <div class="col-md-6">
                                                            <input type="hidden" name="ref_app_tracking_no" value="{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}">

                                                                    <span class="label label-success font_12" >{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                                            &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                                            @if($viewMode != 'on')
                                                                <br>
                                                                <small class="text-danger">N.B.: Once you save or submit the application, the Office Permission tracking no cannot be changed anymore.</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    {!! Form::label('ref_app_approve_date','Approved Date', ['class'=>'col-md-6 text-left required-star']) !!}
                                                    <div class="col-md-6">  

                                                            {!! Form::text('ref_app_approve_date', (!empty($appInfo->ref_app_approve_date)) ? date('d-M-Y',strtotime($appInfo->ref_app_approve_date)) : '', ['class'=>'form-control input-md', 'readonly']) !!}

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Office Info.</h3>
                        <fieldset>
                            {{--start basic information section--}}
                            @include('WaiverCondition8::basic-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Office Info</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ref_office_app_tracking_no') ? 'has-error': ''}}">
                                                {!! Form::label('ref_office_app_tracking_no','Approved office permission reference no.',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ref_office_app_tracking_no', $appInfo->ref_office_app_tracking_no, ['class' => 'form-control input-md', 'readonly']) !!}
                                                    {!! $errors->first('ref_office_app_tracking_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                {!! Form::label('ref_office_app_approved_date','Approved date',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('ref_office_app_approved_date', empty($appInfo->ref_office_app_approved_date) ? '' : date('d-M-Y', strtotime($appInfo->ref_office_app_approved_date)), ['class' => 'form-control input-md date',  'placeholder'=>'dd-mm-yyyy', 'readonly']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_type') ? 'has-error': ''}}">
                                                {!! Form::label('office_type','Office type', ['class' => 'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('office_type', $officeType, $appInfo->office_type, ['placeholder' => 'Select One',
                                                    'class' => 'form-control input-md cusReadonly', 'id' => 'office_type', 'onchange' => "CategoryWiseDocLoad(this.value)",'readonly']) !!}
                                                    {!! $errors->first('office_type','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Approved Permission Period</legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('approved_permission_start_date','Start date',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('approved_permission_start_date', empty($appInfo->approved_permission_start_date) ? '' : date('d-M-Y', strtotime($appInfo->approved_permission_start_date)), ['class' => 'form-control input-md date',  'placeholder'=>'dd-mm-yyyy', 'readonly']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('approved_permission_end_date','End date',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('approved_permission_end_date', empty($appInfo->approved_permission_end_date) ? '' : date('d-M-Y', strtotime($appInfo->approved_permission_end_date)), ['class' => 'form-control input-md date',  'placeholder'=>'dd-mm-yyyy', 'readonly']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('approved_permission_duration','Duration',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('approved_permission_duration', $appInfo->approved_permission_duration, ['class' => 'form-control input-md', 'readonly']) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('approved_permission_duration_amount','Payable amount',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('approved_permission_duration_amount', $appInfo->approved_permission_duration_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Information about the principal company</strong></div>
                                <div class="panel-body">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Company information</legend>

                                        {{-- Company name --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('c_company_name') ? 'has-error': ''}}">
                                                    {!! Form::label('c_company_name','Name of the principal company:',['class'=>'text-left  col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_company_name', $appInfo->c_company_name, ['class'=>'form-control input-md cusReadonly required', 'data-rule-maxlength'=>'255', 'id'=>"c_company_name", 'readonly']) !!}
                                                        {!! $errors->first('c_company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                {{-- Country & House/ Plot/ Holding No --}}
                                                <div class="col-md-6 {{$errors->has('c_origin_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('c_origin_country_id','Country of origin of principal office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('c_origin_country_id', $countries, $appInfo->c_origin_country_id, ['placeholder' => 'Select One',
                                                        'class' => 'form-control input-md cusReadonly', 'readonly']) !!}
                                                        {!! $errors->first('c_origin_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Country & House/ Plot/ Holding No --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('c_org_type') ? 'has-error': ''}}">
                                                    {!! Form::label('c_org_type','Type of the organization',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('c_org_type', $organizationTypes, $appInfo->c_org_type,['class' => 'form-control cusReadonly input-md','placeholder' => 'Select One', 'id' => 'c_org_type', 'readonly']) !!}
                                                        {!! $errors->first('c_org_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 has-feedback {{ $errors->has('c_flat_apart_floor') ? 'has-error' : ''}}">
                                                    {!! Form::label('c_flat_apart_floor','Flat/ Apartment/ Floor no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_flat_apart_floor', $appInfo->c_flat_apart_floor, ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'40', 'id'=>"c_flat_apart_floor", 'readonly']) !!}
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
                                                        {!! Form::text('c_house_plot_holding', $appInfo->c_house_plot_holding, ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'40', 'id'=>"c_house_plot_holding", 'readonly']) !!}
                                                        {!! $errors->first('c_house_plot_holding','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('c_post_zip_code') ? 'has-error': ''}}">
                                                    {!! Form::label('c_post_zip_code','Post/ Zip code',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_post_zip_code', $appInfo->c_post_zip_code, ['data-rule-maxlength'=>'80',
                                                        'class' => 'form-control input-md cusReadonly', 'id' => 'c_post_zip_code', 'readonly']) !!}
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
                                                        {!! Form::text('c_street', $appInfo->c_street, ['class'=>'form-control input-md cusReadonly','data-rule-maxlength'=>'40', 'id' => 'c_street', 'readonly']) !!}
                                                        {!! $errors->first('c_street','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('c_email') ? 'has-error': ''}}">
                                                    {!! Form::label('c_email','Email',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_email', $appInfo->c_email, ['class' => 'form-control input-md email cusReadonly', 'id' => 'c_email', 'readonly']) !!}
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
                                                        {!! Form::text('c_city', $appInfo->c_city,['class' => 'form-control input-md cusReadonly', 'id' => 'c_city', 'readonly']) !!}
                                                        {!! $errors->first('c_city','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('c_telephone') ? 'has-error': ''}}">
                                                    {!! Form::label('c_telephone','Telephone no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_telephone', $appInfo->c_telephone,['data-rule-maxlength'=>'20',  'class' => 'form-control input-md cusReadonly', 'id' => 'c_telephone','readonly']) !!}
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
                                                        {!! Form::text('c_state_province', $appInfo->c_state_province,['class' => 'form-control input-md cusReadonly', 'id' => 'c_state_province', 'readonly']) !!}
                                                        {!! $errors->first('c_state_province','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('c_fax') ? 'has-error': ''}}">
                                                    {!! Form::label('c_fax','Fax no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_fax', $appInfo->c_fax, ['class' => 'form-control input-md cusReadonly', 'id' => 'c_fax', 'readonly']) !!}
                                                        {!! $errors->first('c_fax','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Major Activities --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('c_major_activity_brief') ? 'has-error': ''}}">
                                                    <div class="col-md-3" style="width: 20.2%;">
                                                        {!! Form::label('c_major_activity_brief','Major activities in brief',['class'=>'text-left']) !!}
                                                    </div>
                                                    <div class="col-md-7" style="width:79.8%;">
                                                        {!! Form::textarea('c_major_activity_brief', $appInfo->c_major_activity_brief,['class' => 'bigInputField form-control input-md cusReadonly maxTextCountDown', 'size'=>'1x2', 'id' => 'c_major_activity_brief','data-charcount-maxlength'=>'200', 'readonly']) !!}
                                                        {!! $errors->first('c_major_activity_brief','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Information about the proposed branch/ liaison/ representative office</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('local_company_name') ? 'has-error': ''}}">
                                                {!! Form::label('local_company_name','Name of the local company:',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('local_company_name', $appInfo->local_company_name, ['class'=>'form-control input-md cusReadonly bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name", 'readonly']) !!}
                                                    {!! $errors->first('local_company_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('local_company_name_bn') ? 'has-error': ''}}">
                                                {!! Form::label('local_company_name_bn','Name of the local company(Bangla):',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('local_company_name_bn', $appInfo->local_company_name_bn, ['class'=>'form-control input-md cusReadonly bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name_bn", 'readonly']) !!}
                                                    {!! $errors->first('local_company_name_bn','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Local address of the principal company: (Bangladesh only)</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_division_id','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_division_id', $divisions, $appInfo->ex_office_division_id, ['class' => 'form-control cusReadonly required input-md', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id', ". $appInfo->ex_office_district_id .")", 'readonly']) !!}
                                                        {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_district_id', $district_eng, $appInfo->ex_office_district_id, ['class' => 'form-control required cusReadonly input-md','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id', ". $appInfo->ex_office_thana_id .")", 'readonly']) !!}
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
                                                        {!! Form::select('ex_office_thana_id', $thana_eng, $appInfo->ex_office_thana_id, ['class' => 'form-control required cusReadonly input-md', 'readonly']) !!}
                                                        {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_post_office','Post office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_post_office', $appInfo->ex_office_post_office, ['class' => 'form-control cusReadonly input-md', 'readonly']) !!}
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
                                                        {!! Form::text('ex_office_post_code', $appInfo->ex_office_post_code, ['class' => 'form-control required cusReadonly input-md post_code_bd', 'readonly']) !!}
                                                        {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_address', $appInfo->ex_office_address, ['maxlength'=>'150','class' => 'form-control required cusReadonly input-md', 'readonly']) !!}
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
                                                        {!! Form::text('ex_office_telephone_no', $appInfo->ex_office_telephone_no, ['maxlength'=>'20','class' => 'form-control cusReadonly input-md phone_or_mobile', 'readonly'=>"readonly"]) !!}
                                                        {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_mobile_no', 'Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_mobile_no',$appInfo->ex_office_mobile_no, ['class' => 'form-control required cusReadonly input-md helpText15' ,'id' => 'ex_office_mobile_no', 'readonly'=>"readonly"]) !!}
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
                                                        {!! Form::text('ex_office_fax_no', $appInfo->ex_office_fax_no, ['class' => 'form-control cusReadonly input-md', 'readonly']) !!}
                                                        {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_email', $appInfo->ex_office_email, ['class' => 'form-control required cusReadonly input-md', 'readonly']) !!}
                                                        {!! $errors->first('ex_office_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Activities in Bangladesh</legend>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('activities_in_bd') ? 'has-error': ''}}" id="courtesy_service_reason_div">
                                                    {!! Form::label('activities_in_bd','Activities in Bangladesh through the proposed branch/ liaison/ representative office (Max. 250 characters )',['class'=>'col-md-6 text-left']) !!}
                                                    <div class="col-md-6">
                                                        {!! Form::textarea('activities_in_bd', $appInfo->activities_in_bd, ['data-rule-maxlength'=>'250', 'id' => 'activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control bigInputField input-md maxTextCountDown cusReadonly',
                                                        'size'=>'10x3','data-charcount-maxlength'=>'250', 'readonly']) !!}
                                                        {!! $errors->first('activities_in_bd','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>Financial Statements</strong>
                                        </div>
                                        <div class="panel-body">
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Comprehensive income for the Period</legend>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('comprehensive_income_start_date') ? 'has-error': ''}}">
                                                            {!! Form::label('comprehensive_income_start_date','Start date',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                <div id="duration_start_datepicker" class="required input-group date">
                                                                    {!! Form::text('comprehensive_income_start_date', empty($appInfo->comprehensive_income_start_date) ? '' : date('d-M-Y', strtotime($appInfo->comprehensive_income_start_date)), ['class' => 'form-control input-md date required', 'placeholder'=>'dd-mm-yyyy', 'id' => 'duration_start_date', 'required','readonly']) !!}
                                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                                </div>
                                                                {!! $errors->first('comprehensive_income_start_date','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('comprehensive_income_end_date') ? 'has-error': ''}}">
                                                            {!! Form::label('comprehensive_income_end_date','End date',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                <div id="duration_end_datepicker" class="required input-group date">
                                                                    {!! Form::text('comprehensive_income_end_date', empty($appInfo->comprehensive_income_end_date) ? '' : date('d-M-Y', strtotime($appInfo->comprehensive_income_end_date)), ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'duration_end_date', 'required','readonly']) !!}
                                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                                </div>
                                                                <span class="text-danger" style="font-size: 12px; font-weight: bold" id="date_compare_error"></span>
                                                                {!! $errors->first('comprehensive_income_end_date','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('comprehensive_income_duration') ? 'has-error': ''}}">
                                                            {!! Form::label('comprehensive_income_duration','Desired duration',['class'=>'text-left  col-md-5']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('comprehensive_income_duration', $appInfo->comprehensive_income_duration, ['class' => 'form-control input-md cusReadonly', 'id' => 'desired_duration', 'readonly']) !!}
                                                                {!! $errors->first('comprehensive_income_duration','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </fieldset>

                                            <div id="investment_review">
                                                <fieldset class="scheduler-border">
                                                    <legend class="scheduler-border">Investment</legend>
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" aria-label="Detailed Investment Report">
                                                            <thead>
                                                                <tr class="d-none">
                                                                    <th aria-hidden="true" scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Total Revenue
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Total Revenue Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('total_revenue', $appInfo->total_revenue, ['class' => 'cusReadonly form-control total_investment_item input-md number','id'=>'total_revenue', 'readonly' ]) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>


                                                                <td class="width_14_percent">
                                                                    Total Expense
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Total Expense Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('total_expense', $appInfo->total_expense, ['class' => 'cusReadonly form-control total_investment_item input-md number','id'=>'total_expense', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Total Comprehensive Income
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Report Data Table">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('total_comprehensive_income', $appInfo->total_comprehensive_income, ['class' => 'cusReadonly form-control input-md number','id'=>'total_comprehensive_income', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{--    end total revenue--}}

                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                                            <thead>
                                                                <tr class="d-none">
                                                                    <th aria-hidden="true" scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Fixed Assets
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Fixed Assets Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('fixed_assets', $appInfo->fixed_assets, ['class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="width_14_percent">
                                                                    Current Assets
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Current Assets Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('current_assets', $appInfo->current_assets, ['class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{--    end fixed assets--}}
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                                            <thead>
                                                                <tr class="d-none">
                                                                    <th aria-hidden="true" scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Bank Balance
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Bank Balance Report ">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('bank_balance', $appInfo->bank_balance, ['data-rule-maxlength'=>'40','class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td>
                                                                    Cash Balance
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Cash Balance Report ">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('cash_balance', $appInfo->cash_balance, ['data-rule-maxlength'=>'40','class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{--    end bank balance --}}
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                                            <thead>
                                                                <tr class="d-none">
                                                                    <th aria-hidden="true" scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Fixed Liabilities
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Fixed Liabilities Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('fixed_liabilities', $appInfo->fixed_liabilities, ['data-rule-maxlength'=>'40','class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td>
                                                                    Current Liabilities
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Current Liabilities Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('current_liabilities', $appInfo->current_liabilities, ['data-rule-maxlength'=>'40','class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{--    end fixed liabilites --}}

                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                                            <thead>
                                                                <tr class="d-none">
                                                                    <th aria-hidden="true" scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Equility
                                                                </td>

                                                                <td>
                                                                    <table aria-label="Detailed Equility Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('equility', $appInfo->equility, ['data-rule-maxlength'=>'40','class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>

                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    {{--    end equility --}}
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                                            <thead>
                                                                <tr class="d-none">
                                                                    <th aria-hidden="true" scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table aria-label="Detailed Accumulated Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                Accumulated

                                                                            </td>
                                                                            <td>
                                                                                {!! Form::radio('profit_loss',"Profit",$appInfo->profit_loss == 'Profit' ? 'Profit' : null , array('id'=>'profit_chk', 'class'=>"float-left margin_left_110",'disabled')) !!}
                                                                                {!! Form::label('profit_chk','Profit',[ 'id'=> 'profit_chk', 'class'=> "float-left margin_left_10"]) !!}
                                                                            <input type="hidden" name="profit_loss" value="{{$appInfo->profit_loss}}">
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::radio('profit_loss',"Loss",$appInfo->profit_loss == 'Loss' ? 'Loss' : null, array('id'=>'loss_chk', 'class'=>"float-left margin_left_40",'disabled')) !!}

                                                                                {!! Form::label('loss_chk','Loss',[ 'id'=> 'loss_chk', 'class'=> "float-left margin_left_10"]) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td class="width_14_percent">
                                                                    Accumulated Profit/Loss
                                                                </td>
                                                                <td>
                                                                    <table aria-label="Detailed Accumulated Profit/Loss Report">
                                                                        <tr>
                                                                            <th aria-hidden="true" scope="col"></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('acc_profit_loss', $appInfo->acc_profit_loss, ['data-rule-maxlength'=>'40','class' => 'cusReadonly form-control input-md number', 'readonly']) !!}
                                                                            </td>

                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def", 'readonly']) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{--    end accumulated profit/loss --}}
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="col-md-12" style="padding:0">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table" width="100%">
                                                    <thead class="alert alert-info">
                                                    <tr>
                                                        <th scope="col" class="text-center text-title required-star" colspan="9">Proposed organizational set up of the office with expatriate and local man power ratio</th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="alert alert-info text-center" colspan="3">Local (a)</th>
                                                        <th scope="col" class="alert alert-info text-center" colspan="3">Foreign (b)</th>
                                                        <th scope="col" class="alert alert-info text-center" colspan="1">Grand Total</th>
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
                                                            {!! Form::text('local_executive', $appInfo->local_executive, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'local_executive', 'readonly']) !!}
                                                            {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_stuff', $appInfo->local_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'local_stuff', 'readonly']) !!}
                                                            {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_total', $appInfo->local_total, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'local_total','readonly']) !!}
                                                            {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_executive', $appInfo->foreign_executive, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'foreign_executive', 'readonly']) !!}
                                                            {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_stuff', $appInfo->foreign_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'foreign_stuff', 'readonly']) !!}
                                                            {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_total', $appInfo->foreign_total, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'foreign_total','readonly']) !!}
                                                            {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_total', $appInfo->manpower_total, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'mp_total','readonly']) !!}
                                                            {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_local_ratio', $appInfo->manpower_local_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'mp_ratio_local','readonly']) !!}
                                                            {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_foreign_ratio', $appInfo->manpower_foreign_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'mp_ratio_foreign','readonly']) !!}
                                                            {!! $errors->first('manpower_foreign_ratio','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Establishment expenses and operational expenses of the office (in US Dollar)</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('est_initial_expenses') ? 'has-error': ''}}">
                                                    {!! Form::label('est_initial_expenses','(a) Estimated initial expenses :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('est_initial_expenses', $appInfo->est_initial_expenses,['class' => 'form-control input-md number', 'id' => 'est_initial_expenses', 'readonly']) !!}
                                                        {!! $errors->first('est_initial_expenses','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('est_monthly_expenses') ? 'has-error': ''}}">
                                                    {!! Form::label('est_monthly_expenses','(b) Estimated monthly expenses:',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('est_monthly_expenses', $appInfo->est_monthly_expenses,['class' => 'form-control input-md number', 'id' => 'est_monthly_expenses', 'readonly']) !!}
                                                        {!! $errors->first('est_monthly_expenses','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            {{--Necessary documents from Waiver Condition 7 to be attached--}}
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Attached documents from waiver condition 7 (PDF file)</strong>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered table-hover" aria-label="Detailed Attachments Report">
                                        <thead>
                                        <tr>
                                            <th width="5%">No.</th>
                                            <th width="60%">Required attachment  <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title="" data-original-title="If your present company has already attached any file while doing application previously, there will be a button as 'recent attachment' after the name of attachment" aria-describedby="tooltip"></i></th>
                                            <th width="35%">Attached PDF file </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @if(count($waiver7Doc) > 0)
                                            @foreach($waiver7Doc as $row)
                                                <tr>
                                                    <td>
                                                        <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                    </td>
                                                    <td>{!!  $row->doc_name !!}</td>
                                                    <td>
                                                        @if(!empty($row->doc_file_path))
                                                            <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                               href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                               title="{{$row->doc_name}}">
                                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                Open File
                                                            </a>
                                                        @else
                                                            No file found
                                                        @endif
                                                    </td>
                                                </tr>
                                                    <?php $i++; ?>
                                            @endforeach
                                        @else
                                            <tr class="text-center">
                                                <td> No documents!</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--Necessary documents to be attached--}}
                            <div id="docListDiv">

                            </div>
                            @if($viewMode != 'off')
                                @include('WaiverCondition8::doc-tab')
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
                                                            {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'cusReadonly form-control input-sm phone_or_mobile required', 'readonly']) !!}
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

                                    <div class="form-group {{$errors->has('accept_terms') ? 'has-error' : ''}}">
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
                                                        {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md required email']) !!}
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
                                                        {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control input-md required sfp_contact_phone']) !!}
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

                        @if(ACL::getAccsessRight('WaiverCondition8','-E-') && $viewMode != "on" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                            
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
{{--Datepicker js--}}
<script src="{{ asset("vendor/datepicker/datepicker.min.js") }}"></script>

<script>

    function CategoryWiseDocLoad(office_type) {

        var attachment_key = "waiver8";

        if(office_type != 0 && office_type != ''){
            var _token = $('input[name="_token"]').val();
            var app_id = $("#app_id").val();
            var viewMode = $("#viewMode").val();

            $.ajax({
                type: "POST",
                url: '/waiver-condition-8/getDocList',
                dataType: "json",
                data: {_token : _token, attachment_key : attachment_key, app_id:app_id, viewMode:viewMode},
                success: function(result) {
                    if (result.html != undefined) {
                        $('#docListDiv').html(result.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
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
            var action = "{{url('/waiver-condition-8/upload-document')}}";

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

    $(document).ready(function(){
        $('#office_type').trigger('change');

        @if ($viewMode != 'on')
        var form = $("#WaiverForm8").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
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
                console.log(currentIndex);
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
                console.log(form.validate());
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
                popupWindow = window.open('<?php echo URL::to('/waiver-condition-8/preview'); ?>', 'Sample', '');
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


        $("#local_c_city_district").trigger('change');

    });
    
</script>

{{--initial- input plugin script start--}}
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
            hiddenInput: "ex_office_telephone_no",
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

        $(".gfp_contact_phone").intlTelInput({
            hiddenInput: "gfp_contact_phone",
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
</script>
{{--initial- input plugin script end--}}

{{--Applicant desired duration & payment calculation--}}
<script>
    $(function () {
        var process_id = '{{ $process_type_id }}';
        var dd_startDateDivID = 'duration_start_datepicker';
        var dd_startDateValID = 'duration_start_date';
        var dd_endDateDivID = 'duration_end_datepicker';
        var dd_endDateValID = 'duration_end_date';
        var dd_show_durationID = 'desired_duration';
        var dd_show_amountID = 'duration_amount';
        var dd_show_yearID = '';

        $("#"+dd_startDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });
        $("#"+dd_endDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });

        $("#"+dd_startDateDivID).on("dp.change", function (e) {

            var startDateVal = $("#"+dd_startDateValID).val();

            if (startDateVal != '') {
                // Min value set for end date
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(e.date);
                var endDateVal = $("#"+dd_endDateValID).val();
                if (endDateVal != '') {
                    getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID, dd_show_amountID, dd_show_yearID);
                } else {
                    $("#"+dd_endDateValID).addClass('error');
                }
            } else {
                $("#"+dd_show_durationID).val('');
                $("#"+dd_show_amountID).val('');
                $("#"+dd_show_yearID).text('');
            }
        });

        $("#"+dd_endDateDivID).on("dp.change", function (e) {

            // Max value set for start date
            $("#"+dd_startDateDivID).data("DateTimePicker").maxDate(e.date);

            var startDateVal = $("#"+dd_startDateValID).val();

            if (startDateVal === '') {
                $("#"+dd_startDateValID).addClass('error');
            } else {
                var day = moment(startDateVal, ['DD-MMM-YYYY']);
                //var minStartDate = moment(day).add(1, 'day');
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(day);
            }

            var endDateVal = $("#"+dd_endDateValID).val();

            if (startDateVal != '' && endDateVal != '') {
                getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID, dd_show_amountID, dd_show_yearID);
            }else{
                $("#"+dd_show_durationID).val('');
                $("#"+dd_show_amountID).val('');
                $("#"+dd_show_yearID).text('');
            }
        });

    });

    //Office type will not be changed when application is resubmitted
    var status_id = '{{ $appInfo->status_id }}';
    if (status_id == 5){ // 5 = shortfall
        $('#office_type').attr("readonly", "readonly");
        $('#office_type option:not(:selected)').remove();
    }

 /*   function totalExpense(total_expense){
        var total_revenue = $("#total_revenue").val();
        var total_comprehensive_income = total_revenue - total_expense;
        if(!isNaN(total_comprehensive_income)){
            $("#total_comprehensive_income").val(total_comprehensive_income);
        }

    }*/

  /*  function totalRevenue(total_revenue){
        var total_expense = $("#total_expense").val();
        var total_comprehensive_income = total_revenue - total_expense;
        if(!isNaN(total_comprehensive_income)){
            $("#total_comprehensive_income").val(total_comprehensive_income);
        }
    }*/

    // Datepicker Plugin initialize
    $('.datepickerV2').datepicker({
        outputFormat: 'dd-MMM-y',
        // daysOfWeekDisabled: [5,6],
        theme : 'blue',
    });
</script>