<?php
$accessMode = ACL::getAccsessRight('NewConectionBPDB');
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
                        <h5><strong>Application For New Connection (DPDC)</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'new-connection-dpdc/store','method' => 'post', 'class' =>
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

                        @if($appInfo->status_id == 5 && count($shortfallarr)>0)
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>SHORTFALL DOCUMENTS</strong></div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <ul>
                                            @foreach($shortfallarr as $key=>$doc)
                                                <li>{{$doc->docDesc}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    @endif

                    <!--panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong id="heading"></strong></div>
                            <div class="panel-body">
                                <div class="row col-md-12 text-center" style="margin-bottom:10px;">
                                    {!! Form::label('consumer_type','Consumer Type', ['class'=>'col-md-2 col-md-offset-3
                                    required-star','style'=>'margin-top:5px;']) !!}
                                    <div class="col-md-6 {{$errors->has('consumer_type') ? 'has-error': ''}}">
                                        <label class="radio-inline pull-left">{!! Form::radio('consumer_type','P',
                                        $appData->consumer_type == 'P' ? true : false,
                                        ['id' => 'personal_type','onchange'=>'application_type(this.value)']) !!}
                                            Personal</label>
                                        <label class="radio-inline">{!! Form::radio('consumer_type', 'O',
                                          $appData->consumer_type == 'O' ? true : false,
                                        ['id' => 'organization','onchange'=>'application_type(this.value)']) !!}
                                            Organization / Institute</label>

                                        {!! $errors->first('consumer_type','<span class="help-block">:message</span>') !!}
                                    </div>

                                </div>


                                {{-- ------------------------      Organization        --------------------------------------------}}
                                <div id="organization_1">
                                    <div class="form-group" style="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('organization_name',"Organization Name", ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('organization_name') ? 'has-error': ''}}">
                                                    {!! Form::text('organization_name',isset($appData->organization_name) ? $appData->organization_name :'',['class' => 'form-control input-sm','id'=>'organization_name']) !!}

                                                    {!! $errors->first('organization_name','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('proprietor_name',"Proprietor Name", ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('proprietor_name') ? 'has-error': ''}}">
                                                    {!! Form::text('proprietor_name',isset($appData->proprietor_name) ? $appData->proprietor_name :'',['class' => 'form-control input-sm','id'=>'proprietor_name']) !!}

                                                    {!! $errors->first('proprietor_name','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ----------------------------------------------------------------------------------------------}}


                                <div>
                                    <div class="form-group" id="personal_user_1" style="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_name','Name', ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_name', isset($appData->applicant_name) ? $appData->applicant_name :'',['class' => 'form-control input-sm','id'=>'applicant_name']) !!}

                                                    {!! $errors->first('applicant_name','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_spouse_name','Spouse Name', ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_spouse_name') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_spouse_name',isset($appData->applicant_spouse_name) ? $appData->applicant_spouse_name :'',['class' => 'form-control input-sm','id'=>'applicant_spouse_name']) !!}

                                                    {!! $errors->first('applicant_spouse_name','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="personal_user_2" style="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_father_name',"Father's Name", ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('applicant_father_name') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_father_name',isset($appData->applicant_father_name) ? $appData->applicant_father_name :'',['class' => 'form-control input-sm','id'=>'applicant_father_name']) !!}
                                                    {!! $errors->first('applicant_father_name','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('applicant_mother_name',"Mother's Name", ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7 {{$errors->has('union') ? 'has-error': ''}}">
                                                    {!! Form::text('applicant_mother_name', isset($appData->applicant_mother_name) ? $appData->applicant_mother_name :'' ,['class' => 'form-control input-sm','id'=>'applicant_mother_name']) !!}

                                                    {!! $errors->first('applicant_mother_name','<span class="help-block">:message</span>')
                                                    !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="">
                                        <div class="row">
                                            <div class="col-md-6" id="organization_2">
                                                {!! Form::label('pr_date_of_birth',"Proprietor's Birthday :", ['class'=>'col-md-5'])
                                                !!}
                                                <div class="col-md-7 {{$errors->has('pr_date_of_birth') ? 'has-error': ''}}">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('pr_date_of_birth',isset($appData->pr_date_of_birth) ? $appData->pr_date_of_birth :'' ,['class' => 'form-control
                                                        input-sm','id'=>'pr_date_of_birth']) !!}
                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('pr_date_of_birth','<span
                                                        class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6" id="personal_user_3">
                                                {!! Form::label('date_of_birth','Date of Birth :', ['class'=>'col-md-5'])
                                                !!}
                                                <div class="col-md-7 {{$errors->has('date_of_birth') ? 'has-error': ''}}">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('date_of_birth', isset($appData->date_of_birth) ? $appData->date_of_birth :'',['class' => 'form-control
                                                        input-sm','id'=>'date_of_birth']) !!}

                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('date_of_birth','<span
                                                        class="help-block">:message</span>') !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-md-4">
                                                        {!! Form::select('identity', ['nid'=>'National ID','passport'=>'Passport'], $appData->identity , ['class' =>
                                                           'form-control','id'=>'identity','onchange'=>'changeId(this.value)']) !!}
                                                    </div>
                                                    <div id="nid">
                                                        <div class="col-md-5">
                                                            <div class=" {{$errors->has('nid_number') ? 'has-error': ''}}"
                                                                 style="margin-bottom:8px;">
                                                                {!! Form::text('nid_number',isset($appData->nid_number) ? $appData->nid_number :'' ,['class' => 'form-control input-sm','id'=>'nid_number','placeholder'=>'Enter NID']) !!}
                                                                {!! $errors->first('nid_number','<span class="help-block">:message</span>')
                                                                !!}
                                                            </div>
                                                            <div class=" {{$errors->has('confirm_nid_number') ? 'has-error': ''}}">
                                                                {!! Form::text('confirm_nid_number',isset($appData->confirm_nid_number) ? $appData->confirm_nid_number :'' ,['class' => 'form-control input-sm','id'=>'confirm_nid_number','placeholder'=>'Confirm NID']) !!}

                                                                {!! $errors->first('confirm_nid_number','<span class="help-block">:message</span>')
                                                                !!}
                                                            </div>
                                                            {!! $errors->first('signature','<span
                                                                class="help-block">:message</span>') !!}

                                                            <span style="color: #0a6829"><small><b>* After entering the NID, press the 'Validate NID' button</b></small></span>

                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="hidden" name="nid_verified" id="nid_verified"
                                                                   value="{{isset($appData->nid_verified) ? $appData->nid_verified :''}}">
                                                            <input type="hidden" name="hidden_nid" id="hidden_nid"
                                                                   value="{{isset($appData->hidden_nid) ? $appData->hidden_nid :''}}">
                                                            <button id="validate_nid"  type="button" class="btn btn-success"><i class="fa fa-spinner fa-spin saveLoader" style="display: none;"></i> Validate NID</button>
                                                        </div>
                                                    </div>
                                                    <div id="passport"
                                                         class="col-md-6 {{$errors->has('passport') ? 'has-error': ''}}">
                                                        {!! Form::text('passport',isset($appData->passport) ? $appData->passport :'' ,['class' => 'form-control input-sm','id'=>'passport_id']) !!}
                                                        {!! $errors->first('passport','<span class="help-block">:message</span>')
                                                        !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group" id="personal_user_4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_gender','Gender :', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_gender') ? 'has-error': ''}}">
                                                {!! Form::select('applicant_gender', [''=>'Select Gender','male'=>'Male','female'=>'Female'], isset($appData->applicant_gender) ? $appData->applicant_gender :'', ['class' =>'form-control','id'=>'applicant_gender']) !!}
                                                {!! $errors->first('applicant_gender','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('photo','Photo :', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('photo') ? 'has-error': ''}}">
                                                <input type="file" name="photo" id="photo" class="form-control"
                                                       onchange="uploadDocument('preview_photo', this.id, 'validate_field_photo',1)">
                                                {!! $errors->first('photo','<span class="help-block">:message</span>')
                                                !!}
                                                <span style="color:#993333;">[N.B. Supported file extension is
                                                    pdf,png,jpg,jpeg.]</span>
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

                                                {{--                                                <input type="file" accept="image/*" onchange="loadFile(event)">--}}
                                                {{--                                                <img id="output" height="70"/>--}}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <!--/panel-body-->
                        </div>
                        <!--/panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>MAILING ADDRESS</strong></div>
                            <div class="panel-body">

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('house_no','House/Plot/Dag No', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('house_no') ? 'has-error': ''}}">
                                                {!! Form::text('house_no', $appData->house_no,['class' => 'form-control input-sm']) !!}
                                                {!! $errors->first('house_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('lane_no','LANE/Road No', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('lane_no') ? 'has-error': ''}}">
                                                {!! Form::text('lane_no', $appData->lane_no,['class' => 'form-control input-sm']) !!}
                                                {!! $errors->first('lane_no','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('section','Section', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('section') ? 'has-error': ''}}">
                                                {!! Form::text('section', $appData->section,['class' => 'form-control input-sm','id'=>'section']) !!}
                                                {!! $errors->first('section','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('block','Block', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('block') ? 'has-error': ''}}">
                                                {!! Form::text('block', $appData->block,['class' => 'form-control input-sm','id'=>'block']) !!}
                                                {!! $errors->first('block','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('district') ? 'has-error': ''}}">
                                            {!! Form::label('district','District',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('district', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md','id'=>'district']) !!}
                                                {!! $errors->first('district','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('post_code','Post Code', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('post_code') ? 'has-error': ''}}">
                                                {!! Form::text('post_code', $appData->post_code,['class' => 'form-control input-sm onlyNumber']) !!}
                                                {!! $errors->first('post_code','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('thana') ? 'has-error': ''}}">
                                            {!! Form::label('thana','Thana',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('thana', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md search-box','id'=>'thana']) !!}
                                                {!! $errors->first('district','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('email','Email', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('email') ? 'has-error': ''}}">
                                                {!! Form::text('email', $appData->email,['class' => 'form-control input-sm email'])
                                                !!}
                                                {!! $errors->first('email','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('telephone','Telephone', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('telephone') ? 'has-error': ''}}">
                                                {!! Form::text('telephone', $appData->telephone,['class' => 'form-control input-sm','id'=>'telephone']) !!}
                                                {!! $errors->first('telephone','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('mobile_no','Mobile', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('mobile_no') ? 'has-error': ''}}">
                                                {!! Form::text('mobile_no', $appData->mobile_no ,['class' => 'form-control input-sm','id'=>'mobile_no']) !!}
                                                {!! $errors->first('mobile_no','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--/panel-body-->
                        </div>
                        <!--panel-->
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>CONNECTION ADDRESS</strong></div>
                            <div class="panel-body">

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class=" col-md-12" style="font-size: 17px;">
                                            <div class="col-md-offset-2 col-md-9">
                                                {!! Form::checkbox('same_as_mailing',1,isset($appData->same_as_mailing) ? $appData->same_as_mailing :'',
                                                array('id'=>'same_as_mailing','class'=>'col-md-1'))
                                                !!}
                                                {!! Form::label('same_as_mailing','If the Mailing address and Connection address are same, please click the box', ['class'=>'col-md-11']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_house_no','House/Plot/Dag No', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_house_no') ? 'has-error': ''}}">
                                                {!! Form::text('connection_house_no', $appData->connection_house_no,['class' => 'form-control input-sm ']) !!}
                                                {!! $errors->first('connection_house_no','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_lane_no','LANE/Road No', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_lane_no') ? 'has-error': ''}}">
                                                {!! Form::text('connection_lane_no', $appData->connection_lane_no,['class' => 'form-control input-sm']) !!}
                                                {!! $errors->first('connection_lane_no','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_section','Section', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_section') ? 'has-error': ''}}">
                                                {!! Form::text('connection_section', $appData->connection_section,['class' => 'form-control
                                                input-sm','id'=>'connection_section']) !!}
                                                {!! $errors->first('connection_section','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_block','Block', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_block') ? 'has-error': ''}}">
                                                {!! Form::text('connection_block', $appData->connection_block,['class' => 'form-control input-sm']) !!}
                                                {!! $errors->first('connection_block','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('district') ? 'has-error': ''}}">
                                            {!! Form::label('connection_district','District',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('connection_district', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md','id'=>'connection_district']) !!}
                                                {!! $errors->first('connection_district','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_post_code','Post Code', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_post_code') ? 'has-error': ''}}">
                                                {!! Form::text('connection_post_code', $appData->connection_post_code,['class' => 'form-control input-sm onlyNumber']) !!}
                                                {!! $errors->first('connection_post_code','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('connection_thana') ? 'has-error': ''}}">
                                            {!! Form::label('connection_thana','Thana',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('connection_thana', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md search-box','id'=>'connection_thana']) !!}
                                                {!! $errors->first('connection_thana','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_email','Email', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_email') ? 'has-error': ''}}">
                                                {!! Form::text('connection_email', $appData->connection_email,['class' => 'form-control input-sm email'])
                                                !!}
                                                {!! $errors->first('connection_email','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_telephone','Telephone', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_telephone') ? 'has-error': ''}}">
                                                {!! Form::text('connection_telephone', $appData->connection_telephone,['class' => 'form-control
                                                input-sm','id'=>'connection_telephone']) !!}
                                                {!! $errors->first('connection_telephone','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_mobile','Mobile', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('connection_mobile') ? 'has-error': ''}}">
                                                {!! Form::text('connection_mobile', $appData->connection_mobile,['class' => 'form-control input-sm','id'=>'connection_mobile']) !!}
                                                {!! $errors->first('connection_mobile','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_area','Area', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::select('connection_area', [],'', ['placeholder' => 'Select
                                                One',
                                                'class' => 'form-control input-md search-box','id'=>'connection_area']) !!}
                                                {!! $errors->first('connection_area','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_division','Division', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::select('connection_division', [],'', ['placeholder' => 'Select
                                                One',
                                                'class' => 'form-control input-md','id'=>'connection_division']) !!}
                                                {!! $errors->first('connection_division','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="clearfix"></div>
                            </div>
                            <!--/panel-body-->
                        </div>


                    </fieldset>


                    <h3 class="text-center stepHeader">Attachments</h3>
                    <fieldset>
                        <!--/panel-->
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('NewConnectionDPDC::documents')
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
                                            'form-control required input-sm email', 'readonly']) !!}
                                            {!! $errors->first('auth_email','<span
                                                class="help-block">:message</span>') !!}
                                        </td>
                                        <td>
                                            {!! Form::label('auth_cell_number','Cell number:',
                                            ['class'=>'required-star']) !!}<br>
                                            {!! Form::text('auth_cell_number', Auth::user()->user_phone, ['class' =>
                                            'form-control input-sm required phone_or_mobile', 'readonly']) !!}
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

                    @if ($viewMode != 'on')
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="pull-left">
                                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                </div>

                                <div class="pull-left" style="padding-left: 1em;">
                                    @if($appInfo->status_id == 5)
                                        <button type="submit" id="submitForm" style="cursor: pointer;"
                                                class="btn btn-success btn-md" value="submit" name="actionBtn">Re Submit
                                        </button>
                                    @else
                                        <button type="submit" id="submitForm" style="cursor: pointer;"
                                                class="btn btn-success btn-md" value="submit" name="actionBtn"> Submit
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12 button_last">
                                <div class="clearfix"></div>
                            </div>
                        </div> {{--row--}}

                </div>

                @endif

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

        var form = $("#NewConnection").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                var val = $('#nid_verified').val();
                var valid_id = $('#identity').val();
                var hiddenNid = $('#hidden_nid').val();
                var inputnid = $("#nid_number").val();
                if ((val != '1' && valid_id == 'nid') || hiddenNid != inputnid) {
                    $("#nid_number").addClass('error');
                    $("#confirm_nid_number").addClass('error');
                    alert('Please Validate Your NID!');
                    return false;
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
            minDate: '01/01/' + (yyyy - 100)
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


        $('#applicant_passport_no').on('keyup', function () {
            var passport = $('#applicant_passport_no').val();
            if (passport !== null) {
                $("#nation_id").removeClass('required');
                $("#nation_id_label").removeClass('required-star');
            } else {
                $("#nation_id").addClass('required');
                $("#nation_id_label").addClass('required-star');
            }

        });
        $("#applicant_passport_no").trigger('keyup');

        $('#nid_number').on('keyup', function () {
            var nid_number = $("#nid_number").val()
            var ln = parseInt(nid_number.length)
            if (ln == 10 || ln == 17) {
                $("#nid_number").removeClass('error')
            } else {
                $("#nid_number").addClass('error')
                return false;
            }

        });

        $('#confirm_nid_number').on('keyup', function () {
            var nid_number = $("#nid_number").val()
            var confirm_nid_number = $("#confirm_nid_number").val()
            if (nid_number !== confirm_nid_number) {
                $("#confirm_nid_number").addClass('error');
                return false;
            }
        });

        $(document).on('click', '#validate_nid', function () {
            var nid_number = $("#nid_number").val()
            var confirm_nid_number = $("#confirm_nid_number").val()
            var ct = $("input[name='consumer_type']:checked").val()
            var _token = $('input[name="_token"]').val()
            var ln = parseInt(nid_number.length)
            if (nid_number == '') {
                $("#nid_number").addClass('error');
                return false;
            }

            if (confirm_nid_number == '') {
                $("#confirm_nid_number").addClass('error');
                return false;
            }

            $('#nid_number').trigger('keyup');
            // $('#confirm_nid_number').trigger('keyup');


            if (ct == 'P') {
                var dob = $("#date_of_birth").val()
                if (dob == '') {
                    $("#date_of_birth").addClass('error');
                    return false;
                }
            } else {
                var dob = $("#pr_date_of_birth").val()
                if (dob == '') {
                    $("#pr_date_of_birth").addClass('error');
                    return false;
                }
            }


            if (nid_number != '' && dob != '') {
                $.ajax({
                    type: "POST",
                    url: '/new-connection-dpdc/validate-nid',
                    dataType: "json",
                    data: {
                        _token: _token,
                        nid_number: nid_number,
                        dob: dob,
                    },
                    beforeSend: function () {
                        $('.saveLoader').show()
                    },
                    success: function (result) {
                        if ((typeof result.verify_nid.data.data.statusCode != "undefined") && (result.verify_nid.data.data.statusCode == 'SUCCESS')) {
                            $('#nid_verified').val('1');
                            var verifiednid = $("#nid_number").val()
                            $('#hidden_nid').val(verifiednid);
                            $('.saveLoader').hide()
                            alert('Nid Verified Successfully')
                        } else {
                            alert('Nid Not Verified')
                            $("#nid_number").val('')
                            $("#confirm_nid_number").val('')
                            $("#nid_number").addClass('error')
                            $("#confirm_nid_number").addClass('error')
                        }
                    },
                });
            }
        });

        $('#confirm_nid_number').on('keyup', function () {
            var nid = $("#nid_number").val();
            var c_nid = $("#confirm_nid_number").val();
            if (nid !== c_nid) {
                $("#confirm_nid_number").addClass('error');
            } else {
                $("#confirm_nid_number").removeClass('error');
            }
        });

        $('#nation_id').on('keyup', function () {
            var passport = $('#nation_id').val();
            if (passport !== null) {
                $("#applicant_passport_no").removeClass('required');
                $("#applicant_passport_label").removeClass('required-star');
            } else {
                $("#applicant_passport_no").addClass('required');
                $("#applicant_passport_label").addClass('required-star');
            }

        });
        $("#nation_id").trigger('keyup');


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

    });

    // Add table Row script
    function addTableRowbpdb(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length - 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var idText = 'rowCount' + tableID + rowCount;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');
        //edited by ishrat to solve select box id auto increment related bug
        for (var i = 0; i < attrSel.length; i++) {
            var nameAtt = attrSel[i].name;
            var selectId = attrSel[i].id;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            var ret = selectId.replace('_1', '');
            var repTextId = ret + '_' + rowCo;
            attrSel[i].id = repTextId;
            attrSel[i].name = repText;
        }
        attrSel.val(''); //value reset
        // end of  solving issue related select box id auto increment related bug by ishrat

        //get input elements
        var attrInput = $("#" + tableID).find('#' + idText).find('input');
        for (var i = 0; i < attrInput.length; i++) {
            var nameAtt = attrInput[i].name;
            var inputId = attrInput[i].id;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            var ret = inputId.replace('_1', '');
            var repTextId = ret + '_' + rowCo;
            attrInput[i].id = repTextId;
            attrInput[i].name = repText;
        }
        attrInput.val(''); //value reset
        //edited by ishrat to solve textarea id auto increment related bug
        //get textarea elements
        var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
        for (var i = 0; i < attrTextarea.length; i++) {
            var nameAtt = attrTextarea[i].name;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            attrTextarea[i].name = repText;
            $('#' + idText).find('.readonlyClass').prop('readonly', true);
        }
        attrTextarea.val(''); //value reset
        // end of  solving issue related textarea id auto increment related bug by ishrat
        attrSel.prop('selectedIndex', 0);
        if ((tableID === 'machinaryTbl' && templateRow === 'rowMachineCount0') || (tableID === 'machinaryTbl' && templateRow === 'rowMachineCount')) {
            $("#" + tableID).find('#' + idText).find('select.m_currency').val("107");  //selected index reset
        } else {
            attrSel.prop('selectedIndex', 0);  //selected index reset
        }
        //$('.m_currency ').prop('selectedIndex', 102);
        //Class change by btn-danger to btn-primary
        $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
        $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
        $('#' + tableID).find('tr').last().attr('data-number', rowCount);

        $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
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


    } // end of addTableRowTraHis() function

    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
        total();
    }


    function changeId(id) {
        if (id === 'nid') {
            $('#nid').show();
            $("#nid").prop('disabled', false);
            $("#passport").prop('disabled', true);
            $('#passport').hide();
        } else if (id === 'passport') {
            $('#nid').hide();
            $("#nid").prop('disabled', true);
            $("#passport").prop('disabled', false);
            $('#passport').show();
        }
    }

    application_type("{{$appData->consumer_type}}");

    function application_type(appType) {
        if (appType == 'P') {
            $("#personal_user_1").show();
            $("#personal_user_2").show();
            $("#personal_user_3").show();
            $("#personal_user_4").show();

            $("#applicant_name").prop('disabled', false);
            $("#applicant_spouse_name").prop('disabled', false);
            $("#applicant_father_name").prop('disabled', false);
            $("#applicant_mother_name").prop('disabled', false);
            $("#applicant_gender").prop('disabled', false);
            $("#date_of_birth").prop('disabled', false);

            $("#organization_1").hide();
            $("#organization_2").hide();

            $("#organization_name").prop('disabled', true);
            $("#proprietor_name").prop('disabled', true);
            $("#pr_date_of_birth").prop('disabled', true);

            $("#heading").text("PERSONAL INFORMATION");
        } else if (appType == 'O') {
            $("#personal_user_1").hide();
            $("#personal_user_2").hide();
            $("#personal_user_3").hide();
            $("#personal_user_4").hide();

            $("#applicant_name").prop('disabled', true);
            $("#applicant_spouse_name").prop('disabled', true);
            $("#applicant_father_name").prop('disabled', true);
            $("#applicant_mother_name").prop('disabled', true);
            $("#applicant_gender").prop('disabled', true);
            $("#date_of_birth").prop('disabled', true);

            $("#organization_1").show();
            $("#organization_2").show();

            $("#organization_name").prop('disabled', false);
            $("#proprietor_name").prop('disabled', false);
            $("#pr_date_of_birth").prop('disabled', false);
            $("#heading").text("ORGANIZATIONAL/INSTITUTIONAL INFORMATION");
        }
    }


    function districtcallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
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
        $('#' + calling_id).trigger('change');
    }

    function areacallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
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
        $('#' + calling_id).trigger('change');
    }

    function callbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                console.log(selected_value);
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        //alert(dependent_section_id);
        $("#" + calling_id).next().hide();
    }

    function districtCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[district_id];
                var value = row[element_name];
                // console.log(selected_value);
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
        //alert(dependent_section_id);
        $("#" + calling_id).next().hide();
        $('.search-box').select2();
    }

    function conndistrictCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[district_id];
                var value = row[element_name];
                // console.log(selected_value);
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
        $("#" + dependent_section_id).trigger('change');
        //alert(dependent_section_id);
        $("#" + calling_id).next().hide();
        $('.search-box').select2();
    }

    function areaCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                // alert(selected_value);

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
        // $("#" + dependent_section_id).trigger('change');
        $("#" + calling_id).next().hide();
    }

    function connThanaCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                // alert(selected_value);

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
        $("#" + dependent_section_id).trigger('change');
        $("#" + calling_id).next().hide();
        $('.search-box').select2();
    }

    $(document).ready(function () {
        $("#heading").text("PERSONAL INFORMATION");
        $("#passport").hide();
        $("#identity").trigger("change");

        $('#identity').on('change', function () {
            //  alert('ss');
            var valid_id = $('#identity').val();

            if (valid_id == 'passport') {
                $("#passport_id").addClass('required');
                $("#nid_number").removeClass('required');
                $("#nid_number").removeClass('error');
                $("#nid_verified").val('0');

            } else if (valid_id == 'nid') {
                $("#nid_number").addClass('required');
                $("#confirm_nid_number").addClass('required');
                $("#passport_id").removeClass('required');
                $("#passport_id").removeClass('error');
            }
        });

        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/new-connection-dpdc/get-refresh-token';

            if ($('#validate_field_photo').val() !== '') {
                $('#photo').removeClass('required error');
            }
            ;
            $('#district').keydown();

            $('#connection_district').keydown();

        });
        var document_onload_status = 1;

        $('#district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$dpdc_service_url}}/district";
            var selected_value = '{{isset($appData->district) ? $appData->district : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "DISTRICTID"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
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

            apiCallGet(e, options, apiHeaders, districtcallbackResponse, arrays);

        });

        $("#district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            // $(this).after('<span class="loading_data">Loading...</span>');
            $("#thana").html('<option value="">Please Wait...</option>');
            var district = $('#district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this);
                var api_url = "{{$dpdc_service_url}}/thanaByDistrictId?districtId" + '=' + districtId;
                var selected_value = '{{isset($appData->thana) ? $appData->thana : ''}}'; // for callback
                //alert(selected_value);
                var calling_id = $(this).attr('id');
                var dependent_section_id = "thana"; // for callback
                var element_id = "THANAID"; //dynamic id for callback
                var element_name = "NAME"; //dynamic name for callback
                var district_id = "DISTRICTID";
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

        $('#connection_district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$dpdc_service_url}}/district";
            var selected_value = '{{isset($appData->connection_district) ? $appData->connection_district : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "DISTRICTID"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
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

            apiCallGet(e, options, apiHeaders, districtcallbackResponse, arrays);

        });

        $("#connection_district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            // $(this).after('<span class="loading_data">Loading...</span>');
            $("#connection_thana").html('<option value="">Please Wait...</option>');
            var district = $('#connection_district').val();
            var districtId = district.split("@")[0];
            var checkBox = $('#same_as_mailing').is(':checked');
            var selected_value = '';
            if (checkBox == true) {
                if (document_onload_status == 1) {
                    selected_value = '{{isset($appData->thana) ? $appData->thana : ''}}';

                } else {
                    selected_value = $("#thana  ").val(); // for callback
                }
                document_onload_status = 0;
            } else {
                selected_value = '{{isset($appData->connection_thana) ? $appData->connection_thana : ''}}'; // for callback
            }


            if (districtId) {
                var e = $(this);
                var api_url = "{{$dpdc_service_url}}/thanaByDistrictId?districtId" + '=' + districtId;
                var calling_id = $(this).attr('id');
                var dependent_section_id = "connection_thana"; // for callback
                var element_id = "THANAID"; //dynamic id for callback
                var element_name = "NAME"; //dynamic name for callback
                var district_id = "DISTRICTID";
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
                apiCallGet(e, options, apiHeaders, conndistrictCallbackResponseDependentSelect, arrays);
                $("#connection_thana").trigger('change');
                // alert(3);

            } else {
                $("#connection_thana").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        });

        $("#connection_thana").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            // $(this).after('<span class="loading_data">Loading...</span>');
            // $("#connection_area").html('<option value="">Please Wait...</option>');
            var conn_thana = $("#connection_thana").val();
            var conn_thana_id = conn_thana.split("@")[0];

            // alert(conn_thana_id)
            if (conn_thana_id) {
                var e = $(this);
                var api_url = "{{$dpdc_service_url}}/areaByThanaId?thanaid" + '=' + conn_thana_id;
                var selected_value = '{{isset($appData->connection_area) ? $appData->connection_area : ''}}';
                //alert(selected_value)// for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "connection_area"; // for callback
                var element_id = "AREAID"; //dynamic id for callback
                var element_name = "NAME"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

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
                apiCallGet(e, options, apiHeaders, connThanaCallbackResponseDependentSelect, arrays);

            } else {
                $("#connection_division").html('<option value="">Select Area First</option>');
                $(self).next().hide();
            }

        });

        $("#connection_area").on("change", function () {
            // alert('ss');
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#connection_division").html('<option value="">Please Wait...</option>');
            var area = $('#connection_area').val();
            var areaId = area.split("@")[0];
            if (areaId) {
                var e = $(this);
                var api_url = "{{$dpdc_service_url}}/divisionByAreaId?areaid" + '=' + areaId;
                var selected_value = '{{isset($appData->connection_division) ? $appData->connection_division : ''}}'; // for callback
                // alert(selected_value)
                var calling_id = $(this).attr('id');
                var dependent_section_id = "connection_division"; // for callback
                var element_id = "DIVISIONID"; //dynamic id for callback
                var element_name = "NAME"; //dynamic name for callback
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

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
                apiCallGet(e, options, apiHeaders, areaCallbackResponseDependentSelect, arrays);

            } else {
                $("#connection_division").html('<option value="">Select Area First</option>');
                $(self).next().hide();
            }

        });

        $(document).on('focusout', '#confirm_nid_number', function () {
            var nid = $("#nid_number").val();
            var c_nid = $("#confirm_nid_number").val();
            if (nid !== c_nid) {
                $("#confirm_nid_number").addClass('error');
            } else {
                $("#confirm_nid_number").removeClass('error');
            }
        });


    });

    $(document).on('change', '#same_as_mailing', function () {
        //  var checkBox = document.getElementById("same_as_mailing");
        var checkBox = $('#same_as_mailing').is(':checked');
        var house = $("#house_no").val();
        var section = $("#section").val();
        var lane = $("#lane_no").val();
        var block = $("#block").val();
        var postCode = $("#post_code").val();
        var telephone = $("#telephone").val();
        var mobile = $("#mobile_no").val();
        var email = $("#email").val();
        var district = $("#district").val();

        if (checkBox == true) {

            $("#connection_house_no").val(house);
            $("#connection_section").val(section);
            $("#connection_lane_no").val(lane);
            $("#connection_block").val(block);
            $("#connection_post_code").val(postCode);
            $("#connection_mobile").val(mobile);
            $("#connection_telephone").val(telephone);
            $("#connection_email").val(email);
            $("#connection_district").val(district);
            $("#connection_thana").val(district);
            $("#connection_district").trigger("change");
            //    alert($("#connection_thana").val());

        } else {
            $("#connection_house_no").val('');
            $("#connection_section").val('');
            $("#connection_lane_no").val('');
            $("#connection_block").val('');
            $("#connection_post_code").val('');
            $("#connection_mobile").val('');
            $("#connection_email").val('');
            $("#connection_telephone").val('');
            $("#connection_district").val('');
            $("#connection_thana").val('');
            $("#connection_district").trigger("keydown");
        }

    });


    var loadFile = function (event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('output');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    /*document upload start*/

    function uploadDocument(targets, id, vField, isRequired) {
        var inputFile = $("#" + id).val();
        alert(inputFile);
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
            var action = "{{URL::to('/new-connection-dpdc/upload-document')}}";
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

</script>