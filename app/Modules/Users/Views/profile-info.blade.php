@extends('layouts.admin')

@section('page_heading',trans('messages.profile'))

@section('style')
    <style type="text/css">

        .picture-container {
            position: relative;
            width: 500px;
            height: 300px;
            margin: 20px auto;
            border: 10px solid #fff;
            box-shadow: 0 5px 5px #000;
        }

        .picture {
            display: block;
            width: 100%;
            height: 300px;
        }

        .face {
            position: absolute;
            border: 2px solid green;
        }

        .iti {
            width: 100%;
        }

        .image-upload figure figcaption {
            position: absolute;
            bottom: 0;
            color: #fff;
            width: 100%;
            padding-left: 9px;
            padding-bottom: 5px;
            text-shadow: 0 0 10px #000;
        }

        .numberCheck{
            position: absolute;
            right: -50px;
            line-height: 2;
        }
    </style>
@endsection

{{--Intel input--}}
{{--<link rel="stylesheet" href="{{ asset("build/css/intlTelInput_v16.0.8.css") }}"/>--}}


@section('content')

    @if(Session::has('checkProfile'))

        <div class="col-sm-12">
            <div class="alert alert-danger">
                <strong>Dear user,</strong><br><br>
                <p>We noticed that your profile setting does not complete yet 100%.<br/>
                    Update your <strong>User name,
                        Profile Image, Designation, {{ Auth::user()->user_type == '4x404' ? ' Signature,' : '' }} and other useful information</strong>.
                    You can not apply any type of registration without proper informational profile.
                    <br><br>Thanks<br> {!! env('PROJECT_NAME') !!}</p>
            </div>
        </div>
    @endif

    <div class="col-md-12">
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i>{{ Session::get('success') }}
            </div>
        @endif
        @if(Session::has('message'))
            <div class="alert alert-warning">
                {{session('message')}}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-warning">
                {{ Session::get('error') }}

            </div>
        @endif
        {{--@if (count($errors) > 0)--}}
        {{--<div class="alert alert-danger">--}}
        {{--<ul>--}}
        {{--@foreach ($errors->all() as $error)--}}
        {{--<li>{{ $error }}</li>--}}
        {{--@endforeach--}}
        {{--</ul>--}}
        {{--</div>--}}
        {{--@endif--}}
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <div class="panel with-nav-tabs panel-info">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab"
                                              aria-expanded="false"><strong>Profile</strong></a></li>
                        {{--                @if($users->social_login != 1)--}}
                        <li>
                            {{--<a href="#tab_2" data-id="{{$users->social_login}}" data-toggle="tab" class="checkGoogleLogin" aria-expanded="false"><strong>Change Password</strong></a>--}}
                            <a target="_blank" rel="noopener"
                               href="{{ url( config('app.osspid_base_url').'/user/profile-setting#change_password') }}"><strong>Change
                                    Password</strong></a>
                        </li>
                        {{--@endif--}}
                        @if(Auth::user()->user_type != '1x101' &&  Auth::user()->user_type != '5x505')
                            <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Delegation</a></li>
                        @endif

                        <li class=""><a href="#tab_5" id="accessLog" data-toggle="tab" aria-expanded="false"><b>Access Log</b></a></li>
                        <li class=""><a href="#tab_6" id="accessLogFailed" data-toggle="tab" aria-expanded="false"><b>Access Log
                                    Failed</b></a></li>
                        <li class=""><a href="#tab_7" id="50Activities" data-toggle="tab" aria-expanded="false"><b>Last 50 Action</b></a>
                        </li>
                        @if(in_array(Auth::user()->user_type,['1x101', '15x151', '2x202', '1x102']))
                            <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false"
                                            class="server_date_time"><b>Server Time </b></a></li>
                        @endif
                        {{--<li class=""><a href="#tab_9" data-toggle="tab" aria-expanded="false" class="notifications"><b>Notifications</b></a></li>--}}
                    </ul>
                </div>

                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="">
                                {!! Form::open(array('url' => '/users/profile_update','method' =>'patch','id'=>'update_form', 'class' => 'form-horizontal',
                                            'enctype'=>'multipart/form-data')) !!}
                                {{--Left Side--}}
                                <div class="col-md-6 col-sm-6">
                                    <fieldset>
                                        <legend class="d-none">Profile</legend>
                                        {!! Form::hidden('Uid', $id) !!}
                                        <div class="row">
                                            <div class="progress hidden pull-right" id="upload_progress"
                                                 style="width: 50%;">
                                                <div class="progress-bar progress-bar-striped active" role="progressbar"
                                                     aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
                                                     style="width: 100%">
                                                </div>
                                            </div>
                                        </div>

                                        @if($users->user_status == "rejected")
                                            <div class="form-group has-feedback {{ $errors->has('group_id') ? 'has-error' : ''}}">
                                                <label class="col-lg-4 text-left">Agency</label>
                                                <div class="col-lg-8">
                                                    {!! Form::text('agency_id', $value = null, $attributes = array('class'=>'form-control bnEng required agency',
                                                    'placeholder'=>'Enter the Name of the Agency','id'=>"group_name",'onblur'=>'checkAutoComplete(this,"agency")')) !!}
                                                    {!! Form::hidden('agency_id','',array('class'=>'group_name_hidden','id'=>'group_id')) !!}
                                                    @if($errors->first('group_id'))
                                                        <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('group_id','') }}</em>
                                                    </span>
                                                    @endif
                                                    <p class="empty-message"></p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 text-left">User Type</label>
                                                <div class="col-lg-8">
                                                    {{ $user_type_info->type_name }}
                                                </div>
                                            </div>
                                            @if($users->desk_id = '0')
                                                <div class="form-group has-feedback">
                                                    <label class="col-lg-4 text-left">User Desk Name</label>
                                                    <div class="col-lg-7">
                                                        {{$user_desk->desk_name}}
                                                    </div>
                                                </div>
                                            @endif

                                        @endif

                                        <div class="form-group has-feedback">
                                            <label class="col-lg-4 text-left">Email Address</label>
                                            <div class="col-lg-8">
                                                {{ $users->user_email }}
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('user_first_name') ? 'has-error' : ''}}">
                                            <label class="col-lg-4 text-left required-star">User’s first name</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('user_first_name',$users->user_first_name, $attributes = array('class'=>'form-control required input-sm',
                                                'placeholder'=>'Enter your Name','id'=>"user_first_name", 'data-rule-maxlength'=>'50')) !!}
                                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                                {!! $errors->first('user_first_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('user_middle_name') ? 'has-error' : ''}}">
                                            <label class="col-lg-4 text-left">User’s middle name</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('user_middle_name',$users->user_middle_name, $attributes = array('class'=>'form-control input-sm',
                                                'placeholder'=>'Enter your Name','id'=>"user_middle_name", 'data-rule-maxlength'=>'50')) !!}
                                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                                {!! $errors->first('user_middle_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group has-feedback {{ $errors->has('user_last_name') ? 'has-error' : ''}}">
                                            <label class="col-lg-4 text-left">User’s last name</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('user_last_name',$users->user_last_name, $attributes = array('class'=>'form-control input-sm',
                                                'placeholder'=>'Enter your Name','id'=>"user_last_name", 'data-rule-maxlength'=>'50')) !!}
                                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                                {!! $errors->first('user_last_name','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback {{ $errors->has('designation') ? 'has-error' : '' }}">
                                            <label class="col-lg-4 text-left required-star">Designation</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('designation',$users->designation, ['class'=>'form-control input-sm required','data-rule-maxlength'=>'250',
                                                'placeholder'=>'Enter your Designation']) !!}
                                                {!! $errors->first('designation','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-4 text-left required-star">Date of Birth</label>
                                            <div class="col-lg-8">
                                                <div class="datepicker input-group date" data-date-format="yyyy-mm-dd">
                                                    @if($users->user_DOB)
                                                            <?php $dob = App\Libraries\CommonFunction::changeDateFormat($users->user_DOB) ?>
                                                    @else
                                                            <?php $dob = '' ?>
                                                    @endif
                                                    {!! Form::text('user_DOB', $dob, ['class'=>'form-control input-sm required']) !!}
                                                    <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                    {!! $errors->first('user_DOB','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group {{ $errors->has('user_phone') ? 'has-error' : ''}}">
                                            <label for="user_phone" class="col-md-4 text-left required-star">
                                                Mobile Number
                                            </label>
                                            <div class="col-lg-8">
                                                {!! Form::text('user_phone',$users->user_phone, $attributes = array('class'=>'form-control input-sm required',
                                                'placeholder'=>'Enter your Mobile Number','id'=>"user_phone", 'data-rule-maxlength'=>'16')) !!}
                                                {!! $errors->first('user_phone','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div id="valid-msg" class="numberCheck hidden text-success"><i class="fa fa-check" aria-hidden="true"></i> Valid</div>
                                            <div id="error-msg" class="numberCheck hidden text-danger"><i class="fa fa-times" aria-hidden="true"></i> Invalid</div>

                                        </div>

                                        <div class="form-group {{ $errors->has('user_number') ? 'has-error' : ''}}">
                                            <label for="user_number" class="col-md-4 text-left">Telephone Number</label>
                                            <div class="col-lg-8">
                                                {!! Form::text('user_number',$users->user_number, $attributes = array('class'=>'form-control input-sm',
                                                'placeholder'=>'Enter your Telephone Number','id'=>"user_number", 'data-rule-maxlength'=>'16')) !!}
                                                {!! $errors->first('user_number','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        @if(Auth::user()->identity_type == 'nid')
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 text-left">National ID No.</label>
                                                <div class="col-lg-8">
                                                    {{ $users->user_nid }}
                                                </div>
                                            </div>
                                        @elseif(Auth::user()->identity_type == 'tin')
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 text-left">TIN No.</label>
                                                <div class="col-lg-8">
                                                    {{ $users->user_tin }}
                                                </div>
                                            </div>
                                        @elseif(Auth::user()->identity_type == 'passport')
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 text-left">Passport No.</label>
                                                <div class="col-lg-8">
                                                    {{ $users->passport_no }}
                                                </div>
                                            </div>
                                        @endif


                                        {{-- Start business category, 2 = government --}}
                                        @if($business_category != 2)
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 text-left">Nationality</label>
                                                <div class="col-lg-8">
                                                    {{ $user_nationality }}
                                                </div>
                                            </div>

                                            @if (Auth::user()->nationality_id == '18' || Auth::user()->nationality == 'BD')

                                                <div class="form-group has-feedback {{ $errors->has('division') ? 'has-error' : ''}}">
                                                    <label class="col-lg-4 text-left required-star">{!! trans('messages.division') !!}</label>
                                                    <div class="col-lg-8">
                                                        {!! Form::select('division', $divisions, (!empty($users->division) ? $users->division : null), ['class' => 'form-control input-sm required', 'id' => 'division', 'onchange'=>"getDistrictByDivisionId('division', this.value, 'district',".(!empty($users->district) ? $users->district:'').")"]) !!}
                                                        @if($errors->first('division'))
                                                            <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('division','') }}</em>
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback {{ $errors->has('district') ? 'has-error' : ''}}">
                                                    <label class="col-lg-4 text-left required-star">{!! trans('messages.district') !!}</label>
                                                    <div class="col-lg-8">
                                                        {!! Form::select('district', $districts, (!empty($users->district) ? $users->district : null), ['class' => 'form-control required input-sm','placeholder' => 'Select division first', 'id' => 'district', 'onchange'=>"getThanaByDistrictId('district', this.value, 'thana', ".(!empty($users->thana) ? $users->thana : '').")"]) !!}
                                                        @if($errors->first('district'))
                                                            <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('district','') }}</em>
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback {{ $errors->has('thana') ? 'has-error' : ''}}">
                                                    <label class="col-lg-4 text-left required-star">{!! trans('messages.police_station') !!}</label>
                                                    <div class="col-lg-8">
                                                        {!! Form::select('thana', [], $users->thana, $attributes = array('class'=>'form-control required input-sm', 'placeholder' => 'Select district first', 'id'=>"thana")) !!}
                                                        @if($errors->first('thana'))
                                                            <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('thana','') }}</em>
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback {{ $errors->has('post_office') ? 'has-error' : ''}}">
                                                    <label class="col-lg-4 text-left required-star">{!! trans('messages.post_office') !!}</label>
                                                    <div class="col-lg-8">
                                                        {!! Form::text('post_office', $users->post_office, $attributes = array('class'=>'form-control input-sm required', 'placeholder' => 'Name of your post office', 'data-rule-maxlength'=>'40', 'id'=>"post_office")) !!}
                                                        @if($errors->first('post_office'))
                                                            <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('post_office','') }}</em>
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            @else

                                                <div class="form-group has-feedback {{ $errors->has('state') ? 'has-error' : ''}}">
                                                    <label class="col-lg-4 text-left"> State</label>
                                                    <div class="col-lg-8">
                                                        {!! Form::text('state', $users->state, $attributes = array('class'=>'form-control input-sm', 'id'=>"state")) !!}
                                                        @if($errors->first('state'))
                                                            <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('state','') }}</em>
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="form-group has-feedback {{ $errors->has('province') ? 'has-error' : ''}}">
                                                    <label class="col-lg-4 text-left"> Province/ City</label>
                                                    <div class="col-lg-8">
                                                        {!! Form::text('province', $users->province, $attributes = array('class'=>'form-control input-sm', 'id'=>"province")) !!}
                                                        @if($errors->first('province'))
                                                            <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('province','') }}</em>
                                                </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            @endif

                                            <div class="form-group has-feedback {{ $errors->has('post_code') ? 'has-error' : ''}}">
                                                <label class="col-lg-4 text-left required-star">{!! trans('messages.post_code') !!}</label>
                                                <div class="col-lg-8">
                                                    {!! Form::text('post_code', $users->post_code, $attributes = array('class'=>'form-control post_code_bd input-sm required', 'placeholder'=>'Enter your post code', 'maxlength'=>"20", 'id'=>"post_code")) !!}
                                                    @if($errors->first('post_code'))
                                                        <span class="control-label">
                                                    <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('post_code','') }}</em>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group has-feedback {{ $errors->has('road_no') ? 'has-error' : ''}}">
                                                <label class="col-lg-4 text-left required-star">{!! trans('messages.address') !!}</label>
                                                <div class="col-lg-8">
                                                    {!! Form::text('road_no',$users->road_no, $attributes = array('class'=>'form-control required input-sm bnEng', 'placeholder'=>'Road No/ Address Line 1')) !!}
                                                    <span class="text-danger"></span>
                                                    {!! $errors->first('road_no','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        @endif
                                        {{-- End business category --}}

                                        <div class="form-group has-feedback">
                                            <label class="col-lg-4 text-left">User Status</label>
                                            <div class="col-lg-8">
                                                {{ $users->user_status }}
                                            </div>
                                        </div>

                                        @if($users->user_status == "rejected")
                                            <div class="form-group has-feedback">
                                                <label class="col-lg-4 text-left">Reject Reason</label>
                                                <div class="col-lg-8">
                                                    {{ $users->user_status_comment }}
                                                </div>
                                            </div>
                                        @endif

                                        @if(!empty($auth_letter))
                                            <div class="form-group">
                                                <label class="col-lg-4 text-left">Authorization Letter</label>
                                                <div class="col-md-8">
                                                    @if(file_exists("users/upload/" . $auth_letter))
                                                        <a target="_blank" rel="noopener" class="btn btn-primary btn-xs"
                                                           href="{!! url("users/upload/" . $auth_letter) !!}">
                                                            <i aria-hidden="true" class="fa fa-file-pdf-o"></i>
                                                            Open letter
                                                        </a>
                                                    @else
                                                        <span class="text-danger">
                                                            Authorization letter not found!
                                                        </span>
                                                    @endif {{-- auth file existed --}}
                                                </div>
                                            </div>
                                        @endif {{--  auth file is not Null--}}

                                        {{--                                        @if($users->user_status == "inactive")--}}
                                        {{--                                            <div class="form-group has-feedback {{ $errors->has('authorization_file') ? 'has-error' : ''}}">--}}
                                        {{--                                                <label class="col-lg-4 text-left  required-star">Authorization--}}
                                        {{--                                                    Letter<br/>--}}
                                        {{--                                                    <span style="font-size: 9px; color: grey">--}}
                                        {{--                                                {!! $doc_config !!}--}}
                                        {{--                                            </span>--}}
                                        {{--                                                </label>--}}
                                        {{--                                                <div class="col-lg-8">--}}
                                        {{--                                                    {!! '<img src="' . $auth_file . '" class="profile-user-img img-responsive"  alt="Authorization File" id="authorized_file"  width="350" />' !!}--}}
                                        {{--                                                    <br/>--}}
                                        {{--                                                    <div id="auth_file_err" style="color: red;">--}}

                                        {{--                                                    </div>--}}
                                        {{--                                                    <input type="hidden" class="upload_flags" value="0">--}}
                                        {{--                                                    {!! Form::file('authorization_file',['onchange'=>'readAuthFile(this)','data-ref'=>''.Encryption::encodeId(Auth::user()->id).'','data-type'=>'auth_file']) !!}--}}
                                        {{--                                                    <button class="btn btn-xs btn-primary hidden change_btn"--}}
                                        {{--                                                            type="button">Change--}}
                                        {{--                                                    </button>--}}
                                        {{--                                                    {!! $errors->first('authorization_file','<span class="help-block">:message</span>') !!}--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        @endif--}}
                                    </fieldset>
                                </div>

                                {{--Right Side--}}
                                <div class="col-md-1 col-sm-1"></div>
                                <div class="col-md-5 col-sm-5 col-sm-offset-1">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-4">
                                                <label class="center-block image-upload" for="applicant_photo">
                                                    <figure>
                                                        {{-- <img src="{{ \App\Libraries\UtilFunction::userProfileUrl($users->user_pic, 'users/upload/') }}" alt="user_pic.png" class="img-responsive img-thumbnail" id="applicant_photo_preview"/> --}}
                                                        <img src="{{ url('users/upload/'.$users->user_pic) }}" alt="user_pic.png"
                                                             class="img-responsive img-thumbnail" id="applicant_photo_preview"
                                                             onerror="this.src=`{{asset('/assets/images/default_profile.jpg')}}`" />

                                                    </figure>
                                                    <input type="hidden" id="applicant_photo_base64"
                                                           name="applicant_photo_base64"/>
                                                    @if(!empty($users->user_pic))
                                                        <input type="hidden" id="applicant_photo_hidden" name="applicant_photo"
                                                               value="{{$users->user_pic}}"/>
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="col-sm-6 col-md-8">
                                                <h4 class="required-star" id="profile_image">Profile Image</h4>
                                                <span style="font-size: 9px; color: grey">
                                                    [File Format: *.jpg / *.png, Dimension: 300x300 pixel]
                                                </span>
                                                <br><br>
                                                <label id="profile_image_div" class="btn btn-primary btn-file" {{ $errors->has('applicant_photo') ? 'has-error' : '' }}>
                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    Browse
                                                    <input type="file" style="display: none;"
                                                           class="form-control input-sm {{!empty($users->user_pic) ? '' : 'required'}}"
                                                           name="applicant_photo"
                                                           id="applicant_photo"
                                                           onchange="imageUploadWithCroppingAndDetect(this, 'applicant_photo_preview', 'applicant_photo_base64')"
                                                           size="300x300"/>
                                                </label>

                                                <label class="btn btn-primary" id="captureProfilePicture" data-profile-capture="yes">
                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    Camera
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(!in_array(Auth::user()->user_type,['5x505']))
                                    <div class="col-xs-12 col-md-5 col-sm-5 col-sm-offset-1">
                                        <div class="well well-sm">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-4">
                                                    <label class="center-block image-upload" for="applicant_signature">
                                                        <figure>
                                                            <img src="{{ (!empty($users->signature)? url('users/signature/'.$users->signature) : url('assets/images/photo_default.png')) }}" alt="photo_default.png"
                                                                 class="img-responsive img-thumbnail"
                                                                 id="applicant_signature_preview"
                                                                 onerror="this.src=`{{asset('/assets/images/photo_default.png')}}`"/>
                                                        </figure>
                                                        <input type="hidden" id="applicant_signature_base64"
                                                               name="applicant_signature_base64"/>
                                                        @if(!empty($users->signature))
                                                            <input type="hidden" id="applicant_signature_hidden" name="applicant_signature"
                                                                   value="{{$users->signature}}"/>
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="col-sm-6 col-md-8">
                                                    <h4 @if($users->user_type == '4x404') class="required-star" @endif>
                                                        Signature
                                                    </h4>
                                                    <span style="font-size: 9px; color: grey">
                                                        [File Format: *.jpg / *.png, Dimension: 300x80 pixel]
                                                    </span>
                                                    <br><br>
                                                    <label class="btn btn-primary btn-file" {{ $errors->has('applicant_signature') ? 'has-error' : '' }}>
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i> Browse
                                                        <input @if($users->user_type == '4x404' && !isset($users->signature)) class="required"
                                                               @endif
                                                               type="file"
                                                               style="display: none"
                                                               name="applicant_signature"
                                                               id="applicant_signature"
                                                               onchange="imageUploadWithCropping(this, 'applicant_signature_preview', 'applicant_signature_base64')"
                                                               size="300x80"/>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                @if(Auth::user()->user_type == '5x505')
                                    <div class="col-md-5 col-sm-5 col-sm-offset-1">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Associated Company</legend>
                                            <div class="control-group">
                                                    <?php $i = 1;?>
                                                @foreach($companyAssociated as $companyDetails)
                                                    <dd>{{$i++}}.
                                                            <?php
                                                            $returnData = $companyDetails->company_name;
                                                            if ($companyDetails->divisionName)
                                                                $returnData .= ", " . $companyDetails->divisionName;
                                                            if ($companyDetails->districtName)
                                                                $returnData .= ", " . $companyDetails->districtName;
                                                            echo $returnData;
                                                            ?>
                                                    </dd>
                                                @endforeach
                                            </div>
                                        </fieldset>
                                    </div>
                                @endif

                                @if(in_array(Auth::user()->user_type,['4x404','9x901','9x902','9x903','9x904']))
                                    <div class="col-md-5 col-sm-5 col-sm-offset-1">
                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Assigned Desk</legend>
                                            <div class="control-group">
                                                    <?php $i = 1;?>
                                                @if($desk != "")
                                                    @foreach($desk as $desk_name)
                                                        <dd>{{$i++}}. {!!$desk_name->desk_name!!}</dd>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </fieldset>
                                        @if($approvalCenter != "" || $approvalCenter != null)
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Assigned Division</legend>
                                                <div class="control-group">
                                                        <?php $i = 1;?>
                                                    @foreach($approvalCenter as $apc)
                                                        <dd>{{$i++}}. {!!$apc->office_name!!}</dd>
                                                    @endforeach
                                                </div>
                                            </fieldset>
                                        @endif

                                        @if($dpts != "" || $dpts != null)
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Assigned Department</legend>
                                                <div class="control-group">
                                                        <?php $i = 1;?>
                                                    @foreach($dpts as $dpt)
                                                        <dd>{{$i++}}. {!!$dpt->name!!}</dd>
                                                    @endforeach
                                                </div>
                                            </fieldset>
                                        @endif
                                        @if($sub_dpts != "" || $sub_dpts != null)
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Assigned Sub Department</legend>
                                                <div class="control-group">
                                                        <?php $i = 1;?>
                                                    @foreach($sub_dpts as $dpt)
                                                        <dd>{{$i++}}. {!!$dpt->name!!}</dd>
                                                    @endforeach
                                                </div>
                                            </fieldset>
                                        @endif

                                        @if(checkUserTrainingDesk())
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Assigned Training Desk</legend>
                                                <div class="control-group">
                                                    <dd>{{ userTrainingDesk() }}</dd>
                                                </div>
                                            </fieldset>
                                        @endif
                                    </div>
                                @endif

                                <div class="col-sm-12">
                                    <div class="text-right">
                                        <a href="{{url("/dashboard")}}" class="btn btn-md btn-default"><i
                                                    class="fa fa-times"></i> Close</a>
                                        <button type="submit" class="btn btn-primary btn-md" id='update_info_btn'><b>Update
                                                Profile</b></button>
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.tab-pane -->

                        {{--                        <div class="tab-pane" id="tab_2">--}}
                        {{--                            <div class="col-sm-10">--}}
                        {{--                                {!! Form::open(array('url' => '/users/update-password-from-profile','method' => 'patch', 'class' => 'form-horizontal',--}}
                        {{--                                'id'=> 'password_change_form')) !!}--}}
                        {{--                                --}}{{--<fieldset>--}}
                           {{--   <legend class="d-none">Passport Information</legend>--}}
                        {{--                                <div class="clearfix"><br/><br/></div>--}}
                        {{--                                {!! Form::hidden('Uid', $id) !!}--}}

                        {{--                                <div class="form-group has-feedback {{ $errors->has('user_old_password') ? 'has-error' : ''}}">--}}
                        {{--                                    <label class="col-lg-4 text-left">Old Password</label>--}}
                        {{--                                    <div class="col-lg-4">--}}
                        {{--                                        {!! Form::password('user_old_password', $attributes = array('class'=>'form-control required',--}}
                        {{--                                        'placeholder'=>'Enter your Old password','id'=>"user_old_password", 'data-rule-maxlength'=>'120')) !!}--}}
                        {{--                                        <span class="glyphicon glyphicon-check form-control-feedback"></span>--}}
                        {{--                                        {!! $errors->first('user_old_password','<span class="help-block">:message</span>') !!}--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}

                        {{--                                <div class="form-group has-feedback {{ $errors->has('user_new_password') ? 'has-error' : ''}}">--}}
                        {{--                                    <label class="col-lg-4 text-left">New Password</label>--}}
                        {{--                                    <div class="col-lg-4">--}}
                        {{--                                        {!! Form::password('user_new_password', $attributes = array('class'=>'form-control required',  'minlength' => "6",--}}
                        {{--                                        'placeholder'=>'Enter your New password','id'=>"user_new_password", 'data-rule-maxlength'=>'120')) !!}--}}
                        {{--                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>--}}
                        {{--                                        {!! $errors->first('user_new_password','<span class="help-block">:message</span>') !!}--}}
                        {{--                                    </div>--}}
                        {{--                                    <div class="col-lg-4">--}}
                        {{--                                        <code>[*Minimum 6 characters at least 1 Alphabet, 1 Number and 1 Special--}}
                        {{--                                            Character]</code>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}

                        {{--                                <div class="form-group has-feedback {{ $errors->has('user_confirm_password') ? 'has-error' : ''}}">--}}
                        {{--                                    <label class="col-lg-4 text-left">Confirm New Password</label>--}}
                        {{--                                    <div class="col-lg-4">--}}
                        {{--                                        {!! Form::password('user_confirm_password', $attributes = array('class'=>'form-control required', 'minlength' => "6",--}}
                        {{--                                        'placeholder'=>'Confirm your New password','id'=>"user_confirm_password", 'data-rule-maxlength'=>'120')) !!}--}}
                        {{--                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>--}}
                        {{--                                        {!! $errors->first('user_confirm_password','<span class="help-block">:message</span>') !!}--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}


                        {{--                                <div class="form-group">--}}
                        {{--                                    <div class="col-lg-2 col-lg-offset-6">--}}
                        {{--                                        <div class="clearfix"><br></div>--}}
                        {{--                                        <button type="submit" class="btn btn-block btn-primary" id="update_pass_btn"><b>Save</b>--}}
                        {{--                                        </button>--}}
                        {{--                                    </div>--}}
                        {{--                                    <div class="col-lg-4"></div>--}}
                        {{--                                </div>--}}

                        {{--                                <div class="form-group has-feedback">--}}
                        {{--                                    <div class="col-lg-1"></div>--}}
                        {{--                                    <div class="col-lg-5">--}}
                        {{--                                        {!! App\Libraries\CommonFunction::showAuditLog($users->updated_at, $users->updated_by) !!}--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                                --}}{{--</fieldset>--}}
                        {{--                                <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                        {{--                                {!! Form::close() !!}--}}
                        {{--                            </div>--}}

                        {{--                        </div><!-- /.tab-pane -->--}}

                        @if(Auth::user()->user_type != '1x101')
                            <div class="tab-pane table-responsive" id="tab_3">
                                <br>
                                {!! Form::open(array('url' => '/users/process-deligation','method' =>
                                'patch','id'=>'deligation', 'class' => '','enctype'
                                =>'multipart/form-data')) !!}
                                <div class="form-group col-lg-8">
                                    <div class="col-lg-3"><label class="required-star">User Type</label></div>
                                    <div class="col-lg-6">
                                            <?php $desig = ($delegate_to_types ? $delegate_to_types : '') ?>
                                        {!! Form::select('designation', $desig, '', $attributes =
                                        array('class'=>'form-control required', 'onchange'=>'getUserDeligate()',
                                        'placeholder' => 'Select Type', 'id'=>"designation_2")) !!}
                                    </div>
                                </div>

                                <div class="form-group  col-lg-8">
                                    <div class="col-lg-3"><label class="required-star">Delegated User</label></div>
                                    <div class="col-lg-6">
                                        {!! Form::select('delegated_user', [] , '', $attributes =
                                        array('class'=>'form-control required',
                                        'placeholder' => 'Select User', 'id'=>"delegated_user")) !!}
                                    </div>
                                </div>

                                <div class="form-group  col-lg-8">
                                    <div class="col-lg-3"><label>Remarks</label></div>
                                    <div class="col-lg-6">
                                        {!! Form::text('remarks','', $attributes = array('class'=>'form-control',
                                        'placeholder'=>'Enter your Remarks','id'=>"remarks")) !!}
                                    </div>
                                </div>


                                <div class="form-group  col-lg-8">
                                    <div class="col-lg-6 col-lg-offset-3">
                                        <button type="submit" class="btn btn-primary" id='deligate_btn'><b>Deligate</b>
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div><!-- /.tab-pane -->
                        @endif


                        <div class="tab-pane" id="tab_5">
                            <table aria-label="Detailed Report accessList" id="accessList"
                                   class="table table-striped table-responsive table-bordered dt-responsive"
                                   width="100%" cellspacing="0" style="font-size: 14px;">
                                <thead>
                                <tr>
                                    <th>Remote Address</th>
                                    <th>Login Type</th>
                                    <th>Log in time</th>
                                    <th>Log out time</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="tab_6">
                            <table aria-label="Detailed ReportaccessLogFailedList" id="accessLogFailedList"
                                   class="table table-striped table-responsive table-bordered dt-responsive"
                                   width="100%" cellspacing="0" style="font-size: 14px;">
                                <thead>
                                <tr>
                                    <th>Remote Address</th>
                                    <th>Failed Login Time</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_7">
                            <table aria-label="Detailed Report last50action" id="last50action"
                                   class="table table-striped table-responsive table-bordered dt-responsive"
                                   width="100%" cellspacing="0" style="font-size: 14px;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action Taken</th>
                                    <th>IP</th>
                                    <th>Date & Time</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_8">
                            <div class="form-group has-feedback" id="serverTime">
                                <div class="col-lg-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Application Time</legend>
                                        <div class="control-group">
                                            <strong>Date : </strong> <span id="app_date">{{ date('d-M-Y') }}</span>
                                            <br/>
                                            <strong>Time : </strong> <span id="app_time">{{ date('g:i:s A') }}</span>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="form-group has-feedback">
                                <div class="col-lg-12">
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Database Time</legend>
                                        <div class="control-group">
                                            <strong>Date : </strong> <span id="db_date">{{ date('d-M-Y') }}</span> <br/>
                                            <strong>Time : </strong> <span id="db_time">{{ date('g:i:s A') }}</span>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div><!-- /.tab-pane --><!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_9">
                            <div class="form-group has-feedback">
                                <div class="col-lg-11">
                                    <table aria-label="Detailed Report productionPrgTbl" id="productionPrgTbl" class="table table-bordered">
                                        <thead>
                                        <th>Process Type</th>
                                        <th>Process Status</th>
                                        <th>E-mail</th>
                                        <th>SMS</th>
                                        <th class="valigh-middle text-center"><span class="hashs">#</span></th>
                                        </thead>
                                        <tbody>
                                        <input type="checkbox" checked data-toggle="toggle" data-onstyle="primary">
                                        <input type="checkbox" checked data-toggle="toggle" data-onstyle="success">
                                        <input type="checkbox" checked data-toggle="toggle" data-onstyle="info">
                                        <input type="checkbox" checked data-toggle="toggle" data-onstyle="warning">
                                        <input type="checkbox" checked data-toggle="toggle" data-onstyle="danger">
                                        <input type="checkbox" checked data-toggle="toggle" data-onstyle="default">
                                        <tr id="rowProductionCount">
                                            <td>
                                                <?php $desig = ($process_type ? $process_type : '') ?>
                                                {!! Form::select('designation', $desig, '', $attributes =
                                                array('class'=>'form-control required', 'onchange'=>'getUserType()',
                                                'placeholder' => 'Select Type', 'id'=>"user_type")) !!}
                                            </td>
                                            <td>
                                                {!! Form::select('delegated_user', [] , '', $attributes =
                                                    array('class'=>'form-control required',
                                                    'placeholder' => 'Select User', 'id'=>"_user")) !!}
                                            </td>
                                            <td>
                                                <input type="checkbox" class="toggle-one" checked
                                                       data-width="100">

                                            </td>
                                            <td>
                                                <input type="checkbox" checked data-toggle="toggle" data-width="100">
                                            </td>
                                            <td>
                                                <a class="btn btn-xs btn-primary addTableRows productionPrgAddRow"
                                                   onclick="addTableRow('productionPrgTbl', 'rowProductionCount');"><i
                                                            class="fa fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.tab-pane --><!-- /.tab-pane -->

                    </div><!-- /.tab-content -->
                </div>
                <div class="panel-footer">
                    {!! App\Libraries\CommonFunction::showAuditLog($users->updated_at, $users->updated_by) !!}
                </div>
            </div>
        </div><!-- nav-tabs-custom -->
    </div>

    <div class="clearfix"></div>

@endsection

@section('footer-script')
    @include('partials.image-resize.image-upload')

    @include('partials.profile-capture')

    {{--    <script>--}}
    {{--        var base_url_qr = '{{url()}}';--}}
    {{--        var token = '{{ csrf_token() }}';--}}
    {{--    </script>--}}

    <script src="{{ asset("assets/scripts/jquery.autocomplete.min.js") }}"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="{{ asset("assets/scripts/jquery-ui-1.11.4.js") }}"></script>>

    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // var url = document.location.toString();
        //
        // if (url.match('#')) {
        //     var googleSignUpId = $('.checkGoogleLogin').attr("data-id");
        //     if (googleSignUpId == 1) {
        //         alert("You Have sign up by google.no need to change password");
        //         // return false;
        //     } else {
        //         $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        //     }
        // }

        function getUserDeligate() {
            var _token = $('input[name="_token"]').val();
            var designation = $('#designation_2').val();
            $.ajax({
                url: '{{url("users/get-delegate-userinfo")}}',
                type: 'post',
                data: {
                    _token: _token,
                    designation: designation
                },
                dataType: 'json',
                success: function (response) {
                    html = '<option value="">Select User</option>';
                    $.each(response, function (index, value) {
                        html += '<option value="' + value.id + '" >' + value.user_full_name + '</option>';
                    });
                    $('#delegated_user').html(html);
                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    //completed
                }
            });
        }

        {{--$(function () {--}}
        {{--    var _token = $('input[name="_token"]').val();--}}
        {{--    $("#vreg_form").validate({--}}
        {{--        errorPlacement: function () {--}}
        {{--            return false;--}}
        {{--        }--}}
        {{--    });--}}
        {{--    $(".agency").autocomplete({--}}
        {{--        source: function (request, response) {--}}
        {{--            $.ajax({--}}
        {{--                url: "{{url('users/get-agency')}}",--}}
        {{--                dataType: "json",--}}
        {{--                data: {--}}
        {{--                    q: request.term--}}
        {{--                },--}}
        {{--                success: function (data) {--}}
        {{--                    response(data);--}}
        {{--                }--}}
        {{--            });--}}
        {{--        },--}}
        {{--        response: function (event, ui) {--}}
        {{--            if (ui.content.length === 0) {--}}
        {{--                $(".empty-message").text("No results found");--}}
        {{--            } else {--}}
        {{--                $(".empty-message").empty();--}}
        {{--            }--}}
        {{--        },--}}
        {{--        select: function (event, data) {--}}
        {{--            $('.group_name_hidden').val(data.item.id);--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}




        $(document).ready(function () {
            $('#division').trigger('change');
            $("#district").trigger('change');

            // $('.checkGoogleLogin').click(function () {
            //     var googleSignUpId = $(this).attr("data-id");
            //     if (googleSignUpId == 1) {
            //         alert("You Have sign up by google.no need to change password");
            //         return false;
            //     }
            // });
        });

        $(function () {
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: 'now',
                minDate: '01/01/1916'
            });
        });

        $(function () {

            var clickAccessLog = 0;
            $('#accessLog').click(function () {
                clickAccessLog++;
                if (clickAccessLog == 1) {
                    $('#accessList').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{url("users/get-access-log-data-for-self")}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            error: function(xhr, error, thrown) {
                                let errorMessage = 'An error occurred while fetching data. Please try again later.';
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMessage = xhr.responseJSON.error;
                                }
                                $('#accessList').html('<div class="alert alert-warning" role="alert">' + errorMessage + '</div>');
                                $(".dataTables_processing").hide()
                            }
                        },
                        columns: [
                            {data: 'ip_address', name: 'ip_address'},
                            {data: 'user_login_type', name: 'user_login_type'},
                            {data: 'login_dt', name: 'login_dt'},
                            {data: 'logout_dt', name: 'logout_dt'},

                        ],
                        "aaSorting": []
                    });
                }
            });


            var clickAccessLogFailed = 0;
            $('#accessLogFailed').click(function () {
                clickAccessLogFailed++;
                if (clickAccessLogFailed == 1) {
                    $('#accessLogFailedList').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{url("users/get-access-log-failed")}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            error: function(xhr, error, thrown) {
                                let errorMessage = 'An error occurred while fetching data. Please try again later.';
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMessage = xhr.responseJSON.error;
                                }
                                $('#accessLogFailedList').html('<div class="alert alert-warning" role="alert">' + errorMessage + '</div>');
                                $(".dataTables_processing").hide()
                            }
                        },
                        columns: [
                            {data: 'remote_address', name: 'remote_address'},
                            {data: 'created_at', name: 'created_at'}

                        ],
                        "aaSorting": []
                    });
                }
            });

            var activitiesClick = 0;
            $('#50Activities').click(function () {
                activitiesClick++;
                if (activitiesClick == 1) {
                    $('#last50action').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: '{{url("users/get-last-50-action")}}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            error: function(xhr, error, thrown) {
                                let errorMessage = 'An error occurred while fetching data. Please try again later.';
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMessage = xhr.responseJSON.error;
                                }
                                $('#last50action').html('<div class="alert alert-warning" role="alert">' + errorMessage + '</div>');
                                $(".dataTables_processing").hide()
                            }
                        },
                        columns: [
                            {data: 'rownum', name: 'rownum'},
                            {data: 'action', name: 'action'},
                            {data: 'ip_address', name: 'ip_address'},
                            {data: 'created_at', name: 'created_at'}

                        ],
                        "aaSorting": []
                    });
                }
            });

            $('.server_date_time').on('click', function () {

                $.ajax({
                    type: 'POST',
                    url: '{{url("users/get-server-time")}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {

                        $('#db_date').html(data.db_date);
                        $('#db_time').html(data.db_time);
                        $('#app_date').html(data.app_date);
                        $('#app_time').html(data.app_time);

                    }
                });

                // setInterval(function () {
                //
                // }, 1000);



            });

        });


        $('#password_change_form').validate({
            rules: {
                user_confirm_password: {
                    equalTo: "#user_new_password"
                }
            },
            errorPlacement: function () {
                return false;
            }
        });

        $(document).ready(
            function () {
                $("#profile-form").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
        $("#deligation").validate({
            errorPlacement: function () {
                return false;
            }

        });
        $("#update_form").validate({
            errorPlacement: function () {
                return false;
            }
        });

        $(function () {
            $('.toggle-one').bootstrapToggle();
        })

        $('#update_info_btn').on('click', function (ev) {
            uploadCrop.croppie('result', {
                type: 'canvas',
                size: 'original'
            }).then(function (resp) {
                $('#imagebase64').val(resp);
                $('#update_info_btn').submit();
            });
        });
    </script>

    <style>
        #accessList {
            height: 100px !important;
            overflow: scroll;
        }

        .dataTables_scrollHeadInner {
            width: 100% !important;
        }

        .profileinfo-table {
            width: 100% !important;
        }
    </style>

    <script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
    <script>
        //Initiate number plugin
        $(function () {
            $("#user_phone").intlTelInput({
                hiddenInput: "user_phone",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true,
            });
        });

        $("#user_phone").change(function() {
            var telInput = $("#user_phone");
            if ($.trim(telInput.val())) {
                if (telInput.intlTelInput("isValidNumber")) {
                    $('#valid-msg').removeClass('hidden');
                    $('#error-msg').addClass('hidden');
                } else {
                    $('#error-msg').removeClass('hidden');
                    $('#valid-msg').addClass('hidden');
                }
            }
        });
    </script>
@endsection