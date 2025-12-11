@extends('layouts.admin')
@section('style')
    {{--Datepicker css--}}
    <link rel="stylesheet" href="{{ asset("vendor/datepicker/datepicker.min.css") }}">

    <style>
        .bg-yellow{
            background-color: rgba(246, 209, 15, 1);
            width: 23em !important;
        }
        .bg-green{
            background-color: rgba(103, 219, 56, 1);
            width: 23em !important;
        }
        .datepicker-width {
            min-width: 16em !important;
        }
        .number-width {
            width: 21em !important;
        }
    </style>
@endsection

@section('content')
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

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h5><strong>Application for Change Basic Information </strong></h5>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => '/settings/store-change-basic-info','method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'changeBasicInfoForm',
                            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                            {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($appInfo->id)) !!}
                            {!! Form::hidden('company_id', \App\Libraries\Encryption::encodeId($appInfo->company_id)) !!}

                            {{--A. Company Information--}}
                            <div class="panel panel-info">
                                <div class="panel-heading "><strong>A. Company Information</strong></div>
                                <div class="panel-body">
                                    <div class="clearfix padding"></div>
                                    <table aria-label="Detailed Company Information" class="table table-responsive table-bordered">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td>Field name</td>
                                            <td class="bg-yellow">Existing information (Latest BIDA Reg. Info.)</td>
                                            <td class="bg-green">Proposed information</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Name of organization/ company</td>
                                            <td class="light-yellow">
                                                {!! Form::text('company_name', (!empty($appInfo->company_name) ? $appInfo->company_name : ''), ['class'=>'form-control input-md cusReadonly', 'id'=>"company_name"]) !!}
                                                {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_company_name]" value="Name of organization/ company"/>
                                                <input type="hidden" name="column_name[n_company_name]" value="company_name"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_company_name', '', ['class'=>'form-control input-md', 'id'=>"n_company_name", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_company_name]", 1, null, ['class' => 'field', 'id' => 'n_company_name_check', 'onclick' => "toggleCheckBox('n_company_name_check', ['n_company_name']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_company_name','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Name of organization/ company (বাংলা)</td>
                                            <td class="light-yellow">
                                                {!! Form::text('company_name_bn',(!empty($appInfo->company_name_bn) ? $appInfo->company_name_bn : ''), ['class'=>'form-control input-md cusReadonly', 'id'=>"company_name_bn"]) !!}
                                                {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_company_name_bn]" value="Name of organization/ company (বাংলা)"/>
                                                <input type="hidden" name="column_name[n_company_name_bn]" value="company_name_bn"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_company_name_bn', '', ['class'=>'form-control input-md', 'id'=>"n_company_name_bn", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_company_name_bn]", 1, null, ['class' => 'field', 'id' => 'n_company_name_bn_check', 'onclick' => "toggleCheckBox('n_company_name_bn_check', ['n_company_name_bn']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_company_name_bn','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Desired Service from BIDA</td>
                                            <td class="light-yellow">
                                                {!! Form::select('service_type', $eaService, (!empty($appInfo->service_type) ? $appInfo->service_type : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'service_type']) !!}
                                                {!! $errors->first('service_type','<span class="help-block">:message</span>') !!}

                                                <div id="reg_commercial_office" style="margin-top: 10px; display:none;">
                                                    {!! Form::select('reg_commercial_office',$eaRegCommercialOffices, (!empty($appInfo->reg_commercial_office) ? $appInfo->reg_commercial_office  : ''),['class'=>'form-control input-md required cusReadonly', 'id' => 'reg_commercial_office', ]) !!}
                                                </div>
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_service_type]" value="Desired Service from BIDA"/>
                                                <input type="hidden" name="column_name[n_service_type]" value="service_type"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_service_type', $eaService, '',['class'=>'form-control input-md ', 'id' => 'n_service_type', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_service_type]", 1, null, ['class' => 'field', 'id' => 'n_project_name_check', 'onclick' => "toggleCheckBox('n_project_name_check', ['n_service_type']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_service_type','<span class="help-block">:message</span>') !!}

                                                <div id="n_reg_commercial_office" style="margin-top: 10px; display:none;">
                                                    {!! Form::select('n_reg_commercial_office',$eaRegCommercialOffices, '',['class'=>'form-control input-md', 'id' => 'n_reg_commercial_office']) !!}
                                                </div>
                                            </td>
                                        </tr>

                                        @if($appInfo->business_category == 1)
                                            <tr>
                                                <td><label>Ownership status</label></td>
                                                <td class="light-yellow">
                                                    {!! Form::select('ownership_status_id', $eaOwnershipStatus, (!empty($appInfo->ownership_status_id) ? $appInfo->ownership_status_id : ''), ['class' => 'form-control cusReadonly input-md ', 'id'=>'ownership_status_id']) !!}
                                                    {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ownership_status_id]" value="Ownership status"/>
                                                    <input type="hidden" name="column_name[n_ownership_status_id]" value="ownership_status_id"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ownership_status_id', $eaOwnershipStatus, '', ['class' => 'form-control input-md','id'=>'n_ownership_status_id', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ownership_status_id]", 1, null, ['class' => 'field', 'id' => 'n_ownership_status_id_check', 'onclick' => "toggleCheckBox('n_ownership_status_id_check', ['n_ownership_status_id']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ownership_status_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <td><label>Type of the organization</label></td>
                                            <td class="light-yellow">
                                                {!! Form::select('organization_type_id', $eaOrganizationType, (!empty($appInfo->organization_type_id) ? $appInfo->organization_type_id : ''), ['class' => 'form-control cusReadonly input-md ', 'id'=>'organization_type_id']) !!}
                                                {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_organization_type_id]" value="Type of the organization"/>
                                                <input type="hidden" name="column_name[n_organization_type_id]" value="organization_type_id"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_organization_type_id', $eaOrganizationType, '', ['class' => 'form-control input-md','id'=>'n_organization_type_id', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_organization_type_id]", 1, null, ['class' => 'field', 'id' => 'n_organization_type_id_check', 'onclick' => "toggleCheckBox('n_organization_type_id_check', ['n_organization_type_id']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_organization_type_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Major activities in brief</td>
                                            <td class="light-yellow">
                                                {!! Form::text('major_activities', (!empty($appInfo->major_activities) ? $appInfo->major_activities : ''), ['class'=>'form-control input-md cusReadonly', 'id'=>"major_activities"]) !!}
                                                {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_major_activities]" value="Major activities in brief"/>
                                                <input type="hidden" name="column_name[n_major_activities]" value="major_activities"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_major_activities', '', ['class'=>'form-control input-md', 'id'=>"n_major_activities", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_major_activities]", 1, null, ['class' => 'field', 'id' => 'n_major_activities_check', 'onclick' => "toggleCheckBox('n_major_activities_check', ['n_major_activities']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_major_activities','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            {{--B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO--}}
                            @if($appInfo->business_category == 2)
                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>B. Information of Responsible Person</strong></div>
                                    <div class="panel-body">
                                        <div class="clearfix padding"></div>
                                        <table aria-label="Detailed Information of Responsible Person" class="table table-responsive table-bordered">
                                            <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true"  scope="col"></th>
                                            </tr>
                                            <tr>
                                                <td>Field name</td>
                                                <td class="bg-yellow">Existing information (Latest BIDA Reg. Info.)</td>
                                                <td class="bg-green">Proposed information</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Country</td>
                                                <td class="light-yellow">
                                                    {!! Form::select('ceo_country_id', $countries, (!empty($appInfo->ceo_country_id) ? $appInfo->ceo_country_id : ''), ['class' => 'form-control cusReadonly input-md ','id'=>'ceo_country_id']) !!}
                                                    {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_country_id]" value="Principal promoter Country"/>
                                                    <input type="hidden" name="column_name[n_ceo_country_id]" value="ceo_country_id"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ceo_country_id', $countries, '', ['class' => 'form-control  input-md ','id'=>'n_ceo_country_id', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_country_id]", 1, null, ['class' => 'field', 'id' => 'n_ceo_country_id_check', 'onclick' => "toggleCheckBox('n_ceo_country_id_check', ['n_ceo_country_id']);"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_country_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Full Name</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_full_name', (!empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_full_name']) !!}
                                                    {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_full_name]" value="Principal promoter Full Name"/>
                                                    <input type="hidden" name="column_name[n_ceo_full_name]" value="ceo_full_name"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_full_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_full_name', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_full_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_full_name_check', 'onclick' => "toggleCheckBox('n_ceo_full_name_check', ['n_ceo_full_name']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_full_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>NID/ Passport No.</td>
                                                <td class="light-yellow hidden" id="foreignExistingPassportField">
                                                    {!! Form::text('ceo_passport_no', (!empty($appInfo->ceo_passport_no) ? $appInfo->ceo_passport_no : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_passport_no', 'placeholder' => 'Passport No.']) !!}
                                                    {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-yellow hidden" id="BDNIDExistingField">
                                                    {!! Form::text('ceo_nid', (!empty($appInfo->ceo_nid) ? $appInfo->ceo_nid : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_nid', 'placeholder' => 'NID']) !!}
                                                    {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                </td>

                                                <td class="light-green hidden" id="foreignProposedPassportField">
                                                    <input type="hidden" name="label_name[n_ceo_passport_no]" value="Principal promoter NID/ Passport No."/>
                                                    <input type="hidden" name="column_name[n_ceo_passport_no]" value="ceo_passport_no"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_passport_no','',['class'=>'form-control input-md', 'placeholder' => 'Passport No.', 'id' => 'n_ceo_passport_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_passport_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_passport_no_check', 'onclick' => "toggleCheckBox('n_ceo_passport_no_check', ['n_ceo_passport_no']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green hidden" id="BDNIDProposedField">
                                                    <input type="hidden" name="label_name[n_ceo_nid]" value="Principal promoter NID/ Passport No."/>
                                                    <input type="hidden" name="column_name[n_ceo_nid]" value="ceo_nid"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_nid','',['class'=>'form-control input-md', 'placeholder' => 'NID', 'id' => 'n_ceo_nid', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_nid]", 1, null, ['class' => 'field', 'id' => 'n_ceo_nid_no_check', 'onclick' => "toggleCheckBox('n_ceo_nid_no_check', ['n_ceo_nid']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_nid','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Designation</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_designation', (!empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_designation']) !!}
                                                    {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_designation]" value="Principal promoter Designation"/>
                                                    <input type="hidden" name="column_name[n_ceo_designation]" value="ceo_designation"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_designation','',['class'=>'form-control input-md', 'id' => 'n_ceo_designation', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_designation]", 1, null, ['class' => 'field', 'id' => 'n_ceo_designation_check', 'onclick' => "toggleCheckBox('n_ceo_designation_check', ['n_ceo_designation']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_designation','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Mobile No.</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_mobile_no', (!empty($appInfo->ceo_mobile_no) ? $appInfo->ceo_mobile_no : ''),['class'=>'form-control input-md cusReadonly number-width', 'id' => 'ceo_mobile_no']) !!}
                                                    {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_mobile_no]" value="Principal promoter Principal promoter mobile no."/>
                                                    <input type="hidden" name="column_name[n_ceo_mobile_no]" value="ceo_mobile_no"/>
                                                    <div class="input-group mobile-plugin">
                                                        {!! Form::text('n_ceo_mobile_no','',['class'=>'form-control input-md', 'id' => 'n_ceo_mobile_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_mobile_no_check', 'onclick' => "toggleCheckBox('n_ceo_mobile_no_check', ['n_ceo_mobile_no']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Email</td>
                                                <td class="light-yellow">
                                                    {!! Form::email('ceo_email', (!empty($appInfo->ceo_email) ? $appInfo->ceo_email : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_email']) !!}
                                                    {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_email]" value="Principal promoter Email"/>
                                                    <input type="hidden" name="column_name[n_ceo_email]" value="ceo_email"/>
                                                    <div class="input-group">
                                                        {!! Form::email('n_ceo_email','',['class'=>'form-control input-md', 'id' => 'n_ceo_email', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_email]", 1, null, ['class' => 'field', 'id' => 'n_ceo_email_check', 'onclick' => "toggleCheckBox('n_ceo_email_check', ['n_ceo_email']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Gender</td>
                                                <td class="light-yellow">
                                                    <label class="radio-inline">{!! Form::radio('ceo_gender','Male', (!empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Male" ? true : false), ['class'=>'cusReadonly', 'id'=>'male']) !!}  Male</label>
                                                    <label class="radio-inline">{!! Form::radio('ceo_gender', 'Female',(!empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Female" ? true : false), ['class'=>'cusReadonly', 'id'=>'female']) !!}  Female</label>
                                                    {!! $errors->first('ceo_gender','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_gender]" value="Principal promoter Gender"/>
                                                    <input type="hidden" name="column_name[n_ceo_gender]" value="ceo_gender"/>
                                                    <div class="input-group">
                                                        <label class="radio-inline">{!! Form::radio('n_ceo_gender','male', '', ['class'=>'required', 'id'=>'n_male', 'disabled' => 'disabled']) !!}  Male</label>
                                                        <label class="radio-inline">{!! Form::radio('n_ceo_gender', 'female', '', ['class'=>'required', 'id'=>'n_female', 'disabled' => 'disabled']) !!}  Female</label>
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_gender]", 1, null, ['class' => 'field', 'id' => 'n_ceo_gender_check', 'onclick' => "toggleCheckBox('n_ceo_gender_check', ['n_male', 'n_female']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_gender_check','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            @else
                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO</strong></div>
                                    <div class="panel-body">
                                        <div class="clearfix padding"></div>
                                        <table aria-label="Detailed Information of Principal Promoter" class="table table-responsive table-bordered">
                                            <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true"  scope="col"></th>
                                            </tr>
                                            <tr>
                                                <td>Field name</td>
                                                <td class="bg-yellow">Existing information (Latest BIDA Reg. Info.)</td>
                                                <td class="bg-green">Proposed information</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Country</td>
                                                <td class="light-yellow">
                                                    {!! Form::select('ceo_country_id', $countries, (!empty($appInfo->ceo_country_id) ? $appInfo->ceo_country_id : ''), ['class' => 'form-control cusReadonly input-md ','id'=>'ceo_country_id']) !!}
                                                    {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_country_id]" value="Principal promoter Country"/>
                                                    <input type="hidden" name="column_name[n_ceo_country_id]" value="ceo_country_id"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ceo_country_id', $countries, '', ['class' => 'form-control  input-md ','id'=>'n_ceo_country_id', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_country_id]", 1, null, ['class' => 'field', 'id' => 'n_ceo_country_id_check', 'onclick' => "toggleCheckBox('n_ceo_country_id_check', ['n_ceo_country_id']);"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_country_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Date of Birth</td>
                                                <td class="light-yellow">
                                                    <div class="input-group date" data-date-format="dd-mm-yyyy">
                                                        {!! Form::text('ceo_dob', (empty($appInfo->ceo_dob) ? '' : date('d-M-Y', strtotime($appInfo->ceo_dob))), ['class'=>'form-control input-md datepicker cusReadonly', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker']) !!}
                                                    </div>
                                                    {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_dob]" value="Principal promoter Date of Birth"/>
                                                    <input type="hidden" name="column_name[n_ceo_dob]" value="ceo_dob"/>
                                                    <div class="input-group">
                                                        <div class="date" data-date-format="dd-mm-yyyy" style="display: flex">
                                                            {!! Form::text('n_ceo_dob', '', ['class'=>'form-control input-md datepicker datepicker-width', 'id' => 'n_ceo_dob', 'placeholder'=>'Pick from datepicker', 'disabled' => 'disabled']) !!}
                                                        </div>
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ceo_dob]", 1, null, ['id' => 'n_ceo_dob_check', 'onclick' => "toggleCheckBox('n_ceo_dob_check', ['n_ceo_dob']);"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_dob','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>NID/ Passport No.</td>
                                                <td class="light-yellow hidden" id="foreignExistingPassportField">
                                                    {!! Form::text('ceo_passport_no', (!empty($appInfo->ceo_passport_no) ? $appInfo->ceo_passport_no : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_passport_no', 'placeholder' => 'Passport No.']) !!}
                                                    {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-yellow hidden" id="BDNIDExistingField">
                                                    {!! Form::text('ceo_nid', (!empty($appInfo->ceo_nid) ? $appInfo->ceo_nid : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_nid', 'placeholder' => 'NID']) !!}
                                                    {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                </td>

                                                <td class="light-green hidden" id="foreignProposedPassportField">
                                                    <input type="hidden" name="label_name[n_ceo_passport_no]" value="Principal promoter NID/ Passport No."/>
                                                    <input type="hidden" name="column_name[n_ceo_passport_no]" value="ceo_passport_no"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_passport_no','',['class'=>'form-control input-md', 'placeholder' => 'Passport No.', 'id' => 'n_ceo_passport_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_passport_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_passport_no_check', 'onclick' => "toggleCheckBox('n_ceo_passport_no_check', ['n_ceo_passport_no']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green hidden" id="BDNIDProposedField">
                                                    <input type="hidden" name="label_name[n_ceo_nid]" value="Principal promoter NID/ Passport No."/>
                                                    <input type="hidden" name="column_name[n_ceo_nid]" value="ceo_nid"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_nid','',['class'=>'form-control input-md', 'placeholder' => 'NID', 'id' => 'n_ceo_nid', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_nid]", 1, null, ['class' => 'field', 'id' => 'n_ceo_nid_no_check', 'onclick' => "toggleCheckBox('n_ceo_nid_no_check', ['n_ceo_nid']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_nid','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Designation</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_designation', (!empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_designation']) !!}
                                                    {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_designation]" value="Principal promoter Designation"/>
                                                    <input type="hidden" name="column_name[n_ceo_designation]" value="ceo_designation"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_designation','',['class'=>'form-control input-md', 'id' => 'n_ceo_designation', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_designation]", 1, null, ['class' => 'field', 'id' => 'n_ceo_designation_check', 'onclick' => "toggleCheckBox('n_ceo_designation_check', ['n_ceo_designation']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_designation','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Full Name</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_full_name', (!empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_full_name']) !!}
                                                    {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_full_name]" value="Principal promoter Full Name"/>
                                                    <input type="hidden" name="column_name[n_ceo_full_name]" value="ceo_full_name"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_full_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_full_name', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_full_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_full_name_check', 'onclick' => "toggleCheckBox('n_ceo_full_name_check', ['n_ceo_full_name']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_full_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>District/ City/ State</td>

                                                <td class="light-yellow hidden" id="foreignExistingCity">
                                                    {!! Form::text('ceo_city', (!empty($appInfo->ceo_city) ? $appInfo->ceo_city : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_City', 'placeholder' => 'District/ City/ State']) !!}
                                                    {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-yellow hidden" id="BDExistingDistrict">
                                                    {!! Form::select('ceo_district_id', $districts, (!empty($appInfo->ceo_district_id) ? $appInfo->ceo_district_id : ''),['class'=>'form-control cusReadonly input-md', 'id' => 'ceo_district_id']) !!}
                                                    {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                </td>

                                                <td class="light-green hidden" id="foreignProposedCity">
                                                    <input type="hidden" name="label_name[n_ceo_city]" value="Principal promoter District/ City/ State"/>
                                                    <input type="hidden" name="column_name[n_ceo_city]" value="ceo_city"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_city','',['class'=>'form-control input-md', 'id' => 'n_ceo_city', 'placeholder' => 'District/ City/ State', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_city]", 1, null, ['class' => 'field', 'id' => 'n_ceo_City_check', 'onclick' => "toggleCheckBox('n_ceo_City_check', ['n_ceo_city']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_city','<span class="help-block">:message</span>') !!}
                                                </td>

                                                <td class="light-green hidden" id="BDProposedDistrict">
                                                    <input type="hidden" name="label_name[n_ceo_district_id]" value="Principal promoter District/ City/ State"/>
                                                    <input type="hidden" name="column_name[n_ceo_district_id]" value="ceo_district_id"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ceo_district_id', $districts, '',['class'=>'form-control input-md', 'id' => 'n_ceo_district_id', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_district_id]", 1, null, ['class' => 'field', 'id' => 'n_ceo_district_id_check', 'onclick' => "toggleCheckBox('n_ceo_district_id_check', ['n_ceo_district_id']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_district_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>State/ Province/ Police station/ Town</td>
                                                <td class="light-yellow hidden" id="foreignExistingState">
                                                    {!! Form::text('ceo_state', (!empty($appInfo->ceo_state) ? $appInfo->ceo_state : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_state', 'placeholder' => 'State/ Province']) !!}
                                                    {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-yellow hidden" id="BDExistingTown">
                                                    {!! Form::select('ceo_thana_id', $thana, (!empty($appInfo->ceo_thana_id) ? $appInfo->ceo_thana_id : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_thana_id']) !!}
                                                    {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                </td>

                                                <td class="light-green hidden" id="foreignProposedState">
                                                    <input type="hidden" name="label_name[n_ceo_state]" value="Principal promoter State/ Province/ Police station/ Town"/>
                                                    <input type="hidden" name="column_name[n_ceo_state]" value="ceo_state"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_state','',['class'=>'form-control input-md', 'id' => 'n_ceo_state', 'placeholder' => 'State/ Province', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_state]", 1, null, ['class' => 'field', 'id' => 'n_ceo_state_check', 'onclick' => "toggleCheckBox('n_ceo_state_check', ['n_ceo_state']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_state','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green hidden" id="BDProposedTown">
                                                    <input type="hidden" name="label_name[n_ceo_thana_id]" value="Principal promoter State/ Province/ Police station/ Town"/>
                                                    <input type="hidden" name="column_name[n_ceo_thana_id]" value="ceo_thana_id"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ceo_thana_id', $thana, '',['class'=>'form-control input-md', 'id' => 'n_ceo_thana_id', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_thana_id]", 1, null, ['class' => 'field', 'id' => 'n_ceo_thana_id_check', 'onclick' => "toggleCheckBox('n_ceo_thana_id_check', ['n_ceo_thana_id']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Post/ Zip Code</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_post_code', (!empty($appInfo->ceo_post_code) ? $appInfo->ceo_post_code : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_post_code']) !!}
                                                    {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_post_code]" value="Principal promoter Post/ Zip Code"/>
                                                    <input type="hidden" name="column_name[n_ceo_post_code]" value="ceo_post_code"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_post_code','',['class'=>'form-control input-md', 'id' => 'n_ceo_post_code', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_post_code]", 1, null, ['class' => 'field', 'id' => 'n_ceo_post_code_check', 'onclick' => "toggleCheckBox('n_ceo_post_code_check', ['n_ceo_post_code']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_post_code','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>House, Flat/ Apartment, Road</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_address', (!empty($appInfo->ceo_address) ? $appInfo->ceo_address : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_address']) !!}
                                                    {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_address]" value="Principal promoter House, Flat/ Apartment, Road"/>
                                                    <input type="hidden" name="column_name[n_ceo_address]" value="ceo_address"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_address','',['class'=>'form-control input-md', 'id' => 'n_ceo_address', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_address]", 1, null, ['class' => 'field', 'id' => 'n_ceo_address_check', 'onclick' => "toggleCheckBox('n_ceo_address_check', ['n_ceo_address']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_address','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Telephone No.</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_telephone_no', (!empty($appInfo->ceo_telephone_no) ? $appInfo->ceo_telephone_no : ''),['class'=>'form-control input-md cusReadonly number-width', 'id' => 'ceo_telephone_no']) !!}
                                                    {!! $errors->first('ceo_telephone_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_telephone_no]" value="Principal promoter Telephone No."/>
                                                    <input type="hidden" name="column_name[n_ceo_telephone_no]" value="ceo_telephone_no"/>
                                                    <div class="input-group mobile-plugin">
                                                        {!! Form::text('n_ceo_telephone_no','',['class'=>'form-control input-md', 'id' => 'n_ceo_telephone_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon" style="padding: 6px 24px 6px 12px;">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_telephone_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_telephone_no_check', 'onclick' => "toggleCheckBox('n_ceo_telephone_no_check', ['n_ceo_telephone_no']);"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_telephone_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Mobile No.</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_mobile_no', (!empty($appInfo->ceo_mobile_no) ? $appInfo->ceo_mobile_no : ''),['class'=>'form-control input-md cusReadonly number-width', 'id' => 'ceo_mobile_no']) !!}
                                                    {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_mobile_no]" value="Principal promoter Principal promoter mobile no."/>
                                                    <input type="hidden" name="column_name[n_ceo_mobile_no]" value="ceo_mobile_no"/>
                                                    <div class="input-group mobile-plugin">
                                                        {!! Form::text('n_ceo_mobile_no','',['class'=>'form-control input-md', 'id' => 'n_ceo_mobile_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_mobile_no_check', 'onclick' => "toggleCheckBox('n_ceo_mobile_no_check', ['n_ceo_mobile_no']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td class="light-yellow">
                                                    {!! Form::email('ceo_email', (!empty($appInfo->ceo_email) ? $appInfo->ceo_email : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_email']) !!}
                                                    {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_email]" value="Principal promoter Email"/>
                                                    <input type="hidden" name="column_name[n_ceo_email]" value="ceo_email"/>
                                                    <div class="input-group">
                                                        {!! Form::email('n_ceo_email','',['class'=>'form-control input-md', 'id' => 'n_ceo_email', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_email]", 1, null, ['class' => 'field', 'id' => 'n_ceo_email_check', 'onclick' => "toggleCheckBox('n_ceo_email_check', ['n_ceo_email']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Fax No.</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_fax_no', (!empty($appInfo->ceo_fax_no) ? $appInfo->ceo_fax_no : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_fax_no']) !!}
                                                    {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_fax_no]" value="Principal promoter Fax No."/>
                                                    <input type="hidden" name="column_name[n_ceo_fax_no]" value="ceo_fax_no"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_fax_no','',['class'=>'form-control input-md', 'id' => 'n_ceo_fax_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_fax_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_fax_no_check', 'onclick' => "toggleCheckBox('n_ceo_fax_no_check', ['n_ceo_fax_no']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Father's Name</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_father_name', (!empty($appInfo->ceo_father_name) ? $appInfo->ceo_father_name : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_father_name']) !!}
                                                    {!! $errors->first('ceo_father_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_father_name]" value="Principal promoter Father's Name"/>
                                                    <input type="hidden" name="column_name[n_ceo_father_name]" value="ceo_father_name"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_father_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_father_name', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_father_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_father_name_check', 'onclick' => "toggleCheckBox('n_ceo_father_name_check', ['n_ceo_father_name']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_father_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Mother's Name</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_mother_name', (!empty($appInfo->ceo_mother_name) ? $appInfo->ceo_mother_name : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_mother_name']) !!}
                                                    {!! $errors->first('ceo_mother_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_mother_name]" value="Principal promoter Mother's Name"/>
                                                    <input type="hidden" name="column_name[n_ceo_mother_name]" value="ceo_mother_name"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_mother_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_mother_name', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_mother_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_mother_name_check', 'onclick' => "toggleCheckBox('n_ceo_mother_name_check', ['n_ceo_mother_name']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_mother_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Spouse name</td>
                                                <td class="light-yellow">
                                                    {!! Form::text('ceo_spouse_name', (!empty($appInfo->ceo_spouse_name) ? $appInfo->ceo_spouse_name : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'ceo_spouse_name']) !!}
                                                    {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_spouse_name]" value="Principal promoter Spouse name"/>
                                                    <input type="hidden" name="column_name[n_ceo_spouse_name]" value="ceo_spouse_name"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ceo_spouse_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_spouse_name', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_spouse_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_spouse_name_check', 'onclick' => "toggleCheckBox('n_ceo_spouse_name_check', ['n_ceo_spouse_name']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_spouse_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Gender</td>
                                                <td class="light-yellow">
                                                    <label class="radio-inline">{!! Form::radio('ceo_gender','Male', (!empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Male" ? true : false), ['class'=>'cusReadonly', 'id'=>'male']) !!}  Male</label>
                                                    <label class="radio-inline">{!! Form::radio('ceo_gender', 'Female',(!empty($appInfo->ceo_gender) && $appInfo->ceo_gender == "Female" ? true : false), ['class'=>'cusReadonly', 'id'=>'female']) !!}  Female</label>
                                                    {!! $errors->first('ceo_gender','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="label_name[n_ceo_gender]" value="Principal promoter Gender"/>
                                                    <input type="hidden" name="column_name[n_ceo_gender]" value="ceo_gender"/>
                                                    <div class="input-group">
                                                        <label class="radio-inline">{!! Form::radio('n_ceo_gender','male', '', ['class'=>'required', 'id'=>'n_male', 'disabled' => 'disabled']) !!}  Male</label>
                                                        <label class="radio-inline">{!! Form::radio('n_ceo_gender', 'female', '', ['class'=>'required', 'id'=>'n_female', 'disabled' => 'disabled']) !!}  Female</label>
                                                        <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_ceo_gender]", 1, null, ['class' => 'field', 'id' => 'n_ceo_gender_check', 'onclick' => "toggleCheckBox('n_ceo_gender_check', ['n_male', 'n_female']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_ceo_gender_check','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            @endif

                            {{--C. Office Address--}}
                            <div class="panel panel-info">
                                <div class="panel-heading "><strong>C. Office Address</strong></div>
                                <div class="panel-body">
                                    <div class="clearfix padding"></div>
                                    <table aria-label="Detailed Office Address" class="table table-responsive table-bordered">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td>Field name</td>
                                            <td class="bg-yellow">Existing information (Latest BIDA Reg. Info.)</td>
                                            <td class="bg-green">Proposed information</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Division</td>

                                            <td class="light-yellow">
                                                {!! Form::select('office_division_id', $divisions, (!empty($appInfo->office_division_id) ? $appInfo->office_division_id : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id',". (!empty($appInfo->office_district_id) ? $appInfo->office_district_id : '') .")"]) !!}
                                                {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_division_id]" value="Office Division"/>
                                                <input type="hidden" name="column_name[n_office_division_id]" value="office_division_id"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_division_id', $divisions, '',['class'=>'form-control input-md', 'id' => 'n_office_division_id', 'onchange'=>"getDistrictByDivisionId('n_office_division_id', this.value, 'n_office_district_id')", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_division_id]", 1, null, ['class' => 'field', 'id' => 'n_office_division_id_check', 'onclick' => "toggleCheckBox('n_office_division_id_check', ['n_office_division_id']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_division_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>District</td>
                                            <td class="light-yellow">
                                                {!! Form::select('office_district_id', $districts, (!empty($appInfo->office_district_id) ? $appInfo->office_district_id : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'office_district_id', 'placeholder' => 'Select Division First', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id', ". (!empty($appInfo->office_thana_id) ? $appInfo->office_thana_id : '') .")"]) !!}
                                                {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_district_id]" value="Office District"/>
                                                <input type="hidden" name="column_name[n_office_district_id]" value="office_district_id"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_district_id',[],'',['class'=>'form-control input-md', 'id' => 'n_office_district_id', 'placeholder' => 'Select Division First', 'onchange'=>"getThanaByDistrictId('n_office_district_id', this.value, 'n_office_thana_id')", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_district_id]", 1, null, ['class' => 'field', 'id' => 'n_office_district_id_check', 'onclick' => "toggleCheckBox('n_office_district_id_check', ['n_office_district_id']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_district_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Police Station</td>
                                            <td class="light-yellow">
                                                {!! Form::select('office_thana_id', $thana, (!empty($appInfo->office_thana_id) ? $appInfo->office_thana_id : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'office_thana_id', 'placeholder' => 'Select District First']) !!}
                                                {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_thana_id]" value="Office Police Station"/>
                                                <input type="hidden" name="column_name[n_office_thana_id]" value="office_thana_id"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_thana_id',[],'',['class'=>'form-control input-md', 'id' => 'n_office_thana_id', 'placeholder' => 'Select District First', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_thana_id]", 1, null, ['class' => 'field', 'id' => 'n_office_thana_id_check', 'onclick' => "toggleCheckBox('n_office_thana_id_check', ['n_office_thana_id']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Post Office</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_post_office', (!empty($appInfo->office_post_office) ? $appInfo->office_post_office : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'office_post_office']) !!}
                                                {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_post_office]" value="Office Post Office"/>
                                                <input type="hidden" name="column_name[n_office_post_office]" value="office_post_office"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_post_office','',['class'=>'form-control input-md', 'id' => 'n_office_post_office', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_post_office]", 1, null, ['class' => 'field', 'id' => 'n_office_post_office_check', 'onclick' => "toggleCheckBox('n_office_post_office_check', ['n_office_post_office']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Post Code</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_post_code', (!empty($appInfo->office_post_code) ? $appInfo->office_post_code : ''),['class'=>'form-control input-md cusReadonly alphaNumeric', 'id' => 'office_post_code']) !!}
                                                {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_post_code]" value="Office Post Code"/>
                                                <input type="hidden" name="column_name[n_office_post_code]" value="office_post_code"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_post_code','',['class'=>'form-control input-md alphaNumeric', 'id' => 'n_office_post_code', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_post_code]", 1, null, ['class' => 'field', 'id' => 'n_office_post_code_check', 'onclick' => "toggleCheckBox('n_office_post_code_check', ['n_office_post_code']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_office_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_address', (!empty($appInfo->office_address) ? $appInfo->office_address : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'office_address']) !!}
                                                {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_address]" value="Office Address"/>
                                                <input type="hidden" name="column_name[n_office_address]" value="office_address"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_address','',['class'=>'form-control input-md', 'id' => 'n_office_address', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_address]", 1, null, ['class' => 'field', 'id' => 'n_office_address_check', 'onclick' => "toggleCheckBox('n_office_address_check', ['n_office_address']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_office_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Telephone No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_telephone_no', (!empty($appInfo->office_telephone_no) ? $appInfo->office_telephone_no : ''),['class'=>'form-control input-md cusReadonly number-width', 'id' => 'office_telephone_no']) !!}
                                                {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_telephone_no]" value="Office Telephone No."/>
                                                <input type="hidden" name="column_name[n_office_telephone_no]" value="office_telephone_no"/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_office_telephone_no','',['class'=>'form-control input-md', 'id' => 'n_office_telephone_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                        {!! Form::checkbox("toggleCheck[n_office_telephone_no]", 1, null, ['class' => 'field', 'id' => 'n_office_telephone_no_check', 'onclick' => "toggleCheckBox('n_office_telephone_no_check', ['n_office_telephone_no']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mobile No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_mobile_no', (!empty($appInfo->office_mobile_no) ? $appInfo->office_mobile_no : ''),['class'=>'form-control input-md cusReadonly number-width', 'id' => 'office_mobile_no']) !!}
                                                {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_mobile_no]" value="Office Mobile No."/>
                                                <input type="hidden" name="column_name[n_office_mobile_no]" value="office_mobile_no"/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_office_mobile_no','',['class'=>'form-control input-md', 'id' => 'n_office_mobile_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                        {!! Form::checkbox("toggleCheck[n_office_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_office_mobile_no_check', 'onclick' => "toggleCheckBox('n_office_mobile_no_check', ['n_office_mobile_no']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_office_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fax No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_fax_no', (!empty($appInfo->office_fax_no) ? $appInfo->office_fax_no : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'office_fax_no']) !!}
                                                {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_fax_no]" value="Office Fax No."/>
                                                <input type="hidden" name="column_name[n_office_fax_no]" value="office_fax_no"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_fax_no','',['class'=>'form-control input-md', 'id' => 'n_office_fax_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_fax_no]", 1, null, ['class' => 'field', 'id' => 'n_office_fax_no_check', 'onclick' => "toggleCheckBox('n_office_fax_no_check', ['n_office_fax_no']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Email </td>
                                            <td class="light-yellow">
                                                {!! Form::email('office_email', (!empty($appInfo->office_email) ? $appInfo->office_email : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'office_email']) !!}
                                                {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_office_email]" value="Office Email"/>
                                                <input type="hidden" name="column_name[n_office_email]" value="office_email"/>
                                                <div class="input-group">
                                                    {!! Form::email('n_office_email','',['class'=>'form-control input-md', 'id' => 'n_office_email', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                        {!! Form::checkbox("toggleCheck[n_office_email]", 1, null, ['class' => 'field', 'id' => 'n_office_email_check', 'onclick' => "toggleCheckBox('n_office_email_check', ['n_office_email']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            {{--D. Factory Address--}}
                            @if($appInfo->service_type == 1 || $appInfo->service_type == 2 || $appInfo->service_type == 3) {{-- 1 = local, 2 = joint, 3 = foreign --}}
                                <div class="panel panel-info">
                                <div class="panel-heading "><strong>D. Factory Address</strong></div>
                                <div class="panel-body">
                                    <div class="clearfix padding"></div>
                                    <table aria-label="Detailed Factory Address" class="table table-responsive table-bordered">
                                        <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"  scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td>Field name</td>
                                            <td class="bg-yellow">Existing information (Latest BIDA Reg. Info.)</td>
                                            <td class="bg-green">Proposed information</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>District</td>
                                            <td class="light-yellow">
                                                {!! Form::select('factory_district_id', $districts, (!empty($appInfo->factory_district_id) ? $appInfo->factory_district_id : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'factory_district_id', 'onchange'=>"getThanaByDistrictId('factory_district_id', this.value, 'factory_thana_id', ". (!empty($appInfo->factory_thana_id) ? $appInfo->factory_thana_id : '') .")"]) !!}
                                                {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_district_id]" value="Factory district"/>
                                                <input type="hidden" name="column_name[n_factory_district_id]" value="factory_district_id"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_factory_district_id', $districts,'',['class'=>'form-control input-md', 'id' => 'n_factory_district_id', 'onchange'=>"getThanaByDistrictId('n_factory_district_id', this.value, 'n_factory_thana_id')", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_district_id]", 1, null, ['class' => 'field', 'id' => 'n_factory_district_id_check', 'onclick' => "toggleCheckBox('n_factory_district_id_check', ['n_factory_district_id']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_district_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Police Station</td>
                                            <td class="light-yellow">
                                                {!! Form::select('factory_thana_id', $thana, (!empty($appInfo->factory_thana_id) ? $appInfo->factory_thana_id : ''),['class'=>'form-control input-md cusReadonly', 'placeholder' => 'Select District First','id' => 'factory_thana_id']) !!}
                                                {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_thana_id]" value="Factory police station"/>
                                                <input type="hidden" name="column_name[n_factory_thana_id]" value="factory_thana_id"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_factory_thana_id',[],'',['class'=>'form-control input-md', 'id' => 'n_factory_thana_id', 'placeholder' => 'Select District First', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_thana_id]", 1, null, ['class' => 'field', 'id' => 'n_factory_thana_id_check', 'onclick' => "toggleCheckBox('n_factory_thana_id_check', ['n_factory_thana_id']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Post Office</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_post_office', (!empty($appInfo->factory_post_office) ? $appInfo->factory_post_office : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'factory_post_office']) !!}
                                                {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_post_office]" value="Factory post office"/>
                                                <input type="hidden" name="column_name[n_factory_post_office]" value="factory_post_office"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_factory_post_office','',['class'=>'form-control input-md', 'id' => 'n_factory_post_office', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_post_office]", 1, null, ['class' => 'field', 'id' => 'n_factory_post_office_check', 'onclick' => "toggleCheckBox('n_factory_post_office_check', ['n_factory_post_office']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Post Code</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_post_code', (!empty($appInfo->factory_post_code) ? $appInfo->factory_post_code : ''),['class'=>'form-control input-md cusReadonly alphaNumeric', 'id' => 'factory_post_code']) !!}
                                                {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_post_code]" value="Factory post code"/>
                                                <input type="hidden" name="column_name[n_factory_post_code]" value="factory_post_code"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_factory_post_code','',['class'=>'form-control input-md alphaNumeric', 'id' => 'n_factory_post_code', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_post_code]", 1, null, ['class' => 'field', 'id' => 'n_factory_post_code_check', 'onclick' => "toggleCheckBox('n_factory_post_code_check', ['n_factory_post_code']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_address', (!empty($appInfo->factory_address) ? $appInfo->factory_address : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'factory_address']) !!}
                                                {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_address]" value="Factory address"/>
                                                <input type="hidden" name="column_name[n_factory_address]" value="factory_address"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_factory_address','',['class'=>'form-control input-md', 'id' => 'n_factory_address', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_address]", 1, null, ['class' => 'field', 'id' => 'n_factory_address_check', 'onclick' => "toggleCheckBox('n_factory_address_check', ['n_factory_address']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Telephone No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_telephone_no', (!empty($appInfo->factory_telephone_no) ? $appInfo->factory_telephone_no : ''),['class'=>'form-control input-md cusReadonly number-width', 'id' => 'factory_telephone_no']) !!}
                                                {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_telephone_no]" value="Factory telephone no."/>
                                                <input type="hidden" name="column_name[n_factory_telephone_no]" value="factory_telephone_no"/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_factory_telephone_no','',['class'=>'form-control input-md ', 'id' => 'n_factory_telephone_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                            {!! Form::checkbox("toggleCheck[n_factory_telephone_no]", 1, null, ['class' => 'field', 'id' => 'n_factory_telephone_no_check', 'onclick' => "toggleCheckBox('n_factory_telephone_no_check', ['n_factory_telephone_no']);"]) !!}
                                                            </span>
                                                </div>
                                                {!! $errors->first('n_factory_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mobile No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_mobile_no', (!empty($appInfo->factory_mobile_no) ? $appInfo->factory_mobile_no : ''),['class'=>'form-control input-md cusReadonly number-width', 'id' => 'factory_mobile_no']) !!}
                                                {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_mobile_no]" value="Factory mobile no."/>
                                                <input type="hidden" name="column_name[n_factory_mobile_no]" value="factory_mobile_no"/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_factory_mobile_no','',['class'=>'form-control input-md', 'id' => 'n_factory_mobile_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                            {!! Form::checkbox("toggleCheck[n_factory_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_factory_mobile_no_check', 'onclick' => "toggleCheckBox('n_factory_mobile_no_check', ['n_factory_mobile_no']);"]) !!}
                                                            </span>
                                                </div>
                                                {!! $errors->first('n_factory_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fax No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_fax_no', (!empty($appInfo->factory_fax_no) ? $appInfo->factory_fax_no : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'factory_fax_no']) !!}
                                                {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_fax_no]" value="Factory fax no."/>
                                                <input type="hidden" name="column_name[n_factory_fax_no]" value="factory_fax_no"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_factory_fax_no','',['class'=>'form-control input-md', 'id' => 'n_factory_fax_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_fax_no]", 1, null, ['class' => 'field', 'id' => 'n_factory_fax_no_check', 'onclick' => "toggleCheckBox('n_factory_fax_no_check', ['n_factory_fax_no']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_fax_no', (!empty($appInfo->factory_email) ? $appInfo->factory_email : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'factory_email']) !!}
                                                {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_email]" value="Factory fax no."/>
                                                <input type="hidden" name="column_name[n_factory_email]" value="factory_email"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_factory_email','',['class'=>'form-control input-md', 'id' => 'n_factory_email', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_email]", 1, null, ['class' => 'field', 'id' => 'n_factory_email_check', 'onclick' => "toggleCheckBox('n_factory_email_check', ['n_factory_email']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mouja No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('factory_mouja', (!empty($appInfo->factory_mouja) ? $appInfo->factory_mouja : ''),['class'=>'form-control input-md cusReadonly', 'id' => 'factory_mouja']) !!}
                                                {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="label_name[n_factory_mouja]" value="Factory fax no."/>
                                                <input type="hidden" name="column_name[n_factory_mouja]" value="factory_mouja"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_factory_mouja','',['class'=>'form-control input-md', 'id' => 'n_factory_mouja', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_factory_mouja]", 1, null, ['class' => 'field', 'id' => 'n_factory_mouja_check', 'onclick' => "toggleCheckBox('n_factory_mouja_check', ['n_factory_mouja']);"]) !!}
                                                        </span>
                                                </div>
                                                {!! $errors->first('n_factory_mouja','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            @endif
                            <div class="pull-right" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md" value="Submit" name="actionBtn">
                                    Update
                                    <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title="" data-original-title="After clicking this button will submit the application. After approved another IT help desk or System admit the basic info will be updated." aria-describedby="tooltip"></i>
                                </button>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-script')
    {{--Datepicker js--}}
    <script src="{{ asset("vendor/datepicker/datepicker.min.js") }}"></script>
    <script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>

    <script type="text/javascript">

        $(function () {
            $("#changeBasicInfoForm").valid();
            $(".cusReadonly").attr('readonly', true);
            $(".cusReadonly option:not(:selected)").remove();
            $(".cusReadonly:radio:not(:checked)").attr('disabled', true);
            $(".cusReadonly:checkbox:not(:checked)").attr('disabled', true);


            $("#service_type").change(function (e) {
                var service_value = this.value;
                if (service_value == 5) { // 5 = Registered Commercial Offices
                    $("#reg_commercial_office").show();
                }else {
                    $("#reg_commercial_office").hide();
                }
            });
            $('#service_type').trigger('change');

             $("#n_service_type").change(function (e) {
                var service_value = this.value;
                if (service_value == 5) { // 5 = Registered Commercial Offices
                    $("#n_reg_commercial_office").show();
                }else {
                    $("#n_reg_commercial_office").hide();
                }
            });
            $('#n_service_type').trigger('change');       

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
                separateDialCode: true
            });

            $("#factory_telephone_no").intlTelInput({
                hiddenInput: "factory_telephone_no",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true
            });

            // Datepicker Plugin initialize
            $('.datepicker').datepicker({
                outputFormat: 'dd-MMM-y',
                // daysOfWeekDisabled: [5,6],
                theme : 'blue',
            });

            $('#n_ceo_dob').datepicker('disable');

            $('#submitForm').click(function () {
                let atLeastOneChecked = $('input:checkbox.field').is(':checked');
                if (atLeastOneChecked) {
                    return form.valid();
                } else {
                    swal({type: 'error', text: "In order to Proceed please select at least one field for amendment."});
                    return false;
                }
            });

            $('#ceo_country_id').change(function (e) {
                var country_id = this.value;
                if (country_id == '18') {
                    $("#BDNIDExistingField").removeClass('hidden');
                    $("#foreignExistingPassportField").addClass('hidden');

                    $("#BDExistingTown").removeClass('hidden');
                    $("#foreignExistingState").addClass('hidden');

                    $("#BDExistingDistrict").removeClass('hidden');
                    $("#foreignExistingCity").addClass('hidden');


                } else {
                    $("#BDNIDExistingField").addClass('hidden');
                    $("#foreignExistingPassportField").removeClass('hidden');

                    $("#BDExistingTown").addClass('hidden');
                    $("#foreignExistingState").removeClass('hidden');

                    $("#BDExistingDistrict").addClass('hidden');
                    $("#foreignExistingCity").removeClass('hidden');
                }
            });

            $('#n_ceo_country_id').change(function (e) {
                var n_ceo_country_id = this.value;
                if (n_ceo_country_id == '18') {
                    $("#BDNIDProposedField").removeClass('hidden');
                    $("#foreignProposedPassportField").addClass('hidden');

                    $("#BDProposedTown").removeClass('hidden');
                    $("#foreignProposedState").addClass('hidden');

                    $("#BDProposedDistrict").removeClass('hidden');
                    $("#foreignProposedCity").addClass('hidden');

                    if ($('#n_ceo_nid_no_check').is(':not(:checked)')) {
                        $('#n_ceo_nid_no_check').click();
                    }

                    if ($('#n_ceo_district_id_check').is(':not(:checked)')) {
                        $('#n_ceo_district_id_check').click();
                    }

                    if ($('#n_ceo_thana_id_check').is(':not(:checked)')) {
                        $('#n_ceo_thana_id_check').click();
                    }

                } else {
                    $("#BDNIDProposedField").addClass('hidden');
                    $("#foreignProposedPassportField").removeClass('hidden');

                    $("#BDProposedTown").addClass('hidden');
                    $("#foreignProposedState").removeClass('hidden');

                    $("#BDProposedDistrict").addClass('hidden');
                    $("#foreignProposedCity").removeClass('hidden');

                    if (n_ceo_country_id != "") {
                        if ($('#n_ceo_passport_no_check').is(':not(:checked)')) {
                            $('#n_ceo_passport_no_check').click();
                        }

                        if ($('#n_ceo_City_check').is(':not(:checked)')) {
                            $('#n_ceo_City_check').click();
                        }

                        if ($('#n_ceo_state_check').is(':not(:checked)')) {
                            $('#n_ceo_state_check').click();
                        }
                    }
                }
            });

            $('#ceo_country_id').trigger('change');
            $('#n_ceo_country_id').trigger('change');

        });

        function toggleCheckBox(boxId, newFieldId) {
            $.each(newFieldId, function (id, val) {
                if (document.getElementById(boxId).checked) {
                    document.getElementById(val).disabled = false;
                    if (val == 'n_male' || val == 'n_female'){ //for radio button
                        $("#" + val).attr("disabled", false);
                    }
                    var field = document.getElementById(val);
                    $(field).addClass("required");

                    if (val == 'n_ceo_dob') {
                        $("#" + val).datepicker('enable');
                    }

                } else {
                    document.getElementById(val).disabled = true;
                    if (val == 'n_male' || val == 'n_female'){//for radio button
                        $("#" + val).attr("disabled", true);
                        $("#" + val).attr('checked', false);
                    }

                    var field = document.getElementById(val);
                    $(field).removeClass("required");
                    $(field).removeClass("error");
                    $(field).val("");

                    if (val == 'n_ceo_dob') {
                        $("#" + val).datepicker('disable');
                    }
                }
            });
        }

    </script>
@endsection