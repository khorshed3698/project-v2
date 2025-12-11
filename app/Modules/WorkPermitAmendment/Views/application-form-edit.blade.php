<?php
$accessMode = ACL::getAccsessRight('WorkPermitAmendment');
if (!ACL::isAllowed($accessMode, $mode)) {
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

    .n_datepicker_row {
        width: 100%;
    }
    .n_datepicker_icon_border {
        border-radius: 0px;
    }
    .n_datepicker_checkbox_div {
        width: 10%;
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
    #previous-info > tbody > tr:last-child {
        border-bottom: 1px solid #ddd;
    }
    .blink_me {
        animation: blinker 5s linear infinite;
    }

    @keyframes blinker {
        50% { opacity: .5; }
    }
</style>

<section class="content" id="applicationForm">
    @include('ProcessPath::remarks-modal')
    <div class="col-md-12">
        <div class="box"  id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}


                {{--Remarks file for conditional approved status--}}
                @if($viewMode == 'on' && Auth::user()->user_type == '5x505' && in_array($appInfo->status_id, [17,31]))
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h5><strong>Conditionally approve information</strong></h5>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => 'work-permit-amendment/conditionalApproveStore', 'method' => 'post','id' => 'WorkPermitPayment','enctype'=>'multipart/form-data', 'files' => true, 'role'=>'form')) !!}

                            <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"/>

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">

                                    <div class="form-group {{$errors->has('conditional_approved_file') ? 'has-error': ''}}" style="overflow: hidden; margin-bottom: 15px;">
                                        {!! Form::label('conditional_approved_file ','Attachment', ['class'=>'col-md-3 required-star text-left']) !!}
                                        <div class="col-md-9">
                                            <input type="file" id="conditional_approved_file"
                                                   name="conditional_approved_file" onchange="checkPdfDocumentType(this.id, 2)" accept="application/pdf"
                                                   class="form-control input-md required"/>
                                            {!! $errors->first('conditional_approved_file','<span class="help-block">:message</span>') !!}
                                            <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 2MB]</span>
                                            <br>
                                        </div>
                                    </div>

                                    <div class="form-group {{$errors->has('conditional_approved_remarks') ? 'has-error': ''}}" style="overflow: hidden; margin-bottom: 15px;">
                                        {!! Form::label('conditional_approved_remarks','Remarks',['class'=>'text-left col-md-3']) !!}
                                        <div class="col-md-9">
                                            {!! Form::textarea('conditional_approved_remarks', $appInfo->conditional_approved_remarks, ['data-rule-maxlength'=>'1000', 'placeholder'=>'Remarks', 'class' => 'form-control input-md',
                                                'size'=>'5x6','maxlength'=>'1000']) !!}
                                            {!! $errors->first('conditional_approved_remarks','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md pull-right"
                                            value="submit" name="actionBtn">Condition Fulfilled
                                    </button>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                @endif
                {{--End remarks file for conditional approved status--}}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>Application for Work Permit Amendment</strong></h5>
                        </div>
                        <div class="pull-right">
                            @if (isset($appInfo) && $appInfo->status_id == -1)
                                <a href="{{ asset('assets/images/SampleForm/work_permit_amendment.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                    <i class="fas fa-file-pdf"></i>
                                    Download Sample Form
                                </a>
                            @endif

                            @if(in_array($appInfo->status_id,[5,6,17,22]))
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">

                        {!! Form::open(array('url' => 'work-permit-amendment/store', 'method' => 'post','id' => 'WorkPermitAmendmentForm','enctype'=>'multipart/form-data', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <input type="hidden" name="ref_app_approve_date" value="{{ (!empty($appInfo->ref_app_approve_date) ? date('d-M-Y', strtotime($appInfo->ref_app_approve_date)) : '') }}">

                        <h3 class="stepHeader">Basic Instructions</h3>
                        <fieldset>
                            <legend class="d-none">Basic Instructions</legend>
                            @if($appInfo->status_id == 5 && (!empty($appInfo->resend_deadline)))
                                <div class="form-group blink_me">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert btn-danger" role="alert">
                                                You must re-submit the application before <strong>{{ date("d-M-Y", strtotime($appInfo->resend_deadline)) }}</strong>, otherwise, it will be automatically rejected.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic Instructions</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                {!! Form::label('is_approval_online','Did you receive last work-permit through online OSS?',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    @if ($appInfo->is_approval_online == 'yes')
                                                        <label class="radio-inline">{!! Form::radio('is_approval_online','yes', ($appInfo->is_approval_online == 'yes') ? true : false, ['class'=>'custom_readonly required helpTextRadio', 'id' => 'yes', 'onclick' => 'wpApplication(this.value)']) !!}  Yes</label>
                                                    @endif
                                                    @if ($appInfo->is_approval_online == 'no')
                                                        <label class="radio-inline">{!! Form::radio('is_approval_online', 'no', ($appInfo->is_approval_online == 'no') ? true : false, ['class'=>'custom_readonly required', 'id' => 'no', 'onclick' => 'wpApplication(this.value)']) !!}  No</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            @if ($appInfo->is_approval_online == 'yes')
                                                <div id="ref_app_tracking_no_div" class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ref_app_tracking_no','Please give your approved work permit reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div class="input-group">
                                                            {!! Form::hidden('ref_app_tracking_no', $appInfo->ref_app_tracking_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm', 'readonly']) !!}
                                                            <span class="label label-success" style="font-size: 15px">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                                            @if ($appInfo->is_approval_online == 'yes')
                                                                &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                                            @endif
                                                            <br/>

                                                            @if($viewMode != 'on')
                                                                <small class="text-danger">N.B.: Once you save or submit the application, the Work permit tracking no cannot be changed anymore.</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($appInfo->is_approval_online == 'no')
                                                <div id="manually_approved_no_div" class="col-md-12 hidden {{$errors->has('manually_approved_wp_no') ? 'has-error': ''}} ">
                                                    {!! Form::label('manually_approved_wp_no','Please give your manually approved work permit reference  No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('manually_approved_wp_no', $appInfo->manually_approved_wp_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm']) !!}
                                                        {!! $errors->first('manually_approved_wp_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div id="issue_date_of_first_div"
                                                 class="col-md-12 {{$errors->has('issue_date_of_first_wp') ? 'has-error': ''}}">

                                                {!! Form::label('issue_date_of_first_wp','Effective date of the previous work permit',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('issue_date_of_first_wp', (!empty($appInfo->issue_date_of_first_wp) ? date('d-M-Y', strtotime($appInfo->issue_date_of_first_wp)) : ''), ['class' => 'form-control custom_readonly input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}

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
                            {{--start basic information section--}}
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
                                    @if(Auth::user()->company->business_category == 2)
                                        {{--Information of Responsible Person--}}
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Information of Responsible Person</legend>

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
                                                        {!! Form::label('ceo_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <input id="ceo_mobile_no" class="form-control input-md" type="text" value="{{ $basicInfo->ceo_mobile_no }}" readonly>
                                                            {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                        {!! Form::label('ceo_email','Email',['class'=>'col-md-5 text-left']) !!}
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
                                                </div>
                                            </div>
                                        </fieldset>
                                    @else
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Information of Principal Promoter/ Chairman/ Managing Director/ State CEO</legend>

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
                                                            {!! Form::label('ceo_district_id','District/ City/ State',['class'=>'col-md-5 text-left']) !!}
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
                                                            {!! Form::label('ceo_thana_id','Police Station/ Town',['class'=>'col-md-5 text-left','placeholder'=>'Select district first']) !!}
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
                                                        {!! Form::label('ceo_post_code','Post/ Zip Code',['class'=>'col-md-5 text-left']) !!}
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
                                                        {!! Form::label('ceo_address','House, Flat/ Apartment, Road',['class'=>'col-md-5 text-left']) !!}
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
                                                        {!! Form::label('ceo_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
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
                                                        {!! Form::label('ceo_email','Email',['class'=>'col-md-5 text-left']) !!}
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
                                                        {!! Form::label('ceo_fax_no','Fax No.',['class'=>'col-md-5 text-left']) !!}
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
                                    @if(Auth::user()->company->business_category != 2 && $basicInfo->department_id == 2)
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Factory Address</legend>
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
                                                        {!! Form::label('factory_address','House, Flat/ Apartment, Road',['class'=>'col-md-5 text-left', 'id'=>'factory_address_label']) !!}
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
                                                        {!! Form::label('factory_mobile_no','Mobile No.',['class'=>'col-md-5 text-left', 'id'=>'factory_mobile_label']) !!}
                                                        <div class="col-md-7">
                                                            <input class="form-control input-md" type="text" value="{{ $basicInfo->factory_mobile_no }}" readonly>
                                                            {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                        {!! Form::label('factory_fax_no','Fax No.',['class'=>'col-md-5 text-left']) !!}
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
                            {{--(End) Basic Company Information--}}

                            {{--(Start) CEo information section--}}
{{--                            <div class="panel panel-info">--}}
{{--                                <div class="panel-heading "><strong>Information of Principal Promoter/Chairman/Managing Director/CEO/Country Manager</strong></div>--}}
{{--                                <div class="panel-body">--}}
{{--                                    <table class="table table-responsive table-bordered" aria-label="Detailed CEo information">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                        <th aria-hidden="true" scope="col"></th>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Field name</td>--}}
{{--                                            <td class="bg-yellow">Existing information (Latest BIDA Reg. Info.)</td>--}}
{{--                                            <td class="bg-green">Proposed information</td>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>Country</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::select('ceo_country_id', $countries, $appInfo->ceo_country_id, ['class' => 'form-control  input-md ','id'=>'ceo_country_id']) !!}--}}
{{--                                                {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_country_id]" value="Principal promoter country"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_ceo_country_id', $countries, $appInfo->n_ceo_country_id, ['class' => 'form-control  input-md ','id'=>'n_ceo_country_id', (empty($appInfo->n_ceo_country_id) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_country_id]", 1, (empty($appInfo->n_ceo_country_id) ? false : true), ['class' => 'field', 'id' => 'n_ceo_country_id_check', 'onclick' => "toggleCheckBox('n_ceo_country_id_check', ['n_ceo_country_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_country_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Date of Birth</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                <div class="datepicker input-group date">--}}
{{--                                                    {!! Form::text('ceo_dob', ($appInfo->ceo_dob != '0000-00-00' ? date('d-M-Y', strtotime($appInfo->ceo_dob)) : ""), ['class'=>'form-control input-md custom_readonly', 'id' => 'ceo_dob', 'placeholder'=>'dd-mm-yyyy']) !!}--}}
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
{{--                                                        {!! Form::text('n_ceo_dob', !empty($appInfo->n_ceo_dob) ? date('d-M-Y', strtotime($appInfo->n_ceo_dob)) : '', ['class'=>'form-control input-md date', 'id' => 'n_ceo_dob', 'placeholder'=>'dd-mm-yyyy', (empty($appInfo->n_ceo_dob) ? 'disabled' : '')]) !!}--}}
{{--                                                        <span class="input-group-addon n_datepicker_icon_border"><span--}}
{{--                                                                    class="glyphicon glyphicon-calendar"></span></span>--}}
{{--                                                    </div>--}}
{{--                                                    <span class="input-group-addon n_datepicker_checkbox_div">--}}
{{--                                                        {!! Form::checkbox("toggleCheck[n_ceo_dob]", 1, (empty($appInfo->n_ceo_dob) ? false : true), ['class' => 'n_datepicker_checkbox', 'id' => 'n_ceo_dob_check', 'onclick' => "toggleCheckBox('n_ceo_dob_check', ['n_ceo_dob']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_dob','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>NID/ Passport No.</td>--}}

{{--                                            <td class="light-yellow hidden" id="foreignExistingPassportField">--}}
{{--                                                {!! Form::text('ceo_passport_no', $appInfo->ceo_passport_no,['class'=>'form-control input-md', 'id' => 'ceo_passport_no', 'placeholder' => 'Passport No.']) !!}--}}
{{--                                                {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-yellow hidden" id="BDNIDExistingField">--}}
{{--                                                {!! Form::text('ceo_nid', $appInfo->ceo_nid,['class'=>'form-control input-md', 'id' => 'ceo_nid', 'placeholder' => 'NID']) !!}--}}
{{--                                                {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}

{{--                                            <td class="light-green hidden" id="foreignProposedPassportField">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_passport_no]" value="Principal promoter passport no."/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_passport_no', $appInfo->n_ceo_passport_no,['class'=>'form-control input-md', 'placeholder' => 'Passport No.', 'id' => 'n_ceo_passport_no', (empty($appInfo->n_ceo_passport_no) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_passport_no]", 1, (empty($appInfo->n_ceo_passport_no) ? false:true), ['class' => 'field', 'id' => 'n_ceo_passport_no_check', 'onclick' => "toggleCheckBox('n_ceo_passport_no_check', ['n_ceo_passport_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_passport_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green hidden" id="BDNIDProposedField">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_nid]" value="Principal promoter NID"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_nid', $appInfo->n_ceo_nid,['class'=>'form-control input-md', 'placeholder' => 'NID', 'id' => 'n_ceo_nid', (empty($appInfo->n_ceo_nid)? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_nid]", 1, (empty($appInfo->n_ceo_nid)? false:true), ['class' => 'field', 'id' => 'n_ceo_nid_no_check', 'onclick' => "toggleCheckBox('n_ceo_nid_no_check', ['n_ceo_nid']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_nid','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Designation</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_designation', $appInfo->ceo_designation,['class'=>'form-control input-md', 'id' => 'ceo_designation']) !!}--}}
{{--                                                {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_designation]" value="Principal promoter designation"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_designation', $appInfo->n_ceo_designation,['class'=>'form-control input-md', 'id' => 'n_ceo_designation', (empty($appInfo->n_ceo_designation)? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_designation]", 1, (empty($appInfo->n_ceo_designation)? false : true), ['class' => 'field', 'id' => 'n_ceo_designation_check', 'onclick' => "toggleCheckBox('n_ceo_designation_check', ['n_ceo_designation']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_designation','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Full Name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_full_name', $appInfo->ceo_full_name,['class'=>'form-control input-md', 'id' => 'ceo_full_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_full_name]" value="Principal promoter full name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_full_name', $appInfo->n_ceo_full_name,['class'=>'form-control input-md', 'id' => 'n_ceo_full_name', (empty($appInfo->n_ceo_full_name) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_full_name]", 1, (empty($appInfo->n_ceo_full_name) ? false:true), ['class' => 'field', 'id' => 'n_ceo_full_name_check', 'onclick' => "toggleCheckBox('n_ceo_full_name_check', ['n_ceo_full_name']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_full_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>District/ City/ State</td>--}}
{{--                                            <td class="light-yellow hidden" id="foreignExistingCity">--}}
{{--                                                {!! Form::text('ceo_city', $appInfo->ceo_city,['class'=>'form-control input-md', 'id' => 'ceo_City', 'placeholder' => 'District/ City/ State']) !!}--}}
{{--                                                {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-yellow hidden" id="BDExistingDistrict">--}}
{{--                                                {!! Form::select('ceo_district_id', $districts, $appInfo->ceo_district_id,['class'=>'form-control input-md', 'id' => 'ceo_district_id']) !!}--}}
{{--                                                {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}

{{--                                            <td class="light-green hidden" id="foreignProposedCity">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_city]" value="Principal promoter city"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_city', $appInfo->n_ceo_city,['class'=>'form-control input-md', 'id' => 'n_ceo_city', (empty($appInfo->n_ceo_city)? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_city]", 1, (empty($appInfo->n_ceo_city)? false : true), ['class' => 'field', 'id' => 'n_ceo_City_check', 'onclick' => "toggleCheckBox('n_ceo_City_check', ['n_ceo_city']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}

{{--                                                {!! $errors->first('n_ceo_city','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green hidden" id="BDProposedDistrict">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_district_id]" value="Principal promoter district"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_ceo_district_id', $districts, $appInfo->n_ceo_district_id,['class'=>'form-control input-md', 'id' => 'n_ceo_district_id', 'onchange'=>"getThanaByDistrictId('n_ceo_district_id', this.value, 'n_ceo_thana_id', ". $appInfo->n_ceo_thana_id .")", (empty($appInfo->n_ceo_district_id)? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_district_id]", 1, (empty($appInfo->n_ceo_district_id)? false:true), ['class' => 'field', 'id' => 'n_ceo_district_id_check', 'onclick' => "toggleCheckBox('n_ceo_district_id_check', ['n_ceo_district_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>State/ Province/ Police station/ Town</td>--}}
{{--                                            <td class="light-yellow hidden" id="foreignExistingState">--}}
{{--                                                {!! Form::text('ceo_state', $appInfo->ceo_state,['class'=>'form-control input-md', 'id' => 'ceo_state', 'placeholder' => 'State/ Province']) !!}--}}
{{--                                                {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-yellow hidden" id="BDExistingTown">--}}
{{--                                                {!! Form::select('ceo_thana_id', $thana, $appInfo->ceo_thana_id,['class'=>'form-control input-md', 'id' => 'ceo_thana_id']) !!}--}}
{{--                                                {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}

{{--                                            <td class="light-green hidden" id="foreignProposedState">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_state]" value="Principal promoter state"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_state', $appInfo->n_ceo_state,['class'=>'form-control input-md', 'id' => 'n_ceo_state', 'placeholder' => 'State/ Province', (empty($appInfo->n_ceo_state) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_state]", 1, (empty($appInfo->n_ceo_state) ? false:true), ['class' => 'field', 'id' => 'n_ceo_state_check', 'onclick' => "toggleCheckBox('n_ceo_state_check', ['n_ceo_state']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_state','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green hidden" id="BDProposedTown">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_thana_id]" value="Principal promoter police station"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_ceo_thana_id', [], $appInfo->n_ceo_thana_id,['class'=>'form-control input-md', 'id' => 'n_ceo_thana_id', (empty($appInfo->n_ceo_thana_id) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_thana_id]", 1, (empty($appInfo->n_ceo_thana_id) ? false:true), ['class' => 'field', 'id' => 'n_ceo_thana_id_check', 'onclick' => "toggleCheckBox('n_ceo_thana_id_check', ['n_ceo_thana_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Post/ Zip Code</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_post_code', $appInfo->ceo_post_code,['class'=>'form-control input-md', 'id' => 'ceo_post_code']) !!}--}}
{{--                                                {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_post_code]" value="Principal promoter post/ zip code"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_post_code', $appInfo->n_ceo_post_code,['class'=>'form-control input-md', 'id' => 'n_ceo_post_code', (empty($appInfo->n_ceo_post_code)? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_post_code]", 1, (empty($appInfo->n_ceo_post_code)? false:true), ['class' => 'field', 'id' => 'n_ceo_post_code_check', 'onclick' => "toggleCheckBox('n_ceo_post_code_check', ['n_ceo_post_code']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>House, Flat/ Apartment, Road</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_address', $appInfo->ceo_address,['class'=>'form-control input-md', 'id' => 'ceo_address']) !!}--}}
{{--                                                {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_address]" value="Principal promoter house, flat/ apartment, road"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_address', $appInfo->n_ceo_address,['class'=>'form-control input-md', 'id' => 'n_ceo_address', (empty($appInfo->n_ceo_address) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_address]", 1, (empty($appInfo->n_ceo_address) ? false:true), ['class' => 'field', 'id' => 'n_ceo_address_check', 'onclick' => "toggleCheckBox('n_ceo_address_check', ['n_ceo_address']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Telephone No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_telephone_no', $appInfo->ceo_telephone_no,['class'=>'form-control input-md', 'id' => 'ceo_telephone_no']) !!}--}}
{{--                                                {!! $errors->first('ceo_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_telephone_no]" value="Principal promoter telephone no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_ceo_telephone_no', $appInfo->n_ceo_telephone_no,['class'=>'form-control input-md', 'id' => 'n_ceo_telephone_no', (empty($appInfo->n_ceo_telephone_no) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_telephone_no]", 1, (empty($appInfo->n_ceo_telephone_no) ? false:true), ['class' => 'field', 'id' => 'n_ceo_telephone_no_check', 'onclick' => "toggleCheckBox('n_ceo_telephone_no_check', ['n_ceo_telephone_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Mobile No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_mobile_no', $appInfo->ceo_mobile_no,['class'=>'form-control input-md', 'id' => 'ceo_mobile_no']) !!}--}}
{{--                                                {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_mobile_no]" value="Principal promoter mobile no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_ceo_mobile_no', $appInfo->n_ceo_mobile_no,['class'=>'form-control input-md', 'id' => 'n_ceo_mobile_no', (empty($appInfo->n_ceo_mobile_no)? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_mobile_no]", 1, (empty($appInfo->n_ceo_mobile_no)? false:true), ['class' => 'field', 'id' => 'n_ceo_mobile_no_check', 'onclick' => "toggleCheckBox('n_ceo_mobile_no_check', ['n_ceo_mobile_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Email</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::email('ceo_email', $appInfo->ceo_email,['class'=>'form-control input-md', 'id' => 'ceo_email']) !!}--}}
{{--                                                {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_email]" value="Principal promoter email"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::email('n_ceo_email', $appInfo->n_ceo_email,['class'=>'form-control input-md', 'id' => 'n_ceo_email', (empty($appInfo->n_ceo_email) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_email]", 1, (empty($appInfo->n_ceo_email) ? false:true), ['class' => 'field', 'id' => 'n_ceo_email_check', 'onclick' => "toggleCheckBox('n_ceo_email_check', ['n_ceo_email']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_email','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Fax No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_fax_no', $appInfo->ceo_fax_no,['class'=>'form-control input-md', 'id' => 'ceo_fax_no']) !!}--}}
{{--                                                {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_fax_no]" value="Principal promoter fax no."/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_fax_no', $appInfo->n_ceo_fax_no,['class'=>'form-control input-md', 'id' => 'n_ceo_fax_no', (empty($appInfo->n_ceo_fax_no) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_fax_no]", 1, (empty($appInfo->n_ceo_fax_no) ? false:true), ['class' => 'field', 'id' => 'n_ceo_fax_no_check', 'onclick' => "toggleCheckBox('n_ceo_fax_no_check', ['n_ceo_fax_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_fax_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Father's Name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_father_name', $appInfo->ceo_father_name,['class'=>'form-control input-md', 'id' => 'ceo_father_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_father_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_father_name]" value="Principal promoter father's name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_father_name', $appInfo->n_ceo_father_name,['class'=>'form-control input-md', 'id' => 'n_ceo_father_name', (empty($appInfo->n_ceo_father_name) ? 'disabled' : '' )]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_father_name]", 1, (empty($appInfo->n_ceo_father_name) ? false:true), ['class' => 'field', 'id' => 'n_ceo_father_name_check', 'onclick' => "toggleCheckBox('n_ceo_father_name_check', ['n_ceo_father_name']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_father_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Mother's Name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_mother_name', $appInfo->ceo_mother_name,['class'=>'form-control input-md', 'id' => 'ceo_mother_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_mother_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_mother_name]" value="Principal promoter mother's name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_mother_name', $appInfo->n_ceo_mother_name,['class'=>'form-control input-md', 'id' => 'n_ceo_mother_name', (empty($appInfo->n_ceo_mother_name) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_mother_name]", 1, (empty($appInfo->n_ceo_mother_name) ? false:true), ['class' => 'field', 'id' => 'n_ceo_mother_name_check', 'onclick' => "toggleCheckBox('n_ceo_mother_name_check', ['n_ceo_mother_name']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_mother_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Spouse name</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('ceo_spouse_name', $appInfo->ceo_spouse_name,['class'=>'form-control input-md', 'id' => 'ceo_spouse_name']) !!}--}}
{{--                                                {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_spouse_name]" value="Principal promoter spouse name"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_ceo_spouse_name', $appInfo->n_ceo_spouse_name,['class'=>'form-control input-md', 'id' => 'n_ceo_spouse_name', (empty($appInfo->n_ceo_spouse_name) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_spouse_name]", 1, (empty($appInfo->n_ceo_spouse_name) ? false:true), ['class' => 'field', 'id' => 'n_ceo_spouse_name_check', 'onclick' => "toggleCheckBox('n_ceo_spouse_name_check', ['n_ceo_spouse_name']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_ceo_spouse_name','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Gender</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                <label class="radio-inline">{!! Form::radio('ceo_gender','male', ($appInfo->ceo_gender == 'Male' ? true : false)) !!}  Male</label>--}}
{{--                                                <label class="radio-inline">{!! Form::radio('ceo_gender', 'female', ($appInfo->ceo_gender == 'Female' ? true : false)) !!}  Female</label>--}}
{{--                                                {!! $errors->first('ceo_gender','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_ceo_gender]" value="Principal promoter gender"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    <label class="radio-inline">{!! Form::radio('n_ceo_gender','male', ($appInfo->n_ceo_gender == 'Male' ? true : false), ['class'=>'', 'id'=>'n_male', (empty($appInfo->n_ceo_gender) ? 'disabled' : '')]) !!}  Male</label>--}}
{{--                                                    <label class="radio-inline">{!! Form::radio('n_ceo_gender', 'female', ($appInfo->n_ceo_gender == 'Female' ? true : false), ['class'=>'', 'id'=>'n_female', (empty($appInfo->n_ceo_gender) ? 'disabled' : '')]) !!}  Female</label>--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_ceo_gender]", 1, (empty($appInfo->n_ceo_gender) || $appInfo->ceo_gender == 'Not defined' ? false : true), ['class' => 'field', 'id' => 'n_ceo_gender_check', 'onclick' => "toggleCheckBox('n_ceo_gender_check', ['n_male', 'n_female']);"]) !!}--}}
{{--                                                    </span>--}}
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
                                    <table class="table table-responsive table-bordered" aria-label="Detailed office information">
                                        <thead>
                                            <tr  class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                            <tr>
                                                <td width="30%">Field name</td>
                                                <td class="bg-yellow" width="35%">Existing information (Latest BIDA Reg. Info.)</td>
                                                <td class="bg-green" width="35%">Proposed information</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <label class="required-star">Division</label>
                                            </td>

                                            <td class="light-yellow">
                                                {!! Form::select('office_division_id', $divisions, $appInfo->office_division_id,['class'=>'form-control required input-md', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id', ". $appInfo->office_district_id .")"]) !!}
                                                {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_division_id]" value="Office division"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_division_id', $divisions, $appInfo->n_office_division_id,['class'=>'form-control input-md', 'id' => 'n_office_division_id', 'onchange'=>"getDistrictByDivisionId('n_office_division_id', this.value, 'n_office_district_id', ". $appInfo->n_office_district_id .")", (empty($appInfo->n_office_division_id) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_division_id]", 1, (empty($appInfo->n_office_division_id) ? false:true), ['class' => 'field', 'id' => 'n_office_division_id_check', 'onclick' => "toggleCheckBox('n_office_division_id_check', ['n_office_division_id']);"]) !!}
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
                                                {!! Form::select('office_district_id', $districts, $appInfo->office_district_id,['class'=>'form-control required input-md', 'id' => 'office_district_id', 'placeholder' => 'Select Division First', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id', ". $appInfo->office_thana_id .")"]) !!}
                                                {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_district_id]" value="Office district"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_district_id', $districts, $appInfo->n_office_district_id,['class'=>'form-control input-md', 'id' => 'n_office_district_id', 'placeholder' => 'Select Division First', 'onchange'=>"getThanaByDistrictId('n_office_district_id', this.value, 'n_office_thana_id', ". $appInfo->n_office_thana_id .")", (empty($appInfo->n_office_district_id) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_district_id]", 1, (empty($appInfo->n_office_district_id) ? false : true), ['class' => 'field', 'id' => 'n_office_district_id_check', 'onclick' => "toggleCheckBox('n_office_district_id_check', ['n_office_district_id']);"]) !!}
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
                                                {!! Form::select('office_thana_id', [], $appInfo->office_thana_id,['class'=>'form-control required input-md', 'id' => 'office_thana_id', 'placeholder' => 'Select District First']) !!}
                                                {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                            </td>

                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_thana_id]" value="Office police station"/>
                                                <div class="input-group">
                                                    {!! Form::select('n_office_thana_id',[], $appInfo->n_office_thana_id,['class'=>'form-control input-md', 'id' => 'n_office_thana_id', 'placeholder' => 'Select District First', (empty($appInfo->n_office_thana_id) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_thana_id]", 1, (empty($appInfo->n_office_thana_id) ? false : true), ['class' => 'field', 'id' => 'n_office_thana_id_check', 'onclick' => "toggleCheckBox('n_office_thana_id_check', ['n_office_thana_id']);"]) !!}
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
                                                {!! Form::text('office_post_office', $appInfo->office_post_office,['class'=>'form-control required input-md', 'id' => 'office_post_office']) !!}
                                                {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_post_office]" value="Office post office"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_post_office', $appInfo->n_office_post_office,['class'=>'form-control input-md', 'id' => 'n_office_post_office', (empty($appInfo->n_office_post_office) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_post_office]", 1, (empty($appInfo->n_office_post_office) ? false:true), ['class' => 'field', 'id' => 'n_office_post_office_check', 'onclick' => "toggleCheckBox('n_office_post_office_check', ['n_office_post_office']);"]) !!}
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
                                                {!! Form::text('office_post_code', $appInfo->office_post_code,['class'=>'form-control required input-md alphaNumeric', 'id' => 'office_post_code']) !!}
                                                {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_post_code]" value="Office post code"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_post_code', $appInfo->n_office_post_code,['class'=>'form-control input-md alphaNumeric', 'id' => 'n_office_post_code', (empty($appInfo->n_office_post_code) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_post_code]", 1, (empty($appInfo->n_office_post_code) ? false:true), ['class' => 'field', 'id' => 'n_office_post_code_check', 'onclick' => "toggleCheckBox('n_office_post_code_check', ['n_office_post_code']);"]) !!}
                                                    </span>
                                                </div>
                                                {!! $errors->first('n_office_post_code','<span class="help-block">:message</span>') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label class="required-star">Address</label>
                                            </td>
                                            <td class="light-yellow">
                                                {!! Form::text('office_address', $appInfo->office_address,['class'=>'form-control required input-md', 'id' => 'office_address']) !!}
                                                {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_address]" value="Office address"/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_address', $appInfo->n_office_address,['class'=>'form-control input-md', 'id' => 'n_office_address', (empty($appInfo->n_office_address) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_address]", 1, (empty($appInfo->n_office_address) ? false : true), ['class' => 'field', 'id' => 'n_office_address_check', 'onclick' => "toggleCheckBox('n_office_address_check', ['n_office_address']);"]) !!}
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
                                                {!! Form::text('office_telephone_no', $appInfo->office_telephone_no,['class'=>'form-control input-md', 'id' => 'office_telephone_no']) !!}
                                                {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_telephone_no]" value="Office telephone no."/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_office_telephone_no', $appInfo->n_office_telephone_no,['class'=>'form-control input-md', 'id' => 'n_office_telephone_no', (empty($appInfo->n_office_telephone_no) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                    {!! Form::checkbox("toggleCheck[n_office_telephone_no]", 1, (empty($appInfo->n_office_telephone_no) ? false:true), ['class' => 'field', 'id' => 'n_office_telephone_no_check', 'onclick' => "toggleCheckBox('n_office_telephone_no_check', ['n_office_telephone_no']);"]) !!}
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
                                                {!! Form::text('office_mobile_no', $appInfo->office_mobile_no,['class'=>'form-control required input-md', 'id' => 'office_mobile_no']) !!}
                                                {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_mobile_no]" value="Office mobile no."/>
                                                <div class="input-group mobile-plugin">
                                                    {!! Form::text('n_office_mobile_no', $appInfo->n_office_mobile_no,['class'=>'form-control input-md', 'id' => 'n_office_mobile_no', (empty($appInfo->n_office_mobile_no) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">
                                                    {!! Form::checkbox("toggleCheck[n_office_mobile_no]", 1, (empty($appInfo->n_office_mobile_no) ? false:true), ['class' => 'field', 'id' => 'n_office_mobile_no_check', 'onclick' => "toggleCheckBox('n_office_mobile_no_check', ['n_office_mobile_no']);"]) !!}
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
                                                {!! Form::text('office_fax_no', $appInfo->office_fax_no,['class'=>'form-control input-md', 'id' => 'office_fax_no']) !!}
                                                {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_fax_no]" value="Office fax no."/>
                                                <div class="input-group">
                                                    {!! Form::text('n_office_fax_no', $appInfo->n_office_fax_no,['class'=>'form-control input-md', 'id' => 'n_office_fax_no', (empty($appInfo->n_office_fax_no) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_fax_no]", 1, (empty($appInfo->n_office_fax_no) ? false:true), ['class' => 'field', 'id' => 'n_office_fax_no_check', 'onclick' => "toggleCheckBox('n_office_fax_no_check', ['n_office_fax_no']);"]) !!}
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
                                                {!! Form::email('office_email', $appInfo->office_email,['class'=>'form-control required input-md', 'id' => 'office_email']) !!}
                                                {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                            </td>
                                            <td class="light-green">
                                                <input type="hidden" name="caption[n_office_email]" value="Office email"/>
                                                <div class="input-group">
                                                    {!! Form::email('n_office_email', $appInfo->n_office_email,['class'=>'form-control input-md', 'id' => 'n_office_email', (empty($appInfo->n_office_email) ? 'disabled' : '')]) !!}
                                                    <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_office_email]", 1, (empty($appInfo->n_office_email) ? false : true), ['class' => 'field', 'id' => 'n_office_email_check', 'onclick' => "toggleCheckBox('n_office_email_check', ['n_office_email']);"]) !!}
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
{{--                                    <table class="table table-responsive table-bordered" aria-label="Detailed Report Data Table">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                        <th aria-hidden="true" scope="col"></th>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td width="30%">Field name</td>--}}
{{--                                            <td class="bg-yellow" width="35%">Existing information (Latest BIDA Reg. Info.)</td>--}}
{{--                                            <td class="bg-green" width="35%">Proposed information</td>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        <tr>--}}
{{--                                            <td>District</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::select('factory_district_id', $districts, $appInfo->factory_district_id,['class'=>'form-control input-md', 'id' => 'factory_district_id', 'onchange'=>"getThanaByDistrictId('factory_district_id', this.value, 'factory_thana_id', ". $appInfo->factory_thana_id .")"]) !!}--}}
{{--                                                {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_district_id]" value="Factory district"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_factory_district_id', $districts, $appInfo->n_factory_district_id,['class'=>'form-control input-md', 'id' => 'n_factory_district_id', 'onchange'=>"getThanaByDistrictId('n_factory_district_id', this.value, 'n_factory_thana_id', ". $appInfo->n_factory_thana_id .")", (empty($appInfo->n_factory_district_id) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_district_id]", 1, (empty($appInfo->n_factory_district_id) ? false : true), ['class' => 'field', 'id' => 'n_factory_district_id_check', 'onclick' => "toggleCheckBox('n_factory_district_id_check', ['n_factory_district_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_district_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Police Station</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::select('factory_thana_id', [], $appInfo->factory_thana_id,['class'=>'form-control input-md', 'placeholder' => 'Select District First','id' => 'factory_thana_id']) !!}--}}
{{--                                                {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_thana_id]" value="Factory police station"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::select('n_factory_thana_id',[], $appInfo->n_factory_thana_id,['class'=>'form-control input-md', 'id' => 'n_factory_thana_id', 'placeholder' => 'Select District First', (empty($appInfo->n_factory_thana_id) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_thana_id]", 1, (empty($appInfo->n_factory_thana_id) ? false:true), ['class' => 'field', 'id' => 'n_factory_thana_id_check', 'onclick' => "toggleCheckBox('n_factory_thana_id_check', ['n_factory_thana_id']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_thana_id','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Post Office</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_post_office', $appInfo->factory_post_office,['class'=>'form-control input-md', 'id' => 'factory_post_office']) !!}--}}
{{--                                                {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_post_office]" value="Factory post office"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_post_office', $appInfo->n_factory_post_office,['class'=>'form-control input-md', 'id' => 'n_factory_post_office', (empty($appInfo->n_factory_post_office) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_post_office]", 1, (empty($appInfo->n_factory_post_office) ? false:true), ['class' => 'field', 'id' => 'n_factory_post_office_check', 'onclick' => "toggleCheckBox('n_factory_post_office_check', ['n_factory_post_office']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_post_office','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Post Code</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_post_code', $appInfo->factory_post_code,['class'=>'form-control input-md', 'id' => 'factory_post_code']) !!}--}}
{{--                                                {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_post_code]" value="Factory post code"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_post_code', $appInfo->n_factory_post_code,['class'=>'form-control input-md', 'id' => 'n_factory_post_code', (empty($appInfo->n_factory_post_code) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_post_code]", 1, (empty($appInfo->n_factory_post_code) ? false:true), ['class' => 'field', 'id' => 'n_factory_post_code_check', 'onclick' => "toggleCheckBox('n_factory_post_code_check', ['n_factory_post_code']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_post_code','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Address</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_address', $appInfo->factory_address,['class'=>'form-control input-md', 'id' => 'factory_address']) !!}--}}
{{--                                                {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_address]" value="Factory address"/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_address', $appInfo->n_factory_address,['class'=>'form-control input-md', 'id' => 'n_factory_address', (empty($appInfo->n_factory_address) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_address]", 1, (empty($appInfo->n_factory_address) ? false:true), ['class' => 'field', 'id' => 'n_factory_address_check', 'onclick' => "toggleCheckBox('n_factory_address_check', ['n_factory_address']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_address','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Telephone No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_telephone_no', $appInfo->factory_telephone_no,['class'=>'form-control input-md', 'id' => 'factory_telephone_no']) !!}--}}
{{--                                                {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_telephone_no]" value="Factory telephone no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_factory_telephone_no', $appInfo->n_factory_telephone_no,['class'=>'form-control input-md', 'id' => 'n_factory_telephone_no', (empty($appInfo->n_factory_telephone_no) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_telephone_no]", 1, (empty($appInfo->n_factory_telephone_no) ? false:true), ['class' => 'field', 'id' => 'n_factory_telephone_no_check', 'onclick' => "toggleCheckBox('n_factory_telephone_no_check', ['n_factory_telephone_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_telephone_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Mobile No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_mobile_no', $appInfo->factory_mobile_no,['class'=>'form-control input-md', 'id' => 'factory_mobile_no']) !!}--}}
{{--                                                {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_mobile_no]" value="Factory mobile no."/>--}}
{{--                                                <div class="input-group mobile-plugin">--}}
{{--                                                    {!! Form::text('n_factory_mobile_no', $appInfo->n_factory_mobile_no,['class'=>'form-control input-md', 'id' => 'n_factory_mobile_no', (empty($appInfo->n_factory_mobile_no) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon" style="padding: 8px 24px 6px 12px;">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_mobile_no]", 1, (empty($appInfo->n_factory_mobile_no) ? false:true), ['class' => 'field', 'id' => 'n_factory_mobile_no_check', 'onclick' => "toggleCheckBox('n_factory_mobile_no_check', ['n_factory_mobile_no']);"]) !!}--}}
{{--                                                    </span>--}}
{{--                                                </div>--}}
{{--                                                {!! $errors->first('n_factory_mobile_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td>Fax No.</td>--}}
{{--                                            <td class="light-yellow">--}}
{{--                                                {!! Form::text('factory_fax_no', $appInfo->factory_fax_no,['class'=>'form-control input-md', 'id' => 'factory_fax_no']) !!}--}}
{{--                                                {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}--}}
{{--                                            </td>--}}
{{--                                            <td class="light-green">--}}
{{--                                                <input type="hidden" name="caption[n_factory_fax_no]" value="Factory fax no."/>--}}
{{--                                                <div class="input-group">--}}
{{--                                                    {!! Form::text('n_factory_fax_no', $appInfo->n_factory_fax_no,['class'=>'form-control input-md', 'id' => 'n_factory_fax_no', (empty($appInfo->n_factory_fax_no) ? 'disabled' : '')]) !!}--}}
{{--                                                    <span class="input-group-addon">--}}
{{--                                                    {!! Form::checkbox("toggleCheck[n_factory_fax_no]", 1, (empty($appInfo->n_factory_fax_no) ? false:true), ['class' => 'field', 'id' => 'n_factory_fax_no_check', 'onclick' => "toggleCheckBox('n_factory_fax_no_check', ['n_factory_fax_no']);"]) !!}--}}
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
                                                    {!! Form::text('emp_name', $appInfo->emp_name, ['class'=>'form-control input-md custom_readonly required textOnly', 'id'=>"emp_name"]) !!}
                                                    {!! $errors->first('emp_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_name]" value="Full Name"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_emp_name', $appInfo->n_emp_name, ['class'=>'form-control input-md', 'id'=>"n_emp_name", (empty($appInfo->n_emp_name) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_name]", 1, (empty($appInfo->n_emp_name) ? false : true), ['class' => 'field', 'id' => 'n_emp_name_check', 'onclick' => "toggleCheckBox('n_emp_name_check', ['n_emp_name']);"]) !!}
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
                                                    {!! Form::text('emp_designation', $appInfo->emp_designation, ['class'=>'form-control required input-md custom_readonly', 'id'=>"emp_designation"]) !!}
                                                    {!! $errors->first('emp_designation','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_designation]" value="Position/ Designation"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_emp_designation', $appInfo->n_emp_designation, ['class'=>'form-control input-md', 'id'=>"n_emp_designation", (empty($appInfo->n_emp_designation) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_designation]", 1, (empty($appInfo->n_emp_designation) ? false : true), ['class' => 'field', 'id' => 'n_emp_designation_check', 'onclick' => "toggleCheckBox('n_emp_designation_check', ['n_emp_designation']);"]) !!}
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
                                                    {!! Form::text('emp_passport_no', $appInfo->emp_passport_no, ['data-rule-maxlength'=>'20','class'=>'form-control input-md required custom_readonly', 'id'=>"emp_passport_no"]) !!}
                                                    {!! $errors->first('emp_passport_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_passport_no]" value="Passport No."/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_emp_passport_no', $appInfo->n_emp_passport_no, ['class'=>'form-control input-md', 'id'=>"n_emp_passport_no", (empty($appInfo->n_emp_passport_no) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_passport_no]", 1, (empty($appInfo->n_emp_passport_no) ? false : true), ['class' => 'field', 'id' => 'n_emp_passport_no_check', 'onclick' => "toggleCheckBox('n_emp_passport_no_check', ['n_emp_passport_no']);"]) !!}
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
                                                    {!! Form::select('emp_nationality_id', $nationality, $appInfo->emp_nationality_id, ['placeholder' => 'Select One', 'class' => 'form-control required cusReadonly input-md ', 'id'=>'emp_nationality_id']) !!}
                                                    {!! $errors->first('emp_nationality_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="light-green">
                                                    <input type="hidden" name="caption[n_emp_nationality_id]" value="Nationality"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_emp_nationality_id', $nationality, $appInfo->n_emp_nationality_id, ['placeholder' => 'Select One', 'class' => 'form-control input-md','id'=>'n_emp_nationality_id', (empty($appInfo->n_emp_nationality_id) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                    {!! Form::checkbox("toggleCheck[n_emp_nationality_id]", 1, (empty($appInfo->n_emp_nationality_id) ? false : true), ['class' => 'field', 'id' => 'n_emp_nationality_id_check', 'onclick' => "toggleCheckBox('n_emp_nationality_id_check', ['n_emp_nationality_id']);"]) !!}
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
                                                            {!! Form::text('p_duration_start_date',(!empty($appInfo->p_duration_start_date) ? date('d-M-Y', strtotime($appInfo->p_duration_start_date)) : ''), ['class' => 'form-control input-md custom_readonly date yellow', 'placeholder'=>'dd-mm-yyyy', 'id' => 'p_duration_start_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('p_duration_start_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" {{$errors->has('p_duration_end_date') ? 'has-error': ''}}">
                                                        <div class="datepicker input-group date" id="pd_end_datepicker">
                                                            {!! Form::text('p_duration_end_date',(!empty($appInfo->p_duration_end_date) ? date('d-M-Y', strtotime($appInfo->p_duration_end_date)) : ''), ['class' => 'form-control custom_readonly input-md date yellow', 'placeholder'=>'dd-mm-yyyy', 'id' => 'p_duration_end_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('p_duration_end_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" {{$errors->has('p_desired_duration') ? 'has-error': ''}}">
                                                        {!! Form::text('p_desired_duration',$appInfo->p_desired_duration, ['class' => 'form-control custom_readonly  input-md yellow','id' => 'p_desired_duration']) !!}
                                                        {!! $errors->first('p_desired_duration','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="light-green">
                                                <td>
                                                    <div class="input-group">
                                                        {{-- {!! Form::checkbox("toggleCheck[n_p_duration]", 1, (empty(($appInfo->n_duration_start_date != '0000-00-00') || ($appInfo->n_duration_end_date != '0000-00-00') || $appInfo->n_desired_duration) ? null : true), ['class' => 'field', 'id' => 'n_p_duration_check', 'onclick' => "toggleCheckBox('n_p_duration_check', ['n_p_duration_start_date', 'n_p_duration_end_date', 'n_p_desired_duration']);"]) !!} --}}
                                                        {!! Form::checkbox("toggleCheck[n_p_duration]", 1, (empty(!empty($appInfo->n_duration_start_date) || !empty($appInfo->n_duration_end_date) || $appInfo->n_desired_duration) ? null : true), ['class' => 'field', 'id' => 'n_p_duration_check', 'onclick' => "toggleCheckBox('n_p_duration_check', ['n_p_duration_start_date', 'n_p_duration_end_date', 'n_p_desired_duration']);"]) !!}
                                                    </div>
                                                </td>
                                                <td class="bg-green">
                                                    Proposed information
                                                </td>
                                                <td>
                                                    <div class=" {{$errors->has('n_p_duration_start_date') ? 'has-error': ''}}">
                                                        <input type="hidden" name="caption[n_p_duration_start_date]" value="Start Date"/>
                                                        <div class="">
                                                            <div id="duration_start_datepicker" class="input-group date">
                                                                {!! Form::text('n_p_duration_start_date', (!empty($appInfo->n_duration_start_date) ? date('d-M-Y', strtotime($appInfo->n_duration_start_date)) : ''), ['class' => 'form-control input-md date green', 'placeholder'=>'dd-mm-yyyy', 'id' => 'n_p_duration_start_date', (empty($appInfo->n_duration_start_date) ? 'disabled' : '')]) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('n_p_duration_start_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="{{$errors->has('n_p_duration_end_date') ? 'has-error': ''}}">
                                                        <input type="hidden" name="caption[n_p_duration_end_date]" value="End Date"/>
                                                        <div class="">
                                                            <div id="duration_end_datepicker" class="datepicker input-group date">
                                                                {!! Form::text('n_p_duration_end_date', (!empty($appInfo->n_duration_end_date) ? date('d-M-Y', strtotime($appInfo->n_duration_end_date)) : ''), ['class' => 'form-control input-md date green', 'placeholder'=>'dd-mm-yyyy', 'id' => 'n_p_duration_end_date', (empty($appInfo->n_duration_end_date) ? 'disabled' : '')]) !!}
                                                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('n_p_duration_end_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class=" {{$errors->has('n_p_desired_duration') ? 'has-error': ''}}">
                                                        <input type="hidden" name="caption[n_p_desired_duration]" value="Desired Duration"/>
                                                        <div class="">
                                                            {!! Form::text('n_p_desired_duration', $appInfo->n_desired_duration, ['class' => 'form-control input-md green', 'id' => 'n_p_desired_duration', 'readonly', (empty($appInfo->n_desired_duration) ? 'disabled' : '')]) !!}
                                                        </div>
                                                        {!! $errors->first('n_p_desired_duration','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Compensation and Benefit</legend>
                                        <div class="table-responsive" id="compensationTableId">
                                            <table class="table table-striped table-bordered" cellspacing="0" width="100%" aria-label="Detailed Compensation and Benefit">
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
                                                                {!! Form::checkbox("CBtoggleCheck[n_basic_payment_type_id]", 1, (empty($appInfo->n_basic_payment_type_id || $appInfo->n_basic_local_amount || $appInfo->n_basic_local_currency_id) ? false : true), ['class' => 'field', 'id' => 'n_basic_payment_type_id_check', 'onclick' => "toggleCheckBox('n_basic_payment_type_id_check', ['n_basic_payment_type_id', 'n_basic_local_amount', 'n_basic_local_currency_id']);"]) !!}
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
                                                            {!! Form::select('basic_payment_type_id', $paymentMethods, $appInfo->basic_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control required custom_readonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('basic_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('basic_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('basic_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->basic_local_amount) : $appInfo->basic_local_amount , ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md number cb_req_field', 'step' => '0.01', 'id' => 'basic_local_amount']) !!}
                                                            {!! $errors->first('basic_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('basic_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_local_currency_id', $currencies, $appInfo->basic_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control required custom_readonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('basic_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_basic_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_basic_payment_type_id', $paymentMethods, $appInfo->n_basic_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_basic_payment_type_id', (empty($appInfo->n_basic_payment_type_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_basic_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_basic_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_basic_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->n_basic_local_amount) : $appInfo->n_basic_local_amount , ['data-rule-maxlength'=>'40','class' => 'form-control input-md number green', 'step' => '0.01', 'id'=>'n_basic_local_amount', (empty($appInfo->n_basic_local_amount) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_basic_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_basic_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_basic_local_currency_id', $currencies, $appInfo->n_basic_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_basic_local_currency_id', (empty($appInfo->n_basic_local_currency_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_basic_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_overseas_payment_type_id]", 1, (empty($appInfo->n_overseas_payment_type_id || $appInfo->n_overseas_local_amount || $appInfo->n_overseas_local_currency_id) ? false : true), ['class' => 'field', 'id' => 'n_overseas_payment_type_id_check', 'onclick' => "toggleCheckBox('n_overseas_payment_type_id_check', ['n_overseas_payment_type_id', 'n_overseas_local_amount', 'n_overseas_local_currency_id']);"]) !!}
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
                                                            {!! Form::select('overseas_payment_type_id', $paymentMethods, $appInfo->overseas_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md yellow', 
                                                                'id' => 'overseas_payment_type_id', 'onchange' => "dependentRequire('overseas_payment_type_id', ['overseas_local_amount', 'overseas_local_currency_id']);"]) !!}
                                                            {!! $errors->first('overseas_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('overseas_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('overseas_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->overseas_local_amount) : $appInfo->overseas_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md number cb_req_field', 'step' => '0.01', 
                                                                'id' => 'overseas_local_amount', 'onchange' => "dependentRequire('overseas_local_amount', ['overseas_payment_type_id', 'overseas_local_currency_id']);"]) !!}
                                                            {!! $errors->first('overseas_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('overseas_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_local_currency_id', $currencies, $appInfo->overseas_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md yellow', 'id' => 'overseas_local_currency_id']) !!}
                                                            {!! $errors->first('overseas_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_overseas_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_overseas_payment_type_id', $paymentMethods, $appInfo->n_overseas_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_overseas_payment_type_id', (empty($appInfo->n_overseas_payment_type_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_overseas_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_overseas_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_overseas_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->n_overseas_local_amount) : $appInfo->n_overseas_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md number green', 'step' => '0.01', 'id' => 'n_overseas_local_amount', (empty($appInfo->n_overseas_local_amount) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_overseas_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_overseas_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_overseas_local_currency_id', $currencies, $appInfo->n_overseas_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_overseas_local_currency_id', (empty($appInfo->n_overseas_local_currency_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_overseas_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_house_payment_type_id]", 1, (empty($appInfo->n_house_payment_type_id || $appInfo->n_house_local_amount || $appInfo->n_house_local_currency_id) ? false : true), ['class' => 'field', 'id' => 'n_house_payment_type_id_check', 'onclick' => "toggleCheckBox('n_house_payment_type_id_check', ['n_house_payment_type_id', 'n_house_local_amount', 'n_house_local_currency_id']);"]) !!}
                                                            </span>
                                                            <div class="form-control">
                                                                <div style="position: relative">
                                                                    <span class="helpTextCom" id="house_local_amount_label">c. House rent</span>
                                                                </div>
                                                                <input type="hidden" name="caption[n_house_rent]" value="House rent"/>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('house_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('house_payment_type_id', $paymentMethods, $appInfo->house_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id' => 'house_payment_type_id', 'onchange' => "dependentRequire('house_payment_type_id', ['house_local_amount', 'house_local_currency_id']);"]) !!}
                                                            {!! $errors->first('house_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('house_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('house_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->house_local_amount) : $appInfo->house_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md number cb_req_field', 'step' => '0.01', 
                                                                'id'=>'house_local_amount', 'onchange' => "dependentRequire('house_local_amount', ['house_payment_type_id', 'house_local_currency_id']);"]) !!}
                                                            {!! $errors->first('house_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('house_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('house_local_currency_id', $currencies, $appInfo->house_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id' => 'house_local_currency_id']) !!}
                                                            {!! $errors->first('house_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_house_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_house_payment_type_id', $paymentMethods, $appInfo->n_house_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_house_payment_type_id', (empty($appInfo->n_house_payment_type_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_house_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_house_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_house_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->n_house_local_amount) : $appInfo->n_house_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md number green', 'step' => '0.01', 'id' => 'n_house_local_amount', (empty($appInfo->n_house_local_amount) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_house_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_house_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_house_local_currency_id', $currencies, $appInfo->n_house_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_house_local_currency_id', (empty($appInfo->n_house_local_currency_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_house_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_conveyance_payment_type_id]", 1, (empty($appInfo->n_conveyance_payment_type_id || $appInfo->n_conveyance_local_amount || $appInfo->n_conveyance_local_currency_id) ? false : true), ['class' => 'field', 'id' => 'n_conveyance_payment_type_id_check', 'onclick' => "toggleCheckBox('n_conveyance_payment_type_id_check', ['n_conveyance_payment_type_id', 'n_conveyance_local_amount', 'n_conveyance_local_currency_id']);"]) !!}
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
                                                            {!! Form::select('conveyance_payment_type_id', $paymentMethods, $appInfo->conveyance_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'conveyance_payment_type_id', 'onchange' => "dependentRequire('conveyance_payment_type_id', ['conveyance_local_amount', 'conveyance_local_currency_id']);"]) !!}
                                                            {!! $errors->first('conveyance_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('conveyance_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('conveyance_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->conveyance_local_amount) : $appInfo->conveyance_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md number cb_req_field', 'step' => '0.01', 
                                                                'id' => 'conveyance_local_amount', 'onchange' => "dependentRequire('conveyance_local_amount', ['conveyance_payment_type_id', 'conveyance_local_currency_id']);"]) !!}
                                                            {!! $errors->first('conveyance_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('conveyance_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_local_currency_id', $currencies, $appInfo->conveyance_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'conveyance_local_currency_id']) !!}
                                                            {!! $errors->first('conveyance_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_conveyance_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_conveyance_payment_type_id', $paymentMethods, $appInfo->n_conveyance_payment_type_id, ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md green', 'id' => 'n_conveyance_payment_type_id', (empty($appInfo->n_conveyance_payment_type_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_conveyance_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_conveyance_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_conveyance_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->n_conveyance_local_amount) : $appInfo->n_conveyance_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md number green', 'step' => '0.01', 'id' => 'n_conveyance_local_amount', (empty($appInfo->n_conveyance_local_amount) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_conveyance_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_conveyance_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_conveyance_local_currency_id', $currencies, $appInfo->n_conveyance_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_conveyance_local_currency_id', (empty($appInfo->n_conveyance_local_currency_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_conveyance_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_medical_payment_type_id]", 1, (empty($appInfo->n_medical_payment_type_id || $appInfo->n_medical_local_amount || $appInfo->n_medical_local_currency_id) ? false : true), ['class' => 'field', 'id' => 'n_medical_payment_type_id_check', 'onclick' => "toggleCheckBox('n_medical_payment_type_id_check', ['n_medical_payment_type_id', 'n_medical_local_amount', 'n_medical_local_currency_id']);"]) !!}
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
                                                            {!! Form::select('medical_payment_type_id', $paymentMethods, $appInfo->medical_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'medical_payment_type_id', 'onchange' => "dependentRequire('medical_payment_type_id', ['medical_local_amount', 'medical_local_currency_id']);"]) !!}
                                                            {!! $errors->first('medical_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('medical_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('medical_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->medical_local_amount) : $appInfo->medical_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md number cb_req_field', 'step' => '0.01', 
                                                                'id'=>'medical_local_amount', 'onchange' => "dependentRequire('medical_local_amount', ['medical_payment_type_id', 'medical_local_currency_id']);"]) !!}
                                                            {!! $errors->first('medical_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('medical_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_local_currency_id', $currencies, $appInfo->medical_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'medical_local_currency_id']) !!}
                                                            {!! $errors->first('medical_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_medical_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_medical_payment_type_id', $paymentMethods, $appInfo->n_medical_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_medical_payment_type_id', (empty($appInfo->n_medical_payment_type_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_medical_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_medical_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_medical_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->n_medical_local_amount) : $appInfo->n_medical_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md number green', 'step' => '0.01', 'id' => 'n_medical_local_amount', (empty($appInfo->n_medical_local_amount) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_medical_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_medical_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_medical_local_currency_id', $currencies, $appInfo->n_medical_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_medical_local_currency_id', (empty($appInfo->n_medical_local_currency_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_medical_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_ent_payment_type_id]", 1, (empty($appInfo->n_ent_payment_type_id || $appInfo->n_ent_local_amount || $appInfo->n_ent_local_currency_id) ? false : true), ['class' => 'field', 'id' => 'n_ent_payment_type_id_check', 'onclick' => "toggleCheckBox('n_ent_payment_type_id_check', ['n_ent_payment_type_id', 'n_ent_local_amount', 'n_ent_local_currency_id']);"]) !!}
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
                                                            {!! Form::select('ent_payment_type_id', $paymentMethods, $appInfo->ent_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'ent_payment_type_id', 'onchange' => "dependentRequire('ent_payment_type_id', ['ent_local_amount', 'ent_local_currency_id']);"]) !!}
                                                            {!! $errors->first('ent_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('ent_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('ent_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->ent_local_amount) : $appInfo->ent_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md number cb_req_field', 'step' => '0.01', 
                                                                'id' => 'ent_local_amount', 'onchange' => "dependentRequire('ent_local_amount', ['ent_payment_type_id', 'ent_local_currency_id']);"]) !!}
                                                            {!! $errors->first('ent_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('ent_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_local_currency_id', $currencies, $appInfo->ent_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'ent_local_currency_id']) !!}
                                                            {!! $errors->first('ent_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_ent_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_ent_payment_type_id', $paymentMethods, $appInfo->n_ent_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_ent_payment_type_id', (empty($appInfo->n_ent_payment_type_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_ent_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_ent_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_ent_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->n_ent_local_amount) : $appInfo->n_ent_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md number green', 'step' => '0.01', 'id' => 'n_ent_local_amount', (empty($appInfo->n_ent_local_amount) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_ent_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_ent_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_ent_local_currency_id', $currencies, $appInfo->n_ent_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_ent_local_currency_id', (empty($appInfo->n_ent_local_currency_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_ent_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_bonus_payment_type_id]", 1, (empty($appInfo->n_bonus_payment_type_id || $appInfo->n_bonus_local_amount || $appInfo->n_bonus_local_currency_id) ? false : true), ['class' => 'field', 'id' => 'n_bonus_payment_type_id_check', 'onclick' => "toggleCheckBox('n_bonus_payment_type_id_check', ['n_bonus_payment_type_id', 'n_bonus_local_amount', 'n_bonus_local_currency_id']);"]) !!}
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
                                                            {!! Form::select('bonus_payment_type_id', $paymentMethods, $appInfo->bonus_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 
                                                                'id'=>'bonus_payment_type_id', 'onchange' => "dependentRequire('bonus_payment_type_id', ['bonus_local_amount', 'bonus_local_currency_id']);"]) !!}
                                                            {!! $errors->first('bonus_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('bonus_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('bonus_local_amount', $viewMode == 'on' ? CommonFunction::convertToBdtAmount($appInfo->bonus_local_amount) : $appInfo->bonus_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md number cb_req_field', 'step' => '0.01', 
                                                                'id' => 'bonus_local_amount', 'onchange' => "dependentRequire('bonus_local_amount', ['bonus_payment_type_id', 'bonus_local_currency_id']);"]) !!}
                                                            {!! $errors->first('bonus_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-yellow">
                                                        <div class="{{ $errors->has('bonus_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_local_currency_id', $currencies, $appInfo->bonus_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control custom_readonly input-md cb_req_field', 'id'=>'bonus_local_currency_id']) !!}
                                                            {!! $errors->first('bonus_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>

                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_bonus_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('n_bonus_payment_type_id', $paymentMethods, $appInfo->n_bonus_payment_type_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_bonus_payment_type_id', (empty($appInfo->n_bonus_payment_type_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_bonus_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_bonus_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('n_bonus_local_amount', $viewMode == 'on' ? \App\Libraries\CommonFunction::convertToBdtAmount($appInfo->n_bonus_local_amount) : $appInfo->n_bonus_local_amount, ['data-rule-maxlength'=>'40','class' => 'form-control input-md number green', 'step' => '0.01', 'id' => 'n_bonus_local_amount', (empty($appInfo->n_bonus_local_amount) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_bonus_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td class="light-green">
                                                        <div class="{{ $errors->has('n_bonus_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('n_bonus_local_currency_id', $currencies, $appInfo->n_bonus_local_currency_id, ['data-rule-maxlength'=>'40','class' => 'form-control input-md green', 'id' => 'n_bonus_local_currency_id', (empty($appInfo->n_bonus_local_currency_id) ? 'disabled' : '')]) !!}
                                                            {!! $errors->first('n_bonus_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("CBtoggleCheck[n_other_benefits]", 1,
                                                                (empty($appInfo->n_other_benefits) ? false : true),
                                                                ['class' => 'field', 'id' => 'n_other_benefits_check', 'onclick' => "toggleCheckBox('n_other_benefits_check', ['n_other_benefits']);"]) !!}
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
                                                            {!! Form::textarea('other_benefits', $appInfo->other_benefits, ['class' => 'form-control custom_readonly input-md bigInputField yellow', 'data-charcount-maxlength' => '350', 'size' =>'5x2','data-rule-maxlength'=>'350', 'placeholder' => 'Maximum 350 characters', 'id' => 'other_benefits']) !!}
                                                            {!! $errors->first('other_benefits','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td colspan="3" class="light-green">
                                                        <div class="{{ $errors->has('n_other_benefits')?'has-error':'' }}">
                                                            {!! Form::textarea('n_other_benefits', $appInfo->n_other_benefits,['class' => 'form-control input-md bigInputField green', 'data-charcount-maxlength' => '350', 'size' =>'5x2','data-rule-maxlength'=>'350', 'placeholder' => 'Maximum 350 characters', 'id' => 'n_other_benefits', (empty($appInfo->n_other_benefits) ? 'disabled' : '')]) !!}
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
                                                    {!! Form::label('effective_date','Effective date of Amendment',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div class="datepicker input-group date">
                                                            {!! Form::text('effective_date', (!empty($appInfo->effective_date) ? date('d-M-Y', strtotime($appInfo->effective_date)) : ''), ['class' => 'form-control input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
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

                        <h3 class="text-center stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv">
                                @include('WorkPermitAmendment::documents')
                            </div>
                            @if($viewMode != 'off')
                                @include('WorkPermitAmendment::doc-tab')
                            @endif
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
                                                            {!! Form::text('auth_full_name', $appInfo->auth_full_name, ['class' => 'form-control required input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', $appInfo->auth_designation, ['class' => 'form-control required input-md', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'form-control required input-sm phone_or_mobile', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', $appInfo->auth_email, ['class' => 'form-control required input-sm email', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail img-user"
                                                                 src="{{ (!empty($appInfo->auth_image) ? url('users/upload/'.$appInfo->auth_image) : url('users/upload/'.Auth::user()->user_pic)) }}"
                                                                 alt="User Photo">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="auth_image" value="{{ (!empty($appInfo->auth_image) ? $appInfo->auth_image : Auth::user()->user_pic) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('accept_terms',1, ($appInfo->accept_terms == 1) ? true : false, array('id'=>'accept_terms', 'class'=>'required')) !!}
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
                            @if($viewMode != 'on')
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
                                                        {!! Form::text('sfp_contact_name', $appInfo->sfp_contact_name, ['class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md email required']) !!}
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
                                                        {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control input-md phone_or_mobile required']) !!}
                                                        {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_contact_address', $appInfo->sfp_contact_address, ['class' => 'bigInputField form-control input-md required']) !!}
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
                                                        {!! Form::text('sfp_pay_amount', $appInfo->sfp_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_vat_on_pay_amount', $appInfo->sfp_vat_on_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
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
                                                        {!! Form::text('sfp_total_amount', number_format($appInfo->sfp_total_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_status','Payment Status',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        @if($appInfo->sfp_payment_status == 0)
                                                            <span class="label label-warning">Pending</span>
                                                        @elseif($appInfo->sfp_payment_status == -1)
                                                            <span class="label label-info">In-Progress</span>
                                                        @elseif($appInfo->sfp_payment_status == 1)
                                                            <span class="label label-success">Paid</span>
                                                        @elseif($appInfo->sfp_payment_status == 2)
                                                            <span class="label label-danger">-Exception</span>
                                                        @elseif($appInfo->sfp_payment_status == 3)
                                                            <span class="label label-warning">Waiting for Payment Confirmation</span>
                                                        @else
                                                            <span class="label label-warning">invalid status</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($appInfo->sfp_payment_status != 1)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="alert alert-danger" role="alert">
                                                            <b>Vat/ Tax</b> and <b>Transaction charge</b> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </fieldset>

                        @if(ACL::getAccsessRight('WorkPermitAmendment','-E-') && $viewMode != "on" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                            
                            @if(!in_array($appInfo->status_id,[5,22]))
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
                            @endif

                            @if(in_array($appInfo->status_id,[5,22]))
                                <div class="pull-left">
                                    <span style="display: block; height: 34px">&nbsp;</span>
                                </div>
                                <div class="pull-left" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-info btn-md"
                                            value="resubmit" name="actionBtn">Re-submit
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

                    {!! Form::close() !!}<!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
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

    function validateCorrectionForm() {
        var atLeastOneChecked = $('input:checkbox').is(':checked');

        if(atLeastOneChecked){
            return true;
        }else{
            alert('In order to Submit please select at least one field.');
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

    let is_approval_online = '{{ $appInfo->is_approval_online }}';
    if (is_approval_online == 'yes') {
        wpApplication(is_approval_online);

        $(".custom_readonly").attr('readonly', true);
        $(".custom_readonly option:not(:selected)").remove();
        $(".custom_readonly:radio:not(:checked)").attr('disabled', true);
    }

    $(document).ready(function(){
        @if ($viewMode != 'on')
        let form = $("#WorkPermitAmendmentForm").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {

                if(newIndex === 2){
                    var atLeastOneChecked = $('input:checkbox.field').is(':checked');
                    if(atLeastOneChecked == false) {
                        alert('In order to Proceed please select at least one field for amendment.');
                        return false;
                    }
                }

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
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
        @endif

        let popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/work-permit-amendment/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        // Bootstrap Tooltip initialize
        $('[data-toggle="tooltip"]').tooltip();

        // Datepicker Plugin initialize
        let today = new Date();
        let yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100)
        });

        $('input[name=is_approval_online]:checked').trigger('click');

        let compensationBenefitCheckedCounter = $('#compensationTableId input[type="checkbox"]:checked').length;
        if (compensationBenefitCheckedCounter > 0) {
            document.getElementById('effective_date_fieldset').style.display = 'block';
            document.getElementById('effective_date').classList.add('required');
        } else {
            document.getElementById('effective_date_fieldset').style.display = 'none';
            document.getElementById('effective_date').classList.remove('required');
        }


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

        $("#office_division_id").trigger('change');
        $("#office_district_id").trigger('change');
        $("#n_office_division_id").trigger('change');
        $("#n_office_district_id").trigger('change');

        $("#factory_district_id").trigger('change');
        $("#n_factory_district_id").trigger('change');
    });

    function toggleCheckBox(boxId, newFieldId) {
        console.log(boxId);
        $.each(newFieldId, function (id, val) {
            // console.log(val + '  ' + document.getElementById(boxId).checked);
            if (document.getElementById(boxId).checked) {
                document.getElementById(val).disabled = false;
                var field = document.getElementById(val);
                $(field).addClass("required");
            } else {
                document.getElementById(val).disabled = true;
                var field = document.getElementById(val);
                $(field).removeClass("required");
                $(field).removeClass("error");
                $(field).val("");
            }
        });

        //for effective date div
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

    $(document).ready(function() {
        var checkedCheckboxes = $('input[type="checkbox"]:checked');
        checkedCheckboxes.each(function() {
            var boxId = $(this).attr('id');
            var button = $('#'+boxId);
            if (button.attr('onclick')) {
                // Extract the function name and parameters from the onclick attribute
                var onclickValue = button.attr('onclick');
                var functionName = onclickValue.split('(')[1].split(')')[0].split('[')[1].split(']')[0].split(',');
                const newFieldId = functionName.map(element => {
                    return element.replace(/'/g, '').trim();
                });
                toggleCheckBox(boxId,newFieldId);
            }
        });
    });
    
</script>

{{--initail -input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
{{--//textarea count down--}}
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" type="text/javascript"></script>

<script>
    $(function () {
        //max text count down
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
            }else{
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

            let startDateVal = $("#"+pd_startDateValID).val();

            if (startDateVal != '') {
                // Min value set for end date
                $("#"+pd_endDateDivID).data("DateTimePicker").minDate(e.date);
                let endDateVal = $("#"+pd_endDateValID).val();
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

            let startDateVal = $("#"+pd_startDateValID).val();

            if (startDateVal === '') {
                $("#"+pd_startDateValID).addClass('error');
            } else {
                let day = moment(startDateVal, ['DD-MMM-YYYY']);
                //var minStartDate = moment(day).add(1, 'day');
                $("#"+pd_endDateDivID).data("DateTimePicker").minDate(day);
            }

            let endDateVal = $("#"+pd_endDateValID).val();

            if (startDateVal != '' && endDateVal != '') {
                getDesiredDurationAmount(process_id, startDateVal, endDateVal, pd_show_durationID, 0, 0);
            } else {
                $("#"+pd_show_durationID).val('');
            }
        });

    });
</script>