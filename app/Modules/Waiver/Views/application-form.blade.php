<?php
$accessMode = ACL::getAccsessRight('Waiver');
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
    #office_type{
        pointer-events: none;
    }

    .usd-def{
        pointer-events: none;
    }

    .width_14_percent{
        width: 14%;
    }
    .country{
        pointer-events: none;
    }
    .cusReadonly{
        pointer-events: none;
    }
</style>

<section class="content" id="applicationForm">
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
                            <h5><strong>Application for Waiver</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/waiver.pdf') }}" target="_blank" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">

                        {!! Form::open(array('url' => 'waiver/store','method' => 'post','id' => 'WaiverForm','enctype'=>'multipart/form-data',
                                'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        <input type="hidden" name="ref_app_approve_date" value="{{ (Session::get('waiver.approved_date') ? Session::get('waiver.approved_date') : '') }}">

                        <h3 class="stepHeader">Application Info.</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic information</strong></div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <div class="row">
                                            <div id="ref_app_tracking_no_div" class="col-md-12  {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                {!! Form::label('ref_app_tracking_no','Please provide your Branch office permission tracking No. New/Extension:',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        {!! Form::text('ref_app_tracking_no', Session::get('waiver.ref_app_tracking_no'), ['data-rule-maxlength'=>'100', 'class' => 'form-control  input-sm', Session::get('waiver.ref_app_tracking_no') ? 'readonly':'' ]) !!}
                                                        {!! $errors->first('ref_app_tracking_no','<span class="help-block">:message</span>') !!}
                                                        <span class="input-group-btn">
                                                            @if(Session::get('waiver'))
                                                                <button type="submit" class="btn btn-danger btn-sm" value="clean_load_data" name="actionBtn">Clear Loaded Data</button>

                                                                <a href="{{ Session::get('waiver.certificate_link') }}" target="_blank" class="btn btn-success btn-sm">View Certificate</a>

                                                            @else
                                                                <button type="submit" class="btn btn-success btn-sm cancel" value="searchWaiverinfo" name="searchWaiverinfo" id="searchWaiverinfo">Load Office Permission Data</button>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <small class="text-danger">N.B.: Once you save or submit the application, the Office permission tracking no cannot be changed anymore.</small>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Office Info.</h3>
                        <fieldset>
                            {{-- Common Basic Information By Company Id --}}
                            @include('ProcessPath::basic-company-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Office type</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_type') ? 'has-error': ''}}">
                                                {!! Form::label('office_type','Office type', ['class' => 'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('office_type', $officeType, 1, ['placeholder' => 'Select One',
                                                    'class' => 'form-control input-md cusReadonly', 'id' => 'office_type', 'readonly'=>"readonly", 'onchange' => "CategoryWiseDocLoad(this.value)"]) !!}
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
                                        <div class="col-md-6 {{$errors->has('approved_permission_start_date') ? 'has-error': ''}}">
                                            {!! Form::label('approved_permission_start_date','Start date',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <div class="input-group date">
                                                    {!! Form::text('approved_permission_start_date', Session::get('waiver.approved_duration_start_date'), ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy',  'readonly']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('approved_permission_start_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('approved_permission_end_date') ? 'has-error': ''}}">
                                            {!! Form::label('approved_permission_end_date','End date',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <div class="input-group date">
                                                    {!! Form::text('approved_permission_end_date', Session::get('waiver.approved_duration_end_date'), ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'readonly']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                <span class="text-danger" style="font-size: 12px; font-weight: bold" id="date_compare_error"></span>
                                                {!! $errors->first('approved_permission_end_date','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('approved_permission_duration') ? 'has-error': ''}}">
                                            {!! Form::label('approved_permission_duration','Duration',['class'=>'text-left  col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('approved_permission_duration', Session::get('waiver.approved_desired_duration'), ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('approved_permission_duration','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{$errors->has('approved_permission_duration_amount') ? 'has-error': ''}}">
                                            {!! Form::label('duration_amount','Payable amount',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('approved_permission_duration_amount', Session::get('waiver.duration_amount'), ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('duration_amount','<span class="help-block">:message</span>') !!}

                                                {{--Show duration in year--}}
                                                <span class="text-danger" style="font-size: 12px; font-weight: bold" id="duration_year"></span>
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
                                                        {!! Form::text('c_company_name', (Session::get('waiver.c_company_name') ? Session::get('waiver.c_company_name') : $company_info->c_company_name), ['class'=>'form-control required input-md cusReadonly bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"c_company_name", "readonly"]) !!}
                                                        {!! $errors->first('c_company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                {{-- Country & House/ Plot/ Holding No --}}
                                                <div class="col-md-6 {{$errors->has('c_origin_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('c_origin_country_id','Country of origin of principal office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('c_origin_country_id', $countries,Session::get('waiver.c_origin_country_id'), ['placeholder' => 'Select One',
                                                        'class' => 'form-control input-md cusReadonly']) !!}
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
                                                        {!! Form::select('c_org_type', $organizationTypes, Session::get('waiver.c_org_type'),
                                                        ['class' => 'form-control cusReadonly input-md','placeholder' => 'Select One', 'id' => 'c_org_type']) !!}
                                                        {!! $errors->first('c_org_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 has-feedback {{ $errors->has('c_flat_apart_floor') ? 'has-error' : ''}}">
                                                    {!! Form::label('c_flat_apart_floor','Flat/ Apartment/ Floor no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_flat_apart_floor', Session::get('waiver.c_flat_apart_floor'), ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'40', 'id'=>"c_flat_apart_floor"]) !!}
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
                                                        {!! Form::text('c_house_plot_holding', Session::get('waiver.c_house_plot_holding'), ['class'=>'form-control input-md cusReadonly', 'data-rule-maxlength'=>'40', 'id'=>"c_house_plot_holding"]) !!}
                                                        {!! $errors->first('c_house_plot_holding','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('c_post_zip_code') ? 'has-error': ''}}">
                                                    {!! Form::label('c_post_zip_code','Post/ Zip code',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_post_zip_code', Session::get('waiver.c_post_zip_code'), ['data-rule-maxlength'=>'80',
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
                                                        {!! Form::text('c_street', Session::get('waiver.c_street'), ['class'=>'form-control input-md cusReadonly','data-rule-maxlength'=>'40', 'id' => 'c_street']) !!}
                                                        {!! $errors->first('c_street','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('c_email') ? 'has-error': ''}}">
                                                    {!! Form::label('c_email','Email',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_email', Session::get('waiver.c_email'), ['class' => 'form-control input-md email cusReadonly', 'id' => 'c_email']) !!}
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
                                                        {!! Form::text('c_city', Session::get('waiver.c_city'),['class' => 'form-control input-md cusReadonly', 'id' => 'c_city']) !!}
                                                        {!! $errors->first('c_city','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('c_telephone') ? 'has-error': ''}}">
                                                    {!! Form::label('c_telephone','Telephone no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_telephone', Session::get('waiver.c_telephone'),['data-rule-maxlength'=>'20',  'class' => 'form-control input-md cusReadonly', 'id' => 'c_telephone']) !!}
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
                                                        {!! Form::text('c_state_province', Session::get('waiver.c_state_province'),['class' => 'form-control input-md cusReadonly', 'id' => 'c_state_province']) !!}
                                                        {!! $errors->first('c_state_province','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('c_fax') ? 'has-error': ''}}">
                                                    {!! Form::label('c_fax','Fax no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_fax', Session::get('waiver.c_fax'), ['class' => 'form-control input-md cusReadonly', 'id' => 'c_fax']) !!}
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
                                                    <div class="col-md-7" style="width: 79.8%">
                                                        {!! Form::textarea('c_major_activity_brief', Session::get('waiver.c_major_activity_brief'),['class' => 'bigInputField form-control input-md cusReadonly maxTextCountDown', 'size'=>'1x2', 'id' => 'c_major_activity_brief','data-charcount-maxlength'=>'200']) !!}
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
                                                    {!! Form::text('local_company_name', Session::get('waiver.local_company_name'), ['class'=>'form-control input-md cusReadonly bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name"]) !!}
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
                                                    {!! Form::text('local_company_name_bn', Session::get('waiver.local_company_name_bn'), ['class'=>'form-control input-md cusReadonly bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name_bn"]) !!}
                                                    {!! $errors->first('local_company_name_bn','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">
                                            Local address of the principal company: (Bangladesh only):
                                        </legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_division_id','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_division_id', $divisions, Session::get('waiver.ex_office_division_id'), ['class' => 'form-control required cusReadonly  input-md', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id', ". Session::get('waiver.ex_office_district_id') .")"]) !!}
                                                        {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_district_id', $district_eng, Session::get('waiver.ex_office_district_id'), ['class' => 'form-control required cusReadonly  input-md', 'id' => 'ex_office_district_id' ,'placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id', ". Session::get('waiver.ex_office_thana_id') .")"]) !!}
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
                                                        {!! Form::select('ex_office_thana_id', $thana_eng, Session::get('waiver.ex_office_thana_id'), ['class' => 'form-control required  input-md cusReadonly','placeholder' => 'Select district first', 'id' => 'ex_office_thana_id']) !!}
                                                        {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_post_office','Post office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_post_office', Session::get('waiver.ex_office_post_office'), ['class' => 'form-control input-md cusReadonly']) !!}
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
                                                        {!! Form::text('ex_office_post_code', Session::get('waiver.ex_office_post_code'), ['class' => 'form-control required cusReadonly input-md post_code_bd']) !!}
                                                        {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_address', Session::get('waiver.ex_office_address'), ['maxlength'=>'150','class' => 'form-control required cusReadonly input-md']) !!}
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
                                                        {!! Form::text('ex_office_telephone_no', Session::get('waiver.ex_office_telephone_no'), ['maxlength'=>'20','class' => 'form-control cusReadonly input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_mobile_no','Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_mobile_no', Session::get('waiver.ex_office_mobile_no'), ['class' => 'form-control required cusReadonly input-md helpText15' ,'id' => 'ex_office_mobile_no']) !!}
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
                                                        {!! Form::text('ex_office_fax_no', Session::get('waiver.ex_office_fax_no'), ['class' => 'form-control cusReadonly input-md']) !!}
                                                        {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_email', Session::get('waiver.ex_office_email'), ['class' => 'form-control required cusReadonly email input-md']) !!}
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
                                                        {!! Form::textarea('activities_in_bd', Session::get('waiver.activities_in_bd'), ['data-rule-maxlength'=>'250', 'id' => 'activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control cusReadonly bigInputField input-md maxTextCountDown',
                                                        'size'=>'10x3','data-charcount-maxlength'=>'250']) !!}
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
                                                                <div id="duration_start_datepicker" class="input-group date">
                                                                    {!! Form::text('comprehensive_income_start_date', '', ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'duration_start_date']) !!}
                                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                                </div>
                                                                {!! $errors->first('comprehensive_income_start_date','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('comprehensive_income_end_date') ? 'has-error': ''}}">
                                                            {!! Form::label('comprehensive_income_end_date','End date',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                <div id="duration_end_datepicker" class="input-group date">
                                                                    {!! Form::text('comprehensive_income_end_date', '', ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'duration_end_date']) !!}
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
                                                                {!! Form::text('comprehensive_income_duration', '', ['class' => 'form-control input-md', 'readonly', 'id' => 'desired_duration']) !!}
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
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="width_14_percent">
                                                                         Total Revenue
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    {!! Form::text('total_revenue', '', ['class' => 'form-control total_investment_item input-md number','id'=>'total_revenue' , 'onKeyUp'=>"totalRevenue(this.value)"]) !!}
                                                                                </td>
                                                                                <td>
                                                                                    {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>


                                                                    <td class="width_14_percent">
                                                                        Total Expense
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    {!! Form::text('total_expense', '', ['class' => 'form-control total_investment_item input-md number','id'=>'total_expense', 'onKeyUp'=>"totalExpense(this.value)"]) !!}
                                                                                </td>
                                                                                <td>
                                                                                    {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
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
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    {!! Form::text('total_comprehensive_income', '', ['class' => 'form-control input-md number','id'=>'total_comprehensive_income', 'readonly']) !!}
                                                                                </td>
                                                                                <td>
                                                                                    {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
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
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="width_14_percent">
                                                                       Fixed Assets
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    {!! Form::text('fixed_assets', '', ['class' => 'form-control input-md number']) !!}
                                                                                </td>
                                                                                <td>
                                                                                    {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>

                                                                    <td class="width_14_percent">
                                                                        Current Assets
                                                                    </td>
                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    {!! Form::text('current_assets', '', ['class' => 'form-control input-md number']) !!}
                                                                                </td>
                                                                                <td>
                                                                                    {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
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
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Bank Balance
                                                                </td>
                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('bank_balance', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td>
                                                                    Cash Balance
                                                                </td>
                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('cash_balance', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
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
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Fixed Liabilities
                                                                </td>
                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('fixed_liabilities', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>

                                                                <td>
                                                                    Current Liabilities
                                                                </td>
                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('current_liabilities', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number']) !!}
                                                                            </td>
                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
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
                                                        <table class="table table-striped table-bordered">
                                                            <tbody>
                                                            <tr>
                                                                <td class="width_14_percent">
                                                                    Equility
                                                                </td>

                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                {!! Form::text('equility', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number']) !!}
                                                                            </td>

                                                                            <td>
                                                                                {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
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
                                                        <table class="table table-striped table-bordered">
                                                        <tbody>
                                                        <tr>
                                                            <td class="width_14_percent">
                                                                Accumulated Profit/Loss
                                                            </td>
                                                            <td>
                                                                <table>
                                                                    <tr>
                                                                        <td>
                                                                            {!! Form::text('acc_profit_loss', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number']) !!}
                                                                        </td>

                                                                        <td>
                                                                            {!! Form::select("currency_name", $currencies,114, ["placeholder" => "Select One", "class" => "form-control input-md usd-def"]) !!}
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
                                        <div class="col-md-12" style="padding: 0">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead class="alert alert-info">
                                                    <tr>
                                                        <th class="text-center text-title required-star" colspan="9">Proposed organizational set up of the office with expatriate and local man power ratio</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="alert alert-info text-center" colspan="3">Local (a)</th>
                                                        <th class="alert alert-info text-center" colspan="3">Foreign (b)</th>
                                                        <th class="alert alert-info text-center" colspan="1">Grand total</th>
                                                        <th class="alert alert-info text-center" colspan="2">Ratio</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="manpower">
                                                    <tr>
                                                        <th class="alert alert-info text-center">Executive</th>
                                                        <th class="alert alert-info text-center">Supporting staff</th>
                                                        <th class="alert alert-info text-center">Total</th>
                                                        <th class="alert alert-info text-center">Executive</th>
                                                        <th class="alert alert-info text-center">Supporting staff</th>
                                                        <th class="alert alert-info text-center">Total</th>
                                                        <th class="alert alert-info text-center"> (a+b)</th>
                                                        <th class="alert alert-info text-center">Local</th>
                                                        <th class="alert alert-info text-center">Foreign</th>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            {!! Form::text('local_executive', Session::get('waiver.local_executive'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'local_executive']) !!}
                                                            {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_stuff', Session::get('waiver.local_stuff'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'local_stuff']) !!}
                                                            {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_total', Session::get('waiver.local_total'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'local_total','readonly']) !!}
                                                            {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_executive', Session::get('waiver.foreign_executive'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'foreign_executive']) !!}
                                                            {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_stuff', Session::get('waiver.foreign_stuff'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'foreign_stuff']) !!}
                                                            {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_total', Session::get('waiver.foreign_total'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'foreign_total','readonly']) !!}
                                                            {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_total', Session::get('waiver.manpower_total'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'mp_total','readonly']) !!}
                                                            {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_local_ratio', Session::get('waiver.manpower_local_ratio'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'mp_ratio_local','readonly']) !!}
                                                            {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_foreign_ratio', Session::get('waiver.manpower_foreign_ratio'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md number required','id'=>'mp_ratio_foreign','readonly']) !!}
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
                                                        {!! Form::text('est_initial_expenses', Session::get('waiver.est_initial_expenses'),['class' => 'form-control cusReadonly input-md number', 'id' => 'est_initial_expenses']) !!}
                                                        {!! $errors->first('est_initial_expenses','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('est_monthly_expenses') ? 'has-error': ''}}">
                                                    {!! Form::label('est_monthly_expenses','(b) Estimated monthly expenses:',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('est_monthly_expenses', Session::get('waiver.est_monthly_expenses'),['class' => 'form-control cusReadonly input-md number', 'id' => 'est_monthly_expenses']) !!}
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
                            <div id="docListDiv">

                            </div>
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
                                                            {!! Form::text('auth_full_name', CommonFunction::getUserFullName(), ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', Auth::user()->designation, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', Auth::user()->user_phone, ['class' => 'form-control input-sm phone_or_mobile required', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' => 'form-control input-sm email required', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
{{--                                                    <div class="form-group col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">--}}
{{--                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left']) !!}--}}
{{--                                                        <div class="col-md-7">--}}
{{--                                                            <img class="img-thumbnail"--}}
{{--                                                                 src="{{ (!empty(Auth::user()->user_pic) ? url('users/upload/'.Auth::user()->user_pic) : url('assets/images/photo_default.png')) }}"--}}
{{--                                                                 alt="User Photo">--}}
{{--                                                        </div>--}}
{{--                                                        <input type="hidden" name="auth_image" value="{{ Auth::user()->user_pic }}">--}}
{{--                                                    </div>--}}

                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <label  id="profile_image">Picture</label>
                                                        <span style="font-size: 9px; color: grey">[File Format: *.jpg / *.png, Dimension: 300x300 pixel]</span>
                                                        <br><br>
                                                        <label id="profile_image_div" class="btn btn-primary btn-file" {{ $errors->has('applicant_photo') ? 'has-error' : '' }}>
                                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                            Browse
                                                            <input type="file" style="display: none;"
                                                                   class="form-control input-sm {{!empty($users->user_pic) ? '' : 'required'}}"
                                                                   name="applicant_photo"
                                                                   id="applicant_photo"
                                                                   onchange="imageUploadWithCroppingAndDetect(this, 'applicant_photo_preview', 'applicant_photo_base64')"
                                                                   size="300x300"/>
                                                        </label>

                                                        <label class="btn btn-primary" id="captureProfilePicture" data-profile-capture="yes">
                                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                            Camera
                                                        </label>
                                                    </div>

                                                    <div class="col-sm-6 col-md-4">
                                                        <label class="center-block image-upload" for="applicant_photo">
                                                            <figure>
                                                                <img src="{{ \App\Libraries\UtilFunction::userProfileUrl($users->user_pic, 'users/upload/') }}" class="img-responsive img-thumbnail" id="applicant_photo_preview"/>
                                                            </figure>
                                                            <input type="hidden" id="applicant_photo_base64"
                                                                   name="applicant_photo_base64"/>
                                                            @if(!empty($users->user_pic))
                                                                <input type="hidden" id="applicant_photo_hidden" name="applicant_photo"
                                                                       value="{{$users->user_pic}}"/>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                                I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Payment & Submit</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Service fee payment</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                                    {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md email required']) !!}
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
                                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md phone_or_mobile required']) !!}
                                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
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
                                                    {!! Form::text('sfp_pay_amount', $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                    {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_vat_on_pay_amount', $payment_config->vat_on_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
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
                                                    {!! Form::text('sfp_total_amount', number_format($payment_config->amount + $payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    <span class="label label-warning">Not Paid</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--Vat/ tax and service charge is an approximate amount--}}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    <strong>Vat/ Tax</strong> and <strong>Transaction charge</strong> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="pull-left">
                            <button type="submit" class="btn btn-info btn-md cancel"
                                    value="draft" name="actionBtn" id="save_as_draft">Save as Draft
                            </button>
                        </div>
                        <div class="pull-left" style="padding-left: 1em;">
                            <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md"
                                    value="Submit" name="actionBtn">Payment & Submit
                                <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title="" data-original-title="After clicking this button will take you in the payment portal for providing the required payment. After payment, the application will be automatically submitted and you will get a confirmation email with necessary info." aria-describedby="tooltip"></i>
                            </button>
                        </div>

                    {!! Form::close() !!}<!-- /.form end -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@include('partials.image-resize.image-upload')
@include('partials.profile-capture')

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.2.24/jquery.autocomplete.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script>
    function CategoryWiseDocLoad(office_type) {

        var attachment_key = "waiver7";

        if(office_type != 0 && office_type != ''){
            var _token = $('input[name="_token"]').val();
            var app_id = $("#app_id").val();
            var viewMode = $("#viewMode").val();

            $.ajax({
                type: "POST",
                url: '/waiver/getDocList',
                dataType: "json",
                data: {_token : _token, attachment_key : attachment_key, app_id:app_id, viewMode:viewMode, office_type:office_type},
                success: function(result) {
                    if (result.html != undefined) {
                        $('#docListDiv').html(result.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    alert('Unknown error occured. Please, try again after reload');
                },
            });
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
            var action = "{{url('/waiver/upload-document')}}";

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


    var sessionLastTrNo = '{{ Session::get('waiver.ref_app_tracking_no') }}';

    $(document).ready(function(){

        $("#office_type").trigger('change');

        var form = $("#WaiverForm").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if(newIndex == 1){
                   if(sessionLastTrNo == ''){
                       alert('Please, load Office Permission data.');
                       return false;
                   }

                }

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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/waiver/preview'); ?>', 'Sample', '');
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

        $(".period_start_date").on("dp.change", function (e) {
            $('#period_end_date').val('');
//            $('#construction_duration').val('');

            var nextDate = e.date.add(1, 'day');
            $('.period_end_date').datetimepicker({
                format: 'DD-MMM-YYYY',
                minDate : nextDate,
                useCurrent: false // Important! See issue #1075
            });
//            $('.period_end_date').data("DateTimePicker").minDate(nextDate);
            startDateSelected = nextDate;
        });
        $(".period_end_date").on("dp.change", function (e) {
            var startDateVal = $("#period_start_date").val();
            var day = moment(startDateVal, ['DD-MMM-YYYY','YYYY-MM-DD']);
            var startDate = moment(day).add(1, 'day');
            if(startDateVal !=''){
                $('.period_end_date').data("DateTimePicker").minDate(startDate);
            }
            var endDate = moment($("#period_end_date").val()).add(1, 'day');
            var endDateMoment = moment(endDate, ['DD-MMM-YYYY','YYYY-MM-DD']);
            var endDateVal = $("#period_end_date").val();
            var dayEnd = moment(endDateVal, ['DD-MMM-YYYY','YYYY-MM-DD']);
            var endDate = moment(dayEnd).add(1, 'day');

            //var startDate = startDateSelected;
            //var endDate = e.date.add(1, 'day');
//            if (startDate != '' && endDate != '' && $("#period_end_date").val() > $("#period_start_date").val()) {
//                var days = (endDate - startDate) / 1000 / 60 / 60 / 24;
//                $('#construction_duration').val(Math.floor(days));
//            }
        });
        $('.period_end_date').trigger('dp.change');  // End of Construction Schedule

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

        $("#sfp_contact_phone").intlTelInput({
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
        var dd_show_yearID = 'duration_year';

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
                //$("#"+dd_endDateDivID).data("DateTimePicker").minDate(e.date);
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
</script>

<script>
    function totalExpense(total_expense){
        var total_revenue = $("#total_revenue").val();
        var total_comprehensive_income = total_revenue - total_expense;
        if(!isNaN(total_comprehensive_income)){
            $("#total_comprehensive_income").val(total_comprehensive_income);
        }

    }

    function totalRevenue(total_revenue){
        var total_expense = $("#total_expense").val();
        var total_comprehensive_income = total_revenue - total_expense;
        if(!isNaN(total_comprehensive_income)){
            $("#total_comprehensive_income").val(total_comprehensive_income);
        }
    }
</script>