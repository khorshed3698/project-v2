@extends('layouts.front')
<?php $userData = session('oauth_data');
//dd($userData);
?>


<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    .identity_hover, .identity_type {
        cursor: pointer;
    }

    fieldset.scheduler-border {
        border: 1px solid #afa3a3 !important;
        padding: 0 1.4em 0 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow: 0px 0px 0px 0px #000;
        box-shadow: 0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width: auto;
        padding: 0 10px;
        border-bottom: none;
    }

    input[type="radio"] {
        -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
        -moz-appearance: checkbox; /* Firefox */
        -ms-appearance: checkbox; /* not currently supported */
    }

    .select2-container--bootstrap {
        display: block;
        width: 100% !important;
    }

    .intl-tel-input {
        width: 100% !important;
    }

    /*.select2-results {*/
    /*display: none !important;*/
    /*}*/
</style>
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/select2-bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset("build/css/intlTelInput_v16.0.8.css") }}"/>

<style>
    .intl-tel-input .country-list {
        z-index: 5;
    }
</style>

@section("content")



    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class=" box-div">
                <h3 class="text-center">Registration process</h3>

                @if(Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif


                @if($errors->has())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <hr/>

                <div class="col-md-7 col-sm-7">
                    {!! Form::open(array('url' => '/osspid/store','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'OSSSignUpForm',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

                    <fieldset>
                        {{-- First Name --}}
                        <div class="form-group has-feedback {{ $errors->has('user_first_name') ? 'has-error' : ''}}">
                            <label class="col-md-5 text-left required-star" for="user_first_name">First Name</label>
                            <div class="col-md-7">
                                {!! Form::text('user_first_name', $userData->user_full_name, $attributes = array('class'=>'form-control textOnly required input-sm', 'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your first name', 'id'=>"user_first_name")) !!}
                                <span class="fa fa-user form-control-feedback"></span>
                                {!! $errors->first('user_first_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Middle Name --}}
                        <div class="form-group has-feedback {{ $errors->has('user_middle_name') ? 'has-error' : ''}}">
                            <label class="col-md-5 text-left" for="user_middle_name">Middle Name</label>
                            <div class="col-md-7">
                                {!! Form::text('user_middle_name', '', $attributes = array('class'=>'form-control textOnly input-sm', 'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your middle name', 'id'=>"user_middle_name")) !!}
                                <span class="fa fa-user form-control-feedback"></span>
                                {!! $errors->first('user_middle_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Last Name --}}
                        <div class="form-group has-feedback {{ $errors->has('user_last_name') ? 'has-error' : ''}}">
                            <label class="col-md-5 text-left" for="user_last_name">Last Name</label>
                            <div class="col-md-7">
                                {!! Form::text('user_last_name', '', $attributes = array('class'=>'form-control textOnly input-sm', 'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your last name', 'id'=>"user_last_name")) !!}
                                <span class="fa fa-user form-control-feedback"></span>
                                {!! $errors->first('user_last_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Gender --}}
                        <div class="form-group has-feedback {{$errors->has('user_gender') ? 'has-error': ''}}">
                            {!! Form::label('user_gender','Gender',['class'=>'text-left required-star col-md-5', 'id' => 'user_gender']) !!}
                            <div class="col-md-7">
                                <label class="identity_hover">
                                    {!! Form::radio('user_gender', 'Male', (ucfirst($userData->gender)=="Male")?true:false, ['class'=>'required']) !!}
                                    Male
                                </label>
                                &nbsp;&nbsp;
                                <label class="identity_hover">
                                    {!! Form::radio('user_gender', 'Female', (ucfirst($userData->gender)=="Female")?true:false, ['class'=>'required']) !!}
                                    Female
                                </label>
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="form-group has-feedback {{$errors->has('user_DOB') ? 'has-error' : ''}}">
                            <label for="user_DOB" class="col-md-5 text-left required-star">Date of Birth</label>
                            <div class="col-md-7">
                                <div class="datepicker input-group date" data-date="12-03-2015"
                                     data-date-format="dd-mm-yyyy">
                                    {!! Form::text('user_DOB', '', ['class'=>'form-control input-sm required', 'id'=>'user_DOB', 'placeholder' => 'Pick from calendar', 'data-rule-maxlength'=>'40']) !!}
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>

                                </div>
                                {!! $errors->first('user_DOB','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Organization Types --}}
                        <div class="form-group has-feedback {{$errors->has('company_type') ? 'has-error': ''}}"
                             id="company_type_div">
                            {!! Form::label('company_type','Organization Types :',['class'=>'text-left col-md-5', 'id' => 'company_type_label']) !!}
                            <div class="col-md-7">
                                <label>{!! Form::radio('company_type', '1', false, ['class'=>'company_type required']) !!}
                                    Existing</label>
                                &nbsp;&nbsp;
                                <label>{!! Form::radio('company_type', '2',false, ['class'=>'company_type required']) !!}
                                    New</label>
                                {!! $errors->first('company_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Organization Name --}}
                        <div>
                            <div class="form-group has-feedback hidden {{ $errors->has('company_name') ? 'has-error' : ''}}"
                                 id="new_company">
                                <label class="col-md-5 text-left required-star" for="company_info">Organization Name
                                    (English)</label>
                                <div class="col-md-7">
                                    {!! Form::text('company_name', null, $attributes = array('class'=>'form-control required input-sm company',  'data-rule-maxlength'=>'255',
                            'placeholder'=>'Organization name in English', 'id'=>"company_name")) !!}
                                    {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group has-feedback hidden {{ $errors->has('company_name_bn') ? 'has-error' : ''}}"
                                 id="new_company_bn">
                                <label class="col-md-5 text-left" for="company_info">Organization Name (Bangla)</label>
                                <div class="col-md-7">
                                    {!! Form::text('company_name_bn', null, $attributes = array('class'=>'form-control input-sm',  'data-rule-maxlength'=>'255',
                            'placeholder'=>'Organization name in Bangla', 'id'=>"company_name_bn")) !!}
                                    {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>

                        {{-- Company Info --}}
                        <div class="form-group has-feedback hidden {{ $errors->has('company_info') ? 'has-error' : ''}}"
                             id="company_info_div">
                            <label class="col-md-5 text-left required-star" for="company_info">Organization Info</label>
                            <div class="col-md-7">
                                {!! Form::select('company_info', $company_infos, '', $attributes = array('class'=>'input-sm form-control required',
                                'id'=>"company_info", 'style'=>'width:100%')) !!}
                                {!! $errors->first('company_info','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- Nationality --}}
                        <div class="form-group has-feedback {{ $errors->has('nationality') ? 'has-error' : ''}}">
                            <label for="nationality" class="col-md-5 text-left required-star">Nationality</label>
                            <div class="col-md-7">
                                {!! Form::select('nationality', $nationalities, '', $attributes = array('class'=>'form-control input-sm required',
                                       'placeholder' => 'Select one', 'id'=>"nationality")) !!}
                                {!! $errors->first('nationality','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        {{-- National ID No --}}
                        <div class="form-group has-feedback {{ $errors->has('user_nid') ? 'has-error' : ''}} hidden"
                             id="nid_div">
                            <label for="user_nid" class="col-md-5 text- required-star">National ID Number</label>
                            <div class="col-md-7">
                                {!! Form::text('user_nid', null, $attributes = array('class'=>'form-control bd_nid input-sm',  'data-rule-maxlength'=>'17',
                                'placeholder'=>'Enter your NID number', 'id'=>"user_nid")) !!}
                                <span class="fa fa-credit-card form-control-feedback"></span>
                                <small class="text-danger" style="font-size: 9px; font-weight: bold">You need to enter
                                    13 or 17 digits NID or 10 digits smart card ID.</small>
                                {!! $errors->first('user_nid','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <fieldset class="scheduler-border hidden" id="passport_div">
                            <legend class="scheduler-border text-left">Passport Information</legend>

                            {{-- Passport No --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_no') ? 'has-error' : ''}}">
                                <label for="passport_no" class="col-md-5 text-left required-star">Passport
                                    Number</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_no', null, $attributes = array('class'=>'form-control pass_req_field input-sm passport', 'data-rule-maxlength'=>'20',
                                    'placeholder'=>'Enter your passport number', 'id'=>"passport_no")) !!}
                                    <span class="fa fa-book form-control-feedback"></span>
                                    {!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Personal No --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_personal_no') ? 'has-error' : ''}}">
                                <label for="passport_personal_no" class="col-md-5 text-left">Personal Number</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_personal_no', null, $attributes = array('class'=>'form-control number input-sm',  'data-rule-maxlength'=>'40',
                                    'placeholder'=>'Enter your personal number', 'id'=>"passport_personal_no")) !!}
                                    <span class="fa fa-book form-control-feedback"></span>
                                    {!! $errors->first('passport_personal_no','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Surname --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_surname') ? 'has-error' : ''}}">
                                <label for="passport_surname" class="col-md-5 text-left">Surname</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_surname', null, $attributes = array('class'=>'form-control textOnly input-sm',  'data-rule-maxlength'=>'40',
                                    'placeholder'=>'Enter your surname', 'id'=>"passport_surname")) !!}
                                    <span class="fa fa-user form-control-feedback"></span>
                                    {!! $errors->first('passport_surname','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Issuing Authority --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_issuing_authority') ? 'has-error' : ''}}">
                                <label for="passport_issuing_authority" class="col-md-5 text-left">Issuing
                                    Authority</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_issuing_authority', null, $attributes = array('class'=>'form-control textOnly input-sm',  'data-rule-maxlength'=>'40',
                                    'placeholder'=>'Enter your issuing authority', 'id'=>"passport_issuing_authority")) !!}
                                    <span class="fa fa-user form-control-feedback"></span>
                                    {!! $errors->first('passport_issuing_authority','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Given Name	 --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_given_name') ? 'has-error' : ''}}">
                                <label for="passport_given_name" class="col-md-5 text-left">Given Name</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_given_name', null, $attributes = array('class'=>'form-control textOnly input-sm',  'data-rule-maxlength'=>'40',
                                    'placeholder'=>'Enter your given name', 'id'=>"passport_given_name")) !!}
                                    <span class="fa fa-user form-control-feedback"></span>
                                    {!! $errors->first('passport_given_name','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Nationality --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_nationality') ? 'has-error' : ''}}">
                                <label for="passport_nationality"
                                       class="col-md-5 text-left required-star">Nationality</label>
                                <div class="col-md-7">
                                    {!! Form::select('passport_nationality', $nationalities, '', $attributes = array('class'=>'form-control input-sm pass_req_field',  'data-rule-maxlength'=>'40',
                                        'placeholder' => 'Select one', 'id'=>"passport_nationality")) !!}
                                    {!! $errors->first('passport_nationality','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="form-group has-feedback {{$errors->has('passport_DOB') ? 'has-error' : ''}}">
                                <label for="passport_DOB" class="col-md-5 text-left required-star">Date of Birth</label>
                                <div class="col-md-7">
                                    <div class="datepicker input-group date" data-date="12-03-2015"
                                         data-date-format="dd-mm-yyyy">
                                        {!! Form::text('passport_DOB', '', ['class'=>'form-control input-sm pass_req_field', 'placeholder' => 'Pick from calendar', 'data-rule-maxlength'=>'40']) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('passport_DOB','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Place of Birth --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_place_of_birth') ? 'has-error' : ''}}">
                                <label for="passport_place_of_birth" class="col-md-5 text-left required-star">Place of
                                    Birth</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_place_of_birth', null, $attributes = array('class'=>'form-control textOnly input-sm pass_req_field',  'data-rule-maxlength'=>'40',
                                    'placeholder'=>'Enter your place of birth', 'id'=>"passport_place_of_birth")) !!}
                                    <span class="fa fa-map-marker form-control-feedback"></span>
                                    {!! $errors->first('passport_place_of_birth','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Date of Issue --}}
                            <div class="form-group has-feedback {{$errors->has('passport_date_of_issue') ? 'has-error' : ''}}">
                                <label for="passport_date_of_issue" class="col-md-5 text-left required-star">Date of
                                    Issue</label>
                                <div class="col-md-7">
                                    <div class="datepicker input-group date" data-date="12-03-2015"
                                         data-date-format="dd-mm-yyyy">
                                        {!! Form::text('passport_date_of_issue', '', ['class'=>'form-control input-sm pass_req_field', 'placeholder' => 'Pick from calendar', 'data-rule-maxlength'=>'40']) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('passport_date_of_issue','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Date of Expire --}}
                            <div class="form-group has-feedback {{$errors->has('passport_date_of_expire') ? 'has-error' : ''}}">
                                <label for="passport_date_of_expire" class="col-md-5 text-left required-star">Date of
                                    Expire</label>
                                <div class="col-md-7">
                                    <div class="passExpiryDate input-group date" data-date="12-03-2015"
                                         data-date-format="dd-mm-yyyy">
                                        {!! Form::text('passport_date_of_expire', '', ['class'=>'form-control input-sm pass_req_field', 'placeholder' => 'Pick from calendar', 'data-rule-maxlength'=>'40']) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('passport_date_of_expire','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border text-left">Contact Details</legend>

                            {{-- Email --}}
                            <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : ''}}">
                                <label for="email" class="col-md-5 text-left required-star">Email Address</label>
                                <div class="col-md-7">
                                    {!! Form::text('email', $userData->user_email, $attributes = array('class'=>'form-control input-sm required email','readonly',
                                    'placeholder'=>'Enter your e-mail', 'id'=>"email",'style'=>"cursor:not-allowed")) !!}
                                    <span class="fa fa-envelope form-control-feedback"></span>
                                    {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Mobile Number --}}
                            <div class="form-group has-feedback {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                                <label for="user_phone" class="col-md-5 text-left required-star">Mobile Number</label>
                                <div class="col-md-7">
                                    {!! Form::text('user_phone', $userData->mobile, $attributes = array('class'=>'form-control input-sm required',
                                    'maxlength'=>"20", 'data-rule-maxlength'=>'40', 'placeholder'=>'Enter your mobile number','id'=>"user_phone")) !!}
                                    {{--<span class="fa  fa-phone form-control-feedback"></span>--}}
                                    <span class="text-danger mobile_number_error"></span>
                                    {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Home Number --}}
                            <div class="form-group has-feedback {{ $errors->has('user_home_phone') ? 'has-error' : ''}}">
                                <label for="user_home_phone" class="col-md-5 text-left">Phone Number (Home)</label>
                                <div class="col-md-7">
                                    {!! Form::text('user_home_phone', null, $attributes = array('class'=>'form-control input-sm phone_or_mobile',
                                    'maxlength'=>"20", 'data-rule-maxlength'=>'40', 'placeholder'=>'Enter your phone number of home','id'=>"user_home_phone")) !!}
                                    <span class="text-danger mobile_number_error"></span>
                                    {!! $errors->first('user_home_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Office Number --}}
                            <div class="form-group has-feedback {{ $errors->has('user_office_phone') ? 'has-error' : ''}}">
                                <label for="user_office_phone" class="col-md-5 text-left">Phone Number (Office)</label>
                                <div class="col-md-7">
                                    {!! Form::text('user_office_phone', null, $attributes = array('class'=>'form-control input-sm phone_or_mobile',
                                    'maxlength'=>"20", 'data-rule-maxlength'=>'40', 'placeholder'=>'Enter your phone number of office','id'=>"user_office_phone")) !!}
                                    <span class="text-danger mobile_number_error"></span>
                                    {!! $errors->first('user_office_phone','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border" id="address_bd">
                            <legend class="scheduler-border text-left">Address (Bangladesh)</legend>

                            {{-- Country --}}
                            <div class="form-group has-feedback {{ $errors->has('country') ? 'has-error' : ''}}">
                                {!! Form::hidden('location_lat', '', $attributes = array('id'=>"location_lat")) !!}
                                {!! Form::hidden('location_lon', '', $attributes = array('id'=>"location_lon")) !!}
                                <label for="country" class="col-md-5 text-left required-star">Country </label>
                                <div class="col-md-7">
                                    {!! Form::select('country', $countries, 'BD', $attributes = array('class'=>'form-control input-sm ab_Req_field', 'data-rule-maxlength'=>'40',
                                    'placeholder' => 'Select one', 'id'=>"country")) !!}
                                    {!! $errors->first('country','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- District --}}
                            <div class="form-group has-feedback {{ $errors->has('district') ? 'has-error' : ''}}">
                                <label for="district" class="col-md-5 text-left required-star"> District </label>
                                <div class="col-md-7">
                                    {!! Form::select('district', $districts, '', $attributes = array('class'=>'form-control input-sm ab_Req_field', 'placeholder' => 'Select district',
                                    'data-rule-maxlength'=>'40','id'=>"district")) !!}
                                    {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Thana --}}
                            <div class="form-group has-feedback {{ $errors->has('thana') ? 'has-error' : ''}}">
                                <label for="thana" class="col-md-5 text-left required-star">Police Station</label>
                                <div class="col-md-7">
                                    {!! Form::select('thana', [''], '', $attributes = array('class'=>'form-control input-sm ab_Req_field', 'placeholder' => 'Select district first',
                                    'data-rule-maxlength'=>'40','id'=>"thana")) !!}
                                    {!! $errors->first('thana','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Post office --}}
                            <div class="form-group has-feedback {{ $errors->has('post_office') ? 'has-error' : ''}}"
                                 id="state_div">
                                <label for="post_office" class="col-md-5 text-left required-star">Post Office</label>
                                <div class="col-md-7">
                                    {!! Form::text('post_office', '', $attributes = array('class'=>'form-control textOnly input-sm ab_Req_field', 'placeholder' => 'Name of your post office',
                                    'data-rule-maxlength'=>'40', 'id'=>"post_office")) !!}
                                    <span class="fa fa-map-marker form-control-feedback"></span>
                                    {!! $errors->first('post_office','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Post --}}
                            <div class="form-group has-feedback {{ $errors->has('post_code') ? 'has-error' : ''}}">
                                <label for="post_code" class="col-md-5 text-left required-star">Post Code
                                    (Number)</label>
                                <div class="col-md-7">
                                    {!! Form::text('post_code', null, $attributes = array('class'=>'form-control post_code_bd input-sm ab_Req_field', 'placeholder'=>'Enter your post code',
                                    'maxlength'=>"20",'id'=>"post_code")) !!}
                                    {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Address  --}}
                            <div class="form-group has-feedback {{ $errors->has('road_no') ? 'has-error' : ''}}">
                                <label for="road_no" class="col-md-5 text-left required-star"> Address</label>
                                <div class="col-md-7">
                                    {!! Form::text('road_no', '', $attributes = array('class'=>'form-control bnEng input-sm ab_Req_field', 'data-rule-maxlength'=>'100',
                                    'placeholder' => 'Enter road / street Name / number', 'id'=>"road_no")) !!}
                                    <span class="fa fa-road  form-control-feedback"></span>
                                    {!! $errors->first('road_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border hidden" id="address_abroad">
                            <legend class="scheduler-border text-left">Address (Abroad)</legend>

                            {{-- Country --}}
                            <div class="form-group has-feedback {{ $errors->has('country_abroad') ? 'has-error' : ''}}">
                                {!! Form::hidden('location_lat', '', $attributes = array('id'=>"location_lat")) !!}
                                {!! Form::hidden('location_lon', '', $attributes = array('id'=>"location_lon")) !!}
                                <label for="country_abroad" class="col-md-5 text-left required-star">Country </label>
                                <div class="col-md-7">
                                    {!! Form::select('country_abroad', $countries, null, $attributes = array('class'=>'form-control input-sm aa_req_field', 'data-rule-maxlength'=>'40',
                                    'placeholder' => 'Select One', 'id'=>"country_abroad")) !!}
                                    {!! $errors->first('country_abroad','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- State --}}
                            <div class="form-group has-feedback {{ $errors->has('state') ? 'has-error' : ''}}"
                                 id="state_div">
                                <label for="state" class="col-md-5 text-left required-star"> State </label>
                                <div class="col-md-7">
                                    {!! Form::text('state', '', $attributes = array('class'=>'form-control textOnly input-sm aa_req_field', 'placeholder' => 'Name of your state / division',
                                    'data-rule-maxlength'=>'40', 'id'=>"state")) !!}
                                    <span class="fa fa-map-marker form-control-feedback"></span>
                                    {!! $errors->first('state','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Province / City --}}
                            <div class="form-group has-feedback {{ $errors->has('province') ? 'has-error' : ''}}"
                                 id="province_div">
                                <label for="province" class="col-md-5 text-left required-star">City</label>
                                <div class="col-md-7">
                                    {!! Form::text('province', '', $attributes = array('class'=>'form-control textOnly input-sm aa_req_field', 'data-rule-maxlength'=>'40',
                                    'placeholder' => 'Enter your city', 'id'=>"province")) !!}
                                    <span class="fa fa-map-marker form-control-feedback"></span>
                                    {!! $errors->first('province','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Post --}}
                            <div class="form-group has-feedback {{ $errors->has('post_code_abroad') ? 'has-error' : ''}}">
                                <label for="post_code_abroad" class="col-md-5 text-left required-star">Post Code</label>
                                <div class="col-md-7">
                                    {!! Form::text('post_code_abroad', null, $attributes = array('class'=>'form-control engOnly input-sm aa_req_field', 'placeholder'=>'Enter your post code',
                                    'maxlength'=>"20",'id'=>"post_code_abroad")) !!}
                                    {!! $errors->first('post_code_abroad','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="form-group has-feedback {{ $errors->has('road_no_abroad') ? 'has-error' : ''}}">
                                <label for="road_no_abroad" class="col-md-5 text-left required-star"> Address</label>
                                <div class="col-md-7">
                                    {!! Form::text('road_no_abroad', '', $attributes = array('class'=>'form-control input-sm aa_req_field', 'data-rule-maxlength'=>'100',
                                    'placeholder' => 'Enter road / street name / number', 'id'=>"road_no_abroad")) !!}
                                    <span class="fa fa-road  form-control-feedback"></span>
                                    {!! $errors->first('road_no_abroad','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </fieldset>

                        <div class="form-group has-feedback {{ $errors->has('authorization_file') ? 'has-error' : ''}}">
                            <label class="col-md-5 text-left required-star" for="authorization_file">Authorization
                                letter</label>
                            <div class="col-md-7">
                                {!! Form::file('authorization_file', ['id'=>'auth_letter', 'class'=>'form-control input-sm required', 'accept'=>"application/pdf"]) !!}
                                <small class="text-danger" style="font-size: 9px; font-weight: bold"> [Format: *.PDF |
                                    Maximum 3 MB, Application with Name & Signature] </small>
                                {!! $errors->first('authorization_file','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group pull-right  {{$errors->has('g-recaptcha-response') ? 'has-error' : ''}}">
                            <div class="col-md-12">
                                {!! Recaptcha::render() !!}
                                {!! $errors->first('g-recaptcha-response','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-lg btn-primary pull-right"><b>Submit</b></button>
                            </div>
                        </div>

                    </fieldset>
                    <div class="clearfix"></div>
                    <hr>

                </div>

                <div class="col-md-5">
                    <h3>Terms of Usage of BIDA OSS</h3>
                    Terms and conditions to use this system can be briefed as -
                    <ul>
                        <li>You must follow any policies made available to you within the Services.</li>
                        <li>You have to fill all the given fields with correct information and take responsibility if
                            any wrong or misleading information has been given
                        </li>
                        <li>You are responsible for the activity that happens on or through your account. So, keep your
                            password confidential.
                        </li>
                        <li>We may modify these terms or any additional terms that apply to a Service to, for example,
                            reflect changes to the law or changes to our Services. You should look at the terms
                            regularly.
                        </li>
                    </ul>

                    <br/>
                    <h5><strong>What is an Authorization Letter ?</strong></h5>
                    <p>If anyone wants to work on behalf of an organization, the company's managing director / chief of
                        the company will sanction a consent letter printed on a Letter Head pad of the respective
                        company.</p>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection


@section('footer-script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {

            function matchCustom(params, data) {
                $('.select2-results').css('display', 'block');

                // If there are no search terms
                if ($.trim(params.term) === '') {
                    $('.select2-results').css('display', 'none');
                    return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }

                if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    var modifiedData = $.extend({}, data, true);
                    //modifiedData.text += ' (matched)';

                    return modifiedData;
                }
                // Return `null` if the term should not be displayed
                return null;
            }

            $("#company_info").select2({
                matcher: matchCustom,
                minimumInputLength: 3,
                tags: false,
            });

            $(function () {
                var _token = $('input[name="_token"]').val();
                $("#OSSSignUpForm").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });

            var today = new Date();
            var yyyy = today.getFullYear();
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110)
            });

            $(".passExpiryDate").datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: '01/01/' + (yyyy + 10),
                minDate: '01/01/' + (yyyy - 10)
            });

            $('.company_type').click(function (e) {
                if (this.value == '1') { // 1 for old
                    $('#company_info_div').removeClass('hidden');
                    $('#company_info').addClass('required');
                    $('#exist_company_div').removeClass('hidden');
                    $('#companyInfo').addClass('hidden');
                    $('#exist_company_div').addClass('required');
                    $('#new_company').addClass('hidden');
                    $('#new_company_bn').addClass('hidden');
                    $('#new_company_error').removeClass('required');
                    $('#company_name').removeClass('error');
                    $('#company_name').removeClass('required');
                    $('#company_id').addClass('required');
                } else if (this.value == '2') { // 2 is for new
                    $('#company_info_div').addClass('hidden');
                    $('#company_info').removeClass('required');
                    $('#exist_company_div').addClass('hidden');
                    $('#companyInfo').removeClass('hidden');
                    $('#exist_company_div').removeClass('required');
                    $('#new_company').removeClass('hidden');
                    $('#new_company_bn').removeClass('hidden');
                    $('.company').addClass('required');
                    $('#new_company_error').addClass('required');
                    $('#company_id').removeClass('error');
                    $('#company_id').removeClass('required');
                }
            });
//                            $('.company_type').trigger('click');


            // nationality
            $("#nationality").change(function (e) {
                //updateMobileCountryCode($(this));
                if (this.value == 'BD') {
                    $('#passport_div').addClass('hidden');
                    $('.pass_req_field').removeClass('required');
                    $('#passport_no').val('');
                    $('#nid_div').removeClass('hidden');
                    $('#user_nid').addClass('required');
                    $('#address_abroad').addClass('hidden');
                    $('.aa_req_field').removeClass('required');
                    $('#address_bd').removeClass('hidden');
                    $('.ab_Req_field').addClass('required');
                } else {
                    $('#passport_div').removeClass('hidden');
                    $('.pass_req_field').addClass('required');
                    $('#nid_div').addClass('hidden');
                    $('#user_nid').removeClass('required');
                    $('#user_nid').val('');
                    $('#address_abroad').removeClass('hidden');
                    $('.aa_req_field').addClass('required');
                    $('#address_bd').addClass('hidden');
                    $('.ab_Req_field').removeClass('required');
                }
            });
            $("#nationality").trigger('change');

            // country dialing code function
//                            function updateMobileCountryCode(obj){
//                                var dialing_code = "", $this = obj;
//
//                                if($this.val() != 0){
//                                    dialing_code = $this.find('option:selected').attr('rel');
//                                }
//                                $("#user_phone").val(dialing_code);
//                            }

            $("#district").change(function (e) {
                var districtId = $('#district').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select Thana</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#thana").html(option);
                        $(self).next().hide();
                    }
                });
            });
        });

    </script>

    <script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
    <script src="{{ asset("build/js/utils.js") }}" type="text/javascript"></script>
    <script>
        $(function () {
            $("#user_phone").intlTelInput({
                hiddenInput: "user_phone",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true
            });
            $("#user_home_phone").intlTelInput({
                hiddenInput: "user_home_phone",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true
            });
            $("#user_office_phone").intlTelInput({
                hiddenInput: "user_office_phone",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true
            });
        });
    </script>
@endsection
