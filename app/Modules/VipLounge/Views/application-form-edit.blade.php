<?php
$accessMode = ACL::getAccsessRight('VipLounge');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .wizard > .content, .wizard, .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 2px;
    }

    .wizard > .steps > ul > li {
        width: 16.65% !important;
    }

    .wizard > .steps > ul > li a {
        padding: 0.5em 0.5em !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .img-thumbnail {
        height: 100px;
        max-width: 100px;
    }

    .wizard > .actions {
        top: -15px;
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

    .blink_me {
        animation: blinker 5s linear infinite;
    }

    .visa_type_box {
        margin: 20px 0px 50px 0;
    }

    @keyframes blinker {
        50% { opacity: .5; }
    }
</style>

<section class="content" id="applicationForm">
    @if(in_array($appInfo->status_id,[5,6]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong> Application for Vip Lounge</strong></h5>
                        </div>
                        <div class="pull-right">
                            {{-- @if (isset($appInfo) && $appInfo->status_id == -1)
                                <a href="{{ asset('assets/images/SampleForm/visa_recommendation.pdf') }}"
                                   target="_blank" rel="noopener" class="btn btn-warning">
                                    <i class="fas fa-file-pdf"></i>
                                    Download Sample Form
                                </a>
                            @endif --}}

                            @if(in_array($appInfo->status_id,[5,6,17,22]))
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                                </a>
                            @endif
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'vip-lounge/store','method' => 'post','id' => 'VipLoungeForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="viewMode" id="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="process_type_id" id="process_type_id" value="{{ $process_type_id }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id"/>
                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>

                        <h3 class="text-center stepHeader">Basic Instructions</h3>
                        <fieldset>
                            <legend class="d-none">Basic Instructions</legend>
                            <div class="visa_type_box">
                                <div class="row" id="vip_longue_purpose_id">
                                    <div class="col-md-12 {{$errors->has('vip_longue_purpose_id') ? 'has-error': ''}}">
                                        {!! Form::label('vip_longue_purpose_id','Do you want to use VIP/CIP longue for:', ['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('vip_longue_purpose_id', $vip_longue_purpose, $appInfo->vip_longue_purpose_id,
                                            ["placeholder" => "Select One", 'class' => 'form-control input-md required', 'id'=>'vip_longue_purpose_id', 'onchange'=>'sectionChange(this.value)']) !!}
                                            {!! $errors->first('vip_longue_purpose_id','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="text-center stepHeader">Expatriate Info</h3>
                        <fieldset>
                            {{--start basic information section--}}
                            @include('VipLounge::basic-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong id="embassy_airport_label">Basic Information</strong></div>
                                <div class="panel-body">
                                    <div class="" id="on_arrival_information_area1">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Reference Number: </legend>
                                            <div class="form-group">
                                                <div class="row">

                                                    <div class="col-md-12 {{$errors->has('ref_no_type') ? 'has-error': ''}}">
                                                        {!! Form::label('ref_no_type','Reference number type',['class'=>'col-md-3 text-left']) !!}
                                                        <div class="col-md-7">
                                                            @foreach($ref_no_types as $ref_no_type)
                                                                <label class="radio-inline">
                                                                    {!! Form::radio('ref_no_type',$ref_no_type, ($appInfo->ref_no_type == $ref_no_type ? true : false), ['id' => 'ref_no_type' ]) !!}{{ $ref_no_type }}
                                                                </label>                                                      
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 {{$errors->has('reference_number') ? 'has-error': ''}}">
                                                        {!! Form::label('reference_number','Reference number',['class'=>'col-md-3 text-left']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('reference_number', $appInfo->reference_number, ['data-rule-maxlength'=>'30', 'class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('reference_number','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </fieldset>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Which Airport do you want to receive the VIP lounge in Bangladesh: </legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {!! Form::label('airport_id',' Desired airport ',['class'=>'text-left col-md-5 required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('airport_id', $airports,$appInfo->airport_id,['class' => 'form-control input-md required', 'placeholder' => 'Select One']) !!}
                                                            {!! $errors->first('airport_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('visa_purpose') ? 'has-error': ''}}">
                                                        {!! Form::label('visa_purpose','Purpose of visit',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('visa_purpose', $appInfo->visa_purpose, ['data-rule-maxlength'=>'150', 'class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('visa_purpose','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Information of Expatriate/ Investor/ Employee </strong>
                                </div>
                                <div class="panel-body">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">General Information:</legend>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="form-group col-md-12 {{$errors->has('emp_name') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_name','Full Name',['class'=>'col-md-3 required-star text-left']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::text('emp_name', $appInfo->emp_name, ['class' => 'form-control required input-md textOnly','maxlength'=>'100']) !!}
                                                            {!! $errors->first('emp_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 {{$errors->has('emp_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_designation','Position/ Designation',['class'=>'col-md-3 required-star text-left']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('emp_designation', $appInfo->emp_designation, ['data-rule-maxlength'=>'100', 'class' => 'form-control required bigInputField input-md cusReadonly',
                                                                   'size'=>'5x2','maxlength'=>'100']) !!}
                                                            {!! $errors->first('emp_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="brief_job_description_div"
                                                         class="form-group col-md-12 {{$errors->has('brief_job_description') ? 'has-error': ''}}">
                                                        {!! Form::label('brief_job_description','Brief job description',['class'=>'text-left col-md-3']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('brief_job_description', $appInfo->brief_job_description, ['data-rule-maxlength'=>'1000', 'placeholder'=>'Brief job description', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                                'size'=>'5x2','data-charcount-maxlength'=>'1000']) !!}
                                                            {!! $errors->first('brief_job_description','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 {{$errors->has('investor_photo') ? 'has-error': ''}}">
                                                <div id="investorPhotoViewerDiv">
                                                    <?php
                                                    if (!empty($appInfo->investor_photo)) {
                                                        $userPic = file_exists('users/upload/' . $appInfo->investor_photo) ? url('users/upload/' . $appInfo->investor_photo) : url('uploads/' . $appInfo->investor_photo);
                                                    } else {
                                                        $userPic = url('assets/images/photo_default.png');
                                                    }

                                                    ?>
                                                    <img class="img-thumbnail" id="investorPhotoViewer" src="{{ $userPic  }}" alt="Investor Photo">
                                                    <input type="hidden" name="investor_photo_base64" id="investor_photo_base64">
                                                    @if(!empty($appInfo->investor_photo))
                                                        <input type="hidden" name="investor_photo_name" id="investor_photo_name" value="{{$appInfo->investor_photo}}">
                                                    @endif
                                                </div>

                                                <div class="form-group">
                                                    {!! Form::label('investor_photo','Photo ', ['class'=>'text-left required-star','style'=>'']) !!}
                                                    <br/>
                                                    <span id="investorPhotoUploadError" class="text-danger"></span>

                                                    <input type="file"
                                                           class="custom-file-input {{(!empty($appInfo->investor_photo)? '' : 'required')}}"
                                                           onchange="readURLUser(this);" id="investorPhotoUploadBtn" name="investorPhotoUploadBtn"
                                                           data-type="user" data-ref="{{Encryption::encodeId(Auth::user()->id)}}">

                                                    <a id="investorPhotoResetBtn" class="btn btn-sm btn-warning resetIt hidden"
                                                       onclick="resetImage(this);"  data-src="{{ $userPic }}">
                                                       <i class="fa fa-refresh"></i> Reset</a>

                                                    <span class="text-danger" style="font-size: 9px; font-weight: bold; display: block;">
                                                        [File Format: *.jpg/ .jpeg/ .png | Max size 100KB | Width 300px, Height 300px]
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
                                                    {!! Form::label('emp_passport_no','Passport No.',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_passport_no', $appInfo->emp_passport_no, ['data-rule-maxlength'=>'20', 'class' => 'form-control required input-md']) !!}
                                                        {!! $errors->first('emp_passport_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_personal_no') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_personal_no','Personal No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_personal_no', $appInfo->emp_personal_no, ['data-rule-maxlength'=>'20', 'class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('emp_personal_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('emp_surname') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_surname','Surname',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_surname', $appInfo->emp_surname, ['class' => 'form-control required input-md textOnly']) !!}
                                                        {!! $errors->first('emp_surname','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('place_of_issue') ? 'has-error': ''}}">
                                                    {!! Form::label('place_of_issue','Issuing authority',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('place_of_issue', $appInfo->place_of_issue, ['class' => 'form-control required input-md textOnly']) !!}
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
                                                        {!! Form::text('emp_given_name', $appInfo->emp_given_name, ['class' => 'form-control required input-md textOnly']) !!}
                                                        {!! $errors->first('emp_given_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_nationality_id') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_nationality_id','Nationality',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('emp_nationality_id', $nationality, $appInfo->emp_nationality_id, ['placeholder' => 'Select One',
                                                        'class' => 'form-control required input-md']) !!}
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
                                                            {!! Form::text('emp_date_of_birth', !empty($appInfo->emp_date_of_birth) ? date('d-M-Y', strtotime($appInfo->emp_date_of_birth)) : '', ['class' => 'form-control required input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon">
                                                                <span class="fa fa-calendar"></span>
                                                            </span>
                                                        </div>
                                                        {!! $errors->first('emp_date_of_birth','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_place_of_birth') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_place_of_birth','Place of Birth',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_place_of_birth', $appInfo->emp_place_of_birth, ['class' => 'form-control required input-md textOnly']) !!}
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
                                                            {!! Form::text('pass_issue_date', !empty($appInfo->pass_issue_date) ? date('d-M-Y', strtotime($appInfo->pass_issue_date)) : '', ['class' => 'form-control required input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon">
                                                                <span class="fa fa-calendar"></span>
                                                            </span>
                                                        </div>
                                                        {!! $errors->first('pass_issue_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('pass_expiry_date') ? 'has-error': ''}}">
                                                    {!! Form::label('pass_expiry_date','Date of expiry',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="minDateToday input-group date ">
                                                            {!! Form::text('pass_expiry_date', !empty($appInfo->pass_expiry_date) ? date('d-M-Y', strtotime($appInfo->pass_expiry_date)) : '', ['class' => 'form-control required input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon">
                                                                <span class="fa fa-calendar"></span>
                                                            </span>
                                                        </div>
                                                        {!! $errors->first('pass_expiry_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Spouse/child Information</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table aria-label="Detailed Spouse/child Information" class="table table-striped table-bordered" cellspacing="0" width="100%" id="spouseOrChildTableId">
                                                        <thead>
                                                        <tr>
                                                            <th> Spouse/child</th>
                                                            <th> Name </th>
                                                            <th> Passport/Personal No. </th>
                                                            <th> Remarks </th>
                                                            <th>#</th>
                                                        </tr>
                                                        </thead>
                                                        @if(count($spouse_child_info) > 0)
                                                            <?php $inc = 0; ?>
                                                            @foreach($spouse_child_info as $spouse_child)
                                                                <tr id="spouseOrChildTableIdRow{{$inc}}">
                                                                    <input type="hidden" name="spouseChildId[]" value="{{$spouse_child->id}}">
                                                                    <td>
                                                                        {!!Form::select('spouse_child_type[]', $spouse_child_type, $spouse_child->spouse_child_type, ['class' => 'form-control','id'=>'spouse_child_type'])!!}
                                                                        {!! $errors->first('spouse_child_type','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text('spouse_child_name[]', $spouse_child->spouse_child_name, ['class' => 'form-control input-md spouse_child_name']) !!}
                                                                        {!! $errors->first('spouse_child_name','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text('spouse_child_passport_per_no[]', $spouse_child->spouse_child_passport_per_no, ['class' => 'form-control input-md spouse_child_passport_per_no']) !!}
                                                                        {!! $errors->first('spouse_child_passport_per_no','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    <td>
                                                                        {!! Form::text('spouse_child_remarks[]', $spouse_child->spouse_child_remarks, ['class' => 'form-control input-md spouse_child_remarks']) !!}
                                                                        {!! $errors->first('spouse_child_remarks','<span class="help-block">:message</span>') !!}
                                                                    </td>
                                                                    
                                                                    <td style="text-align: left;">
                                                                        @if($inc==0)
                                                                            <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                                                onclick="addTableRow('spouseOrChildTableId', 'spouseOrChildTableIdRow0');">
                                                                                <i class="fa fa-plus"></i>
                                                                            </a>
                                                                        @else
                                                                            @if($viewMode != 'on')
                                                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger removeRow"
                                                                                    onclick="removeTableRow('spouseOrChildTableId','spouseOrChildTableIdRow{{$inc}}');">
                                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <?php $inc++; ?>
                                                            @endforeach
                                                        @else
                                                            <tr id="spouseOrChildTableIdRow">
                                                                <td>
                                                                    {!!Form::select('spouse_child_type[]', $spouse_child_type, null, ['class' => 'form-control', 'style' => 'width: 100%'])!!}
                                                                    {!! $errors->first('spouse_child_type','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('spouse_child_name[]', '', ['class' => 'form-control input-md spouse_child_name']) !!}
                                                                    {!! $errors->first('spouse_child_name','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('spouse_child_passport_per_no[]', '', ['class' => 'form-control input-md spouse_child_passport_per_no']) !!}
                                                                    {!! $errors->first('spouse_child_passport_per_no','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('spouse_child_remarks[]', '', ['class' => 'form-control input-md spouse_child_remarks']) !!}
                                                                    {!! $errors->first('spouse_child_remarks','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td style="text-align: left;">
                                                                    <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                                        onclick="addTableRow('spouseOrChildTableId', 'spouseOrChildTableIdRow');">
                                                                        <i class="fa fa-plus"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">To whom, the p- pass will be issued</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered" cellspacing="0" width="100%" id="passportHolderTableId">
                                                        <thead>
                                                        <tr>
                                                            <th> Name </th>
                                                            <th> Designation </th>
                                                            <th> Mobile Number </th>
                                                            <th> NID/Passport Number </th>
                                                            <th class="table-header">NID/Passport Copy  <br/><span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span></th>
                                                            <th>#</th>
                                                        </tr>
                                                        </thead>
                                                        @if(count($passport_holder_info) > 0)
                                                        <?php $inc = 0; ?>
                                                        @foreach($passport_holder_info as $passport_holder)
                                                            <tr id="passportHolderTableIdRow{{$inc}}">
                                                                <input type="hidden" name="passport_holder_id[]" value="{{$passport_holder->id}}">
                                                                <td>
                                                                    {!! Form::text('passport_holder_name[]', $passport_holder->passport_holder_name, ['class' => 'form-control input-md passport_holder_name required']) !!}
                                                                    {!! $errors->first('passport_holder_name','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('passport_holder_designation[]', $passport_holder->passport_holder_designation, ['class' => 'form-control input-md passport_holder_designation required']) !!}
                                                                    {!! $errors->first('passport_holder_designation','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('passport_holder_mobile[]', $passport_holder->passport_holder_mobile, ['class' => 'form-control input-md passport_holder_mobile phone_or_mobile required']) !!}
                                                                    {!! $errors->first('passport_holder_mobile','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('passport_holder_passport_no[]', $passport_holder->passport_holder_passport_no, ['class' => 'form-control input-md passport_holder_passport_no required']) !!}
                                                                    {!! $errors->first('passport_holder_passport_no','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    <input type="file" id="passport_holder_attachment" name="passport_holder_attachment[]" class="form-control input-md"/>
                                                                    {!! $errors->first('passport_holder_attachment[]','<span class="help-block">:message</span>') !!}
                                                                    @if(!empty($passport_holder->passport_holder_attachment))
                                                                    <input type="hidden" name="passport_holder_attachment_path[]" value="{{$passport_holder->passport_holder_attachment}}">
                                                                        <a target="_blank" rel="noopener" class="btn btn-xs btn-primary documentUrl"
                                                                            href="{{URL::to('/uploads/'.(!empty($passport_holder->passport_holder_attachment) ? $passport_holder->passport_holder_attachment : ''))}}"
                                                                            title="{{$passport_holder->passport_holder_attachment}}">
                                                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                            Open File
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                                <td style="text-align: left;">
                                                                    @if($inc==0)
                                                                        <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                                            onclick="addTableRow('passportHolderTableId', 'passportHolderTableIdRow0');">
                                                                            <i class="fa fa-plus"></i>
                                                                        </a>
                                                                    @else
                                                                        @if($viewMode != 'on')
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-danger removeRow"
                                                                                onclick="removeTableRow('passportHolderTableId','passportHolderTableIdRow{{$inc}}');">
                                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <?php $inc++; ?>
                                                        @endforeach
                                                        @else
                                                            <tr id="passportHolderTableIdRow">
                                                                <td>
                                                                    {!! Form::text('passport_holder_name[]', '', ['class' => 'form-control input-md passport_holder_name required']) !!}
                                                                    {!! $errors->first('passport_holder_name','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('passport_holder_designation[]', '', ['class' => 'form-control input-md passport_holder_designation required']) !!}
                                                                    {!! $errors->first('passport_holder_designation','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('passport_holder_mobile[]', '', ['class' => 'form-control input-md passport_holder_mobile phone_or_mobile required']) !!}
                                                                    {!! $errors->first('passport_holder_mobile','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('passport_holder_passport_no[]', '', ['class' => 'form-control input-md passport_holder_passport_no required']) !!}
                                                                    {!! $errors->first('passport_holder_passport_no','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    <input type="file" id="passport_holder_attachment" name="passport_holder_attachment[]" class="form-control input-md"/>
                                                                    {!! $errors->first('passport_holder_attachment[]','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td style="text-align: left;">
                                                                    <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                                        onclick="addTableRow('passportHolderTableId', 'passportHolderTableIdRow');">
                                                                        <i class="fa fa-plus"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div><!-- /panel-->

                        </fieldset>
                        
                        <h3 class="text-center stepHeader"> Travel Info</h3>
                        <fieldset>
                            <legend class="d-none">Travel Info</legend>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Flight Details of the visiting expatriates</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group" id="arrival_in_bangladesh">
                                        <div class="row">
                                            <div class="col-md-4 {{$errors->has('arrival_date') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_date','Arrival date',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="datepickerFuture input-group date">
                                                        {!! Form::text('arrival_date', !empty($appInfo->arrival_date) ? date('d-M-Y', strtotime($appInfo->arrival_date)) : '', ['class' => 'form-control input-md aib_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('arrival_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('arrival_time') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_time','Arrival time',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="timepicker input-group date">
                                                        {!! Form::text('arrival_time', $appInfo->arrival_time, ['class' => 'form-control input-md aib_req_field', 'placeholder'=>'HH:mm']) !!}
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                    </div>
                                                    {!! $errors->first('arrival_time','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('arrival_flight_no') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_flight_no',' Arrival Flight No.',['class'=>'text-left col-md-12 required-star']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::text('arrival_flight_no', $appInfo->arrival_flight_no, ['class' => 'form-control input-md aib_req_field']) !!}
                                                    {!! $errors->first('arrival_flight_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="departure_in_bangladesh">
                                        <div class="row">
                                            <div class="col-md-4 {{$errors->has('departure_date') ? 'has-error': ''}}">
                                                {!! Form::label('departure_date','Departure date',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="datepickerFuture input-group date">
                                                        {!! Form::text('departure_date', !empty($appInfo->departure_date) ? date('d-M-Y', strtotime($appInfo->departure_date)) : '', ['class' => 'form-control input-md dib_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('departure_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('departure_time') ? 'has-error': ''}}">
                                                {!! Form::label('departure_time','Departure time',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="timepicker input-group date">
                                                        {!! Form::text('departure_time', $appInfo->departure_time, ['class' => 'form-control input-md dib_req_field', 'placeholder'=>'HH:mm']) !!}
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                    </div>
                                                    {!! $errors->first('departure_time','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('departure_flight_no') ? 'has-error': ''}}">
                                                {!! Form::label('departure_flight_no',' Departure Flight No.',['class'=>'text-left col-md-12 required-star']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::text('departure_flight_no', $appInfo->departure_flight_no, ['class' => 'form-control input-md dib_req_field']) !!}
                                                    {!! $errors->first('departure_flight_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /panel-body-->
                            </div>

                        </fieldset>
                        
                        <h3 class="text-center stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv"></div>
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
                                                        <p>I do hereby declare that the information given above is true to the best of my knowledge
                                                            and I shall be liable for any false information/ statement given</p>
                                                    </li>
                                                    <li>
                                                        <p>I do hereby undertake full responsibility of the expatriate for whom 
                                                            visa recommendation is sought during their stay in Bangladesh. </p>
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
                                                            {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'form-control input-sm required phone_or_mobile', 'readonly']) !!}
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
                                                    <input type="hidden" name="auth_image"
                                                           value="{{ (!empty($appInfo->auth_image) ? $appInfo->auth_image : Auth::user()->user_pic) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('accept_terms',1, ($appInfo->accept_terms == 1) ? true : false, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                                I do here by declare that the information given above is true to the
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
                                                    {!! Form::text('sfp_contact_name', $appInfo->sfp_contact_name, ['class' => 'form-control required input-md']) !!}
                                                    {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control required input-md email']) !!}
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
                                                    {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control required sfp_contact_phone phone_or_mobile input-md']) !!}
                                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_address', $appInfo->sfp_contact_address, ['class' => 'bigInputField required form-control input-md']) !!}
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
                                                        <strong>Vat/ Tax</strong> and <strong>Transaction charge</strong> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </fieldset>
                       
                        @if(ACL::getAccsessRight('VipLounge','-E-') && $viewMode == "off" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                            
                            @if($appInfo->status_id != 5)
                                <div class="pull-left">
                                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                </div>
                                <div class="pull-left" style="padding-left: 1em;">
                                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md"
                                            value="submit" name="actionBtn">Payment &amp; Submit
                                        <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title=""
                                           data-original-title="After clicking this button will take you in the payment portal for providing the required payment. After payment, the application will be automatically submitted and you will get a confirmation email with necessary info."
                                           aria-describedby="tooltip"></i>
                                    </button>
                                </div>
                            @endif

                            @if($appInfo->status_id == 5)
                                <div class="pull-left">
                                    <span style="display: block; height: 34px">&nbsp;</span>
                                </div>
                                <div class="pull-left">
                                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-info btn-md"
                                            value="resubmit" name="actionBtn">Re-submit
                                    </button>
                                </div>
                            @endif
                        @else
                            <style>
                                .wizard > .actions {
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
@include('partials.image-resize')
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script>
    function TypeWiseDocLoad(attachment_key) {
        var _token = $('input[name="_token"]').val();
        var app_id = $("#app_id").val();
        var viewMode = $("#viewMode").val();
        var dept_id = '{{ $department_id }}';

        attachment_key = "vip_lounge";

        let is_doc_loaded = 0;
        
        $.ajax({
            type: "POST",
            url: '/vip-lounge/getDocList',
            dataType: "json",
            data: {
                _token: _token,
                attachment_key: attachment_key,
                doc_section: 'master',
                app_id: app_id,
                viewMode: viewMode
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
        is_doc_loaded++;
    }

    function imageDisplay(input, imageView, requiredSize = 0) {
        if (input.files && input.files[0]) {
            var mime_type = input.files[0].type;
            if (!(mime_type == 'image/jpeg' || mime_type == 'image/jpg' || mime_type == 'image/png')) {
                alert("Image format is not valid. Please upload in jpg,jpeg or png format");
                $('#' + imageView).attr('src', '{{url('assets/images/photo_default.png')}}');
                $(input).val('').addClass('btn-danger').removeClass('btn-primary');
                return false;
            } else {
                $(input).addClass('btn-primary').removeClass('btn-danger');
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                if (requiredSize != 0) {
                    var size = requiredSize.split('x');
                    var requiredwidth = parseInt(size[0]);
                    var requiredheight = parseInt(size[1]);
                    if (requiredheight != 0 && requiredwidth != 0) {
                        var image = new Image();
                        image.src = e.target.result;
                        image.onload = function () {
                            if (requiredheight != this.height || requiredwidth != this.width) {
                                alert("Image size must be " + requiredSize);
                                $('#' + imageView).attr('src', '{{url('assets/images/photo_default.png')}}');
                                $(input).val('').addClass('btn-danger').removeClass('btn-primary');
                                return false;
                            } else {
                                $('#' + imageView).attr('src', e.target.result);
                            }
                        }
                    } else {
                        alert('Error in image required size!');
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

    function uploadDocument(targets, id, vField, isRequired, requiredClass = '') {
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
            var action = "{{url('/visa-recommendation/upload-document')}}";

            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('requiredClass', requiredClass);
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
                    //console.log(response);
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = parseInt(id.substring(4));
                    var required_class = (requiredClass) ? 1 : 0;
                    var newInput = $('<label class="upload-attachment saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                        ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id
                        + ', ' + isRequired + ', '+ required_class +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
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

    $(document).ready(function () {
        TypeWiseDocLoad();
        var form = $("#VipLoungeForm").show();
        form.find('#save_as_draft').css('display', 'none');
        form.find('#submitForm').css('display', 'none');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",

            onStepChanging: function (event, currentIndex, newIndex) {
                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }

                // if (newIndex == 3) {
                //     var arrival_date = new Date($('#arrival_date').val().replace(/-/g, ' ')); // convert to actual date
                //     var departure_date = new Date($('#departure_date').val().replace(/-/g, ' ')); // convert to actual date

                //     if ((Date.parse(arrival_date) > Date.parse(departure_date))) {
                //         swal({
                //             type: 'error',
                //             title: 'Oops...',
                //             text: 'The departure date must be a date after arrival date.'
                //         });
                //         return false;
                //     }
                // }
                if (newIndex == 2) {
                    let validation = validatePassportHolderRows();
                    let validationSpouseChild = validateSpouseChildTable();

                    if (!validation.isValid) {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Please fill all the required fields in p- pass issued section.'
                        });
                        return false;
                    }
                    if (!validationSpouseChild.isValid) {
                        swal({
                            type: 'error',
                            title: 'Oops...',
                            text: validationSpouseChild.message
                        });
                        return false;
                    }
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

                if (currentIndex == 5) {
                    form.find('#submitForm').css('display', 'block');

                    $('#submitForm').on('click', function (e) {
                        form.validate().settings.ignore = ":disabled, :hidden";
                        //console.log(form.validate().errors()); // show hidden errors in last step
                        return form.valid();
                    });

                } else {
                    form.find('#submitForm').css('display', 'none');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                //console.log(form.validate().errors()); // show hidden errors in last step
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

        function validateSpouseChildTable() {
            let isValid = true;
            let errorMessage = '';
            
            $('#spouseOrChildTableId tbody tr').each(function(index) {
                let row = $(this);
                let filledFields = 0;
                let totalFields = 0;
                
                // Check all input fields in the row
                row.find('select, input[type="text"]').each(function() {
                    totalFields++;
                    if ($(this).val() && $(this).val().trim() !== '') {
                        filledFields++;
                    }
                });
                
                // If some fields are filled but not all
                if (filledFields > 0 && filledFields < totalFields) {
                    isValid = false;
                    errorMessage = 'Please fill all fields in row ' + (index + 1) + ' or leave all empty';
                    return false; // Break loop
                }
            });
            
            return {
                isValid: isValid,
                message: errorMessage
            };
        }

        function validatePassportHolderRows() {
            let isValid = true;
            let errorMsg = '';
            
            $('#passportHolderTableId tbody tr').each(function() {
                let row = $(this);
                let hasValue = false;
                
                row.find('input[type="text"], input[type="file"]').each(function() {
                    if ($(this).val()) {
                        hasValue = true;
                    }
                });

                row.find('.required').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        errorMsg = 'Please fill all required fields in each row';
                        return false;
                    }
                });
            });

            return {
                isValid: isValid,
                message: errorMsg
            };
        }

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/vip-lounge/preview'); ?>', 'Sample', '');
            } else {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }
        });

        var today = new Date();
        var yyyy = today.getFullYear();
        var mmm = today.getMonth() + 1;
        var dd = today.getDate() + 1;

        $('.timepicker').datetimepicker({
            format: 'HH:mm'
        });

        $('.datetimepicker').datetimepicker({
            sideBySide: true
        });

        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: '01/01/' + (yyyy + 150)
        });

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: 'now'
        });

        $('.datepickerFuture').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 6)
        });
        
        sectionChange('{{$appInfo->vip_longue_purpose_id}}');
    });

    function sectionChange(selectedvalue) {
        if (selectedvalue == 1) { // 0 = Arrival in Bangladesh
            document.getElementById('arrival_in_bangladesh').style.display = 'block';
            document.getElementById('departure_in_bangladesh').style.display = 'none';
            $(".aib_req_field").addClass('required');
            $(".dib_req_field").removeClass('required');

        } else if(selectedvalue == 2) { // 1 = Departure in Bangladesh
            document.getElementById('arrival_in_bangladesh').style.display = 'none';
            document.getElementById('departure_in_bangladesh').style.display = 'block';
            $(".dib_req_field").addClass('required');
            $(".aib_req_field").removeClass('required');

        }else{ // 3,4
            document.getElementById('arrival_in_bangladesh').style.display = 'block';
            document.getElementById('departure_in_bangladesh').style.display = 'block';
            $(".aib_req_field").addClass('required');
            $(".dib_req_field").addClass('required');
        }
    }
</script>

{{--initail -input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
{{--//textarea count down--}}
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" type="text/javascript"></script>

<script>
    $(function () {
        //max text count down
        $('.maxTextCountDown, #other_benefits').characterCounter();

        $(".sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#auth_mobile_no").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $(".passport_holder_mobile").intlTelInput({
            hiddenInput: "passport_holder_mobile",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#applicationForm").find('.iti').css({"width": "-moz-available", "width": "-webkit-fill-available"});
    });
</script>

<script>
    $(document).ready(function () {
        $('#compensation_table').DataTable({
            searching: false,
            paging: false,
            info: false,
            ordering: false,
            responsive: true
        });
        $('#prev_wp_attachment_table').DataTable({
            searching: false,
            paging: false,
            info: false,
            ordering: false,
            responsive: false,
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

            if (start != "") {
                $(".minDateToday").data("DateTimePicker").minDate(day);
            }
        });

        $('.PassportIssueDate').trigger('dp.change');
    });
</script>