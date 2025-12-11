<?php
$accessMode = ACL::getAccsessRight('E-tin');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<style>
    .ml-20{
        margin-left: 39px!important;
        width:95%;
        border-color:#ccc;
        border-radius: 4px;
        background-color: inherit;
    }
    .form-group{
        margin-bottom: 2px;
    }
    .img-thumbnail{
        height: 80px;
        width: 100px;
    }
    input[type=radio].error,
    input[type=checkbox].error{
        outline: 1px solid red !important;
    }
    .wizard>.steps>ul>li{
        width: 25% !important;
    }
    .table-striped > tbody#manpower > tr > td, .table-striped > tbody#manpower > tr > th {
        text-align: center;
    }
</style>
        <div class="box">
            <div class="box-body" id="inputForm">

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>  Application for E-TIN to National Board of Revenue (NBR) </strong></h5>
                        </div>

                        <div class="row" style="margin:15px 0 5px 0">
                            <div class="col-md-12">
                                <div class="heading_img">
                                    <img class="img-responsive pull-left"
                                         src="{{ asset('assets/images/u34.png') }}"/>
                                </div>
                                <div class="heading_text pull-left">
                                    National Board of Revenue (NBR)
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-body">
                        <div class="panel-body">
                            {!! Form::open(array('url' => '/single-licence/e-tin/add','method' => 'post','id' => 'EtinFormApplication','role'=>'form','enctype'=>'multipart/form-data')) !!}

                            <input type="hidden" name="mode" value="{{$viewMode}}">
                            {!! Form::hidden('app_id', Encryption::encodeId($appInfoEtin->single_licence_ref_id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
                            {!! Form::hidden('curr_process_status_id', $appInfoEtin->status_id,['class' => 'form-control input-md required', 'id'=>'process_status_id']) !!}

                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>


                            <fieldset>
                                <div class="panel panel-info">
                                    {{--<div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>--}}
                                    <div class="panel-body ">
                                        <div id="validationError"></div>
                                        @if(!in_array($appInfoEtin->status_id, [0,-1,1,2,5,6]) && $appInfoEtin->department_id != '')
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('department_id') ? 'has-error': ''}}">
                                                        {!! Form::label('department_id','Department',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('department_id', $departmentList, $appInfoEtin->department_id, ['class' => 'form-control required input-md ','id'=>'department_id']) !!}
                                                            {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 form-group {{$errors->has('taxpayer_status') ? 'has-error': ''}}">
                                                    {!! Form::label('taxpayer_status','Taxpayer\'s Status',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('taxpayer_status', $taxpayerStatus, $appInfoEtin->taxpayer_status, ['class'=>'form-control input-md required', 'id' => 'taxpayer_status']) !!}
                                                        {!! $errors->first('taxpayer_status','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                    {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('organization_type_id', $eaOrganizationType,$appInfoEtin->organization_type_id, ['class'=>'form-control input-md', 'id' => 'organization_type_id','readonly']) !!}
                                                        {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('reg_type') ? 'has-error': ''}}">
                                                    {!! Form::label('reg_type','Registration Type',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('reg_type', $registrationType,$appInfoEtin->reg_type, ['class'=>'form-control input-md', 'id' => 'reg_type']) !!}
                                                        {!! $errors->first('reg_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group cat-2 hide {{$errors->has('existing_tin_no') ? 'has-error': ''}}">
                                                    {!! Form::label('existing_tin_no','Existing(10 Digits) TIN',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('existing_tin_no', $appInfoEtin->existing_tin_no, ['class'=>'form-control input-md ', 'id' => 'existing_tin_no']) !!}
                                                        {!! $errors->first('existing_tin_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group cat-1 hide {{$errors->has('main_source_income') ? 'has-error': ''}}">
                                                    {!! Form::label('main_source_income','Main Source of Income',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('main_source_income', $mainSourceIncome,$appInfoEtin->main_source_income, ['class'=>'form-control input-md ', 'id' => 'main_source_income']) !!}
                                                        {!! $errors->first('main_source_income','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cat-1 hide form-group {{$errors->has('main_source_income_location') ? 'has-error': ''}}">
                                                    {!! Form::label('main_source_income_location','Location of main source of income',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('main_source_income_location', $districts,$appInfoEtin->main_source_income_location, ['class'=>'form-control input-md ', 'id' => 'main_source_income_location']) !!}
                                                        {!! $errors->first('main_source_income_location','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cat-1 hide form-group {{$errors->has('company_id') ? 'has-error': ''}}">
                                                    {!! Form::label('company_id','Company',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('company_id', $companies,$appInfoEtin->company_id, ['class'=>'form-control input-md ', 'id' => 'company_id']) !!}
                                                        {!! $errors->first('company_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-body ">
                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name','Name of Organization/ Company/ Industrial Project',['class'=>'col-md-5 text-left ']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('company_name', \App\Libraries\CommonFunction::getCompanyNameById(\Illuminate\Support\Facades\Auth::user()->company_ids), ['class' => 'form-control input-md required','readonly']) !!}
                                                        {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group {{$errors->has('incorporation_certificate_number') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_certificate_number','Incorporation Number',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('incorporation_certificate_number', $appInfoEtin->incorporation_certificate_number, ['class' => 'form-control input-md number','id'=>'incorporation_certificate_number','style'=>"display:inline"]) !!}

                                                        {!! $errors->first('incorporation_certificate_number','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="date_of_incorpotation_info" class="col-md-12 form-group {{$errors->has('incorporation_certificate_date') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_certificate_date','Date of incorporation',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <div class="date_of_incorpotation input-group date">
                                                            {!! Form::text('incorporation_certificate_date', ($appInfoEtin->incorporation_certificate_date == '0000-00-00' ? '' : date('d-M-Y', strtotime($appInfoEtin->incorporation_certificate_date))), ['class' => 'form-control input-md','id'=>'incorporation_certificate_date','style'=>"display:inline",'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('incorporation_certificate_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_designation','Principal Promoter Designation',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_designation', $appInfoEtin->ceo_designation, ['class' => 'form-control input-md ','id'=>'ceo_designation','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_full_name', $appInfoEtin->ceo_full_name, ['class' => 'form-control input-md ','id'=>'ceo_full_name','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_mobile_no','Mobile Number',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_mobile_no', $appInfoEtin->ceo_mobile_no, ['class' => 'form-control input-md','id'=>'ceo_mobile_no','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('ceo_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_fax_no','Fax',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_fax_no', $appInfoEtin->ceo_fax_no, ['class' => 'form-control input-md number','id'=>'ceo_fax_no','style'=>"display:inline"]) !!}

                                                        {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_email','Email',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_email', $appInfoEtin->ceo_email, ['class' => 'form-control input-md ','id'=>'ceo_email','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>


                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>Principal Promoter Address</strong></div>
                                    <div class="panel-body readOnlyCl">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_country_id', $countries, $appInfoEtin->ceo_country_id, ['class' => 'form-control  input-md ','id'=>'ceo_country_id','readonly']) !!}
                                                        {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div id="" class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_thana_id','Police Station',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_thana_id',$thana, $appInfoEtin->ceo_thana_id, ['maxlength'=>'80','class' => 'form-control input-md','placeholder' => 'Select district first','readonly']) !!}
                                                        {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div id="" class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_district_id','District ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_district_id',$districts, $appInfoEtin->ceo_district_id, ['maxlength'=>'80','class' => 'form-control input-md','readonly']) !!}
                                                        {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_post_code','Post Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_post_code', $appInfoEtin->ceo_post_code, ['maxlength'=>'80','class' => 'form-control input-md engOnly ','readonly']) !!}
                                                        {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('ceo_address') ? 'has-error' : ''}}">
                                                    {!! Form::label('ceo_address','Address',['class'=>'col-md-2']) !!}
                                                    <div class="col-md-10 ">
                                                        {!! Form::textarea('ceo_address', $appInfoEtin->ceo_address, ['class' => 'input-md bigInputField ml-20 readOnly', 'size' =>'5x2','data-rule-maxlength'=>'240','readonly','style'=>"background-color:#eee;opacity:1;"]) !!}
                                                        {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>Registered Office Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('reg_office_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('reg_office_country_id','Country',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('reg_office_country_id', $countries, $appInfoEtin->reg_office_country_id, ['class' => 'form-control  imput-md', 'id' => 'reg_office_country_id']) !!}
                                                        {!! $errors->first('reg_office_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_thana_id',$thana, $appInfoEtin->office_thana_id, ['class' => 'form-control input-md ','placeholder' => 'Select district first','readonly']) !!}
                                                        {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_district_id', $districts, $appInfoEtin->office_district_id, ['class' => 'form-control input-md ','placeholder' => 'Select division first','readonly']) !!}
                                                        {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('office_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_post_code', $appInfoEtin->office_post_code, ['class' => 'form-control input-md','readonly']) !!}
                                                        {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class=" col-md-12 {{$errors->has('office_address') ? 'has-error' : ''}}">
                                                    {!! Form::label('office_address','Address',['class'=>'col-md-2']) !!}
                                                    <div class="col-md-10">
                                                        {!! Form::textarea('office_address', $appInfoEtin->office_address, ['class' => 'form-control input-md bigInputField ml-20', 'size' =>'5x2','data-rule-maxlength'=>'240','readonly']) !!}
                                                        {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>Others Address ( Working address/ Business address)</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('other_address_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_country_id','Country',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('other_address_country_id', $countries, $appInfoEtin->other_address_country_id , ['class' => 'form-control  imput-md', 'id' => 'other_address_country_id']) !!}
                                                        {!! $errors->first('other_address_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('other_address_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('other_address_thana_id',$thana, $appInfoEtin->other_address_thana_id, ['class' => 'form-control input-md ','placeholder' => 'Select district first','id'=>'other_address_thana_id']) !!}
                                                        {!! $errors->first('other_address_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6  {{$errors->has('other_address_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_district_id','District',['class'=>'col-md-5 text-left ']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('other_address_district_id', $districts, $appInfoEtin->other_address_district_id, ['class' => 'form-control input-md ','placeholder' => 'Select One','id'=>'other_address_district_id','onchange'=>"getThanaByDistrictId('other_address_district_id', this.value, 'other_address_thana_id')"]) !!}
                                                        {!! $errors->first('other_address_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('other_address_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_post_code','Post Code', ['class'=>'col-md-5 text-left ']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('other_address_post_code', $appInfoEtin->other_address_post_code, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('other_address_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class=" col-md-12 {{$errors->has('other_address') ? 'has-error' : ''}}">
                                                    {!! Form::label('other_address','Address',['class'=>'col-md-2']) !!}
                                                    <div class="col-md-10">
                                                        {!! Form::textarea('other_address',$appInfoEtin->other_address, ['class' => 'form-control input-md bigInputField ml-20', 'size' =>'5x2','data-rule-maxlength'=>'240']) !!}
                                                        {!! $errors->first('other_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                                @if(ACL::getAccsessRight('E-tin','-E-') && $viewMode == "off")
                                    @if($appInfoEtin->status_id != 5)
                                        <button type="submit" class="btn btn-info btn-md cancel"
                                                value="draft" name="actionBtn">Save as Draft
                                        </button>
                                        <div class="pull-right">
                                            <button type="submit" id="" style="cursor: pointer;" class="btn btn-info btn-md submit"
                                                    value="Submit" name="actionBtn">Save & Next
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
                            {!! Form::close() !!}

                    </div>


                </div>
                {{--End application form with wizard--}}
            </div>
        </div>
    </div>


<script  type="text/javascript">

    $(document).ready(function(){
        $("#organization_type_id option:not(:selected)").prop('disabled', true);
            $(".readOnlyCl option:not(:selected)").prop('disabled', true);
            $("#office_thana_id option:not(:selected)").prop('disabled', true);
            $("#office_district_id option:not(:selected)").prop('disabled', true);
                $('#reg_type').on('change',function(){
            var cat_id = $(this).val();
            if(cat_id == 1){
                $('.cat-1').removeClass('hide').slideDown(200);
                $('.cat-2').hide();
                $('.cat-2 input').each(function () {
                   $(this).val('');
                    //$(this).val('');
                });
            }
            else if(cat_id == 2){
                $('.cat-2').removeClass('hide').slideDown(200);
                $('.cat-1').hide();
                $('.cat-1 select').each(function () {
                    $(this).val('');
                    //$(this).val('');
                });
            }
            else{
                $('.cat-1').hide(100);
                $('.cat-2').hide(100);
            }
        });
        $('#reg_type').trigger('change');
        $('.date_of_incorpotation').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
        });

        $('.commercial_operation_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
        });

        $('#EtinFormApplication').validate();
        $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea, .readOnly').attr('readonly',true);

    });



    $("#office_division_id").change(function () {
        var divisionId = $('#office_division_id').val();
        $(this).after('<span class="loading_data">Loading...</span>');
        var self = $(this);
        $.ajax({
            type: "GET",
            url: "<?php echo url(); ?>/licence-application/get-district-by-division",
            data: {
                divisionId: divisionId
            },
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {
                        option += '<option value="' + id + '">' + value + '</option>';
                    });
                }
                $("#office_district_id").html(option);
                $(self).next().hide();
            }
        });
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();

        $('#registered_by_id').change(function (e) {
            var type = this.value;
            if(type == 1){
                $("#registration_no_label").html('Registration Number');
            }else{
                $("#registration_no_label").html('Incorporation Certificate Number');
            }


            if (type == 1 || type == 3 || type == 4) {
                $('#registration_no_div').show('slow');
                $('#registration_no').addClass('required');
                $('#registration_copy_div').show('slow');
                $("#registration_copy_label").addClass('required-star');
                $("#registration_copy_label").html('Attach the copy of Incorporation Certificate');
                $('#registration_copy').addClass('required');
                $('#registration_other_div').hide('slow');
                //$('.registrationFieldRequired').addClass('required');
            } else if(type == 12) {
                $('#registration_other_div').show('slow');
                $('#registration_no_div').hide('slow');
                $('#registration_no').removeClass('required');
                $('#registration_copy_div').hide('slow');
                $("#registration_copy_label").removeClass('required-star');
                $("#registration_copy_label").html('Attach the copy of Registration/Permission No.');
                $('#registration_copy').removeClass('required');
            } else {
                $('#registration_no_div').hide('slow');
                $('#registration_no').removeClass('required');
                $('#registration_copy_div').hide('slow');
                $("#registration_copy_label").removeClass('required-star');
                $("#registration_copy_label").html('Attach the copy of Registration/Permission No.');
                $('#registration_copy').removeClass('required');
                $('#registration_other_div').hide('slow');
                //$('.registrationFieldRequired').removeClass('required');
            }
        });

        $('input[name=is_registered]:checked').trigger('click');

        // ceo country, city district, state thana, father, mother
        $('#ceo_country_id').change(function (e) {
            var country_id = this.value;
            if (country_id == '18') {
                $("#ceo_city_div").addClass('hidden');
                $("#ceo_city").removeClass('required');
                $("#ceo_state_div").addClass('hidden');
                $("#ceo_state").removeClass('required');
                $("#ceo_passport_div").addClass('hidden');
                $("#ceo_passport_no").removeClass('required');


                $("#ceo_district_div").removeClass('hidden');
                $("#ceo_district_id").addClass('required');
                $("#ceo_thana_div").removeClass('hidden');
                $("#ceo_thana_id").addClass('required');
                $("#ceo_nid_div").removeClass('hidden');
                $("#ceo_nid").addClass('required');

                $("#ceo_father_label").addClass('required-star');
                $("#ceo_father_name").addClass('required');
                $("#ceo_mother_label").addClass('required-star');
                $("#ceo_mother_name").addClass('required');
            } else {
                $("#ceo_city_div").removeClass('hidden');
                $("#ceo_city").addClass('required');
                $("#ceo_state_div").removeClass('hidden');
                $("#ceo_state").addClass('required');
                $("#ceo_passport_div").removeClass('hidden');
                $("#ceo_passport_no").addClass('required');

                $("#ceo_district_div").addClass('hidden');
                $("#ceo_district_id").removeClass('required');
                $("#ceo_thana_div").addClass('hidden');
                $("#ceo_thana_id").removeClass('required');
                $("#ceo_nid_div").addClass('hidden');
                $("#ceo_nid").removeClass('required');

                $("#ceo_father_label").removeClass('required-star');
                $("#ceo_father_name").removeClass('required');
                $("#ceo_mother_label").removeClass('required-star');
                $("#ceo_mother_name").removeClass('required');
            }
        });
        $('#ceo_country_id').trigger('change');

//        $('[data-toggle="tooltip"]').tooltip();


        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker_registration_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/'+(yyyy-50),
            maxDate: today,
        });

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
//            minDate: '01/01/'+(yyyy-10),
//            maxDate: '01/01/'+(yyyy+10)
            maxDate: 'now'
        });


        $("#ceo_district_id").change(function () {
            var districtId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if (id == '{{ $appInfoEtin->ceo_thana_id }}'){
                                option += '<option value="'+ id + '" selected>' + value + '</option>';
                            }else {
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#ceo_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });
       // $('#ceo_district_id').trigger('change');

        $("#office_district_id").change(function () {
            var districtId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if (id == '{{ $appInfoEtin->office_thana_id }}'){
                                option += '<option value="'+ id + '" selected>' + value + '</option>';
                            }else {
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#office_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });
//        $('#office_district_id').trigger('change');

        $("#factory_district_id").change(function () {
            var districtId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if (id == '{{ $appInfoEtin->factory_thana_id }}'){
                                option += '<option value="'+ id + '" selected>' + value + '</option>';
                            }else {
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#factory_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });
        $('#factory_district_id').trigger('change');
    });


    @if ($viewMode == 'on')
        $('#EtinFormApplication :input').attr('disabled', true);
    $('#EtinFormApplication h3').hide();
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">'+this.value+'</span>');
    });
    // all radio or checkbox which is not checked, that will be hide
    $('#EtinFormApplication :input[type=radio], input[type=checkbox]').each(function () {
        if(!$(this).is(":checked")){
            //alert($(this).attr('name'));
            $(this).parent().replaceWith('');
            $(this).replaceWith('');
        }
    });
    $('#EtinFormApplication :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on

</script>
<script src="{{ asset("assets/scripts/custom.js") }}" type="text/javascript"></script>