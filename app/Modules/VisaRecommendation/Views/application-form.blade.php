<?php
$accessMode = ACL::getAccsessRight('VisaRecommendation');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .fa-question-circle{
        left: -15px !important;
    }
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

    .visaTypeTabContent {
        width: 100%;
        margin-top: 10px;
    }

    .visaTypeTab .btn {
        margin: 5px 10px 5px 0;
    }

    .visaTypeTab .btn-info {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }

    .visaTypeTab .btn-info.active {
        color: #fff;
        background-color: #31b0d5;
        border-color: #269abc
    }

    .visaTypeTab label.radio-inline {
        margin-bottom: 0px !important;
        padding: 0;
    }

    .visaTypeTabPane {
        width: 100%;
    }

    .visaTypeTabPane .checkbox {
        margin-left: 20px;
    }

    .visaTypeTabPane .checkbox {
        font-weight: bold;
    }

    .tab-content {
        float: left;
        margin-bottom: 20px;
    }

    .tab-content .visaTypeTabPane.active {
        border: 1px solid #ccc !important;
        float: left;
        border-radius: 4px;
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
</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>Application for Visa Recommendation</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/visa_recommendation.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">

                        {!! Form::open(array('url' => 'visa-recommendation/store','method' => 'post','id' => 'VisaRecommendationForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>

                        <input type="hidden" id="process_type_id" name="process_type_id" value="{{ $process_type_id }}">

                        <h3 class="text-center stepHeader">Basic Instructions</h3>
                        <fieldset>
                            <legend class="d-none">Basic Instructions</legend>
                            <div class="visa_type_box">
                                <h4 class="required-star">Please specify your visa type:</h4>
                                <small class="text-danger">N.B.: Once you save or submit the application, the visa type
                                    cannot be changed anymore.
                                </small>

                                <div id="tab" class="visaTypeTab" data-toggle="buttons">
                                    @foreach($app_category as $category)
                                        <a href="#tab{{$category->id}}" class="showInPreview btn btn-md btn-info"
                                           data-toggle="tab">
                                            {!! Form::radio('app_type_id', $category->id, false, ['class'=>'badgebox required', 'onchange' => "TypeWiseDocLoad(this.value, '$category->attachment_key')"]) !!}  {{ $category->name }}
                                            <span class="badge">&check;</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-content visaTypeTabContent">
                                @foreach($app_category as $key => $category)
                                    <div class="tab-pane visaTypeTabPane fade in" id="tab{{$category->id}}">
                                        <div class="col-sm-12">
                                            <div class="visa_type_box">
                                                <h3 class="page-header">You have selected {{$category->name}}. Please
                                                    read the following instructions:</h3>
                                                {!! $category->app_instruction !!}

                                                <div class="form-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            {!! Form::checkbox('agree_with_instruction',1,null, array('id'=>'eTypeChecked_'.$key, 'class'=>'required')) !!}
                                                            I have read the above information and the relevant guidance.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{--only preview mode--}}
                                            <h4 id="selected_visa_type" style="margin-top: 0px; display: none;">Visa
                                                type: {{ $category->name }}</h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>


                        <h3 class="text-center stepHeader">Expatriate Info</h3>
                        <fieldset>
                            {{-- Common Basic Information By Company Id --}}
                            @include('ProcessPath::basic-company-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong id="embassy_airport_label">Basic Information</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="" id="embassy_info_area">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Embassy/ high commission of Bangladesh in abroad where recommendation letter to be sent:</legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('mission_country_id') ? 'has-error': ''}}">
                                                        {!! Form::label('mission_country_id','Select desired country',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('mission_country_id', $countriesWithoutBD, '',
                                                            ['class' => 'form-control input-md embassy_info_req_field','placeholder' => 'Select One', 'onchange'=>"getEmbassyByCountryId(this, 'high_commision_id')"]) !!}
                                                            {!! $errors->first('mission_country_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('high_commision_id') ? 'has-error': ''}}">
                                                        {!! Form::label('high_commision_id','Embassy/ High Commission',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('high_commision_id', [],'', ['placeholder' => 'Select One',
                                                            'class' => 'form-control input-md embassy_info_req_field']) !!}
                                                            {!! $errors->first('high_commision_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="" id="on_arrival_information_area1" hidden>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Which Airport do you want to receive the
                                                visa recommendation in Bangladesh:
                                            </legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        {!! Form::label('airport_id',' Select your desired  airport ',['class'=>'text-left col-md-5 required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('airport_id', $airports,'',['class' => 'form-control input-md oa_req_field', 'placeholder' => 'Select One']) !!}
                                                            {!! $errors->first('airport_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div id="visit_purpose_div"
                                                         class="col-md-6 {{$errors->has('visa_purpose_id') ? 'has-error': ''}}">
                                                        {!! Form::label('visa_purpose_id','Purpose of visit',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('visa_purpose_id', $travel_purpose,'', ['placeholder' => 'Select one',
                                                            'class' => 'form-control input-md', 'onchange' => 'getPurpose(this.value)']) !!}
                                                            {!! $errors->first('visa_purpose_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div style="margin-top: 10px;"
                                                             class="col-md-7 col-md-offset-5" id="PURPOSE_OTHERS"
                                                             hidden>
                                                            {!! Form::textarea('visa_purpose_others', null, ['data-rule-maxlength'=>'240', 'placeholder'=>'Specify others purpose', 'id'=> 'visa_purpose_others', 'class' => 'form-control visa_purpose_others bigInputField input-md maxTextCountDown',
                                                                'size'=>'5x2','data-charcount-maxlength'=>'200']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>

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
                                                        {!! Form::label('brief_job_description','Brief job description',['class'=>'text-left col-md-3 required-star']) !!}
                                                        <div class="col-md-9">
                                                            {!! Form::textarea('brief_job_description', null, ['data-rule-maxlength'=>'1000', 'placeholder'=>'Brief job description', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                                'size'=>'5x2','data-charcount-maxlength'=>'1000']) !!}
                                                            {!! $errors->first('brief_job_description','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    {{-- Start business category --}}
                                                    @if($business_category == 2) {{---2 for government----}}
                                                    <div class="col-md-12 {{$errors->has('emp_marital_status') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_marital_status','Marital Status',['class'=>'col-md-3 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            <label class="radio-inline">{!! Form::radio('emp_marital_status','married', false, ['id' => 'emp_marital_status', 'onclick' => 'maritalStatusWiseSpouseInfoShow(this.value)']) !!}
                                                                Married</label>
                                                            <label class="radio-inline">{!! Form::radio('emp_marital_status', 'unmarried', false, ['id' => 'emp_marital_status', 'onclick' => 'maritalStatusWiseSpouseInfoShow(this.value)']) !!}
                                                                Unmarried</label>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    {{-- End business category --}}
                                                </div>
                                            </div>
                                            <div class="col-md-3 {{$errors->has('investor_photo') ? 'has-error': ''}}">
                                                <div id="investorPhotoViewerDiv">
                                                    <img class="img-thumbnail" id="investorPhotoViewer"
                                                         src="{{ url('assets/images/photo_default.png')  }}"
                                                         alt="Investor Photo">
                                                    <input type="hidden" name="investor_photo_base64"
                                                           id="investor_photo_base64">
                                                    <input type="hidden" name="investor_photo_name"
                                                           id="investor_photo_name">
                                                </div>

                                                <div class="form-group">
                                                    @if($viewMode != 'on')
                                                        {!! Form::label('investor_photo','Photo:', ['class'=>'text-left required-star','style'=>'']) !!}
                                                        <br/>
                                                    @endif
                                                    <span id="investorPhotoUploadError" class="text-danger"></span>

                                                    <input type="file"
                                                           class="custom-file-input required"
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

                                    <fieldset class="scheduler-border" id="compensation_benefit_area">
                                        <legend class="scheduler-border">Compensation and Benefit:</legend>
                                        <div class="table-responsive">
                                            <table aria-label="Detailed Compensation and Benefit" id="" class="table table-striped table-bordered" cellspacing="0"
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
                                                            <span class="helpTextCom required-star" id="basic_local_amount_label">a. Basic salary/ Honorarium</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_payment_type_id', $paymentMethods,'' , ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field', ]) !!}
                                                            {!! $errors->first('basic_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_local_amount')?'has-error':'' }}">
                                                            {!! Form::number('basic_local_amount','', ['data-rule-maxlength'=>'40','class' => 'form-control input-md basic_local_amount cb_req_field numberNoNegative', 'step' => '0.01']) !!}
                                                            {!! $errors->first('basic_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('basic_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('basic_local_currency_id', $currencies, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md cb_req_field']) !!}
                                                            {!! $errors->first('basic_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative">
                                                            <span class="helpTextCom" id="overseas_local_currency_label">b. Overseas allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 
                                                                'id' => 'overseas_payment_type_id', 'onchange' => "dependentRequire('overseas_payment_type_id', ['overseas_local_amount', 'overseas_local_currency_id']);"]) !!}
                                                            {!! $errors->first('overseas_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_local_amount')?'has-error':'' }}">
                                                            {!! Form::number('overseas_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative', 'step' => '0.01', 
                                                                'id' => 'overseas_local_amount', 'onchange' => "dependentRequire('overseas_local_amount', ['overseas_payment_type_id', 'overseas_local_currency_id']);"]) !!}
                                                            {!! $errors->first('overseas_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('overseas_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('overseas_local_currency_id', $currencies, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 'id' => 'overseas_local_currency_id']) !!}
                                                            {!! $errors->first('overseas_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="helpTextCom" id="house_local_currency_label">c. House rent</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('house_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 
                                                                'id' => 'house_payment_type_id', 'onchange' => "dependentRequire('house_payment_type_id', ['house_local_amount', 'house_local_currency_id']);"]) !!}
                                                            {!! $errors->first('house_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_local_amount')?'has-error':'' }}">
                                                            {!! Form::number('house_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative', 'step' => '0.01', 
                                                                'id'=>'house_local_amount', 'onchange' => "dependentRequire('house_local_amount', ['house_payment_type_id', 'house_local_currency_id']);"]) !!}
                                                            {!! $errors->first('house_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('house_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('house_local_currency_id', $currencies, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 'id' => 'house_local_currency_id']) !!}
                                                            {!! $errors->first('house_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="helpTextCom" id="conveyance_local_currency_label">d. Conveyance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md', 
                                                                'id'=>'conveyance_payment_type_id', 'onchange' => "dependentRequire('conveyance_payment_type_id', ['conveyance_local_amount', 'conveyance_local_currency_id']);"]) !!}
                                                            {!! $errors->first('conveyance_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_local_amount')?'has-error':'' }}">
                                                            {!! Form::number('conveyance_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative', 'step' => '0.01', 
                                                                'id' => 'conveyance_local_amount', 'onchange' => "dependentRequire('conveyance_local_amount', ['conveyance_payment_type_id', 'conveyance_local_currency_id']);"]) !!}
                                                            {!! $errors->first('conveyance_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('conveyance_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('conveyance_local_currency_id', $currencies, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 'id' => 'conveyance_local_currency_id']) !!}
                                                            {!! $errors->first('conveyance_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="helpTextCom" id="medical_local_currency_label">e. Medical allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 
                                                                'id'=>'medical_payment_type_id', 'onchange' => "dependentRequire('medical_payment_type_id', ['medical_local_amount', 'medical_local_currency_id']);"]) !!}
                                                            {!! $errors->first('medical_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_local_amount')?'has-error':'' }}">
                                                            {!! Form::number('medical_local_amount','', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative', 'step' => '0.01', 
                                                                'id'=>'medical_local_amount', 'onchange' => "dependentRequire('medical_local_amount', ['medical_payment_type_id', 'medical_local_currency_id']);"]) !!}
                                                            {!! $errors->first('medical_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('medical_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('medical_local_currency_id', $currencies, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 'id' => 'medical_local_currency_id']) !!}
                                                            {!! $errors->first('medical_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="helpTextCom" id="ent_local_currency_label">f. Entertainment allowance</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 
                                                                'id'=>'ent_payment_type_id', 'onchange' => "dependentRequire('ent_payment_type_id', ['ent_local_amount', 'ent_local_currency_id']);"]) !!}
                                                            {!! $errors->first('ent_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_local_amount')?'has-error':'' }}">
                                                            {!! Form::number('ent_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative', 'step' => '0.01', 
                                                                'id' => 'ent_local_amount', 'onchange' => "dependentRequire('ent_local_amount', ['ent_payment_type_id', 'ent_local_currency_id']);"]) !!}
                                                            {!! $errors->first('ent_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('ent_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('ent_local_currency_id', $currencies, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 'id' => 'ent_local_currency_id']) !!}
                                                            {!! $errors->first('ent_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="helpTextCom" id="bonus_local_currency_label">g. Annual Bonus</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_payment_type_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_payment_type_id', $paymentMethods, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 
                                                                'id'=>'bonus_payment_type_id', 'onchange' => "dependentRequire('bonus_payment_type_id', ['bonus_local_amount', 'bonus_local_currency_id']);"]) !!}
                                                            {!! $errors->first('bonus_payment_type_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_local_amount')?'has-error':'' }}">
                                                            {!! Form::number('bonus_local_amount', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative', 'step' => '0.01', 
                                                                'id' => 'bonus_local_amount', 'onchange' => "dependentRequire('bonus_local_amount', ['bonus_payment_type_id', 'bonus_local_currency_id']);"]) !!}
                                                            {!! $errors->first('bonus_local_amount','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="{{ $errors->has('bonus_local_currency_id')?'has-error':'' }}">
                                                            {!! Form::select('bonus_local_currency_id', $currencies, '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md', 'id' => 'bonus_local_currency_id']) !!}
                                                            {!! $errors->first('bonus_local_currency_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div style="position: relative;">
                                                            <span class="helpTextCom">h. Other fringe benefits (if any)</span>
                                                        </div>
                                                    </td>
                                                    <td colspan="5">
                                                        <div class="{{ $errors->has('other_benefits')?'has-error':'' }}">
                                                            {!! Form::textarea('other_benefits', '', ['style' => 'width: 100%;' ,'class' => 'form-control input-md bigInputField', 'data-charcount-maxlength' => '250', 'size' =>'5x2','data-rule-maxlength'=>'250', 'placeholder' => 'Maximum 250 characters', 'id' => 'other_benefits']) !!}
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
                                                    {!! Form::label('ex_office_division_id','Division',['class'=>'text-left  col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_division_id', $divisions, '', ['class' => 'form-control input-md required', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id')"]) !!}
                                                        {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_district_id', [], '', ['class' => 'form-control input-md required','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id')"]) !!}
                                                        {!! $errors->first('ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ex_office_thana_id',[''], '', ['class' => 'form-control input-md required', 'placeholder' => 'Select district first']) !!}
                                                        {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_post_office', '', ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ex_office_post_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ex_office_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_post_code', '', ['class' => 'form-control input-md post_code_bd required']) !!}
                                                        {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_address', '', ['maxlength'=>'150', 'class' => 'form-control input-md required']) !!}
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
                                                        {!! Form::text('ex_office_telephone_no', '', ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ex_office_mobile_no', '', ['class' => 'form-control input-md helpText15 required' ,'id' => 'ex_office_mobile_no']) !!}
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
                                                        {!! Form::text('ex_office_fax_no', '', ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ex_office_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ex_office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('ex_office_email', '', ['class' => 'form-control email input-md required']) !!}
                                                        {!! $errors->first('ex_office_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    @if($business_category == 1)
                                    <div id="particular_information">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Others Particular of Organization(If
                                                Commercial)
                                            </legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('nature_of_business') ? 'has-error': ''}}">
                                                        {!! Form::label('nature_of_business','Nature of Business',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('nature_of_business', '', ['class' => 'form-control input-md bigInputField']) !!}
                                                            {!! $errors->first('nature_of_business','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('received_remittance') ? 'has-error': ''}}">
                                                        {!! Form::label('received_remittance','Remittance received during the last twelve months (USD)',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('received_remittance', '', ['class' => 'form-control input-md number']) !!}
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
                                                            {!! Form::number('auth_capital', '', ['class' => 'form-control input-md number']) !!}
                                                            {!! $errors->first('auth_capital','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('paid_capital') ? 'has-error': ''}}">
                                                        {!! Form::label('paid_capital','(ii) Paid-up Capital (USD)',['class'=>'text-left  col-md-5']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::number('paid_capital', '', ['class' => 'form-control input-md number']) !!}
                                                            {!! $errors->first('paid_capital','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                    @endif

                                    {{-- Start business category --}}
                                    @if($business_category == 2)
                                        {{--Spouse Information--}}
                                        <fieldset class="scheduler-border" id="business_category" hidden>
                                            <legend class="scheduler-border">
                                                Spouse Information
                                            </legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('emp_spouse_name') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_spouse_name','Spouse Name', ['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('emp_spouse_name', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('emp_spouse_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('emp_spouse_passport_no') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_spouse_passport_no','Passport Number ',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('emp_spouse_passport_no', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('emp_spouse_passport_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('emp_spouse_nationality') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_spouse_nationality','Nationality',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('emp_spouse_nationality', $nationality,'', ['placeholder' => 'Select One',
                                                            'class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('emp_spouse_nationality','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('work_in_bd') ? 'has-error': ''}}">
                                                        {!! Form::label('work_in_bd','Does he/she work in Bangladesh?',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <label class="radio-inline">{!! Form::radio('emp_spouse_work_status','yes', false, ['id' => 'emp_spouse_work_status', 'onclick' => 'isWorkInBangladesh(this.value)']) !!}
                                                                Yes</label>
                                                            <label class="radio-inline">{!! Form::radio('emp_spouse_work_status', 'no', false, ['id' => 'emp_spouse_work_status', 'onclick' => 'isWorkInBangladesh(this.value)']) !!}
                                                                No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="emp_spouse_org_name_div" hidden>
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('emp_spouse_org_name') ? 'has-error': ''}}">
                                                        {!! Form::label('emp_spouse_org_name','Organization Name', ['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('emp_spouse_org_name', '', ['class' => 'form-control input-md']) !!}
                                                            {!! $errors->first('emp_spouse_org_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    @endif
                                </div>
                            </div>
                        </fieldset>


                        <h3 class="text-center stepHeader"> Travel Info</h3>
                        <fieldset>
                            <div class="panel panel-info" id="flight_details_area" hidden>
                                <div class="panel-heading">
                                    <strong>Flight Details of the visiting expatriates</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4 {{$errors->has('arrival_date') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_date','Arrival date',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="datepickerFuture input-group date">
                                                        {!! Form::text('arrival_date', '', ['class' => 'form-control input-md fda_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('arrival_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('arrival_time') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_time','Arrival time',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="timepicker input-group date">
                                                        {!! Form::text('arrival_time', '', ['class' => 'form-control input-md fda_req_field', 'placeholder'=>'HH:mm']) !!}
                                                        <span class="input-group-addon"><span
                                                                    class="glyphicon glyphicon-time"></span></span>
                                                    </div>
                                                    {!! $errors->first('arrival_time','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('arrival_flight_no') ? 'has-error': ''}}">
                                                {!! Form::label('arrival_flight_no',' Arrival Flight No.',['class'=>'text-left col-md-12 required-star']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::text('arrival_flight_no', '', ['class' => 'form-control input-md fda_req_field']) !!}
                                                    {!! $errors->first('arrival_flight_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4 {{$errors->has('departure_date') ? 'has-error': ''}}">
                                                {!! Form::label('departure_date','Departure date',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="datepickerFuture input-group date">
                                                        {!! Form::text('departure_date', '', ['class' => 'form-control input-md fda_req_field date', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('departure_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('departure_time') ? 'has-error': ''}}">
                                                {!! Form::label('departure_time','Departure time',['class'=>'col-md-12 text-left required-star']) !!}
                                                <div class="col-md-12">
                                                    <div class="timepicker input-group date">
                                                        {!! Form::text('departure_time', '', ['class' => 'form-control input-md fda_req_field', 'placeholder'=>'HH:mm']) !!}
                                                        <span class="input-group-addon"><span
                                                                    class="glyphicon glyphicon-time"></span></span>
                                                    </div>
                                                    {!! $errors->first('departure_time','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-4 {{$errors->has('departure_flight_no') ? 'has-error': ''}}">
                                                {!! Form::label('departure_flight_no',' Departure Flight No.',['class'=>'text-left col-md-12 required-star']) !!}
                                                <div class="col-md-12">
                                                    {!! Form::text('departure_flight_no', '', ['class' => 'form-control input-md fda_req_field']) !!}
                                                    {!! $errors->first('departure_flight_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /panel-body-->
                            </div>

                            <div class="panel panel-info" id="on_arrival_information_area" hidden>
                                <div class="panel-heading"><strong>On Arrival Information</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('visiting_service_id','Type the services required for the visiting expatriate', ['class'=>' col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::select('visiting_service_id', $visiting_service_type, '', ['class' => 'form-control input-md oa_req_field','placeholder' => 'Select one']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {!! Form::label('visa_on_arrival_sought_id','On what circumstances the visa on arrival is sought instead of obtaining Visa from Bangladesh mission abroad', ['class'=>' col-md-6 required-star']) !!}
                                                <div class="col-md-6">
                                                    {!! Form::select('visa_on_arrival_sought_id', $visa_on_arrival_sought, '', ['class' => 'form-control input-md oa_req_field','placeholder' => 'Select one', 'onchange'=>"visaOnarrivalSought(this.value)"]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-12"
                                                     id="VISA_ON_ARRIVAL_SOUGHT_OTHER_DIV" hidden>
                                                    {!! Form::textarea('visa_on_arrival_sought_other', null, ['data-rule-maxlength'=>'200', 'placeholder'=>'Specify others', 'class' => 'form-control bigInputField input-md maxTextCountDown','size'=>'5x2','data-charcount-maxlength'=>'200'])!!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info" id="travel_history_area">
                                <div class="panel-heading">
                                    <strong>Previous Travel history of the expatriate to Bangladesh</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('travel_history') ? 'has-error': ''}}">
                                                {!! Form::label('travel_history','Have you visited to Bangladesh previously?',['class'=>'col-md-6 text-left required-star']) !!}
                                                <div class="col-md-6">
                                                    <label class="radio-inline">{!! Form::radio('travel_history','yes', false, ['class'=>'travel_history th_req_field helpTextRadio', 'id' => 'travel_history_yes', 'onclick' => 'checkTravelHistory(this.value)']) !!}
                                                        Yes</label>
                                                    <label class="radio-inline">{!! Form::radio('travel_history', 'no', false, ['class'=>'travel_history th_req_field', 'id' => 'travel_history_no', 'onclick' => 'checkTravelHistory(this.value)']) !!}
                                                        No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="travel_employment_period" hidden>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">In which period:</legend>
                                            <table aria-label="Detailed In which period Report" id="travelPeriodTable"
                                                   class="table table-striped table-bordered dt-responsive"
                                                   cellspacing="0" width="100%">
                                                <thead>
                                                <th class="text-center">Start Date</th>
                                                <th class="text-center">End Date</th>
                                                <th class="text-center">Type of visa availed</th>
                                                <th class="text-center">#</th>
                                                </thead>
                                                <tbody>
                                                <tr id="travelPeriodTableRow">
                                                    <td>
                                                        <div class="datepickerTraHisStart0 input-group date">
                                                            {!! Form::text('th_emp_duration_from[]', '', ['class' => 'form-control input-md date which-period', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('th_emp_duration_from','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        <div class="datepickerTraHisEnd0 input-group date">
                                                            {!! Form::text('th_emp_duration_to[]', '', ['class' => 'form-control input-md date which-period', 'placeholder'=>'dd-mm-yyyy']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('th_emp_duration_to','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::select('th_visa_type_id[]', $travelVisaType, '', ['class' => 'form-control input-md visited_req_field prev_tr_visa which-period','placeholder' => 'Select one', 'onchange'=>"TravelHistoryVisaType(this.value)"]) !!}
                                                        {!! $errors->first('th_visa_type_id[]','<span class="help-block">:message</span>') !!}
                                                        <div class="form-group col-sm-12" id="th_visa_type_id[]" hidden>
                                                            {!! Form::textarea('th_visa_type_others[]', null, ['data-rule-maxlength'=>'240', 'placeholder'=>'Specify others visa type availed', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                            'size'=>'5x1','data-charcount-maxlength'=>'200']) !!}
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle; text-align: center">
                                                        <a class="btn btn-sm btn-primary addTableRows"
                                                           title="Add more Visa record" onclick="addTableRowTraHis('travelPeriodTable', 'travelPeriodTableRow');">
                                                            <i class="fa fa-plus"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </fieldset>

                                        <div class="row">
                                            <div class="col-md-12 {{$errors->has('th_visit_with_emp_visa') ? 'has-error': ''}}">
                                                {!! Form::label('th_visit_with_emp_visa','Have you visited to Bangladesh with Employment Visa?',['class'=>'col-md-6 text-left required-star']) !!}
                                                <div class="col-md-6">
                                                    <label class="radio-inline">{!! Form::radio('th_visit_with_emp_visa','yes', false, ['class'=>'travel_history visited_req_field helpTextRadio', 'id'=>'th_visit_with_emp_visa_1', 'onclick' => 'thVisitWithEmpVisa(this.value)']) !!}
                                                        Yes</label>
                                                    <label class="radio-inline">{!! Form::radio('th_visit_with_emp_visa', 'no', false, ['class'=>'travel_history visited_req_field','id'=>'th_visit_with_emp_visa_2', 'onclick' => 'thVisitWithEmpVisa(this.value)']) !!}
                                                        No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="visited_with_emp_visa" hidden>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('th_emp_work_permit') ? 'has-error': ''}}">
                                                    {!! Form::label('th_emp_work_permit','Have you received work permit from Bangladesh?',['class'=>'col-md-6 text-left required-star']) !!}
                                                    <div class="col-md-6">
                                                        <label class="radio-inline">{!! Form::radio('th_emp_work_permit','yes', false, ['class'=>'th_visit_with_emp_visa visited_with_emp_visa_req helpTextRadio', 'id'=>'th_emp_work_permit_1', 'onclick' => 'checkReceivedWorkPermit(this.value)']) !!}
                                                            Yes</label>
                                                        <label class="radio-inline">{!! Form::radio('th_emp_work_permit', 'no', false, ['class'=>'th_visit_with_emp_visa visited_with_emp_visa_req', 'id'=>'th_emp_work_permit_2', 'onclick' => 'checkReceivedWorkPermit(this.value)']) !!}
                                                            No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="travel_employment" hidden>
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Previous work permit information in
                                                Bangladesh
                                            </legend>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('th_emp_tin_no') ? 'has-error': ''}}">
                                                        {!! Form::label('th_emp_tin_no','TIN Number',['class'=>'col-md-5 text-left travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('th_emp_tin_no', '', ['class' => 'form-control number input-md travelFileRequired']) !!}
                                                            {!! $errors->first('th_emp_tin_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('th_emp_wp_no') ? 'has-error': ''}}">
                                                        {!! Form::label('th_emp_wp_no','Last Work Permit Ref. No.',['class'=>'col-md-5 text-left travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('th_emp_wp_no', '', ['class' => 'form-control input-md travelFileRequired']) !!}
                                                            {!! $errors->first('th_emp_wp_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('th_emp_org_name') ? 'has-error': ''}}">
                                                        {!! Form::label('th_emp_org_name',' Name of the employer organization',['class'=>'text-left col-md-5 travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('th_emp_org_name', '', ['class' => 'form-control input-md travelFileRequired ']) !!}
                                                            {!! $errors->first('th_emp_org_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('th_emp_org_address') ? 'has-error': ''}}">
                                                        {!! Form::label('th_emp_org_address','Address of the organization',['class'=>'text-left col-md-5 travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::textarea('th_emp_org_address', null, ['data-rule-maxlength'=>'200', 'placeholder'=>'Contact address', 'class' => 'form-control bigInputField input-md travelFileRequired maxTextCountDown',
                                                                'size'=>'5x2','data-charcount-maxlength'=>'200']) !!}
                                                            {!! $errors->first('th_emp_org_address','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('th_org_district_id') ? 'has-error': ''}}">
                                                        {!! Form::label('th_org_district_id','City/ District',['class'=>'col-md-5 text-left travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('th_org_district_id', $districts,'',['class' => 'form-control input-md travelFileRequired ', 'placeholder' => 'Select One','onchange'=>"getThanaByDistrictId('th_org_district_id', this.value, 'th_org_thana_id')"]) !!}
                                                            {!! $errors->first('th_org_district_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('th_org_thana_id') ? 'has-error': ''}}">
                                                        {!! Form::label('th_org_thana_id','Thana/ Upazilla',['class'=>'text-left col-md-5 travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('th_org_thana_id', [],'',['class' => 'form-control input-md travelFileRequired ', 'placeholder' => 'Select One']) !!}
                                                            {!! $errors->first('th_org_thana_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{$errors->has('th_org_post_office') ? 'has-error': ''}}">
                                                        {!! Form::label('th_org_post_office','Post Office ',['class'=>'col-md-5 text-left travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('th_org_post_office','', ['class' => 'form-control input-md travelFileRequired']) !!}
                                                            {!! $errors->first('th_org_post_office','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('th_org_post_code') ? 'has-error': ''}}">
                                                        {!! Form::label('th_org_post_code','Post Code',['class'=>'col-md-5 text-left travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('th_org_post_code', '', ['data-rule-maxlength'=>'20','class' => 'form-control post_code_bd input-md travelFileRequired ']) !!}
                                                            {!! $errors->first('th_org_post_code','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 {{ $errors->has('th_org_telephone_no') ? 'has-error' : ''}}">
                                                        {!! Form::label('th_org_telephone_no','Contact Number ',['class'=>'col-md-5 text-left travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('th_org_telephone_no', '', ['class'=>'input-md form-control travelFileRequired ', 'data-rule-maxlength'=>'40']) !!}
                                                            {!! $errors->first('th_org_telephone_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 {{$errors->has('th_org_email') ? 'has-error': ''}}">
                                                        {!! Form::label('th_org_email','Email ',['class'=>'text-left col-md-5 travelFileRequiredLabel']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('th_org_email', '', ['class' => 'form-control input-md email travelFileRequired']) !!}
                                                            {!! $errors->first('th_org_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br/>
                                            {{-- Previous travel history attachment upload section --}}
                                            <div id="travelHistorydocListDiv"></div>

                                        </fieldset>
                                    </div>
                                </div><!-- /panel-body-->
                            </div>
                            @if($business_category == 1)
                            <div class="panel panel-info" id="manpowerSection">
                                <div class="panel-heading"><strong>Manpower of the organization</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table aria-label="Detailed Manpower of the organization" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead class="alert alert-info">
                                                        <tr>
                                                            <th scope="col" class="text-center text-title required-star" colspan="9">Manpower of the organization</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="manpower">
                                                        <tr>
                                                            <th scope="col" class="alert alert-info" colspan="3">Local (Bangladesh only)</th>
                                                            <th scope="col" class="alert alert-info" colspan="3">Foreign (Abroad country)</th>
                                                            <th scope="col" class="alert alert-info" colspan="1">Grand total</th>
                                                            <th scope="col" class="alert alert-info" colspan="2">Ratio</th>
                                                        </tr>
                                                        <tr>
                                                            <th scope="col" class="alert alert-info">Executive</th>
                                                            <th scope="col" class="alert alert-info">Supporting staff</th>
                                                            <th scope="col" class="alert alert-info">Total (a)</th>
                                                            <th scope="col" class="alert alert-info">Executive</th>
                                                            <th scope="col" class="alert alert-info">Supporting staff</th>
                                                            <th scope="col" class="alert alert-info">Total (b)</th>
                                                            <th scope="col" class="alert alert-info"> (a+b)</th>
                                                            <th scope="col" class="alert alert-info">Local</th>
                                                            <th scope="col" class="alert alert-info">Foreign</th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                {!! Form::text('local_executive','', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_executive']) !!}
                                                                {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('local_stuff', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_stuff']) !!}
                                                                {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('local_total', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field numberNoNegative','id'=>'local_total','readonly']) !!}
                                                                {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_executive', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_executive']) !!}
                                                                {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_stuff', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_stuff']) !!}
                                                                {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('foreign_total', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field numberNoNegative','id'=>'foreign_total','readonly']) !!}
                                                                {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('manpower_total', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_total','readonly']) !!}
                                                                {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('manpower_local_ratio', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_local','readonly']) !!}
                                                                {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                            </td>
                                                            <td>
                                                                {!! Form::text('manpower_foreign_ratio', '', ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_foreign','readonly']) !!}
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
                            @endif
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
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ol type="a">
                                                    <li>
                                                        <p>I do hereby declare that the information given above is true
                                                            to the best of my knowledge and I shall be liable for any
                                                            false information/ statement given</p>
                                                    </li>
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

    function TypeWiseDocLoad(app_type_id, attachment_key) {
        $('input[name=agreeWithInstruction]').prop('checked', false);
        var _token = $('input[name="_token"]').val();
        var app_id = $("#app_id").val();
        var viewMode = $("#viewMode").val();
        var dept_id = '{{ $department_id }}';


        // PI Type Visa
        if (app_type_id == 4) {
            $("#on_arrival_information_area").hide();
            $("#on_arrival_information_area1").hide();
            $(".oa_req_field").removeClass('required');
            $("#flight_details_area").hide();
            $(".fda_req_field").removeClass('required');
            $(".basic_local_amount").attr("min", "0.01");
            $("#basic_local_amount_label").addClass("required-star"); // Required for PI type visa
            $("#embassy_info_area").show();
            $(".embassy_info_req_field").addClass('required');

            $("#visit_purpose_div").hide();
            $("#visa_purpose_id").removeClass('required');

            $("#compensation_benefit_area").show();
            $("#particular_information").show();
            $(".cb_req_field").addClass('required');


            $(".mp_req_field").addClass('required');

            $("#travel_history_area").show();
            $(".th_req_field").addClass('required');

            $("#embassy_airport_label").text("Embassy/ High Commission Info");
            $("#brief_job_description_div").show();
            $("#brief_job_description").addClass('required');

            $("#manpowerSection").show();
        }
        // if visa type is on arrival
        else if (app_type_id == 5) {
            $("#compensation_benefit_area").hide();
            $("#particular_information").hide();
            $(".cb_req_field").removeClass('required');
            $(".basic_local_amount").removeAttr("min");

            $(".mp_req_field").removeClass('required');

            $("#travel_history_area").hide();
            $(".th_req_field").removeClass('required');

            $("#on_arrival_information_area").show();
            $("#on_arrival_information_area1").show();
            $(".oa_req_field").addClass('required');
            $("#flight_details_area").show();
            $(".fda_req_field").addClass('required');

            $("#embassy_info_area").hide();
            $(".embassy_info_req_field").removeClass('required');

            $("#visit_purpose_div").show();
            $("#visa_purpose_id").addClass('required');

            $("#embassy_airport_label").text("Airport Info");
            $("#brief_job_description_div").hide();
            $("#brief_job_description").removeClass('required');

            $("#manpowerSection").hide();
        }
        // A3 Type Visa	or E1 Tye Visa
        else if (app_type_id == 1 || app_type_id == 3) {
            $("#compensation_benefit_area").show();
            $("#particular_information").show();
            $(".cb_req_field").removeClass('required');
            if (app_type_id == 3){
                $(".basic_local_amount").removeAttr("min");
                $("#basic_local_amount_label").removeClass("required-star");
            }else {
                $(".basic_local_amount").attr("min", "0.01");
            }
            $(".mp_req_field").addClass('required');
            $("#MP_LOC_EXECUTIVE").addClass('required');
            $("#MP_LOC_STAFF").addClass('required');
            $("#FOR_LOC_EXECUTIVE").addClass('required');
            $("#FOR_LOC_STAFF").addClass('required');

            $("#travel_history_area").show();
            $(".th_req_field").addClass('required');

            $("#on_arrival_information_area").hide();
            $("#on_arrival_information_area1").hide();
            $(".oa_req_field").removeClass('required');
            $("#flight_details_area").hide();
            $(".fda_req_field").removeClass('required');

            $("#embassy_info_area").show();
            $(".embassy_info_req_field").addClass('required');

            $("#visit_purpose_div").hide();
            $("#visa_purpose_id").removeClass('required');

            $("#embassy_airport_label").text("Embassy/ High Commission Info");
            $("#brief_job_description_div").show();
            $("#brief_job_description").addClass('required');

            $("#manpowerSection").show();
        } else {
            $("#compensation_benefit_area").show();
            $("#particular_information").show();
            $(".cb_req_field").addClass('required');
            $(".basic_local_amount").attr("min", "0.01");
            $("#basic_local_amount_label").addClass("required-star"); // Required for other type E visa
            
            $(".mp_req_field").addClass('required');
            $("#MP_LOC_EXECUTIVE").addClass('required');
            $("#MP_LOC_STAFF").addClass('required');
            $("#FOR_LOC_EXECUTIVE").addClass('required');
            $("#FOR_LOC_STAFF").addClass('required');

            $("#travel_history_area").show();
            $(".th_req_field").addClass('required');

            $("#on_arrival_information_area").hide();
            $("#on_arrival_information_area1").hide();
            $(".oa_req_field").removeClass('required');
            $("#flight_details_area").hide();
            $(".fda_req_field").removeClass('required');

            $("#embassy_info_area").show();
            $(".embassy_info_req_field").addClass('required');

            $("#visit_purpose_div").hide();
            $("#visa_purpose_id").removeClass('required');

            $("#embassy_airport_label").text("Embassy/ High Commission Info");
            $("#brief_job_description_div").show();
            $("#brief_job_description").addClass('required');

            $("#manpowerSection").show();
        }

        attachment_key = "vrn" + attachment_key;
        if (dept_id == 1) {
            attachment_key += "cml";
        } else if (dept_id == 2) {
            attachment_key += "i";
        } else {
            attachment_key += "comm";
        }
        let is_doc_loaded = 0;
        if (app_type_id != 0 && app_type_id != '' && is_doc_loaded == 0) {
            $.ajax({
                type: "POST",
                url: '/visa-recommendation/getDocList',
                dataType: "json",
                data: {
                    _token: _token,
                    attachment_key: attachment_key,
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

    // Previous work permit information in Bangladesh section show and required field
    let is_attachment_loaded = 0;
    function loadPreviousTravelHistoryAttachment() {
        if (is_attachment_loaded == 0) {
            var _token = $('input[name="_token"]').val();
            var app_id = $("#app_id").val();
            var viewMode = $("#viewMode").val();

            $.ajax({
                type: "POST",
                url: '/visa-recommendation/getTravelHistoryDocList',
                dataType: "json",
                data: {
                    _token: _token,
                    app_id: app_id,
                    attachment_key: "vrn_travel_history",
                    doc_section: "type2",
                    viewMode: viewMode
                },
                success: function (result) {
                    if (result.html != undefined) {
                        $('#travelHistorydocListDiv').html(result.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Unknown error occured. Please, try again after reload');
                }
            });
            is_attachment_loaded++;
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

        var form = $("#VisaRecommendationForm").show();
        form.find('#save_as_draft').css('display', 'none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex == 1) {
                    var visaCategoryIsSelect = $('input[name=app_type_id]:checked').length;
                    if (visaCategoryIsSelect != 1) {
                        //$(".visaTypeTab").css({"border": "1px solid red"});
                        alert('Sorry! You must select any one of the Visa types.');
                        return false;
                    }
                }

                if (newIndex == 3) {
                    var visa_type = $('input[name=app_type_id]:checked').val();

                    if (visa_type == 5) { // Visa on arrival

                        var arrival_date = new Date($('#arrival_date').val().replace(/-/g, ' ')); // convert to actual date
                        var departure_date = new Date($('#departure_date').val().replace(/-/g, ' ')); // convert to actual date

                        if ((Date.parse(arrival_date) > Date.parse(departure_date))) {
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'The departure date must be a date after arrival date.'
                            });
                            return false;
                        }
                    }
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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/visa-recommendation/preview'); ?>', 'Sample', '');
            } else {
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            }
        });


        //Date picker starts
//        $('[data-toggle="tooltip"]').tooltip();

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

        // startDateSelected = '';
        // $(".PassportIssueDate").on("dp.change", function (e) {
        // $('#pass_expiry_date').val('');
//            $('#construction_duration').val('');

        // var nextDate = e.date.add(1, 'day');
        // $('.PassportExpiryDate').datetimepicker({
        //     format: 'DD-MMM-YYYY',
        //     minDate: nextDate,
        //useCurrent: false // Important! See issue #1075
        //});
//            $('.PassportExpiryDate').data("DateTimePicker").minDate(nextDate);
//             startDateSelected = nextDate;
//         });
//         $(".PassportExpiryDate").on("dp.change", function (e) {
//             var startDateVal = $("#pass_issue_date").val();
//             var day = moment(startDateVal, ['DD-MMM-YYYY', 'YYYY-MM-DD']);
//             var startDate = moment(day).add(1, 'day');
//             if (startDateVal != '') {
//                 $('.PassportExpiryDate').data("DateTimePicker").minDate(startDate);
//             }
        // var endDate = moment($("#pass_expiry_date").val()).add(1, 'day');
        // var endDateMoment = moment(endDate, ['DD-MMM-YYYY', 'YYYY-MM-DD']);
        // var endDateVal = $("#pass_expiry_date").val();
        // var dayEnd = moment(endDateVal, ['DD-MMM-YYYY', 'YYYY-MM-DD']);
        // var endDate = moment(dayEnd).add(1, 'day');

        //var startDate = startDateSelected;
        //var endDate = e.date.add(1, 'day');
//            if (startDate != '' && endDate != '' && $("#pass_expiry_date").val() > $("#pass_issue_date").val()) {
//                var days = (endDate - startDate) / 1000 / 60 / 60 / 24;
//                $('#construction_duration').val(Math.floor(days));
//            }
        //});
        // $('.PassportExpiryDate').trigger('dp.change');  // End of Construction Schedule


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
            hiddenInput: "ex_office_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#office_telephone_no").intlTelInput({
            hiddenInput: "office_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#th_org_telephone_no").intlTelInput({
            hiddenInput: "th_org_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
        $("#auth_cell_number").intlTelInput({
            hiddenInput: "auth_cell_number",
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

        $("#auth_mobile_no").intlTelInput({
            hiddenInput: "sfp_contact_phone",
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
        {{--initail -input plugin script end--}}

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

    function maritalStatusWiseSpouseInfoShow(maritalStatus) {
        if (maritalStatus == 'married') {
            document.getElementById('business_category').style.display = 'block';
        } else {
            document.getElementById('business_category').style.display = 'none';
        }
    }

    function isWorkInBangladesh(workStatus) {
        if(workStatus == 'yes') {
            document.getElementById('emp_spouse_org_name_div').style.display = 'block';
        } else {
            document.getElementById('emp_spouse_org_name_div').style.display = 'none';
        }
    }

</script>