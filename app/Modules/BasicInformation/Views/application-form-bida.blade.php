@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap.css') }}">
    <style>
        .mb0 {
            margin-bottom: 0;
        }
        .disable_forStkeholder {
            /*pointer-events: none;*/
        }
        .form-group {
            margin-bottom: 2px;
        }

        .img-thumbnail {
            height: 100px;
            width: 100px;
        }

        input[type=radio].error,
        input[type=checkbox].error {
            outline: 1px solid red !important;
        }
    </style>
@endsection

@section('content')
    @if (!empty($alert))
        <h4 style='color: red;margin-top: 250px;text-align: center;'>{{ $alert }}</h4>
    @else

        <?php

        if($isExitForStakeholder == 'YES'){
            $readonly = 'readonly'; ?>

        <?php

        }else {
            $readonly = '';

        } ?>

        <section class="content">

            <div class="modal fade" id="changeCompanyModal" role="dialog"
                 aria-labelledby="changeCompanyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content load_modal"></div>
                </div>
            </div>

            <div class="modal fade" id="deptMoreInfoModal" role="dialog"
                 aria-labelledby="deptMoreInfoLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content load_modal"></div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box">
                    <div class="box-body" id="inputForm">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{--start application form with wizard--}}
                        {!! Session::has('success') ? '
                        <div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                        ' : '' !!}
                        {!! Session::has('error') ? '
                        <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                        ' : '' !!}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h5><strong> Application for Basic Information ({{ $applicant_type_name }})</strong></h5>
                                </div>
                                <div class="pull-right">

                                    {{--Company info change in modal--}}
                                    @if(!in_array((!empty($appInfo->status_id) ? $appInfo->status_id : 0),[25]) && \App\Libraries\ACL::getAccsessRight('processPath', '-CC-'))
                                        <a class="btn btn-warning" data-toggle="modal" data-target="#changeCompanyModal"
                                           onclick="openChangeCompanyModal(this)"
                                           data-action="{{ url('process/change-company/'.\App\Libraries\Encryption::encodeId($company_id)) }}">
                                            Change organization
                                        </a>
                                    @endif

                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                {!! Form::open(array('url' => '/basic-information/form-bida/add','method' => 'post','id' => 'basicInformationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                                <input type="hidden" name="applicant_type"
                                       value="{{ Encryption::encodeId($applicant_type) }}"/>

                                {{--A. Company Information--}}
                                <div class="panel panel-info">
                                    <div class="panel-heading margin-for-preview"><strong>A. Company
                                            Information</strong></div>
                                    <div class="panel-body">
                                        <div id="validationError"></div>

                                        {{--                                        @if(in_array(!empty($appInfo->status_id), [25]) && !empty($appInfo->department_id) != '')--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <div class="row">--}}
                                        {{--                                                    <div class="col-md-12 {{$errors->has('department_id') ? 'has-error': ''}}">--}}
                                        {{--                                                        {!! Form::label('department_id','Department',['class'=>'col-md-3 text-left required-star']) !!}--}}
                                        {{--                                                        <div class="col-md-9">--}}
                                        {{--                                                            {!! Form::select('department_id', $departmentList, $appInfo->department_id, ['class' => 'form-control required input-md ','id'=>'department_id']) !!}--}}
                                        {{--                                                            {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}--}}
                                        {{--                                                        </div>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        @endif--}}

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name','Name of Organization in English (Proposed)',['class'=>'col-md-3 text-left']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::text('company_name', (!empty($appInfo->company_name) ? $appInfo->company_name : \App\Libraries\CommonFunction::getCompanyNameById(\Illuminate\Support\Facades\Auth::user()->company_ids)), ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name_bn','Name of Organization in Bangla (Proposed)',['class'=>'col-md-3 text-left']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::text('company_name_bn', (!empty($appInfo->company_name_bn) ? $appInfo->company_name_bn : \App\Libraries\CommonFunction::getCompanyBnNameById(\Illuminate\Support\Facades\Auth::user()->company_ids)), ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('service_type') ? 'has-error': ''}}">
                                                    {!! Form::label('service_type','Desired Service from BIDA',['class'=>'col-md-3 text-left required-star', 'id'=> 'service_type_label']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::select('service_type', $eaService, (!empty($appInfo->service_type) ? $appInfo->service_type : ''),['class'=>'form-control required input-md', 'id' => 'service_type']) !!}
                                                        {!! $errors->first('service_type','<span class="help-block">:message</span>') !!}
                                                        <a style="cursor: pointer; font-size: small; margin-bottom: 5px;" data-toggle="modal" data-target="#deptMoreInfoModal" onclick="openDeptMoreInfoModal(this)"
                                                           data-action="{{ url('basic-information/dept-more-info') }}">
                                                            Click here for details information
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="RegCommercialOfficesDiv">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="col-md-3"></label>
                                                    <div class="col-md-9">
                                                        {!! Form::select('reg_commercial_office',$eaRegCommercialOffices, (!empty($appInfo->reg_commercial_office) ? $appInfo->reg_commercial_office  : ''),['class'=>'form-control input-md required', 'id' => 'reg_commercial_office', ]) !!}
                                                        {!! $errors->first('reg_commercial_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($business_category == 1)  {{---1 = Private--}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-3 text-left required-star']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::select('ownership_status_id', $eaOwnershipStatus, (!empty($appInfo->ownership_status_id) ? $appInfo->ownership_status_id : ''), ['class' => 'form-control required input-md disable_forStkeholder', 'id'=>'ownership_status_id']) !!}
                                                        {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                    {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-3 text-left required-star']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::select('organization_type_id', $eaOrganizationType, (!empty($appInfo->organization_type_id) ? $appInfo->organization_type_id : ''), ['class' => 'form-control required input-md disable_forStkeholder','id'=>'organization_type_id' ]) !!}
                                                        {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('organization_type_other') ? 'has-error': ''}}" id="organization_type_other_div" hidden>
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-9">
                                                        {!! Form::text('organization_type_other',  (!empty($appInfo->organization_type_other) ? $appInfo->organization_type_other : ''), ['class' => 'form-control input-md','id'=>'organization_type_other', 'placeholder'=>'Specify others type']) !!}
                                                        {!! $errors->first('organization_type_other','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                                                    {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::textarea('major_activities', (!empty($appInfo->major_activities) ? $appInfo->major_activities : ''), ['class' => 'form-control input-md bigInputField maxTextCountDown', 'size' =>'5x2','data-rule-maxlength'=>'200', 'placeholder' => 'Maximum 200 characters','data-charcount-maxlength'=>'200', $readonly]) !!}
                                                        {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Start business category --}}
                                @if($business_category == 2)
                                    {{--B. Information of Responsible Person--}}
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>B. Information of Responsible Person</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ceo_country_id', $countries, (!empty($appInfo->ceo_country_id) ? $appInfo->ceo_country_id : ''), ['class' => 'form-control required input-md disable_forStkeholder','id'=>'ceo_country_id']) !!}
                                                            {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_full_name', (!empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : ''), ['maxlength'=>'80',
                                                            'class' => 'form-control input-md required', $readonly]) !!}
                                                            {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="ceo_passport_div"
                                                         class="col-md-6 hidden {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_passport_no', (!empty($appInfo->ceo_passport_no) ? $appInfo->ceo_passport_no : ''), ['maxlength'=>'20',
                                                            'class' => 'form-control input-md', 'id'=>'ceo_passport_no', $readonly]) !!}
                                                            {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_nid_div"
                                                         class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_nid', (!empty($appInfo->ceo_nid) ? $appInfo->ceo_nid : ''), ['maxlength'=>'20',
                                                            'class' => 'form-control input-md required bd_nid','id'=>'ceo_nid',$readonly]) !!}
                                                            {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_designation', (!empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : ''),
                                                            ['maxlength'=>'80','class' => 'form-control input-md required',$readonly]) !!}
                                                            {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_mobile_no', (!empty($appInfo->ceo_mobile_no) ? $appInfo->ceo_mobile_no : ''), ['class' => 'form-control input-md helpText15 required','id' => 'ceo_mobile_no', $readonly]) !!}
                                                            {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('ceo_email', (!empty($appInfo->ceo_email) ? $appInfo->ceo_email : ''), ['class' => 'form-control required email input-md' , $readonly]) !!}
                                                            {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
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
                                                                {!! Form::radio('ceo_gender', 'Male', !empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Male", ['class'=>'required']) !!}
                                                                Male
                                                            </label>
                                                            <label class="radio-inline">
                                                                {!! Form::radio('ceo_gender', 'Female', !empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Female", ['class'=>'required']) !!}
                                                                Female
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('ceo_auth_letter') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_auth_letter','Authorization Letter',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            @if(!empty($appInfo->ceo_auth_letter))
                                                                <div class="save_file" style="margin-top: 5px">
                                                                    <a target="_blank" rel="noopener" class="btn btn-xs btn-primary show-in-view" title="Authorization Letter"
                                                                       href="{{URL::to('users/upload/'.$appInfo->ceo_auth_letter)}}"><i class="fa fa-file-pdf-o"></i>
                                                                        Authorization Letter
                                                                    </a>
                                                                    <input type="hidden" value="{{ $appInfo->ceo_auth_letter }}" id="ceo_auth_letter" name="ceo_auth_letter"/>
                                                                </div>
                                                            @endif

                                                            <input type="file" accept="application/pdf" name="ceo_auth_letter" onchange="checkPdfDocumentType(this.id, 3)"
                                                                   id="ceo_auth_letter" class="form-control input-md required"/>
                                                            <small style="font-size: 9px; font-weight: bold; color: #666363; font-style: italic">
                                                                [Format: *.PDF | Maximum 3 MB, Application with Name & Signature] </small>
                                                                <br/>
                                                            {!! $errors->first('ceo_auth_letter','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{--B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO--}}
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>B. Information of Principal Promoter/
                                                Chairman/ Managing Director/ State CEO</strong></div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ceo_country_id', $countries, (!empty($appInfo->ceo_country_id) ? $appInfo->ceo_country_id : ''), ['class' => 'form-control required input-md disable_forStkeholder','id'=>'ceo_country_id']) !!}
                                                            {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5']) !!}
                                                        <div class=" col-md-7">
                                                            <div class="datepicker input-group date"
                                                                 data-date-format="dd-mm-yyyy">
                                                                {!! Form::text('ceo_dob', (empty($appInfo->ceo_dob) ? '' : date('d-M-Y', strtotime($appInfo->ceo_dob))), ['class'=>'form-control input-md date', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker']) !!}
                                                                <span class="input-group-addon"><span
                                                                            class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="ceo_passport_div"
                                                         class="col-md-6 hidden {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_passport_no', (!empty($appInfo->ceo_passport_no) ? $appInfo->ceo_passport_no : ''), ['maxlength'=>'20',
                                                            'class' => 'form-control input-md', 'id'=>'ceo_passport_no', $readonly]) !!}
                                                            {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_nid_div"
                                                         class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_nid', (!empty($appInfo->ceo_nid) ? $appInfo->ceo_nid : ''), ['maxlength'=>'20',
                                                            'class' => 'form-control input-md required bd_nid','id'=>'ceo_nid',$readonly]) !!}
                                                            {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_designation', (!empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : ''),
                                                            ['maxlength'=>'80','class' => 'form-control input-md required',$readonly]) !!}
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
                                                            {!! Form::text('ceo_full_name', (!empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : ''), ['maxlength'=>'80',
                                                            'class' => 'form-control input-md required', $readonly]) !!}
                                                            {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_district_div"
                                                         class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_district_id','District/ City/ State ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ceo_district_id', $districts, (!empty($appInfo->ceo_district_id) ? $appInfo->ceo_district_id : ''), ['class' => 'form-control input-md disable_forStkeholder', 'onchange'=>"getThanaByDistrictId('ceo_district_id', this.value, 'ceo_thana_id', ".(!empty($appInfo->ceo_thana_id)?$appInfo->ceo_thana_id:'').")"]) !!}
                                                            {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_city_div"
                                                         class="col-md-6 hidden {{$errors->has('ceo_city') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_city','District/ City/ State',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_city', (!empty($appInfo->ceo_city) ? $appInfo->ceo_city : ''),['class' => 'form-control input-md', $readonly]) !!}
                                                            {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="ceo_state_div"
                                                         class="col-md-6 hidden {{$errors->has('ceo_state') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_state','State/ Province',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_state', (!empty($appInfo->ceo_state) ? $appInfo->ceo_state : ''),['class' => 'form-control input-md', $readonly]) !!}
                                                            {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="ceo_thana_div"
                                                         class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_thana_id','Police Station/ Town ',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('ceo_thana_id', [''], '', ['class' => 'form-control input-md disable_forStkeholder','placeholder' => 'Select district first']) !!}
                                                            {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_post_code','Post/ Zip Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_post_code', (!empty($appInfo->ceo_post_code) ? $appInfo->ceo_post_code : ''), ['maxlength'=>'80','class' => 'form-control input-md required', $readonly]) !!}
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
                                                            {!! Form::text('ceo_address', (!empty($appInfo->ceo_address) ? $appInfo->ceo_address : ''), ['maxlength'=>'150','class' => 'form-control input-md required',$readonly]) !!}
                                                            {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_telephone_no', (!empty($appInfo->ceo_telephone_no) ? $appInfo->ceo_telephone_no : ''), ['maxlength'=>'20','class' => 'form-control input-md helpText15', $readonly]) !!}
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
                                                            {!! Form::text('ceo_mobile_no', (!empty($appInfo->ceo_mobile_no) ? $appInfo->ceo_mobile_no : ''), ['class' => 'form-control input-md helpText15 required','id' => 'ceo_mobile_no', $readonly]) !!}
                                                            {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_father_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_father_name', (!empty($appInfo->ceo_father_name) ? $appInfo->ceo_father_name : ''), ['class' => 'form-control input-md required',$readonly]) !!}
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
                                                            {!! Form::email('ceo_email', (!empty($appInfo->ceo_email) ? $appInfo->ceo_email : ''), ['class' => 'form-control required email input-md' , $readonly]) !!}
                                                            {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_mother_label']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_mother_name', (!empty($appInfo->ceo_mother_name) ? $appInfo->ceo_mother_name : ''), ['class' => 'form-control required input-md', $readonly]) !!}
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
                                                            {!! Form::text('ceo_fax_no', (!empty($appInfo->ceo_fax_no) ? $appInfo->ceo_fax_no : ''), ['class' => 'form-control input-md',$readonly]) !!}
                                                            {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('ceo_spouse_name', (!empty($appInfo->ceo_spouse_name) ? $appInfo->ceo_spouse_name : ''), ['class' => 'form-control input-md', $readonly]) !!}
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
                                                                {!! Form::radio('ceo_gender', 'Male', !empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Male", ['class'=>'required']) !!}
                                                                Male
                                                            </label>
                                                            <label class="radio-inline">
                                                                {!! Form::radio('ceo_gender', 'Female', !empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Female", ['class'=>'required']) !!}
                                                                Female
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{--C. Office Address--}}
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>C. Office Address </strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_division_id','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_division_id', $divisions, (!empty($appInfo->office_division_id) ? $appInfo->office_division_id : null), ['class' => 'form-control input-md required disable_forStkeholder', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id',".(!empty($appInfo->office_district_id)?$appInfo->office_district_id:'').")"]) !!}
                                                        {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_district_id', $districts, (!empty($appInfo->office_district_id) ? $appInfo->office_district_id : null), ['class' => 'form-control required input-md disable_forStkeholder','placeholder' => 'Select division first', 'id' => 'office_district_id', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id', ".(!empty($appInfo->office_thana_id)?$appInfo->office_thana_id:'').")"]) !!}
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
                                                        {!! Form::select('office_thana_id',[''], '', ['class' => 'form-control required input-md disable_forStkeholder','placeholder' => 'Select district first']) !!}
                                                        {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('office_post_office','Post Office',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_post_office', (!empty($appInfo->office_post_office) ? $appInfo->office_post_office : null), ['class' => 'form-control required input-md', $readonly]) !!}
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
                                                        {!! Form::text('office_post_code', (!empty($appInfo->office_post_code) ? $appInfo->office_post_code : ''), ['class' => 'form-control input-md required', $readonly]) !!}
                                                        {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_address', (!empty($appInfo->office_address) ? $appInfo->office_address : ''), ['maxlength'=>'150','class' => 'form-control required input-md', $readonly]) !!}
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
                                                        {!! Form::text('office_telephone_no', (!empty($appInfo->office_telephone_no) ? $appInfo->office_telephone_no : ''), ['maxlength'=>'20','class' => 'form-control input-md helpText15', $readonly]) !!}
                                                        {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_mobile_no', (!empty($appInfo->office_mobile_no) ? $appInfo->office_mobile_no : ''), ['class' => 'form-control required phone_or_mobile input-md helpText15' ,'id' => 'office_mobile_no', $readonly]) !!}
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
                                                        {!! Form::text('office_fax_no', (!empty($appInfo->office_fax_no) ? $appInfo->office_fax_no : ''), ['class' => 'form-control input-md', $readonly]) !!}
                                                        {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_email') ? 'has-error': ''}}">
                                                    {!! Form::label('office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('office_email', (!empty($appInfo->office_email) ? $appInfo->office_email : ''), ['class' => 'form-control required email input-md', $readonly]) !!}
                                                        {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--D. Factory Address--}}
                                <div class="panel panel-info" id="factory_info">
                                    <div class="panel-heading"><strong>D. Factory Address </strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left','id' => 'factory_district_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('factory_district_id', $districts, (!empty($appInfo->factory_district_id) ? $appInfo->factory_district_id : ''), ['class' => 'form-control input-md', 'onchange'=>"getThanaByDistrictId('factory_district_id', this.value, 'factory_thana_id',".(!empty($appInfo->factory_thana_id)?$appInfo->factory_thana_id:'').")"]) !!}
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
                                                        {!! Form::text('factory_post_office', (!empty($appInfo->factory_post_office) ? $appInfo->factory_post_office : ''), ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left', 'id'=>'factory_post_code_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_post_code', (!empty($appInfo->factory_post_code) ? $appInfo->factory_post_code : ''), ['class' => 'form-control input-md engOnly']) !!}
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
                                                        {!! Form::text('factory_address', (!empty($appInfo->factory_address) ? $appInfo->factory_address : ''), ['maxlength'=>'150','class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_telephone_no', (!empty($appInfo->factory_telephone_no) ? $appInfo->factory_telephone_no : ''), ['maxlength'=>'20','class' => 'form-control input-md']) !!}
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
                                                        {!! Form::text('factory_mobile_no', (!empty($appInfo->factory_mobile_no) ? $appInfo->factory_mobile_no : ''), ['class' => 'form-control input-md helpText15','id' => 'factory_mobile_no']) !!}
                                                        {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_fax_no', (!empty($appInfo->factory_fax_no) ? $appInfo->factory_fax_no : ''), ['class' => 'form-control input-md']) !!}
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
                                                        {!! Form::text('factory_email', (!empty($appInfo->factory_email) ? $appInfo->factory_email : ''), ['class' => 'form-control email input-md']) !!}
                                                        {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left', 'id'=>'factory_mouja_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_mouja', (!empty($appInfo->factory_mouja) ? $appInfo->factory_mouja : ''), ['class' => 'form-control input-md']) !!}
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
                                        @if($business_category != 2)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('auth_full_name') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_full_name','Full Name ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_full_name', ((!empty($appInfo->auth_full_name)) ? $appInfo->auth_full_name : CommonFunction::getUserFullName()), ['class' => 'form-control required input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', ((!empty($appInfo->auth_designation)) ? $appInfo->auth_designation : Auth::user()->designation), ['class' => 'form-control required input-md', 'readonly']) !!}
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
                                                            {!! Form::text('auth_mobile_no', ((!empty($appInfo->auth_mobile_no)) ? $appInfo->auth_mobile_no : Auth::user()->user_phone), ['class' => 'form-control required input-md helpText15','id' => 'auth_mobile_no', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address ',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', ((!empty($appInfo->auth_email)) ? $appInfo->auth_email : \Illuminate\Support\Facades\Auth::user()->user_email), ['class' => 'form-control required-star input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('auth_letter') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_letter','Authorization Letter',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        @if($viewMode != 'on')
                                                            @if(!empty($appInfo->auth_letter))
                                                                <div class="save_file" style="margin-top: 5px">
                                                                    <a target="_blank" rel="noopener"
                                                                       class="btn btn-xs btn-primary show-in-view"
                                                                       title="Authorization Letter"
                                                                       href="{{URL::to('users/upload/'.$appInfo->auth_letter)}}"><i
                                                                                class="fa fa-file-pdf-o"></i>
                                                                        Authorization
                                                                        Letter</a>
                                                                    <input type="hidden"
                                                                           value="{{ $appInfo->auth_letter }}"
                                                                           id="auth_letter"
                                                                           name="auth_letter"/>
                                                                </div>
                                                            @else
                                                                {{--Old Authorization letter--}}
                                                                @if(!empty($auth_letter))
                                                                    <input type="hidden" name="old_auth_letter"
                                                                           value="{{ $auth_letter }}">
                                                                    <a href="{{ URL::to('users/upload/'.$auth_letter) }}"
                                                                       target="_blank" rel="noopener" 
                                                                       class="btn btn-xs btn-primary show-in-view"
                                                                       title="Provided authorization letter."
                                                                       style="margin-top: 5px;">
                                                                        <i class="fa  fa-file-pdf-o"></i>
                                                                        Provided authorization letter
                                                                    </a><br/>
                                                                @endif
                                                            @endif

                                                            @if($viewMode != 'on')
                                                                <br/>
                                                                <small>
                                                                    If you want to change the authorization letter, please select below.
                                                                </small>

                                                                <input type="file" onchange="checkPdfDocumentType(this.id, 3)" accept="application/pdf" name="auth_letter"
                                                                       id="auth_letter" class="form-control input-md"/>

                                                                <small style="font-size: 9px; font-weight: bold; color: #666363; font-style: italic">
                                                                    [Format: *.PDF | Maximum 3 MB, Application with Name & Signature] </small>
                                                                <br/>

                                                                <a target="_blank" rel="noopener" href="{{ url('assets/images/sample_auth_letter.png') }}"><i class="fa fa-file" aria-hidden="true"></i> <i>Sample Authorization letter</i></a>

                                                                {!! $errors->first('auth_letter','<span class="help-block">:message</span>') !!}
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($business_category != 2)
                                                    <div class="col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail" id="authImageViewer"
                                                                 src="{{ isset($appInfo->auth_image) != '' ? url('users/upload/'.$appInfo->auth_image) : url('users/upload/'.\Illuminate\Support\Facades\Auth::user()->user_pic) }}"
                                                                 alt="Auth Image">
                                                        </div>
                                                    </div>
                                                @endif
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
                                                <label style="margin-left: 1%">If you are submitting above
                                                    any false information through the system, you shall be
                                                    liable under ICT act of Government of
                                                    Bangladesh.</label>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>

                                                        {!! Form::checkbox('acceptTerms', 'yes', (!empty($appInfo->acceptTerms) ? $appInfo->acceptTerms : ''), array('class'=>'required col-md-1 col-xs-1 col-sm-1','id'=>'acceptTerms-2','style'=>'width:3%;')) !!}
                                                        <label for="acceptTerms-2"
                                                               class="text-left required-star text-danger">I
                                                            do here by declare that the information given
                                                            above is true to the best of my knowledge and I
                                                            shall be liable for any false information is
                                                            given. </label>
                                                        <div class="clearfix"></div>
                                                        {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer">
                                <div class="pull-right">
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md"
                                            value="Submit" name="actionBtn">Submit
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    {{--End application form with wizard--}}
                </div>
            </div>
        </section>

        <script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
        <script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" type="text/javascript"></script>
        <script type="text/javascript">

            function openDeptMoreInfoModal(btn) {
                var this_action = btn.getAttribute('data-action');
                console.log(this_action);
                if (this_action != '') {
                    $.get(this_action, function (data, success) {
                        console.log(data);
                        if (success === 'success') {
                            $('#deptMoreInfoModal .load_modal').html(data);
                        } else {
                            $('#deptMoreInfoModal .load_modal').html('Unknown Error!');
                        }
                        $('#deptMoreInfoModal').modal('show', {backdrop: 'static'});
                    });
                }
            }

            $(document).ready(function () {

                $("#ceo_telephone_no").intlTelInput({
                    hiddenInput: "ceo_telephone_no",
                    initialCountry: "BD",
                    placeholderNumberType: "MOBILE",
                    separateDialCode: true,
                });
                $("#ceo_mobile_no").intlTelInput({
                    hiddenInput: "ceo_mobile_no",
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

                $("#basicInformationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});

                //max text count down
                $('.maxTextCountDown').characterCounter();

                // Applicant type wise
                var applicant_type = '{{ $applicant_type }}';
                if (applicant_type == 'NUBS') { // New applicant
                    $("#service_type_label").text('Desired Service from BIDA');
                } else if (applicant_type == 'EUBS') { // Existing applicant
                    $("#service_type_label").text('Registered with BIDA as');
                }

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

                $("#service_type").change(function (e) {
                    var service_value = this.value;
                    if (service_value == 5) { // 5 = Registered Commercial Offices
                        $("#RegCommercialOfficesDiv").show();
                    } else {
                        $("#RegCommercialOfficesDiv").hide();
                    }

                    if (service_value == 1 || service_value == 2 || service_value == 3) {

                        $("#factory_district_label").addClass('required-star');
                        $("#factory_district_id").addClass('required');
                        $("#factory_thana_label").addClass('required-star');
                        $("#factory_thana_id").addClass('required');
                        $("#factory_post_label").addClass('required-star');
                        $("#factory_post_office").addClass('required');
                        $("#factory_post_code_label").addClass('required-star');
                        $("#factory_post_code").addClass('required');
                        $("#factory_address_label").addClass('required-star');
                        $("#factory_address").addClass('required');
                        $("#factory_mobile_label").addClass('required-star');
                        $("#factory_mobile_no").addClass('required');
                        $("#factory_email_label").addClass('required-star');
                        $("#factory_email").addClass('required');
                        // $("#factory_mouja_label").addClass('required-star');
                        // $("#factory_mouja").addClass('required');

                        $('#factory_info').show();

                    } else {

                        $("#factory_district_id").removeClass('required');
                        $("#factory_thana_id").removeClass('required');
                        $("#factory_post_office").removeClass('required');
                        $("#factory_post_code").removeClass('required');
                        $("#factory_address").removeClass('required');
                        $("#factory_mobile_no").removeClass('required');
                        $("#factory_email").removeClass('required');
                        $("#factory_mouja").removeClass('required');

                        $('#factory_info').hide();
                    }
                });
                $('#service_type').trigger('change');

                var today = new Date();
                var yyyy = today.getFullYear();

                $('.datepicker').datetimepicker({
                    viewMode: 'years',
                    format: 'DD-MMM-YYYY',
                    maxDate: 'now',
                    minDate: '01/01/1916'
                });

                $("#basicInformationForm").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });

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

                                    if (validate_class.search("required") != -1) {

                                        var closest_div = $("#" + value.filed_id).closest("div");
                                        (closest_div.hasClass('input-group') || closest_div.hasClass('intl-tel-input')) == false ?
                                            closest_div.prev().addClass('required-star') :
                                            closest_div.parent("div").prev().addClass('required-star');
                                    }
                                    $("#" + value.filed_id).addClass(value.validation_class);

                                    if (value.type == 'tooltip') {

                                        if ($("#" + value.filed_id).hasClass("date") || $("#" + value.filed_id).hasClass("helpText15")) {
                                            $("#" + value.filed_id).before('<i class="fa fa-question-circle" style="cursor: pointer; position: absolute; top: 10px; left: -15px;" data-toggle="tooltip" data-placement="top" title="' + value.help_text + '" ></i>');
                                        } else {
                                            $("#" + value.filed_id).before('<i class="fa fa-question-circle" style="cursor: pointer; position: absolute; top: 10px; left: -5px;" data-toggle="tooltip" data-placement="top" title="' + value.help_text + '" ></i>');
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

            });



            let business_category = "{{ $business_category }}";
            // Type of the organization
            if (business_category == 2) {
                $("#organization_type_id").change(function (e) {
                    var org_type_id = this.value;
                    if (org_type_id == '14') {
                        $("#organization_type_other_div").show();
                        $("#organization_type_other").addClass('required');
                    } else {
                        $("#organization_type_other_div").hide();
                        $("#organization_type_other").removeClass('required');
                    }
                });
            }
        </script>
    @endif
@endsection
