<?php
$accessMode = ACL::getAccsessRight('CompanyRegistration');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>


<style>
    .form-group {
        margin-bottom: 2px;
    }

    .img-thumbnail {
        height: 80px;
        width: 100px;
    }

    input[type=radio].error,
    input[type=checkbox].error {
        outline: 1px solid red !important;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .table-striped > tbody#manpower > tr > td, .table-striped > tbody#manpower > tr > th {
        text-align: center;
    }
</style>

<section class="content">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                {{--start application form with wizard--}}
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}

                @if($viewMode == 'on' && in_array(Auth::user()->user_type,['5x505']) && $appInfo->status_id == 9)
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h5><strong>Government Fee Payment</strong></h5>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => 'licence-applications/company-registration/payment','method' => 'post','id' => 'VRAPayment','enctype'=>'multipart/form-data',
                                 'files' => true, 'role'=>'form')) !!}

                            <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                            <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />


                            <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md pull-right"
                                    value="Submit" name="actionBtn">Payment Submit
                            </button>
                        {!! Form::close() !!}<!-- /.form end -->
                        </div>
                    </div>
                @endif
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                        <h5><strong> Application for Company Registration to Bangladesh </strong></h5>
                        </div>
                        <div class="pull-right">
                            @if(!in_array($appInfo->status_id,[-1,5,6]))
                                <a href="/licence-applications/company-registration/view-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                                   target="_blank"
                                   class="btn btn-danger btn-md">
                                    <i class="fa fa-download"></i> <strong> Application Download as PDF</strong>
                                </a>
                            @endif
                            @if (isset($appInfo->add_file_path) && $appInfo->add_file_path != "")
                                <a href="{{ url($appInfo->add_file_path) }}"
                                   class="btn btn-danger btn-md">
                                    <i class="fa fa-download"></i> <strong> Download Process File</strong>
                                </a>

                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="row" style="margin:15px 0 5px 0">
                        <div class="col-md-12">
                            <div class="heading_img">
                                <img class="img-responsive pull-left"
                                     src="{{ asset('assets/images/u34.png') }}"/>
                            </div>
                            <div class="heading_text pull-left">
                                Registrar of Joint Stock Companies And Firms  (RJSC)
                            </div>
                        </div>

                        @if ($viewMode == 'on' || (isset($appInfo->status_id) && $appInfo->status_id == 5))

                            <section class="content-header">
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                    <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }} </li>
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

                                    {{--@if(isset($appInfo->status_id) && $appInfo->status_id == 5)--}}
                                    {{--<li>--}}
                                    {{--<strong>Shortfall Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}--}}
                                    {{--</li>--}}
                                    {{--@endif--}}
                                    {{--@if(isset($appInfo->status_id) && $appInfo->status_id == 6)--}}
                                    {{--<li>--}}
                                    {{--<strong>Discard Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}--}}
                                    {{--</li>--}}
                                    {{--@endif--}}
                                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                                        <li>
                                            <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-xs btn-info"
                                               title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                        </li>
                                    @endif
                                </ol>
                            </section>
                        @endif
                        {{-- End of Breadcumb bar --}}
                        @if (isset($appInfo) && $appInfo->status_id > 1)
                            @if(($appInfo->status_id == 25 && Auth::user()->user_type == '5x505') || Auth::user()->user_type != '5x505')
                                <div class="box">
                                    <div class="box-body">

                                        <div class="panel panel-info" id="inputForm">

                                            <div class="panel-body" style="font-size: 14px;">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <b> Reference Number:</b>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{$appInfo->ref_no}}

                                                        </div>
                                                        <div class="col-md-2">
                                                            <b>Registration Number</b>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{$appInfo->reg_no}}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif


                    </div>

                    <div class="form-body panel-body">
                        {!! Form::open(array('url' => 'licence-application/company-registration/add','method' => 'post','id' => 'company_registration','role'=>'form','enctype'=>'multipart/form-data')) !!}
                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>
                        {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}

                        <div class="panel panel-info">
                            <div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>
                            <div class="panel-body readOnlyCl">
                                <div id="validationError"></div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                            {!! Form::label('company_name','Name of Organization/ Company/ Industrial Project',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('company_name', $appInfo->company_name, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                            {!! Form::label('company_name_bn','Name of Organization/ Company/ Industrial Project (বাংলা)',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('company_name_bn', $appInfo->company_name_bn, ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('country_of_origin_id','Country of Origin',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('country_of_origin_id',$countries, $appInfo->country_of_origin_id,['class'=>'form-control input-md required', 'id' => 'country_of_origin_id']) !!}
                                                {!! $errors->first('country_of_origin_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                            {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('organization_type_id', $eaOrganizationType, $appInfo->organization_type_id, ['class' => 'form-control required input-md ','id'=>'organization_type_id']) !!}
                                                {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('organization_status_id') ? 'has-error': ''}}">
                                            {!! Form::label('organization_status_id','Status of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('organization_status_id', $eaOrganizationStatus, $appInfo->organization_status_id, ['class' => 'form-control input-md required','id'=>'organization_status_id']) !!}
                                                {!! $errors->first('organization_status_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                                            {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('ownership_status_id', $eaOwnershipStatus, $appInfo->ownership_status_id, ['class' => 'form-control required input-md ','id'=>'ownership_status_id']) !!}
                                                {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('business_sector_id') ? 'has-error': ''}}">
                                            {!! Form::label('business_sector_id','Business sector',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_sector_id', $sectors, $appInfo->business_sector_id, ['class' => 'form-control input-md','id'=>'business_sector_id']) !!}
                                                {!! $errors->first('business_sector_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('business_sub_sector_id') ? 'has-error': ''}}">
                                            {!! Form::label('business_sub_sector_id','Sub sector',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('business_sub_sector_id', $sub_sectors, $appInfo->business_sub_sector_id, ['class' => 'form-control required input-md','id'=>'business_sub_sector_id']) !!}
                                                {!! $errors->first('business_sub_sector_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                                            {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                                            <div class="col-md-9 maxTextCountDown">
                                                {!! Form::textarea('major_activities', $appInfo->major_activities, ['class' => 'form-control input-md bigInputField', 'size' =>'5x2','data-rule-maxlength'=>'240', 'placeholder' => 'Maximum 240 characters','maxlength'=>'200']) !!}
                                                {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>B. Office Address</strong></div>
                            <div class="panel-body readOnlyCl">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                                            {!! Form::label('office_division_id','Division',['class'=>'text-left required-star col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('office_division_id', $divisions, $appInfo->office_division_id, ['class' => 'form-control required input-md', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id')"]) !!}
                                                {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                            {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('office_district_id', $districts, $appInfo->office_district_id, ['class' => 'form-control input-md required','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id')"]) !!}
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
                                                {!! Form::select('office_thana_id',$thana, $appInfo->office_thana_id, ['class' => 'form-control input-md required','placeholder' => 'Select district first']) !!}
                                                {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('office_post_office') ? 'has-error': ''}}">
                                            {!! Form::label('office_post_office','Post Office',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('office_post_office', $appInfo->office_post_office, ['class' => 'form-control input-md required']) !!}
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
                                                {!! Form::text('office_post_code', $appInfo->office_post_code, ['class' => 'form-control input-md ']) !!}
                                                {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('office_address') ? 'has-error': ''}}">
                                            {!! Form::label('office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('office_address', $appInfo->office_address, ['maxlength'=>'80','class' => 'form-control input-md required']) !!}
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
                                                {!! Form::text('office_telephone_no', $appInfo->office_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('office_mobile_no') ? 'has-error': ''}}">
                                            {!! Form::label('office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('office_mobile_no', $appInfo->office_mobile_no, ['class' => 'form-control input-md required phone_or_mobile' ,'id' => 'office_mobile_no']) !!}
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
                                                {!! Form::text('office_fax_no',  $appInfo->office_fax_no, ['class' => 'form-control input-md']) !!}
                                                {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('office_email') ? 'has-error': ''}}">
                                            {!! Form::label('office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('office_email', $appInfo->office_email, ['class' => 'form-control email input-md required']) !!}
                                                {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--2 new section add -->
                        <div class="panel panel-info">
                            <div class="panel-heading margin-for-preview"><strong>C. General Information (as of Memorandum and Articles of Association, Form-VI)</strong></div>
                            <div class="panel-body ">
                                <div id="validationError"></div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('business_objective') ? 'has-error' : ''}}">
                                            {!! Form::label('business_objective', 'Main Business Objective', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('business_objective', $appInfo->business_objective, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('business_objective', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('min_no_director') ? 'has-error' : ''}}">
                                            {!! Form::label('min_no_director', 'Minimum No. of Directors', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('min_no_director', $appInfo->min_no_director , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('min_no_director', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('authorized_capital') ? 'has-error' : ''}}">
                                            {!! Form::label('authorized_capital', 'Authorized Capital (BDT)', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('authorized_capital', $appInfo->authorized_capital , ['class' => 'onlyNumber form-control input-md required']) !!}
                                                {!! $errors->first('authorized_capital', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('max_no_director') ? 'has-error' : ''}}">
                                            {!! Form::label('max_no_director', 'Maximum No. of Directors', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('max_no_director', $appInfo->max_no_director , ['class' => 'form-control input-md required onlyNumber']) !!}
                                                {!! $errors->first('max_no_director', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('number_of_shares') ? 'has-error' : ''}}">
                                            {!! Form::label('number_of_shares', 'Number of Shares', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('number_of_shares', $appInfo->number_of_shares , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('number_of_shares', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 {{ $errors->has('quorum_agm_egm') ? 'has-error' : ''}}">
                                            {!! Form::label('quorum_agm_egm', 'Quorum of AGM/EGM', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('quorum_agm_egm', $appInfo->quorum_agm_egm , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('quorum_agm_egm', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('quorum_bod_meeting') ? 'has-error' : ''}}">
                                            {!! Form::label('quorum_bod_meeting', 'Quorum of Board of Director\'s Meeting', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('quorum_bod_meeting', $appInfo->quorum_bod_meeting , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('quorum_bod_meeting', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('duration_chairman') ? 'has-error' : ''}}">
                                            {!! Form::label('duration_chairman', 'Duration for Chairmanship (year)', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('duration_chairman', $appInfo->duration_chairman , ['class' => 'form-control input-md required onlyNumber']) !!}
                                                {!! $errors->first('duration_chairman', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('duration_md') ? 'has-error' : ''}}">
                                            {!! Form::label('duration_md', 'Duration for Managing Directorship (year)', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('duration_md', $appInfo->duration_md , ['class' => 'form-control input-md required onlyNumber']) !!}
                                                {!! $errors->first('duration_md', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('value_each_share') ? 'has-error' : ''}}">
                                            {!! Form::label('value_each_share', 'Value of each Share (BDT)', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('value_each_share', $appInfo->value_each_share , ['class' => 'onlyNumber form-control input-md required']) !!}
                                                {!! $errors->first('value_each_share', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading "><strong>D. Qualification Shares of Each
                                    Director</strong></div>
                            <div class="panel-body ">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('q_shares_number') ? 'has-error' : ''}}">
                                            {!! Form::label('q_shares_number', '1. Number', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('q_shares_number', $appInfo->q_shares_number , ['class' => 'onlyNumber form-control input-md required']) !!}
                                                {!! $errors->first('q_shares_number', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('q_shares_value') ? 'has-error' : ''}}">
                                            {!! Form::label('q_shares_value', '2. Value', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('q_shares_value', $appInfo->q_shares_value , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('q_shares_value', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{ $errors->has('q_shares_witness_agreement') ? 'has-error' : ''}}">
                                            {!! Form::label('q_shares_witness_agreement', '3. Witness to the agreement of taking qualification Shares', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('q_shares_witness_agreement', $appInfo->q_shares_witness_agreement , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('q_shares_witness_agreement', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-offset-2 col-md-9 {{ $errors->has('q_shares_witness_name') ? 'has-error' : ''}}">
                                            {!! Form::label('q_shares_witness_name', 'a. Name of Witness', ['class' => 'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-5">
                                                {!! Form::text('q_shares_witness_name', $appInfo->q_shares_witness_name , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('q_shares_witness_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-offset-2 col-md-9 {{ $errors->has('q_shares_witness_address') ? 'has-error' : ''}}">
                                            {!! Form::label('q_shares_witness_address', 'b. Address of Witness', ['class' => 'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-5">
                                                {!! Form::text('q_shares_witness_address', $appInfo->q_shares_witness_address , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('q_shares_witness_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading "><strong>E. Particulars of Body Corporate Subscribers
                                    (if any, as of Memorandum and Articles of Association)</strong></div>
                            <div class="panel-body ">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="corpSubscriberTbl"
                                                       class="table table-striped table-bordered dt-responsive"
                                                       width="100%" cellspacing="0">
                                                    <thead class="">
                                                    <tr>
                                                        {{--<th class="text-center col-md-1">SL. No--}}
                                                        </th>
                                                        <th class="text-center col-md-2">Name (of the
                                                            corporate body)
                                                            <span class="required-star"></span>
                                                        </th>
                                                        <th class="text-center col-md-2">Represented By
                                                            (name of the
                                                            representative)
                                                            <span class="required-star"></span>
                                                        </th>

                                                        <th class="text-center col-md-2">Number of
                                                            Subscribed Shares
                                                            <span class="required-star"></span>
                                                        </th>
                                                        <th class="text-center  col-md-2" colspan="2">District
                                                            <span class="required-star"></span>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(count($crCorporateSubscriber)>0)
                                                        <?php $inc = 0; ?>

                                                        @foreach($crCorporateSubscriber as $corporateSubscriber)
                                                            <tr id="rowCountcorpSubscriberTbl{{$inc}}">
                                                                {{--<td style="text-align: center;">1</td>--}}
                                                                <td>
                                                                    <input type="text" id="cs_name_{{$inc}}"
                                                                           class="form-control required"
                                                                           name="cs_name[{{$inc}}]"
                                                                           value="{{$corporateSubscriber->cs_name}}">
                                                                    {!! $errors->first('cs_name','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="cs_represented_by_{{$inc}}"
                                                                           class="form-control required"
                                                                           name="cs_represented_by[{{$inc}}]"
                                                                           value="{{$corporateSubscriber->cs_represented_by}}">
                                                                </td>

                                                                <td>
                                                                    <input type="text" class="form-control required"
                                                                           name="cs_subscribed_share_no[{{$inc}}]"
                                                                           id="cs_subscribed_share_no{{$inc}}"
                                                                           value="{{$corporateSubscriber->cs_subscribed_share_no}}">
                                                                </td>
                                                                <td>
                                                                    {!! Form::select("cs_district[$inc]", $districts, $corporateSubscriber->cs_district, ['class' => 'form-control required ','id'=>"cs_district_$inc"]) !!}
                                                                </td>
                                                                <td>
                                                                    @if ($viewMode == 'off')
                                                                        <?php if ($inc == 0) { ?>
                                                                        <a href="javascript:void(0);"
                                                                           class="btn btn-xs btn-primary addTableRows"
                                                                           onclick="addTableRow1('corpSubscriberTbl', 'rowCountcorpSubscriberTbl0');"><i
                                                                                    class="fa fa-plus"></i></a>
                                                                        <?php } else { ?>
                                                                        <a href="javascript:void(0);"
                                                                           class="btn btn-xs btn-danger removeRow"
                                                                           onclick="removeTableRow('corpSubscriberTbl','rowCountcorpSubscriberTbl{{$inc}}');">
                                                                            <i class="fa fa-times"
                                                                               aria-hidden="true"></i></a>
                                                                        <?php } ?>
                                                                    @endif
                                                                </td>
                                                                <?php $inc++; ?>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr id="rowCountcorpSubscriberTbl0">
                                                            {{--<td style="text-align: center;">1</td>--}}
                                                            <td>
                                                                <input type="text" class="form-control required"
                                                                       name="cs_name[0]" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control required"
                                                                       name="cs_represented_by[0]" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control required"
                                                                       name="cs_license_app[0]" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control required"
                                                                       name="cs_subscribed_share_no[0]"
                                                                       value="">
                                                            </td>
                                                            <td>
                                                                {!! Form::select('cs_district[0]', $districts, '', ['class' => 'form-control required ','id'=>'cs_district_0']) !!}
                                                            </td>
                                                            <td>
                                                                @if ($viewMode == 'off')
                                                                    <a class="btn btn-xs btn-primary addTableRows"
                                                                       onclick="addTableRow1('corpSubscriberTbl', 'rowCountcorpSubscriberTbl0');"><i
                                                                                class="fa fa-plus"></i></a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading "><strong>F. List of
                                    Subscribers/Directors/Managers/Managing Agents</strong></div>
                            <div class="panel-body ">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="witnessTbl"
                                                       class="table table-striped table-bordered dt-responsive"
                                                       width="100%" cellspacing="0">
                                                    <thead class="">
                                                    <tr>
                                                        {{--<th class="text-center col-md-1">SL. No</th>--}}
                                                        <th class="text-center col-md-4">Name <span
                                                                    class="required-star"></span></th>
                                                        <th class="text-center col-md-4">Position <span
                                                                    class="required-star"></span></th>
                                                        <th class="text-center col-md-3" colspan="2">Number of
                                                            Subscribed Shares <span
                                                                    class="required-star"></span></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(count($subscribersAgentList)>0)
                                                        <?php $inc = 0; ?>

                                                        @foreach($subscribersAgentList as $subscribersAgent)
                                                            <tr id="rowCountwitnessTb{{$inc}}">
                                                                {{--<td style="text-align: center;">1</td>--}}
                                                                <td>
                                                                    <input type="text" class="form-control required"
                                                                           name="lsa_name[0]"
                                                                           id="cs_represented_by_{{$inc}}"
                                                                           value="{{$subscribersAgent->lsa_name}}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control required"
                                                                           name="lsa_position[0]"
                                                                           id="cs_represented_by_{{$inc}}"
                                                                           value="{{$subscribersAgent->lsa_position}}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control required"
                                                                           name="lsa_no_subs_share[0]"
                                                                           id="cs_represented_by_{{$inc}}"
                                                                           value="{{$subscribersAgent->lsa_no_subs_share}}">
                                                                </td>

                                                                <td>
                                                                    @if ($viewMode == 'off')
                                                                        <?php if ($inc == 0) { ?>
                                                                        <a class="btn btn-xs btn-primary addTableRows"
                                                                           onclick="addTableRow1('witnessTbl', 'rowCountwitnessTb0');"><i
                                                                                    class="fa fa-plus"></i></a>
                                                                        <?php } else { ?>
                                                                        <a href="javascript:void(0);"
                                                                           class="btn btn-xs btn-danger removeRow"
                                                                           onclick="removeTableRow('witnessTbl','rowCountwitnessTb{{$inc}}');">
                                                                            <i class="fa fa-times"
                                                                               aria-hidden="true"></i></a>
                                                                        <?php } ?>
                                                                    @endif

                                                                </td>
                                                                <?php $inc++; ?>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr id="rowCountwitnessTb0">
                                                            {{--<td style="text-align: center;">1</td>--}}
                                                            <td>
                                                                <input type="text" class="form-control required"
                                                                       id="lsa_name_0"
                                                                       name="lsa_name[0]" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control required"
                                                                       id="lsa_position_0"
                                                                       name="lsa_position[0]" value="">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control required"
                                                                       id="lsa_no_subs_share_0"
                                                                       name="lsa_no_subs_share[0]" value="">
                                                            </td>
                                                            <td>
                                                                @if ($viewMode == 'off')
                                                                    <a class="btn btn-xs btn-primary addTableRows"
                                                                       onclick="addTableRow1('witnessTbl', 'rowCountwitnessTb0');"><i
                                                                                class="fa fa-plus"></i></a>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endif

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading "><strong>G. Witnesses</strong></div>
                            <div class="panel-body ">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('witnesses_name') ? 'has-error' : ''}}">
                                            {!! Form::label('witnesses_name', '1. Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('witnesses_name', $appInfo->witnesses_name , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('witnesses_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('witnesses_address') ? 'has-error' : ''}}">
                                            {!! Form::label('witnesses_address', '2. Address', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('witnesses_address', $appInfo->witnesses_address , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('witnesses_address', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('witnesses_phone') ? 'has-error' : ''}}">
                                            {!! Form::label('witnesses_phone', '3. Phone', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('witnesses_phone', $appInfo->witnesses_phone , ['class' => 'onlyNumber form-control input-md required']) !!}
                                                {!! $errors->first('witnesses_phone', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('witnesses_national_id') ? 'has-error' : ''}}">
                                            {!! Form::label('witnesses_national_id', '4. National ID', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('witnesses_national_id', $appInfo->witnesses_national_id , ['class' => 'onlyNumber form-control input-md required']) !!}
                                                {!! $errors->first('witnesses_national_id', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading "><strong>H. Declaration on Registration of the Company Signed
                                    By (as of Form-I)</strong></div>
                            <div class="panel-body ">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_country') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_country', 'Country', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('declaration_signed_country', $countries, $appInfo->declaration_signed_country , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_country', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_designation') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_designation', 'Designation', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('declaration_signed_designation', $appInfo->declaration_signed_designation , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_designation', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_district') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_district', 'Declaration Signed District', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('declaration_signed_district', $districts, $appInfo->declaration_signed_district, ['class' => 'form-control required  input-md','id'=>'declaration_signed_district']) !!}
                                                {!! $errors->first('declaration_signed_district', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_full_name') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_full_name', 'Full Name', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('declaration_signed_full_name', $appInfo->declaration_signed_full_name , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_full_name', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_zip_code') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_zip_code', 'Post/Zip Code', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::number('declaration_signed_zip_code', $appInfo->declaration_signed_zip_code , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_zip_code', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_town') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_town', 'Police Station/Town', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('declaration_signed_town', $thana, $appInfo->declaration_signed_town, ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_town', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_telephone') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_telephone', 'Telephone No', ['class' => 'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('declaration_signed_telephone', $appInfo->declaration_signed_telephone , ['class' => 'form-control input-md']) !!}
                                                {!! $errors->first('declaration_signed_telephone', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_house') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_house', 'House,Flat/Apartment,Road', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('declaration_signed_house', $appInfo->declaration_signed_house , ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_house', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_fax') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_fax', 'Fax No', ['class' => 'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('declaration_signed_fax', $appInfo->declaration_signed_fax , ['class' => 'form-control input-md']) !!}
                                                {!! $errors->first('declaration_signed_fax', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_mobile') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_mobile', 'Mobile No', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('declaration_signed_mobile', $appInfo->declaration_signed_mobile , ['class' => 'onlyNumber form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_mobile', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_momorandum') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_momorandum', 'Upload Momorandum of Association', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if($viewMode == 'off')
                                                <input name="declaration_signed_momorandum_file" class="input-md {{!empty($appInfo->declaration_signed_momorandum)?'':' required'}}"
                                                       id="declaration_signed_momorandum_file" type="file"
                                                       onchange="uploadDocument('declaration_signed_momorandum_preview', this.id, 'declaration_signed_momorandum', '1')"/>
                                                <div id="declaration_signed_momorandum_preview" class="uploadbox">
                                                    <input type="hidden" class="travelFileRequired"
                                                           id="th_first_work_permit"
                                                           name="declaration_signed_momorandum" value="{{$appInfo->declaration_signed_momorandum}}"/>
                                                </div>
                                                @endif
                                                @if(!empty($appInfo->declaration_signed_momorandum))
                                                    <div class="save_file pdl-10">
                                                        <a target="_blank" class="documentUrl show-in-view" title=""
                                                           href="{{URL::to('/uploads/'. $appInfo->declaration_signed_momorandum)}}">
                                                            <i
                                                                    class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            Open File
                                                        </a>
                                                    </div>
                                                @endif
                                                {!! $errors->first('declaration_signed_momorandum', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_email') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_email', 'Email', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('declaration_signed_email', $appInfo->declaration_signed_email , ['class' => 'email form-control input-md required']) !!}
                                                {!! $errors->first('declaration_signed_email', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('declaration_signed_article') ? 'has-error' : ''}}">
                                            {!! Form::label('declaration_signed_article', 'Upload Article', ['class' => 'col-md-5 text-left required-star']) !!}
                                            <div class="col-md-7">
                                                @if($viewMode == 'off')
                                                <input name="declaration_signed_article_file" class="input-md {{!empty($appInfo->declaration_signed_article)?'':' required'}}"
                                                       id="declaration_signed_article_file" type="file"
                                                       onchange="uploadDocument('declaration_signed_article_preview', this.id, 'declaration_signed_article', '1')"/>
                                                <div id="declaration_signed_article_preview" class="uploadbox">
                                                    <input type="hidden" class="travelFileRequired"
                                                           id="declaration_signed_article"
                                                           name="declaration_signed_article" value="{{$appInfo->declaration_signed_article}}"/>
                                                </div>
                                                @endif
                                                @if(!empty($appInfo->declaration_signed_article))
                                                    <div class="save_file pdl-10">
                                                        <a target="_blank" class="documentUrl show-in-view" title=""
                                                           href="{{URL::to('/uploads/'. $appInfo->declaration_signed_article)}}">
                                                            <i
                                                                    class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            Open File
                                                        </a>
                                                    </div>
                                                @endif
                                                {!! $errors->first('declaration_signed_article', '<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(ACL::getAccsessRight('CompanyRegistration','-E-') && $viewMode == "off")
                            @if($appInfo->status_id != 5)
                                <button type="submit" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                                <div class="pull-right">
                                    <button type="submit" id="" style="cursor: pointer;"
                                            class="btn btn-info btn-md submit"
                                            value="Submit" name="actionBtn">Submit
                                    </button>
                                </div>
                            @endif
                            @if($appInfo->status_id == 5)
                                <div class="pull-left">
                                    <button type="submit" id="" style="cursor: pointer;"
                                            class="btn btn-info btn-md submit"
                                            value="Submit" name="actionBtn">Re-submit
                                    </button>
                                </div>
                            @endif
                        @endif
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

<script type="text/javascript">
    //--------Step Form init+validation End----------//
    var popupWindow = null;
    $('.finish').on('click', function (e) {
        if (form.valid()) {
            $('body').css({"display": "none"});
            popupWindow = window.open('<?php echo URL::to('/licence-application/preview'); ?>', 'Sample', '');
        } else {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        }
    });

    /********Calculating the numbers of two fields inside multiple rowed tables******/
    function CalculatePercent(id) {
        var oneValue = parseFloat($("#" + id).val());

        if (oneValue > 100) {
            alert("Total percentage can't be more than 100");
        }
        var anotherVal = 100 - oneValue;
        if (id == 'local_sales_per') {
            $("#foreign_sales_per").val(anotherVal);
        } else {
            $("#local_sales_per").val(anotherVal);
        }

    }

    //--------File Upload Script Start----------//


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
            var action = "{{url('/licence-applications/company-registration/upload-document')}}";

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
                        +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    var validate_field = $('#'+vField).val();
                    if(validate_field ==''){
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    //--------File Upload Script End----------//


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
        $("#organization_type_id option:not(:selected)").prop('disabled', true);
        $(".readOnlyCl option:not(:selected)").prop('disabled', true);
        $("#office_thana_id option:not(:selected)").prop('disabled', true);
        $("#office_district_id option:not(:selected)").prop('disabled', true);
        $("#office_division_id option:not(:selected)").prop('disabled', true);
        $("#country_of_origin_id option:not(:selected)").prop('disabled', true);
        $("#organization_status_id option:not(:selected)").prop('disabled', true);
        $("#ownership_status_id option:not(:selected)").prop('disabled', true);
        $("#business_sector_id option:not(:selected)").prop('disabled', true);
        $("#business_sub_sector_id option:not(:selected)").prop('disabled', true);
        $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea,.readOnly').attr('readonly',true);

        $(".submit").click(function (e) {
            $('#company_registration').validate();
        });

        $(".other_utility").click(function () {
            $('.other_utility_txt').hide();
            var ischecked = $(this).is(':checked');
            console.log(ischecked);
            if (ischecked == true) {
                $('.other_utility_txt').show();
            }
        });

        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker_registration_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 50),
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

    // Add table Row script
    function addTableRow1(tableID, templateRow) {
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
            var nameAtt1 = attrInput[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repText1 = nameAtt.replace('[0]', '_' + rowCo);
            attrInput[i].name = repText;
            attrInput[i].id = repText1;
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
            .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
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
            extraFormats: ['DD.MM.YY', 'DD.MM.YYYY'],
            maxDate: 'now',
            minDate: '01/01/1905'
        });
    } // end of addTableRow() functionDistrict

    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
    }


    function calculateAnnulCapacity(event) {
        var id = event.split(/[_ ]+/).pop();
        var no1 = $('#apc_quantity_' + id).val() ? parseFloat($('#apc_quantity_' + id).val()) : 0;
        var no2 = $('#apc_price_usd_' + id).val() ? parseFloat($('#apc_price_usd_' + id).val()) : 0;
        var bdtValue = $('#crvalue').val() ? parseFloat($('#crvalue').val()) : 0;
        var usdToBdt = bdtValue * no2;
        var total = (no1 * usdToBdt) / 1000000;

        $('#apc_value_taka_' + id).val(total);
    }

    function calculateSourceOfFinance(event) {
        var no1 = $('#finance_src_loc_equity_1').val() ? parseFloat($('#finance_src_loc_equity_1').val()) : 0;
        var no2 = $('#finance_src_foreign_equity_1').val() ? parseFloat($('#finance_src_foreign_equity_1').val()) : 0;
        var total = (no1 + no2);

        $('#finance_src_loc_total_equity_1').val(total);
        $('#finance_src_loc_equity_2').val(no1 * total / 100);
        $('#finance_src_foreign_equity_2').val(no2 * total / 100);

        var no3 = $('#finance_src_loc_loan_1').val() ? parseFloat($('#finance_src_loc_loan_1').val()) : 0;
        var no4 = $('#finance_src_foreign_loan_1').val() ? parseFloat($('#finance_src_foreign_loan_1').val()) : 0;

        var total2 = (no3 + no4);
        $('#finance_src_total_loan').val(total2);
        $('#finance_src_loc_total_financing_1').val(no1 + no2 + no3 + no4);


    }


    function Calculate44Numbers(arg1, arg2, place) {

        var no1 = $('#' + arg1).val() ? parseFloat($('#' + arg1).val()) : 0;
        var no2 = $('#' + arg2).val() ? parseFloat($('#' + arg2).val()) : 0;

        var total = new SumArguments(no1, no2);
        $('#' + place).val(total.sum());

        var inputs = $(".totalTakaOrM");
        var total1 = 0;

        // $(inputs).each(function( value ) {
        //    console.log(value)
        // });
        var total7 = 0;
        for (var i = 0; i < inputs.length; i++) {
            if ($(inputs[i]).val() !== '')
                total7 += parseFloat($(inputs[i]).val());

        }
        $("#total_ivst").val(total7);


    }

    function SumArguments() {
        var _arguments = arguments;
        this.sum = function () {
            var i = _arguments.length;
            var result = 0;
            while (i--) {
                result += _arguments[i];
            }
            return result;
        };
    }


    $(document).ready(function () {
        $('.onlyNumber').on('keydown', function (e) {
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
        });
                @if ($viewMode != 'on')
        var form = $("#company_registration").show();
        form.find('#save_as_draft').css('display', 'none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');

                @endif

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/visa-recommendation-amendment/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        $('input[name=is_approval_online]:checked').trigger('click');

    });

    @if ($viewMode == 'on')
    $('#company_registration .stepHeader').hide();
    $('#company_registration :input').attr('disabled', true);
    $('#company_registration').find('.MoreInfo').attr('disabled', false);
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
//console.log($(this)[0]['localName']);
        if ($(this)[0]['localName'] == 'select') {
//var text = $(this).find('option:selected').text();
//var val = jQuery(this).val();
//$(this).find('option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");

// This style will not work in mozila firefox, it's bug in firefox, maybe they will update it in next version
            $(this).attr('style', '-webkit-appearance: button; -moz-appearance: button; -webkit-user-select: none; -moz-user-select: none; text-overflow: ellipsis; white-space: pre-wrap; height: auto;');
        } else {
            $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">' + this.value + '</span>');
        }
    });
    $('#company_registration :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on
</script>