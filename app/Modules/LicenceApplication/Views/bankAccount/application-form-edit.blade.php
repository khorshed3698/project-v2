<?php
$accessMode = ACL::getAccsessRight('BankAccount');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<style>
    .selectInputBank{
        background-color: rgba(61, 181, 215, 1);
        box-sizing: border-box;
        border-width: 1px;
        border-style: solid;
        border-color: rgba(61, 181, 215, 1);
        border-radius: 9px;
        padding-top:10px;
        padding-bottom:10px;
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

                @if($viewMode == 'on' && in_array(Auth::user()->user_type,['5x505']) && $appInfo->status_id == 9)
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h5><strong>Government Fee Payment</strong></h5>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => 'licence-applications/bank-account/payment','method' => 'post','id' => 'VRAPayment','enctype'=>'multipart/form-data',
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
                            <h5><strong> Apply for Bank Account opening to Bangladesh </strong></h5>
                        </div>
                        <div class="pull-right">
                            @if(!in_array($appInfo->status_id,[-1,5,6]))
                                <a href="/licence-applications/bank-account/view-pdf/{{ Encryption::encodeId($appInfo->id)}}"
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
                    <div class="form-body">
                        <div class="row" style="margin:15px 0 15px 0">
                            <div class="col-md-12">
                                <div class="heading_img">
                                    <img class="img-responsive pull-left"
                                         src="{{ asset('assets/images/u39.png') }}"/>
                                </div>
                                <div class="heading_text pull-left">
                                    Sonali Bank Ltd.
                                </div>
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
                                                            <b>Account Number</b>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{$appInfo->acc_no}}

                                                        </div>
                                                        <div class="col-md-2">
                                                            <b>Branch Name</b>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{$appInfo->branch_name}}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif


                        <div class="panel-body">
                            {!! Form::open(array('url' => '/licence-application/bank-account/add','method' => 'post','id' => 'BankAccountForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                            <input type="hidden" value="{{$usdValue->bdt_value}}" id="crvalue">
                            {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
                            {!! Form::hidden('curr_process_status_id', $appInfo->status_id,['class' => 'form-control input-md required', 'id'=>'process_status_id']) !!}
                            <div class="selectInputBank form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            {!! Form::label('bank_id','Select Bank',['class'=>'required-star']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::select('bank_id',$banks, $appInfo->bank_id,['class'=>'form-control input-md required', 'id' => 'bank_id']) !!}

                                        </div>
                                        <div class="col-md-2">
                                            {!! Form::label('bank_branch_id','Select Branch',['class'=>'required-star']) !!}
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::select('bank_branch_id',$bankBranches, $appInfo->bank_branch_id,['class'=>'form-control input-md required', 'id' => 'bank_branch_id']) !!}

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>

                            <h3 class="text-center stepHeader"> Application Information</h3>
                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>
                                    <div class="panel-body readOnlyCl">
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
                                    <div class="panel-body readOnlyCl">
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
                                                        'class' => 'form-control input-md', 'id'=>'ceo_passport_no']) !!}
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
                                                <div class="col-md-6 hidden {{$errors->has('ceo_state') ? 'has-error': ''}}">
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

                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>C. Office Address</strong></div>
                                    <div class="panel-body readOnlyCl">
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
                                                        {!! Form::text('office_post_code', $appInfo->office_post_code, ['class' => 'form-control input-md ']) !!}
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
                                    <div class="panel-heading"><strong>D. Factory Address (Optional)</strong></div>
                                    <div class="panel-body readOnlyCl">
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
                                                        {!! Form::text('factory_mobile_no', $appInfo->factory_mobile_no, ['class' => 'form-control input-md']) !!}
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
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-body">
                                        <div class="form-group">

                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('tin_no') ? 'has-error': ''}}">
                                                    {!! Form::label('tin_no','TIN Number',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('tin_no', $appInfo->tin_no, ['class' => 'form-control input-md number','id'=>'tin_no']) !!}
                                                        <span class=" btn-success btn-sm" style="display:inline">verified</span>
                                                        {!! $errors->first('tin_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 {{$errors->has('tin_file') ? 'has-error': ''}}">
                                                    {!! Form::label('tin_file','TIN Certificate Attached', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="tin_file"
                                                               class='form-control input-md '
                                                               onchange="uploadDocument('tin_file_preview', this.id, 'tin_file_name', '0')"
                                                               id='tin_file'>
                                                        <small class="text-muted">Max file size 2MB.</small>
                                                        {!! $errors->first('tin_file','<span class="help-block">:message</span>') !!}
                                                        <div id="tin_file_preview" class="uploadbox">
                                                            <input type="hidden" class="" id="tin_file_name"
                                                                   name="tin_file_name" value="{{$appInfo->tin_file_name}}">
                                                        </div>

                                                    @if(!empty($appInfo->tin_file_name) && $viewMode == 'off')
                                                            <div id="remove_file_1">
                                                            <a href="/uploads/{{$appInfo->tin_file_name}}" target="_blank" ><span class=" btn-success btn-sm" >Download</span></a>
                                                            <a href="javascript:void(0)" onclick="EmptyDocs('remove_file_1','tin_file_name')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                            </div>

                                                        @endif
                                                        @if( $viewMode == 'on')
                                                            @if(!empty($appInfo->tin_file_name))
                                                                <a href="/uploads/{{$appInfo->tin_file_name}}" target="_blank" ><span class=" btn-success btn-sm" >Download</span></a>
                                                            @else
                                                                No file is attached!
                                                             @endif
                                                        @endif



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-6 {{$errors->has('trade_licence') ? 'has-error': ''}}">
                                                    {!! Form::label('trade_licence','Trade Licence',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('trade_licence', $appInfo->trade_licence, ['class' => 'form-control input-md number','id'=>'trade_licence']) !!}
                                                        <span class=" btn-success btn-sm" style="display:inline">verified</span>
                                                        {!! $errors->first('trade_licence','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('trade_file') ? 'has-error': ''}}">
                                                    {!! Form::label('trade_file','Trade Licence Attached', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="trade_file"
                                                               class='form-control input-md'
                                                               onchange="uploadDocument('trade_file_preview', this.id, 'trade_file_name', '0')"
                                                               id='trade_file'>
                                                        <small class="text-muted">Max file size 2MB.</small>
                                                        {!! $errors->first('trade_file','<span class="help-block">:message</span>') !!}
                                                        <div id="trade_file_preview" class="uploadbox">
                                                            <input type="hidden" class="" id="trade_file_name"
                                                                   name="trade_file_name" value="{{$appInfo->trade_file_name}}">
                                                        </div>
                                                        @if(!empty($appInfo->trade_file_name) && $viewMode == 'off')
                                                            <div id="remove_file_2">
                                                                <a href="/uploads/{{$appInfo->trade_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                                <a href="javascript:void(0)" onclick="EmptyDocs('remove_file_2','trade_file_name')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                            </div>

                                                        @endif
                                                        @if( $viewMode == 'on')
                                                            @if(!empty($appInfo->trade_file_name))
                                                                <a href="/uploads/{{$appInfo->trade_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                            @else
                                                                No file is attached!
                                                            @endif
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('incorporation_no') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_no','Incorporation No',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('incorporation_no', $appInfo->incorporation_no, ['class' => 'form-control input-md number','id'=>'incorporation_no','style'=>"display:inline"]) !!}
                                                        <span class=" btn-success btn-sm" style="display:inline">verified</span>
                                                        {!! $errors->first('incorporation_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('incorporation_file') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_file','Incorporation Attached', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name='incorporation_file'
                                                               class='form-control input-md'
                                                               onchange="uploadDocument('incorporation_file_preview', this.id, 'incorporation_file_name', '0')"
                                                               id='incorporation_file'>
                                                        <small class="text-muted">Max file size 2MB.</small>
                                                        {!! $errors->first('incorporation_file','<span class="help-block">:message</span>') !!}
                                                        <div id="incorporation_file_preview" class="uploadbox">
                                                            <input type="hidden" class=""
                                                                   id="incorporation_file_name"
                                                                   name="incorporation_file_name" value="{{$appInfo->incorporation_file_name}}">
                                                        </div>
                                                        @if(!empty($appInfo->incorporation_file_name) && $viewMode == 'off')
                                                            <div id="remove_file_3">
                                                                <a href="/uploads/{{$appInfo->incorporation_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                                <a href="javascript:void(0)" onclick="EmptyDocs('remove_file_3','incorporation_file_name')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                            </div>

                                                        @endif
                                                        @if( $viewMode == 'on')
                                                            @if(!empty($appInfo->incorporation_file_name))
                                                                <a href="/uploads/{{$appInfo->incorporation_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                            @else
                                                                No file is attached!
                                                            @endif
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('mem_association') ? 'has-error': ''}}">
                                                    {!! Form::label('mem_association','Memorandum of Association',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="mem_association"
                                                               id="mem_association"
                                                               class='form-control input-md'
                                                               onchange="uploadDocument('mem_association_preview', this.id, 'mem_association_file_name', '0')">
                                                        <small class="text-muted">Max file size 2MB.</small>
                                                        {!! $errors->first('mem_association','<span class="help-block">:message</span>') !!}
                                                        <div id="mem_association_preview" class="uploadbox">
                                                            <input type="hidden" class=""
                                                                   id="mem_association_file_name"
                                                                   name="mem_association_file_name" value="{{$appInfo->mem_association_file_name}}">
                                                        </div>
                                                        @if(!empty($appInfo->mem_association_file_name) && $viewMode == 'off')
                                                            <div id="remove_file_4">
                                                                <a href="/uploads/{{$appInfo->mem_association_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                                <a href="javascript:void(0)" onclick="EmptyDocs('remove_file_4','mem_association_file_name')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                            </div>

                                                        @endif
                                                        @if( $viewMode == 'on')
                                                            @if(!empty($appInfo->mem_association_file_name))
                                                                <a href="/uploads/{{$appInfo->mem_association_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                            @else
                                                                No file is attached!
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>


                                                <div class="col-md-6 {{$errors->has('resolution_bank') ? 'has-error': ''}}">
                                                    {!! Form::label('resolution_bank','Resolution for Bank Account',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="resolution_bank"
                                                               id="resolution_bank"
                                                               class='form-control input-md'
                                                               onchange="uploadDocument('resolution_bank_preview', this.id, 'resolution_bank_file_name', '0')">
                                                        <small class="text-muted">Max file size 2MB.</small>
                                                        {!! $errors->first('resolution_bank','<span class="help-block">:message</span>') !!}
                                                        <div id="resolution_bank_preview" class="uploadbox">
                                                            <input type="hidden" class="required"
                                                                   id="resolution_bank_file_name"
                                                                   name="resolution_bank_file_name" value="{{$appInfo->resolution_bank_file_name}}">
                                                        </div>
                                                        @if(!empty($appInfo->resolution_bank_file_name) && $viewMode == 'off')
                                                            <div id="remove_file_5">
                                                                <a href="/uploads/{{$appInfo->resolution_bank_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                                <a href="javascript:void(0)" onclick="EmptyDocs('remove_file_5','resolution_bank_file_name')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                            </div>

                                                        @endif
                                                        @if( $viewMode == 'on')
                                                            @if(!empty($appInfo->resolution_bank_file_name))
                                                                <a href="/uploads/{{$appInfo->resolution_bank_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                            @else
                                                                No file is attached!
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('art_association') ? 'has-error': ''}}">
                                                    {!! Form::label('art_association','Article of Association',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="art_association"
                                                               id="art_association"
                                                               class='form-control input-md'
                                                               onchange="uploadDocument('art_association_preview', this.id, 'art_association_file_name', '0')">
                                                        <small class="text-muted">Max file size 2MB.</small>
                                                        {!! $errors->first('art_association','<span class="help-block">:message</span>') !!}
                                                        <div id="art_association_preview" class="uploadbox">
                                                            <input type="hidden" class=""
                                                                   id="art_association_file_name"
                                                                   name="art_association_file_name" value="{{$appInfo->art_association_file_name}}">
                                                        </div>
                                                        @if(!empty($appInfo->art_association_file_name) && $viewMode == 'off')
                                                            <div id="remove_file_5">
                                                                <a href="/uploads/{{$appInfo->art_association_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                                <a href="javascript:void(0)" onclick="EmptyDocs('remove_file_5','art_association_file_name')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                            </div>

                                                        @endif
                                                        @if( $viewMode == 'on')
                                                            @if(!empty($appInfo->art_association_file_name))
                                                                <a href="/uploads/{{$appInfo->art_association_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                            @else
                                                                No file is attached!
                                                            @endif
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">

                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('list_share_holder_n_director') ? 'has-error': ''}}">
                                                    {!! Form::label('list_share_holder_n_director','List of share holder & Director',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="list_share_holder_n_director"
                                                               id="list_share_holder_n_director"
                                                               class='form-control input-md'
                                                               onchange="uploadDocument('list_share_holder_n_director_preview', this.id, 'list_share_holder_n_director_file_name', '0')">
                                                        <small class="text-muted">Max file size 2MB.</small>
                                                        {!! $errors->first('list_share_holder_n_director','<span class="help-block">:message</span>') !!}
                                                        <div id="list_share_holder_n_director_preview"
                                                             class="uploadbox">
                                                            <input type="hidden" class=""
                                                                   id="list_share_holder_n_director_file_name"
                                                                   name="list_share_holder_n_director_file_name" value="{{$appInfo->list_share_holder_n_director_file_name}}">
                                                        </div>
                                                        @if(!empty($appInfo->list_share_holder_n_director_file_name) && $viewMode == 'off')
                                                            <div id="remove_file_6">
                                                                <a href="/uploads/{{$appInfo->list_share_holder_n_director_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                                <a href="javascript:void(0)" onclick="EmptyDocs('remove_file_6','list_share_holder_n_director_file_name')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a>
                                                            </div>

                                                        @endif
                                                        @if( $viewMode == 'on')
                                                            @if(!empty($appInfo->list_share_holder_n_director_file_name))
                                                                <a href="/uploads/{{$appInfo->list_share_holder_n_director_file_name}}" target="_blank"><span class=" btn-success btn-sm" >Download</span></a>
                                                            @else
                                                                No file is attached!
                                                            @endif
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-6 ">

                                                </div>
                                            </div>
                                        </div>

                                  <!--  {{--<div class="form-group">--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-md-12">--}}
                                                {{--<label for="tin_number" class="col-md-2 text-left">8. TIN Number </label>--}}
                                                {{--<div class="col-md-3">--}}
                                                    {{--{!! Form::text('tin_number', $appInfo->tin_number, ['data-rule-maxlength'=>'40','class' => 'tin_number form-control input-md','id'=>'tin_number']) !!}--}}
                                                    {{--{!! $errors->first('tin_number','<span class="help-block">:message</span>') !!}--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-1">--}}
                                                    {{--<span class="verified_icon">Verified</span>--}}
                                                {{--</div>--}}
                                                {{--<label for="tin_number" class="col-md-3 text-left">TIN Certificate Attached</label>--}}
                                                {{--<div class="col-md-3">--}}
                                                    {{--<input type="file" name="tin_file_path" id="tin_file_path" class="form-control input-md required"/>--}}
                                                    {{--{!! $errors->first('tin_file_path','<span class="help-block">:message</span>') !!}--}}
                                                    {{--@if($viewMode != 'on')--}}
                                                        {{--<span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>--}}
                                                    {{--@endif--}}

                                                    {{--<div class="save_file" style="margin-top: 5px">--}}
                                                        {{--<?php if(!empty($appInfo->tin_file_path)){ ?>--}}
                                                        {{--<a target="_blank" class="btn btn-xs btn-primary show-in-view" title="Registration Copy"--}}
                                                           {{--href="{{URL::to('uploads/'.$appInfo->tin_file_path)}}"><i class="fa fa-file-pdf-o"></i> TIN Certificate Copy</a>--}}
                                                        {{--<input type="hidden" value="<?php--}}
                                                        {{--if ($appInfo->tin_file_path != '') {--}}
                                                            {{--echo $appInfo->tin_file_path;--}}
                                                        {{--}--}}
                                                        {{--?>" id="tin_file_path"--}}
                                                               {{--name="tin_file_path"/>--}}
                                                        {{--<?php } ?>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}

                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}-->


                            @if(ACL::getAccsessRight('BankAccount','-E-') && $viewMode == "off")
                                @if($appInfo->status_id != 5)
                                    <button type="submit" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                    <div class="pull-right">
                                        <button type="submit" id="" style="cursor: pointer;" class="btn btn-info btn-md submit"
                                                value="Submit" name="actionBtn">Submit
                                        </button>
                                    </div>
                                @endif
                                @if($appInfo->status_id == 5)
                                    <div class="pull-left">
                                        <button type="submit" id="" style="cursor: pointer;" class="btn btn-info btn-md submit"
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

<script  type="text/javascript">

    function  EmptyDocs(id,fileInputId){
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
            var file_info = document.getElementById(id);
            file_info.remove();
            document.getElementById(fileInputId).value='';
            swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
            )
        } else {
            return false;
        }
    });
    }




    $(document).ready(function(){

        $('.commercial_operation_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
        });



        $('#BankAccountForm').validate();


        $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea, .readOnly').attr('readonly',true);
        $(".readOnlyCl option:not(:selected)").prop('disabled', true);

        $('#bank_id').on('change', function () {
            var bank_id = $(this).val();
            var action = "{{url('/licence-application/bank-account/branches')}}";
            var form_data = new FormData();
            form_data.append('bank_id', bank_id);
            form_data.append('_token', "{{ csrf_token() }}");
            $.ajax({
                url: action,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    var bankBranches = JSON.parse(response);
                    if (bankBranches.responseCode) {
                        var html = "<option value='' >Select one</option>";
                        $.each(bankBranches.data, function (key, value) {
                            html += "<option value='" + value.id + "' >" + value.branch_name + "</option>";

                        });
                        $('#bank_branch_id').html(html);
                    }
                }
            });
        });

        $('#BankApplicationForm').validate();
        $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea,.readOnly').attr('readonly', true);
        $(".readOnlyCl option:not(:selected)").prop('disabled', true);
    });

    function calculateAnnulCapacity(event) {
        var id = event.split(/[_ ]+/).pop();
        var no1 = $('#apc_quantity_'+id).val() ? parseFloat($('#apc_quantity_'+id).val()) : 0;
        var no2 = $('#apc_price_usd_'+id).val() ? parseFloat($('#apc_price_usd_'+id).val()) : 0;
        var bdtValue = $('#crvalue').val() ? parseFloat($('#crvalue').val()) : 0;
        var usdToBdt = bdtValue* no2;
        var total = (no1*usdToBdt)/1000000;

        $('#apc_value_taka_'+ id).val(total);
    }

    function Calculate44Numbers(arg1, arg2, place) {

        var no1 = $('#'+arg1).val() ? parseFloat($('#'+arg1).val()) : 0;
        var no2 = $('#'+arg2).val() ? parseFloat($('#'+arg2).val()) : 0;

        var total = new SumArguments(no1, no2);
        $('#'+ place).val(total.sum());

        var inputs = $(".totalTakaOrM");
        var total1 = 0;

        // $(inputs).each(function( value ) {
        //    console.log(value)
        // });
        var total7 = 0;
        for(var i = 0; i < inputs.length; i++){
            if($(inputs[i]).val() !== '')
                total7 += parseFloat($(inputs[i]).val());

        }
        $("#total_ivst").val(total7);
        $("#total_fixed_ivst22").val(total7);


    }

    function SumArguments() {
        var _arguments = arguments;
        this.sum = function() {
            var i = _arguments.length;
            var result = 0;
            while (i--) {
                result += _arguments[i];
            }
            return result;
        };
    }

    function calculateSourceOfFinance(event) {
        var no1 = $('#finance_src_loc_equity_1').val() ? parseFloat($('#finance_src_loc_equity_1').val()) : 0;
        var no2 = $('#finance_src_foreign_equity_1').val() ? parseFloat($('#finance_src_foreign_equity_1').val()) : 0;
        var total = (no1+no2);

        $('#finance_src_loc_total_equity_1').val(total);
        $('#finance_src_loc_equity_2').val(no1*total/100);
        $('#finance_src_foreign_equity_2').val(no2*total/100);

        var no3 = $('#finance_src_loc_loan_1').val() ? parseFloat($('#finance_src_loc_loan_1').val()) : 0;
        var no4 = $('#finance_src_foreign_loan_1').val() ? parseFloat($('#finance_src_foreign_loan_1').val()) : 0;

        var total2 = (no3+no4);
        $('#finance_src_total_loan').val(total2);
        $('#finance_src_loc_total_financing_1').val(no1+no2+no3+no4);


    }


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
            extraFormats: [ 'DD.MM.YY', 'DD.MM.YYYY' ],
            maxDate: 'now',
            minDate: '01/01/1905'
        });
    } // end of addTableRow() function

    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
    }

    function CalculatePercent(id){
        var oneValue = parseFloat($("#"+id).val());

        if(oneValue >100){
            alert("Total percentage can't be more than 100");
        }
        var anotherVal = 100-oneValue;
        if(id == 'local_sales_per'){
            $("#foreign_sales_per").val(anotherVal);
        }else{
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
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length) $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{url('/licence-application/bank-account/upload-document')}}";

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
                url: action,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = parseInt(id.substring(4));
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                            ' </b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
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
        $('#BankAccountForm :input').attr('disabled', true);
    $('#BankAccountForm h3').hide();
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">'+this.value+'</span>');
    });
    // all radio or checkbox which is not checked, that will be hide
    $('#BankAccountForm :input[type=radio], input[type=checkbox]').each(function () {
        if(!$(this).is(":checked")){
            //alert($(this).attr('name'));
            $(this).parent().replaceWith('');
            $(this).replaceWith('');
        }
    });
    $('#BankAccountForm :input[type=file]').hide();
    $('.addTableRows').hide();
    $('.text-muted').hide();
    @endif // viewMode is on

</script>
<script src="{{ asset("assets/scripts/custom.js") }}" type="text/javascript"></script>