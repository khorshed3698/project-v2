<?php
$accessMode = ACL::getAccsessRight('OfficePermissionNew');
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
                            <h5><strong>Application for New Office Permission</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/office_permission_new.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'office-permission-new/store','method' => 'post','id' => 'OfficePermissionNewForm','enctype'=>'multipart/form-data',
                                'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

                        <h3 class="stepHeader">Application Info.</h3>
                        <fieldset>
                            {{-- Common Basic Information By Company Id --}}
                            @include('ProcessPath::basic-company-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic information</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_type') ? 'has-error': ''}}">
                                                {!! Form::label('office_type','Office type', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('office_type', $officeType, '', ['placeholder' => 'Select One',
                                                    'class' => 'form-control input-md required', 'id' => 'office_type', 'onchange' => "CategoryWiseDocLoad(this.value)"]) !!}
                                                    {!! $errors->first('office_type','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                                        {!! Form::text('c_company_name', $company_info->c_company_name, ['class'=>'form-control input-md required', 'data-rule-maxlength'=>'255', 'id'=>"c_company_name"]) !!}
                                                        {!! $errors->first('c_company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                {{-- Country & House/ Plot/ Holding No --}}
                                                <div class="col-md-6 {{$errors->has('c_origin_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('c_origin_country_id','Country of origin of principal office',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('c_origin_country_id', $countries,'', ['placeholder' => 'Select One',
                                                        'class' => 'form-control input-md required']) !!}
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
                                                        {!! Form::select('c_org_type', $organizationTypes, '',
                                                        ['class' => 'form-control input-md','placeholder' => 'Select One', 'id' => 'c_org_type']) !!}
                                                        {!! $errors->first('c_org_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 has-feedback {{ $errors->has('c_flat_apart_floor') ? 'has-error' : ''}}">
                                                    {!! Form::label('c_flat_apart_floor','Flat/ Apartment/ Floor no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_flat_apart_floor', '', ['class'=>'form-control input-md', 'data-rule-maxlength'=>'40', 'id'=>"c_flat_apart_floor"]) !!}
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
                                                        {!! Form::text('c_house_plot_holding', '', ['class'=>'form-control input-md', 'data-rule-maxlength'=>'40', 'id'=>"c_house_plot_holding"]) !!}
                                                        {!! $errors->first('c_house_plot_holding','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('c_post_zip_code') ? 'has-error': ''}}">
                                                    {!! Form::label('c_post_zip_code','Post/ Zip code',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_post_zip_code', '', ['data-rule-maxlength'=>'80',
                                                        'class' => 'form-control input-md', 'id' => 'c_post_zip_code']) !!}
                                                        {!! $errors->first('c_post_zip_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Telephone and fax --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 has-feedback {{ $errors->has('c_street') ? 'has-error' : ''}}">
                                                    {!! Form::label('c_street','Street name/ Street no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_street', '', ['class'=>'form-control input-md','data-rule-maxlength'=>'40', 'id' => 'c_street']) !!}
                                                        {!! $errors->first('c_street','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('c_email') ? 'has-error': ''}}">
                                                    {!! Form::label('c_email','Email',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_email', '', ['class' => 'form-control input-md email', 'id' => 'c_email']) !!}
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
                                                        {!! Form::text('c_city', '',['class' => 'form-control input-md', 'id' => 'c_city']) !!}
                                                        {!! $errors->first('c_city','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('c_telephone') ? 'has-error': ''}}">
                                                    {!! Form::label('c_telephone','Telephone no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_telephone', '',['data-rule-maxlength'=>'20',  'class' => 'form-control input-md phone_or_mobile', 'id' => 'c_telephone']) !!}
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
                                                        {!! Form::text('c_state_province', '',['class' => 'form-control input-md', 'id' => 'c_state_province']) !!}
                                                        {!! $errors->first('c_state_province','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('c_fax') ? 'has-error': ''}}">
                                                    {!! Form::label('c_fax','Fax no.',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('c_fax', '', ['class' => 'form-control input-md', 'id' => 'c_fax']) !!}
                                                        {!! $errors->first('c_fax','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Major Activities --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('c_major_activity_brief') ? 'has-error': ''}}">
                                                    <div class="col-md-2" style="width: 20.2%;">
                                                        {!! Form::label('c_major_activity_brief','Major activities in brief',['class'=>'text-left']) !!}
                                                    </div>
                                                    <div class="col-md-9" style="width: 79.8%">
                                                        {!! Form::textarea('c_major_activity_brief', '',['class' => 'bigInputField form-control input-md maxTextCountDown', 'size'=>'1x2', 'id' => 'c_major_activity_brief','data-charcount-maxlength'=>'200']) !!}
                                                        {!! $errors->first('c_major_activity_brief','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    {{-- Capital of principal company --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">The capital of the principal company (in US $)</legend>
                                        {{-- Authorized and paid capital --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('authorized_capital') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_capital','(i) Authorized capital',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('authorized_capital', '',['class' => 'form-control number input-md ', 'id'=>'authorized_capital']) !!}
                                                        {!! $errors->first('authorized_capital','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('paid_up_capital') ? 'has-error': ''}}">
                                                    {!! Form::label('paid_up_capital','(ii) Paid up capital',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('paid_up_capital', '',['class' => 'form-control number input-md', 'id' => 'paid_up_capital']) !!}
                                                        {!! $errors->first('paid_up_capital','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Office Info.</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Information about the proposed branch/ liaison/ representative office</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('local_company_name') ? 'has-error': ''}}">
                                                {!! Form::label('local_company_name','Name of the Local company:',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('local_company_name', '', ['class'=>'form-control input-md bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name"]) !!}
                                                    {!! $errors->first('local_company_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('local_company_name_bn') ? 'has-error': ''}}">
                                                {!! Form::label('local_company_name_bn','Name of the Local company (Bangla):',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('local_company_name_bn', '', ['class'=>'form-control input-md bigInputField', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name_bn"]) !!}
                                                    {!! $errors->first('local_company_name_bn','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">
                                            Local address of the principal company: (Bangladesh only)
                                        </legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_division_id','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_division_id', $divisions, '', ['class' => 'form-control input-md required', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id')"]) !!}
                                                        {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_district_id', [], '', ['class' => 'form-control input-md required','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id')"]) !!}
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
                                                        {!! Form::select('ex_office_thana_id',[''], '', ['class' => 'form-control input-md required','placeholder' => 'Select district first']) !!}
                                                        {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_post_office','Post office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_post_office', '', ['class' => 'form-control input-md']) !!}
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
                                                        {!! Form::text('ex_office_post_code', '', ['class' => 'form-control input-md post_code_bd required']) !!}
                                                        {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_address', '', ['maxlength'=>'150','class' => 'form-control input-md required']) !!}
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
                                                        {!! Form::text('ex_office_telephone_no', '', ['maxlength'=>'20','class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_mobile_no','Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_mobile_no', '', ['class' => 'form-control input-md helpText15 required' ,'id' => 'ex_office_mobile_no']) !!}
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
                                                        {!! Form::text('ex_office_fax_no', '', ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_email', '', ['class' => 'form-control input-md email required']) !!}
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
                                                        {!! Form::textarea('activities_in_bd', null, ['data-rule-maxlength'=>'250', 'id' => 'activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                        'size'=>'10x3' ,'data-charcount-maxlength' => '250']) !!}
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
                                                            {!! Form::text('first_commencement_date', '', ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy', 'id' => 'first_commencement_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('first_commencement_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('operation_target_date') ? 'has-error': ''}}">
                                                    {!! Form::label('operation_target_date','Target date of operation of the proposed office:',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="datepicker input-group date ">
                                                            {!! Form::text('operation_target_date', '', ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'operation_target_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('operation_target_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Period for which permission is sought for</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3 {{$errors->has('period_start_date') ? 'has-error': ''}}">
                                                    {!! Form::label('period_start_date','Start and effective date:',['class'=>'text-left col-md-12']) !!}
                                                    <div class="col-md-12">
                                                        <div id="duration_start_datepicker" class="input-group date ">
                                                            {!! Form::text('period_start_date', '', ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'period_start_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('period_start_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 {{$errors->has('period_end_date') ? 'has-error': ''}}">
                                                    {!! Form::label('period_end_date','End date',['class'=>'text-left col-md-12']) !!}
                                                    <div class="col-md-12">
                                                        <div id="duration_end_datepicker" class="input-group date ">
                                                            {!! Form::text('period_end_date', '', ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'period_end_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        <span class="text-danger" style="font-size: 12px; font-weight: bold" id="date_compare_error"></span>
                                                        {!! $errors->first('period_end_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 {{$errors->has('period_validity') ? 'has-error': ''}}">
                                                    {!! Form::label('period_validity','Period of validity',['class'=>'col-md-12 text-left']) !!}
                                                    <div class="col-md-12">
                                                        {!! Form::text('period_validity', '', ['class' => 'form-control input-md', 'readonly', 'id' => 'period_validity']) !!}
                                                        {!! $errors->first('period_validity','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 {{$errors->has('duration_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('duration_amount','Payable amount',['class'=>'text-left col-md-12']) !!}
                                                    <div class="col-md-12">
                                                        {!! Form::text('duration_amount', '', ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('duration_amount','<span class="help-block">:message</span>') !!}
                                                        <span class="text-danger" style="font-size: 12px; font-weight: bold" id="duration_year"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group clearfix">
                                        <div class="col-md-12" style="padding: 0">
                                            <div class="table-responsive">
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
                                                            {!! Form::text('local_executive', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'local_executive']) !!}
                                                            {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_stuff', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'local_stuff']) !!}
                                                            {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('local_total', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative required','id'=>'local_total','readonly']) !!}
                                                            {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_executive', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'foreign_executive']) !!}
                                                            {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_stuff', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'foreign_stuff']) !!}
                                                            {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('foreign_total', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative required','id'=>'foreign_total','readonly']) !!}
                                                            {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_total', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'mp_total','readonly']) !!}
                                                            {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_local_ratio', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'mp_ratio_local','readonly']) !!}
                                                            {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('manpower_foreign_ratio', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'mp_ratio_foreign','readonly']) !!}
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
                                                        {!! Form::text('est_initial_expenses', '',['class' => 'form-control number input-md', 'id' => 'est_initial_expenses']) !!}
                                                        {!! $errors->first('est_initial_expenses','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('est_monthly_expenses') ? 'has-error': ''}}">
                                                    {!! Form::label('est_monthly_expenses','(b) Estimated monthly expenses:',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('est_monthly_expenses', '',['class' => 'form-control input-md number', 'id' => 'est_monthly_expenses']) !!}
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
                            <div id="docListDiv">
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Declaration</h3>
                        <fieldset>

                            <div class  ="panel panel-info">
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
                                                    <div class="form-group col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail"
                                                                 src="{{ (!empty(Auth::user()->user_pic) ? url('users/upload/'.Auth::user()->user_pic) : url('assets/images/photo_default.png')) }}"
                                                                 alt="User Photo">
                                                        </div>
                                                        <input type="hidden" name="auth_image" value="{{ Auth::user()->user_pic }}">
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
                            <legend class="d-none">Payment & Submit</legend>
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
                                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md required']) !!}
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
                                    value="submit" name="actionBtn">Payment & Submit
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

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script>

    function CategoryWiseDocLoad(office_type) {

        var attachment_key = "opn_";
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
                url: '/office-permission-new/getDocList',
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
            var action = "{{url('/office-permission-new/upload-document')}}";

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

        var form = $("#OfficePermissionNewForm").show();
        // form.find('#save_as_draft').css('display','none');
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
                // if (currentIndex != 0) {
                //     form.find('#save_as_draft').css('display','block');
                //     form.find('.actions').css('top','-42px');
                // } else {
                //     form.find('#save_as_draft').css('display','none');
                //     form.find('.actions').css('top','-15px');
                // }

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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/office-permission-new/preview'); ?>', 'Sample', '');
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

//        $(".period_start_date").on("dp.change", function (e) {
//            $('#period_end_date').val('');
////            $('#construction_duration').val('');
//
//            var nextDate = e.date.add(1, 'day');
//            $('.period_end_date').datetimepicker({
//                format: 'DD-MMM-YYYY',
//                minDate : nextDate,
//                useCurrent: false // Important! See issue #1075
//            });
////            $('.period_end_date').data("DateTimePicker").minDate(nextDate);
//            startDateSelected = nextDate;
//        });
//        $(".period_end_date").on("dp.change", function (e) {
//            var startDateVal = $("#period_start_date").val();
//            var day = moment(startDateVal, ['DD-MMM-YYYY','YYYY-MM-DD']);
//            var startDate = moment(day).add(1, 'day');
//            if(startDateVal !=''){
//                $('.period_end_date').data("DateTimePicker").minDate(startDate);
//            }
//            var endDate = moment($("#period_end_date").val()).add(1, 'day');
//            var endDateMoment = moment(endDate, ['DD-MMM-YYYY','YYYY-MM-DD']);
//            var endDateVal = $("#period_end_date").val();
//            var dayEnd = moment(endDateVal, ['DD-MMM-YYYY','YYYY-MM-DD']);
//            var endDate = moment(dayEnd).add(1, 'day');
//
//            //var startDate = startDateSelected;
//            //var endDate = e.date.add(1, 'day');
////            if (startDate != '' && endDate != '' && $("#period_end_date").val() > $("#period_start_date").val()) {
////                var days = (endDate - startDate) / 1000 / 60 / 60 / 24;
////                $('#construction_duration').val(Math.floor(days));
////            }
//        });
//        $('.period_end_date').trigger('dp.change');  // End of Construction Schedule

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

        $("#auth_cell_number").intlTelInput({
            hiddenInput: "auth_cell_number",
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

        $("#c_telephone").intlTelInput({
            hiddenInput: "c_telephone",
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
        var dd_startDateValID = 'period_start_date';
        var dd_endDateDivID = 'duration_end_datepicker';
        var dd_endDateValID = 'period_end_date';
        var dd_show_durationID = 'period_validity';
        var dd_show_amountID = 'duration_amount';
        var dd_show_yearID = 'duration_year';

        $("#"+dd_startDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY'
        });
        $("#"+dd_endDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });

        $("#"+dd_startDateDivID).on("dp.change", function (e) {
            var startDateVal = $("#"+dd_startDateValID).val();

            var date_format = $("#"+dd_startDateValID).val().replace(/-/g, ' ');
            var actualDate = new Date(date_format); // convert to actual date
            //for next three year after date
            var nextThreeYearDate = new Date(actualDate.getFullYear(), actualDate.getMonth() + 36, actualDate.getDate() - 1);

            if (startDateVal != '') {
                // Min value set for end date
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(e.date);
                    $("#"+dd_endDateDivID).data("DateTimePicker").maxDate(nextThreeYearDate);

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
           // $("#"+dd_startDateDivID).data("DateTimePicker").maxDate(e.date);

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
