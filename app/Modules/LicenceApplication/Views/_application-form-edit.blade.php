<?php
$accessMode = ACL::getAccsessRight('LicenceApplication');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>


<style>
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

<section class="content" id="applicationForm">
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
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>  Apply Industrial Project Registration to Bangladesh </strong></h5>
                        </div>
                        <div class="pull-right">
                            @if(!in_array($appInfo->status_id,[-1,5,6]))
                                <a href="/licence-application/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank"
                                   class="btn btn-danger btn-md">
                                    <i class="fa fa-download"></i> <strong> Application Download as PDF</strong>
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-body">
                        {{-- Breadcumb bar --}}
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

                        <div>
                            {!! Form::open(array('url' => '/licence-application/add','method' => 'post','id' => 'LicenceApplicationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}

                            {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
                            {!! Form::hidden('curr_process_status_id', $appInfo->status_id,['class' => 'form-control input-md required', 'id'=>'process_status_id']) !!}

                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>

                            <h3 class="text-center stepHeader"> Application Information</h3>
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>
                                    <div class="panel-body">
                                        <div id="validationError"></div>
                                        @if(!in_array($appInfo->status_id, [0,-1,1,2,5,6]) && $appInfo->department_id != '')
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('department_id') ? 'has-error': ''}}">
                                                        {!! Form::label('department_id','Department',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('department_id', $departmentList, $appInfo->department_id, ['class' => 'form-control required input-md ','id'=>'department_id']) !!}
                                                            {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

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
                                                    {!! Form::label('company_name_bn','Name of Organization/ Company/ Industrial Project (বাংলা)',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('company_name_bn', $appInfo->company_name_bn, ['class' => 'form-control input-md required', 'readonly']) !!}
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
                                                        {!! Form::select('organization_status_id', $eaOrganizationStatus, $appInfo->organization_status_id, ['class' => 'form-control required input-md ','id'=>'organization_status_id']) !!}
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
                                                    {!! Form::label('business_sector_id','Business sector',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('business_sector_id', $sectors, $appInfo->business_sector_id, ['class' => 'form-control  input-md','id'=>'business_sector_id']) !!}
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
                                                    <div class="col-md-9">
                                                        {!! Form::textarea('major_activities', $appInfo->major_activities, ['class' => 'form-control input-md bigInputField', 'size' =>'5x2','data-rule-maxlength'=>'240', 'placeholder' => 'Maximum 240 characters']) !!}
                                                        {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>B. Information of Principal Promoter/Chairman/Managing Director/CEO/Country Manager</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_country_id', $countries, $appInfo->ceo_country_id, ['class' => 'form-control required input-md ','id'=>'ceo_country_id']) !!}
                                                        {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5']) !!}
                                                    <div class=" col-md-7">
                                                        <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">
                                                            {!! Form::text('ceo_dob', ($appInfo->ceo_dob == '0000-00-00' ? '' : date('d-M-Y', strtotime($appInfo->ceo_dob))), ['class'=>'form-control input-md', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker']) !!}
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
                                                    {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_passport_no', $appInfo->ceo_passport_no, ['maxlength'=>'20',
                                                        'class' => 'form-control input-md required passport', 'id'=>'ceo_passport_no']) !!}
                                                        {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_nid_div" class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_nid', $appInfo->ceo_nid, ['maxlength'=>'20',
                                                        'class' => 'form-control input-md required bd_nid','id'=>'ceo_nid']) !!}
                                                        {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_designation', $appInfo->ceo_designation,
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
                                                        {!! Form::text('ceo_full_name', $appInfo->ceo_full_name, ['maxlength'=>'80',
                                                        'class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_district_div" class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_district_id','District/City/State ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_district_id',$districts, $appInfo->ceo_district_id, ['maxlength'=>'80','class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_city_div" class="col-md-6 hidden {{$errors->has('ceo_city') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_city','City',['class'=>'text-left  col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_city', $appInfo->ceo_city,['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div id="ceo_state_div" class="col-md-6 hidden {{$errors->has('ceo_state') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_state','State / Province',['class'=>'text-left  col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_state', $appInfo->ceo_state,['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_thana_div" class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_thana_id','Police Station/Town ',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_thana_id',[], $appInfo->ceo_thana_id, ['maxlength'=>'80','class' => 'form-control input-md','placeholder' => 'Select district first']) !!}
                                                        {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_post_code','Post/Zip Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_post_code', $appInfo->ceo_post_code, ['maxlength'=>'80','class' => 'form-control input-md required engOnly']) !!}
                                                        {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_address','House,Flat/Apartment,Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_address', $appInfo->ceo_address, ['maxlength'=>'80','class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_telephone_no', $appInfo->ceo_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
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
                                                        {!! Form::text('ceo_mobile_no', $appInfo->ceo_mobile_no, ['class' => 'form-control input-md required phone_or_mobile']) !!}
                                                        {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_father_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_father_name', $appInfo->ceo_father_name, ['class' => 'form-control textOnly input-md required']) !!}
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
                                                        {!! Form::text('ceo_email', $appInfo->ceo_email, ['class' => 'form-control email input-md required']) !!}
                                                        {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_mother_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_mother_name', $appInfo->ceo_mother_name, ['class' => 'form-control textOnly required input-md']) !!}
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
                                                        {!! Form::text('ceo_fax_no', $appInfo->ceo_fax_no, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_spouse_name', $appInfo->ceo_spouse_name, ['class' => 'form-control textOnly input-md']) !!}
                                                        {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h3 class="text-center stepHeader">Organization information</h3>
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>D. Office Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_division_id','Division',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_division_id', $divisions, $appInfo->office_division_id, ['class' => 'form-control imput-md required','id' => 'office_division_id']) !!}
                                                        {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_district_id', $districts, $appInfo->office_district_id, ['class' => 'form-control input-md required']) !!}
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
                                                        {!! Form::select('office_thana_id',[''], $appInfo->office_thana_id, ['class' => 'form-control input-md required']) !!}
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
                                                        {!! Form::text('office_post_code', $appInfo->office_post_code, ['class' => 'form-control input-md required post_code_bd']) !!}
                                                        {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('office_address','House,Flat/Apartment,Road ',['class'=>'col-md-5 text-left required-star']) !!}
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
                                                        {!! Form::text('office_mobile_no', $appInfo->office_mobile_no, ['class' => 'form-control input-md required phone_or_mobile']) !!}
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
                                                        {!! Form::text('office_fax_no', $appInfo->office_fax_no, ['class' => 'form-control input-md']) !!}
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


                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>E. Factory Address (Optional)</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('factory_district_id', $districts, $appInfo->factory_district_id, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('factory_thana_id',[''], $appInfo->factory_thana_id, ['class' => 'form-control input-md']) !!}
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
                                                        {!! Form::text('factory_post_office', $appInfo->factory_post_office, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_post_code', $appInfo->factory_post_code, ['class' => 'form-control input-md number']) !!}
                                                        {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_address') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_address','House,Flat/Apartment,Road ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_address', $appInfo->factory_address, ['maxlength'=>'80','class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_telephone_no', $appInfo->factory_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
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
                                                        {!! Form::text('factory_mobile_no', $appInfo->factory_mobile_no, ['class' => 'form-control input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_fax_no', $appInfo->factory_fax_no, ['class' => 'form-control input-md']) !!}
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
                                                        {!! Form::text('factory_email', $appInfo->factory_email, ['class' => 'form-control email input-md']) !!}
                                                        {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_mouja', $appInfo->factory_mouja, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead class="alert alert-info">
                                                            <tr>
                                                                <th class="text-center text-title" colspan="9">Manpower of the organization</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="manpower">
                                                            <tr>
                                                                <th class="alert alert-info" colspan="3">Local (Bangladesh only)</th>
                                                                <th class="alert alert-info" colspan="3">Foreign (Abroad country)</th>
                                                                <th class="alert alert-info" colspan="1">Grand total</th>
                                                                <th class="alert alert-info" colspan="2">Ratio</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="alert alert-info">Executive</th>
                                                                <th class="alert alert-info">Supporting staff</th>
                                                                <th class="alert alert-info">Total (a)</th>
                                                                <th class="alert alert-info">Executive</th>
                                                                <th class="alert alert-info">Supporting staff</th>
                                                                <th class="alert alert-info">Total (b)</th>
                                                                <th class="alert alert-info"> (a+b)</th>
                                                                <th class="alert alert-info">Local</th>
                                                                <th class="alert alert-info">Foreign</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    {!! Form::text('local_executive', $appInfo->local_executive, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_executive']) !!}
                                                                    {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('local_stuff', $appInfo->local_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_stuff']) !!}
                                                                    {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('local_total', $appInfo->local_total, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_total','readonly']) !!}
                                                                    {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_executive', $appInfo->foreign_executive, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_executive']) !!}
                                                                    {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_stuff', $appInfo->foreign_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_stuff']) !!}
                                                                    {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_total', $appInfo->foreign_total, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_total','readonly']) !!}
                                                                    {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('manpower_total', $appInfo->manpower_total, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_total','readonly']) !!}
                                                                    {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('manpower_local_ratio', $appInfo->manpower_local_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_local','readonly']) !!}
                                                                    {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('manpower_foreign_ratio', $appInfo->manpower_foreign_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_foreign','readonly']) !!}
                                                                    {!! $errors->first('manpower_foreign_ratio','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h3 class="text-center stepHeader">Attachments</h3>
                            <fieldset>
                                <div id="docListDiv">
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>F. Necessary documents to be attached here (Only PDF file to be attach here)</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover ">
                                                    <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th colspan="6">Required attachments</th>
                                                        <th colspan="2">Attached PDF file (Each File Maximum size 2MB)
                                                            {{--<span>--}}
                                                            {{--<i title="Attached PDF file (Each File Maximum size 2MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>--}}
                                                            {{--</span>--}}
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i = 1; ?>
                                                    @foreach($document as $row)
                                                        <tr>
                                                            <td>
                                                                <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                            </td>
                                                            <td colspan="6">{!!  $row->doc_name !!}</td>
                                                            <td colspan="2">
                                                                <input name="document_id_<?php echo $row->id; ?>" type="hidden" value="{{(!empty($clrDocuments[$row->id]['document_id']) ? $clrDocuments[$row->id]['document_id'] : '')}}">
                                                                <input type="hidden" value="{!!  $row->doc_name !!}" id="doc_name_<?php echo $row->id; ?>" name="doc_name_<?php echo $row->id; ?>"/>
                                                                <input name="file<?php echo $row->id; ?>"
                                                                       <?php if (empty($clrDocuments[$row->id]['file']) && empty($allRequestVal["file$row->id"]) && $row->doc_priority == "1") {
                                                                           echo "class='required'";
                                                                       } ?>
                                                                       id="file<?php echo $row->id; ?>" type="file" size="20" onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '<?php echo $row->doc_priority; ?>')"/>

                                                                @if($row->additional_field == 1)
                                                                    <table>
                                                                        <tr>
                                                                            <td>Other file Name :</td>
                                                                            <td><input maxlength="64" class="form-control input-md <?php if ($row->doc_priority == "1") {
                                                                                    echo 'required';
                                                                                } ?>" name="other_doc_name_<?php echo $row->id; ?>"
                                                                                       type="text" value="{{(!empty($clrDocuments[$row->id]['doc_name']) ? $clrDocuments[$row->id]['doc_name'] : '')}}">
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                @endif

                                                                @if(!empty($clrDocuments[$row->id]['file']))
                                                                    <div class="save_file saved_file_{{$row->id}}">
                                                                        <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['file']) ?
                                                                $clrDocuments[$row->id]['file'] : ''))}}"
                                                                           title="{{$row->doc_name}}">
                                                                            <i class="fa fa-file-pdf-o"
                                                                               aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->id]['file']); echo end($file_name); ?>
                                                                        </a>

                                                                        <?php if($viewMode != 'on') {?>
                                                                        <a href="javascript:void(0)" onclick="removeAttachedFile({!! $row->id !!}, {!! $row->doc_priority !!})"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                @endif

                                                                <div id="preview_<?php echo $row->id; ?>">
                                                                    <input type="hidden"
                                                                           value="<?php echo !empty($clrDocuments[$row->id]['file']) ?
                                                                               $clrDocuments[$row->id]['file'] : ''?>"
                                                                           id="validate_field_<?php echo $row->id; ?>"
                                                                           name="validate_field_<?php echo $row->id; ?>"
                                                                           class="<?php echo $row->doc_priority == "1" ? "required" : '';  ?>"/>
                                                                </div>

                                                                @if(!empty($allRequestVal["file$row->id"]))
                                                                    <label id="label_file{{$row->id}}"><b>File: {{$allRequestVal["file$row->id"]}}</b></label>
                                                                    <input type="hidden" class="required" value="{{$allRequestVal["validate_field_".$row->id]}}" id="validate_field_{{$row->id}}" name="validate_field_{{$row->id}}">
                                                                @endif

                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($viewMode != 'off')
                                    {{--@include('LicenceApplication::doc-tab')--}}
                                @endif
                            </fieldset>



                            <h3>Declaration & Submit</h3>
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Authorized Persons Information</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('auth_full_name') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_full_name','Full Name ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('auth_full_name', $appInfo->auth_full_name, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_designation','Designation ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('auth_designation', $appInfo->auth_designation, ['class' => 'form-control input-md']) !!}
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
                                                        {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'form-control input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_email','Email address ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('auth_email', $appInfo->auth_email, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group col-md-6 {{$errors->has('auth_letter') ? 'has-error' : ''}}">
                                                    {!! Form::label('auth_letter','Authorization Letter',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="auth_letter" class="form-control input-md {{ (empty($appInfo->auth_letter) ? 'required' : '') }}"/>
                                                        {!! $errors->first('auth_letter','<span class="help-block">:message</span>') !!}
                                                        <a href="/assets/images/sample_auth_letter.png" target="_blank"> <i class="fa  fa-file-pdf-o"></i> Sample Authorization Letter </a><br/>
                                                        @if($viewMode != 'on')
                                                            <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>
                                                        @endif

                                                        <div class="save_file" style="margin-top: 5px">
                                                            <?php if(!empty($appInfo->auth_letter)){ ?>
                                                            <a target="_blank" class="btn btn-xs btn-primary show-in-view" title="Authorization Letter"
                                                               href="{{URL::to('uploads/'.$appInfo->auth_letter)}}"><i class="fa fa-file-pdf-o"></i> Authorization Letter</a>
                                                            <input type="hidden" value="<?php
                                                            if ($appInfo->auth_letter != '') {
                                                                echo $appInfo->auth_letter;
                                                            }
                                                            ?>" id="auth_letter"
                                                                   name="auth_letter"/>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                    <div class="col-sm-9">
                                                        {!! Form::label('auth_image','Profile Picture', ['class'=>'text-left required-star','style'=>'']) !!}
                                                        {!! $errors->first('auth_image','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <img class="img-thumbnail" id="authImageViewer" src="{{ $appInfo->auth_image != '' ? url('users/upload/'.$appInfo->auth_image) : url('assets/images/photo_default.png') }}" alt="Auth Image">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-6 {{$errors->has('auth_signature') ? 'has-error': ''}}">
                                                    <div class="col-sm-9">
                                                        {!! Form::label('auth_signature','Signature', ['class'=>'text-left required-star','style'=>'']) !!}
                                                        {!! $errors->first('auth_signature','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <img class="img-thumbnail" id="authSignatureViewer"
                                                             src="{{ $appInfo->auth_signature != '' ? url('users/signature/'.$appInfo->auth_signature) : url('assets/images/photo_default.png') }}" alt="Auth Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div><!-- /.panel-body -->
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Terms and Conditions</strong></div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 form-group {{$errors->has('acceptTerms') ? 'has-error' : ''}}">
                                                {!! Form::checkbox('acceptTerms', 'yes', $appInfo->acceptTerms, array('class'=>'required col-md-1 col-xs-1 col-sm-1 text-left','id'=>'acceptTerms-2','style'=>'width:3%;margin-left: 2px;')) !!}
                                                <label for="acceptTerms-2" class="col-md-11 col-xs-11 col-sm-11 text-left required-star">I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/system is given. </label>
                                                <div class="clearfix"></div>
                                                {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            @if(ACL::getAccsessRight('LicenceApplication','-E-') && $viewMode != "on")
                                @if($appInfo->status_id != 5)
                                    <button type="submit" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                @endif
                                @if($appInfo->status_id == 5)
                                    <div class="pull-left">
                                        <button type="submit" id="" style="cursor: pointer;" class="btn btn-info btn-md"
                                                value="Submit" name="actionBtn">Re-submit
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
                </div>
                {{--End application form with wizard--}}
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset('assets/scripts/jquery.steps.js') }}"></script>

<script  type="text/javascript">

    @if($viewMode != 'on')
    //--------Step Form init+validation Start----------//
    var form = $("#LicenceApplicationForm").show();
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {
            if(newIndex == 1){}
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

        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            console.log(form.validate().errors()); // show hidden errors in last step
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            }
        }
    });
    //--------Step Form init+validation End----------//
            @endif

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


    //--------File Upload Script Start----------//
    function uploadDocument(targets, id, vField, isRequired) {
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
            var action = "{{url('/licence-application/upload-document')}}";

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
                    //console.log(response);
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

    //------- Manpower start -------//
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

    function companyIsRegistered(is_registered) {
        if(is_registered == 'yes') {
            $("#registered_by_div").show('slow');

            $("#registered_by_other_div").hide('slow');
            $("#registration_copy_div").hide('slow');
            $("#incorporation_certificate_number_div").hide('slow');
            $("#incorporation_certificate_date_div").hide('slow');

            $('#registered_by_id').trigger('change');
//            $("#incorporation_certificate_date_div").show('slow');
        } else if(is_registered == 'no') {
            $("#registered_by_other_div").show('slow');
            $("#registration_copy_div").show('slow');
            $("#registration_copy_label").removeClass('required-star');
            $("#registration_copy").removeClass('required');
            $("#registration_copy").removeClass('error');
            $("#registered_by_id").removeClass('required');
            $('#registration_no').removeClass('required');
            $("#incorporation_certificate_number_div").show('slow');
            $("#incorporation_certificate_date_div").show('slow');

            $("#registered_by_div").hide();
            $("#registration_no_div").hide();
            $("#registration_other_div").hide();
        } else {
            $("#registered_by_div").hide();
            $("#registered_by_other_div").hide();
            $("#registration_copy_div").hide();
            $("#incorporation_certificate_number_div").hide();
            $("#incorporation_certificate_date_div").hide();
            $("#registration_no_div").hide();
            $("#registration_other_div").hide();
        }
    }

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
                            if (id == '{{ $appInfo->ceo_thana_id }}'){
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
        $('#ceo_district_id').trigger('change');

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
                            if (id == '{{ $appInfo->office_thana_id }}'){
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
        $('#office_district_id').trigger('change');

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
                            if (id == '{{ $appInfo->factory_thana_id }}'){
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
        $('#LicenceApplicationForm :input').attr('disabled', true);
    $('#LicenceApplicationForm h3').hide();
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">'+this.value+'</span>');
    });
    // all radio or checkbox which is not checked, that will be hide
    $('#LicenceApplicationForm :input[type=radio], input[type=checkbox]').each(function () {
        if(!$(this).is(":checked")){
            //alert($(this).attr('name'));
            $(this).parent().replaceWith('');
            $(this).replaceWith('');
        }
    });
    $('#LicenceApplicationForm :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on

</script>
<script src="{{ asset("assets/scripts/custom.js") }}" type="text/javascript"></script>