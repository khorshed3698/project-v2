<?php
$accessMode = ACL::getAccsessRight('VisaRecommendationAmendment');
if (!ACL::isAllowed($accessMode, '-A-')) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .form-group {
        margin-bottom: 2px;
    }

    .img-thumbnail {
        height: 80px;
        width: 100px;
    }

    .img-user {
        height: 100px;
        width: 100px;
    }

    textarea {
        resize: vertical;
    }

    .wizard > .steps > ul > li {
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

    /*n_datepicker*/
    .n_datepicker_row{
        width: 100%;
    }
    .n_datepicker_icon_border{
        border-radius: 0px;
    }
    .n_datepicker_checkbox_div{
        width: 10%;
    }
    .n_datepicker_checkbox{
        margin-top: 4px!important;
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

</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>Application for Visa Recommendation Amendment</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/visa_recommendation_amendment.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">

                        {!! Form::open(array('url' => 'visa-recommendation-amendment/store','method' => 'post','id' => 'VisaRecommendationAmendmentForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>

                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="10">

                        <h3 class="stepHeader">Basic Information</h3>
                        <fieldset>
                            <legend class="d-none">Basic Information</legend>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic Information </strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                        {!! Form::label('is_approval_online','Did you receive your Visa Recommendation approval online OSS?',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            <label class="radio-inline">{!! Form::radio('is_approval_online','yes', (Session::get('vrInfo.is_approval_online') == 'yes' ? true :false), ['class'=>'cusReadonly required helpTextRadio', 'id' => 'yes', 'onclick' => 'isApprovalOnline(this.value)']) !!}
                                                                Yes</label>
                                                            <label class="radio-inline">{!! Form::radio('is_approval_online', 'no', (Session::get('vrInfo.is_approval_online') == 'no' ? true :false), ['class'=>'cusReadonly required', 'id' => 'no', 'onclick' => 'isApprovalOnline(this.value)']) !!}
                                                                No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="ref_app_tracking_no_div"
                                                         class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ref_app_tracking_no','Please give your approved visa recommendation reference no.',['class'=>'col-md-5 text-left required-star']) !!}

                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                {!! Form::text('ref_app_tracking_no', Session::get('vrInfo.ref_app_tracking_no'), ['data-rule-maxlength'=>'100', 'class' => 'form-control required cusReadonly input-sm']) !!}
                                                                {!! $errors->first('ref_app_tracking_no','<span class="help-block">:message</span>') !!}
                                                                <span class="input-group-btn">
                                                                    @if(Session::get('vrInfo'))
                                                                        <button type="submit"
                                                                                class="btn btn-danger btn-sm"
                                                                                value="clean_load_data"
                                                                                name="actionBtn">Clear Loaded Data</button>

                                                                        <a href="{{ Session::get('vrInfo.certificate_link') }}" target="_blank" rel="noopener" class="btn btn-success btn-sm">View Certificate</a>
                                                                    @else
                                                                        <button type="submit"
                                                                                class="btn btn-success btn-sm"
                                                                                value="searchVRinfo" name="actionBtn"
                                                                                id="searchVRinfo">Load Visa Recommendation Data</button>
                                                                    @endif
                                                                </span>
                                                            </div>

                                                            <small class="text-danger">N.B.: Once you save or submit the
                                                                application, the Visa Recommendation tracking no cannot
                                                                be changed anymore.</small>
                                                        </div>
                                                    </div>
                                                    <div id="manually_approved_no_div"
                                                         class="col-md-12 hidden {{$errors->has('manually_approved_vr_no') ? 'has-error': ''}} ">
                                                        {!! Form::label('manually_approved_vr_no','Please give your manually approved Visa Recommendation reference no.',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('manually_approved_vr_no', '', ['data-rule-maxlength'=>'100', 'class' => 'form-control cusReadonly input-sm']) !!}
                                                            {!! $errors->first('manually_approved_vr_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="issue_date_of_first_div"
                                                         class="col-md-6 {{$errors->has('issue_date_of_prev_vr') ? 'has-error': ''}}">

                                                        {!! Form::label('issue_date_of_prev_vr','Effective date of the previous VR',['class'=>'text-left col-md-5 required-star']) !!}
                                                        <div class="col-md-7">
                                                            <div class="datepicker input-group date">
                                                                {!! Form::text('issue_date_of_prev_vr', (Session::get('vrInfo.approved_date') ? date('d-M-Y', strtotime(Session::get('vrInfo.approved_date'))) : ''), ['class' => 'form-control cusReadonly input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}

                                                                <span class="input-group-addon"><span
                                                                            class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('issue_date_of_prev_vr','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('app_type_id') ? 'has-error': ''}}">
                                                        {!! Form::label('app_type_id','Visa type',['class'=>'col-md-5 required-star text-left']) !!}
                                                        <div class="col-md-7">

                                                            <select class="form-control required cusReadonly input-md"
                                                                    id="app_type_id" name="app_type_id"
                                                                    onchange="CategoryWiseDocLoad(this.value, this.options[this.selectedIndex].getAttribute('attachment_key'))">
                                                                <option value="">Select Visa type</option>
                                                                @foreach($app_category as $category)
                                                                    <option value="{{ $category->id }}"
                                                                            attachment_key="{{ $category->attachment_key }}"
                                                                            <?php if(Session::get('vrInfo.app_type_id') == $category->id){ ?> selected <?php } ?>>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            {!! $errors->first('app_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Application Information</h3>
                        <fieldset>
                            {{-- Common Basic Information By Company Id --}}
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic Company Information (Non editable info. pulled from the basic information provided at the first time by your company)</strong></div>
                                <div class="panel-body">
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
                            {{--                            end basic company info--}}

                            {{--                            start office address --}}
                            <div class="panel panel-info">
                                <div class="panel-heading "><strong>Office Address</strong></div>
                                <div class="panel-body">
                                    <table aria-label="Detailed Office Address Report" class="table table-responsive table-bordered">
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
                                            <td class="required-star">Division</td>

                                            <td class="light-yellow">
                                                {!! Form::select('office_division_id', $divisions, !empty(Session::get('vrInfo.office_division_id')) ? Session::get('vrInfo.office_division_id') : $basicInfo->office_division_id,['class'=>'form-control required input-md custom_readonly', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id',". Session::get('vrInfo.office_district_id') .")"]) !!}
                                                {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_division_id]" value="Office division"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_division_id', $divisions, '',['class'=>'form-control input-md', 'id' => 'n_office_division_id', 'onchange'=>"getDistrictByDivisionId('n_office_division_id', this.value, 'n_office_district_id')", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_division_id]", 1, null, ['class' => 'field', 'id' => 'n_office_division_id_check', 'onclick' => "toggleCheckBox('n_office_division_id_check', 'n_office_division_id');"]) !!}
                                                </span>
                                                </div>
                                                {!! $errors->first('n_office_division_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="required-star">District</td>
                                            <td class="light-yellow">
                                                {!! Form::select('office_district_id', $districts, !empty(Session::get('vrInfo.office_district_id')) ? Session::get('vrInfo.office_district_id') : $basicInfo->office_district_id,['class'=>'form-control required input-md custom_readonly', 'id' => 'office_district_id', 'placeholder' => 'Select Division First', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id', ". Session::get('vrInfo.office_thana_id') .")"]) !!}
                                                {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_district_id]" value="Office district"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_district_id',[],'',['class'=>'form-control input-md', 'id' => 'n_office_district_id', 'placeholder' => 'Select Division First', 'onchange'=>"getThanaByDistrictId('n_office_district_id', this.value, 'n_office_thana_id')", 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_district_id]", 1, null, ['class' => 'field', 'id' => 'n_office_district_id_check', 'onclick' => "toggleCheckBox('n_office_district_id_check', 'n_office_district_id');"]) !!}
                                                </span>
                                                </div>
                                                {!! $errors->first('n_office_district_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Police Station</td>
                                            <td class="light-yellow">
                                                {!! Form::select('office_thana_id', $thana, !empty(Session::get('vrInfo.office_thana_id')) ? Session::get('vrInfo.office_thana_id') : $basicInfo->office_thana_id,['class'=>'form-control required input-md custom_readonly', 'id' => 'office_thana_id', 'placeholder' => 'Select District First']) !!}
                                                {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_thana_id]" value="Office police station"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_thana_id',[],'',['class'=>'form-control input-md', 'id' => 'n_office_thana_id', 'placeholder' => 'Select District First', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_thana_id]", 1, null, ['class' => 'field', 'id' => 'n_office_thana_id_check', 'onclick' => "toggleCheckBox('n_office_thana_id_check', 'n_office_thana_id');"]) !!}
                                                </span>
                                                </div>
                                                {!! $errors->first('n_office_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Post Office</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_post_office', !empty(Session::get('vrInfo.office_post_office')) ? Session::get('vrInfo.office_post_office') : $basicInfo->office_post_office,['class'=>'form-control required input-md custom_readonly', 'id' => 'office_post_office']) !!}
                                                {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_post_office]" value="Office post office"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_post_office','',['class'=>'form-control input-md', 'id' => 'n_office_post_office', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_post_office]", 1, null, ['class' => 'field', 'id' => 'n_office_post_office_check', 'onclick' => "toggleCheckBox('n_office_post_office_check', 'n_office_post_office');"]) !!}
                                                </span>
                                                </div>
                                                {!! $errors->first('n_office_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Post Code</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_post_code', !empty(Session::get('vrInfo.office_post_code')) ? Session::get('vrInfo.office_post_code') : $basicInfo->office_post_code,['class'=>'form-control required alphaNumeric input-md custom_readonly', 'id' => 'office_post_code']) !!}
                                                {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_post_code]" value="Office post code"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_post_code','',['class'=>'form-control input-md alphaNumeric', 'id' => 'n_office_post_code', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_post_code]", 1, null, ['class' => 'field', 'id' => 'n_office_post_code_check', 'onclick' => "toggleCheckBox('n_office_post_code_check', 'n_office_post_code');"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Address</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_address', !empty(Session::get('vrInfo.office_address')) ? Session::get('vrInfo.office_address') : $basicInfo->office_address,['class'=>'form-control required input-md custom_readonly', 'id' => 'office_address']) !!}
                                                {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_address]" value="Office address"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_address','',['class'=>'form-control input-md', 'id' => 'n_office_address', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_address]", 1, null, ['class' => 'field', 'id' => 'n_office_address_check', 'onclick' => "toggleCheckBox('n_office_address_check', 'n_office_address');"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Telephone No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_telephone_no', !empty(Session::get('vrInfo.office_telephone_no')) ? Session::get('vrInfo.office_telephone_no') : $basicInfo->office_telephone_no,['class'=>'form-control input-md custom_readonly', 'id' => 'office_telephone_no']) !!}
                                                {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_telephone_no]" value="Office telephone no."/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_office_telephone_no','',['class'=>'form-control input-md', 'id' => 'n_office_telephone_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                    {!! Form::checkbox("toggleCheck[n_office_telephone_no]", 1, null, ['class' => 'field', 'id' => 'n_office_telephone_no_check', 'onclick' => "toggleCheckBox('n_office_telephone_no_check', 'n_office_telephone_no');"]) !!}
                                                </span>
                                                </div>
                                                {!! $errors->first('n_office_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Mobile No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_mobile_no', !empty(Session::get('vrInfo.office_mobile_no')) ? Session::get('vrInfo.office_mobile_no') : $basicInfo->office_mobile_no,['class'=>'form-control required input-md custom_readonly', 'id' => 'office_mobile_no']) !!}
                                                {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_mobile_no]" value="Office mobile no."/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_office_mobile_no','',['class'=>'form-control input-md', 'id' => 'n_office_mobile_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                    {!! Form::checkbox("toggleCheck[n_office_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_office_mobile_no_check', 'onclick' => "toggleCheckBox('n_office_mobile_no_check', 'n_office_mobile_no');"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Fax No.</td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_fax_no', !empty(Session::get('vrInfo.office_fax_no')) ? Session::get('vrInfo.office_fax_no') : $basicInfo->office_fax_no,['class'=>'form-control input-md custom_readonly', 'id' => 'office_fax_no']) !!}
                                                {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_fax_no]" value="Office fax no."/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_fax_no','',['class'=>'form-control input-md', 'id' => 'n_office_fax_no', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_fax_no]", 1, null, ['class' => 'field', 'id' => 'n_office_fax_no_check', 'onclick' => "toggleCheckBox('n_office_fax_no_check', 'n_office_fax_no');"]) !!}
                                                </span>
                                                </div>
                                                {!! $errors->first('n_office_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="required-star">Email </td>
                                            <td class="light-yellow">
                                                {!! Form::email('office_email', !empty(Session::get('vrInfo.office_email')) ? Session::get('vrInfo.office_email') : $basicInfo->office_email,['class'=>'form-control required input-md custom_readonly', 'id' => 'office_email']) !!}
                                                {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_email]" value="Office email"/>
                                                <div class="input-group">
                                                    {!! Form::email('n_office_email','',['class'=>'form-control input-md', 'id' => 'n_office_email', 'disabled' => 'disabled']) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_email]", 1, null, ['class' => 'field', 'id' => 'n_office_email_check', 'onclick' => "toggleCheckBox('n_office_email_check', 'n_office_email');"]) !!}
                                                </span>
                                                </div>
                                                {!! $errors->first('n_office_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{--                            end office address--}}

                            <div class="table-responsive">
                                <table aria-label="Detailed Report Data Table" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col" width="25%">Field name</th>
                                        <th scope="col" width="35%" class="alert-warning text-center"
                                            style="color: #fff; background-color: #f6d10f;">Existing information
                                        </th>
                                        <th scope="col" width="35%" class="alert-success text-center"
                                            style="color: #fff; background-color: #67db38;">Proposed information
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div style="position: relative;">
                                                            <span class="helpTextCom" id="emp_name_label">
                                                                {!! Form::label('emp_name', 'Full Name', ['class'=>'required-star']) !!}
                                                            </span>
                                            </div>
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::text('emp_name', Session::get('vrInfo.emp_name'), ['class' => 'form-control required cusReadonly textOnly input-md']) !!}
                                            {!! $errors->first('emp_name','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_emp_name]" value="Full Name"/>
                                            <div class="input-group">
                                                {!! Form::text('n_emp_name', '', ['class' => 'form-control textOnly input-md', 'id' => 'n_emp_name', 'disabled' => 'disabled']) !!}
                                                <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_emp_name]", 1, null, ['class' => 'field', 'id' => 'n_emp_name_check', 'onclick' => "toggleCheckBox('n_emp_name_check', 'n_emp_name');"]) !!}
                                                        </span>
                                            </div>
                                            {!! $errors->first('n_emp_name','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <div style="position: relative;">
                                                <span class="helpTextCom" id="emp_designation_label">
                                                   {!! Form::label('emp_designation_label','Position/ Designation', ['class' => 'required-star']) !!}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::text('emp_designation', Session::get('vrInfo.emp_designation'), ['class' => 'form-control required cusReadonly input-md']) !!}
                                            {!! $errors->first('emp_designation','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_emp_designation]" value="Position/ Designation"/>
                                            <div class="input-group">
                                                {!! Form::text('n_emp_designation', '', ['class' => 'form-control input-md', 'id' => 'n_emp_designation', 'disabled' => 'disabled']) !!}
                                                <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_designation]", 1, null, ['class' => 'field', 'id' => 'n_emp_designation_check', 'onclick' => "toggleCheckBox('n_emp_designation_check', 'n_emp_designation');"]) !!}
                                                </span>
                                            </div>
                                            {!! $errors->first('n_emp_designation','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>
                                            <div style="position: relative;">
                                                <span class="helpTextCom" id="emp_passport_no_label">
                                                   {!! Form::label('emp_passport_no','Passport No.', ['class' => 'required-star']) !!}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::text('emp_passport_no', Session::get('vrInfo.emp_passport_no'), ['data-rule-maxlength'=>'20', 'class' => 'form-control required cusReadonly input-md']) !!}
                                            {!! $errors->first('emp_passport_no','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_emp_passport_no]"
                                                   value="Passport No."/>
                                            <div class="input-group">
                                                {!! Form::text('n_emp_passport_no', '', ['data-rule-maxlength'=>'20', 'class' => 'form-control input-md', 'id' => 'n_emp_passport_no', 'disabled' => 'disabled']) !!}
                                                <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_emp_passport_no]", 1, null, ['class' => 'field', 'id' => 'n_emp_passport_no_check', 'onclick' => "toggleCheckBox('n_emp_passport_no_check', 'n_emp_passport_no');"]) !!}
                                                        </span>
                                            </div>
                                            {!! $errors->first('n_emp_passport_no','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>
                                            <div style="position: relative;">
                                                            <span class="helpTextCom" id="emp_nationality_id_label">
                                                              {!! Form::label('emp_nationality_id','Nationality', ['class' => 'required-star']) !!}
                                                            </span>
                                            </div>
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::select('emp_nationality_id', $nationality, Session::get('vrInfo.emp_nationality_id'), ['placeholder' => 'Select One',
                                                    'class' => 'form-control required cusReadonly input-md']) !!}
                                            {!! $errors->first('emp_nationality_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_emp_nationality_id]"
                                                   value="Nationality"/>
                                            <div class="input-group">
                                                {!! Form::select('n_emp_nationality_id', $nationality, '', ['placeholder' => 'Select One',
                                            'class' => 'form-control input-md', 'id' => 'n_emp_nationality_id', 'disabled' => 'disabled']) !!}
                                                <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_emp_nationality_id]", 1, null, ['class' => 'field', 'id' => 'n_emp_nationality_id_check', 'onclick' => "toggleCheckBox('n_emp_nationality_id_check', 'n_emp_nationality_id');"]) !!}
                                                        </span>
                                            </div>
                                            {!! $errors->first('n_emp_nationality_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    </tbody>

                                    <tbody id="previous_embassy_info">
                                    <tr>
                                        <th scope="col" colspan="4">
                                            Embassy/ high commission of Bangladesh in abroad where recommendation letter to be sent:
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>
                                            {!! Form::label('mission_country_id','Select desired country', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::select('mission_country_id', $countries, Session::get('vrInfo.mission_country_id'),
                                                            ['class' => 'form-control cusReadonly input-md pre_embassy_info_req','placeholder' => 'Select One', 'onchange'=>"getEmbassyByCountryId(this, 'high_commision_id', ". Session::get('vrInfo.high_commision_id') .")"]) !!}
                                            {!! $errors->first('mission_country_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_mission_country_id]"
                                                   value="Mission country"/>
                                            <div class="input-group">
                                                {!! Form::select('n_mission_country_id', $countries, '', ['class' => 'form-control input-md', 'id' => 'n_mission_country_id', 'disabled' => 'disabled','placeholder' => 'Select One', 'onchange'=>"getEmbassyByCountryId(this, 'n_high_commision_id')"]) !!}
                                                <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_mission_country_id]", 1, null, ['class' => 'field', 'id' => 'n_mission_country_id_check', 'onclick' => "toggleCheckBox('n_mission_country_id_check', 'n_mission_country_id');"]) !!}
                                                </span>
                                            </div>
                                            {!! $errors->first('n_mission_country_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>
                                            {!! Form::label('high_commision_id','Embassy/ High Commission', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::select('high_commision_id', $highCommission, Session::get('vrInfo.high_commision_id'), ['placeholder' => 'First select the country',
                                                            'class' => 'form-control cusReadonly input-md pre_embassy_info_req']) !!}
                                            {!! $errors->first('high_commision_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_high_commision_id]"
                                                   value="Embassy/ High Commission"/>
                                            <div class="input-group">
                                                {!! Form::select('n_high_commision_id', [],'', ['placeholder' => 'First select the country', 'id' => 'n_high_commision_id', 'disabled' => 'disabled', 'class' => 'form-control input-md']) !!}
                                                <span class="input-group-addon">
                                                                    {!! Form::checkbox("toggleCheck[n_high_commision_id]", 1, null, ['class' => 'field', 'id' => 'n_high_commision_id_check', 'onclick' => "toggleCheckBox('n_high_commision_id_check', 'n_high_commision_id');"]) !!}
                                                                </span>
                                            </div>
                                            {!! $errors->first('n_high_commision_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    </tbody>

                                    <tbody id="previous_on_arrival_info" class="hidden">
                                    <tr>
                                        <th scope="col" colspan="4">Which Airport do you want to receive the visa recommendation in
                                            Bangladesh:
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>
                                            {!! Form::label('airport_id',' Select your desired  airport ',['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::select('airport_id', $airports, Session::get('vrInfo.airport_id'), ['class' => 'form-control cusReadonly input-md pre_oa_req_field', 'placeholder' => 'Select One']) !!}
                                            {!! $errors->first('airport_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_airport_id]"
                                                   value="Select your desired airport"/>
                                            <div class="input-group">
                                                {!! Form::select('n_airport_id', $airports, '',['class' => 'form-control input-md', 'disabled' => 'disabled', 'id' => 'n_airport_id', 'placeholder' => 'Select One']) !!}
                                                <span class="input-group-addon">
                                                                    {!! Form::checkbox("toggleCheck[n_airport_id]", 1, null, ['class' => 'field', 'id' => 'n_airport_id_check', 'onclick' => "toggleCheckBox('n_airport_id_check', 'n_airport_id');"]) !!}
                                                                </span>
                                            </div>
                                            {!! $errors->first('n_airport_id','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>
                                            {!! Form::label('visa_purpose_id','Purpose of visit', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::select('visa_purpose_id', $travel_purpose, Session::get('vrInfo.visa_purpose_id'), ['placeholder' => 'Select one',
                                                            'class' => 'form-control cusReadonly input-md pre_oa_req_field', 'onchange' => 'getPurposeOthers(this.value)']) !!}
                                            {!! $errors->first('visa_purpose_id','<span class="help-block">:message</span>') !!}
                                            <div style="margin-top: 10px;  display: none;" id="purpose_others">
                                                {!! Form::textarea('visa_purpose_others', Session::get('vrInfo.visa_purpose_others'), ['data-rule-maxlength'=>'200', 'placeholder'=>'Specify others purpose', 'id'=> 'visa_purpose_others', 'class' => 'form-control bigInputField cusReadonly input-md maxTextCountDown',
                                                    'size'=>'5x2','data-charcount-maxlength'=>'200']) !!}
                                            </div>
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_visa_purpose_id]"
                                                   value="Purpose of visit"/>

                                            <div class="input-group">
                                                {!! Form::select('n_visa_purpose_id', $travel_purpose, '', ['placeholder' => 'Select one', 'class' => 'form-control input-md', 'id' => 'n_visa_purpose_id', 'disabled' => 'disabled', 'onchange' => 'getAmendPurpose(this.value)']) !!}
                                                <span class="input-group-addon">
                                                                    {!! Form::checkbox("toggleCheck[n_visa_purpose_id]", 1, null, ['class' => 'field', 'id' => 'n_visa_purpose_id_check', 'onclick' => "toggleCheckBox('n_visa_purpose_id_check', 'n_visa_purpose_id');"]) !!}
                                                                </span>
                                            </div>
                                            {!! $errors->first('n_visa_purpose_id','<span class="help-block">:message</span>') !!}
                                            <div style="margin-top: 10px; display: none;" id="n_purpose_others">
                                                {!! Form::textarea('n_visa_purpose_others', null, ['data-rule-maxlength'=>'240', 'placeholder'=>'Specify others purpose', 'class' => 'form-control bigInputField input-md maxTextCountDown', 'id'=> 'n_visa_purpose_others',
                                                    'size'=>'5x2','data-charcount-maxlength'=>'200']) !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="col" colspan="4">Flight Details of the visiting expatriates :</th>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>
                                            {!! Form::label('arrival_date','Arrival date', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            <div class="datepicker input-group date">
                                                {!! Form::text('arrival_date', (!empty(Session::get('vrInfo.arrival_date'))) ? date('d-M-Y', strtotime(Session::get('vrInfo.arrival_date'))) : '', ['class' => 'form-control cusReadonly input-md pre_oa_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            </div>
                                            {!! $errors->first('arrival_date','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_arrival_date]" value="Arrival date"/>
                                            <div class="" style="display: flex">
                                                <div class="input-group datepicker n_datepicker_row" data-date-format="dd-mm-yyyy"  >
                                                    {!! Form::text('n_arrival_date', '', ['class' => 'form-control input-md date', 'id' => 'n_arrival_date', 'placeholder'=>'dd-mm-yyyy', 'disabled']) !!}
                                                    <span class="input-group-addon n_datepicker_icon_border"><span
                                                                class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                                <div class="input-group-addon n_datepicker_checkbox_div">
                                                    {!! Form::checkbox("toggleCheck[n_arrival_date]", 1, false, ['class' => 'field n_datepicker_checkbox', 'id' => 'n_arrival_date_check', 'onclick' => "toggleCheckBox('n_arrival_date_check', 'n_arrival_date');"]) !!}
                                                </div>
                                            </div>
                                            {!! $errors->first('n_arrival_date','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>
                                            {!! Form::label('arrival_time','Arrival time', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            <div class="timepicker input-group date">
                                                {!! Form::text('arrival_time', Session::get('vrInfo.arrival_time'), ['class' => 'form-control cusReadonly input-md pre_oa_req_field', 'placeholder'=>'hh:mm']) !!}
                                                <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-time"></span></span>
                                            </div>
                                            {!! $errors->first('arrival_time','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_arrival_time]" value="Arrival time"/>
                                            <div class="" style="display: flex">
                                                <div class="input-group timepicker n_datepicker_row">
                                                    {!! Form::text('n_arrival_time', '', ['class' => 'form-control input-md', 'id' => 'n_arrival_time', 'placeholder'=>'hh:mm', 'disabled']) !!}
                                                    <span class="input-group-addon n_datepicker_icon_border"><span
                                                                class="glyphicon glyphicon-time"></span></span>
                                                </div>
                                                <div class="input-group-addon n_datepicker_checkbox_div">
                                                    {!! Form::checkbox("toggleCheck[n_arrival_time]", 1, false, ['class' => 'field n_datepicker_checkbox', 'id' => 'n_arrival_time_check', 'onclick' => "toggleCheckBox('n_arrival_time_check', 'n_arrival_time');"]) !!}
                                                </div>
                                            </div>
                                            {!! $errors->first('n_arrival_time','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>
                                            {!! Form::label('arrival_flight_no',' Arrival Flight No.', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::text('arrival_flight_no', Session::get('vrInfo.arrival_flight_no'), ['class' => 'form-control cusReadonly input-md pre_oa_req_field']) !!}
                                            {!! $errors->first('arrival_flight_no','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_arrival_flight_no]"
                                                   value="Arrival Flight No."/>
                                            <div class="input-group ">
                                                {!! Form::text('n_arrival_flight_no', '', ['class' => 'form-control input-md', 'id' => 'n_arrival_flight_no', 'disabled' => 'disabled']) !!}
                                                <span class="input-group-addon">
                                                                    {!! Form::checkbox("toggleCheck[n_arrival_flight_no]", 1, null, ['class' => 'field', 'id' => 'n_arrival_flight_no_check', 'onclick' => "toggleCheckBox('n_arrival_flight_no_check', 'n_arrival_flight_no');"]) !!}
                                                                </span>
                                            </div>
                                            {!! $errors->first('n_arrival_flight_no','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>
                                            {!! Form::label('departure_date','Departure date', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            <div class="datepicker input-group date">
                                                {!! Form::text('departure_date',(!empty(Session::get('vrInfo.departure_date'))) ? date('d-M-Y', strtotime(Session::get('vrInfo.departure_date'))) : '', ['class' => 'form-control cusReadonly input-md pre_oa_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                <span class="input-group-addon"><span
                                                            class="fa fa-calendar"></span></span>
                                            </div>
                                            {!! $errors->first('departure_date','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_departure_date]" value="Departure date"/>
                                            <div class="" style="display: flex">
                                                <div class="input-group datepicker n_datepicker_row" data-date-format="dd-mm-yyyy"  >
                                                    {!! Form::text('n_departure_date', '', ['class' => 'form-control input-md date', 'id' => 'n_departure_date', 'placeholder'=>'dd-mm-yyyy', 'disabled']) !!}
                                                    <span class="input-group-addon n_datepicker_icon_border"><span
                                                                class="fa fa-calendar"></span></span>
                                                </div>
                                                <div class="input-group-addon n_datepicker_checkbox_div">
                                                    {!! Form::checkbox("toggleCheck[n_departure_date]", 1, false, ['class' => 'field n_datepicker_checkbox', 'id' => 'n_departure_date_check', 'onclick' => "toggleCheckBox('n_departure_date_check', 'n_departure_date');"]) !!}
                                                </div>
                                            </div>
                                            {!! $errors->first('n_departure_date','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>
                                            {!! Form::label('departure_time','Departure time', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            <div class="timepicker input-group date">
                                                {!! Form::text('departure_time', Session::get('vrInfo.departure_time'), ['class' => 'form-control input-md pre_oa_req_field cusReadonly', 'placeholder'=>'hh:mm']) !!}
                                                <span class="input-group-addon"><span
                                                            class="glyphicon glyphicon-time"></span></span>
                                            </div>
                                            {!! $errors->first('departure_time','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_departure_time]" value="Departure time"/>
                                            <div class="" style="display: flex">
                                                <div class="input-group timepicker n_datepicker_row">
                                                    {!! Form::text('n_departure_time', '', ['class' => 'form-control input-md', 'id' => 'n_departure_time', 'placeholder'=>'hh:mm', 'disabled']) !!}
                                                    <span class="input-group-addon n_datepicker_icon_border"><span
                                                                class="glyphicon glyphicon-time"></span></span>
                                                </div>
                                                <div class="input-group-addon n_datepicker_checkbox_div">
                                                    {!! Form::checkbox("toggleCheck[n_departure_time]", 1, false, ['class' => 'field n_datepicker_checkbox', 'id' => 'n_departure_time_check', 'onclick' => "toggleCheckBox('n_departure_time_check', 'n_departure_time');"]) !!}
                                                </div>
                                            </div>
                                            {!! $errors->first('n_departure_time','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>
                                            {!! Form::label('departure_flight_no',' Departure Flight No.', ['class' => 'required-star']) !!}
                                        </td>
                                        <td class="alert-warning">
                                            {!! Form::text('departure_flight_no', Session::get('vrInfo.departure_flight_no'), ['class' => 'form-control cusReadonly input-md pre_oa_req_field']) !!}
                                            {!! $errors->first('departure_flight_no','<span class="help-block">:message</span>') !!}
                                        </td>
                                        <td class="alert-success">
                                            <input type="hidden" name="caption[n_departure_flight_no]"
                                                   value="Departure Flight No."/>
                                            <div class="input-group">
                                                {!! Form::text('n_departure_flight_no', '', ['class' => 'form-control input-md', 'id' => 'n_departure_flight_no', 'disabled' => 'disabled']) !!}
                                                <span class="input-group-addon">
                                                                    {!! Form::checkbox("toggleCheck[n_departure_flight_no]", 1, null, ['class' => 'field', 'id' => 'n_departure_flight_no_check', 'onclick' => "toggleCheckBox('n_departure_flight_no_check', 'n_departure_flight_no');"]) !!}
                                                                </span>
                                            </div>
                                            {!! $errors->first('n_departure_flight_no','<span class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
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
                                    <strong>Declaration And Undertaking</strong>
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
                                                            <img class="img-user img-thumbnail"
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
                                                I do hereby declare that the information given above is true to the
                                                best of my knowledge and I shall be liable for any false information/
                                                system is given.
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
                            <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                    value="draft" name="actionBtn">Save as Draft
                            </button>
                        </div>

                        <div class="pull-left" style="padding-left: 1em;">
                            <button type="submit" id="submitForm" style="cursor: pointer;"
                                    class="btn btn-success btn-md"
                                    value="submit" name="actionBtn">Payment &amp; Submit
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

{{---Step JS--}}
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
{{--initail -input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
{{--//textarea count down--}}
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" src="" type="text/javascript"></script>

<script>
    {{--if data load using VRN service tracking--}}
    var sessionLastVR = '{{ Session::get('vrInfo.is_approval_online') }}';

    function CategoryWiseDocLoad(app_type_id, attachment_key) {
        var dept_id = '{{ $department_id }}';
        var _token = $('input[name="_token"]').val();
        var app_id = $("#app_id").val();
        var viewMode = 'off';

        if (app_type_id == 5) {
            $("#previous_embassy_info").addClass('hidden');
            $(".pre_embassy_info_req").removeClass('required');
            $("#mission_country_id").removeClass('required');
            $("#high_commision_id").removeClass('required');

            $("#previous_on_arrival_info").removeClass('hidden');
            $(".pre_oa_req_field").addClass('required');

        } else {
            $("#previous_embassy_info").removeClass('hidden');
            $(".pre_embassy_info_req").addClass('required');

            $("#previous_on_arrival_info").addClass('hidden');
            $(".pre_oa_req_field").removeClass('required');
        }

        attachment_key = "vra" + attachment_key;
        if (dept_id == 1) {
            attachment_key += "cml";
        } else if (dept_id == 2) {
            attachment_key += "i";
        } else {
            attachment_key += "comm";
        }


        if (app_type_id != 0 && app_type_id != '') {

            $.ajax({
                type: "POST",
                url: '/visa-recommendation-amendment/getDocList',
                dataType: "json",
                data: {
                    _token: _token,
                    attachment_key: attachment_key,
                    app_id: app_id,
                    viewMode: viewMode,
                    app_type_id: app_type_id
                },
                success: function (result) {
                    if (result.html != undefined) {
                        $('#docListDiv').html(result.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //console.log(errorThrown);
                    alert('Unknown error occured. Please, try again after reload');
                },
            });
        } else {
            //console.log('Unknown Visa Type');
        }
    }

    function uploadDocument(targets, id, vField, isRequired) {
        var file_id = document.getElementById(id);
        var file = file_id.files;
        var inputFile = $("#" + id).val();

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
            var action = "{{url('/visa-recommendation-amendment/upload-document')}}";

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
                        ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id
                        + ', ' + isRequired + ')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field != '') {
                        $("#" + id).removeClass('required');
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    function isApprovalOnline(value) {
        if (value == 'yes') {
            $("#ref_app_tracking_no_div").removeClass('hidden');
            $("#ref_app_tracking_no").addClass('required');
            $("#manually_approved_no_div").addClass('hidden');
            $("#manually_approved_vr_no").removeClass('required');
        } else if (value == 'no') {
            $("#manually_approved_no_div").removeClass('hidden');
            $("#manually_approved_vr_no").addClass('required');
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
        }
    }

    function toggleCheckBox(boxId, newFieldId) {
        $(newFieldId, function (id, val){
            if (document.getElementById(boxId).checked) {
                document.getElementById(newFieldId).disabled = false;
                var field = document.getElementById(newFieldId);
                $(field).addClass("required");
                if (val == 'n_arrival_date') {
                    $("#" + val).datepicker('enable');
                }
                if (val == 'n_arrival_time') {
                    $("#" + val).timepicker('enable');
                }
                if (val == 'n_departure_date') {
                    $("#" + val).datepicker('enable');
                }
                if (val == 'n_departure_time') {
                    $("#" + val).timepicker('enable');
                }
                if (newFieldId == 'n_visa_purpose_id') {
                    document.getElementById('n_visa_purpose_others').disabled = false;
                    $("#n_visa_purpose_id").trigger('change');
                }

            } else {
                document.getElementById(newFieldId).disabled = true;
                var field = document.getElementById(newFieldId);
                $(field).removeClass("required");
                $(field).removeClass("error");
                $(field).val("");
                if (val == 'n_arrival_date') {
                    $("#" + val).datepicker('disable');
                }
                if (val == 'n_arrival_time') {
                    $("#" + val).timepicker('disable');
                }
                if (val == 'n_departure_date') {
                    $("#" + val).datepicker('disable');
                }
                if (val == 'n_departure_time') {
                    $("#" + val).timepicker('disable');
                }
                if (newFieldId == 'n_visa_purpose_id') {
                    document.getElementById('n_visa_purpose_others').disabled = true;
                    $("#n_visa_purpose_others").val("");
                    $("#n_visa_purpose_id").trigger('change');
                }
            }
        });
    }

    function getPurposeOthers(visa_purpose_value) {
        if (visa_purpose_value == '3') {
            $("#visa_purpose_others").addClass('required');
            $('#purpose_others').css('display', 'block');
        } else {
            $("#visa_purpose_others").removeClass('required');
            $('#purpose_others').css('display', 'none');
        }
    }

    function getAmendPurpose(visa_purpose_value) {
        if (visa_purpose_value == '3') {
            $("#n_visa_purpose_others").addClass('required');
            $('#n_purpose_others').css('display', 'block');
        } else {
            $("#n_visa_purpose_others").removeClass('required');
            $('#n_purpose_others').css('display', 'none');
        }
    }

    if (sessionLastVR == 'yes') {
        isApprovalOnline(sessionLastVR);
        //$(".cusReadonly").prop('readonly', true);
        $(".cusReadonly").attr('readonly', true);
        // $(".cusReadonly option:not(:selected)").prop('disabled', true);
        $(".cusReadonly option:not(:selected)").remove();
        $(".cusReadonly:radio:not(:checked)").attr('disabled', true);
    }

    $(document).ready(function () {
        $("#visa_purpose_id").trigger('change');
        $("#n_visa_purpose_id").trigger('change');
        $("#app_type_id").trigger('change');
        var form = $("#VisaRecommendationAmendmentForm").show();
        form.find('#save_as_draft').css('display', 'none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
                if (newIndex == 1) {
                    var is_approval_online = $("input[name='is_approval_online']:checked").val();
                    if (is_approval_online == 'yes') {
                        if (sessionLastVR == 'yes') {
                            return true;
                        }
                        alert('Please, load Visa Recommendation data.');
                        return false;
                    }
                }

                if (newIndex == 2) {
                    var atLeastOneChecked = $('input:checkbox.field').is(':checked');

                    if (atLeastOneChecked) {
                        if ($('#n_mission_country_id_check').is(':checked')) {
                            // if ($('#n_high_commision_id_check').is(':checked')) {
                            //     return form.valid();
                            // }
                            // alert('If you want to select a new country, then you must select the Embassy/ High Commission of corresponding country from below..');
                            // return false;

                            $("#n_high_commision_id_check").prop("checked", true);
                            $("#n_high_commision_id").prop("disabled", false);
                            $("#n_high_commision_id").addClass("required");
                            if($("#n_high_commision_id").val() == '') {
                                alert('If you want to select a new country, then you must select the Embassy/ High Commission of corresponding country from below..');
                            }
                        }

                        //return form.valid();
                    } else {
                        alert('In order to Proceed please select at least one field for amendment.');
                        return false;
                    }
                }

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex != 0) {
                    form.find('#save_as_draft').css('display', 'block');
                    form.find('.actions').css('top', '-42px');
                } else {
                    form.find('#save_as_draft').css('display', 'none');
                    form.find('.actions').css('top', '-15px');
                }

                if (currentIndex == 4) {
                    form.find('#submitForm').css('display', 'block');

                    $('#submitForm').on('click', function (e) {
                        form.validate().settings.ignore = ":disabled";
                        //console.log(form.validate().errors()); // show hidden errors in last step
                        return form.valid();
                    });
                } else {
                    form.find('#submitForm').css('display', 'none');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                console.log(form.validate());
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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/visa-recommendation-amendment/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 150),
            minDate: '01/01/' + (yyyy - 150)
        });

        $('.datepickerFuture').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            maxDate: '01/01/' + (yyyy + 6)
        });
        $('.timepicker').datetimepicker({
            format: 'HH:mm'
        });
    });

    $(function () {
        //max text count down
        $('.maxTextCountDown').characterCounter();

        $("#auth_mobile_no").intlTelInput({
            hiddenInput: "auth_mobile_no",
            onlyCountries: ["bd"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#office_mobile_no").intlTelInput({
            hiddenInput: "ex_office_mobile_no",
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

        $("#office_telephone_no").intlTelInput({
            hiddenInput: "ex_office_mobile_no",
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

        $("#ceo_mobile_no").intlTelInput({
            hiddenInput: "ex_office_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#ceo_telephone_no").intlTelInput({
            hiddenInput: "ex_office_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});
    });

</script>
{{--initail -input plugin script end--}}

