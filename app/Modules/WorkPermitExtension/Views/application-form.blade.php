<?php
$accessMode = ACL::getAccsessRight('WorkPermitExtension');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .form-group {
        margin-bottom: 2px;
    }

    .img-thumbnail {
        height: 100px;
        width: 100px;
    }

    textarea {
        resize: vertical;
    }

    .wizard > .steps > ul > li {
        width: 19.95% !important;
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

    .custom-file-input {
        color: transparent;
        border: none !important;
    }

    .custom-file-input::-webkit-file-upload-button {
        visibility: hidden;
    }

    .custom-file-input::before {
        content: 'Browse';
        color: black;
        display: inline-block;
        background: -webkit-linear-gradient(top, #f9f9f9, #e3e3e3);
        border: 1px solid #999;
        border-radius: 3px;
        padding: 5px 8px;
        outline: none;
        white-space: nowrap;
        -webkit-user-select: none;
        cursor: pointer;
        text-shadow: 1px 1px #fff;
        font-weight: 700;
        font-size: 10pt;
    }

    .custom-file-input:hover::before {
        border-color: black;
    }

    .custom-file-input:active, .custom-file-input:focus {
        outline: 0;
    }

    .custom-file-input:active::before {
        background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
    }
</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box" id="inputForm">
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
                            <h5><strong>Application for Work Permit Extension</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/work_permit_extension.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'work-permit-extension/store','method' => 'post','id' => 'WorkPermitExtensionForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>
                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        <input type="hidden" name="ref_app_approve_date" value="{{ (Session::get('wpnInfo.approved_date') ? Session::get('wpnInfo.approved_date') : '') }}">

                        <h3 class="stepHeader">Basic Instructions</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic Instructions</strong></div>
                                <div class="panel-body">

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('last_work_permit') ? 'has-error': ''}}">
                                                {!! Form::label('last_work_permit','Did you receive last work-permit through online OSS?',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <label class="radio-inline">{!! Form::radio('last_work_permit','yes', (Session::get('wpnInfo.last_work_permit') == 'yes' ? true :false), ['class'=>'cusReadonly required helpTextRadio', 'id' => 'last_work_permit_yes','onclick' => 'lastWorkPermit(this.value)']) !!}
                                                        Yes</label>
                                                    <label class="radio-inline">{!! Form::radio('last_work_permit', 'no', (Session::get('wpnInfo.last_work_permit') == 'no' ? true :false), ['class'=>'cusReadonly required', 'id' => 'last_work_permit_no', 'onclick' => 'lastWorkPermit(this.value)']) !!}
                                                        No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div id="ref_app_tracking_no_div"
                                                 class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                {!! Form::label('ref_app_tracking_no','Please give your approved work permit reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                                        {!! Form::text('ref_app_tracking_no', Session::get('wpnInfo.ref_app_tracking_no'), ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm cusReadonly']) !!}
                                                        {!! $errors->first('ref_app_tracking_no','<span class="help-block">:message</span>') !!}
                                                        <span class="input-group-btn">

                                                            @if(Session::get('wpnInfo'))
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                        value="clean_load_data" name="actionBtn">Clear Loaded Data</button>

                                                                <a href="{{ Session::get('wpnInfo.certificate_link') }}"
                                                                   target="_blank" rel="noopener" class="btn btn-success btn-sm">View Certificate</a>
                                                            @else
                                                                <button type="submit"
                                                                        class="btn btn-success btn-sm cancel"
                                                                        value="searchWPNinfo" name="searchWPNinfo"
                                                                        id="searchWPNinfo">Load Work Permit Data</button>
                                                            @endif

                                                        </span>
                                                    </div>
                                                    <small class="text-danger">N.B.: Once you save or submit the
                                                        application, the Work permit tracking no cannot be changed
                                                        anymore.
                                                    </small>
                                                </div>
                                            </div>
                                            <div id="manually_approved_no_div"
                                                 class="col-md-12 hidden {{$errors->has('manually_approved_wp_no') ? 'has-error': ''}} ">
                                                {!! Form::label('manually_approved_wp_no','Please give your manually approved work permit reference  No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('manually_approved_wp_no', '', ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm required']) !!}
                                                    {!! $errors->first('manually_approved_wp_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">                                                   
                                        <div class="row">
                                            <div id="issue_date_of_first_div"
                                                 class="col-md-6 hidden {{$errors->has('issue_date_of_first_wp') ? 'has-error': ''}}">

                                                {!! Form::label('issue_date_of_first_wp','Effective date of the first Work Permit',['class'=>'text-left required-star col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('issue_date_of_first_wp', (Session::get('wpnInfo.approved_duration_start_date') ? date('d-M-Y', strtotime(Session::get('wpnInfo.approved_duration_start_date'))) : ''), ['class' => 'form-control required cusReadonly input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}

                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('issue_date_of_first_wp','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div id="work_permit_type_div"
                                                 class="col-md-6 hidden {{$errors->has('work_permit_type') ? 'has-error': ''}}">
                                                {!! Form::label('work_permit_type','Type of visa',['class'=>'text-left required-star col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('work_permit_type', $WP_visaTypes, Session::get('wpnInfo.work_permit_type'), ['class' => 'form-control required input-md','placeholder' => 'Select one']) !!}
                                                    {!! $errors->first('work_permit_type','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--Show only commercial department--}}
                                    @if($department_id == 1 || $department_id == '1')
                                        <div class="form-group">
                                            <div class="row">
                                                <div id="expiry_date_of_op_div"
                                                     class="col-md-6 hidden {{$errors->has('expiry_date_of_op') ? 'has-error': ''}}">
                                                    {!! Form::label('expiry_date_of_op','Expiry Date of Office Permission',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="datepicker input-group date">
                                                            {!! Form::text('expiry_date_of_op', (Session::get('wpnInfo.expiry_date_of_op') ? date('d-M-Y', strtotime(Session::get('wpnInfo.expiry_date_of_op'))) : ''), ['class' => 'form-control cusReadonly input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('expiry_date_of_op','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <fieldset class="scheduler-border hidden" id="duration_div">
                                        <legend class="scheduler-border">Desired duration for work permit</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('duration_start_date') ? 'has-error': ''}}">
                                                    {!! Form::label('duration_start_date','Start Date',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div id="duration_start_datepicker" class="input-group date">
                                                            {!! Form::text('duration_start_date', '', ['class' => 'form-control required input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('duration_start_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('duration_end_date') ? 'has-error': ''}}">
                                                    {!! Form::label('duration_end_date','End Date',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div id="duration_end_datepicker" class="input-group date">
                                                            {!! Form::text('duration_end_date', '', ['class' => 'form-control input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        <span class="text-danger" style="font-size: 12px; font-weight: bold" id="date_compare_error"></span>
                                                        {!! $errors->first('duration_end_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('desired_duration') ? 'has-error': ''}}">
                                                    {!! Form::label('desired_duration','Desired Duration',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('desired_duration', '', ['class' => 'form-control input-md required', 'readonly']) !!}
                                                        {!! $errors->first('desired_duration','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 {{$errors->has('duration_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('duration_amount','Payable amount (BDT)',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('duration_amount', '', ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('duration_amount','<span class="help-block">:message</span>') !!}

                                                        {{--Show duration in year--}}
                                                        <span class="text-danger"
                                                              style="font-size: 12px; font-weight: bold"
                                                              id="duration_year"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Applicant Details</h3>
                        <fieldset>

                            {{-- Common Basic Information By Company Id --}}
                            @include('ProcessPath::basic-company-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Information of Expatriate/ Investor/
                                        Employee </strong></div>
                                <div class="panel-body">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">General Information:</legend>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="form-group col-md-12 {{$errors->has('emp_name') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_name','Full Name',['class'=>'col-md-3 text-left required-star']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::text('emp_name', Session::get('wpnInfo.emp_name'), ['class' => 'form-control textOnly input-md required cusReadonly']) !!}
                                                            {!! $errors->first('emp_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 {{$errors->has('emp_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_designation','Position/ Designation',['class'=>'col-md-3 text-left required-star']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('emp_designation', Session::get('wpnInfo.emp_designation'), ['data-rule-maxlength'=>'255', 'class' => 'form-control required bigInputField input-md cusReadonly',
                                                                      'size'=>'5x1','maxlength'=>'255']) !!}
                                                            {!! $errors->first('emp_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="brief_job_description_div"
                                                         class="form-group col-md-12 {{$errors->has('brief_job_description') ? 'has-error': ''}}">
                                                        {!! Form::label('brief_job_description','Brief job description',['class'=>'text-left col-md-3']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('brief_job_description', Session::get('wpnInfo.brief_job_description'), ['data-rule-maxlength'=>'1000', 'placeholder'=>'Brief job description', 'class' => 'form-control bigInputField input-md cusReadonly maxTextCountDown',
                                                                'size'=>'5x1','data-charcount-maxlength'=>'1000']) !!}
                                                            {!! $errors->first('brief_job_description','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 {{$errors->has('investor_photo') ? 'has-error': ''}}">
                                                <div id="investorPhotoViewerDiv">

                                                    <?php
                                                        if(!empty(Session::get('wpnInfo.investor_photo'))) {
                                                            $userPic = file_exists('users/upload/'.Session::get('wpnInfo.investor_photo')) ? url('users/upload/'.Session::get('wpnInfo.investor_photo')) : url('uploads/'.Session::get('wpnInfo.investor_photo'));
                                                        }
                                                    ?>

                                                    <img class="img-thumbnail" id="investorPhotoViewer"
                                                         src="{{ (!empty(Session::get('wpnInfo.investor_photo')) ? $userPic :
                                                         url('assets/images/photo_default.png')) }}"
                                                         alt="Investor Photo">
                                                    <input type="hidden" name="investor_photo_base64"
                                                           id="investor_photo_base64">

                                                    @if(!empty(Session::get('wpnInfo.investor_photo')))
                                                        <input type="hidden" name="investor_photo_name"
                                                               value="{{Session::get('wpnInfo.investor_photo')}}"/>
                                                    @else
                                                        <input type="hidden" name="investor_photo_name"
                                                               id="investor_photo_name">
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    @if($viewMode != 'on')
                                                        {!! Form::label('investor_photo','Photo:', ['class'=>'text-left required-star','style'=>'']) !!}
                                                        <br/>
                                                    @endif
                                                    <span id="investorPhotoUploadError" class="text-danger"></span>

                                                    <input type="file"
                                                           class="custom-file-input {{(!empty(Session::get('wpnInfo.investor_photo')) ? '' : 'required')}}"
                                                           onchange="readURLUser(this);"
                                                           id="investorPhotoUploadBtn"
                                                           name="investorPhotoUploadBtn"
                                                           data-type="user"
                                                           data-ref="{{Encryption::encodeId(Auth::user()->id)}}">

                                                    <a id="investorPhotoResetBtn"
                                                       class="btn btn-sm btn-warning resetIt hidden"
                                                       onclick="resetImage(this);"
                                                       data-src="{{ url('assets/images/photo_default.png') }}"><i
                                                                class="fa fa-refresh"></i> Reset</a>

                                                    <span class="text-danger"
                                                          style="font-size: 9px; font-weight: bold; display: block;">
                                                                [File Format: *.jpg/ .jpeg/ .png | Resize Image]
                                                            </span>
                                                    {!! $errors->first('investor_photo','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Passport Information:</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('emp_passport_no') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_passport_no','Passport No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_passport_no', Session::get('wpnInfo.emp_passport_no'), ['data-rule-maxlength'=>'20', 'class' => 'form-control input-md cusReadonly']) !!}
                                                        {!! $errors->first('emp_passport_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_personal_no') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_personal_no','Personal No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_personal_no', Session::get('wpnInfo.emp_personal_no'), ['data-rule-maxlength'=>'20', 'class' => 'form-control input-md cusReadonly']) !!}
                                                        {!! $errors->first('emp_personal_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('emp_surname') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_surname','Surname',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_surname', Session::get('wpnInfo.emp_surname'), ['class' => 'form-control required textOnly input-md cusReadonly']) !!}
                                                        {!! $errors->first('emp_surname','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('place_of_issue') ? 'has-error': ''}}">
                                                    {!! Form::label('place_of_issue','Issuing authority',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('place_of_issue', Session::get('wpnInfo.place_of_issue'), ['class' => 'form-control required textOnly input-md cusReadonly']) !!}
                                                        {!! $errors->first('place_of_issue','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('emp_given_name') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_given_name','Given Name',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_given_name', Session::get('wpnInfo.emp_given_name'), ['class' => 'form-control required textOnly input-md cusReadonly']) !!}
                                                        {!! $errors->first('emp_given_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_nationality_id') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_nationality_id','Nationality',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('emp_nationality_id', $nationality, Session::get('wpnInfo.emp_nationality_id'), ['placeholder' => 'Select One',
                                                        'class' => 'form-control required input-md cusReadonly']) !!}
                                                        {!! $errors->first('emp_nationality_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('emp_date_of_birth') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_date_of_birth','Date of Birth',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="datepickerDob input-group date">
                                                            {!! Form::text('emp_date_of_birth', (Session::get('wpnInfo.emp_date_of_birth') ? date('d-M-Y', strtotime(Session::get('wpnInfo.emp_date_of_birth'))) : ''), ['class' => 'form-control required input-md cusReadonly date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('emp_date_of_birth','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_place_of_birth') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_place_of_birth','Place of Birth',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_place_of_birth', Session::get('wpnInfo.emp_place_of_birth'), ['class' => 'form-control required textOnly input-md cusReadonly']) !!}
                                                        {!! $errors->first('emp_place_of_birth','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('pass_issue_date') ? 'has-error': ''}}">
                                                    {!! Form::label('pass_issue_date','Date of issue',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="PassportIssueDate input-group date ">
                                                            {!! Form::text('pass_issue_date', (Session::get('wpnInfo.pass_issue_date') ? date('d-M-Y', strtotime(Session::get('wpnInfo.pass_issue_date'))) : ''), ['class' => 'form-control required input-md cusReadonly date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('pass_issue_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('pass_expiry_date') ? 'has-error': ''}}">
                                                    {!! Form::label('pass_expiry_date','Date of expiry',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="minDateToday input-group date ">
                                                            {!! Form::text('pass_expiry_date', (Session::get('wpnInfo.pass_expiry_date') ? date('d-M-Y', strtotime(Session::get('wpnInfo.pass_expiry_date'))) : ''), ['class' => 'form-control required input-md cusReadonly date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('pass_expiry_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Compensation and Benefit</legend>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" aria-label="Detailed Compensation and Benefit Report"
                                                   width="100%">
                                                <thead class="alert alert-warning">
                                                <tr>
                                                    <th class="text-center" style="vertical-align: middle"><strong>Salary
                                                            structure</strong></th>
                                                    <th class="text-center">Payment</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Currency</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="required-star helpTextCom" id="basic_local_amount_label">a. Basic salary/ Honorarium</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_payment_type_id', $paymentMethods, Session::get('wpnInfo.basic_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control required cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('basic_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('basic_local_amount', Session::get('wpnInfo.basic_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control required cusReadonly input-md numberNoNegative cb_req_field basic_salary_amount', 'step' => '0.01', 'id' => 'basic_local_amount']) !!}
                                                            {!! $errors->first('basic_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_local_currency_id', $currencies, Session::get('wpnInfo.basic_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control required cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('basic_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="overseas_local_amount_label">b. Overseas allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_payment_type_id', $paymentMethods, Session::get('wpnInfo.overseas_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md']) !!}
                                                            {!! $errors->first('overseas_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('overseas_local_amount', Session::get('wpnInfo.overseas_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative cb_req_field', 'step' => '0.01']) !!}
                                                            {!! $errors->first('overseas_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_local_currency_id', $currencies, Session::get('wpnInfo.overseas_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md']) !!}
                                                            {!! $errors->first('overseas_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="house_local_amount_label">c. House rent</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('house_payment_type_id', $paymentMethods, Session::get('wpnInfo.house_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('house_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('house_local_amount', Session::get('wpnInfo.house_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative cb_req_field', 'step' => '0.01']) !!}
                                                            {!! $errors->first('house_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('house_local_currency_id', $currencies, Session::get('wpnInfo.house_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('house_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="conveyance_local_amount_label">d. Conveyance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_payment_type_id', $paymentMethods, Session::get('wpnInfo.conveyance_payment_type_id'), ['data-rule-maxlength'=>'40',
                                                            'class' => 'form-control input-md cb_req_field cusReadonly']) !!}
                                                            {!! $errors->first('conveyance_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('conveyance_local_amount', Session::get('wpnInfo.conveyance_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative cb_req_field', 'step' => '0.01']) !!}
                                                            {!! $errors->first('conveyance_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_local_currency_id', $currencies, Session::get('wpnInfo.conveyance_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('conveyance_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="medical_local_amount_label">e. Medical allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_payment_type_id', $paymentMethods, Session::get('wpnInfo.medical_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('medical_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('medical_local_amount', Session::get('wpnInfo.medical_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative cb_req_field', 'step' => '0.01']) !!}
                                                            {!! $errors->first('medical_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_local_currency_id', $currencies, Session::get('wpnInfo.medical_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('medical_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="ent_local_amount_label">f. Entertainment allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_payment_type_id', $paymentMethods, Session::get('wpnInfo.ent_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('ent_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('ent_local_amount', Session::get('wpnInfo.ent_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative cb_req_field', 'step' => '0.01']) !!}
                                                            {!! $errors->first('ent_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_local_currency_id', $currencies, Session::get('wpnInfo.ent_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('ent_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="bonus_local_amount_label">g. Annual Bonus</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_payment_type_id', $paymentMethods, Session::get('wpnInfo.bonus_payment_type_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('bonus_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_local_amount')?'has-error':'' }}">
                                                            {!! Form::text('bonus_local_amount', Session::get('wpnInfo.bonus_local_amount'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md numberNoNegative cb_req_field', 'step' => '0.01']) !!}
                                                            {!! $errors->first('bonus_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_local_currency_id', $currencies, Session::get('wpnInfo.bonus_local_currency_id'), ['data-rule-maxlength'=>'40','class' => 'form-control cusReadonly input-md cb_req_field']) !!}
                                                            {!! $errors->first('bonus_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="other_benefits_label">h. Other fringe benefits (if any)</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="5">
                                                        <div class="{{ $errors->has('other_benefits')?'has-error':'' }}">
                                                            {!! Form::textarea('other_benefits', Session::get('wpnInfo.other_benefits'), ['class' => 'form-control cusReadonly input-md bigInputField', 'data-charcount-maxlength' => '250', 'id' => 'other_benefits', 'size' =>'5x1','data-rule-maxlength'=>'250', 'placeholder' => 'Maximum 250 characters']) !!}
                                                            {!! $errors->first('other_benefits','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">
                                            Contact address of the expatriate in Bangladesh:
                                        </legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_division_id','Division',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_division_id', $divisions, Session::get('wpnInfo.ex_office_division_id'), ['class' => 'form-control required cusReadonly input-md', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id', ". Session::get('wpnInfo.ex_office_district_id') .")"]) !!}
                                                        {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_district_id','District',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_district_id', $district_eng, Session::get('wpnInfo.ex_office_district_id'), ['class' => 'form-control required cusReadonly input-md','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id', ". Session::get('wpnInfo.ex_office_thana_id') .")"]) !!}
                                                        {!! $errors->first('ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_thana_id','Police Station', ['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_thana_id', $thana_eng, Session::get('wpnInfo.ex_office_thana_id'), ['class' => 'form-control required cusReadonly input-md','placeholder' => 'Select district first']) !!}
                                                        {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_post_office', Session::get('wpnInfo.ex_office_post_office'), ['class' => 'form-control input-md cusReadonly']) !!}
                                                        {!! $errors->first('ex_office_post_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_post_code','Post Code', ['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_post_code', Session::get('wpnInfo.ex_office_post_code'), ['class' => 'form-control required cusReadonly input-md post_code_bd']) !!}
                                                        {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_address', Session::get('wpnInfo.ex_office_address'), ['maxlength'=>'150', 'class' => 'form-control required cusReadonly input-md']) !!}
                                                        {!! $errors->first('ex_office_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_telephone_no', Session::get('wpnInfo.ex_office_telephone_no'), ['maxlength'=>'20','class' => 'form-control cusReadonly input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_mobile_no', Session::get('wpnInfo.ex_office_mobile_no'), ['class' => 'form-control required cusReadonly input-md helpText15' ,'id' => 'ex_office_mobile_no']) !!}
                                                        {!! $errors->first('ex_office_mobile_no','<span class="help-block">:message</span>') !!}
                                                        <span id="valid-msg" class="hidden text-success" style="font-size: 12px"><i class="fa fa-check" aria-hidden="true"></i> Valid</span>
                                                        <span id="error-msg" class="hidden text-danger" style="font-size: 12px"><i class="fa fa-times" aria-hidden="true"></i> Invalid</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_fax_no', Session::get('wpnInfo.ex_office_fax_no'), ['class' => 'form-control cusReadonly input-md']) !!}
                                                        {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_email','Email ',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_email', Session::get('wpnInfo.ex_office_email'), ['class' => 'form-control required cusReadonly email input-md']) !!}
                                                        {!! $errors->first('ex_office_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Others Particular of Organization</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('nature_of_business') ? 'has-error': ''}}">
                                                    {!! Form::label('nature_of_business','Nature of Business',['class'=>'text-left  col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('nature_of_business', Session::get('wpnInfo.nature_of_business'), ['class' => 'form-control input-md cusReadonly bigInputField']) !!}
                                                        {!! $errors->first('nature_of_business','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('received_remittance') ? 'has-error': ''}}">
                                                    {!! Form::label('received_remittance','Remittance received during the last twelve months  (USD)',['class'=>'text-left  col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('received_remittance', Session::get('wpnInfo.received_remittance'), ['class' => 'form-control input-md cusReadonly bigInputField']) !!}
                                                        {!! $errors->first('received_remittance','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="text-success">Capital Structure:</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('nature_of_business') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_capital','(i) Authorized Capital (USD)',['class'=>'text-left  col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::number('auth_capital', Session::get('wpnInfo.auth_capital'), ['class' => 'form-control input-md cusReadonly number']) !!}
                                                        {!! $errors->first('auth_capital','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('paid_capital') ? 'has-error': ''}}">
                                                    {!! Form::label('paid_capital','(ii) Paid-up Capital (USD)',['class'=>'text-left  col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::number('paid_capital', Session::get('wpnInfo.paid_capital'), ['class' => 'form-control input-md cusReadonly number']) !!}
                                                        {!! $errors->first('paid_capital','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                            </div>

                            <div class="panel panel-info" id="travel_history_area">
                                <div class="panel-heading"><strong>Manpower of the organization</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" width="100%" aria-label="Detailed Manpower of the organization Report">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center" colspan="9">Manpower of the organization</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="manpower">
                                                        <tr>
                                                            <th scope="col" colspan="3">Local (Bangladesh only)</th>
                                                            <th scope="col" colspan="3">Foreign (Abroad country)</th>
                                                            <th scope="col" colspan="1">Grand total</th>
                                                            <th scope="col" colspan="2">Ratio</th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col">Executive</th>
                                                            <th scope="col">Supporting staff</th>
                                                            <th scope="col">Total (a)</th>
                                                            <th scope="col">Executive</th>
                                                            <th scope="col">Supporting staff</th>
                                                            <th scope="col">Total (b)</th>
                                                            <th scope="col"> (a+b)</th>
                                                            <th scope="col">Local</th>
                                                            <th scope="col">Foreign</th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('local_executive', Session::get('wpnInfo.local_executive'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_executive']) !!}
                                                                {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('local_stuff', Session::get('wpnInfo.local_stuff'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_stuff']) !!}
                                                                {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('local_total', Session::get('wpnInfo.local_total'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field numberNoNegative','id'=>'local_total','readonly']) !!}
                                                                {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_executive', Session::get('wpnInfo.foreign_executive'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_executive']) !!}
                                                                {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_stuff', Session::get('wpnInfo.foreign_stuff'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_stuff']) !!}
                                                                {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_total', Session::get('wpnInfo.foreign_total'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field numberNoNegative','id'=>'foreign_total','readonly']) !!}
                                                                {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('manpower_total', Session::get('wpnInfo.manpower_total'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_total','readonly']) !!}
                                                                {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('manpower_local_ratio', Session::get('wpnInfo.manpower_local_ratio'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_local','readonly']) !!}
                                                                {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('manpower_foreign_ratio', Session::get('wpnInfo.manpower_foreign_ratio'), ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_foreign','readonly']) !!}
                                                                {!! $errors->first('manpower_foreign_ratio','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /panel-body-->
                            </div>

                        </fieldset>


                        <h3 class="stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Document List</legend>
                            <div id="docListDiv">
                                @include('WorkPermitExtension::documents')
                            </div>
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
                                                        <p>I do hereby undertake full responsibility of the expatriate
                                                            for whom visa recommendation is sought during their stay in
                                                            Bangladesh. </p>
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
                                                            {!! Form::text('auth_mobile_no', Auth::user()->user_phone, ['class' => 'form-control input-sm required phone_or_mobile', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' => 'form-control input-sm required email', 'readonly']) !!}
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
                                                I do hereby declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement given.
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
                                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md required phone_or_mobile']) !!}
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
                                                <div style="margin-bottom: 0px" class="alert alert-danger" role="alert">
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
                            <button type="submit" id="submitForm" style="cursor: pointer;"
                                    class="btn btn-success btn-md"
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
@include('partials.image-resize')

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script>

    function imageDisplay(input, imageView, requiredSize = 0) {
        if (input.files && input.files[0]) {
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
//                alert("Image format is not valid. Please upload in jpg,jpeg or png format");
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Image format is not valid. Please upload in jpg,jpeg or png format',
                });
                $('#' + imageView).attr('src', '{{url('assets/images/photo_default.png')}}');
                $(input).val('').addClass('btn-danger').removeClass('btn-primary');
                return false;
            } else {
                $(input).addClass('btn-primary').removeClass('btn-danger');
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                //$('#'+imageView).attr('src', e.target.result);

                // check height-width
                // in funciton calling third parameter should be (requiredWidth x requiredHeight)
                if (requiredSize != 0) {
                    var size = requiredSize.split('x');
                    var requiredwidth = parseInt(size[0]);
                    var requiredheight = parseInt(size[1]);
                    if (requiredheight != 0 && requiredwidth != 0) {
                        var image = new Image();
                        image.src = e.target.result;
                        image.onload = function () {
                            if (requiredheight != this.height || requiredwidth != this.width) {
                                //alert("Image size must be " + requiredSize);
                                swal({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Image size must be ' + requiredSize + ' PX',
                                });
                                $('#' + imageView).attr('src', '{{url('assets/images/photo_default.png')}}');
                                $(input).val('').addClass('btn-danger').removeClass('btn-primary');
                                return false;
                            } else {
                                $('#' + imageView).attr('src', e.target.result);
                            }
                        }
                    } else {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Error in image required size!',
                        });
                        //alert('Error in image required size!');
                    }
                }
                // if image height and width is not defined , means any size will be uploaded
                else {
                    $('#' + imageView).attr('src', e.target.result);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
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
            var action = "{{url('/work-permit-extension/upload-document')}}";

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
                        + ', '+ isRequired +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
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

    function lastWorkPermit(value) {
        if (value == 'yes') {
            $("#ref_app_tracking_no_div").removeClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
            $("#work_permit_type_div").removeClass('hidden');
            $("#issue_date_of_first_div").removeClass('hidden');
            $("#duration_div").removeClass('hidden');
            $("#expiry_date_of_op_div").removeClass('hidden');

            $("#ref_app_tracking_no").addClass('required');
            $("#manually_approved_wp_no").removeClass('required');
        } else if (value == 'no') {
            $("#manually_approved_no_div").removeClass('hidden');
            $("#issue_date_of_first_div").removeClass('hidden');
            $("#work_permit_type_div").removeClass('hidden');
            $("#duration_div").removeClass('hidden');
            $("#expiry_date_of_op_div").removeClass('hidden');
            $("#ref_app_tracking_no_div").addClass('hidden');

            $("#ref_app_tracking_no").removeClass('required');
            $("#manually_approved_wp_no").addClass('required');
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
            $("#issue_date_of_first_div").addClass('hidden');
            $("#work_permit_type_div").addClass('hidden');
            $("#duration_div").addClass('hidden');
            $("#expiry_date_of_op_div").addClass('hidden');

            $("#ref_app_tracking_no").addClass('required');
            $("#manually_approved_wp_no").addClass('required');
        }
    }

    var sessionLastWP = '{{ Session::get('wpnInfo.last_work_permit') }}';

    if (sessionLastWP == 'yes') {
        lastWorkPermit(sessionLastWP);
//        $(".cusReadonly").prop('readonly', true);
//        $(".cusReadonly option:not(:selected)").prop('disabled', true);
//        $(".cusReadonly:radio:not(:checked)").attr('disabled', true);
//        $(".cusReadonlyPhoto").attr('disabled', true);

        $("#ref_app_tracking_no").prop('readonly', true);
    }

    function checkCourtesyService(courtesy_service) {
        if (courtesy_service == 'yes') {
            $("#courtesy_service_reason_div").show('slow');
        } else {
            $("#courtesy_service_reason_div").hide('slow');
        }
    }

    $(document).ready(function () {
        var form = $("#WorkPermitExtensionForm").show();
        form.find('#save_as_draft').css('display', 'none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex == 1) {
                    var last_work_permit = $("input[name='last_work_permit']:checked").val();
                    if (last_work_permit == 'yes') {
                        if (sessionLastWP == 'yes') {
                            return form.valid();
                        } else {
                            alert('Please, load work permit data.');
                            return false;
                        }
                    }

                    // Compare start_date and end_date
                    var checkStartEndDate = startEndDateValidation('duration_start_date', 'duration_end_date');
                    if (checkStartEndDate == 0) {
                        return false;
                    }
                }

                if (newIndex == 2) {
                    jQuery.validator.addClassRules("basic_salary_amount", {
                        required: true,
                        min: 0.01
                    });
                }

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // Forbid next action on "Warning" step if the user is to young
                // if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                //     return false;
                // }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                // form.validate({
                //     rules: {
                //         basic_local_amount: {
                //             min: 0.01
                //         }
                //     }
                // }).settings.ignore = ":disabled,:hidden";
                // return form.valid();

                return  form.valid();
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
                        // form.validate({
                        //     rules: {
                        //         basic_local_amount: {
                        //             min: 0.01
                        //         }
                        //     }
                        // }).settings.ignore = ":disabled";

                        form.validate().settings.ignore = ":disabled";
                        //console.log(form.validate().errors()); // show hidden errors in last step
                        return form.valid();
                    });
                } else {
                    form.find('#submitForm').css('display', 'none');
                }
            },
            onFinishing: function (event, currentIndex) {
                // form.validate({
                //     rules: {
                //         basic_local_amount: {
                //             min: 0.01
                //         }
                //     }
                // }).settings.ignore = ":disabled";

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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/work-permit-extension/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 150),
            minDate: '01/01/' + (yyyy - 150)
        });

        $('.datepickerTraHis').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 100),
            maxDate: 'now'
        });

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: 'now'
        });

//         // Passport issue expiry date
//         $('.PassportIssueDate').datetimepicker({
//             viewMode: 'days',
//             format: 'DD-MMM-YYYY',
//             minDate: '01/01/' + (yyyy - 60),
//             maxDate: 'now'
//         });
//         startDateSelected = '';
//         $(".PassportIssueDate").on("dp.change", function (e) {
//             $('#pass_expiry_date').val('');
// //            $('#construction_duration').val('');
//
//             var nextDate = e.date.add(1, 'day');
//             $('.PassportExpiryDate').datetimepicker({
//                 format: 'DD-MMM-YYYY',
//                 minDate: nextDate,
//                 //useCurrent: false // Important! See issue #1075
//             });
// //            $('.PassportExpiryDate').data("DateTimePicker").minDate(nextDate);
//             startDateSelected = nextDate;
//         });
        // $(".PassportExpiryDate").on("dp.change", function (e) {
        //     var startDateVal = $("#pass_issue_date").val();
        //     var day = moment(startDateVal, ['DD-MMM-YYYY', 'YYYY-MM-DD']);
        //     var startDate = moment(day).add(1, 'day');
        //     if (startDateVal != '') {
        //         $('.PassportExpiryDate').data("DateTimePicker").minDate(startDate);
        //     }
        //     var endDate = moment($("#pass_expiry_date").val()).add(1, 'day');
        //     var endDateMoment = moment(endDate, ['DD-MMM-YYYY', 'YYYY-MM-DD']);
        //     var endDateVal = $("#pass_expiry_date").val();
        //     var dayEnd = moment(endDateVal, ['DD-MMM-YYYY', 'YYYY-MM-DD']);
        //     var endDate = moment(dayEnd).add(1, 'day');

            //var startDate = startDateSelected;
            //var endDate = e.date.add(1, 'day');
//            if (startDate != '' && endDate != '' && $("#pass_expiry_date").val() > $("#pass_issue_date").val()) {
//                var days = (endDate - startDate) / 1000 / 60 / 60 / 24;
//                $('#construction_duration').val(Math.floor(days));
//            }
        //});
       // $('.PassportExpiryDate').trigger('dp.change');  // End of Construction Schedule

        // Travel history, courtesy_service trigger if session has yes
        $('input[name=courtesy_service]:checked').trigger('click');

        // Trigger district field of Expatriate info
        $("#emp_bd_dist_id").trigger('change');
        $("#th_org_district_id").trigger('change');

    });
</script>

{{--initail -input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
{{--//textarea count down--}}
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" src="" type="text/javascript"></script>
<script>
    $(function () {
        //max text count down
        $('.maxTextCountDown, #other_benefits').characterCounter();

        $("#ex_office_mobile_no").intlTelInput({
            hiddenInput: ["ex_office_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#ex_office_telephone_no").intlTelInput({
            hiddenInput: ["ex_office_telephone_no"],
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
        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: ["sfp_contact_phone"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});

        $("#ex_office_mobile_no").change(function()
        {
            var telInput = $("#ex_office_mobile_no");
            if ($.trim(telInput.val()))
            {
                if (telInput.intlTelInput("isValidNumber"))
                {
                    // console.log(telInput.intlTelInput("getNumber"));
                    $('#valid-msg').removeClass('hidden');
                    $('#error-msg').addClass('hidden');
                }
                else
                {
                    // console.log(telInput.intlTelInput("getValidationError"));
                    $('#error-msg').removeClass('hidden');
                    $('#valid-msg').addClass('hidden');
                }
            }
        });
    });
</script>
{{--initail -input plugin script start--}}

{{--Applicant desired duration & payment calculation--}}
<script>
    $(function () {
        var process_id = '{{ $process_type_id }}';
        var dd_startDateDivID = 'duration_start_datepicker';
        var dd_startDateValID = 'duration_start_date';
        var dd_endDateDivID = 'duration_end_datepicker';
        var dd_endDateValID = 'duration_end_date';
        var dd_show_durationID = 'desired_duration';
        var dd_show_amountID = 'duration_amount';
        var dd_show_yearID = 'duration_year';

        $("#" + dd_startDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });
        $("#" + dd_endDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });

        $("#" + dd_startDateDivID).on("dp.change", function (e) {

            var startDateVal = $("#" + dd_startDateValID).val();

            if (startDateVal != '') {
                // Min value set for end date
                //$("#" + dd_endDateDivID).data("DateTimePicker").minDate(e.date);
                var endDateVal = $("#" + dd_endDateValID).val();
                if (endDateVal != '') {
                    getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID, dd_show_amountID, dd_show_yearID);
                } else {
                    $("#" + dd_endDateValID).addClass('error');
                }
            } else {
                $("#" + dd_show_durationID).val('');
                $("#" + dd_show_amountID).val('');
                $("#" + dd_show_yearID).text('');
            }
        });

        $("#" + dd_endDateDivID).on("dp.change", function (e) {

            // Max value set for start date
            $("#" + dd_startDateDivID).data("DateTimePicker").maxDate(e.date);

            var startDateVal = $("#" + dd_startDateValID).val();

            if (startDateVal === '') {
                $("#" + dd_startDateValID).addClass('error');
            } else {
                var day = moment(startDateVal, ['DD-MMM-YYYY']);
                //var minStartDate = moment(day).add(1, 'day');
                $("#" + dd_endDateDivID).data("DateTimePicker").minDate(day);
            }

            var endDateVal = $("#" + dd_endDateValID).val();

            if (startDateVal != '' && endDateVal != '') {
                getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID, dd_show_amountID, dd_show_yearID);
            } else {
                $("#" + dd_show_durationID).val('');
                $("#" + dd_show_amountID).val('');
                $("#" + dd_show_yearID).text('');
            }
        });

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
    });

    //passport issue and expire date
    $(function () {
        $('.PassportIssueDate').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/1905',
            maxDate: 'now'
        });

        /* Date must should be maximum today  */
        $('.minDateToday').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            useCurrent: false
        });

        $(".PassportIssueDate").on("dp.change", function (e) {
            var start = $(".PassportIssueDate").find('input').val();
            var day = moment(start, ['DD-MMM-YYYY']);

            //var minStartDate = moment(day).add(1, 'day');
            if (start != "") {
                $(".minDateToday").data("DateTimePicker").minDate(day);
            }
        });

        if (sessionLastWP == 'yes') {
            $('.PassportIssueDate').trigger('dp.change');
        }
    });
</script>