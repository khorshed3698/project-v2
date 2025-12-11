@extends('layouts.admin')

@section('page_heading',trans('messages.profile'))

@section('style')
    <link rel="stylesheet" href="{{ asset("build/css/intlTelInput_v16.0.8.css") }}" />

    <style>
        .intl-tel-input .country-list {
            z-index: 5;
        }
        .iti {
            width: 100%;
        }
        .iti__country-list {
            z-index: 10;
        }
    </style>
@endsection

@section("content")
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');

    $user_type_explode = explode('x', $users->user_type);
    $random_number = str_random(30);
    ?>

    <div class="col-md-12">
        @include('message.message')
    </div>

    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong><i class="fa fa-user" aria-hidden="true"></i> Edit User</strong></h5>
            </div>
            {!! Form::open(array('url' => '/users/update/'.Encryption::encodeId($users->id),'method' => 'patch', 'class' => 'form-horizontal',
                    'id'=> 'user_edit_form')) !!}

            {!! Form::hidden('selected_file', '', array('id' => 'selected_file')) !!}
            {!! Form::hidden('validateFieldName', '', array('id' => 'validateFieldName')) !!}
            {!! Form::hidden('isRequired', '', array('id' => 'isRequired')) !!}
            {!! Form::hidden('TOKEN_NO', $random_number) !!}

            <div class="panel-body">
                <div class="col-md-6">
                    <div class="form-group has-feedback {{ $errors->has('user_first_name') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left required-star">First Name</label>

                        <div class="col-md-7">
                            {!! Form::text('user_first_name', $value = $users->user_first_name, $attributes = array('class'=>'form-control',
                            'id'=>"user_first_name", 'data-rule-maxlength'=>'50')) !!}
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if($errors->first('user_first_name'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_first_name','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('user_middle_name') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left">Middle Name</label>

                        <div class="col-md-7">
                            {!! Form::text('user_middle_name', $value = $users->user_middle_name, $attributes = array('class'=>'form-control',
                            'id'=>"user_middle_name", 'data-rule-maxlength'=>'50')) !!}
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if($errors->first('user_middle_name'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_middle_name','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback {{ $errors->has('user_last_name') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left">Last Name</label>

                        <div class="col-md-7">
                            {!! Form::text('user_last_name', $value = $users->user_last_name, $attributes = array('class'=>'form-control',
                            'id'=>"user_last_name", 'data-rule-maxlength'=>'50')) !!}
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @if($errors->first('user_last_name'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_last_name','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>
                    @if(isset($user_type_explode[0]) && $user_type_explode[0]=='11')
                        <div class="form-group has-feedback">
                            <label class="col-md-4 text-left">Bank Name</label>

                            <div class="col-md-7">
                                {{ $bank_name }}
                            </div>
                        </div>


                        <div class="form-group has-feedback {{ $errors->has('bank_branch_id') ? 'has-error' : ''}}">
                            <label class="col-md-4 text-left">Bank Branch</label>

                            <div class="col-md-7">
                                {!! Form::select('bank_branch_id', $branch_list, $users->bank_branch_id, array('class'=>'form-control required',
                                'placeholder' => 'Select One', 'id'=>"bank_branch_id")) !!}
                                @if($errors->first('bank_branch_id'))
                                    <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('bank_branch_id','') }}</em>
                        </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- designation --}}
                    <div class="form-group has-feedback {{ $errors->has('designation') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left required-star" for="designation">Designation</label>
                        <div class="col-md-7">
                            <div class="input-group">
                                {!! Form::text('designation', $users->designation, $attributes = array('class'=>'form-control input-md required', 'data-rule-maxlength'=>'40',
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
                                {!! Form::radio('user_gender', 'Male', ($users->user_gender == 'Male' ? true :false), ['class'=>'required', 'id' => 'male']) !!} Male
                            </label>
                            &nbsp;&nbsp;
                            <label class="identity_hover">
                                {!! Form::radio('user_gender', 'Female', ($users->user_gender == 'Female' ? true :false), ['class'=>'required', 'id' => 'female']) !!} Female
                            </label>
                        </div>
                    </div>

                    {{-- User Type --}}
                    <div class="form-group has-feedback {{ $errors->has('user_type') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left required-star">User Type</label>

                        <div class="col-md-7">
                            {!! Form::select('user_type', $user_types, $users->user_type, $attributes = array('class'=>'form-control required',
                            'id'=>"user_type",'readonly' => "readonly")) !!}
                            @if($errors->first('user_type'))
                                <span class="control-label">
                            <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('user_type','') }}</em>
                        </span>
                            @endif
                        </div>
                    </div>

                    {{-- Mobile Number --}}
                    <div class="form-group has-feedback {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Mobile Number</label>
                        <div class="col-md-7">
                            {!! Form::text('user_phone', $users->user_phone, $attributes = array('class'=>'form-control input-md  required phone_or_mobile', 'onkeyup' => "mobileNumberValidation(this.id)",'placeholder'=>'Mobile Number','id'=>"user_phone")) !!}
                            {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{--Telephone number--}}
                    <div class="form-group {{ $errors->has('user_number') ? 'has-error' : ''}}">
                        <label for="user_number" class="col-md-4 text-left">Telephone Number</label>
                        <div class="col-lg-7">
                            {!! Form::text('user_number', $users->user_number, $attributes = array('class'=>'form-control input-sm',
                            'placeholder'=>'Enter your Telephone Number','id'=>"user_number", 'data-rule-maxlength'=>'16')) !!}
                            {!! $errors->first('user_number','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Date of birth --}}
                    <div class="form-group has-feedback {{ $errors->has('user_DOB') ? 'has-error' : ''}}">
                        <label class="col-md-4 text-left">{!! trans('messages.dob') !!}</label>

                        <div class="col-md-7">
                            <div class="userDP input-group date">
                                {!! Form::text('user_DOB', (date('d-M-Y', strtotime($users->user_DOB))), ['class'=>'form-control input-md required', 'placeholder' => 'Pick from calender','id' => 'user_DOB']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Email Address --}}
                    <div class="form-group has-feedback {{ $errors->has('user_email') ? 'has-error' : ''}}">
                        <label  class="col-md-4 required-star">Email Address</label>
                        <div class="col-md-7">
                            {!! Form::text('user_email', $users->user_email, $attributes = array('class'=>'form-control input-md email required', 'data-rule-maxlength'=>'40', 'placeholder'=>'Enter your Email Address','id'=>'user_email', 'readonly' => 'readonly')) !!}
                            {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>

                {{--Right Side--}}
                <div class="col-md-6">

                    {{-- Nationality Type --}}
                    <div class="form-group has-feedback {{$errors->has('nationality_type') ? 'has-error': ''}}">
                        {!! Form::label('nationality_type','Nationality Type',['class'=>'text-left col-md-5 required-star', 'id' => 'nationality_type_label']) !!}
                        <div class="col-md-7">
                            <label class="nationality_hover">
                                {!! Form::radio('nationality_type', 'bangladeshi',  ($users->nationality_type == 'bangladeshi' ? true :false), ['class'=>'nationality_type required', 'id' => 'nationality_type_bd']) !!}
                                Bangladeshi
                            </label>
                            &nbsp;&nbsp;
                            <label class="nationality_hover">
                                {!! Form::radio('nationality_type', 'foreign',  ($users->nationality_type == 'foreign' ? true :false), ['class'=>'nationality_type required', 'id' => 'nationality_type_foreign']) !!}
                                Foreign
                            </label>
                        </div>
                    </div>

                    {{-- Identification Type Start --}}
                    <div id="bd_nationality_fields" class="form-group has-feedback {{$errors->has('identity_type_bd') ? 'has-error': ''}} hidden">
                        {!! Form::label('identity_type_bd','Identification Type :',['class'=>'text-left col-md-5 required-star', 'id' => 'identity_type_label']) !!}
                        <div class="col-md-7">
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_bd', 'nid', ($users->identity_type == 'nid' ? true :false), ['class'=>'identity_type required', 'id' => 'identity_type_nid', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                NID
                            </label>
                            &nbsp;&nbsp;
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_bd', 'tin', ($users->identity_type == 'tin' ? true :false), ['class'=>'identity_type required', 'id' => 'identity_type_tin1', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                TIN (Bangladesh)
                            </label>
                        </div>
                        {!! $errors->first('identity_type_bd','<span class="help-block">:message</span>') !!}
                    </div>

                    <div id="foreign_nationality_fields" class="form-group has-feedback {{$errors->has('identity_type_foreign') ? 'has-error': ''}} hidden">
                        {!! Form::label('identity_type_foreign','Identification Type :',['class'=>'text-left col-md-5 required-star', 'id' => 'identity_type_label']) !!}
                        <div class="col-md-7">
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_foreign', 'passport', ($users->identity_type == 'passport' ? true :false), ['class'=>'identity_type required', 'id' => 'identity_type_passport', 'onclick' => 'setUserIdentity(this.value)']) !!}
                                Passport
                            </label>
                            &nbsp;&nbsp;
                            <label class="identity_hover">
                                {!! Form::radio('identity_type_foreign', 'tin', ($users->identity_type == 'tin' ? true :false), ['class'=>'identity_type required', 'id' => 'identity_type_tin2', 'onclick' => 'setUserIdentity(this.value)']) !!}
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
                            {!! Form::text('user_nid', $users->user_nid, $attributes = array('class'=>'form-control required input-md nid_data',  'data-rule-maxlength'=>'17',
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
                            {!! Form::text('user_tin', $users->user_tin, $attributes = array('class'=>'form-control input-md digits etin_data required',  'data-rule-maxlength'=>'20',
                            'placeholder'=>'Enter your TIN number', 'id'=>"user_tin")) !!}
                            {!! $errors->first('user_tin','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Pasport Information Start --}}
                    <div class="hidden" id="passport_div">
                        <fieldset>
                            <legend>Passport Information</legend>

                            {{-- Passport Nationality --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_nationality') ? 'has-error' : ''}}">
                                <label for="passport_nationality" class="col-md-5 text-left required-star">Passport Nationality</label>
                                <div class="col-md-7">
                                    {!! Form::select('passport_nationality', $nationalities, $users->passport_nationality_id, $attributes = array('class'=>'form-control passport_data input-sm required',
                                    'placeholder' => 'Select one', 'id'=>"passport_nationality")) !!}
                                    {!! $errors->first('passport_nationality','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Passport Type --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_type') ? 'has-error' : ''}}">
                                <label for="passport_type" class="col-md-5 text-left required-star">Passport Type</label>
                                <div class="col-md-7">
                                    {!! Form::select('passport_type', $passport_types, $users->passport_type, $attributes = array('class'=>'form-control passport_data input-sm required',
                                   'placeholder' => 'Select one', 'id'=>"passport_type")) !!}
                                    {!! $errors->first('passport_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Passport No --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_no') ? 'has-error' : ''}}">
                                <label for="passport_no" class="col-md-5 text-left required-star">Passport No</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_no', $users->passport_no, $attributes = array('class'=>'form-control required alphaNumeric passport_data input-sm', 'data-rule-maxlength'=>'20',
                                 'placeholder'=>'Enter your passport number', 'id'=>"passport_no")) !!}
                                    {!! $errors->first('passport_no','<span class="help-block">:message</span>') !!}
                                    <span class="text-danger pss-error"></span>
                                </div>
                            </div>

                            {{-- Passport Surname --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_surname') ? 'has-error' : ''}}">
                                <label for="passport_surname" class="col-md-5 text-left required-star">Surname</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_surname', $users->passport_surname, $attributes = array('class'=>'form-control textOnly input-sm passport_data required',  'data-rule-maxlength'=>'40',
                                'placeholder'=>'Enter your surname', 'id'=>"passport_surname")) !!}
                                    {!! $errors->first('passport_surname','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{--Passport Given name --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_given_name') ? 'has-error' : ''}}">
                                <label for="passport_given_name" class="col-md-5 text-left required-star">Given Name</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_given_name', $users->passport_given_name, $attributes = array('class'=>'form-control textOnly passport_data input-sm required',  'data-rule-maxlength'=>'40',
                                   'placeholder'=>'Enter your given name', 'id'=>"passport_given_name")) !!}
                                    {!! $errors->first('passport_given_name','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Personal No --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_personal_no') ? 'has-error' : ''}}">
                                <label for="passport_personal_no" class="col-md-5 text-left">Personal No</label>
                                <div class="col-md-7">
                                    {!! Form::text('passport_personal_no', $users->passport_personal_no, $attributes = array('class'=>'form-control alphaNumeric passport_data input-sm',
                                'placeholder'=>'Enter your personal number', 'id'=>"passport_personal_no")) !!}
                                    {!! $errors->first('passport_personal_no','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Passport Date of Birth --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_DOB') ? 'has-error' : ''}}">
                                <label for="passport_DOB" class="col-md-5 text-left required-star">Date of Expiry</label>
                                <div class="col-md-7">
                                    <div class="passportDP input-group date">
                                        {!! Form::text('passport_DOB', (date('d-M-Y', strtotime($users->passport_DOB))), ['class'=>'form-control input-sm passport_data required','id'=>'passport_DOB',  'placeholder' => 'Pick from calendar']) !!}
                                        <span class="input-group-addon">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    {!! $errors->first('passport_DOB','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            {{-- Date of Expire --}}
                            <div class="form-group has-feedback {{ $errors->has('passport_DOB') ? 'has-error' : ''}}">
                                <label for="passport_date_of_expire" class="col-md-5 text-left required-star">Date of Expiry</label>
                                <div class="col-md-7">
                                    <div class="passExpiryDate input-group date">
                                        {!! Form::text('passport_date_of_expire', (date('d-M-Y', strtotime($users->passport_date_of_expire))), ['class'=>'form-control input-sm passport_data required','id'=>'passport_date_of_expire',  'placeholder' => 'Pick from calendar']) !!}
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
                            {!! Form::select('country_id', $countries, $users->country_id, $attributes = array('class'=>'form-control input-md required', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"country_id")) !!}
                            {!! $errors->first('country_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Nationality --}}
                    <div class="form-group has-feedback {{ $errors->has('nationality') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star"> Nationality </label>
                        <div class="col-md-7 nationality_of">
                            {!! Form::select('nationality', $nationalities, $users->nationality_id, $attributes = array('class'=>'form-control input-md required','data-rule-maxlength'=>'40',
                            'placeholder' => 'Select One', 'id'=>"nationality")) !!}
                            {!! $errors->first('nationality','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Division --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('division') ? 'has-error' : ''}}" id="division_div">
                        <label  class="col-md-5 required-star">Division </label>
                        <div class="col-md-7">
                            {!! Form::select('division', $divisions, (!empty($users->division) ? $users->division : null), ['class' => 'form-control', 'id' => 'division', 'onchange'=>"getDistrictByDivisionId('division', this.value, 'district',".(!empty($users->district) ? $users->district:'').")"]) !!}
                            {!! $errors->first('division','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- District --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('district') ? 'has-error' : ''}}" id="district_div">
                        <label  class="col-md-5 required-star"> District </label>
                        <div class="col-md-7">
                            {!! Form::select('district', $districts, (!empty($users->district) ? $users->district : null), ['class' => 'form-control', 'placeholder' => 'Select division first', 'id' => 'district', 'onchange'=>"getThanaByDistrictId('district', this.value, 'police_station', ".(!empty($users->thana) ? $users->thana : '').")"]) !!}
                            {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Police station/Thana --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('police_station_div') ? 'has-error' : ''}}" id="police_station_div">
                        <label  class="col-md-5 required-star"> Police Station </label>
                        <div class="col-md-7">
                            {!! Form::select('police_station', [], $users->thana, $attributes = array('class'=>'form-control input-md', 'placeholder' => 'Select District First', 'id'=>"police_station")) !!}
                            {!! $errors->first('police_station','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Post Office--}}
                    <div class="form-group has-feedback hidden {{ $errors->has('house_no') ? 'has-error' : ''}}" id="post_office_div">
                        <label class="col-md-5 required-star"> Post Office</label>
                        <div class="col-md-7">
                            {!! Form::text('post_office', $users->post_office, $attributes = array('class'=>'form-control input-md', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter post office', 'id'=>"post_office")) !!}
                            {!! $errors->first('post_office','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- State/Division --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('state') ? 'has-error' : ''}}" id="state_div">
                        <label  class="col-md-5 required-star"> State </label>
                        <div class="col-md-7">
                            {!! Form::text('state', $value = $users->state, $attributes = array('class'=>'form-control input-md', 'placeholder' => 'Name of your state/ division',
                            'data-rule-maxlength'=>'40', 'id'=>"state")) !!}
                        </div>
                    </div>

                    {{-- Province/City --}}
                    <div class="form-group has-feedback hidden {{ $errors->has('province') ? 'has-error' : ''}}" id="province_div">
                        <label  class="col-md-5 required-star"> Province/ City</label>
                        <div class="col-md-7">
                            {!! Form::text('province', $users->province, $attributes = array('class'=>'form-control', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter your Province/ City', 'id'=>"province")) !!}
                            {!! $errors->first('province','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Address/Road no. --}}
                    <div class="form-group has-feedback {{ $errors->has('road_no') ? 'has-error' : ''}}">
                        <label  class="col-md-5 required-star"> Address </label>
                        <div class="col-md-7">
                            {!! Form::text('road_no', $users->road_no, $attributes = array('class'=>'form-control input-md bnEng required', 'data-rule-maxlength'=>'100',
                            'placeholder' => 'Enter Road / Street Name / No.', 'id'=>"road_no")) !!}
                            {!! $errors->first('road_no','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    {{-- Post code --}}
                    <div class="form-group has-feedback {{ $errors->has('post_code') ? 'has-error' : ''}}">
                        <label  class="col-md-5 text-left required-star"> Post Code </label>
                        <div class="col-md-7">
                            {!! Form::text('post_code', $users->post_code, $attributes = array('class'=>'form-control required input-md', 'data-rule-maxlength'=>'40',
                            'placeholder' => 'Enter your Post Code ', 'id'=>"post_code")) !!}
                            {!! $errors->first('post_code','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                </div>

            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-2">
                        <div class="pull-left">
                            <a href="/users/lists" class="btn btn-default btn-sm"><i class="fa fa-times"></i><b> Close</b></a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <p style="text-align: center;">{!! App\Libraries\CommonFunction::showAuditLog($users->updated_at, $users->updated_by) !!}</p>
                    </div>
                    <div class="col-md-2">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-sm btn-primary" id='submit_btn'><b>Update info</b>
                            </button>
                        </div>
                    </div>
                    
                    
                    
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

        $(document).ready(function () {
            $("#user_edit_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        function setUserIdentity(identity_type) {

            if (identity_type === 'nid') {

                $("#identity_type_nid").prop("checked", true);
                $("#identity_type_tin1").prop("checked", false);
                $("#identity_type_passport").prop("checked", false);
                $("#identity_type_tin2").prop("checked", false);

                $('#nid_div').removeClass('hidden');
                $('#etin_div').addClass('hidden');
                $('#passport_div').addClass('hidden');

            } else if (identity_type === 'tin') {

                $("#identity_type_nid").prop("checked", false);
                $("#identity_type_tin1").prop("checked", true);
                $("#identity_type_passport").prop("checked", false);
                $("#identity_type_tin2").prop("checked", true);

                $('#etin_div').removeClass('hidden');
                $('#nid_div').addClass('hidden');
                $('#passport_div').addClass('hidden');

            } else if (identity_type === 'passport') {

                $("#identity_type_nid").prop("checked", false);
                $("#identity_type_tin1").prop("checked", false);
                $("#identity_type_passport").prop("checked", true);
                $("#identity_type_tin2").prop("checked", false);

                $('#passport_div').removeClass('hidden');
                $('#etin_div').addClass('hidden');
                $('#nid_div').addClass('hidden');

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

            function checkNationalityType(nationality_type){

                if (nationality_type == 'bangladeshi') {
                    $('#bd_nationality_fields').removeClass('hidden');
                    $('#foreign_nationality_fields').addClass('hidden');
                    $('input[name="identity_type_bd"]').prop('checked', false);
                    $('input[name="identity_type_foreign"]').prop('checked', false);

                    $('#passport_div').addClass('hidden');
                    $('#etin_div').addClass('hidden');
                    $('#nid_div').addClass('hidden');

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
                    $('#province_div').addClass('hidden');
                    $('#province').removeClass('required');

                }else if (nationality_type == 'foreign'){
                    $('#bd_nationality_fields').addClass('hidden');
                    $('#foreign_nationality_fields').removeClass('hidden');
                    $('input[name="identity_type_bd"]').prop('checked', false);
                    $('input[name="identity_type_foreign"]').prop('checked', false);

                    $('#passport_div').addClass('hidden');
                    $('#etin_div').addClass('hidden');
                    $('#nid_div').addClass('hidden');

                    $(".resident_of option[value!='18']").show();
                    $(".resident_of option[value='18']").hide();
                    //$(".resident_of select").val('');

                    $(".nationality_of option[value!='18']").show();
                    $(".nationality_of option[value='18']").hide();
                    //$(".nationality_of select").val('');

                    $('#state_div').removeClass('hidden');
                    $('#state').addClass('required');
                    $('#province_div').removeClass('hidden');
                    $('#province').addClass('required');
                    $('#division_div').addClass('hidden');
                    $('#division').removeClass('required');
                    $('#district_div').addClass('hidden');
                    $('#district').removeClass('required');
                    $('#police_station_div').addClass('hidden');
                    $('#police_station').removeClass('required');
                    $('#post_office_div').addClass('hidden');
                    $('#post_office').removeClass('required');

                } else{
                    $('#bd_nationality_fields').addClass('hidden');
                    $('#foreign_nationality_fields').addClass('hidden');
                    $('input[name="identity_type_bd"]').prop('checked', false);
                    $('input[name="identity_type_foreign"]').prop('checked', false);
                }
            }

            // Trigger on nationality identity
            var nationalityType = "<?php echo $users->nationality_type ?>"
            checkNationalityType(nationalityType);

            $('.nationality_type').click(function (e) {
                var nationality_type = e.target.value;
                checkNationalityType(nationality_type);
            });

            // Trigger on identity identity
            var identityType = "<?php echo $users->identity_type ?>"
            setUserIdentity(identityType);

            $('.identity_type').click(function (e) {
                var identityType = e.target.value;
                setUserIdentity(identityType);
            });

            $("#division").trigger('change');
            $("#district").trigger('change');

        });

        $("#code").blur(function () {
            var code = $(this).val().trim();
            if (code.length > 0 && code.length < 12) {
                $('.code-error').html('');
                $('#submit_btn').attr("disabled", false);
            } else {
                $('.code-error').html('Code number should be at least 1 character to maximum  11 characters!');
                $('#submit_btn').attr("disabled", true);
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
@endsection <!--- footer-script--->