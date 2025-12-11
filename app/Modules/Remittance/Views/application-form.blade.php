<?php
$accessMode = ACL::getAccsessRight('Remittance');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">

<style>
    .form-group{
        margin-bottom: 2px;
    }
    .table{
        margin-bottom: 5px;
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
    .wizard > .steps .number {
        font-size: 1.2em;
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
    .img-user {
        width: 120px;
        height: 120px;
        float: right;
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
                            <h5><strong>Application for Outward Remittance Approval</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/remittance_new.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'remittance-new/store','method' => 'post','id' => 'RemittanceNewForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}
                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        <h3 class="stepHeader">Basic Information</h3>
                        <fieldset>
                            <legend class="d-none">Basic Information</legend>
                            <div class="panel panel-info readOnlyCl">
                                <div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                {!! Form::label('company_name','Name of Organization/ Company/ Industrial Project',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('company_name', \App\Libraries\CommonFunction::getCompanyNameById(\Illuminate\Support\Facades\Auth::user()->company_ids), ['class' => 'form-control input-md','readonly']) !!}
                                                    {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                                {!! Form::label('company_name_bn','Name of Organization/ Company/ Industrial Project (বাংলা)',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('company_name_bn', \App\Libraries\CommonFunction::getCompanyBnNameById(\Illuminate\Support\Facades\Auth::user()->company_ids), ['class' => 'form-control input-md','readonly']) !!}
                                                    {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('origin_country_id','Country of Origin',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('origin_country_id',$countries, $getCompanyData->country_of_origin_id,['class'=>'form-control input-md', 'id' => 'origin_country_id']) !!}
                                                    {!! $errors->first('origin_country_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('organization_type_id', $eaOrganizationType, $getCompanyData->organization_type_id, ['class' => 'form-control input-md ','id'=>'organization_type_id','readonly']) !!}
                                                    {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('organization_status_id') ? 'has-error': ''}}">
                                                {!! Form::label('organization_status_id','Status of the organization',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('organization_status_id', $eaOrganizationStatus, $getCompanyData->organization_status_id, ['class' => 'form-control input-md','id'=>'organization_status_id']) !!}
                                                    {!! $errors->first('organization_status_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                                                {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('ownership_status_id', $eaOwnershipStatus, $getCompanyData->ownership_status_id, ['class' => 'form-control input-md ','id'=>'ownership_status_id','readonly']) !!}
                                                    {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('business_sector_id') ? 'has-error': ''}}">
                                                {!! Form::label('business_sector_id','Business sector',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_sector_id', $sectors, (!empty(Session::get('appInfo.business_sector_id')) ? Session::get('appInfo.business_sector_id') : $getCompanyData->business_sector_id), ['class' => 'form-control input-md', 'id'=>'business_sector_id', 'onchange'=>"LoadSubSector(this.value, 'SECTOR_OTHERS', 'business_sector_others', 'business_sub_sector_id',". (!empty(Session::get('appInfo.business_sub_sector_id')) ? Session::get('appInfo.business_sub_sector_id') : $getCompanyData->business_sub_sector_id) .")"]) !!}
                                                    {!! $errors->first('business_sector_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                                <div style="margin-top: 10px;" class="col-md-12" id="SECTOR_OTHERS" hidden>
                                                    {!! Form::textarea('business_sector_others', (!empty(Session::get('appInfo.business_sector_others')) ? Session::get('appInfo.business_sector_others') : $getCompanyData->business_sector_others), ['placeholder'=>'Specify others sector', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                        'id' => 'business_sector_others', 'size'=>'5x1','data-charcount-maxlength'=>'200']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('business_sub_sector_id') ? 'has-error': ''}}">
                                                {!! Form::label('business_sub_sector_id','Sub sector',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('business_sub_sector_id', $sub_sectors, (!empty(Session::get('appInfo.business_sub_sector_id')) ? Session::get('appInfo.business_sub_sector_id') : $getCompanyData->business_sub_sector_id), ['class' => 'form-control input-md', 'id'=>'business_sub_sector_id', 'onchange'=>"SubSectorOthersDiv(this.value, 'SUB_SECTOR_OTHERS', 'business_sub_sector_others')"]) !!}
                                                    {!! $errors->first('business_sub_sector_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                                <div style="margin-top: 10px;" class="col-md-12" id="SUB_SECTOR_OTHERS" hidden>
                                                    {!! Form::textarea('business_sub_sector_others', (!empty(Session::get('appInfo.business_sub_sector_others')) ? Session::get('appInfo.business_sub_sector_others') : $getCompanyData->business_sub_sector_others), ['placeholder'=>'Specify others sub-sector', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                        'id' => 'business_sub_sector_others', 'size'=>'5x1','data-charcount-maxlength'=>'200']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                                                {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::textarea('major_activities', $getCompanyData->major_activities, ['class' => 'form-control input-md bigInputField maxTextCountDown', 'size' =>'5x2','data-rule-maxlength'=>'200', 'placeholder' => 'Maximum 200 characters','data-charcount-maxlength'=>'200','readonly']) !!}
                                                    {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info readOnlyCl">
                                <div class="panel-heading"><strong>B. Information of Principal Promoter/ Chairman/ Managing Director/ CEO/ Country <Manager></Manager></strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('ceo_country_id', $countries, $getCompanyData->ceo_country_id, ['class' => 'form-control input-md ','id'=>'ceo_country_id','readonly']) !!}
                                                    {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5']) !!}
                                                <div class=" col-md-7">
                                                    <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">
                                                        {!! Form::text('ceo_dob', ($getCompanyData->ceo_dob == '0000-00-00' ? '' : date('d-M-Y', strtotime($getCompanyData->ceo_dob))), ['class'=>'form-control input-md', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker','readonly']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div id="ceo_passport_div" class="col-md-6 hidden {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_passport_no', $getCompanyData->ceo_passport_no, ['maxlength'=>'20',
                                                    'class' => 'form-control input-md', 'id'=>'ceo_passport_no','readonly']) !!}
                                                    {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="ceo_nid_div" class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_nid', $getCompanyData->ceo_nid, ['maxlength'=>'20',
                                                    'class' => 'form-control number input-md required bd_nid','id'=>'ceo_nid', 'readonly']) !!}
                                                    {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_designation', $getCompanyData->ceo_designation,
                                                    ['maxlength'=>'80','class' => 'form-control input-md required', 'readonly']) !!}
                                                    {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_full_name', $getCompanyData->ceo_full_name, ['maxlength'=>'80',
                                                    'class' => 'form-control input-md required', 'readonly']) !!}
                                                    {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="ceo_district_div" class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_district_id','District/ City/ State ',['class'=>'col-md-5 text-left required-star', 'readonly']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('ceo_district_id', $districts, $getCompanyData->ceo_district_id, ['class' => 'form-control input-md', 'readonly', 'onchange'=>"getThanaByDistrictId('ceo_district_id', this.value, 'ceo_thana_id')"]) !!}
                                                    {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="ceo_city_div" class="col-md-6 hidden {{$errors->has('ceo_city') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_city','District/City/State',['class'=>'text-left  col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_city', $getCompanyData->ceo_city,['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div id="ceo_state_div" class="col-md-6 hidden {{$errors->has('ceo_state') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_state','State / Province',['class'=>'text-left  col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_state', $getCompanyData->ceo_state,['class' => 'form-control input-md', 'readonly']) !!}
                                                    {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="ceo_thana_div" class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_thana_id','Police Station/Town ',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('ceo_thana_id', $thana, $getCompanyData->ceo_thana_id, ['class' => 'form-control input-md','placeholder' => 'Select district first', 'readonly']) !!}
                                                    {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_post_code','Post/ Zip Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_post_code', $getCompanyData->ceo_post_code, ['maxlength'=>'80','class' => 'form-control input-md engOnly required', 'readonly']) !!}
                                                    {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ceo_address') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_address', $getCompanyData->ceo_address, ['maxlength'=>'150', 'class' => 'form-control input-md required', 'readonly']) !!}
                                                    {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_telephone_no', $getCompanyData->ceo_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md','readonly', 'id' => 'ceo_telephone_no']) !!}
                                                    {!! $errors->first('ceo_telephone_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_mobile_no', $getCompanyData->ceo_mobile_no, ['class' => 'form-control input-md required phone_or_mobile','id' => 'ceo_mobile_no','readonly']) !!}
                                                    {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_father_label']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_father_name', $getCompanyData->ceo_father_name, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                    {!! $errors->first('ceo_father_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::email('ceo_email', $getCompanyData->ceo_email, ['class' => 'form-control email input-md required', 'readonly']) !!}
                                                    {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_mother_label']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_mother_name', $getCompanyData->ceo_mother_name, ['class' => 'form-control required input-md', 'readonly']) !!}
                                                    {!! $errors->first('ceo_mother_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ceo_fax_no') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_fax_no', $getCompanyData->ceo_fax_no, ['class' => 'form-control input-md' ,'readonly']) !!}
                                                    {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('ceo_spouse_name', $getCompanyData->ceo_spouse_name, ['class' => 'form-control input-md', 'readonly']) !!}
                                                    {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('ceo_gender') ? 'has-error': ''}}">
                                                {!! Form::label('ceo_gender','Gender', ['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <label class="radio-inline">
                                                        {!! Form::radio('ceo_gender', 'Male', !empty($getCompanyData->ceo_gender) && $getCompanyData->ceo_gender == "Male", ['class'=>'required']) !!}
                                                        Male
                                                    </label>
                                                    <label class="radio-inline">
                                                        {!! Form::radio('ceo_gender', 'Female', !empty($getCompanyData->ceo_gender) && $getCompanyData->ceo_gender == "Female", ['class'=>'required']) !!}
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>C. Office Address</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                                                {!! Form::label('office_division_id','Division',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('office_division_id', $divisions, (!empty(Session::get('appInfo.office_division_id')) ? Session::get('appInfo.office_division_id') : $getCompanyData->office_division_id), ['class' => 'form-control input-md', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id')"]) !!}
                                                    {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('office_district_id', $districts, (!empty(Session::get('appInfo.office_district_id')) ? Session::get('appInfo.office_district_id') : $getCompanyData->office_district_id), ['class' => 'form-control input-md','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id')"]) !!}
                                                    {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                                                {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('office_thana_id', $thana, (!empty(Session::get('appInfo.office_thana_id')) ? Session::get('appInfo.office_thana_id') : $getCompanyData->office_thana_id), ['class' => 'form-control input-md','placeholder' => 'Select district first']) !!}
                                                    {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('office_post_office') ? 'has-error': ''}}">
                                                {!! Form::label('office_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_post_office', (!empty(Session::get('appInfo.office_post_office')) ? Session::get('appInfo.office_post_office') : $getCompanyData->office_post_office), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_post_code') ? 'has-error': ''}}">
                                                {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_post_code', (!empty(Session::get('appInfo.office_post_code')) ? Session::get('appInfo.office_post_code') : $getCompanyData->office_post_code), ['class' => 'form-control input-md post_code_bd']) !!}
                                                    {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('office_address') ? 'has-error': ''}}">
                                                {!! Form::label('office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_address', (!empty(Session::get('appInfo.office_address')) ? Session::get('appInfo.office_address') : $getCompanyData->office_address), ['maxlength'=>'150', 'class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_telephone_no') ? 'has-error': ''}}">
                                                {!! Form::label('office_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_telephone_no', (!empty(Session::get('appInfo.office_telephone_no')) ? Session::get('appInfo.office_telephone_no') : $getCompanyData->office_telephone_no), ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                    {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('office_mobile_no') ? 'has-error': ''}}">
                                                {!! Form::label('office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_mobile_no', (!empty(Session::get('appInfo.office_mobile_no')) ? Session::get('appInfo.office_mobile_no') : $getCompanyData->office_mobile_no), ['class' => 'form-control input-md helpText15' ,'id' => 'office_mobile_no']) !!}
                                                    {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('office_fax_no') ? 'has-error': ''}}">
                                                {!! Form::label('office_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_fax_no', (!empty(Session::get('appInfo.office_fax_no')) ? Session::get('appInfo.office_fax_no') : $getCompanyData->office_fax_no), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('office_email') ? 'has-error': ''}}">
                                                {!! Form::label('office_email','Email ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::email('office_email', (!empty(Session::get('appInfo.office_email')) ? Session::get('appInfo.office_email') : $getCompanyData->office_email), ['class' => 'form-control email input-md']) !!}
                                                    {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>D. Factory Address</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                                {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('factory_district_id', $districts, (!empty(Session::get('appInfo.factory_district_id')) ? Session::get('appInfo.factory_district_id') : $getCompanyData->factory_district_id), ['class' => 'form-control input-md', 'onchange'=>"getThanaByDistrictId('factory_district_id', this.value, 'factory_thana_id')"]) !!}
                                                    {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('factory_thana_id') ? 'has-error': ''}}">
                                                {!! Form::label('factory_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('factory_thana_id', $thana, (!empty(Session::get('appInfo.factory_thana_id')) ? Session::get('appInfo.factory_thana_id') : $getCompanyData->factory_thana_id), ['class' => 'form-control input-md','placeholder' => 'Select district first']) !!}
                                                    {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('factory_post_office') ? 'has-error': ''}}">
                                                {!! Form::label('factory_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_post_office', (!empty(Session::get('appInfo.factory_post_office')) ? Session::get('appInfo.factory_post_office') : $getCompanyData->factory_post_office), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                                {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_post_code', (!empty(Session::get('appInfo.factory_post_code')) ? Session::get('appInfo.factory_post_code') : $getCompanyData->factory_post_code), ['class' => 'form-control input-md engOnly']) !!}
                                                    {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('factory_address') ? 'has-error': ''}}">
                                                {!! Form::label('factory_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_address', (!empty(Session::get('appInfo.factory_address')) ? Session::get('appInfo.factory_address') : $getCompanyData->factory_address), ['maxlength'=>'150','class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                                {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_telephone_no', (!empty(Session::get('appInfo.factory_telephone_no')) ? Session::get('appInfo.factory_telephone_no') : $getCompanyData->factory_telephone_no), ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                    {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('factory_mobile_no') ? 'has-error': ''}}">
                                                {!! Form::label('factory_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_mobile_no', (!empty(Session::get('appInfo.factory_mobile_no')) ? Session::get('appInfo.factory_mobile_no') : $getCompanyData->factory_mobile_no), ['class' => 'form-control input-md','id' => 'factory_mobile_no']) !!}
                                                    {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_fax_no', (!empty(Session::get('appInfo.factory_fax_no')) ? Session::get('appInfo.factory_fax_no') : $getCompanyData->factory_fax_no), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('factory_email') ? 'has-error': ''}}">
                                                {!! Form::label('factory_email','Email ',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_email', (!empty(Session::get('appInfo.factory_email')) ? Session::get('appInfo.factory_email') : $getCompanyData->factory_email), ['class' => 'form-control email input-md']) !!}
                                                    {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                                {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('factory_mouja', (!empty(Session::get('appInfo.factory_mouja')) ? Session::get('appInfo.factory_mouja') : $getCompanyData->factory_mouja), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Details Information</h3>
                        <fieldset>
                            {{--1. Basic instructions--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>1. Basic instructions</strong></div>
                                <div class="panel-body">
                                    {{--Remittance type--}}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('remittance_type_id') ? 'has-error': ''}}">
                                                {!! Form::label('remittance_type_id','Type of the Remittance',['class'=>'col-md-3 text-left']) !!}
                                                <div class="col-md-9">
                                                    {!! Form::select('remittance_type_id', $remittanceType, (!empty(Session::get('appInfo.remittance_type_id')) ? Session::get('appInfo.remittance_type_id') : ['0' => 'Select One']),
                                                            ['class' => 'form-control input-md', 'placeholder' => 'Select One', 'id'=>'remittanceType', 'onchange' => "CategoryWiseDocLoad(this.value)"]) !!}
                                                    {!! $errors->first('remittance_type_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="scheduler-border hidden" id="intellectual_property_div" style="margin-top: 10px !important;">
                                        <legend class="scheduler-border">Whether the Intellectual Property (Trade Mark/ Brand Name/ Recipe other Patent) is registration in Bangladesh:</legend>
                                        <div class="form-group">

                                            <div class="row">
                                                {!! Form::label('int_property_attachment','Copy of Trade Mark Certificate/ Copy of Application for Trade Mark Certificate',['class'=>'col-md-6 text-left required-star']) !!}
                                                <div class="col-md-6">
                                                    <input type="file" id="int_property_attachment" name="int_property_attachment" class="form-control input-md intPropReqField"/>
                                                    {!! $errors->first('int_property_attachment','<span class="help-block">:message</span>') !!}
                                                    <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            {{--2. BIDA's registration info--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>2. BIDA's registration info</strong></div>
                                <div class="panel-body">
                                    <table aria-label="BIDA's registration info" id="registrationInfoTable"
                                           class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Registration No</td>
                                            <td class="required-star">Date</td>
                                            <td class="required-star">Proposed Investment (BDT)</td>
                                            <td class="required-star">Actual Investment (BDT)</td>
                                            <td class="required-star">Copy of registration  <br/><span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span></td>
                                            <td class="">Amendment Copy of BIDA Registration  <br/><span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span></td>
                                            <td>#</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="registrationInfoTableRow">
                                            <td>
                                                {!! Form::text('registration_no[]', '', ['class' => 'form-control input-md','placeholder'=>'Registration No', 'id'=>'br_info_reg_no']) !!}
                                                {!! $errors->first('registration_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                <div class="datepicker input-group date">
                                                    {!! Form::text('registration_date[]', '', ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy', 'id'=>'br_info_date']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('registration_date','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('proposed_investment[]', '', ['class' => 'form-control input-md  number proposed_investment', 'id'=>'br_info_proposed_investment', 'placeholder'=>'Proposed Investment', 'onkeyup' => "checkActualInvestment(this)"]) !!}
                                                {!! $errors->first('proposed_investment','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('actual_investment[]', '', ['class' => 'form-control input-md  number actual_investment', 'id'=>'br_info_actual_investment', 'placeholder'=>'Actual Investment', 'onkeyup' => "checkActualInvestment(this)"]) !!}
                                                {!! $errors->first('actual_investment','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                <input type="file" id="registration_copy" name="registration_copy[]" class="form-control input-md required"/>
                                                {!! $errors->first('registration_copy','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                <input type="file" id="amendment_copy" name="amendment_copy[]" class="form-control input-md amendment_copy" style="pointer-events: none; background-color: #ddd"/>
                                                {!! $errors->first('amendment_copy','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td style="text-align: left;">
                                                <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow('registrationInfoTable', 'registrationInfoTableRow');">
                                                    <i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--3. Foreign Collaboration's providing Service/ Intellectual Properties Info--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>3. Foreign collaborator's providing service/ intellectual properties Info</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 form-group {{$errors->has('organization_name') ? 'has-error': ''}}">
                                                {!! Form::label('organization_name','Name of Organization',['class'=>'col-md-2 text-left']) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('organization_name', Session::get('appInfo.organization_name'), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('organization_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-12 form-group {{$errors->has('organization_address') ? 'has-error': ''}}">
                                                {!! Form::label('organization_address','Address',['class'=>'col-md-2 text-left']) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('organization_address', Session::get('appInfo.organization_address'), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('organization_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('property_city') ? 'has-error': ''}}">
                                                {!! Form::label('property_city','City/ State',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('property_city', Session::get('appInfo.property_city'), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('property_city','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('property_post_code') ? 'has-error': ''}}">
                                                {!! Form::label('property_post_code','Post Code/ Zip code',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('property_post_code', Session::get('appInfo.property_post_code'), ['class' => 'form-control input-md']) !!}
                                                    {!! $errors->first('property_post_code','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('property_country_id','Country',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::select('property_country_id', $countries, Session::get('appInfo.property_country_id'),['class'=>'form-control input-md', 'id' => 'property_country_id']) !!}
                                                    {!! $errors->first('property_country_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--4. Effective date of the Agreement--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>4. Effective date of the agreement</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('effective_agreement_date','Date of the Agreement',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('effective_agreement_date', Session::get('appInfo.effective_agreement_date'), ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('effective_agreement_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--5. Duration of the Agreement--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>5. Duration of the agreement</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('agreement_duration_from','From',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    <div class="input-group date" id="agreement_duration_from_dp">
                                                        {!! Form::text('agreement_duration_from', Session::get('appInfo.agreement_duration_from'), ['class' => 'form-control input-md', 'id'=>'agreement_duration_from', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('agreement_duration_from','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('agreement_duration_type','Duration type',['class'=>'col-md-4']) !!}
                                                <div class="col-md-8">
                                                    <label class="radio-inline">  {!! Form::radio('agreement_duration_type', 'Fixed Date', false,['class' => ' agreement_duration_type helpTextRadio', 'id' => 'fixed_date' ,'onchange' => "AgreementDuration(this.value)"]) !!} Fixed Date </label>
                                                    <label class="radio-inline">  {!! Form::radio('agreement_duration_type', 'Until Valid Contact', false,['class' => ' agreement_duration_type', 'id' => 'until_valid_contact', 'onchange' => "AgreementDuration(this.value)"]) !!} Until Valid Contact </label>
                                                </div>
                                                {!! $errors->first('agreement_duration_type','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="fixedDateDiv" hidden>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('agreement_duration_to','To',['class'=>'col-md-4 text-left required-star']) !!}
                                                <div class="col-md-8">
                                                    <div class="input-group date" id="agreement_duration_to_dp">
                                                        {!! Form::text('agreement_duration_to', Session::get('appInfo.agreement_duration_to'), ['class' => 'form-control input-md fixedDateDivReqField', 'id'=>'agreement_duration_to', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('agreement_duration_to','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('agreement_total_duration','Total Duration',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('agreement_total_duration', Session::get('appInfo.agreement_total_duration'), ['class' => 'form-control input-md', 'id'=>'agreement_total_duration', 'placeholder'=>'1 year, 2 month, 5 day (Example)', 'readonly']) !!}
                                                    {!! $errors->first('agreement_total_duration','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group" id="validContactDiv" hidden>
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('valid_contact_attachment','Attach valid contact',['class'=>'col-md-4']) !!}
                                                <div class="col-md-8">
                                                    <input type="file" id="valid_contact_attachment" name="valid_contact_attachment" class="form-control input-md validContactDivReqField"/>
                                                    <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>
                                                </div>
                                                {!! $errors->first('valid_contact_attachment','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--6. Schedule of payment--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>6. Schedule of payment</strong></div>
                                <div class="panel-body">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border required-star">Please tick the appropriate one</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8 pull-right">
                                                    <label class="radio-inline">  {!! Form::radio('schedule_of_payment', 'Monthly', (Session::get('appInfo.schedule_of_payment') == 'Monthly' ? true : false),['id' => 'monthly', 'class' => ' helpTextRadio']) !!} Monthly </label>
                                                    <label class="radio-inline">  {!! Form::radio('schedule_of_payment', 'Quarterly', (Session::get('appInfo.schedule_of_payment') == 'Quarterly' ? true : false),['id' => 'quarterly', 'class' => '']) !!} Quarterly </label>
                                                    <label class="radio-inline">  {!! Form::radio('schedule_of_payment', 'Half Yearly', (Session::get('appInfo.schedule_of_payment') == 'Half Yearly' ? true : false),['id' => 'half_yearly', 'class' => '']) !!} Half Yearly </label>
                                                    <label class="radio-inline">  {!! Form::radio('schedule_of_payment', 'Yearly', (Session::get('appInfo.schedule_of_payment') == 'Yearly' ? true : false),['id' => 'yearly', 'class' => '']) !!} Yearly </label>
                                                    <label class="radio-inline">  {!! Form::radio('schedule_of_payment', 'One Time', (Session::get('appInfo.schedule_of_payment') == 'One Time' ? true : false),['id' => 'one_time', 'class' => '']) !!} One time </label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                            {{--7. Marketing of products (%)--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>7. Marketing of products (%)</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('marketing_of_products_local') ? 'has-error': ''}}">
                                                {!! Form::label('marketing_of_products_local','Local',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('marketing_of_products_local', '', ['class' => 'form-control input-md number', 'id'=>'marketing_of_products_local']) !!}
                                                    {!! $errors->first('marketing_of_products_local','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('marketing_of_products_foreign') ? 'has-error': ''}}">
                                                {!! Form::label('marketing_of_products_foreign','Foreign', ['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('marketing_of_products_foreign', '', ['class' => 'form-control input-md engOnly number', 'id'=>'marketing_of_products_foreign']) !!}
                                                    {!! $errors->first('marketing_of_products_foreign','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{--8. Present status--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>8. Present status</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('present_status_id','Present Status',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::select('present_status_id', $remittancePresentStatus, ['0' => 'Select One'], ['class'=>'form-control input-md', 'id' => 'present_status_id', 'placeholder'=>'Select one']) !!}
                                                    {!! $errors->first('present_status_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{--9. Brief description of technological service received--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong class="">9. Brief description of technological service received</strong></div>
                                <div class="panel-body">
                                    <table aria-label="Brief description of technological service received" id="briefDescriptionTable"
                                           class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true"  scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="briefDescriptionRow">
                                            <td>
                                                {!! Form::text('brief_description[]', '', ['class' => 'form-control input-md']) !!}
                                                {!! $errors->first('brief_description','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td style="text-align: left;">
                                                <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow('briefDescriptionTable', 'briefDescriptionRow');"><i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--10. Total Amount to be paid as per Agreement--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>10. Total amount to be paid as per agreement</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><strong>(a) Total amount</strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">

                                                {!! Form::label('agreement_amount_type','Amount type',['class'=>'col-md-4']) !!}

                                                <div class="col-md-8">
                                                    <label class="radio-inline">  {!! Form::radio('agreement_amount_type', 'Fixed Amount', false,['class' => ' agreement_duration_type helpTextRadio', 'id'=>'fixed_amount','onchange' => "AgreementAmount(this.value)"]) !!} Fixed Amount </label>
                                                    <label class="radio-inline">  {!! Form::radio('agreement_amount_type', 'Percentage', false,['class' => ' agreement_duration_type', 'id'=>'percentage','onchange' => "AgreementAmount(this.value)"]) !!} Percentage </label>
                                                </div>
                                                {!! $errors->first('agreement_amount_type','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="percentage_div" hidden>
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('percentage_of_sales') ? 'has-error': ''}}">
                                                {!! Form::label('percentage_of_sales','Percentage of sales%',['class'=>'col-md-4 text-left required-star']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('percentage_of_sales', '', ['class' => 'form-control input-md number percentage_of_sales perDivReqField', 'id'=>'percentage_of_sales']) !!}
                                                    {!! $errors->first('percentage_of_sales','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="amount_div" hidden>
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('total_agreement_amount_bdt') ? 'has-error': ''}}">
                                                {!! Form::label('total_agreement_amount_bdt','Taka (BDT)',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('total_agreement_amount_bdt', Session::get('appInfo.total_agreement_amount_bdt'), ['class' => 'form-control input-md number total_agreement_amount_bdt', 'id'=>'total_agreement_amount_bdt', 'onkeyup' => "TotalPercentage('total_agreement_amount_bdt', 'project_status_id', 'sales_value_bdt', 'cnf_value')"]) !!}
                                                    {!! $errors->first('total_agreement_amount_bdt','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('total_agreement_amount_usd') ? 'has-error': ''}}">
                                                {!! Form::label('total_agreement_amount_usd','USD', ['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('total_agreement_amount_usd', Session::get('appInfo.total_agreement_amount_usd'), ['class' => 'form-control input-md number total_agreement_amount_usd', 'id'=>'total_agreement_amount_usd']) !!}
                                                    {!! $errors->first('total_agreement_amount_usd','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><strong>(b) For which period</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('period_from','From',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    <div class="input-group date" id="period_from_dp">
                                                        {!! Form::text('period_from', Session::get('appInfo.period_from'), ['class' => 'form-control input-md', 'id'=>'period_from', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('period_from','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('period_to','To',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    <div class="input-group date" id="period_to_dp">
                                                        {!! Form::text('period_to', Session::get('appInfo.period_to'), ['class' => 'form-control input-md', 'id'=>'period_to', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('period_to','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('total_period') ? 'has-error': ''}}">
                                                {!! Form::label('total_period','Total period',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('total_period', Session::get('appInfo.total_period'), ['class' => 'form-control input-md', 'id'=>'total_period', 'placeholder'=>'1 year, 2 month, 5 day (Example)', 'readonly']) !!}
                                                    {!! $errors->first('total_period','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="input-group-btn">
                                                    @if(Session::get('remittanceInfo'))
                                                        <button type="submit" class="btn btn-danger btn-md cancel" value="clean_load_data" name="actionBtn">Clear Loaded Data</button>
                                                    @else
                                                        <button type="submit" class="btn btn-success btn-md cancel" value="searchRemittanceInfo" name="searchRemittanceInfo" id="searchRemittanceInfo" onclick="LoadFiscalYear(event)">Load Past Remittance Data</button>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><strong>(c) Name of products/ services and annual production capacity/ value</strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('product_name_capacity','Annual production capacity/ value',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('product_name_capacity', Session::get('appInfo.product_name_capacity'), ['class' => 'form-control input-md', 'placeholder'=>'']) !!}
                                                    {!! $errors->first('product_name_capacity','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--11. Industrial project status--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>11. Industrial project status</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                {!! Form::label('project_status_id','Project status',['class'=>'col-md-4']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::select('project_status_id', $project_status, '',['class'=>'form-control input-md', 'id' => 'project_status_id', 'onchange' => 'CostSalesPanelView(this.value)']) !!}
                                                    {!! $errors->first('project_status_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--12. Cost + Freight (C&F) value of imported machinery (In case of proposed/under implementation project) (If applicable)--}}
                            <div class="panel panel-info" hidden id="costPanel">
                                <div class="panel-heading"><strong>12. Cost + Freight (C&F) value of imported machinery (In case of proposed/under implementation project) (If applicable)</strong></div>
                                <div class="panel-body">
                                    <table aria-label="imported machinery" id="impoortmachineryTable"
                                           class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Year of import</td>
                                            <td class="required-star">To</td>
                                            <td class="required-star">C&F Value (BDT)</td>
                                            <td>#</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="impoortmachineryTableRow">
                                            <td>
                                                <div class="datepicker input-group date">
                                                    {!! Form::text('import_year_from[]', '', ['class' => 'form-control input-md costPanelReqField required', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('import_year_from','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                <div class="datepicker input-group date">
                                                    {!! Form::text('import_year_to[]', '', ['class' => 'form-control input-md costPanelReqField required', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('import_year_to','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('cnf_value[]', '', ['class' => 'form-control required input-md costPanelReqField number cnf_value', 'onkeyup' => "TotalPercentage('total_agreement_amount_bdt', 'project_status_id', 'sales_value_bdt', 'cnf_value')"]) !!}
                                                {!! $errors->first('cnf_value','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td style="text-align: left;">
                                                <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow('impoortmachineryTable', 'impoortmachineryTableRow');">
                                                    <i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--12. Previous year’s sales as declared in the annual tax return (In case of industrial unit already in operation) (If applicable)--}}
                            <div class="panel panel-info" hidden id="salesPanel">
                                <div class="panel-heading"><strong>12. Previous year’s sales as declared in the annual tax return (In case of industrial unit already in operation) (If applicable)</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('prev_sales_year_from','Sales year',['class'=>'col-md-4 required-star']) !!}
                                                <div class="col-md-8">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('prev_sales_year_from', '', ['class' => 'form-control input-md salesPanelReqField', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('prev_sales_year_from','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('prev_sales_year_to','To',['class'=>'col-md-4 required-star']) !!}
                                                <div class="col-md-8">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('prev_sales_year_to', '', ['class' => 'form-control input-md salesPanelReqField', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('prev_sales_year_to','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><strong>(a) Value of sales:</strong></label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('sales_value_bdt') ? 'has-error': ''}}">
                                                {!! Form::label('sales_value_bdt','Taka (BDT) :',['class'=>'col-md-4 required-star']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('sales_value_bdt', '', ['class' => 'form-control input-md salesPanelReqField', 'onkeyup' => "TotalPercentage('total_agreement_amount_bdt', 'project_status_id', 'sales_value_bdt', 'cnf_value')"]) !!}
                                                    {!! $errors->first('sales_value_bdt','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sales_value_usd') ? 'has-error': ''}}">
                                                {!! Form::label('sales_value_usd','USD', ['class'=>'col-md-4 required-star']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('sales_value_usd', '', ['class' => 'form-control input-md engOnly salesPanelReqField']) !!}
                                                    {!! $errors->first('sales_value_usd','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 pull-right {{$errors->has('usd_conv_rate') ? 'has-error': ''}}">
                                                {!! Form::label('usd_conv_rate','Dollar conversion rate:',['class'=>'col-md-4 required-star']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('usd_conv_rate', '', ['class' => 'form-control input-md salesPanelReqField','placeholder'=>'1 USD = 82.5 BDT']) !!}
                                                    {!! $errors->first('usd_conv_rate','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label><strong>(b) Amount of tax paid:</strong></label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('tax_amount_bdt') ? 'has-error': ''}}">
                                                {!! Form::label('tax_amount_bdt','Taka (BDT)',['class'=>'col-md-4 required-star']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('tax_amount_bdt', '', ['class' => 'form-control input-md salesPanelReqField']) !!}
                                                    {!! $errors->first('tax_amount_bdt','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <label><a target="_blank" rel="noopener" href="https://www.bangladesh-bank.org/econdata/exchangerate.php">Exchange Rate Ref: Bangladesh Bank. Please Enter Today's Exchange Rate</a></label>
                                </div>
                            </div>

                            {{--13. Percentage of the total fees (If applicable)--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>13. Percentage of the total fees (If applicable)</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('total_fee_percentage','Percentage',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        {!! Form::text('total_fee_percentage', (!empty(Session::get('appInfo.total_fee_percentage')) ? Session::get('appInfo.total_fee_percentage'):''), ['class' => 'form-control input-md  number', 'readonly' => 'readonly']) !!}
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                    {!! $errors->first('total_fee_percentage','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--14. Proposed amount of remittances--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>14. Proposed amount of remittances</strong></div>
                                <div class="panel-body">
                                    <table aria-label="Detailed Proposed amount of remittances" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Proposed</td>
                                            <td class="required-star">Taka (BDT)</td>
                                            <td class="required-star">USD</td>
                                            <td>Expressed %</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                {!! Form::text('proposed_remittance_type', (!empty(Session::get('appInfo.proposed_remittance_type')) ? Session::get('appInfo.proposed_remittance_type'):''), ['class' => 'form-control input-md required rea', 'id'=>'proposed_remittance_type', 'readonly']) !!}
                                                {!! $errors->first('proposed_remittance_type','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('proposed_amount_bdt', (!empty(Session::get('appInfo.proposed_amount_bdt')) ? Session::get('appInfo.proposed_amount_bdt'):''), ['class' => 'form-control input-md required numberNoNegative', 'readonly']) !!}
                                                {!! $errors->first('proposed_amount_bdt','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('proposed_amount_usd', (!empty(Session::get('appInfo.proposed_amount_usd')) ? Session::get('appInfo.proposed_amount_usd'):''), ['class' => 'form-control input-md required numberNoNegative', 'readonly']) !!}
                                                {!! $errors->first('proposed_amount_usd','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('proposed_exp_percentage', (!empty(Session::get('appInfo.proposed_exp_percentage')) ? Session::get('appInfo.proposed_exp_percentage'):''), ['class' => 'form-control input-md number', 'id' => 'proposed_exp_percentage', 'readonly' => 'readonly']) !!}
                                                {!! $errors->first('proposed_exp_percentage','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="text-center top-padding">Sub Total</p></td>
                                            <td>
                                                {!! Form::text('proposed_sub_total_bdt', (!empty(Session::get('appInfo.proposed_sub_total_bdt')) ? Session::get('appInfo.proposed_sub_total_bdt'):''), ['class' => 'form-control input-md required numberNoNegative', 'id'=>'proposed_sub_total_bdt', 'readonly']) !!}
                                                {!! $errors->first('proposed_sub_total_bdt','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('proposed_sub_total_usd', (!empty(Session::get('appInfo.proposed_sub_total_usd')) ? Session::get('appInfo.proposed_sub_total_usd'):''), ['class' => 'form-control input-md required numberNoNegative', 'id'=>'proposed_sub_total_usd', 'readonly']) !!}
                                                {!! $errors->first('proposed_sub_total_usd','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('proposed_sub_total_exp_percentage', (!empty(Session::get('appInfo.proposed_sub_total_exp_percentage')) ? Session::get('appInfo.proposed_sub_total_exp_percentage'):''), ['class' => 'form-control input-md required number', 'id'=>'proposed_sub_total_exp_percentage', 'readonly']) !!}
                                                {!! $errors->first('proposed_sub_total_exp_percentage','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td> &nbsp;&nbsp;&nbsp;&nbsp; Total Fee (BDT)</td>
                                            <td>
                                                {!! Form::text('total_fee', (!empty(Session::get('appInfo.total_fee')) ? Session::get('appInfo.total_fee'):''), ['class' => 'form-control input-md numberNoNegative', 'id'=>'total_fee', 'readonly']) !!}
                                            </td>
                                            <td>
                                                <span class="pull-right text-danger">Govt. fee will be deducted filed</span>
                                            </td>
                                            <td>
                                                <a type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#myModal">Govt. Fees Calculator</a>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    <label>Note: Based on type of the remittance</label>
                                </div>
                            </div>

                            {{--15. Other remittance made/to be made during the same calendar/fiscal year (If applicable)--}}
                            <div id="FiscalYealDiv">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>15. Other remittance made/ to be made during the same calendar/ fiscal year (If applicable)</strong></div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table aria-label="Detailed Other remittance made" id="remittancemadeTable"
                                                   class="table table-bordered dt-responsive"
                                                   cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <td>Type of fee</td>
                                                    <td>Taka (BDT)</td>
                                                    <td>USD</td>
                                                    <td>%</td>
                                                    <td>Attachment  <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span></td>
                                                    <td>#</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $inc = 0;
                                                $total_bdt = 0;
                                                $total_usd = 0;
                                                $total_percentage = 0;
                                                ?>
                                                @if(count(Session::get('remittanceInfo'))>0)
                                                    @foreach(Session::get('remittanceInfo') as $otherRemittanceInfo)
                                                        <tr id="remittancemadeTableRow{{$inc}}">
                                                            <td>
                                                                {!! Form::select('other_remittance_type_id[]', $remittanceType, $otherRemittanceInfo->remittance_type_id,
                                                                ['class' => 'form-control input-md', 'placeholder' => 'Select One']) !!}
                                                                {!! $errors->first('other_remittance_type_id','<span class="help-block">:message</span>') !!}

                                                            </td>
                                                            <td>
                                                                {!! Form::text('other_remittance_bdt[]', $otherRemittanceInfo->proposed_amount_bdt, ['class' => 'form-control input-md other_remittance_bdt', 'onkeyup' => "calculateOneNumber('other_remittance_bdt', 'other_sub_total_bdt')"]) !!}
                                                                {!! $errors->first('other_remittance_bdt','<span class="help-block">:message</span>') !!}

                                                            </td>
                                                            <td>
                                                                {!! Form::text('other_remittance_usd[]', $otherRemittanceInfo->proposed_amount_usd, ['class' => 'form-control input-md other_remittance_usd', 'onkeyup' => "calculateOneNumber('other_remittance_usd', 'other_sub_total_usd')"]) !!}
                                                                {!! $errors->first('other_remittance_usd','<span class="help-block">:message</span>') !!}
                                                            </td>

                                                            <td>
                                                                {!! Form::text('other_remittance_percentage[]', $otherRemittanceInfo->proposed_exp_percentage, ['class' => 'form-control input-md other_remittance_percentage', 'onkeyup' => "calculateRemittance('other_remittance_percentage', 'other_sub_total_percentage')"]) !!}
                                                                {!! $errors->first('other_remittance_percentage','<span class="help-block">:message</span>') !!}
                                                            </td>

                                                            <td>
                                                                <input type="file" id="other_remittance_attachment" name="other_remittance_attachment[]" value="{{$otherRemittanceInfo->other_remittance_attachment}}" class="form-control input-md"/>
                                                                {!! $errors->first('other_remittance_attachment','<span class="help-block">:message</span>') !!}

                                                                @if(!empty($otherRemittanceInfo['other_remittance_attachment']))
                                                                    <a target="_blank" rel="noopener" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($otherRemittanceInfo->other_remittance_attachment) ? $otherRemittanceInfo->other_remittance_attachment : ''))}}"
                                                                       title="{{$otherRemittanceInfo->other_remittance_attachment}}">
                                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                        <?php $file_name = explode('/', $otherRemittanceInfo->other_remittance_attachment); echo end($file_name); ?>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td style="text-align: left;">
                                                                @if($inc==0)
                                                                    <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addFiscalYearRow('remittancemadeTable', 'remittancemadeTableRow0');">
                                                                        <i class="fa fa-plus"></i></a>
                                                                @else
                                                                    @if($viewMode != 'on')
                                                                        <a href="javascript:void(0);" class="btn btn-sm btn-danger removeRow" onclick="removeFiscalYearRow('remittancemadeTable','remittancemadeTableRow{{$inc}}');">
                                                                            <i class="fa fa-times" aria-hidden="true"></i></a>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $inc++;
                                                        $total_bdt += $otherRemittanceInfo->proposed_amount_bdt;
                                                        $total_usd += $otherRemittanceInfo->proposed_amount_usd;
                                                        $total_percentage += $otherRemittanceInfo->proposed_exp_percentage;
                                                        ?>
                                                    @endforeach
                                                @else
                                                    <tr id="remittancemadeTableRow">
                                                        <td>
                                                            {!! Form::select('other_remittance_type_id[]', $remittanceType,['0' => 'Select One'],
                                                                    ['class' => 'form-control input-md', 'placeholder' => 'Select One']) !!}
                                                            {!! $errors->first('other_remittance_type_id','<span class="help-block">:message</span>') !!}

                                                        </td>
                                                        <td>
                                                            {!! Form::text('other_remittance_bdt[]', '', ['class' => 'form-control input-md number other_remittance_bdt', 'onkeyup' => "calculateOneNumber('other_remittance_bdt', 'other_sub_total_bdt')"]) !!}
                                                            {!! $errors->first('other_remittance_bdt','<span class="help-block">:message</span>') !!}

                                                        </td>
                                                        <td>
                                                            {!! Form::text('other_remittance_usd[]', '', ['class' => 'form-control input-md number other_remittance_usd', 'onkeyup' => "calculateOneNumber('other_remittance_usd', 'other_sub_total_usd')"]) !!}
                                                            {!! $errors->first('other_remittance_usd','<span class="help-block">:message</span>') !!}
                                                        </td>

                                                        <td>
                                                            {!! Form::text('other_remittance_percentage[]', '', ['class' => 'form-control input-md number other_remittance_percentage', 'onkeyup' => "calculateRemittance('other_remittance_percentage', 'other_sub_total_percentage')"]) !!}
                                                            {!! $errors->first('other_remittance_percentage','<span class="help-block">:message</span>') !!}
                                                        </td>

                                                        <td>
                                                            <input type="file" id="other_remittance_attachment" name="other_remittance_attachment[]" class="form-control input-md"/>
                                                            {!! $errors->first('other_remittance_attachment','<span class="help-block">:message</span>') !!}
                                                        </td>

                                                        <td style="text-align: left;">
                                                            <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addFiscalYearRow('remittancemadeTable', 'remittancemadeTableRow');">
                                                                <i class="fa fa-plus"></i></a>
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th scope="col">Sub Total</th>
                                                    <td>
                                                        {!! Form::text('other_sub_total_bdt', $total_bdt, ['class' => 'form-control input-md numberNoNegative', 'id' => 'other_sub_total_bdt','readonly']) !!}
                                                        {!! $errors->first('other_sub_total_bdt','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('other_sub_total_usd', $total_usd, ['class' => 'form-control input-md numberNoNegative', 'id' => 'other_sub_total_usd','readonly']) !!}
                                                        {!! $errors->first('other_sub_total_usd','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('other_sub_total_percentage', $total_percentage, ['class' => 'form-control input-md number', 'id' => 'other_sub_total_percentage', 'readonly']) !!}
                                                        {!! $errors->first('other_sub_total_percentage','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--16. Percentage of total remittances for the year (If applicable)--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>16. Percentage of total remittances for the year (If applicable)</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('total_remittance_percentage','Percentage',['class'=>'col-md-4 text-left required-star']) !!}
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        {!! Form::text('total_remittance_percentage', (!empty(Session::get('appInfo.total_remittance_percentage')) ? Session::get('appInfo.total_remittance_percentage'):''), ['class' => 'form-control input-md required number', 'readonly']) !!}
                                                        <span class="input-group-addon">%</span>
                                                    </div>
                                                    {!! $errors->first('total_remittance_percentage','<span class="help-block">:message</span>') !!}
                                                    <small class="text-danger remittance_err"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--17. Brief statement of benefits received /to be received by the local company/ firm under the agreement--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong class="required-star">17. Brief statement of benefits received/ to be received by the local company/ firm under the agreement</strong></div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table aria-label="Detailed Brief statement of benefits received" id="briefTable"
                                               class="table table-striped table-bordered dt-responsive"
                                               cellspacing="0" width="100%">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true"  scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr id="briefTableRow">
                                                <td>
                                                    {!! Form::text('brief_statement[]', '', ['class' => 'form-control input-md', 'id'=>'brief_statement_of_benefit']) !!}
                                                    {!! $errors->first('brief_statement','<span class="help-block">:message</span>') !!}
                                                </td>

                                                <td style="text-align: left;">
                                                    <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow('briefTable', 'briefTableRow');">
                                                        <i class="fa fa-plus"></i></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{--18. Brief background of the foreign service provider--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong class="required-star">18. Brief background of the foreign service provider</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-12">
                                                    {!! Form::textarea('brief_background', null, ['data-rule-maxlength'=>'1000', 'placeholder'=>'Remaining Character 1000', 'class' => 'form-control bigInputField input-md maxTextCountDown', 'id'=>'foreign_service_provider',
                                                                'size'=>'5x5', 'data-charcount-maxlength'=>'1000']) !!}
                                                    {!! $errors->first('brief_background','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--19. Statement of remittances of such fees for the last 3(three) years (If applicable)--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>19. Statement of remittances of such fees for the last 3(three) years (If applicable)</strong></div>
                                <div class="panel-body">
                                    <table aria-label="Detailed Statement of remittances" id="remittanceStatementTable"
                                           class="table table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td>Type</td>
                                            <td>Year of remittance</td>
                                            <td>BIDA's ref. Number</td>
                                            <td>Date</td>
                                            <td>Approval Copy <br/><span class="text-danger" style="font-size: 9px; font-weight: bold">[File: *.pdf | Max size 3MB]</span></td>
                                            <td>Amount</td>
                                            <td>%</td>
                                            <td>#</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="remittanceStatementTableRow">
                                            <td>
                                                {!! Form::select('statement_remittance_type_id[]', $remittanceType, ['0' => 'Select One'],
                                                            ['class' => 'form-control input-md', 'placeholder' => 'Select One']) !!}
                                                {!! $errors->first('statement_remittance_type_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                <div class="YearPicker input-group date">
                                                    {!! Form::text('remittance_year[]', '', ['class' => 'form-control input-md', 'placeholder'=>'yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('remittance_year','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('bida_ref_no[]', '', ['class' => 'form-control input-md']) !!}
                                                {!! $errors->first('bida_ref_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                <div class="datepicker input-group date">
                                                    {!! Form::text('date[]', '', ['class' => 'form-control input-md', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('date','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                <input type="file" id="approval_copy" name="approval_copy[]" class="form-control input-md"/>
                                                {!! $errors->first('approval_copy[]','<span class="help-block">:message</span>') !!}

                                            </td>
                                            <td>
                                                {!! Form::text('amount[]', '', ['class' => 'form-control input-md numberNoNegative']) !!}
                                                {!! $errors->first('amount','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('percentage[]', '', ['class' => 'form-control input-md number']) !!}
                                                {!! $errors->first('percentage','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td style="text-align: left;">
                                                <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow('remittanceStatementTable', 'remittanceStatementTableRow');">
                                                    <i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--20. Statement of actual production/ services for the last 3(three) years (If applicable)--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>20. Statement of actual production/ services for the last 3(three) years (If applicable)</strong></div>
                                <div class="panel-body">
                                    <table aria-label="Detailed Statement of actual production" id="actualProductionTable"
                                           class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td>Year</td>
                                            <td>Item of production/ service</td>
                                            <td>Qty</td>
                                            <td>Sales Value/ Revenue</td>
                                            <td>#</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="actualProductionTableRow">
                                            <td>
                                                <div class="YearPicker input-group date">
                                                    {!! Form::text('year_of_remittance[]', '', ['class' => 'form-control input-md', 'placeholder'=>'yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('year_of_remittance','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('item_of_production[]', '', ['class' => 'form-control input-md']) !!}
                                                {!! $errors->first('item_of_production','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('actual_quantity[]', '', ['class' => 'form-control input-md number']) !!}
                                                {!! $errors->first('actual_quantity','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td>
                                                {!! Form::text('sales_value[]', '', ['class' => 'form-control input-md number']) !!}
                                                {!! $errors->first('sales_value','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td style="text-align: left;">
                                                <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow('actualProductionTable', 'actualProductionTableRow');">
                                                    <i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--21. Statement of export earning (If any) (If applicable)--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>21. Statement of export earning (If any) (If applicable)</strong></div>
                                <div class="panel-body">
                                    <table aria-label="Detailed Statement of export earning" id="exportEarningTable"
                                           class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0" width="100%">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td>Year of Export</td>
                                            <td>Item of Export</td>
                                            <td>Qty</td>
                                            <td>C&F/ CIF Value</td>
                                            <td>#</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr id="exportEarningTableRow">
                                            <td>
                                                <div class="YearPicker input-group date">
                                                    {!! Form::text('exp_year_of_remittance[]', '', ['class' => 'form-control input-md', 'placeholder'=>'yyyy']) !!}
                                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                </div>
                                                {!! $errors->first('exp_year_of_remittance','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('item_of_export[]', '', ['class' => 'form-control input-md']) !!}
                                                {!! $errors->first('item_of_export','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td>
                                                {!! Form::text('export_quantity[]', '', ['class' => 'form-control input-md number']) !!}
                                                {!! $errors->first('export_quantity','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td>
                                                {!! Form::text('cnf_cif_value[]', '', ['class' => 'form-control input-md number']) !!}
                                                {!! $errors->first('cnf_cif_value','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td style="text-align: left;">
                                                <a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRow('exportEarningTable', 'exportEarningTableRow');">
                                                    <i class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--22. Name & address of the nominated local bank through which remittance to be effected--}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>22. Name & address of the nominated local bank through which remittance to be effected</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('local_bank_id','Select Bank',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::select('local_bank_id',$banks, '',['class'=>'form-control input-md', 'id' => 'local_bank_id']) !!}
                                                    {!! $errors->first('local_bank_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('local_branch','Branch Name',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {{--                                                    {!! Form::select('local_branch_id',$branch, '',['class'=>'form-control input-md required', 'id' => 'local_branch_id']) !!}--}}
                                                    {!! Form::text('local_branch', '', ['class' => 'form-control input-md textOnly','placeholder'=>'Branch Name', 'id' => 'local_branch']) !!}
                                                    {!! $errors->first('local_branch','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('local_bank_address') ? 'has-error': ''}}">
                                                {!! Form::label('local_bank_address','Address',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('local_bank_address', '', ['class' => 'form-control input-md','placeholder'=>'']) !!}
                                                    {!! $errors->first('local_bank_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('local_bank_city') ? 'has-error': ''}}">
                                                {!! Form::label('local_bank_city','City/ State',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('local_bank_city', '', ['class' => 'form-control input-md','placeholder'=>'']) !!}
                                                    {!! $errors->first('local_bank_city','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('local_bank_post_code') ? 'has-error': ''}}">
                                                {!! Form::label('local_bank_post_code','Post Code/ Zip Code',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::text('local_bank_post_code', '', ['class' => 'form-control input-md','placeholder'=>'']) !!}
                                                    {!! $errors->first('local_bank_post_code','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('local_bank_country_id','Country',['class'=>'col-md-4 text-left']) !!}
                                                <div class="col-md-8">
                                                    {!! Form::select('local_bank_country_id', $bank_country, '',['class'=>'form-control input-md', 'id' => 'local_bank_country_id']) !!}
                                                    {!! $errors->first('local_bank_country_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv"></div>
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
                                                        <p>I do hereby declare that the information given above is true
                                                            to the best of my knowledge and I shall be liable for any
                                                            false information/ statement given.</p>
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
                                                            {!! Form::text('auth_full_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control required input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', Auth::user()->designation, ['class' => 'form-control required input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', Auth::user()->user_phone, ['class' => 'form-control required input-sm phone_or_mobile', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' => 'form-control required input-sm email', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail img-user"
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
                                                I do hereby declare that the Outward Remittance Proposal didn’t submit earlier to the Bangladesh Investment Development Authority. The above information is true and I shall be liable for any false information is given.
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
                                    <strong>Service Fee Payment</strong>
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
                                                    {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md  email required']) !!}
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
                                                    {!! Form::text('sfp_pay_amount', $payment_config->amount, ['class' => 'form-control input-md', 'readonly', 'id'=>'pay_amount']) !!}
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
                                                {!! Form::label('sfp_status','Payment Status',['class'=>'col-md-5 text-left']) !!}
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

<!-- Modal Govt Payment-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Govt. Fees Calculator</h4>
            </div>
            <div class="modal-body">
                <table aria-label="Detailed Govt. Fees Calculator" class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">SI</th>
                        <th colspan="3" scope="colgroup">Fees break down Taka</th>
                        <th scope="col">Fees Taka</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach($feesAmountRange as $fee)
                        <tr>
                            <td scope="row">{{ $i++ }}</td>
                            <td>{{ $fee->min_amount_bdt }}</td>
                            <td>To</td>
                            <td>{{ $fee->max_amount_bdt }}</td>
                            <td>{{ $fee->p_o_amount_bdt }}</td>

                        </tr>
                    @endforeach

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script>

    function CategoryWiseDocLoad(remittance_type) {

        var attachment_key = "ram_";
        if (remittance_type == 1) {
            attachment_key += "others";
        } else if (remittance_type == 2) {
            attachment_key += "technical_know_how";
        } else if(remittance_type == 3) {
            attachment_key += "technical_assistance"
        } else if (remittance_type == 4) {
            attachment_key += "franchise"
        } else {
            attachment_key += "royalty";
        }

        if(remittance_type != 0 && remittance_type != ''){
            var _token = $('input[name="_token"]').val();
            var app_id = $("#app_id").val();
            var viewMode = $("#viewMode").val();

            $.ajax({
                type: "POST",
                url: '/remittance-new/getDocList',
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


    function AgreementDuration(agreement_duration_value) {
        if(agreement_duration_value == 'Until Valid Contact')
        {
            $("#validContactDiv").show();
            $(".validContactDivReqField").addClass('required');
            $("#fixedDateDiv").hide();
            $(".fixedDateDivReqField").removeClass('required');
        }
        else if(agreement_duration_value == 'Fixed Date')
        {
            $("#fixedDateDiv").show();
            $(".fixedDateDivReqField").addClass('required');
            $("#validContactDiv").hide();
            $(".validContactDivReqField").removeClass('required');
        }
        else{
            $("#fixedDateDiv").hide();
            $(".fixedDateDivReqField").removeClass('required');
            $("#validContactDiv").hide();
            $(".validContactDivReqField").removeClass('required');
        }
    }

    function AgreementAmount(agreement_amount_value) {
        if(agreement_amount_value == 'Fixed Amount')
        {
            $("#amount_div").show();
            $("#percentage_div").hide();
            $(".perDivReqField").removeClass('required');
        }
        else if(agreement_amount_value == 'Percentage')
        {
            $("#amount_div").show();
            $("#percentage_div").show();
            $(".perDivReqField").addClass('required');
        }
        else{
            $("#amount_div").hide();
            $("#percentage_div").hide();
            $(".perDivReqField").removeClass('required');
        }
    }

    function LoadFiscalYear(evt) {
        evt.preventDefault();
        var _token = $('input[name="_token"]').val();
        var period_from = document.getElementById("period_from").value;
        var period_to = document.getElementById("period_to").value;
        if(period_from != '' && period_to != ''){
            $.ajax({
                type: 'POST',
                url: "{{url('/remittance-new/load-fiscal-year')}}",
                dataType: 'json',
                data: { _token: "{{ csrf_token() }}", period_from: period_from, period_to: period_to, viewMode: '{{ $viewMode }}'},
                beforeSend: function (data) {
                    //console.log("before send");
                    document.getElementById("loading").style.display = "block";
                },
                success: function (data) {
                    if (data.html != undefined) {
                        $('#FiscalYealDiv').html(data.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    document.getElementById("loading").style.display = "none";
                    alert('Unknown error occured. Please, try again after reload');
                },
                complete: function () {
                    calculateOneNumber('other_remittance_bdt', 'other_sub_total_bdt');
                    calculateOneNumber('other_remittance_usd', 'other_sub_total_usd');
                    calculateRemittance('other_remittance_percentage', 'other_sub_total_percentage');
                    document.getElementById("loading").style.display = "none";
                }
            });
        }
        return false;
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
            if (!(file_size <= 2)) { // maximum file size 2 MB
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
            var action = "{{url('/remittance-new/upload-document')}}";

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

    function CostSalesPanelView(value) {
        if(value == 1){
            $("#costPanel").show();
            $("#costPanel").find('.costPanelReqField').addClass('required');
            $("#salesPanel").hide();
            $("#salesPanel").find('.salesPanelReqField').removeClass('required');
        }else if(value == 2){
            $("#salesPanel").show();
            $("#salesPanel").find('.salesPanelReqField').addClass('required');
            $("#costPanel").hide();
            $("#costPanel").find('.costPanelReqField').removeClass('required');
        }
        else{
            $("#costPanel").hide();
            $("#costPanel").find('.costPanelReqField').removeClass('required');
            $("#salesPanel").hide();
            $("#salesPanel").find('.salesPanelReqField').removeClass('required');
        }
        TotalPercentage('total_agreement_amount_bdt', 'project_status_id', 'sales_value_bdt', 'cnf_value');
    }

    /*
     all parameters are id or name of field
     cnf_value is the class name of field
     */
    function TotalPercentage(total_agreement_amount_bdt, project_status_id, sales_value_bdt, cnf_value){
        var agreement_amount_element = document.getElementById("total_agreement_amount_bdt");
        var project_status_element = document.getElementById("project_status_id");
        var sales_value_element = document.getElementById("sales_value_bdt");
        var cnf_value_elements = document.querySelectorAll('input[name="'+ cnf_value +'"]');

        var project_status_value = project_status_element.options[project_status_element.selectedIndex].value;
        var agreement_amount_value = (agreement_amount_element.value ? parseInt(agreement_amount_element.value) : 0.00);
        var sales_value = 0.00;
        var totalPercentage = 0.00;
        var cost_value = 0.00;
        //console.log(agreement_amount_value);
        /* Project Status
         1 = Proposed/ Under implementation
         2 = Industrial unit already in operation
         */
        if(project_status_value != ''){
            if(project_status_value == '1'){
                $("."+cnf_value).each(function () {
                    cost_value = cost_value + (this.value ? parseInt(this.value) : 0.00);
                });
                //console.log(cost_value)
                totalPercentage  = agreement_amount_value * 100 / cost_value;
            }
            else if(project_status_value == '2'){
                sales_value = (sales_value_element.value ? parseInt(sales_value_element.value) : 0.00);
                //console.log(sales_value);
                totalPercentage  = agreement_amount_value * 100 / sales_value;
            }
        }

        /*
         A Boolean. Returns false if the value is +infinity, -infinity, or NaN, otherwise it returns true.
         */
        if(isFinite(totalPercentage)){
            document.getElementById("total_fee_percentage").value = totalPercentage.toFixed(5);
            document.getElementById("proposed_exp_percentage").value = totalPercentage.toFixed(5);
            document.getElementById("proposed_sub_total_exp_percentage").value = totalPercentage.toFixed(5);
        }
        else{
            document.getElementById("total_fee_percentage").value = 0.00;
            document.getElementById("proposed_exp_percentage").value = 0.00;
            document.getElementById("proposed_sub_total_exp_percentage").value = 0.00;
        }

        calculateRemittance('other_remittance_percentage', 'other_sub_total_percentage');
    }

    function calculateOneNumber(className, totalShowFieldId) {
        var total_remittance_bdt = 0.00;
        $("."+className).each(function () {
            total_remittance_bdt = total_remittance_bdt + (this.value ? Number(this.value) : 0.00);
        });
        $("#"+totalShowFieldId).val(total_remittance_bdt.toFixed(5));
    }

    function calculateRemittance(className, totalShowFieldId) {
        var total_remittance = 0.00;
        $("."+className).each(function () {
            total_remittance = total_remittance + (this.value ? Number(this.value) : 0.00);
        });
        $("#"+totalShowFieldId).val(total_remittance.toFixed(5));

        var total_remittance_percentage = 0.00;
        //var proposed_remittance = ($("#proposed_sub_total_exp_percentage").val() ? Number($("#proposed_sub_total_exp_percentage").val()) : 0.00);
        var proposed_remittance = $("#proposed_sub_total_exp_percentage").val();
        total_remittance_percentage = total_remittance_percentage + Number(proposed_remittance) + total_remittance;
        $("#total_remittance_percentage").val(total_remittance_percentage.toFixed(5));
        if (total_remittance_percentage > 6) {
            $(".remittance_err").text("You're getting more than 6% of this year's remittance!");
        }else{
            $(".remittance_err").text("");
        }
    }

    // Add table Row script
    function addFiscalYearRow(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length - 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var idText = 'rowCount' + tableID + rowCount;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');
        //edited by ishrat to solve select box id auto increment related bug
        for (var i = 0; i < attrSel.length; i++) {
            var nameAtt = attrSel[i].name;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            attrSel[i].name = repText;
        }
        attrSel.val(''); //value reset
        // end of  solving issue related select box id auto increment related bug by ishrat

        //get input elements
        var attrInput = $("#" + tableID).find('#' + idText).find('input');
        for (var i = 0; i < attrInput.length; i++) {
            var nameAtt = attrInput[i].name;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            attrInput[i].name = repText;
        }
        attrInput.val(''); //value reset
        //edited by ishrat to solve textarea id auto increment related bug
        //get textarea elements
        var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
        for (var i = 0; i < attrTextarea.length; i++) {
            var nameAtt = attrTextarea[i].name;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            attrTextarea[i].name = repText;
            $('#' + idText).find('.readonlyClass').prop('readonly', true);
        }
        attrTextarea.val(''); //value reset
        // end of  solving issue related textarea id auto increment related bug by ishrat
        attrSel.prop('selectedIndex', 0);
        if ((tableID === 'machinaryTbl' && templateRow === 'rowMachineCount0') || (tableID === 'machinaryTbl' && templateRow === 'rowMachineCount')) {
            $("#" + tableID).find('#' + idText).find('select.m_currency').val("107");  //selected index reset
        } else {
            attrSel.prop('selectedIndex', 0);  //selected index reset
        }
        //$('.m_currency ').prop('selectedIndex', 102);
        //Class change by btn-danger to btn-primary
        $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            .attr('onclick', 'removeFiscalYearRow("' + tableID + '","' + idText + '")');
        $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
        $('#' + tableID).find('tr').last().attr('data-number', rowCount);

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
        $("#" + tableID).find('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            extraFormats: [ 'DD.MM.YY', 'DD.MM.YYYY' ],
            maxDate: 'now',
            minDate: '01/01/1905'
        });
    } // end of addTableRow() function

    // Remove Table row script
    function removeFiscalYearRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
        calculateOneNumber('other_remittance_bdt', 'other_sub_total_bdt');
        calculateOneNumber('other_remittance_usd', 'other_sub_total_usd');
        calculateRemittance('other_remittance_percentage', 'other_sub_total_percentage');
    }


    /*
     Compare Proposed Investment (BDT) and Actual Investment (BDT)
     of section 2. BIDA's Registration Info
     */
    function checkActualInvestment(element) {
        var proposed_inv = $(element).closest('tr').find('.proposed_investment').val();
        var actual_inv = $(element).closest('tr').find('.actual_investment').val();
        if (parseFloat(proposed_inv) < parseFloat(actual_inv)) {
            //$(element).closest('tr').find('.amendment_copy').attr('readonly', false);
            $(element).closest('tr').find('.amendment_copy').css({
                "pointer-events" : "initial",
                "background-color" : "#fff"
            });
        }
        else{
            //$(element).closest('tr').find('.amendment_copy').attr('readonly', true);
            $(element).closest('tr').find('.amendment_copy').css({
                "pointer-events" : "none",
                "background-color" : "#ddd"
            });
        }
    }

    $(document).ready(function(){

        //Step js .....
        var form = $("#RemittanceNewForm").show();
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
                if (newIndex === 4 && Number($("#age-2").val()) < 18)
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
                popupWindow = window.open('<?php echo URL::to('/remittance-new/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        //datePicker ....
        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/'+(yyyy-100),
            maxDate: '01/01/'+(yyyy+100)
        });


        $("#total_agreement_amount_bdt").on('keyup', function () {
            document.getElementsByName('proposed_amount_bdt')[0].value = this.value;
            document.getElementsByName('proposed_sub_total_bdt')[0].value = this.value;

            var proposed_sub_total_bdt= document.getElementsByName('proposed_sub_total_bdt')[0].value = this.value;
            var totalFee = '<?php echo json_encode($feesAmountRange); ?>';
            var fee = 0;
            $.each(JSON.parse(totalFee), function(i, row) {
                if ((proposed_sub_total_bdt >= parseInt(row.min_amount_bdt)) && (proposed_sub_total_bdt <= parseInt(row.max_amount_bdt))) {
                    fee = parseInt(row.p_o_amount_bdt);
                }
                if( proposed_sub_total_bdt >= 100000001){
                    fee = 500000;
                }

                $("#total_fee").val(fee);

            });
        });

        $("#total_agreement_amount_usd").on('keyup', function () {
            document.getElementsByName('proposed_amount_usd')[0].value = this.value;
            document.getElementsByName('proposed_sub_total_usd')[0].value = this.value;
        });


        //visible input field .........
        $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea,.readOnly').
        not("#business_sector_id, #business_sector_others, #business_sub_sector_id, #business_sub_sector_others, #local_bank_country_id, #origin_country_id, #property_country_id, #organization_status_id").attr('readonly',true);
        //$('.readOnlyCl :radio:not(:checked)').attr('disabled', true);
        $(".readOnlyCl select").each(function () {
            var id = $(this).attr('id');
            if(id != 'business_sector_id' && id != 'business_sub_sector_id' && id != 'local_bank_country_id' && id != 'origin_country_id' && id != 'property_country_id' && id != 'organization_status_id'){
                $("#"+id+" option:not(:selected)").prop('disabled', true);
            }
        });

        //business sector triger .....
        $("#business_sector_id").trigger('change');

        //secton B country wise field hide and show...
        $('#ceo_country_id').change(function (e) {
            var country_id = this.value;
            if (country_id == '18') {
                $("#ceo_city_div").addClass('hidden');
                // $("#ceo_city").removeClass('required');
                $("#ceo_state_div").addClass('hidden');
                $("#ceo_state").removeClass('required');
                $("#ceo_passport_div").addClass('hidden');
                // $("#ceo_passport_no").removeClass('required');


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
                // $("#ceo_city").addClass('required');
                $("#ceo_state_div").removeClass('hidden');
//            $("#ceo_state").addClass('required');
                $("#ceo_passport_div").removeClass('hidden');
                //$("#ceo_passport_no").addClass('required');

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


        $('#local_bank_id').on('change', function () {
            var local_bank_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "/remittance-new/get-branch-by-bank",
                data: {
                    "_token": "{{ csrf_token() }}",
                    local_bank_id: local_bank_id
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#local_branch_id").html(option);
                }
            });

        });

        $("#remittanceType").change(function () {
            var type = $(this).find("option:selected").text();
            var remittance_type_id = $(this).find("option:selected").val();
            $('#proposed_remittance_type').val(type);

            if(remittance_type_id == '4' || remittance_type_id == '5') {
                $("#intellectual_property_div").removeClass('hidden');
                $(".intPropReqField").addClass('required');
            } else {
                $("#intellectual_property_div").addClass('hidden');
                $(".intPropReqField").removeClass('required');
            }
        });

        $("#marketing_of_products_local").on('keyup', function () {
            var marketing_of_products_local= this.value;
            if(marketing_of_products_local <= 100 && marketing_of_products_local >= 0) {
                var cal = 100 - marketing_of_products_local;
                $('#marketing_of_products_foreign').val(cal);
            }else{
                alert("Please select a value between 0 & 100");
                $('#marketing_of_products_local').val('');
                $('#marketing_of_products_foreign').val('');
            }
        });

        $("#marketing_of_products_foreign").on('keyup', function () {
            var marketing_of_products_foreign= this.value;
            if(marketing_of_products_foreign <= 100 && marketing_of_products_foreign >= 0) {
                var cal = 100 - marketing_of_products_foreign;
                $('#marketing_of_products_local').val(cal);
            }else{
                alert("Please select a value between 0 & 100");
                $('#marketing_of_products_local').val('');
                $('#marketing_of_products_foreign').val('');
            }
        });

        //document.getElementById("amendment_copy").disabled = true;

    });

</script>

<script>
    $(document).ready(function(){
        //Duration of Agreement
        var dd_startDateDivID = 'agreement_duration_from_dp';
        var dd_startDateValID = 'agreement_duration_from';
        var dd_endDateDivID = 'agreement_duration_to_dp';
        var dd_endDateValID = 'agreement_duration_to';
        var dd_show_durationID = 'agreement_total_duration';

        $("#"+dd_startDateDivID).datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
        });
        $("#"+dd_endDateDivID).datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
        });

        $("#"+dd_startDateDivID).on("dp.change", function (e) {

            var startDateVal = $("#"+dd_startDateValID).val();

            if (startDateVal != '') {
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(e.date);
                var endDateVal = $("#"+dd_endDateValID).val();
                if (endDateVal !== '') {
                    getDateDuration(startDateVal, endDateVal, dd_show_durationID);
                } else {
                    //$("#"+dd_endDateValID).addClass('error');
                }
            } else {
                $("#"+dd_show_durationID).val('');
            }
        });

        $("#"+dd_endDateDivID).on("dp.change", function (e) {

            $("#"+dd_startDateDivID).data("DateTimePicker").maxDate(e.date);

            var startDateVal = $("#"+dd_startDateValID).val();

            if (startDateVal === '') {
                // $("#"+dd_startDateValID).addClass('error');
            } else {
                var day = moment(startDateVal, ['DD-MMM-YYYY']);
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(day);
            }

            var endDateVal = $("#"+dd_endDateValID).val();

            if (startDateVal != '' && endDateVal != '') {
                getDateDuration(startDateVal, endDateVal, dd_show_durationID);
            }else{
                $("#"+dd_show_durationID).val('');
            }
        });


        //Total Amount to be paid as per Agreement
        var ta_startDateDivID = 'period_from_dp';
        var ta_startDateValID = 'period_from';
        var ta_endDateDivID = 'period_to_dp';
        var ta_endDateValID = 'period_to';
        var ta_show_durationID = 'total_period';

        $("#"+ta_startDateDivID).datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
        });
        $("#"+ta_endDateDivID).datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
        });

        $('.YearPicker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY',
            extraFormats: [ 'DD.MM.YY', 'DD.MM.YYYY' ],
            // maxDate: 'now',
            minDate: '01/01/1905'
        });

        $("#"+ta_startDateDivID).on("dp.change", function (e) {

            var startDateVal = $("#"+dd_startDateValID).val();

            if (startDateVal != '') {
                $("#"+ta_endDateDivID).data("DateTimePicker").minDate(e.date);
                var endDateVal = $("#"+ta_endDateValID).val();
                if (endDateVal !== '') {
                    getDateDuration(startDateVal, endDateVal, ta_show_durationID);
                } else {
                    //$("#"+ta_endDateValID).addClass('error');
                }
            } else {
                $("#"+ta_show_durationID).val('');
            }
        });

        $("#"+ta_endDateDivID).on("dp.change", function (e) {

            $("#"+ta_startDateDivID).data("DateTimePicker").maxDate(e.date);

            var startDateVal = $("#"+ta_startDateValID).val();

            if (startDateVal === '') {
                // $("#"+ta_startDateValID).addClass('error');
            } else {
                var day = moment(startDateVal, ['DD-MMM-YYYY']);
                $("#"+ta_endDateDivID).data("DateTimePicker").minDate(day);
            }

            var endDateVal = $("#"+ta_endDateValID).val();

            if (startDateVal != '' && endDateVal != '') {
                getDateDuration(startDateVal, endDateVal, ta_show_durationID);
            }else{
                $("#"+ta_show_durationID).val('');
            }
        });

    });
</script>

{{--initail -input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
{{--//textarea count down--}}
<script src="{{ asset("assets/plugins/character-counter/jquery.character-counter.min.js") }}" src="" type="text/javascript"></script>
<script>
    $(function () {
        //max text count down
        $('.maxTextCountDown').characterCounter();

        $("#office_mobile_no").intlTelInput({
            hiddenInput: ["office_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#factory_mobile_no").intlTelInput({
            hiddenInput: ["factory_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#ceo_telephone_no").intlTelInput({
            hiddenInput: ["ceo_telephone_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#ceo_mobile_no").intlTelInput({
            hiddenInput: ["ceo_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#office_telephone_no").intlTelInput({
            hiddenInput: ["office_telephone_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#factory_telephone_no").intlTelInput({
            hiddenInput: ["factory_telephone_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: ["sfp_contact_phone"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#auth_mobile_no").intlTelInput({
            hiddenInput: ["auth_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});
    });
</script>