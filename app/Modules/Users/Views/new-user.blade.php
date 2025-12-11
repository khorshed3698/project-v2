@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset("build/css/intlTelInput_v16.0.8.css") }}" />

    <style>
        .intl-tel-input .country-list {
            z-index: 5;
        }
        .iti {
            width: 100%;
        }
    </style>
@endsection
@section("content")
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>

    <div class="col-md-12">
        @include('message.message')
    </div>
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong><i class="fa fa-user-plus" aria-hidden="true"></i> Create new user</strong></h5>
            </div>

            {!! Form::open(array('url' => '/users/store-new-user','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'create_user_form',
            'enctype' =>'multipart/form-data', 'files' => 'true')) !!}

            <div class="panel-body">
                <div class="col-md-6">
                    {{-- First Name --}}
                    <div class="form-group has-feedback {{ $errors->has('user_first_name') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left required-star" for="user_first_name">First Name</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                {!! Form::text('user_first_name', '', $attributes = array('class'=>'form-control required input-md', 'data-rule-maxlength'=>'40',
                            'placeholder'=>'Enter your first name', 'id'=>"user_first_name")) !!}
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            </div>
                            {!! $errors->first('user_first_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Middle Name --}}
                    <div class="form-group has-feedback {{ $errors->has('user_middle_name') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left" for="user_middle_name">Middle Name</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                {!! Form::text('user_middle_name', '', $attributes = array('class'=>'form-control input-md', 'data-rule-maxlength'=>'40',
                            'placeholder'=>'Enter your middle name', 'id'=>"user_middle_name")) !!}
                                <span class="input-group-addon"><i class="fa fa-user"></i> </span>
                                {!! $errors->first('user_middle_name','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>


                    {{-- Last Name --}}
                    <div class="form-group has-feedback {{ $errors->has('user_last_name') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left" for="user_last_name">Last Name</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                {!! Form::text('user_last_name', '', $attributes = array('class'=>'form-control input-md', 'data-rule-maxlength'=>'40',
                            'placeholder'=>'Enter your last name', 'id'=>"user_last_name")) !!}
                                <span class="input-group-addon"><i class="fa fa-user"></i> </span>
                            </div>
                            {!! $errors->first('user_last_name','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>


                    {{-- designation --}}
                    <div class="form-group has-feedback {{ $errors->has('designation') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left required-star" for="designation">Designation</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                {!! Form::text('designation', '', $attributes = array('class'=>'form-control input-md required', 'data-rule-maxlength'=>'40',
                        'placeholder'=>'Enter your designation', 'id'=>"user_last_name")) !!}
                                <span class="input-group-addon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
                            </div>
                            {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="form-group has-feedback {{$errors->has('user_gender') ? 'has-error': ''}}">
                        {!! Form::label('user_gender','Gender',['class'=>'text-left required-star col-md-4', 'id' => 'user_gender']) !!}
                        <div class="col-md-7">
                            <label class="identity_hover">
                                {!! Form::radio('user_gender', 'Male', false, ['class'=>'required', 'id' => 'male']) !!} Male
                            </label>
                            &nbsp;&nbsp;
                            <label class="identity_hover">
                                {!! Form::radio('user_gender', 'Female', false, ['class'=>'required', 'id' => 'female']) !!} Female
                            </label>
                        </div>
                    </div>

                    {{-- Date of birth --}}
                    <div class="form-group has-feedback {{$errors->has('user_DOB') ? 'has-error' : ''}}">
                        {!! Form::label('user_DOB','Date of Birth',['class'=>'col-md-4 required-star']) !!}
                        <div class="col-md-7">
                            <div class="userDP input-group date">
                                {!! Form::text('user_DOB', '', ['class'=>'form-control input-md required', 'placeholder' => 'Pick from calender']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            {!! $errors->first('user_DOB','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    @if($logged_user_type == '1x101' or $logged_user_type == '14x141') {{-- For System Admin or Programmer --}}

                    <div class="form-group has-feedback {{ $errors->has('user_type') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">User Type</label>
                        <div class="col-md-7">
                            {!! Form::select('user_type', $user_types, '', $attributes = array('class'=>'form-control input-md required','data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"user_type")) !!}
                            {!! $errors->first('user_type','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{--                    <div style="display: none" class="form-group has-feedback companyUserType{{ $errors->has('company_id') ? 'has-error' : ''}}">--}}
                    {{--                        <label  class="col-md-4 required-star">Company</label>--}}
                    {{--                        <div class="col-md-7">--}}
                    {{--                            {!! Form::select('company_id', $company_list, '', $attributes = array('class'=>'form-control input-md required','data-rule-maxlength'=>'40',--}}
                    {{--                            'placeholder' => 'Select One', 'id'=>"company_id")) !!}--}}
                    {{--                            {!! $errors->first('company_id','<span class="help-block">:message</span>') !!}--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{-- Department --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('department') ? 'has-error' : ''}}" id="department_div">
                        <label class="col-md-4 text-left required-star" for="department">Department</label>
                        <div class="col-md-7">
                            {!! Form::select('department', $departments, '', $attributes = array('class'=>'form-control input-md required',
                            'id'=>"department")) !!}
                            {!! $errors->first('department','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Desk --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('desk') ? 'has-error' : ''}}" id="desk_div">
                        <label class="col-md-4 text-left required-star" for="desk">Desk</label>
                        <div class="col-md-7">
                            {!! Form::select('desk', $desks, '', $attributes = array('class'=>'form-control input-md required',
                            'id'=>"desk")) !!}
                            {!! $errors->first('desk','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    @endif

                    {{-- Mobile Numbe --}}
                    <div class="form-group has-feedback {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Mobile Number</label>
                        <div class="col-md-7">
                            {!! Form::text('user_phone', $value = null, $attributes = array('class'=>'form-control input-md digits required phone_or_mobile', 'onkeyup' => "mobileNumberValidation(this.id)",
                            'minlength'=>"8", 'placeholder'=>'Mobile Number','id'=>"user_phone")) !!}
                            {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{--Telephone number--}}
                    <div class="form-group {{ $errors->has('user_number') ? 'has-error' : ''}}">
                        <label for="user_number" class="col-md-4 text-left">Telephone Number</label>
                        <div class="col-lg-7">
                            {!! Form::text('user_number','', $attributes = array('class'=>'form-control input-sm',
                            'placeholder'=>'Enter your Telephone Number','id'=>"user_number", 'data-rule-maxlength'=>'16')) !!}
                            {!! $errors->first('user_number','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Email Address --}}
                    <div class="form-group has-feedback {{ $errors->has('user_email') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Email Address</label>
                        <div class="col-md-7">
                            {!! Form::text('user_email', $value = null, $attributes = array('class'=>'form-control input-md email required', 'data-rule-maxlength'=>'40',
                            'placeholder'=>'Enter your Email Address','id'=>"user_email")) !!}
                            {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div><!--/col-md-6-->

                <div class="col-md-6">

                    {{-- Nationality Type --}}
                    <div class="form-group has-feedback {{$errors->has('nationality_type') ? 'has-error': ''}}">
                        {!! Form::label('nationality_type','Nationality Type',['class'=>'text-left col-md-5 required-star', 'id' => 'nationality_type_label']) !!}
                        <div class="col-md-7">
                            <label class="nationality_hover">
                                {!! Form::radio('nationality_type', 'bangladeshi', false, ['class'=>'nationality_type required', 'id' => 'nationality_type_bd']) !!}
                                Bangladeshi
                            </label>
                            &nbsp;&nbsp;
                            <label class="nationality_hover">
                                {!! Form::radio('nationality_type', 'foreign', false, ['class'=>'nationality_type required', 'id' => 'nationality_type_foreign']) !!}
                                Foreign
                            </label>
                        </div>
                    </div>

                    {{-- Identification Type Start --}}
                    <div id="bd_nationality_fields" class="form-group has-feedback {{$errors->has('identity_type_bd') ? 'has-error': ''}} hidden">
                        {!! Form::label('identity_type_bd','Identification Type :',['class'=>'text-left col-md-5 required-star', 'id' => 'identity_type_label']) !!}
                        <div class="col-md-7">
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_bd', 'nid', false, ['class'=>'identity_type required', 'id' => 'identity_type_nid', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                NID
                            </label>
                            &nbsp;&nbsp;
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_bd', 'tin', false, ['class'=>'identity_type required', 'id' => 'identity_type_tin1', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                TIN (Bangladesh)
                            </label>
                        </div>
                        {!! $errors->first('identity_type_bd','<span class="help-block">:message</span>') !!}
                    </div>

                    <div id="foreign_nationality_fields" class="form-group has-feedback {{$errors->has('identity_type_foreign') ? 'has-error': ''}} hidden">
                        {!! Form::label('identity_type_foreign','Identification Type :',['class'=>'text-left col-md-5 required-star', 'id' => 'identity_type_label']) !!}
                        <div class="col-md-7">
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_foreign', 'passport', false, ['class'=>'identity_type required', 'id' => 'identity_type_passport', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                Passport
                            </label>
                            &nbsp;&nbsp;
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_foreign', 'tin', false, ['class'=>'identity_type required', 'id' => 'identity_type_tin2', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                TIN (Bangladesh)
                            </label>
                        </div>
                        {!! $errors->first('identity_type_foreign','<span class="help-block">:message</span>') !!}
                    </div>
                    {{-- Identification Type End --}}

                    {{-- NID Information --}}
                    <div class="form-group has-feedback {{ $errors->has('user_nid') ? 'has-error' : ''}} hidden" id="nid_div">
                        <label  class="col-md-5 text-left required-star">National ID No.</label>
                        <div class="col-md-7">
                            {!! Form::text('user_nid', null, $attributes = array('class'=>'form-control required input-md nid_data',  'data-rule-maxlength'=>'17',
                            'placeholder'=>'Enter the NID number. (if any)', 'id'=>"user_nid")) !!}
                            <small class="text-danger" style="font-size: 9px; font-weight: bold">You need to enter
                                13 or 17 digits NID or 10 digits smart card ID.</small>
                            {!! $errors->first('user_nid','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- TIN Information --}}
                    <div class="form-group has-feedback {{ $errors->has('user_tin') ? 'has-error' : ''}} hidden" id="etin_div">
                        <label  class="col-md-5 text-left required-star">TIN (Bangladesh)</label>
                        <div class="col-md-7">
                            {!! Form::text('user_tin', null, $attributes = array('class'=>'form-control input-md digits etin_data required',  'data-rule-maxlength'=>'20',
                            'placeholder'=>'Enter your TIN number', 'id'=>"user_tin")) !!}
                            {!! $errors->first('user_tin','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Passport Information --}}
                    <div class="hidden" id="passport_div">
                        <fieldset>
                            <legend>Passport Information</legend>

                            {{-- Passport Nationality --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_nationality') ? 'has-error' : ''}}">
                                <label for="passport_nationality" class="col-md-5 text-left required-star">Passport Nationality</label>
                                <div class="col-md-7">
                                    {!! Form::select('passport_nationality', $nationalities, '', $attributes = array('class'=>'form-control passport_data input-sm required',
                                    'placeholder' => 'Select one', 'id'=>"passport_nationality")) !!}
                                    {!! $errors->first('passport_nationality','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Passport Type --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_type') ? 'has-error' : ''}}">
                                <label for="passport_type" class="col-md-5 text-left required-star">Passport Type</label>
                                <div class="col-md-7">
                                    {!! Form::select('passport_type', $passport_types, '', $attributes = array('class'=>'form-control passport_data input-sm required',
                                   'placeholder' => 'Select one', 'id'=>"passport_type")) !!}
                                    {!! $errors->first('passport_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Passport No --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_no') ? 'has-error' : ''}}">
                                <label for="passport_no" class="col-md-5 text-left required-star">Passport No</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_no', null, $attributes = array('class'=>'form-control required alphaNumeric passport_data input-sm', 'data-rule-maxlength'=>'20',
                                 'placeholder'=>'Enter your passport number', 'id'=>"passport_no")) !!}
                                    {!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Surname --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_surname') ? 'has-error' : ''}}">
                                <label for="passport_surname" class="col-md-5 text-left required-star">Surname</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_surname', null, $attributes = array('class'=>'form-control textOnly input-sm passport_data required',  'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your surname', 'id'=>"passport_surname")) !!}
                                    {!! $errors->first('passport_surname','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Given name --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_given_name') ? 'has-error' : ''}}">
                                <label for="passport_given_name" class="col-md-5 text-left required-star">Given Name</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_given_name', null, $attributes = array('class'=>'form-control textOnly passport_data input-sm required',  'data-rule-maxlength'=>'40',
                                   'placeholder'=>'Enter your given name', 'id'=>"passport_given_name")) !!}
                                    {!! $errors->first('passport_given_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Personal No --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_personal_no') ? 'has-error' : ''}}">
                                <label for="passport_personal_no" class="col-md-5 text-left">Personal No</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_personal_no', null, $attributes = array('class'=>'form-control passport_data alphaNumeric input-sm',  'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your personal number', 'id'=>"passport_personal_no")) !!}
                                    {!! $errors->first('passport_personal_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Passport Date of Birth --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_DOB') ? 'has-error' : ''}}">
                                <label for="passport_DOB" class="col-md-5 text-left required-star">Passport Date of Birth</label>
                                <div class="col-md-7">
                                    <div class="passportDP input-group date">
                                        {!! Form::text('passport_DOB', '', ['class'=>'form-control input-sm passport_data required','id'=>'passport_DOB',  'placeholder' => 'Pick from calendar']) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('passport_DOB','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Date of Expire --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_date_of_expire') ? 'has-error' : ''}}">
                                <label for="passport_date_of_expire" class="col-md-5 text-left required-star">Date of Expiry</label>
                                <div class="col-md-7">
                                    <div class="passExpiryDate input-group date">
                                        {!! Form::text('passport_date_of_expire', '', ['class'=>'form-control input-sm passport_data required','id'=>'passport_date_of_expire',  'placeholder' => 'Pick from calendar']) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('passport_date_of_expire','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    {{-- Pasport Information End --}}

                    {{-- Resident of / Counrty --}}
                    <div class="form-group has-feedback {{ $errors->has('country_id') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star">Resident of </label>
                        <div class="col-md-7 resident_of">
                            {!! Form::select('country_id', $countries, null, $attributes = array('class'=>'form-control input-md required', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"country_id")) !!}
                            {!! $errors->first('country_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Nationality --}}
                    <div class="form-group has-feedback {{ $errors->has('nationality') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star"> Nationality </label>
                        <div class="col-md-7 nationality_of">
                            {!! Form::select('nationality', $nationalities, '', $attributes = array('class'=>'form-control input-md required','data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"nationality")) !!}
                            {!! $errors->first('nationality','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{--@if($logged_user_type == '1x101') --}}{{-- For System Admin --}}

                    {{--<div class="form-group hidden" id="userDesk">--}}
                    {{--<label  class="col-md-5 required-star text-left"> User's Desk </label>--}}
                    {{--<div class="col-md-7">--}}
                    {{--{!! Form::select('desk_id', $user_desk,'', $attributes = array('class'=>'form-control','data-rule-maxlength'=>'40',--}}
                    {{--'placeholder' => 'Select One','id'=>"desk_id")) !!}--}}
                    {{--{!! $errors->first('desk_id','<span class="help-block">:message</span>') !!}--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--@endif --}}{{-- logged user is system admin --}}

                    {{-- Division --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('division') ? 'has-error' : ''}}" id="division_div">
                        <label  class="col-md-5 required-star">Division </label>
                        <div class="col-md-7">
                            {!! Form::select('division', $divisions, '', $attributes = array('class'=>'form-control input-md','data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"division")) !!}
                            {!! $errors->first('division','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- District --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('district') ? 'has-error' : ''}}" id="district_div">
                        <label  class="col-md-5 required-star"> District </label>
                        <div class="col-md-7">
                            {!! Form::select('district', [], '', $attributes = array('class'=>'form-control input-md', 'placeholder' => 'Select Division First',
                            'data-rule-maxlength'=>'40','id'=>"district")) !!}
                            {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Police station/Thana --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('police_station_div') ? 'has-error' : ''}}" id="police_station_div">
                        <label class="col-md-5 required-star"> Police Station </label>
                        <div class="col-md-7">
                            {!! Form::select('police_station', [], '', $attributes = array('class'=>'form-control input-md', 'placeholder' => 'Select District First','id'=>"police_station")) !!}
                            {!! $errors->first('police_station','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Post Office--}}
                    <div class="form-group has-feedback hidden {{ $errors->has('house_no') ? 'has-error' : ''}}" id="post_office_div">
                        <label class="col-md-5 required-star"> Post Office</label>
                        <div class="col-md-7">
                            {!! Form::text('post_office', '', $attributes = array('class'=>'form-control input-md', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter post office', 'id'=>"post_office")) !!}
                            {!! $errors->first('post_office','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- State/Division --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('state') ? 'has-error' : ''}}" id="state_div">
                        <label  class="col-md-5 required-star"> State </label>
                        <div class="col-md-7">
                            {!! Form::text('state', '', $attributes = array('class'=>'form-control input-md', 'placeholder' => 'Name of your state/division',
                            'data-rule-maxlength'=>'40', 'id'=>"state")) !!}
                            {!! $errors->first('state','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Province/City --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('province') ? 'has-error' : ''}}" id="province_div">
                        <label  class="col-md-5 required-star"> Province/ City </label>
                        <div class="col-md-7">
                            {!! Form::text('province', '', $attributes = array('class'=>'form-control', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter your Province/ City', 'id'=>"province")) !!}
                            {!! $errors->first('province','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Address/Road no. --}}
                    <div class="form-group has-feedback {{ $errors->has('road_no') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star"> Address </label>
                        <div class="col-md-7">
                            {!! Form::text('road_no', '', $attributes = array('class'=>'form-control input-md bnEng required', 'data-rule-maxlength'=>'100',
                            'placeholder' => 'Enter Road / Street Name / No.', 'id'=>"road_no")) !!}
                            {!! $errors->first('road_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Post code --}}
                    <div class="form-group has-feedback {{ $errors->has('post_code') ? 'has-error' : ''}}">
                        <label  class="col-md-5 text-left required-star"> Post Code </label>
                        <div class="col-md-7">
                            {!! Form::text('post_code', '', $attributes = array('class'=>'form-control required input-md', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter your Post Code ', 'id'=>"post_code")) !!}
                            {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>

            </div> <!--/panel-body-->
            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{url('users/lists')}}" class="btn btn-default btn-sm"><i class="fa fa-times"></i> <b>Close</b></a>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('user','A'))
                        <button type="submit" class="btn btn-block btn-sm btn-primary"><b>Create user</b></button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
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

        $(function () {
            var _token = $('input[name="_token"]').val();
            $("#create_user_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        $(document).ready(function () {
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.userDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110)
            });

            $('.passportDP').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/' + (yyyy - 110)
            });

            $(".passExpiryDate").datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: '01/01/' + (yyyy + 20),
                minDate: '01/01/' + (yyyy - 10)
            });
        });



        $('.nationality_type').click(function (e) {
            var nationality_type = e.target.value;

            if (nationality_type == 'bangladeshi') {
                $('#bd_nationality_fields').removeClass('hidden');
                $('#foreign_nationality_fields').addClass('hidden');
                $('input[name="identity_type_bd"]').prop('checked', false);
                $('input[name="identity_type_foreign"]').prop('checked', false);

                $(".resident_of option[value!='18']").hide();
                $(".resident_of option[value='18']").show();
                $(".resident_of select").val('18');

                $(".nationality_of option[value!='18']").hide();
                $(".nationality_of option[value='18']").show();
                $(".nationality_of select").val('18');

                $('#division_div').removeClass('hidden');
                $('#division').addClass('required');
                $('#district_div').removeClass('hidden');
                $('#district').addClass('required');
                $('#police_station_div').removeClass('hidden');
                $('#police_station').addClass('required');
                $('#post_office_div').removeClass('hidden');
                $('#post_office').addClass('required');
                $('#state_div').addClass('hidden');
                $('#state').removeClass('required');
                $('#state').val('');
                $('#province_div').addClass('hidden');
                $('#province').removeClass('required');
                $('#province').val('');

            }else if (nationality_type == 'foreign'){
                $('#bd_nationality_fields').addClass('hidden');
                $('#foreign_nationality_fields').removeClass('hidden');
                $('input[name="identity_type_bd"]').prop('checked', false);
                $('input[name="identity_type_foreign"]').prop('checked', false);


                $(".resident_of option[value!='18']").show();
                $(".resident_of option[value='18']").hide();
                $(".resident_of select").val('');

                $(".nationality_of option[value!='18']").show();
                $(".nationality_of option[value='18']").hide();
                $(".nationality_of select").val('');

                $('#state_div').removeClass('hidden');
                $('#state').addClass('required');
                $('#province_div').removeClass('hidden');
                $('#province').addClass('required');
                $('#division_div').addClass('hidden');
                $('#division').removeClass('required');
                $('#division').val('');
                $('#district_div').addClass('hidden');
                $('#district').removeClass('required');
                $('#district').val('');
                $('#police_station_div').addClass('hidden');
                $('#police_station').removeClass('required');
                $('#police_station').val('');
                $('#post_office_div').addClass('hidden');
                $('#post_office').removeClass('required');
                $('#post_office').val('');

            } else{
                $('#bd_nationality_fields').addClass('hidden');
                $('#foreign_nationality_fields').addClass('hidden');
                $('input[name="identity_type_bd"]').prop('checked', false);
                $('input[name="identity_type_foreign"]').prop('checked', false);
            }

            // Trigger on user identity
            setUserIdentity();
        });

        function setUserIdentity(identity_type) {
            if (identity_type === 'nid') {
                $('#nid_div').removeClass('hidden');
                $('#etin_div').addClass('hidden');
                $('.etin_data').val('');
                $('#passport_div').addClass('hidden');
                $('.passport_data').val('');
            } else if (identity_type === 'tin') {
                $('#etin_div').removeClass('hidden');
                $('#nid_div').addClass('hidden');
                $('.nid_data').val('');
                $('#passport_div').addClass('hidden');
                $('.passport_data').val('');
            } else if (identity_type === 'passport') {
                $('#passport_div').removeClass('hidden');
                $('#etin_div').addClass('hidden');
                $('.etin_data').val('');
                $('#nid_div').addClass('hidden');
                $('.nid_data').val('');
            } else {
                $('#passport_div').addClass('hidden');
                $('#etin_div').addClass('hidden');
                $('#nid_div').addClass('hidden');
                $('.nid_data').val('');
                $('.etin_data').val('');
                $('.passport_data').val('');
            }
        };

        $(document).ready(function () {
            $("#division").change(function () {
                var divisionId = $('#division').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-district-by-division",
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
                        $("#district").html(option);
                        $(self).next().hide();
                    }
                });
            });

            $("#district").change(function () {
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
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#police_station").html(option);
                        $(self).next().hide();
                    }
                });
            });

            $('#user_type').change(function () {
                var type = $(this).val();
                if (type == '4x404' || type == '6x606') { // 4x404 is desk user and 6x606 is Bangladesh Bank user
                    $('#userDesk').removeClass('hidden');
                    $('#desk_id').addClass('required');
                    $('#department_div').removeClass('hidden');
                    $('#department').addClass('required');
                    $('#desk_div').removeClass('hidden');
                    $('#desk').addClass('required');
                }
                else {
                    $('#userDesk').addClass('hidden');
                    $('#desk_id').removeClass('required');
                    $('#department_div').addClass('hidden');
                    $('#department').removeClass('required');
                    $('#desk_div').addClass('hidden');
                    $('#desk').removeClass('required');
                }
            });
            $('#user_type').trigger('change');

            $("#user_type").change(function () {
                var user_type = $(this).val();
                if (user_type == "5x505") {
                    $(".companyUserType").show();
                } else {
                    $(".companyUserType").hide();
                }
            });

            // nationality
            $("#nationality").change(function (e) {
                updateMobileCountryCode($(this));
            });
            $("#nationality").trigger('change');

            // country dialing code function
            function updateMobileCountryCode(obj){
                var dialing_code = "", $this = obj;

                if($this.val() != 0){
                    dialing_code = $this.find('option:selected').attr('rel');
                }

                $("#user_phone").val(dialing_code);
            }
        });
    </script>
    <script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
    <script>
        $(function () {
            $("#user_phone").intlTelInput({
                hiddenInput: "user_phone",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true
            });
        });
        function mobileNumberValidation(id) {
            var inputValue = document.getElementById(id).value;
            var pattern = /^(01|008801|8801|\+8801)[3-9]{1}\d{8}$/;
            if (pattern.test(inputValue)) {
                document.getElementById(id).classList.remove('error');
                return true;
            } else {
                if(inputValue.length > 11) {
                    Swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please fill the valid phone number.'
                    });
                    document.getElementById(id).value = inputValue.slice(0, -1);
                }
                document.getElementById(id).classList.add('error');
                return false;
            }
        }
    </script>

@endsection
