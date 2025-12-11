<?php
$accessMode = ACL::getAccsessRight('SingleLicence');
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
    .hide{
        display: none;
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

                    <div class="panel-body">
                        {!! Form::open(array('url' => '/single-licence/e-tin/add','method' => 'post','id' => 'EtinApplicationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}

                    <div class="form-body">

                        <div>
                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>

                            <br/>
                            <fieldset>
                                <div class="panel panel-info">
                                    {{--<div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>--}}
                                    <div class="panel-body">
                                        <div id="validationError"></div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 form-group {{$errors->has('taxpayer_status') ? 'has-error': ''}}">
                                                    {!! Form::label('taxpayer_status','Taxpayer\'s Status',['class'=>'required-star col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('taxpayer_status', $taxpayerStatus,'', ['class'=>'required form-control input-md', 'id' => 'taxpayer_status']) !!}
                                                        {!! $errors->first('taxpayer_status','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                    {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('organization_type_id', $eaOrganizationType,$basicAppInfo->organization_type_id, ['class'=>'form-control input-md', 'id' => 'organization_type_id','readonly']) !!}
                                                        {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('reg_type') ? 'has-error': ''}}">
                                                    {!! Form::label('reg_type','Registration Type',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('reg_type', $registrationType,'', ['class'=>'form-control input-md', 'id' => 'reg_type']) !!}
                                                        {!! $errors->first('reg_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>




                                                <div class="col-md-12 form-group cat-2 hide {{$errors->has('existing_tin_no') ? 'has-error': ''}}">
                                                    {!! Form::label('existing_tin_no','Existing(10 Digits) TIN',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('existing_tin_no', '', ['class'=>'form-control input-md ', 'id' => 'existing_tin_no']) !!}
                                                        {!! $errors->first('existing_tin_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group cat-1 hide {{$errors->has('main_source_income') ? 'has-error': ''}}">
                                                    {!! Form::label('main_source_income','Main Source of Income',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('main_source_income', $mainSourceIncome,'', ['class'=>'form-control input-md ', 'id' => 'main_source_income']) !!}
                                                        {!! $errors->first('main_source_income','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cat-1 hide form-group {{$errors->has('main_source_income_location') ? 'has-error': ''}}">
                                                    {!! Form::label('main_source_income_location','Location of main source of income',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('main_source_income_location', $districts,'', ['class'=>'form-control input-md ', 'id' => 'main_source_income_location']) !!}
                                                        {!! $errors->first('main_source_income_location','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cat-1 hide form-group {{$errors->has('company_id') ? 'has-error': ''}}">
                                                    {!! Form::label('company_id','Company',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('company_id', $companies,'', ['class'=>'form-control input-md ', 'id' => 'company_id']) !!}
                                                        {!! $errors->first('company_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>



                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    {{--<div class="panel-heading "><strong>B. Information of Principal Promoter/Chairman/Managing Director/CEO/Country Manager</strong></div>--}}
                                    <div class="panel-body">
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
                                                     {!! Form::text('incorporation_certificate_number', $basicAppInfo->incorporation_certificate_number, ['class' => 'form-control input-md','id'=>'incorporation_certificate_number','style'=>"display:inline"]) !!}

                                                     {!! $errors->first('incorporation_certificate_number','<span class="help-block">:message</span>') !!}
                                                 </div>
                                            </div>
                                            <div id="date_of_incorpotation_info" class="col-md-12 form-group {{$errors->has('incorporation_certificate_date') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_certificate_date','Date of incorporation',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <div class="date_of_incorpotation input-group date">
                                                            {!! Form::text('incorporation_certificate_date', ($basicAppInfo->incorporation_certificate_date == '0000-00-00' ? '' : date('d-M-Y', strtotime($basicAppInfo->incorporation_certificate_date))), ['class' => 'form-control input-md','id'=>'incorporation_certificate_date','style'=>"display:inline",'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('incorporation_certificate_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                            </div>

                                            <div class="col-md-12 form-group {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_designation','Principal Promoter Designation',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_designation', $basicAppInfo->ceo_designation, ['class' => 'form-control input-md ','id'=>'ceo_designation','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                    </div>
                                            </div>
                                            <div class="col-md-12 form-group {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_full_name', $basicAppInfo->ceo_full_name, ['class' => 'form-control input-md ','id'=>'ceo_full_name','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                            </div>
                                            <div class="col-md-12 form-group {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_mobile_no','Mobile Number',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_mobile_no', $basicAppInfo->ceo_mobile_no, ['class' => 'form-control input-md','id'=>'ceo_mobile_no','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                            </div>

                                            <div class="col-md-12 form-group {{$errors->has('ceo_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_fax_no','Fax',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_fax_no', $basicAppInfo->ceo_fax_no, ['class' => 'form-control input-md','id'=>'ceo_fax_no','style'=>"display:inline"]) !!}

                                                        {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                            </div>

                                            <div class="col-md-12 form-group {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_email','Email',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_email', $basicAppInfo->ceo_email, ['class' => 'form-control input-md ','id'=>'ceo_email','style'=>"display:inline",'readonly']) !!}

                                                        {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                            </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>Principal Promoter Address</strong></div>
                                    <div class="panel-body readOnlyCl">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_country_id', $countries, $basicAppInfo->ceo_country_id, ['class' => 'form-control  input-md ','id'=>'ceo_country_id','readonly']) !!}
                                                        {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div id="ceo_thana_div" class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_thana_id','Police Station',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_thana_id',$thana, $basicAppInfo->ceo_thana_id, ['maxlength'=>'80','class' => 'form-control input-md','placeholder' => 'Select district first','readonly']) !!}
                                                        {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div id="ceo_district_div" class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_district_id','District ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_district_id',$districts, $basicAppInfo->ceo_district_id, ['maxlength'=>'80','class' => 'form-control input-md','readonly']) !!}
                                                        {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_post_code','Post Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_post_code', $basicAppInfo->ceo_post_code, ['maxlength'=>'80','class' => 'form-control input-md ','readonly']) !!}
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
                                                        {!! Form::textarea('ceo_address', 'House, Flat/ Apartment, Road: '.$basicAppInfo->ceo_address. ', District/ City/ State: '.$basicAppInfo->ceo_city.', State/ Province: '.$basicAppInfo->ceo_state , ['class' => 'input-md bigInputField ml-20', 'size' =>'5x2','data-rule-maxlength'=>'240','readonly','style'=>"background-color:#eee;opacity:1;"]) !!}
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
                                                        {!! Form::select('reg_office_country_id', $countries, 18, ['class' => 'form-control  imput-md', 'id' => 'reg_office_country_id']) !!}
                                                        {!! $errors->first('reg_office_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_thana_id',$thana, $basicAppInfo->office_thana_id, ['class' => 'form-control input-md ','placeholder' => 'Select district first','readonly']) !!}
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
                                                        {!! Form::select('office_district_id', $districts, $basicAppInfo->office_district_id, ['class' => 'form-control input-md ','placeholder' => 'Select division first','readonly']) !!}
                                                        {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('office_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_post_code', $basicAppInfo->office_post_code, ['class' => 'form-control input-md','readonly']) !!}
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
                                                        {!! Form::textarea('office_address', 'House, Flat/ Apartment, Road: '.$basicAppInfo->office_address.',Post Office: '.$basicAppInfo->office_post_office.', Division: '.$divisions[$basicAppInfo->office_division_id], ['class' => 'form-control input-md bigInputField ml-20', 'size' =>'5x2','data-rule-maxlength'=>'240','readonly']) !!}
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
                                                        {!! Form::select('other_address_country_id', $countries, 18, ['class' => 'form-control  imput-md', 'id' => 'other_address_country_id']) !!}
                                                        {!! $errors->first('other_address_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('other_address_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('other_address_thana_id',[], '', ['class' => 'form-control input-md ','placeholder' => 'Select district first','id'=>'other_address_thana_id']) !!}
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
                                                        {!! Form::select('other_address_district_id', $districts, '', ['class' => 'form-control input-md ','placeholder' => 'Select One','id'=>'other_address_district_id','onchange'=>"getThanaByDistrictId('other_address_district_id', this.value, 'other_address_thana_id')"]) !!}
                                                        {!! $errors->first('other_address_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('other_address_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_post_code','Post Code', ['class'=>'col-md-5 text-left ']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('other_address_post_code', '', ['class' => 'form-control input-md']) !!}
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
                                                        {!! Form::textarea('other_address','', ['class' => 'form-control input-md bigInputField ml-20', 'size' =>'5x2','data-rule-maxlength'=>'240']) !!}
                                                        {!! $errors->first('other_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>

                            @if(ACL::getAccsessRight('E-tin','-E-'))
                                <button type="submit" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                                <button type="submit" class="btn btn-info btn-md submit pull-right"
                                        value="save" name="actionBtn">Save & Next
                                </button>
                            @endif

                        </div>

                    </div>
                        {!! Form::close() !!}
                </div>
                </div>
                {{--End application form with wizard--}}

            </div>
        </div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.css">



<script  type="text/javascript">


    $(document).ready(function(){

        $("#organization_type_id option:not(:selected)").prop('disabled', true);
        $(".readOnlyCl option:not(:selected)").prop('disabled', true);
        $("#office_thana_id option:not(:selected)").prop('disabled', true);
        $("#office_district_id option:not(:selected)").prop('disabled', true);
        $('.date_of_incorpotation').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
        });
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

    $('#EtinApplicationForm').validate();
    $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea,.readOnly').attr('readonly',true);

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

    $(document).ready(function () {


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


        $('.commercial_operation_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
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
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#ceo_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });
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
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#office_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });
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
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#factory_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });
    });





</script>