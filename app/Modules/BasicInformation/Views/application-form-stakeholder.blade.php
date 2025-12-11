@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap.css') }}">
    <style>
        .iti {
            width: 100% !important;
        }
        .form-group{
            margin-bottom: 2px;
        }
        .img-thumbnail{
            height: 100px;
            width: 100px;
        }
        input[type=radio].error,
        input[type=checkbox].error{
            outline: 1px solid red !important;
        }
    </style>
@endsection

@section('content')
    <section class="content">
        <div class="modal fade" id="changeBasicInfoModal" tabindex="-1" role="dialog" aria-labelledby="changeCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content load_modal"></div>
            </div>
        </div>

        <div class="modal fade" id="changeCompanyModal" role="dialog"
             aria-labelledby="changeCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content load_modal"></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="box">
                <div class="box-body" id="inputForm">
                    {{--start application form with wizard--}}
                    {!! Session::has('success') ? '
                    <div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                    ' : '' !!}
                    {!! Session::has('error') ? '
                    <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                    ' : '' !!}
                    <div class="panel panel-info">
                        <div class="panel-heading" style="overflow: hidden;">
                            <div class="pull-left">
                                <h5><strong> Application for Basic Information ({{ $applicant_type_name }})</strong></h5>
                            </div>
                            <div class="pull-right">
                                {{--Basic info change in modal start--}}
                                {{--                                    @if(in_array((!empty($appInfo->status_id) ? $appInfo->status_id : 0),[25]) && (\Illuminate\Support\Facades\Auth::user()->user_type == '5x505') && (\Illuminate\Support\Facades\Auth::user()->company_ids == $appInfo->company_id))--}}
                                {{--                                        <a class="btn btn-info" data-toggle="modal" data-target="#changeBasicInfoModal"--}}
                                {{--                                           onclick="openChangeBasicInfoModal(this)"--}}
                                {{--                                           data-action="{{ url('basic-information/change-basic-info/'.\App\Libraries\Encryption::encodeId($appInfo->id).'/'.\App\Libraries\Encryption::encodeId($company_id)) }}">--}}
                                {{--                                            Change Basic Info--}}
                                {{--                                        </a>--}}
                                {{--                                    @endif--}}
                                {{--Basic info change in modal end--}}

                                {{--Company info change in modal--}}
                                @if(!in_array((!empty($appInfo->status_id) ? $appInfo->status_id : 0),[25]) && \App\Libraries\ACL::getAccsessRight('processPath', '-CC-'))
                                    <a class="btn btn-warning" data-toggle="modal" data-target="#changeCompanyModal"
                                       onclick="openChangeCompanyModal(this)"
                                       data-action="{{ url('process/change-company/'.\App\Libraries\Encryption::encodeId($company_id)) }}">
                                        <strong>Change organization</strong>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="form-body">
                            <div>
                                <?php  ?>
                                {!! Form::open(array('url' => '/basic-information/form-stakeholder/add','method' => 'post','id' => 'basicInformationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                                <input type="hidden" name="applicant_type" value="{{ Encryption::encodeId($applicant_type) }}"/>

                                <div class="col-md-12">
                                    {{--A. Company Information--}}
                                    <div class="panel panel-info" style="margin-top: 20px;">
                                        <div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>
                                        <div class="panel-body">
                                            <div id="validationError"></div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                        {!! Form::label('company_name','Name of Organization in English (Proposed)',['class'=>'col-md-3 text-left', 'id'=> 'company_name_label']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::text('company_name', CommonFunction::getCompanyNameById(Auth::user()->company_ids), ['class' => 'form-control input-md', 'readonly']) !!}
                                                            {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                                        {!! Form::label('company_name_bn','Name of Organization in Bangla (Proposed)',['class'=>'col-md-3 text-left']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::text('company_name_bn', CommonFunction::getCompanyBnNameById(Auth::user()->company_ids), ['class' => 'form-control input-md', 'readonly']) !!}
                                                            {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('organization_status_id') ? 'has-error': ''}}">
                                                        {!! Form::label('organization_status_id','Status of the Organization',['class'=>'col-md-3 text-left', 'id'=> 'organization_status_id_label']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::select('organization_status_id', $eaOrganizationStatus, '',['class'=>'form-control input-md', 'id' => 'organization_status_id']) !!}
                                                            {!! $errors->first('organization_status_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-3 text-left required-star']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::select('ownership_status_id', $eaOwnershipStatus, '', ['class' => 'form-control required input-md','id'=>'ownership_status_id']) !!}
                                                            {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                        {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-3 text-left required-star']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::select('organization_type_id', $eaOrganizationType, '', ['class' => 'form-control required input-md ','id'=>'organization_type_id']) !!}
                                                            {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                                                        {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('major_activities', '', ['class' => 'form-control input-md bigInputField maxTextCountDown', 'size' =>'5x2','data-rule-maxlength'=>'200', 'placeholder' => 'Maximum 200 characters','data-charcount-maxlength'=>'200']) !!}
                                                            {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO--}}
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO</strong></div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ceo_country_id', $countries, '', ['class' => 'form-control required input-md ','id'=>'ceo_country_id']) !!}
                                                            {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5']) !!}
                                                        <div class=" col-md-7">
                                                            <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">
                                                                {!! Form::text('ceo_dob', '', ['class'=>'form-control input-md date', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker']) !!}
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
                                                            {!! Form::text('ceo_passport_no', '', ['maxlength'=>'20',
                                                            'class' => 'form-control input-md', 'id'=>'ceo_passport_no']) !!}
                                                            {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_nid_div" class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_nid', '', ['maxlength'=>'20',
                                                            'class' => 'form-control number input-md required bd_nid','id'=>'ceo_nid']) !!}
                                                            {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_designation', '',
                                                            ['maxlength'=>'80','class' => 'form-control input-md required']) !!}
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
                                                            {!! Form::text('ceo_full_name', '', ['maxlength'=>'80', 'class' => 'form-control input-md required']) !!}
                                                            {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_district_div" class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_district_id','District/ City/ State ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ceo_district_id', $districts, '', ['class' => 'form-control input-md', 'onchange'=>"getThanaByDistrictId('ceo_district_id', this.value, 'ceo_thana_id', ".(!empty($appInfo->ceo_thana_id)?$appInfo->ceo_thana_id:'').")"]) !!}
                                                            {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_city_div" class="col-md-6 hidden {{$errors->has('ceo_city') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_city','District/ City/ State',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_city', '',['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="ceo_state_div" class="col-md-6 hidden {{$errors->has('ceo_state') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_state','State/ Province',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_state', '',['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_thana_div" class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_thana_id','Police Station/ Town ',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ceo_thana_id', [''], '', ['class' => 'form-control input-md','placeholder' => 'Select district first']) !!}
                                                            {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_post_code','Post/ Zip Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_post_code', '', ['maxlength'=>'80','class' => 'form-control input-md required']) !!}
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
                                                            {!! Form::text('ceo_address', '', ['maxlength'=>'150','class' => 'form-control input-md required']) !!}
                                                            {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_telephone_no', '', ['maxlength'=>'20','class' => 'form-control input-md helpText15']) !!}
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
                                                            {!! Form::text('ceo_mobile_no', '', ['class' => 'form-control required input-md helpText15','id' => 'ceo_mobile_no']) !!}
                                                            {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_father_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_father_name', '', ['class' => 'form-control input-md required']) !!}
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
                                                            {!! Form::email('ceo_email', '', ['class' => 'form-control required email input-md']) !!}
                                                            {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_mother_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_mother_name', '', ['class' => 'form-control required input-md']) !!}
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
                                                            {!! Form::text('ceo_fax_no', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_spouse_name', '', ['class' => 'form-control input-md']) !!}
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
                                                                {!! Form::radio('ceo_gender', 'Male', 0, ['class'=>'required']) !!}
                                                                Male
                                                            </label>
                                                            <label class="radio-inline">
                                                                {!! Form::radio('ceo_gender', 'Female', 0, ['class'=>'required']) !!}
                                                                Female
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--C. Office Address--}}
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>C. Office Address </strong></div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                                                        {!! Form::label('office_division_id','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_division_id', $divisions, '', ['class' => 'form-control input-md required', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id',".(!empty($appInfo->office_district_id)?$appInfo->office_district_id:'').")"]) !!}
                                                            {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_district_id', $districts, '', ['class' => 'form-control required input-md','placeholder' => 'Select division first', 'id' => 'office_district_id', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id', ".(!empty($appInfo->office_thana_id)?$appInfo->office_thana_id:'').")"]) !!}
                                                            {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_thana_id',[''], '', ['class' => 'form-control required input-md','placeholder' => 'Select district first']) !!}
                                                            {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('office_post_office') ? 'has-error': ''}}">
                                                        {!! Form::label('office_post_office','Post Office',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_post_office', '', ['class' => 'form-control required input-md']) !!}
                                                            {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('office_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_post_code', '', ['class' => 'form-control required input-md']) !!}
                                                            {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('office_address') ? 'has-error': ''}}">
                                                        {!! Form::label('office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_address', '', ['maxlength'=>'150','class' => 'form-control required input-md']) !!}
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
                                                            {!! Form::text('office_telephone_no', '', ['maxlength'=>'20','class' => 'form-control input-md helpText15']) !!}
                                                            {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('office_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('office_mobile_no', '', ['class' => 'form-control required phone_or_mobile input-md helpText15' ,'id' => 'office_mobile_no']) !!}
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
                                                            {!! Form::text('office_fax_no', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('office_email') ? 'has-error': ''}}">
                                                        {!! Form::label('office_email','Email ',['class'=>'col-md-5 required text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('office_email', '', ['class' => 'form-control required email input-md']) !!}
                                                            {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--D. Factory Address--}}
                                    <div class="panel panel-info" id="factory_info">
                                        <div class="panel-heading"><strong>D. Factory Address [ optional ] </strong></div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left','id' => 'factory_district_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('factory_district_id', $districts, '', ['class' => 'form-control input-md', 'onchange'=>"getThanaByDistrictId('factory_district_id', this.value, 'factory_thana_id',".(!empty($appInfo->factory_thana_id)?$appInfo->factory_thana_id:'').")"]) !!}
                                                            {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_thana_id','Police Station', ['class'=>'col-md-5 text-left', 'id' => 'factory_thana_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('factory_thana_id', [''], '', ['class' => 'form-control input-md','placeholder' => 'Select district first']) !!}
                                                            {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('factory_post_office') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_post_office','Post Office',['class'=>'col-md-5 text-left', 'id'=>'factory_post_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_post_office', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left', 'id'=>'factory_post_code_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_post_code', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('factory_address') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left', 'id'=>'factory_address_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_address', '', ['maxlength'=>'150','class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_telephone_no', '', ['maxlength'=>'20','class' => 'form-control input-md helpText15']) !!}
                                                            {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('factory_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left', 'id'=>'factory_mobile_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_mobile_no', '', ['class' => 'form-control input-md','id' => 'factory_mobile_no']) !!}
                                                            {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_fax_no', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('factory_email') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_email','Email ',['class'=>'col-md-5 text-left', 'id'=>'factory_email_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_email', '', ['class' => 'form-control email input-md']) !!}
                                                            {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left', 'id'=>'factory_mouja_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('factory_mouja', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--Authorized Person Information--}}
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>Authorized Person Information</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('auth_full_name') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_full_name','Full Name ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_full_name', CommonFunction::getUserFullName(), ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', Auth::user()->designation, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', Auth::user()->user_phone, ['class' => 'form-control input-md required phone_or_mobile','id' => 'auth_mobile_no', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' => 'form-control input-md email required', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('auth_letter') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_letter','Authorization Letter ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">

                                                            @if(!empty($appInfo->auth_letter))
                                                                <div class="save_file" style="margin-top: 5px">
                                                                    <a target="_blank" rel="noopener"
                                                                       class="btn btn-xs btn-primary show-in-view"
                                                                       title="Authorization Letter"
                                                                       href="{{URL::to('users/upload/'.$appInfo->auth_letter)}}"><i
                                                                                class="fa fa-file-pdf-o"></i> Authorization
                                                                        Letter</a>
                                                                    <input type="hidden" value="{{ $appInfo->auth_letter }}" id="auth_letter"
                                                                           name="auth_letter"/>
                                                                </div>
                                                            @else
                                                                {{--Old Authorization letter--}}
                                                                @if(!empty($auth_letter))
                                                                    <input type="hidden" name="old_auth_letter" value="{{ $auth_letter }}">
                                                                    <a href="{{ URL::to('users/upload/'.$auth_letter) }}" target="_blank" rel="noopener" class="btn btn-xs btn-primary show-in-view" title="Provided authorization letter" style="margin-top: 5px;">
                                                                        <i class="fa  fa-file-pdf-o"></i>
                                                                        Provided authorization letter
                                                                    </a><br/>
                                                                @endif
                                                            @endif

                                                            <br/>
                                                            <small>
                                                                If you want to change the authorization letter, please select below.
                                                            </small>

                                                            <input type="file" onchange="checkPdfDocumentType(this.id, 3)" accept="application/pdf" name="auth_letter" id="auth_letter" class="form-control input-md"/>
                                                            <small style="font-size: 9px; font-weight: bold; color: #666363; font-style: italic">
                                                                [Format: *.PDF | Maximum 3 MB, Application with Name & Signature] </small>
                                                            <br>

                                                            <a target="_blank" rel="noopener" href="{{ url('assets/images/sample_auth_letter.png') }}"><i class="fa fa-file" aria-hidden="true"></i> <i>Sample Authorization letter</i></a>
                                                            {!! $errors->first('auth_letter','<span class="help-block">:message</span>') !!}

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail" id="authImageViewer"
                                                                 src="{{ isset($appInfo->auth_image) != '' ? url('users/upload/'.$appInfo->auth_image) : url('users/upload/'.\Illuminate\Support\Facades\Auth::user()->user_pic) }}"
                                                                 alt="Auth Image">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--Terms and Conditions--}}
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>Terms and Conditions</strong></div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group {{$errors->has('acceptTerms') ? 'has-error' : ''}}">
                                                    <div class="col-md-12">
                                                        <label style="margin-left: 1%">If you are submitting above any false information through the system, you shall be liable under ICT act of Government of Bangladesh.</label>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="checkbox">
                                                            <label>
                                                                {!! Form::checkbox('acceptTerms', 'yes', '', array('class'=>'required col-md-1 col-xs-1 col-sm-1','id'=>'acceptTerms-2','style'=>'width:3%;')) !!}
                                                                <label for="acceptTerms-2" class="text-left required-star text-danger">I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information is given. </label>
                                                                <div class="clearfix"></div>
                                                                {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        @if(ACL::getAccsessRight('BasicInformation','-E-'))
                            <div class="panel-footer" style="overflow: hidden;">
                                <div class="pull-right">
                                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md"
                                            value="Submit" name="actionBtn">Submit
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    {!! Form::close() !!}
                    {{--End application form with wizard--}}
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/plugins/select2.min.js") }}" type="text/javascript"></script>
    <script type="text/javascript">

        function openChangeBasicInfoModal(btn) {
            var this_action = btn.getAttribute('data-action');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        $('#changeBasicInfoModal .load_modal').html(data);
                    } else {
                        $('#changeBasicInfoModal .load_modal').html('Unknown Error!');
                    }
                    $('#changeBasicInfoModal').modal('show', {backdrop: 'static'});
                });
            }
        }

        $(document).ready(function () {

            $("#ceo_mobile_no").intlTelInput({
                hiddenInput: "ceo_mobile_no",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true,
            });

            $("#ceo_telephone_no").intlTelInput({
                hiddenInput: "ceo_telephone_no",
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

            $("#office_mobile_no").intlTelInput({
                hiddenInput: "office_mobile_no",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true,
            });

            $("#factory_mobile_no").intlTelInput({
                hiddenInput: "factory_mobile_no",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true,
            });

            $("#office_telephone_no").intlTelInput({
                hiddenInput: "office_telephone_no",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true,
            });

            $("#factory_telephone_no").intlTelInput({
                hiddenInput: "factory_telephone_no",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true,
            });
            {{--initail -input plugin script end--}}

            //max text count down
            $('.maxTextCountDown').characterCounter();

            // Get application module name
            var uri = '{{ Request::segment(1) }}';
            if (uri) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo url(); ?>/process/help-text", //checking open mode permission and get url
                    data: {
                        uri: uri,
                        _token: _token
                    },
                    success: function (response) {
                        $.each(response.data, function (key, value) {
                            if (value.filed_id) {

                                // Front-end validation class
                                var validate_class = value.validation_class;

                                if (validate_class.search("required") != -1 ) {

                                    var closest_div = $("#" + value.filed_id ).closest("div");
                                    (closest_div.hasClass('input-group') || closest_div.hasClass('intl-tel-input')) == false ?
                                        closest_div.prev().addClass('required-star') :
                                        closest_div.parent("div").prev().addClass('required-star');
                                }
                                $("#" + value.filed_id).addClass(value.validation_class);

                                if (value.type == 'tooltip') {

                                    if ($("#" + value.filed_id).hasClass("date") || $("#" + value.filed_id).hasClass("helpText15")) {
                                        $("#" + value.filed_id).before('<i class="fa fa-question-circle" style="cursor: pointer; position: absolute; top: 10px; left: -15px;" data-toggle="tooltip" data-placement="top" title="' + value.help_text + '" ></i>');
                                    }else {
                                        $("#" + value.filed_id).before('<i class="fa fa-question-circle" style="cursor: pointer; position: absolute; top: 10px; left: 0px;" data-toggle="tooltip" data-placement="top" title="' + value.help_text + '" ></i>');
                                    }

                                } else if (value.type == 'bubble') {

                                    datas = value.help_text;
                                    count = key;
                                    $("#" + value.filed_id).after('<i class="bubble' + count + ' fa fa-question-circle"  id="bubble' + count + " #" + datas + '" style="cursor: pointer; position: absolute; top: 10px; right: 0px;"onclick="showHelpText(this.id)" data-toggle="tooltip" data-placement="top" title="Please click here"  ></i>');

                                }
                            }
                        });
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }

            $("#basicInformationForm").validate({
                errorPlacement: function () {
                    return false;
                }
            });

            // ceo country, city district, state thana, father, mother
            $('#ceo_country_id').change(function (e) {
                var country_id = this.value;
                if (country_id == '18') {
                    $("#ceo_city_div").addClass('hidden');
                    $("#ceo_city").removeClass('required');
                    $("#ceo_state_div").addClass('hidden');
                    $("#ceo_state").removeClass('required');
                    $("#ceo_passport_div").addClass('hidden');
//                $("#ceo_passport_no").removeClass('required');


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
//                $("#ceo_passport_no").addClass('required');

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
            $('#ceo_district_id').trigger('change');
            $('#office_division_id').trigger('change');
            $('#office_district_id').trigger('change');
            $('#factory_district_id').trigger('change');


            var today = new Date();
            var yyyy = today.getFullYear();

            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now'
            });

        });
    </script>
@endsection
