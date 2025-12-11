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

    .visa_type_box {
        margin: 20px 0px 50px 0;
    }
</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>Application for Vip Lounge</strong></h5>
                        </div>
                        {{-- <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/visa_recommendation.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div> --}}
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">

                        {!! Form::open(array('url' => 'vip-lounge/store','method' => 'post','id' => 'VipLoungeForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>

                        <input type="hidden" id="process_type_id" name="process_type_id" value="{{ $process_type_id }}">

                        <h3 class="text-center stepHeader">Basic Instructions</h3>
                        <fieldset>
                            <legend class="d-none">Basic Instructions</legend>
                            <div class="visa_type_box">
                                <div class="row" id="vip_longue_purpose_id">
                                    <div class="col-md-12 {{$errors->has('vip_longue_purpose_id') ? 'has-error': ''}}">
                                        {!! Form::label('vip_longue_purpose_id','Do you want to use VIP/CIP longue for:', ['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-6">
                                            {!! Form::select('vip_longue_purpose_id', $vip_longue_purpose, Session::get('vip_longue_purpose_id'),
                                            ["placeholder" => "Select One", 'class' => 'form-control input-md required', 'id'=>'vip_longue_purpose_id', 'onchange'=>'sectionChange(this.value)']) !!}
                                            {!! $errors->first('vip_longue_purpose_id','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="text-center stepHeader">Expatriate Info</h3>
                        <fieldset>
                            {{-- Common Basic Information By Company Id --}}
                            @include('ProcessPath::basic-company-info')

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
                                                                <label class="radio-inline">{!! Form::radio('ref_no_type',$ref_no_type, false, ['id' => 'ref_no_type' ]) !!}{{ $ref_no_type }}</label>                                                      
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 {{$errors->has('reference_number') ? 'has-error': ''}}">
                                                        {!! Form::label('reference_number','Reference number',['class'=>'col-md-3 text-left']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('reference_number', '', ['data-rule-maxlength'=>'30', 'class' => 'form-control input-md']) !!}
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
                                                            {!! Form::select('airport_id', $airports,'',['class' => 'form-control input-md required', 'placeholder' => 'Select One']) !!}
                                                            {!! $errors->first('airport_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('visa_purpose') ? 'has-error': ''}}">
                                                        {!! Form::label('visa_purpose','Purpose of visit',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('visa_purpose', '', ['data-rule-maxlength'=>'150', 'class' => 'form-control input-md']) !!}
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
                                <div class="panel-heading"><strong>Information of Expatriate/ Investor/ Employee </strong></div>
                                <div class="panel-body">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">General Information:</legend>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="form-group col-md-12 {{$errors->has('emp_name') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_name','Full Name',['class'=>'col-md-3 required-star text-left']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::text('emp_name', '', ['class' => 'form-control required input-md textOnly','maxlength'=>'100']) !!}
                                                            {!! $errors->first('emp_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-12 {{$errors->has('emp_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_designation','Position/ Designation',['class'=>'col-md-3 required-star text-left']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('emp_designation', Session::get('vrInfo.emp_designation'), ['data-rule-maxlength'=>'100', 'class' => 'form-control required bigInputField input-md cusReadonly',
                                                                     'size'=>'5x2','maxlength'=>'100']) !!}
                                                            {!! $errors->first('emp_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>

                                                    <div id="brief_job_description_div"
                                                         class="form-group col-md-12 {{$errors->has('brief_job_description') ? 'has-error': ''}}">
                                                        {!! Form::label('brief_job_description','Brief job description',['class'=>'text-left col-md-3']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('brief_job_description', null, ['data-rule-maxlength'=>'1000', 'placeholder'=>'Brief job description', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                                'size'=>'5x2','data-charcount-maxlength'=>'1000']) !!}
                                                            {!! $errors->first('brief_job_description','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 {{$errors->has('investor_photo') ? 'has-error': ''}}">
                                                <div id="investorPhotoViewerDiv">
                                                    <img class="img-thumbnail" id="investorPhotoViewer" src="{{ url('assets/images/photo_default.png')  }}" alt="Investor Photo">
                                                    <input type="hidden" name="investor_photo_base64" id="investor_photo_base64">
                                                    <input type="hidden" name="investor_photo_name" id="investor_photo_name">
                                                </div>

                                                <div class="form-group">
                                                    @if($viewMode != 'on')
                                                        {!! Form::label('investor_photo','Photo ', ['class'=>'text-left required-star','style'=>'']) !!}
                                                        <br/>
                                                    @endif
                                                    <span id="investorPhotoUploadError" class="text-danger"></span>

                                                    <input type="file" class="custom-file-input required" onchange="readURLUser(this);"
                                                        id="investorPhotoUploadBtn" name="investorPhotoUploadBtn" data-type="user"
                                                        data-ref="{{Encryption::encodeId(Auth::user()->id)}}">

                                                    <a id="investorPhotoResetBtn" class="btn btn-sm btn-warning resetIt hidden"
                                                       onclick="resetImage(this);" data-src="{{ url('assets/images/photo_default.png') }}">
                                                       <i class="fa fa-refresh"></i> Reset</a>

                                                    <span class="text-danger"
                                                          style="font-size: 9px; font-weight: bold; display: block;">
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
                                                        {!! Form::text('emp_passport_no', '', ['data-rule-maxlength'=>'20', 'class' => 'form-control required input-md']) !!}
                                                        {!! $errors->first('emp_passport_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_personal_no') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_personal_no','Personal No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_personal_no', '', ['data-rule-maxlength'=>'20', 'class' => 'form-control input-md']) !!}
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
                                                        {!! Form::text('emp_surname', '', ['class' => 'form-control required input-md textOnly']) !!}
                                                        {!! $errors->first('emp_surname','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('place_of_issue') ? 'has-error': ''}}">
                                                    {!! Form::label('place_of_issue','Issuing authority',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('place_of_issue', '', ['class' => 'form-control required input-md  textOnly']) !!}
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
                                                        {!! Form::text('emp_given_name', '', ['class' => 'form-control required input-md textOnly']) !!}
                                                        {!! $errors->first('emp_given_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_nationality_id') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_nationality_id','Nationality',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('emp_nationality_id', $nationality,'', ['placeholder' => 'Select One',
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
                                                            {!! Form::text('emp_date_of_birth', '', ['class' => 'form-control required input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('emp_date_of_birth','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('emp_place_of_birth') ? 'has-error': ''}}">
                                                    {!! Form::label('emp_place_of_birth','Place of Birth',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('emp_place_of_birth', '', ['class' => 'form-control required input-md textOnly']) !!}
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
                                                            {!! Form::text('pass_issue_date', '', ['class' => 'form-control required input-md date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('pass_issue_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('pass_expiry_date') ? 'has-error': ''}}">
                                                    {!! Form::label('pass_expiry_date','Date of expiry',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="minDateToday input-group date">
                                                            {!! Form::text('pass_expiry_date', '', ['class' => 'form-control required input-md  date', 'placeholder'=>'dd-mm-yyyy']) !!}
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
                                        <legend class="scheduler-border">Spouse/child Information</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table aria-label="Detailed Spouse/child Information" class="table table-striped table-bordered" cellspacing="0" width="100%" id="spouseOrChildTableId">
                                                        <thead>
                                                        <tr>
                                                            <th> Spouse/child</th>
                                                            <th> Name
                                                                <span class="text-danger" id="spouse_name_err"></span>
                                                            </th>
                                                            <th> Passport/Personal No.
                                                                <span class="text-danger" id="spouse_passport_no_err"></span>
                                                            </th>
                                                            <th> Remarks 
                                                                <span class="text-danger" id="spouse_remarks_err"></span>
                                                            </th>
                                                            <th>#</th>
                                                        </tr>
                                                        </thead>
                                                            <tr id="spouseOrChildTableIdRow0" data-number="1">
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
                                                                <td>
                                                                    <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                                       onclick="addTableRowForVIP('spouseOrChildTableId', 'spouseOrChildTableIdRow0');">
                                                                        <i class="fa fa-plus"></i></a>
                                                                </td>
                                                            </tr>
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
                                                            <tr id="passportHolderTableIdRow" data-number="1">
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
                                                                    <input type="file" id="passport_holder_attachment" name="passport_holder_attachment[0]" class="form-control input-md passport_holder_attachment"/>
                                                                    {!! $errors->first('passport_holder_attachment','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                                                       onclick="addTableRowForVIP('passportHolderTableId', 'passportHolderTableIdRow');">
                                                                        <i class="fa fa-plus"></i></a>
                                                                </td>
                                                            </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
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
                                                        {!! Form::text('arrival_date', '', ['class' => 'form-control input-md aib_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('arrival_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('arrival_time') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_time','Arrival time',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="timepicker input-group date">
                                                        {!! Form::text('arrival_time', '', ['class' => 'form-control input-md aib_req_field', 'placeholder'=>'HH:mm']) !!}
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                    </div>
                                                    {!! $errors->first('arrival_time','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('arrival_flight_no') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_flight_no',' Arrival Flight No.',['class'=>'text-left col-md-12 required-star']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::text('arrival_flight_no', '', ['class' => 'form-control input-md aib_req_field']) !!}
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
                                                        {!! Form::text('departure_date', '', ['class' => 'form-control input-md dib_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('departure_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('departure_time') ? 'has-error': ''}}">
                                                {!! Form::label('departure_time','Departure time',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="timepicker input-group date">
                                                        {!! Form::text('departure_time', '', ['class' => 'form-control input-md dib_req_field', 'placeholder'=>'HH:mm']) !!}
                                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                    </div>
                                                    {!! $errors->first('departure_time','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('departure_flight_no') ? 'has-error': ''}}">
                                                {!! Form::label('departure_flight_no',' Departure Flight No.',['class'=>'text-left col-md-12 required-star']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::text('departure_flight_no', '', ['class' => 'form-control input-md dib_req_field']) !!}
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
                                                            <img class="img-thumbnail"
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
                                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md required']) !!}
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
                    app_id: app_id,
                    doc_section: "master",
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
            var action = "{{url('/vip-lounge/upload-document')}}";

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
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = parseInt(id.substring(4));
                    var required_class = (requiredClass) ? 1 : 0;
                    var newInput = $('<label class="upload-attachment saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                        ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id
                        + ', '+ isRequired +', '+ required_class +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
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

    $(document).ready(function () {
        // Document load
        TypeWiseDocLoad();
        var form = $("#VipLoungeForm").show();
        form.find('#save_as_draft').css('display', 'none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // return true;
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
                    form.find('#save_as_draft').css('display', 'block');
                    form.find('.actions').css('top', '-42px');
                } else {
                    form.find('#save_as_draft').css('display', 'none');
                    form.find('.actions').css('top', '-15px');
                }

                if (currentIndex == 5) {
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


        $('.timepicker').datetimepicker({
            format: 'HH:mm',
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
            minDate: 'now',
            maxDate: '01/01/' + (yyyy + 6)
        });

        // sectionChange('{{Session::get('irc_purpose_id')}}');
    })

    function sectionChange(selectedvalue) {
        if (selectedvalue == 1) { // 1 = Arrival in Bangladesh
            document.getElementById('arrival_in_bangladesh').style.display = 'block';
            document.getElementById('departure_in_bangladesh').style.display = 'none';
            $(".aib_req_field").addClass('required');
            $(".dib_req_field").removeClass('required');

        } else if(selectedvalue == 2) { // 2 = Departure in Bangladesh
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
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" src="" type="text/javascript"></script>
<script>
    $(function () {
        //max text count down
        $('.maxTextCountDown, #other_benefits').characterCounter();

        $("#sfp_contact_phone").intlTelInput({
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
        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});
    });

</script>

<script>
    $(function () {
        $('.datepickerTraHisStart0').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            extraFormats: [ 'DD.MM.YY', 'DD.MM.YYYY' ],
            maxDate: 'now',
            minDate: '01/01/1905'
        });

        $('.datepickerTraHisEnd0').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            useCurrent: false
        });

        $(".datepickerTraHisStart0").on("dp.change", function (e) {
            var start = $(".datepickerTraHisStart0").find('input').val();
            var day = moment(start, ['DD-MMM-YYYY']);

            //var minStartDate = moment(day).add(1, 'day');
            if (start != "") {
                $(".datepickerTraHisEnd0").data("DateTimePicker").minDate(day);
            }
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
    });

</script>


<script type="text/javascript">
    // Add table Row script
    function addTableRowForVIP(tableID, template_row_id) {
        // Copy the template row (first row) of table and reset the ID and Styling
        var new_row = document.getElementById(template_row_id).cloneNode(true);
        new_row.id = "";
        new_row.style.display = "";
        var current_total_row = "";

        //has new datepicker
        var hasDatepickerClass = $('#' + tableID).find('.datepicker').hasClass('datepicker');
        if (hasDatepickerClass) {
            //Get the total row, and last row number of table
            current_total_row = $('#' + tableID).find('tbody').find('.table-tr').length;
        } else {
            // Get the total row number, and last row number of table
            current_total_row = $('#' + tableID).find('tbody tr').length;
        }

        var final_total_row = current_total_row + 1;

        
        // check row count to click add more button end

        // Generate an ID of the new Row, set the row id and append the new row into table
        var last_row_number = $('#' + tableID).find('tbody tr').last().attr('data-number');
        if (last_row_number != '' && typeof last_row_number !== "undefined") {
            last_row_number = parseInt(last_row_number) + 1;
        } else {
            last_row_number = Math.floor(Math.random() * 101);
        }

        var new_row_id = 'rowCount' + tableID + last_row_number;
        new_row.id = new_row_id;
        $("#" + tableID).append(new_row);

        //New datepiker remove after cloning
        $("#" + tableID).find('#' + new_row_id).find('.datepicker-button').remove();
        $("#" + tableID).find('#' + new_row_id).find('.datepicker-calendar').remove();

        //Uploaded file remove after cloning
        $("#" + tableID).find('#' + new_row_id).find('.remove-uploaded-file').remove();
        $("#" + tableID).find('#' + new_row_id).find('.span_validate_field_0').remove();

        // Convert the add button into remove button of the new row
        $("#" + tableID).find('#' + new_row_id).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            .attr('onclick', 'removeTableRow("' + tableID + '","' + new_row_id + '")');
        // Icon change of the remove button of the new row
        $("#" + tableID).find('#' + new_row_id).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');

        //Ajax file upload document function argument change
        $("#" + tableID).find('#' + new_row_id).find('.ajax-file-upload-function').attr('onchange', 'uploadDocument("preview_' + current_total_row + '", this.id,"validate_field_' + current_total_row + '", 0, "true")');

        // data-number attribute update of the new row
        $('#' + tableID).find('tbody tr').last().attr('data-number', last_row_number);

        // Get all select box elements from the new row, reset the selected value, and change the name of select box
        var all_select_box = $("#" + tableID).find('#' + new_row_id).find('select');
        all_select_box.val(''); //reset value
        all_select_box.prop('selectedIndex', 0);
        for (var i = 0; i < all_select_box.length; i++) {
            var name_of_select_box = all_select_box[i].name;
            var updated_name_of_select_box = name_of_select_box.replace('[0]', '[' + current_total_row + ']'); //increment all array element name
            all_select_box[i].name = updated_name_of_select_box;
        }

        // Get all input box elements from the new row, reset the value, and change the name of input box
        var all_input_box = $("#" + tableID).find('#' + new_row_id).find('input');
        all_input_box.val(''); // value reset
        for (var i = 0; i < all_input_box.length; i++) {
            var name_of_input_box = all_input_box[i].name;
            var id_of_input_box = all_input_box[i].id;
            var updated_name_of_input_box = name_of_input_box.replace('[0]', '[' + current_total_row + ']');
            var updated_id_of_input_box = id_of_input_box.replace('0', + current_total_row);
            all_input_box[i].name = updated_name_of_input_box;
            all_input_box[i].id = updated_id_of_input_box;
        }
        // Get all textarea box elements from the new row, reset the value, and change the name of textarea box
        var all_textarea_box = $("#" + tableID).find('#' + new_row_id).find('textarea');
        all_textarea_box.val(''); // value reset
        for (var i = 0; i < all_textarea_box.length; i++) {
            var name_of_textarea = all_textarea_box[i].name;
            var updated_name_of_textarea = name_of_textarea.replace('[0]', '[' + current_total_row + ']');
            all_textarea_box[i].name = updated_name_of_textarea;
            $('#' + new_row_id).find('.readonlyClass').prop('readonly', true);
        }

        //Attachment preview div id replace here
        var findPreviewDivClass = $("#" + tableID).find('#' + new_row_id).find('.preview-div');
        if (findPreviewDivClass.hasClass('preview-div')) {
            var updated_preview_div_id = findPreviewDivClass[0].id.replace('0', + current_total_row);
            $("#" + tableID).find('#' + new_row_id).find('.preview-div')[0].id = updated_preview_div_id;
        }

        // Table footer adding with add more button
        var table_header_columns = "";
        if (final_total_row > 3) {
            const check_tfoot_element = $('#' + tableID + ' tfoot').length;
            if (check_tfoot_element === 0) {
                if (hasDatepickerClass) {
                    table_header_columns = $('#' + tableID).find('thead tr').find('.table-header');
                } else {
                    table_header_columns = $('#' + tableID).find('thead th');
                }
                let table_footer = document.getElementById(tableID).createTFoot();
                table_footer.setAttribute('id', 'autoFooter')
                let table_footer_row = table_footer.insertRow(0);
                for (i = 0; i < table_header_columns.length; i++) {
                    const table_footer_th = table_footer_row.insertCell(i);
                    // if this is the last column, then push add more button
                    if (i === (table_header_columns.length - 1)) {
                        table_footer_th.innerHTML = '<a class="btn btn-sm btn-primary addTableRows" title="Add more" onclick="addTableRowForVIP(\'' + tableID + '\', \'' + template_row_id + '\')"><i class="fa fa-plus"></i></a>';
                    } else {
                        table_footer_th.innerHTML = '<b>' + table_header_columns[i].innerHTML + '</b>';
                    }
                }
            }

            // add colspan attr for As Per L/C Open and section 8
            if (tableID === 'existingMachinesLcTbl') {
                $("#existingMachinesLcTbl").find('tfoot').find('td:eq(3)').attr('colspan', 2);
            }
        }

        $("#" + tableID).find('#' + new_row_id).find('.onlyNumber').on('keydown', function (e) {
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

        $("#" + tableID).find('.datepicker').datepicker({
            outputFormat: 'dd-MMM-y',
            // daysOfWeekDisabled: [5,6],
            theme : 'blue',
        });

    } // end of addTableRowForVIP() function


    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        var current_total_row = "";
        $('#' + tableID).find('#' + removeNum).remove();
        //remove table footer
        if ($('#' + tableID).find('.datepicker').hasClass('datepicker')) {
            current_total_row = $('#' + tableID).find('tbody').find('.table-tr').length;
        } else {
            current_total_row = $('#' + tableID).find('tbody tr').length;
        }

        if (current_total_row <= 3) {
            const tableFooter = document.getElementById('autoFooter');
            if (tableFooter) {
                tableFooter.remove();
            }
        }
    }

</script>