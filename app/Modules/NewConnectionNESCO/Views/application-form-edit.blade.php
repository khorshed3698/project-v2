<?php
$accessMode = ACL::getAccsessRight('NewConnectionNESCO');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 2px;
    }

    .wizard > .steps > ul > li {
        width: 33% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }

    textarea {
        height: 60px !important;
    }

    .col-md-7 {
        margin-bottom: 10px;
    }

    label {
        float: left !important;
    }

    .col-md-5 {
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 8px;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .adhoc {
        margin-left: 15px;
    }

    .adhoc button {
        margin-top: 15px;
    }

    table thead {
        background-color: #ddd;
    }

    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

    }
</style>


<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box" id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h5><strong>Application For New Connection (NESCO)</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'new-connection-nesco/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                           id="app_id"/>
                    <input type="hidden" name="ossTrackingNo"
                           value="{{ \App\Libraries\Encryption::encodeId($appInfo->tracking_no) }}" id="ossTrackingNo"/>
                    <input type="hidden" name="dpdc_traking_num"
                           value="{{ \App\Libraries\Encryption::encodeId($appInfo->trakingNum) }}" id="trakingNum"/>

                    <h3 class="text-center stepHeader"> General Information</h3>
                    <fieldset>
                        @if($appInfo->shortfall_message !="" && $appInfo->status_id == 5 && $appInfo->shortfall_message !=null)
                            <div class="panel panel-info">
                                <div class="panel-heading" style="padding-bottom: 4px;">
                                    <strong>Short Message</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="alert alert-danger ">
                                        {{$appInfo->shortfall_message}}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>সাধারণ তথ্যাবলী</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('applicant_name','নাম :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_name', !empty($appData->applicant_name) ? $appData->applicant_name :'',['class' => 'form-control
                                                input-md','id'=>'applicant_name','placeholder'=>'Name']) !!}
                                                {!! $errors->first('applicant_name','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('father_name_or_organization','পিতা/প্রতিষ্ঠান :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('father_name_or_organization') ? 'has-error': ''}}">
                                                {!! Form::text('father_name_or_organization', !empty($appData->father_name_or_organization) ? $appData->father_name_or_organization :'',['class' => 'form-control
                                                input-md','id'=>'father_name_or_organization','placeholder'=>'Father name']) !!}
                                                {!! $errors->first('father_name_or_organization','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('applicant_mother_name','মায়ের নাম :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_mother_name') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_mother_name', !empty($appData->applicant_mother_name) ? $appData->applicant_mother_name :'',['class' => 'form-control
                                                input-md','id'=>'applicant_mother_name','placeholder'=>'Mother name']) !!}
                                                {!! $errors->first('applicant_mother_name','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('applicant_husband_or_wife_name','স্বামী/স্ত্রীর নাম:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_husband_or_wife_name') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_husband_or_wife_name', !empty($appData->applicant_husband_or_wife_name) ? $appData->applicant_husband_or_wife_name :'',['class' => 'form-control
                                                input-md','id'=>'applicant_husband_or_wife_name','placeholder'=>'Husband or Wife Name']) !!}
                                                {!! $errors->first('applicant_husband_or_wife_name','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('applicant_dob','জন্ম তারিখ:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="datepickerDob col-md-7 {{$errors->has('applicant_dob') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_dob','',['class' => 'form-control
                                                input-md','id'=>'applicant_dob','readonly','style'=>'background:white;']) !!}
                                                {!! $errors->first('applicant_dob','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('applicant_gender','লিঙ্গ:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_gender') ? 'has-error': ''}}">
                                                {!! Form::select('applicant_gender', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md','id'=>'applicant_gender']) !!}
                                                {!! $errors->first('applicant_gender','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('applicant_post_office','ডাকঘর :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_post_office') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_post_office', !empty($appData->applicant_post_office) ? $appData->applicant_post_office :'',['class' => 'form-control
                                                input-md','id'=>'applicant_post_office','placeholder'=>'Post Office']) !!}
                                                {!! $errors->first('applicant_post_office','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('applicant_district','জেলা:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_district') ? 'has-error': ''}}">
                                                {!! Form::select('applicant_district', [],'', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md search-box','id'=>'applicant_district']) !!}
                                                {!! $errors->first('applicant_district','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('applicant_nid_no','জাতীয় পরিচয় পত্র :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_nid_no') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_nid_no', !empty($appData->applicant_nid_no) ? $appData->applicant_nid_no :'',['class' => 'form-control onlyNumber nid
                                                input-md','id'=>'applicant_nid_no','placeholder'=>'ID Card No']) !!}
                                                {!! $errors->first('applicant_nid_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('applicant_post_code','পোস্ট কোড :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_post_code') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_post_code', !empty($appData->applicant_post_code) ? $appData->applicant_post_code :'',['class' => 'form-control
                                                input-md onlyNumber','id'=>'applicant_post_code','placeholder'=>'Post Code']) !!}
                                                {!! $errors->first('applicant_post_code','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('applicant_tin','টিন নম্বর :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_tin') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_tin',  !empty($appData->applicant_tin) ? $appData->applicant_tin :'',['class' => 'form-control
                                                input-md tin','id'=>'applicant_tin','placeholder'=>'TIN No']) !!}
                                                {!! $errors->first('applicant_tin','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>যোগাযোগের তথ্যাবলী</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('address_line_1','১ম ঠিকানা :',
                                            ['class'=>'col-md-3']) !!}
                                            <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                                {!! Form::text('address_line_1', !empty($appData->address_line_1) ? $appData->address_line_1 :'',['class' => 'form-control
                                                input-md','id'=>'address_line_1','placeholder'=>'Address Line 1']) !!}
                                                {!! $errors->first('address_line_1','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('address_line_2','২য় ঠিকানা :',
                                            ['class'=>'col-md-3']) !!}
                                            <div class="col-md-7 {{$errors->has('address_line_2') ? 'has-error': ''}}">
                                                {!! Form::text('address_line_2', !empty($appData->address_line_2) ? $appData->address_line_2 :'',['class' => 'form-control
                                                input-md','id'=>'address_line_2','placeholder'=>'Address Line 2']) !!}
                                                {!! $errors->first('address_line_2','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('mobile_no','মোবাইল নং :',
                                            ['class'=>'col-md-3']) !!}
                                            <div class="col-md-7 {{$errors->has('mobile_no') ? 'has-error': ''}}">
                                                {!! Form::text('mobile_no', !empty($appData->mobile_no) ? $appData->mobile_no :'',['class' => 'form-control onlyNumber
                                                input-md mobile','id'=>'mobile_no','placeholder'=>'Mobile No']) !!}
                                                {!! $errors->first('mobile_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('email','ই-মেইল :',
                                            ['class'=>'col-md-3']) !!}
                                            <div class="col-md-7 {{$errors->has('email') ? 'has-error': ''}}">
                                                {!! Form::text('email', !empty($appData->email) ? $appData->email :'',['class' => 'form-control email
                                                input-md','id'=>'email','placeholder'=>'Email']) !!}
                                                {!! $errors->first('email','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>সংযোগ স্থানের বিবরণ</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('house_or_dag_no','বাড়ি/দাগ নং :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('house_or_dag_no') ? 'has-error': ''}}">
                                                {!! Form::text('house_or_dag_no', !empty($appData->house_or_dag_no) ? $appData->house_or_dag_no :'',['class' => 'form-control
                                                input-md','id'=>'house_or_dag_no','placeholder'=>'House / Dag No']) !!}
                                                {!! $errors->first('house_or_dag_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('plot_no','প্লট নং :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('plot_no') ? 'has-error': ''}}">
                                                {!! Form::text('plot_no', !empty($appData->plot_no) ? $appData->plot_no :'',['class' => 'form-control
                                                input-md','id'=>'plot_no','placeholder'=>'Plot No']) !!}
                                                {!! $errors->first('plot_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('av_lane_road_no','এভিনিউ/লেন/রাস্তা :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('av_lane_road_no') ? 'has-error': ''}}">
                                                {!! Form::text('av_lane_road_no', !empty($appData->av_lane_road_no) ? $appData->av_lane_road_no :'',['class' => 'form-control
                                                input-md','id'=>'av_lane_road_no','placeholder'=>'AV/Lane/Road No']) !!}
                                                {!! $errors->first('av_lane_road_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('block','ব্লক :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('block') ? 'has-error': ''}}">
                                                {!! Form::text('block', !empty($appData->block) ? $appData->block :'',['class' => 'form-control
                                                input-md','id'=>'block','placeholder'=>'Block']) !!}
                                                {!! $errors->first('block','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('district','জেলা :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('district') ? 'has-error': ''}}">
                                                {!! Form::select('district', [],'', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md search-box','id'=>'district']) !!}
                                                {!! $errors->first('district','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('thana','থানা :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('thana') ? 'has-error': ''}}">
                                                {!! Form::select('thana', [],'', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md search-box','id'=>'thana']) !!}
                                                {!! $errors->first('thana','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {!! Form::label('section','সেকশন :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('section') ? 'has-error': ''}}">
                                                {!! Form::text('section', !empty($appData->section) ? $appData->section :'',['class' => 'form-control
                                                input-md','id'=>'section','placeholder'=>'Section']) !!}
                                                {!! $errors->first('section','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('division','বিভাগ :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('division') ? 'has-error': ''}}">
                                                {!! Form::select('division', [],'', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md search-box','id'=>'division']) !!}
                                                {!! $errors->first('division','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            {!! Form::label('existing_account_no','বিদ্যমান হিসাব নং :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('existing_account_no') ? 'has-error': ''}}">
                                                {!! Form::text('existing_account_no', !empty($appData->existing_account_no) ? $appData->existing_account_no :'',['class' => 'form-control
                                                input-md','id'=>'existing_account_no','placeholder'=>'Existing Account No']) !!}
                                                {!! $errors->first('existing_account_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>সংযোগের বিবরণ/অতিরিক্ত লোড</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            {!! Form::label('connection_type','ধরণ :',
                                            ['class'=>'col-md-4']) !!}
                                            <div class="col-md-8 {{$errors->has('connection_type') ? 'has-error': ''}}">
                                                {!! Form::select('connection_type', [],$appData->connection_type,['class' => 'form-control input-md docloader','id'=>'connection_type']) !!}
                                                {!! $errors->first('connection_type','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {!! Form::label('load','লোড :',
                                            ['class'=>'col-md-4']) !!}
                                            <div class="col-md-8 {{$errors->has('plot_no') ? 'has-error': ''}}">
                                                {!! Form::text('load', !empty($appData->load) ? $appData->load :'',['class' => 'form-control
                                                input-md','id'=>'load','placeholder'=>'Load']) !!}
                                                {!! $errors->first('load','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {!! Form::label('phase','ফেইজ :',
                                            ['class'=>'col-md-4']) !!}
                                            <div class="col-md-8 {{$errors->has('phase') ? 'has-error': ''}}">
                                                {!! Form::select('phase', [],$appData->phase, ['class' => 'form-control input-md docloader','id'=>'phase']) !!}
                                                {!! $errors->first('phase','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {!! Form::label('tariff','শ্রেণী :',
                                            ['class'=>'col-md-4']) !!}
                                            <div class="col-md-8 {{$errors->has('tariff') ? 'has-error': ''}}">
                                                {!! Form::select('tariff', [],$appData->tariff, ['placeholder' => 'Select One',
                                                    'class' => 'form-control input-md docloader search-box','id'=>'tariff']) !!}
                                                {!! $errors->first('tariff','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info" style="margin-top: 15px;">
                                    <div class="panel-heading"><strong>সংযোগের সংখ্যা</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('meter','মিটারের সংখ্যা :',
                                                ['class'=>'col-md-3']) !!}
                                                <div class="col-md-7 {{$errors->has('meter') ? 'has-error': ''}}">
                                                    {!! Form::text('meter', !empty($appData->meter) ? $appData->meter :'',['class' => 'form-control
                                                    input-md onlyNumber','id'=>'meter','placeholder'=>'meter','readonly']) !!}
                                                    {!! $errors->first('meter','<span
                                                        class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>কাগজপত্র আপলোড</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_photo','আবেদনকারীর ছবি :', ['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_photo') ? 'has-error': ''}}">
                                                <input type="file" name="applicant_photo" id="applicant_photo" flag="img"
                                                       <?php if (empty($appData->validate_field_photo)) {
                                                           echo "class='required'";
                                                       } ?>
                                                       onchange="uploadDocument('preview_photo', this.id, 'validate_field_photo',1)">
                                                {!! $errors->first('photo','<span class="help-block">:message</span>')
                                                !!}
                                                <span style="color:#993333;">[N.B. Supported file extension is
                                                    pdf,png,jpg,jpeg.Max size less than 300KB]</span>
                                                <div id="preview_photo">
                                                    <input type="hidden"
                                                           value="{{ !empty($appData->validate_field_photo) ? $appData->validate_field_photo : '' }}"
                                                           id="validate_field_photo" name="validate_field_photo"
                                                           class="required">
                                                </div>
                                                @if(!empty($appData->validate_field_photo))
                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.$appData->validate_field_photo)}}"
                                                       title="{{$appData->validate_field_photo}}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_signature','আবেদনকারীর স্বাক্ষর :', ['class'=>'col-md-5
                                            required-star']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_signature') ? 'has-error': ''}}">
                                                <input type="file" name="applicant_signature" id="applicant_signature" flag="img"
                                                       <?php if (empty($appData->validate_field_signature)) {
                                                           echo "class='required'";
                                                       } ?>
                                                       onchange="uploadDocument('preview_signature', this.id, 'validate_field_signature',1)">
                                                {!! $errors->first('applicant_signature','<span
                                                    class="help-block">:message</span>') !!}
                                                <span style="color:#993333;">[N.B. Supported file extension is
                                                    pdf,png,jpg,jpeg.Max size less than 300KB]</span>
                                                <div id="preview_signature">
                                                    <input type="hidden"
                                                           value="{{ !empty($appData->validate_field_signature) ? $appData->validate_field_signature : '' }}"
                                                           id="validate_field_signature" name="validate_field_signature"
                                                           class="required">
                                                </div>
                                                @if(!empty($appData->validate_field_signature))
                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.$appData->validate_field_signature)}}"
                                                       title="{{$appData->validate_field_signature}}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_nid_pdf','আবেদনকারীর এন আইডি  :', ['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_nid_pdf') ? 'has-error': ''}}">
                                                <input type="file" name="applicant_nid_pdf" id="applicant_nid_pdf"
                                                       <?php if (empty($appData->validate_field_nid)) {
                                                           echo "class='required'";
                                                       } ?>
                                                       onchange="uploadDocument('preview_nid', this.id, 'validate_field_nid',1)">
                                                {!! $errors->first('nid','<span class="help-block">:message</span>')
                                                !!}
                                                <span style="color:#993333;">[N.B. Supported file extension is
                                                    pdf,png,jpg,jpeg.Max size less than 300KB]</span>
                                                <div id="preview_nid">
                                                    <input type="hidden"
                                                           value="{{ !empty($appData->validate_field_nid) ? $appData->validate_field_nid : '' }}"
                                                           id="validate_field_nid" name="validate_field_nid"
                                                           class="required">
                                                </div>
                                                @if(!empty($appData->validate_field_nid))
                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.$appData->validate_field_nid)}}"
                                                       title="{{$appData->validate_field_nid}}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_land_pdf','আবেদনকারীর জমি খারিজের কপি  :', ['class'=>'col-md-5
                                            required-star']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_land_pdf') ? 'has-error': ''}}">
                                                <input type="file" name="applicant_land_pdf" id="applicant_land_pdf"
                                                       <?php if (empty($appData->validate_field_land)) {
                                                           echo "class='required'";
                                                       } ?>
                                                       onchange="uploadDocument('preview_land', this.id, 'validate_field_land',1)">
                                                {!! $errors->first('applicant_land','<span
                                                    class="help-block">:message</span>') !!}
                                                <span style="color:#993333;">[N.B. Supported file extension is
                                                    pdf,png,jpg,jpeg.Max size less than 300KB]</span>
                                                <div id="preview_land">
                                                    <input type="hidden"
                                                           value="{{ !empty($appData->validate_field_land) ? $appData->validate_field_land : '' }}"
                                                           id="validate_field_land" name="validate_field_land"
                                                           class="required">
                                                </div>
                                                @if(!empty($appData->validate_field_land))
                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.$appData->validate_field_land)}}"
                                                       title="{{$appData->validate_field_land}}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>


                    <h3 class="text-center stepHeader">Attachments</h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('NewConnectionNESCO::documents')
                                </div>

                            </div>
                        </div>
                    </fieldset>

                    <h3 class="text-center stepHeader">Declaration & Submit</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading" style="padding-bottom: 4px;">
                                <strong>DECLARATION</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <ol type="a">
                                                <li>
                                                    <p>I do hereby declare that the information given above is true to
                                                        the best of my knowledge and I shall be liable for any false
                                                        information/ statement given</p>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered table-striped">
                                    <thead class="alert alert-info">
                                    <tr>
                                        <th colspan="3" style="font-size: 15px">Authorized person of the
                                            organization
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::label('auth_name','Full name:', ['class'=>'required-star'])
                                            !!}
                                            {!! Form::text('auth_name',
                                            \App\Libraries\CommonFunction::getUserFullName(), ['class' =>
                                            'form-control input-md required', 'readonly']) !!}
                                            {!! $errors->first('auth_name','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::label('auth_email','Email:', ['class'=>'required-star']) !!}
                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' =>
                                            'form-control required input-md email', 'readonly']) !!}
                                            {!! $errors->first('auth_email','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::label('auth_cell_number','Cell number:',
                                            ['class'=>'required-star']) !!}<br>
                                            {!! Form::text('auth_cell_number', Auth::user()->user_phone, ['class' =>
                                            'form-control input-md required phone_or_mobile', 'readonly']) !!}
                                            {!! $errors->first('auth_cell_number','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>Date : </strong><?php echo date('F d,Y')?></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                            'class'=>'required')) !!}
                                            All the details and information provided in this form are true and complete.
                                            I am aware that any untrue/incomplete statement may result in delay in BIN
                                            issuance and I may be subjected to full penal action under the Value Added
                                            Tax and Supplementary Duty Act, 2012 or any other applicable Act Prevailing
                                            at present.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            @if($appInfo->status_id != 5)
                            <div class="pull-left">
                                <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                            </div>
                            @endif

                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md" value="Submit" name="actionBtn">
                                    @if($appInfo->status_id == 5)
                                        Re Submit
                                    @else
                                        Submit
                                    @endif
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 button_last">
                            <div class="clearfix"></div>
                        </div>
                    </div> {{--row--}}

                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {
        $(document).on('blur', '.mobile', function () {
            var mobile_telephone = $(this).val()
            if (mobile_telephone.length != 11) {
                $(this).addClass('error');
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }

        });

        $(document).on('keydown', '.mobile', function () {
            var mobile_telephone = $(this).val();
            var reg = /^01/;
            if (mobile_telephone.length == 2) {
                if (reg.test(mobile_telephone)) {
                    $(this).removeClass('error');
                    return true;
                } else {
                    $(this).addClass('error')
                    $(this).val('')
                    return false;
                }
            }

        });

        var form = $("#NewConnection").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex == 2) {
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
                if (currentIndex != -1) {
                    form.find('#save_as_draft').css('display', 'block');
                    form.find('.actions').css('top', '-42px');
                } else {
                    form.find('#save_as_draft').css('display', 'none');
                    form.find('.actions').css('top', '-15px');
                }
                if (currentIndex == 2) {
                    form.find('#submitForm').css('display', 'block');

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

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=NewConnection@2'); ?>');
            } else {
                return false;
            }
        });

        {{----end step js---}}
        $("#NewConnection").validate({
            rules: {
                field: {
                    required: true,
                    email: true,

                }
            }
        });

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });

        var calculatedYear = (new Date).getFullYear() - 19;
        var currentMonth = (new Date).getMonth();
        var currentDay = (new Date).getDate();

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: new Date(calculatedYear, currentMonth, currentDay),
            ignoreReadonly: true
        });
        @if(!empty($appData->applicant_dob))
        $('.datepickerDob').find('input').val('{{$appData->applicant_dob}}');
        @else
        $('.datepickerDob').find('input').val('');
        @endif


        $('.datepickerFuture').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
            maxDate: '01/01/' + (yyyy + 6)
        });

        $('.onlyNumber').on('keydown', function (e) {
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
                $this.val($this.val().replace(/[^0-9]/g, ''));
            }, 5);
        });

        $('.nid').on('focusout', function (e) {
            var nid = $(this).val().length
            if (nid == 10 || nid == 13 || nid == 17) {
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        })

        $('.tin').on('focusout', function (e) {
            var tin = $(this).val().length
            if (tin == 11 || tin == 12) {
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                alert("TIN must be 11 or 12 digit");
                return false;
            }
        });

        $('#meter').on('input', function (e) {
            var meter = $(this).val()
            if (meter != 1) {
                $(this).val('');
                alert("Meter number must be 1(one)");
                $(this).addClass('error');
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }
        })

    });

    /*document upload start*/


    function uploadDocument(targets, id, vField, isRequired) {
        alert(22)
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/new-connection-nesco/upload-document')}}";
            //alert(action);
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
                dataType: 'text', // what to expect back from the PHP script, if anything
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
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    $('#' + id).removeClass('required');
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = '';
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
        } else {
            return false;
        }
    });

    $(document).ready(function () {

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/new-connection-nesco/get-refresh-token';

            $('#applicant_gender').keydown();
            $('#applicant_district').keydown();
            $('#connection_type').keydown();
            $('#phase').keydown();
            $('#tariff').keydown();
            $('#district').keydown();
            $('#division').keydown();


        });

        $('#applicant_gender').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$nesco_service_url}}/genders";
            var selected_value = "{{!empty($appData->applicant_gender) ? $appData->applicant_gender : ''}}"; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "value"; //dynamic id for callback
            var element_name = "option"; //dynamic name for callback
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });

        $('#applicant_district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$nesco_service_url}}/districts";
            var selected_value = '{{!empty($appData->applicant_district) ? $appData->applicant_district :''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "division_id"; //dynamic name for callback
            var data = 'name';
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponseWithData, arrays);

        });

        $('#connection_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$nesco_service_url}}/connection-types";
            var selected_value = "{{!empty($appData->connection_type) ? $appData->connection_type :''}}"; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "code"; //dynamic name for callback
            var data = "description";
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponseWithData, arrays);

        });

        $('#phase').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$nesco_service_url}}/connection-phases";
            var selected_value = '{{!empty($appData->phase) ? $appData->phase :''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "code"; //dynamic name for callback
            var data = "description";
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponseWithData, arrays);

        });

        $('#tariff').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$nesco_service_url}}/tariff-groups";
            var selected_value = '{{!empty($appData->tariff) ? $appData->tariff :''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "code"; //dynamic name for callback
            var data = "description";
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, tariffCallbackResponseWithData, arrays);

        });

        $('#district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$nesco_service_url}}/districts";
            var selected_value = '{{!empty($appData->district) ? $appData->district :''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "division_id"; //dynamic name for callback
            var data = "name";
            // var data = JSON.stringify({name: 'getLandUseList'});
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, districtCallbackResponseWithData, arrays);

        });

        $("#district").on("change", function () {
            var self = $(this);
            $("#thana").html('<option value="">Please Wait...</option>');
            var district = $('#district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this);
                var api_url = "{{$nesco_service_url}}/thana-by-district" + '/' + districtId;
                var selected_value = '{{!empty($appData->thana) ? $appData->thana :''}}'; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "thana"; // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "name"; //dynamic name for callback
                var district_id = "district_id";
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id];

                var apiHeaders = [
                    {
                        key: "Content-Type",
                        value: 'application/json'
                    },
                    {
                        key: "client-id",
                        value: 'OSS_BIDA_DEV_N'
                    },
                ];
                apiCallGet(e, options, apiHeaders, districtCallbackResponseDependentSelect, arrays);

            } else {
                $("#thana").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        });

        $('#division').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$nesco_service_url}}/divisions";
            var selected_value = '{{!empty($appData->division) ? $appData->division :''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "code"; //dynamic name for callback
            var data = "description";
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, divisionCallbackResponseWithData, arrays);

        });

    })

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];

                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
    }

    function callbackResponseWithData(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';

        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[data];
                var value = row[data];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
    }

    function tariffCallbackResponseWithData(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';

        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[data];
                var value = row[data];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).parent().find('.loading_data').hide();
        $("#" + calling_id).trigger('change');
        $(".search-box").select2();
    }

    function divisionCallbackResponseWithData(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';

        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[data];
                var value = row[data];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $('.search-box').select2();
    }

    function districtCallbackResponseWithData(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';

        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[data];
                var value = row[data];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).parent().find('.loading_data').hide()
        $('#' + calling_id).trigger('change');
    }

    function districtCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id]) {
        var option = '<option value="">Select One</option>';
        $("#" + dependent_section_id).select2('destroy');
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[district_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        //alert(dependent_section_id);.
        $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
    }


</script>