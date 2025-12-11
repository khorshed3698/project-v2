<?php
$accessMode = ACL::getAccsessRight('WorkPermitAmendment');
if (!ACL::isAllowed($accessMode, '-A-')) {
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

    .bg-green{
        background-color: rgba(103, 219, 56, 1);
    }
    .bg-yellow{
        background-color: rgba(246, 209, 15, 1);
    }
    .light-green{
        background-color: rgba(223, 240, 216, 1);
    }
    .light-yellow{
        background-color: rgba(252, 248, 227, 1);
    }

    td.fixed-width {
        width: 18% !important;
    }

    .iti__country-list {
        z-index: 999999;
    }

    .iti__selected-flag {
        z-index: 9999;
    }
    .mobile-plugin {
        display: flex;
    }

    .padding{
        padding: 5px;
    }

    #previous-info > tbody > tr:last-child {
        border-bottom: 1px solid #ddd;
    }

    .n_datepicker_row {
        width: 100%;
    }
    .n_datepicker_icon_border {
        border-radius: 0px;
    }
    .n_datepicker_checkbox_div {
        width: 10%;
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
                            <h5><strong>Application for Work Permit Amendment</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/work_permit_amendment.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'work-permit-amendment/store','method' => 'post','id' => 'WorkPermitAmendmentForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        <input type="hidden" name="ref_app_approve_date" value="{{ (Session::get('wpneInfo.approved_date') ? Session::get('wpneInfo.approved_date') : '') }}">

                        <h3 class="stepHeader">Basic Instructions</h3>
                        <fieldset>
                            <legend class="d-none">Basic Instructions</legend>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic Instructions</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                {!! Form::label('is_approval_online','Did you receive last work-permit through online OSS?',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <label class="radio-inline">{!! Form::radio('is_approval_online','yes', (Session::get('wpneInfo.is_approval_online') == 'yes' ? true :false), ['class'=>'custom_readonly required helpTextRadio', 'id' => 'is_approval_online_yes', 'onclick' => 'wpApplication(this.value)']) !!}  Yes</label>
                                                    <label class="radio-inline">{!! Form::radio('is_approval_online', 'no', (Session::get('wpneInfo.is_approval_online') == 'no' ? true :false), ['class'=>'custom_readonly required', 'id' => 'is_approval_online_no', 'onclick' => 'wpApplication(this.value)']) !!}  No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div id="ref_app_tracking_no_div" class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                {!! Form::label('ref_app_tracking_no','Please give your approved work permit reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        {!! Form::text('ref_app_tracking_no', Session::get('wpneInfo.ref_app_tracking_no'), ['data-rule-maxlength'=>'100', 'class' => 'form-control custom_readonly input-sm']) !!}
                                                        {!! $errors->first('ref_app_tracking_no','<span class="help-block">:message</span>') !!}
                                                        <span class="input-group-btn">
                                                            @if(Session::get('wpneInfo'))
                                                                <button type="submit" class="btn btn-danger btn-sm" value="clean_load_data" name="actionBtn">Clear Loaded Data</button>
                                                                <a href="{{ Session::get('wpneInfo.certificate_link') }}" target="_blank" rel="noopener" class="btn btn-success btn-sm">View Certificate</a>
                                                            @else
                                                                <button type="submit" class="btn btn-success btn-sm" value="searchWPNinfo" name="searchWPNinfo" id="searchWPNinfo">Load Work Permit Data</button>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <small class="text-danger">N.B.: Once you save or submit the application, the Work Permit tracking no cannot be changed anymore.</small>
                                                </div>
                                            </div>
                                            <div id="manually_approved_no_div" class="col-md-12 hidden {{$errors->has('manually_approved_wp_no') ? 'has-error': ''}} ">
                                                {!! Form::label('manually_approved_wp_no','Please give your manually approved work permit reference  No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('manually_approved_wp_no', '', ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('manually_approved_wp_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div id="issue_date_of_first_div"
                                                 class="col-md-12 {{$errors->has('issue_date_of_first_wp') ? 'has-error': ''}}">

                                                {!! Form::label('issue_date_of_first_wp','Effective date of the previous work permit',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('issue_date_of_first_wp', (!empty(Session::get('wpneInfo.approved_duration_start_date')) && Session::get('wpneInfo.approved_duration_start_date') != '' ? date('d-M-Y', strtotime(Session::get('wpneInfo.approved_duration_start_date'))) :''),['class' => 'form-control custom_readonly input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}

                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('issue_date_of_first_wp','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Applicant Details</h3>
                        <fieldset>

                            {{-- Common Basic Information By Company Id --}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic Company Information (Non editable info. pulled from the basic information provided at the first time by your company)</strong></div>
                                <div class="panel-body">
                                    {{-- Company Information: --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Company Information:</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('department_id') ? 'has-error': ''}}">
                                                    {!! Form::label('department_id','Department',['class'=>'col-md-3 text-left']) !!}
                                                    <div class="col-md-9">
                                                        <input class="form-control input-md" type="text" value="{{ $basicInfo->department }}" readonly>
                                                        {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name','Name of Organization in English (Proposed)',['class'=>'col-md-3 text-left']) !!}
                                                    <div class="col-md-9">
                                                        <input class="form-control input-md" type="text" value="{{ $basicInfo->company_name }}" readonly>
                                                        {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name_bn','Name of Organization in Bangla (Proposed)',['class'=>'col-md-3 text-left']) !!}
                                                    <div class="col-md-9">
                                                        <input class="form-control input-md" type="text" value="{{ $basicInfo->company_name_bn }}" readonly>
                                                        {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('service_type') ? 'has-error': ''}}">
                                                    {!! Form::label('service_type','Desired Service from BIDA',['class'=>'col-md-3 text-left', 'id'=> 'service_type_label']) !!}
                                                    <div class="col-md-9">
                                                        <input class="form-control input-md" type="text" value="{{ $basicInfo->service_name }}" readonly>
                                                        {!! $errors->first('service_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($basicInfo->service_type == 5)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        {!! Form::label('reg_commercial_office','Commercial office type', ['class'=>'col-md-3 text-left']) !!}
                                                        <div class="col-md-9">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->reg_commercial_office_name }}" readonly>
                                                            {!! $errors->first('reg_commercial_office','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($basicInfo->business_category == 1)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-3 text-left']) !!}
                                                        <div class="col-md-9">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ea_ownership_status }}" readonly>
                                                            {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                    {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-3 text-left']) !!}
                                                    <div class="col-md-9">
                                                        <input class="form-control input-md" type="text" value="{{ $basicInfo->ea_organization_type }}" readonly>
                                                        {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($basicInfo->ea_organization_type_id == 14 && $basicInfo->business_category ==2)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('organization_type_other') ? 'has-error': ''}}">
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-9">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->organization_type_other }}" readonly>
                                                            {!! $errors->first('organization_type_other','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif


                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                                                    {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                                                    <div class="col-md-9">
                                                        <span style="height: 100%; background: #eee;" class="form-control input-md">{{ $basicInfo->major_activities }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    {{-- Start business category --}}
                                    @if($basicInfo->business_category == 2)
                                        {{--Information of Responsible Person--}}
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Information of Responsible Person:</legend>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_country }}" readonly>
                                                            {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_full_name }}" readonly>
                                                            {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    @if($basicInfo->ceo_country_id == 18)
                                                        <div class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_nid }}" readonly>
                                                                {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6 {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_passport_no }}" readonly>
                                                                {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_designation }}" readonly>
                                                            {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input id="ceo_mobile_no" class="form-control input-md" type="text" value="{{ $basicInfo->ceo_mobile_no }}" readonly>
                                                            {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_email','Email ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_email }}" readonly>
                                                            {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_gender') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_gender','Gender', ['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_gender }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-xs-6">
                                                        {!! Form::label('ceo_auth_letter','Authorization Letter', ['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <a target="_blank" rel="noopener" class="btn btn-xs btn-primary" title="Authorization Letter"
                                                               href="{{ URL::to('users/upload/'.$basicInfo->ceo_auth_letter) }}">
                                                                <i class="fa fa-file-pdf-o"></i>
                                                                Authorization Letter
                                                            </a>
                                                            <input type="hidden" name="ceo_auth_letter" value="{{ $basicInfo->ceo_auth_letter }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    @else
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Information of Principal Promoter/ Chairman/ Managing Director/ State CEO:</legend>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_country }}" readonly>
                                                            {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5']) !!}
                                                        <div class=" col-md-7">
                                                            <div class="datepicker input-group date" data-date-format="dd-mm-yyyy">
                                                                <input class="form-control input-md" type="text" value="{{ date('d-M-Y', strtotime($basicInfo->ceo_dob)) }}" readonly>
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    @if($basicInfo->ceo_country_id == 18)
                                                        <div class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_nid }}" readonly>
                                                                {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6 {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_passport_no }}" readonly>
                                                                {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_designation }}" readonly>
                                                            {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_full_name }}" readonly>
                                                            {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    @if($basicInfo->ceo_country_id == 18)
                                                        <div class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_district_id','District/ City/ State ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_district_name }}" readonly>
                                                                {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6 {{$errors->has('ceo_city') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_city','District/ City/ State',['class'=>'text-left  col-md-5']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_city }}" readonly>
                                                                {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    @if($basicInfo->ceo_country_id == 18)
                                                        <div class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_thana_id','Police Station/ Town ',['class'=>'col-md-5 text-left','placeholder'=>'Select district first']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_thana_name }}" readonly>
                                                                {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-6 {{$errors->has('ceo_state') ? 'has-error': ''}}">
                                                            {!! Form::label('ceo_state','State/ Province',['class'=>'text-left  col-md-5']) !!}
                                                            <div class="col-md-7">
                                                                <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_state }}" readonly>
                                                                {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_post_code','Post/ Zip Code ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_post_code }}" readonly>
                                                            {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_address') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_address }}" readonly>
                                                            {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input id="ceo_telephone_no" class="form-control input-md" type="text" value="{{ $basicInfo->ceo_telephone_no }}" readonly>
                                                            {!! $errors->first('ceo_telephone_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input id="ceo_mobile_no" class="form-control input-md" type="text" value="{{ $basicInfo->ceo_mobile_no }}" readonly>
                                                            {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left', 'id' => 'ceo_father_label']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_father_name }}" readonly>
                                                            {!! $errors->first('ceo_father_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_email','Email ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_email }}" readonly>
                                                            {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left', 'id' => 'ceo_mother_label']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_mother_name }}" readonly>
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
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_fax_no }}" readonly>
                                                            {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_spouse_name }}" readonly>
                                                            {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('ceo_gender') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_gender','Gender', ['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->ceo_gender }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    @endif

                                    {{--2 = industrial department --}}
                                    @if($basicInfo->business_category != 2 && $basicInfo->department_id == 2)
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Factory Address:</legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left','id' => 'factory_district_label']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_district_name }}" readonly>
                                                            {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_thana_id','Police Station', ['class'=>'col-md-5 text-left', 'id' => 'factory_thana_label']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_thana_name }}" readonly>
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
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_post_office }}" readonly>
                                                            {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left', 'id'=>'factory_post_code_label']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md alphaNumeric" type="text" value="{{ $basicInfo->factory_post_code }}" readonly>
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
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_address }}" readonly>
                                                            {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_telephone_no }}" readonly>
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
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_mobile_no }}" readonly>
                                                            {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_fax_no }}" readonly>
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
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_email }}" readonly>
                                                            {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left', 'id'=>'factory_mouja_label']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_mouja }}" readonly>
                                                            {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    @endif
                                </div>
                            </div>
                            {{--end basic company info--}}


                            {{--(Start) CEo information section--}}
{{--                            <div class="panel panel-info">--}}
{{--                                <div class="panel-heading "><strong>Information of Principal Promoter/Chairman/Managing Director/CEO/Country Manager</strong></div>--}}
{{--                                <div class="panel-body">--}}
{{--                                    <table class="table table-responsive table-bordered" aria-label="Detailed CEo information Data Table">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                        <th aria-hidden="true" scope="col"></th>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Field name</td>--}}
{{--                                            <td class="bg-yellow">Existing information (Latest Work Permit Info.)</td>--}}
{{--                                            <td class="bg-green">Proposed information</td>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>Country</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::select('ceo_country_id', $countries, Session::get('wpneInfo.ceo_country_id'), ['class' => 'form-control custom_readonly input-md ','id'=>'ceo_country_id']) !!}--}}
{{--                                                {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_country_id]" value="Principal promoter country"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_ceo_country_id', $countries, '', ['class' => 'form-control  input-md ','id'=>'n_ceo_country_id', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_country_id]", 1, null, ['class' => 'field', 'id' => 'n_ceo_country_id_check', 'onclick' => "toggleCheckBox('n_ceo_country_id_check', ['n_ceo_country_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_country_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Date of Birth</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                <div class="datepicker input-group date">--}}
{{--                                                    {!! Form::text('ceo_dob', (!empty(Session::get('wpneInfo.ceo_dob')) && Session::get('wpneInfo.ceo_dob') != '0000-00-00' ? date('d-M-Y', strtotime(Session::get('wpneInfo.ceo_dob'))) : ''), ['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_dob', 'placeholder'=>'dd-mm-yyyy']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                        <span class="fa fa-calendar"></span>--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}

{{--                                                {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_dob]" value="Principal promoter date of birth"/>--}}
{{--                                                <div style="display: flex">--}}
{{--                                                    <div class="input-group datepicker n_datepicker_row" data-date-format="dd-mm-yyyy">--}}
{{--                                                        {!! Form::text('n_ceo_dob', '', ['class'=>'form-control input-md date', 'id' => 'n_ceo_dob', 'placeholder'=>'dd-mm-yyyy', 'disabled' => 'disabled']) !!}--}}
{{--                                                        <span class="input-group-addon n_datepicker_icon_border"><span--}}
{{--                                                                    class="glyphicon glyphicon-calendar"></span></span>--}}
{{--                                                    </div>--}}
{{--                                                    <span class="input-group-addon n_datepicker_checkbox_div">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_ceo_dob]", 1, null, ['class' => 'n_datepicker_checkbox', 'id' => 'n_ceo_dob_check', 'onclick' => "toggleCheckBox('n_ceo_dob_check', ['n_ceo_dob']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_dob','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>NID/ Passport No.</td>--}}

{{--                                            <td class="light-yellow hidden" id="foreignExistingPassportField">--}}
{{--                                                {!! Form::text('ceo_passport_no', Session::get('wpneInfo.ceo_passport_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_passport_no', 'placeholder' => 'Passport No.']) !!}--}}
{{--                                                {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-yellow hidden" id="BDNIDExistingField">--}}
{{--                                                {!! Form::text('ceo_nid', Session::get('wpneInfo.ceo_nid'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_nid', 'placeholder' => 'NID']) !!}--}}
{{--                                                {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}

{{--                                            <td class="light-green hidden" id="foreignProposedPassportField">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_passport_no]" value="Principal promoter passport no."/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_passport_no','',['class'=>'form-control input-md', 'placeholder' => 'Passport No.', 'id' => 'n_ceo_passport_no', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_passport_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_passport_no_check', 'onclick' => "toggleCheckBox('n_ceo_passport_no_check', ['n_ceo_passport_no']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_passport_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green hidden" id="BDNIDProposedField">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_nid]" value="Principal promoter NID"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_nid','',['class'=>'form-control input-md', 'placeholder' => 'NID', 'id' => 'n_ceo_nid', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_nid]", 1, null, ['class' => 'field', 'id' => 'n_ceo_nid_no_check', 'onclick' => "toggleCheckBox('n_ceo_nid_no_check', ['n_ceo_nid']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_nid','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Designation</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_designation', Session::get('wpneInfo.ceo_designation'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_designation']) !!}--}}
{{--                                                {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_designation]" value="Principal promoter designation"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_designation','',['class'=>'form-control input-md', 'id' => 'n_ceo_designation', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_designation]", 1, null, ['class' => 'field', 'id' => 'n_ceo_designation_check', 'onclick' => "toggleCheckBox('n_ceo_designation_check', ['n_ceo_designation']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_designation','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Full Name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_full_name', Session::get('wpneInfo.ceo_full_name'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_full_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_full_name]" value="Principal promoter full name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_full_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_full_name', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_full_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_full_name_check', 'onclick' => "toggleCheckBox('n_ceo_full_name_check', ['n_ceo_full_name']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_full_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>District/ City/ State</td>--}}
{{--                                            <td class="light-yellow hidden" id="foreignExistingCity">--}}
{{--                                                {!! Form::text('ceo_city', Session::get('wpneInfo.ceo_city'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_City', 'placeholder' => 'District/ City/ State']) !!}--}}
{{--                                                {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-yellow hidden" id="BDExistingDistrict">--}}
{{--                                                {!! Form::select('ceo_district_id', $districts, Session::get('wpneInfo.ceo_district_id'),['class'=>'form-control custom_readonly input-md', 'id' => 'ceo_district_id']) !!}--}}
{{--                                                {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}

{{--                                            <td class="light-green hidden" id="foreignProposedCity">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_city]" value="Principal promoter city"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_city','',['class'=>'form-control input-md', 'id' => 'n_ceo_city', 'placeholder' => 'District/ City/ State', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_city]", 1, null, ['class' => 'field', 'id' => 'n_ceo_City_check', 'onclick' => "toggleCheckBox('n_ceo_City_check', ['n_ceo_city']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_city','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green hidden" id="BDProposedDistrict">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_district_id]" value="Principal promoter district"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_ceo_district_id', $districts, '',['class'=>'form-control input-md', 'id' => 'n_ceo_district_id', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_district_id]", 1, null, ['class' => 'field', 'id' => 'n_ceo_district_id_check', 'onclick' => "toggleCheckBox('n_ceo_district_id_check', ['n_ceo_district_id']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>State/ Province/ Police station/ Town</td>--}}
{{--                                            <td class="light-yellow hidden" id="foreignExistingState">--}}
{{--                                                {!! Form::text('ceo_state', Session::get('wpneInfo.ceo_state'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_state', 'placeholder' => 'State/ Province']) !!}--}}
{{--                                                {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-yellow hidden" id="BDExistingTown">--}}
{{--                                                {!! Form::select('ceo_thana_id', $thana, Session::get('wpneInfo.ceo_thana_id'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_thana_id']) !!}--}}
{{--                                                {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}

{{--                                            <td class="light-green hidden" id="foreignProposedState">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_state]" value="Principal promoter state"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_state','',['class'=>'form-control input-md', 'id' => 'n_ceo_state', 'placeholder' => 'State/ Province', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_state]", 1, null, ['class' => 'field', 'id' => 'n_ceo_state_check', 'onclick' => "toggleCheckBox('n_ceo_state_check', ['n_ceo_state']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_state','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green hidden" id="BDProposedTown">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_thana_id]" value="Principal promoter police station"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_ceo_thana_id', $thana, '',['class'=>'form-control input-md', 'id' => 'n_ceo_thana_id', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_thana_id]", 1, null, ['class' => 'field', 'id' => 'n_ceo_thana_id_check', 'onclick' => "toggleCheckBox('n_ceo_thana_id_check', ['n_ceo_thana_id']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Post/ Zip Code</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_post_code', Session::get('wpneInfo.ceo_post_code'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_post_code']) !!}--}}
{{--                                                {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_post_code]" value="Principal promoter post/ zip code"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_post_code','',['class'=>'form-control input-md', 'id' => 'n_ceo_post_code', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_post_code]", 1, null, ['class' => 'field', 'id' => 'n_ceo_post_code_check', 'onclick' => "toggleCheckBox('n_ceo_post_code_check', ['n_ceo_post_code']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>House, Flat/ Apartment, Road</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_address', Session::get('wpneInfo.ceo_address'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_address']) !!}--}}
{{--                                                {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_address]" value="Principal promoter house, flat/ apartment, road"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_address','',['class'=>'form-control input-md', 'id' => 'n_ceo_address', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_address]", 1, null, ['class' => 'field', 'id' => 'n_ceo_address_check', 'onclick' => "toggleCheckBox('n_ceo_address_check', ['n_ceo_address']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Telephone No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_telephone_no', Session::get('wpneInfo.ceo_telephone_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_telephone_no']) !!}--}}
{{--                                                {!! $errors->first('ceo_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_telephone_no]" value="Principal promoter telephone no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_ceo_telephone_no','',['class'=>'form-control input-md', 'id' => 'n_ceo_telephone_no', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 6px 24px 6px 12px;">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_telephone_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_telephone_no_check', 'onclick' => "toggleCheckBox('n_ceo_telephone_no_check', ['n_ceo_telephone_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Mobile No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_mobile_no', Session::get('wpneInfo.ceo_mobile_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_mobile_no']) !!}--}}
{{--                                                {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_mobile_no]" value="Principal promoter mobile no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_ceo_mobile_no','',['class'=>'form-control input-md', 'id' => 'n_ceo_mobile_no', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_mobile_no_check', 'onclick' => "toggleCheckBox('n_ceo_mobile_no_check', ['n_ceo_mobile_no']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Email</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::email('ceo_email', Session::get('wpneInfo.ceo_email'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_email']) !!}--}}
{{--                                                {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_email]" value="Principal promoter email"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::email('n_ceo_email','',['class'=>'form-control input-md', 'id' => 'n_ceo_email', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_email]", 1, null, ['class' => 'field', 'id' => 'n_ceo_email_check', 'onclick' => "toggleCheckBox('n_ceo_email_check', ['n_ceo_email']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_email','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Fax No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_fax_no', Session::get('wpneInfo.ceo_fax_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_fax_no']) !!}--}}
{{--                                                {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_fax_no]" value="Principal promoter fax no."/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_fax_no','',['class'=>'form-control input-md', 'id' => 'n_ceo_fax_no', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_fax_no]", 1, null, ['class' => 'field', 'id' => 'n_ceo_fax_no_check', 'onclick' => "toggleCheckBox('n_ceo_fax_no_check', ['n_ceo_fax_no']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_fax_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Father's Name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_father_name', Session::get('wpneInfo.ceo_father_name'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_father_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_father_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_father_name]" value="Principal promoter father's name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_father_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_father_name', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_father_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_father_name_check', 'onclick' => "toggleCheckBox('n_ceo_father_name_check', ['n_ceo_father_name']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_father_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Mother's Name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_mother_name', Session::get('wpneInfo.ceo_mother_name'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_mother_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_mother_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_mother_name]" value="Principal promoter mother's name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_mother_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_mother_name', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_mother_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_mother_name_check', 'onclick' => "toggleCheckBox('n_ceo_mother_name_check', ['n_ceo_mother_name']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_mother_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Spouse name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_spouse_name', Session::get('wpneInfo.ceo_spouse_name'),['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_spouse_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_spouse_name]" value="Principal promoter spouse name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_spouse_name','',['class'=>'form-control input-md', 'id' => 'n_ceo_spouse_name', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_spouse_name]", 1, null, ['class' => 'field', 'id' => 'n_ceo_spouse_name_check', 'onclick' => "toggleCheckBox('n_ceo_spouse_name_check', ['n_ceo_spouse_name']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_spouse_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Gender</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                <label class="radio-inline">{!! Form::radio('ceo_gender','male', (Session::get('wpneInfo.ceo_gender') == 'Male' ? true : false), ['class'=>'custom_readonly', 'id'=>'male']) !!}  Male</label>--}}
{{--                                                <label class="radio-inline">{!! Form::radio('ceo_gender', 'female',(Session::get('wpneInfo.ceo_gender') == 'Female' ? true : false), ['class'=>'custom_readonly', 'id'=>'female']) !!}  Female</label>--}}
{{--                                                {!! $errors->first('ceo_gender','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_gender]" value="Principal promoter gender"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <label class="radio-inline">{!! Form::radio('n_ceo_gender','male', '', ['class'=>'required', 'id'=>'n_male', 'disabled' => 'disabled']) !!}  Male</label>--}}
{{--                                                    <label class="radio-inline">{!! Form::radio('n_ceo_gender', 'female', '', ['class'=>'required', 'id'=>'n_female', 'disabled' => 'disabled']) !!}  Female</label>--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_gender]", 1, null, ['class' => 'field', 'id' => 'n_ceo_gender_check', 'onclick' => "toggleCheckBox('n_ceo_gender_check', ['n_male', 'n_female']);"]) !!}--}}
{{--                                                </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_gender_check','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            {{--(End) CEO information section--}}

                            {{--(Start) office information section--}}
                            <div class="panel panel-info">
                                <div class="panel-heading "><strong>Office Address</strong></div>
                                <div class="panel-body">
                                    <table class="table table-responsive table-bordered" aria-label="Detailed office info Report">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                            <tr>
                                                <td width="30%">Field name</td>
                                                <td class="bg-yellow" width="35%">Existing information (Latest Work Permit Info.)</td>
                                                <td class="bg-green" width="35%">Proposed information</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label class="required-star">Division</label>
                                            </td>

                                            <td class="light-yellow">
                                                {!! Form::select('office_division_id', $divisions, Session::get('wpneInfo.office_division_id'),['class'=>'form-control required input-md custom_readonly', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id',". Session::get('wpneInfo.office_district_id') .")"]) !!}
                                                {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_division_id]" value="Office division"/>
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
                                            <td>
                                                <label class="required-star">District</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::select('office_district_id', $districts, Session::get('wpneInfo.office_district_id'),['class'=>'form-control required input-md custom_readonly', 'id' => 'office_district_id', 'placeholder' => 'Select Division First', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id', ". Session::get('wpneInfo.office_thana_id') .")"]) !!}
                                                {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_district_id]" value="Office district"/>
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
                                            <td>
                                                <label class="required-star">Police Station</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::select('office_thana_id', [], Session::get('wpneInfo.office_thana_id'),['class'=>'form-control required input-md custom_readonly', 'id' => 'office_thana_id', 'placeholder' => 'Select District First']) !!}
                                                {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_thana_id]" value="Office police station"/>
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
                                            <td>
                                                <label class="required-star">Post Office</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_post_office', Session::get('wpneInfo.office_post_office'),['class'=>'form-control required input-md custom_readonly', 'id' => 'office_post_office']) !!}
                                                {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_post_office]" value="Office post office"/>
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
                                            <td>
                                                <label class="required-star">Post Code</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_post_code', Session::get('wpneInfo.office_post_code'),['class'=>'form-control required input-md custom_readonly alphaNumeric', 'id' => 'office_post_code']) !!}
                                                {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_post_code]" value="Office post code"/>
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
                                            <td >
                                                <label class="required-star">Address</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_address', Session::get('wpneInfo.office_address'),['class'=>'form-control required input-md custom_readonly', 'id' => 'office_address']) !!}
                                                {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_address]" value="Office address"/>
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
                                            <td>
                                                <label>Telephone No.</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_telephone_no', Session::get('wpneInfo.office_telephone_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'office_telephone_no']) !!}
                                                {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_telephone_no]" value="Office telephone no."/>
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
                                            <td>
                                                <label class="required-star">Mobile No.</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_mobile_no', Session::get('wpneInfo.office_mobile_no'),['class'=>'form-control required input-md custom_readonly', 'id' => 'office_mobile_no']) !!}
                                                {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_mobile_no]" value="Office mobile no."/>
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
                                            <td>
                                                <label>Fax No.</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_fax_no', Session::get('wpneInfo.office_fax_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'office_fax_no']) !!}
                                                {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_fax_no]" value="Office fax no."/>
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
                                            <td>
                                                <label class="required-star">Email</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::email('office_email', Session::get('wpneInfo.office_email'),['class'=>'form-control required input-md custom_readonly', 'id' => 'office_email']) !!}
                                                {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_email]" value="Office email"/>
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
                            {{--(End) office information section--}}

                            {{--(Start) factory information section--}}
{{--                            <div class="panel panel-info">--}}
{{--                                <div class="panel-heading "><strong>Factory Address</strong></div>--}}
{{--                                <div class="panel-body">--}}
{{--                                    <table class="table table-responsive table-bordered" aria-label="Detailed factory information Report">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                        <th aria-hidden="true" scope="col"></th>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td width="30%">Field name</td>--}}
{{--                                            <td class="bg-yellow" width="35%">Existing information (Latest Work Permit Info.)</td>--}}
{{--                                            <td class="bg-green" width="35%">Proposed information</td>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>District</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::select('factory_district_id', $districts, Session::get('wpneInfo.factory_district_id'),['class'=>'form-control input-md custom_readonly', 'id' => 'factory_district_id', 'onchange'=>"getThanaByDistrictId('factory_district_id', this.value, 'factory_thana_id', ". Session::get('wpneInfo.factory_thana_id') .")"]) !!}--}}
{{--                                                {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_district_id]" value="Factory district"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_factory_district_id', $districts,'',['class'=>'form-control input-md', 'id' => 'n_factory_district_id', 'onchange'=>"getThanaByDistrictId('n_factory_district_id', this.value, 'n_factory_thana_id')", 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_district_id]", 1, null, ['class' => 'field', 'id' => 'n_factory_district_id_check', 'onclick' => "toggleCheckBox('n_factory_district_id_check', ['n_factory_district_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Police Station</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::select('factory_thana_id', [], Session::get('wpneInfo.factory_thana_id'),['class'=>'form-control input-md custom_readonly', 'placeholder' => 'Select District First','id' => 'factory_thana_id']) !!}--}}
{{--                                                {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_thana_id]" value="Factory police station"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_factory_thana_id',[],'',['class'=>'form-control input-md', 'id' => 'n_factory_thana_id', 'placeholder' => 'Select District First', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_thana_id]", 1, null, ['class' => 'field', 'id' => 'n_factory_thana_id_check', 'onclick' => "toggleCheckBox('n_factory_thana_id_check', ['n_factory_thana_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Post Office</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_post_office', Session::get('wpneInfo.factory_post_office'),['class'=>'form-control input-md custom_readonly', 'id' => 'factory_post_office']) !!}--}}
{{--                                                {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_post_office]" value="Factory post office"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_post_office','',['class'=>'form-control input-md', 'id' => 'n_factory_post_office', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_post_office]", 1, null, ['class' => 'field', 'id' => 'n_factory_post_office_check', 'onclick' => "toggleCheckBox('n_factory_post_office_check', ['n_factory_post_office']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_post_office','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Post Code</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_post_code', Session::get('wpneInfo.factory_post_code'),['class'=>'form-control input-md custom_readonly', 'id' => 'factory_post_code']) !!}--}}
{{--                                                {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_post_code]" value="Factory post code"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_post_code','',['class'=>'form-control input-md', 'id' => 'n_factory_post_code', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_post_code]", 1, null, ['class' => 'field', 'id' => 'n_factory_post_code_check', 'onclick' => "toggleCheckBox('n_factory_post_code_check', ['n_factory_post_code']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Address</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_address', Session::get('wpneInfo.factory_address'),['class'=>'form-control input-md custom_readonly', 'id' => 'factory_address']) !!}--}}
{{--                                                {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_address]" value="Factory address"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_address','',['class'=>'form-control input-md', 'id' => 'n_factory_address', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_address]", 1, null, ['class' => 'field', 'id' => 'n_factory_address_check', 'onclick' => "toggleCheckBox('n_factory_address_check', ['n_factory_address']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Telephone No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_telephone_no', Session::get('wpneInfo.factory_telephone_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'factory_telephone_no']) !!}--}}
{{--                                                {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_telephone_no]" value="Factory telephone no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_factory_telephone_no','',['class'=>'form-control input-md', 'id' => 'n_factory_telephone_no', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_telephone_no]", 1, null, ['class' => 'field', 'id' => 'n_factory_telephone_no_check', 'onclick' => "toggleCheckBox('n_factory_telephone_no_check', ['n_factory_telephone_no']);"]) !!}--}}
{{--                                                        </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Mobile No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_mobile_no', Session::get('wpneInfo.factory_mobile_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'factory_mobile_no']) !!}--}}
{{--                                                {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_mobile_no]" value="Factory mobile no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_factory_mobile_no','',['class'=>'form-control input-md', 'id' => 'n_factory_mobile_no', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_factory_mobile_no_check', 'onclick' => "toggleCheckBox('n_factory_mobile_no_check', ['n_factory_mobile_no']);"]) !!}--}}
{{--                                                        </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Fax No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_fax_no', Session::get('wpneInfo.factory_fax_no'),['class'=>'form-control input-md custom_readonly', 'id' => 'factory_fax_no']) !!}--}}
{{--                                                {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_fax_no]" value="Factory fax no."/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_fax_no','',['class'=>'form-control input-md', 'id' => 'n_factory_fax_no', 'disabled' => 'disabled']) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_factory_fax_no]", 1, null, ['class' => 'field', 'id' => 'n_factory_fax_no_check', 'onclick' => "toggleCheckBox('n_factory_fax_no_check', ['n_factory_fax_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_fax_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            {{--(End) factory information section--}}

                            {{-- (Start) Information of Expatriate/ Investor/ Employee --}}
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>
                                        Information of Expatriate/ Investor/ Employee
                                    </strong>
                                </div>
                                <div class="panel-body">

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">General information</legend>
                                        <table class="table table-bordered" id="previous-info" aria-label="Detailed General information">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                                <tr>
                                                    <td>Field name</td>
                                                    <td class="bg-yellow">Existing information (Latest Work Permit Info.)</td>
                                                    <td class="bg-green">Proposed information</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <label class="required-star">Full Name</label>
                                                </td>
                                                <td class="light-yellow">
                                                    {!! Form::text('emp_name', Session::get('wpneInfo.emp_name'), ['class'=>'form-control required input-md custom_readonly textOnly', 'id'=>"emp_name"]) !!}
                                                    {!! $errors->first('emp_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_name]" value="Full Name"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_emp_name', '', ['class'=>'form-control input-md', 'id'=>"n_emp_name", 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_name]", 1, null, ['class' => 'field', 'id' => 'n_emp_name_check', 'onclick' => "toggleCheckBox('n_emp_name_check', ['n_emp_name']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_emp_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="required-star">Position/ Designation</label>
                                                </td>
                                                <td class="light-yellow">
                                                    {!! Form::text('emp_designation', Session::get('wpneInfo.emp_designation'), ['class'=>'form-control input-md required custom_readonly', 'id'=>"emp_designation"]) !!}
                                                    {!! $errors->first('emp_designation','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_designation]" value="Position/ Designation"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_emp_designation', '', ['class'=>'form-control input-md', 'id'=>"n_emp_designation", 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_designation]", 1, null, ['class' => 'field', 'id' => 'n_emp_designation_check', 'onclick' => "toggleCheckBox('n_emp_designation_check', ['n_emp_designation']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_emp_designation','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="required-star">Passport No.</label>
                                                </td>
                                                <td class="light-yellow">
                                                    {!! Form::text('emp_passport_no', Session::get('wpneInfo.emp_passport_no'), ['data-rule-maxlength'=>'20','class'=>'form-control input-md required custom_readonly', 'id'=>"emp_passport_no"]) !!}
                                                    {!! $errors->first('emp_passport_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_passport_no]" value="Passport No."/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_emp_passport_no', '', ['class'=>'form-control input-md', 'id'=>"n_emp_passport_no", 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_passport_no]", 1, null, ['class' => 'field', 'id' => 'n_emp_passport_no_check', 'onclick' => "toggleCheckBox('n_emp_passport_no_check', ['n_emp_passport_no']);"]) !!}
                                                    </span>
                                                    </div>
                                                    {!! $errors->first('n_emp_passport_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label class="required-star">Nationality</label>
                                                </td>
                                                <td class="light-yellow">
                                                    {!! Form::select('emp_nationality_id', $nationality, Session::get('wpneInfo.emp_nationality_id'), ['placeholder' => 'Select One', 'class' => 'form-control required custom_readonly input-md ', 'id'=>'emp_nationality_id']) !!}
                                                    {!! $errors->first('emp_nationality_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_nationality_id]" value="Nationality"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_emp_nationality_id', $nationality, '', ['placeholder' => 'Select One', 'class' => 'form-control input-md','id'=>'n_emp_nationality_id', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_nationality_id]", 1, null, ['class' => 'field', 'id' => 'n_emp_nationality_id_check', 'onclick' => "toggleCheckBox('n_emp_nationality_id_check', ['n_emp_nationality_id']);"]) !!}
                                                </span>
                                                    </div>
                                                    {!! $errors->first('n_emp_nationality_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Previous work permit duration</legend>

                                        <table class="table table-bordered" aria-label="Detailed Report Data Table">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                                <tr>
                                                    <td>#</td>
                                                    <td>Information</td>
                                                    <td>Start Date <span class="required-star"></span></td>
                                                    <td>End Date <span class="required-star"></span></td>
                                                    <td>Duration (Days)</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="light-yellow">
                                                <td></td>
                                                <td class="bg-yellow">
                                                    Existing information (Latest Work Permit Info.)
                                                </td>
                                                <td>
                                                    <div class="{{$errors->has('p_duration_start_date') ? 'has-error': ''}}">
                                                        <div class="datepicker input-group date" id="pd_start_datepicker">
                                                            {!! Form::text('p_duration_start_date', (Session::get('wpneInfo.approved_duration_start_date') ? date('d-M-Y', strtotime(Session::get('wpneInfo.approved_duration_start_date'))) : ''), ['class' => 'form-control input-md custom_readonly date', 'placeholder'=>'dd-mm-yyyy', 'id' => 'p_duration_start_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('p_duration_start_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" {{$errors->has('p_duration_end_date') ? 'has-error': ''}}">
                                                        <div class="datepicker input-group date" id="pd_end_datepicker">
                                                            {!! Form::text('p_duration_end_date', (Session::get('wpneInfo.approved_duration_end_date') ? date('d-M-Y', strtotime(Session::get('wpneInfo.approved_duration_end_date'))) : ''), ['class' => 'form-control custom_readonly input-md date yellow', 'placeholder'=>'dd-mm-yyyy', 'id' => 'p_duration_end_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('p_duration_end_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" {{$errors->has('p_desired_duration') ? 'has-error': ''}}">
                                                        {!! Form::text('p_desired_duration', Session::get('wpneInfo.approved_desired_duration') ? Session::get('wpneInfo.approved_desired_duration') : '', ['class' => 'form-control custom_readonly  input-md','id' => 'p_desired_duration']) !!}
                                                        {!! $errors->first('p_desired_duration','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="light-green">
                                                <td>
                                                    <div class="input-group">
                                                        {!! Form::checkbox("toggleCheck[n_p_duration]", 1, null, ['class' => 'field', 'id' => 'n_p_duration_check', 'onclick' => "toggleCheckBox('n_p_duration_check', ['n_p_duration_start_date', 'n_p_duration_end_date', 'n_p_desired_duration']);"]) !!}
                                                    </div>
                                                </td>
                                                <td class="bg-green">
                                                    Proposed information
                                                </td>
                                                <td>
                                                    <div class=" {{$errors->has('n_p_duration_start_date') ? 'has-error': ''}}">
                                                        <input type="hidden" name="caption[n_p_duration_start_date]" value="Start Date"/>
                                                        <div>
                                                            <div id="duration_start_datepicker" class="input-group date">
                                                                {!! Form::text('n_p_duration_start_date', '', ['class' => 'form-control input-md date green', 'placeholder'=>'dd-mm-yyyy', 'disabled' => 'disabled', 'id' => 'n_p_duration_start_date']) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('n_p_duration_start_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="{{$errors->has('n_p_duration_end_date') ? 'has-error': ''}}">
                                                        <input type="hidden" name="caption[n_p_duration_end_date]" value="End Date"/>
                                                        <div>
                                                            <div id="duration_end_datepicker" class="datepicker input-group date">
                                                                {!! Form::text('n_p_duration_end_date', '', ['class' => 'form-control input-md date green', 'placeholder'=>'dd-mm-yyyy', 'disabled' => 'disabled', 'id' => 'n_p_duration_end_date']) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('n_p_duration_end_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="{{$errors->has('n_p_desired_duration') ? 'has-error': ''}}">
                                                        <input type="hidden" name="caption[n_p_desired_duration]" value="Desired Duration"/>
                                                        <div>
                                                            {!! Form::text('n_p_desired_duration', '', ['class' => 'form-control input-md green', 'id' => 'n_p_desired_duration', 'disabled' => 'disabled', 'readonly']) !!}
                                                        </div>
                                                        {!! $errors->first('n_p_desired_duration','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Compensation and benefit</legend>
                                        <div class="table-responsive" id="compensationTableId">
                                            <table class="table table-striped table-bordered" aria-label="Detailed Compensation and benefit">
                                                <thead>
                                                    <tr class="d-none">
                                                        <th aria-hidden="true" scope="col"></th>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="3"  width="28%">Salary structure</td>
                                                        <td colspan="3" class="bg-yellow">Existing Information</td>
                                                        <td colspan="3" class="bg-green">Proposed Information</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="light-yellow" width="13%">Payment</td>
                                                        <td class="light-yellow" width="13%">Amount</td>
                                                        <td class="light-yellow" width="10%">Currency</td>
                                                        <td class="light-green" width="13%">Payment</td>
                                                        <td class="light-green" width="13%">Amount</td>
                                                        <td class="light-green" width="10%">Currency</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_basic_payment_type_id]", 1, null, ['class' => 'field', 'id' => 'n_basic_payment_type_id_check', 'onclick' => "toggleCheckBox('n_basic_payment_type_id_check', ['n_basic_payment_type_id', 'n_basic_local_amount', 'n_basic_local_currency_id']);"]) !!}
                                                            </span>

                                                            <div class="form-control">
                                                                <div style="position: relative;">
                                                                    <span class="helpTextCom" id="basic_local_amount_label">a. Basic salary/ Honorarium</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_basic_salary]" value="Basic salary/ Honorarium"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('basic_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_payment_type_id', $paymentMethods, Session::get('wpneInfo.basic_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control required custom_readonly input-md cb_req_field', 'id' => 'basic_payment_type_id']) !!}
                                                            {!! $errors->first('basic_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('basic_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('basic_local_amount', Session::get('wpneInfo.basic_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md numberNoNegative cb_req_field', 'step' => '0.01', 'id'=>'basic_local_amount']) !!}
                                                            {!! $errors->first('basic_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('basic_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_local_currency_id', $currencies, Session::get('wpneInfo.basic_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control required custom_readonly input-md cb_req_field', 'id' => 'basic_local_currency_id']) !!}
                                                            {!! $errors->first('basic_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_basic_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_basic_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_basic_payment_type_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_basic_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_basic_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_basic_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative green', 'step' => '0.01', 'id'=>'n_basic_local_amount', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_basic_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_basic_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_basic_local_currency_id', $currencies, '',['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_basic_local_currency_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_basic_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_overseas_payment_type_id]", 1, null, ['class' => 'field', 'id' => 'n_overseas_payment_type_id_check', 'onclick' => "toggleCheckBox('n_overseas_payment_type_id_check', ['n_overseas_payment_type_id', 'n_overseas_local_amount', 'n_overseas_local_currency_id']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="overseas_local_amount_label">b. Overseas allowance</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_overseas_allowance]" value="Overseas allowance"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('overseas_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_payment_type_id', $paymentMethods, Session::get('wpneInfo.overseas_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md', 
                                                                'id' => 'overseas_payment_type_id', 'onchange' => "dependentRequire('overseas_payment_type_id', ['overseas_local_amount', 'overseas_local_currency_id']);"]) !!}
                                                            {!! $errors->first('overseas_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('overseas_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('overseas_local_amount', Session::get('wpneInfo.overseas_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md numberNoNegative cb_req_field', 'step' => '0.01', 
                                                                'id' => 'overseas_local_amount', 'onchange' => "dependentRequire('overseas_local_amount', ['overseas_payment_type_id', 'overseas_local_currency_id']);"]) !!}
                                                            {!! $errors->first('overseas_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('overseas_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_local_currency_id', $currencies, Session::get('wpneInfo.overseas_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md', 'id' => 'overseas_local_currency_id']) !!}
                                                            {!! $errors->first('overseas_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_overseas_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_overseas_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_overseas_payment_type_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_overseas_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_overseas_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_overseas_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative green', 'step' => '0.01', 'id' => 'n_overseas_local_amount', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_overseas_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_overseas_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_overseas_local_currency_id', $currencies, '',['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_overseas_local_currency_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_overseas_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_house_payment_type_id]", 1, null, ['class' => 'field', 'id' => 'n_house_payment_type_id_check', 'onclick' => "toggleCheckBox('n_house_payment_type_id_check', ['n_house_payment_type_id', 'n_house_local_amount', 'n_house_local_currency_id']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="house_local_amount_label">c. House rent</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_house_rent]" value="House rent"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="lightyellow">
                                                        <div class="{{ $errors->has('house_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('house_payment_type_id', $paymentMethods, Session::get('wpneInfo.house_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id' => 'house_payment_type_id', 'onchange' => "dependentRequire('house_payment_type_id', ['house_local_amount', 'house_local_currency_id']);"]) !!}
                                                            {!! $errors->first('house_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('house_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('house_local_amount', Session::get('wpneInfo.house_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md numberNoNegative cb_req_field', 'step' => '0.01',
                                                                 'id'=>'house_local_amount', 'onchange' => "dependentRequire('house_local_amount', ['house_payment_type_id', 'house_local_currency_id']);"]) !!}
                                                            {!! $errors->first('house_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('house_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('house_local_currency_id', $currencies, Session::get('wpneInfo.house_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id' => 'house_local_currency_id']) !!}
                                                            {!! $errors->first('house_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_house_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_house_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_house_payment_type_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_house_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_house_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_house_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative green', 'step' => '0.01', 'id' => 'n_house_local_amount', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_house_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_house_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_house_local_currency_id', $currencies, '',['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_house_local_currency_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_house_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_conveyance_payment_type_id]", 1, null, ['class' => 'field', 'id' => 'n_conveyance_payment_type_id_check', 'onclick' => "toggleCheckBox('n_conveyance_payment_type_id_check', ['n_conveyance_payment_type_id', 'n_conveyance_local_amount', 'n_conveyance_local_currency_id']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="conveyance_local_amount_label">d. Conveyance</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_conveyance]" value="Conveyance"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('conveyance_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_payment_type_id', $paymentMethods, Session::get('wpneInfo.conveyance_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'conveyance_payment_type_id', 'onchange' => "dependentRequire('conveyance_payment_type_id', ['conveyance_local_amount', 'conveyance_local_currency_id']);"]) !!}
                                                            {!! $errors->first('conveyance_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('conveyance_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('conveyance_local_amount', Session::get('wpneInfo.conveyance_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md numberNoNegative cb_req_field', 'step' => '0.01', 
                                                                'id' => 'conveyance_local_amount', 'onchange' => "dependentRequire('conveyance_local_amount', ['conveyance_payment_type_id', 'conveyance_local_currency_id']);"]) !!}
                                                            {!! $errors->first('conveyance_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('conveyance_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_local_currency_id', $currencies, Session::get('wpneInfo.conveyance_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'conveyance_local_currency_id']) !!}
                                                            {!! $errors->first('conveyance_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_conveyance_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_conveyance_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md green', 'id' => 'n_conveyance_payment_type_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_conveyance_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_conveyance_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_conveyance_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative green', 'step' => '0.01', 'id' => 'n_conveyance_local_amount', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_conveyance_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_conveyance_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_conveyance_local_currency_id', $currencies, '',['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_conveyance_local_currency_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_conveyance_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_medical_payment_type_id]", 1, null, ['class' => 'field', 'id' => 'n_medical_payment_type_id_check', 'onclick' => "toggleCheckBox('n_medical_payment_type_id_check', ['n_medical_payment_type_id', 'n_medical_local_amount', 'n_medical_local_currency_id']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="medical_local_amount_label">e. Medical allowance</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_medical_allowance]" value="Medical allowance"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('medical_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_payment_type_id', $paymentMethods, Session::get('wpneInfo.medical_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'medical_payment_type_id', 'onchange' => "dependentRequire('medical_payment_type_id', ['medical_local_amount', 'medical_local_currency_id']);"]) !!}
                                                            {!! $errors->first('medical_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('medical_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('medical_local_amount', Session::get('wpneInfo.medical_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md numberNoNegative cb_req_field', 'step' => '0.01', 
                                                                'id'=>'medical_local_amount', 'onchange' => "dependentRequire('medical_local_amount', ['medical_payment_type_id', 'medical_local_currency_id']);"]) !!}
                                                            {!! $errors->first('medical_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('medical_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_local_currency_id', $currencies, Session::get('wpneInfo.medical_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'medical_local_currency_id']) !!}
                                                            {!! $errors->first('medical_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_medical_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_medical_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_medical_payment_type_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_medical_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_medical_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_medical_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative green', 'step' => '0.01', 'id' => 'n_medical_local_amount', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_medical_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_medical_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_medical_local_currency_id', $currencies, '',['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_medical_local_currency_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_medical_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_ent_payment_type_id]", 1, null, ['class' => 'field', 'id' => 'n_ent_payment_type_id_check', 'onclick' => "toggleCheckBox('n_ent_payment_type_id_check', ['n_ent_payment_type_id', 'n_ent_local_amount', 'n_ent_local_currency_id']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="ent_local_amount_label">f. Entertainment allowance</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_entertainment_allowance]" value="Entertainment allowance"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('ent_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_payment_type_id', $paymentMethods, Session::get('wpneInfo.ent_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'ent_payment_type_id', 'onchange' => "dependentRequire('ent_payment_type_id', ['ent_local_amount', 'ent_local_currency_id']);"]) !!}
                                                            {!! $errors->first('ent_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('ent_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('ent_local_amount', Session::get('wpneInfo.ent_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md numberNoNegative cb_req_field', 'step' => '0.01', 
                                                                'id' => 'ent_local_amount', 'onchange' => "dependentRequire('ent_local_amount', ['ent_payment_type_id', 'ent_local_currency_id']);"]) !!}
                                                            {!! $errors->first('ent_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('ent_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_local_currency_id', $currencies, Session::get('wpneInfo.ent_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'ent_local_currency_id']) !!}
                                                            {!! $errors->first('ent_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_ent_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_ent_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_ent_payment_type_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_ent_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_ent_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_ent_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative green', 'step' => '0.01', 'id' => 'n_ent_local_amount', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_ent_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_ent_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_ent_local_currency_id', $currencies, '',['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_ent_local_currency_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_ent_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_bonus_payment_type_id]", 1, null, ['class' => 'field', 'id' => 'n_bonus_payment_type_id_check', 'onclick' => "toggleCheckBox('n_bonus_payment_type_id_check', ['n_bonus_payment_type_id', 'n_bonus_local_amount', 'n_bonus_local_currency_id']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="bonus_local_amount_label">g. Annual Bonus</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_annual_bonus]" value="Annual Bonus"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('bonus_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_payment_type_id', $paymentMethods, Session::get('wpneInfo.bonus_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'bonus_payment_type_id', 'onchange' => "dependentRequire('bonus_payment_type_id', ['bonus_local_amount', 'bonus_local_currency_id']);"]) !!}
                                                            {!! $errors->first('bonus_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('bonus_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('bonus_local_amount', Session::get('wpneInfo.bonus_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md numberNoNegative cb_req_field', 'step' => '0.01', 
                                                                'id' => 'bonus_local_amount', 'onchange' => "dependentRequire('bonus_local_amount', ['bonus_payment_type_id', 'bonus_local_currency_id']);"]) !!}
                                                            {!! $errors->first('bonus_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('bonus_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_local_currency_id', $currencies, Session::get('wpneInfo.bonus_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'bonus_local_currency_id']) !!}
                                                            {!! $errors->first('bonus_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_bonus_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_bonus_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_bonus_payment_type_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_bonus_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_bonus_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_bonus_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative green', 'step' => '0.01', 'id' => 'n_bonus_local_amount', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_bonus_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_bonus_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_bonus_local_currency_id', $currencies, '',['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_bonus_local_currency_id', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_bonus_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_other_benefits]", 1, null, ['class' => 'field', 'id' => 'n_other_benefits_check', 'onclick' => "toggleCheckBox('n_other_benefits_check', ['n_other_benefits']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="other_benefits_label">h. Other fringe benefits (if any)</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_other_fringe_benefits]" value="Others"/>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td colspan="3" class="light-yellow">
                                                        <div class="{{ $errors->has('other_benefits')?'has-error':'' }}">
                                                            {!! Form::textarea('other_benefits', Session::get('wpneInfo.other_benefits'), ['class' => 'form-control custom_readonly input-md bigInputField yellow', 'data-charcount-maxlength' => '350', 'size' =>'5x2','data-rule-maxlength'=>'350', 'placeholder' => 'Maximum 350 characters', 'id' => 'other_benefits']) !!}
                                                            {!! $errors->first('other_benefits','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td colspan="3" class="light-green">
                                                        <div class="{{ $errors->has('n_other_benefits')?'has-error':'' }}">
                                                            {!! Form::textarea('n_other_benefits', '',['class' => 'form-control input-md bigInputField green', 'size' =>'5x2', 'data-charcount-maxlength'=>'350', 'placeholder' => 'Maximum 350 characters', 'id' => 'n_other_benefits', 'disabled' => 'disabled']) !!}
                                                            {!! $errors->first('n_other_benefits','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>

                                    {{--  effective date--}}
                                    <fieldset class="scheduler-border" id="effective_date_fieldset" style="display: none">
                                        <legend class="scheduler-border">Effective date of Compensation and Benefit</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('effective_date') ? 'has-error': ''}}">
                                                    {!! Form::label('effective_date','Effective date of amendment',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div class="datepicker input-group date">
                                                            {!! Form::text('effective_date', '', ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('effective_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    {{--  end effective date--}}
                                </div>
                            </div>
                            {{-- (End) Information of Expatriate/ Investor/ Employee --}}
                        </fieldset>

                        <h3 class="stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv">
                                @include('WorkPermitAmendment::documents')
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Declaration</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading" style="padding-bottom: 4px;">
                                    <strong>Declaration and undertaking</strong>
                                </div>
                                <div class="panel-body">
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
                                                            {!! Form::text('auth_mobile_no', Auth::user()->user_phone, ['class' => 'form-control input-sm required phone_or_mobile', 'readonly']) !!}
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
                                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md phone_or_mobile required']) !!}
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
                                                    <b>Vat/ Tax</b> and <b>Transaction charge</b> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
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

{{--//Mobile number flug plugin ....--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}"></script>

{{--//textarea count down--}}
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}"></script>

<script>

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
            var action = "{{url('/work-permit-amendment/upload-document')}}";

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

    function toggleCheckBox(boxId, newFieldId) {
        // console.log(boxId, newFieldId);
        $.each(newFieldId, function (id, val) {
            if (document.getElementById(boxId).checked) {
                document.getElementById(val).disabled = false;
                let field = document.getElementById(val);
                $(field).addClass("required");
            } else {
                document.getElementById(val).disabled = true;
                let field = document.getElementById(val);
                $(field).removeClass("required");
                $(field).removeClass("error");
                $(field).val("");
            }
        });

        //for effective date div.
        let compensationBenefitCheckedCounter = $('#compensationTableId input[type="checkbox"]:checked').length;
        if (compensationBenefitCheckedCounter > 0) {
            document.getElementById('effective_date_fieldset').style.display = 'block';
            document.getElementById('effective_date').classList.add('required');
        } else {
            document.getElementById('effective_date_fieldset').style.display = 'none';
            document.getElementById('effective_date').classList.remove('required');
            document.getElementById('effective_date').value = '';
        }
    }

    function validateCorrectionForm() {
        var atLeastOneChecked = $('input:checkbox').is(':checked');

        if(atLeastOneChecked){
            return true;
        }else{
            alert('In order to Submit please select atleast one field.');
            return false;
        }
    }

    function wpApplication(value) {
        if (value == 'yes') {
            $("#ref_app_tracking_no_div").removeClass('hidden');
            $("#ref_app_tracking_no").addClass('required');
            $("#manually_approved_no_div").addClass('hidden');
            $("#manually_approved_wp_no").removeClass('required');
        } else if(value == 'no') {
            $("#manually_approved_no_div").removeClass('hidden');
            $("#manually_approved_wp_no").addClass('required');
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
            $("#manually_approved_no_div").addClass('hidden');
        }
    }

    var sessionLastWPN = '{{ Session::get('wpneInfo.is_approval_online') }}';
    if (sessionLastWPN == 'yes') {
        wpApplication(sessionLastWPN);
        // $("#ref_app_tracking_no").prop('readonly', true);

        $(".custom_readonly").attr('readonly', true);
//        //$(".custom_readonly option:not(:selected)").prop('disabled', true);
        $(".custom_readonly option:not(:selected)").remove();
        $(".custom_readonly:radio:not(:checked)").attr('disabled', true);
//        $(".custom_readonlyPhoto").attr('disabled', true);
    }

    $(document).ready(function() {

        let form = $("#WorkPermitAmendmentForm").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if(newIndex == 1){
                    var is_approval_online = $("input[name='is_approval_online']:checked").val();
                    if(is_approval_online == 'yes') {
                        if(sessionLastWPN == 'yes') {
                            return true;
                        } else {
                            alert('Please, load work permit data.');
                            return false;
                        }
                    }
                }

                if(newIndex == 2) {
                    var atLeastOneChecked = $('input:checkbox.field').is(':checked');
                    if(atLeastOneChecked == false) {
                        alert('In order to Proceed please select at least one field for amendment.');
                        return false;
                    }
                }

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

        let popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/work-permit-amendment/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        // Datepicker Plugin initialize
        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100)
        });

        $('#ceo_country_id').change(function (e) {
            let country_id = this.value;
            console.log(country_id, 12);
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
        $("#office_division_id").trigger('change');
        $("#office_district_id").trigger('change');
        $("#factory_district_id").trigger('change');

        // End: Information of Principal Promoter/Chairman/Managing Director/CEO/Country Manager
    });


    function dependentRequire(fieldId, dependentFieldId) {
        $.each(dependentFieldId, function (id, val) {
            let field = document.getElementById(val);
            if (document.getElementById(fieldId).value != '') {
                $(field).addClass("required");
            } else {
                $(field).removeClass("required");
                $(field).removeClass("error");
            }
        });
    }
</script>


<script>
    $(function () {
        // max text count down
        $('#other_benefits, #n_other_benefits').characterCounter();

        {{--initail -input plugin script start--}}
        $("#ceo_telephone_no").intlTelInput({
            hiddenInput: "ceo_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#n_ceo_telephone_no").intlTelInput({
            hiddenInput: "n_ceo_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#ceo_mobile_no").intlTelInput({
            hiddenInput: "ceo_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#n_ceo_mobile_no").intlTelInput({
            hiddenInput: "n_ceo_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#office_telephone_no").intlTelInput({
            hiddenInput: "office_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#n_office_telephone_no").intlTelInput({
            hiddenInput: "n_office_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#office_mobile_no").intlTelInput({
            hiddenInput: "office_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#n_office_mobile_no").intlTelInput({
            hiddenInput: "n_office_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#factory_mobile_no").intlTelInput({
            hiddenInput: "factory_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#n_factory_mobile_no").intlTelInput({
            hiddenInput: "n_factory_mobile_no",
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

        $("#n_factory_telephone_no").intlTelInput({
            hiddenInput: "n_factory_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#auth_mobile_no").intlTelInput({
            hiddenInput: ["auth_mobile_no"],
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

        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});
        {{--initail -input plugin script end--}}
    });
</script>
{{--initail -input plugin script end--}}

{{--Applicant desired duration & payment calculation--}}
<script>
    $(function () {
        // Proposed information
        let process_id = '{{ $process_type_id }}';
        let dd_startDateDivID = 'duration_start_datepicker';
        let dd_startDateValID = 'n_p_duration_start_date';
        let dd_endDateDivID = 'duration_end_datepicker';
        let dd_endDateValID = 'n_p_duration_end_date';
        let dd_show_durationID = 'n_p_desired_duration';
        let dd_show_amountID = '';
        let dd_show_yearID = '';

        $("#"+dd_startDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });
        $("#"+dd_endDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });

        $("#"+dd_startDateDivID).on("dp.change", function (e) {

            var startDateVal = $("#"+dd_startDateValID).val();

            if (startDateVal != '') {
                // Min value set for end date
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(e.date);
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
            $("#"+dd_startDateDivID).data("DateTimePicker").maxDate(e.date);

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
            } else {
                $("#"+dd_show_durationID).val('');
                $("#"+dd_show_amountID).val('');
                $("#"+dd_show_yearID).text('');
            }
        });

        // Existing information (Latest Work Permit Info.)
        let pd_startDateDivID = 'pd_start_datepicker';
        let pd_startDateValID = 'p_duration_start_date';
        let pd_endDateDivID = 'pd_end_datepicker';
        let pd_endDateValID = 'p_duration_end_date';
        let pd_show_durationID = 'p_desired_duration';

        $("#"+pd_startDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });
        $("#"+pd_endDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });

        $("#"+pd_startDateDivID).on("dp.change", function (e) {

            var startDateVal = $("#"+pd_startDateValID).val();

            if (startDateVal != '') {
                // Min value set for end date
                $("#"+pd_endDateDivID).data("DateTimePicker").minDate(e.date);
                var endDateVal = $("#"+pd_endDateValID).val();
                if (endDateVal != '') {
                    getDesiredDurationAmount(process_id, startDateVal, endDateVal, pd_show_durationID, 0, 0);
                } else {
                    $("#"+pd_endDateValID).addClass('error');
                }
            } else {
                $("#"+pd_show_durationID).val('');
            }
        });

        $("#"+pd_endDateDivID).on("dp.change", function (e) {

            // Max value set for start date
            $("#"+pd_startDateDivID).data("DateTimePicker").maxDate(e.date);

            var startDateVal = $("#"+pd_startDateValID).val();

            if (startDateVal === '') {
                $("#"+pd_startDateValID).addClass('error');
            } else {
                var day = moment(startDateVal, ['DD-MMM-YYYY']);
                //var minStartDate = moment(day).add(1, 'day');
                $("#"+pd_endDateDivID).data("DateTimePicker").minDate(day);
            }

            var endDateVal = $("#"+pd_endDateValID).val();

            if (startDateVal != '' && endDateVal != '') {
                getDesiredDurationAmount(process_id, startDateVal, endDateVal, pd_show_durationID, 0, 0);
            }else{
                $("#"+pd_show_durationID).val('');
            }
        });

    });
</script>



