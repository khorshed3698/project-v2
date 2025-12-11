<?php
$accessMode = ACL::getAccsessRight('NewConnectionWZPDCL');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ url("assets/plugins/select2.min.css") }}">
<script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
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
                        <h5><strong>Application For New Connection (WZPDCL)</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'new-connection-wzpdcl/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                           id="app_id"/>
                    <h3 class="text-center stepHeader"> General Information</h3>
                    <fieldset>
                        @if($appInfo->status_id == 5)
                            <div class="alert alert-danger">
                                <strong>{{$appInfo->reject_or_shortfall_comment}}</strong>
                            </div>
                        @endif
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Personal Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group ">
                                    <div class="row ">
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_name','Applicant\'s Name :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('applicant_name', !empty($appData->applicant_name) ? $appData->applicant_name : '', ['class' => 'form-control input-md form-control name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('name_in_bengali','Name (in Bengali) :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('name_in_bengali',!empty($appData->name_in_bengali) ? $appData->name_in_bengali : '', ['class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('father_name','Father\'s Name :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('father_name', !empty($appData->father_name) ? $appData->father_name : '', ['class' => 'form-control input-md name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('mother_name','Mother\'s Name :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mother_name', !empty($appData->mother_name) ? $appData->mother_name : '', ['class' => 'form-control input-md name','id'=>'mother_name']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            {!! Form::label('spouse_name','Spouse Name :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('spouse_name', !empty($appData->spouse_name) ? $appData->spouse_name : '', ['class' => 'form-control input-md name','id'=>'spouse_name']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('applicant_gender','Gender :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::select('applicant_gender', [], '', ['class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            {!! Form::label('national_id','National ID :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('national_id', !empty($appData->national_id) ? $appData->national_id : '', ['class' => 'form-control input-md onlyNumber' ,'id'=>'national_id']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            {!! Form::label('applicant_passport','Passport :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('applicant_passport', !empty($appData->applicant_passport) ? $appData->applicant_passport : '', ['class' => 'form-control input-md ','id'=>'applicant_passport']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_mobile','Mobile :',  ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('applicant_mobile',  !empty($appData->applicant_mobile) ? $appData->applicant_mobile : '', ['class' => 'form-control input-md onlyNumber mobile','id'=>'applicant_mobile']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_dob','Date of Birth :', ['class' => 'col-md-5 col-xs-12']) !!}

                                            <div class=" col-md-7 col-xs-12">
                                                <div class="datepickerDob input-group date"
                                                     data-date-format="dd-mm-yyyy">
                                                    {!! Form::text('applicant_dob', !empty($appData->applicant_dob) ? $appData->applicant_dob : '', ['class'=>'form-control input-md', 'id' => 'applicant_dob', 'placeholder'=>'Date of Birth','id'=>'applicant_dob']) !!}
                                                    <span class="input-group-addon"><span
                                                                class="fa fa-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('signature','Signature :', ['class'=>'col-md-5 col-xs-12 required-star']) !!}
                                            <div class="col-md-7 col-xs-12 ">
                                                {!! Form::file('signature', ['class'=>'form-control input-md '.!empty($appData->validate_field_signature) ? '' : 'required','flag'=>'img','id' => 'signature','onchange'=>"uploadDocument('preview_signature', this.id, 'validate_field_signature',1) , imagePreview(this)"]) !!}
                                                <span id="span_signature" style="font-size: 12px; font-weight: bold;color:#993333">*** Maximum file size 150kb and file extension should be only jpg/jpeg/png</span>
                                                <input type="hidden" id="old_image_signature"
                                                       data-img="{{!empty($appData->validate_field_signature) ? URL::to('/uploads/'.$appData->validate_field_signature) : (url('assets/images/no-image.png'))}}"
                                                       value="{{!empty($appData->validate_field_signature) ? $appData->validate_field_signature : ''}}">
                                                <div id="preview_signature">
                                                    {!! Form::hidden('validate_field_signature',!empty($appData->validate_field_signature) ? $appData->validate_field_signature : '', ['class'=>'form-control input-md', 'id' => 'validate_field_signature','data-img'=>!empty($appData->validate_field_signature) ? $appData->validate_field_signature : '']) !!}
                                                </div>
                                                <div class="col-md-5" style="position:relative;">
                                                    <img id="photo_viewer_signature"
                                                         style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                         src="{{!empty($appData->validate_field_signature) ? URL::to('/uploads/'.$appData->validate_field_signature) : (url('assets/images/no-image.png'))}}"

                                                         alt="signature">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('photo','Photo :', ['class'=>'col-md-5 col-xs-12 required-star']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::file('photo', ['class'=>'form-control input-md '.!empty($appData->validate_field_photo) ? '' : 'required', 'id' => 'photo','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                                <span id="span_photo"
                                                      style="font-size: 12px; font-weight: bold;color:#993333">*** Maximum file size 150kb and file extension should be only jpg/jpeg/png</span>
                                                <input type="hidden" id="old_image_photo"
                                                       data-img="{{!empty($appData->validate_field_photo) ? URL::to('/uploads/'.$appData->validate_field_photo) : (url('assets/images/no-image.png'))}}"
                                                       value="{{!empty($appData->validate_field_photo) ? $appData->validate_field_photo : ''}}">

                                                <div id="preview_photo">
                                                    {!! Form::hidden('validate_field_photo',!empty($appData->validate_field_photo) ? $appData->validate_field_photo : '', ['class'=>'form-control input-md', 'id' => 'validate_field_photo']) !!}
                                                </div>
                                                <div class="col-md-5" style="position:relative;">
                                                    <img id="photo_viewer_photo"
                                                         style="width:auto;height:70px;border:1px solid #ddd;padding:2px;"
                                                         src="{{!empty($appData->validate_field_photo) ? URL::to('/uploads/'.$appData->validate_field_photo) : (url('assets/images/no-image.png'))}}"
                                                         data-img="{{!empty($appData->validate_field_photo) ? URL::to('/uploads/'.$appData->validate_field_photo) : (url('assets/images/no-image.png'))}}"
                                                         alt="photo">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong style="padding-top: 10px;">Mailing Address </strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('mail_house_no','House/Plot/Dag No :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mail_house_no', !empty($appData->mail_house_no) ? $appData->mail_house_no : '', ['class' => 'form-control input-md','id'=>'mail_house_no']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('mail_road_no','LANE/Road No :',  ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mail_road_no', !empty($appData->mail_road_no) ? $appData->mail_road_no : '', ['class' => 'form-control input-md','id'=>'mail_road_no']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('mail_section','Section :',  ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mail_section', !empty($appData->mail_section) ? $appData->mail_section : '', ['class' => 'form-control input-md','id'=>'mail_section']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('mail_block','Block :',  ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mail_block', !empty($appData->mail_block) ? $appData->mail_block : '', ['class' => 'form-control input-md','id'=>'mail_block']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            {!! Form::label('mail_district :','Select District', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::select('mail_district', [], '', ['class' => 'form-control input-md','id'=>'mail_district','placeholder'=>'Select from here']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            {!! Form::label('mail_post_code','Post Code :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mail_post_code', !empty($appData->mail_post_code) ? $appData->mail_post_code : '', ['class' => 'form-control input-md onlyNumber','id'=>'mail_post_code']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('mail_thana','Select Thana :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::select('mail_thana', [], '', ['class' => 'form-control input-md','id'=>'mail_thana','placeholder'=>'Select District first']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('mailing_email','Email :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mailing_email', !empty($appData->mailing_email) ? $appData->mailing_email : '', ['class' => 'form-control email input-md','id'=>'mailing_email']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('mail_telephone','Telephone :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('mail_telephone',  !empty($appData->mail_telephone) ? $appData->mail_telephone : '', ['class' => 'form-control input-md onlyNumber telephone','id'=>'mail_telephone']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong style="padding-top: 10px;">Connection Address </strong>
                            </div>
                            <div class="panel-body">
                                <fieldset>
                                    <div class="form-group" style="">
                                        <div class="row">
                                            <div class=" col-md-12" style="font-size: 17px; margin-bottom: 15px;">
                                                <div class="col-md-6">
                                                    {!! Form::checkbox('same_as_mailing',1,!empty($appData->same_as_mailing) ? $appData->same_as_mailing : '',
                                                    array('id'=>'same_as_mailing'))
                                                    !!} Same as Mailing Address
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('conn_house_no','House/Plot/Dag No :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_house_no', !empty($appData->conn_house_no) ? $appData->conn_house_no : '', ['class' => 'form-control input-md','id'=>'conn_house_no']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('conn_road_no','LANE/Road No :',  ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_road_no', !empty($appData->conn_road_no) ? $appData->conn_road_no : '', ['class' => 'form-control input-md','id'=>'conn_road_no']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('conn_section','Section :',  ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_section', !empty($appData->conn_section) ? $appData->conn_section : '', ['class' => 'form-control input-md','id'=>'conn_section']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('conn_block','Block :',  ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_block', !empty($appData->conn_block) ? $appData->conn_block : '', ['class' => 'form-control input-md','id'=>'conn_block']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                {!! Form::label('conn_district :','Select District', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::select('conn_district', [], '', ['class' => 'form-control input-md','id'=>'conn_district','placeholder'=>'Select from here']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                {!! Form::label('conn_post_code','Post Code :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_post_code', !empty($appData->conn_post_code) ? $appData->conn_post_code : '', ['class' => 'form-control input-md onlyNumber','id'=>'conn_post_code']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('conn_thana','Select Thana :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::select('conn_thana', [], '', ['class' => 'form-control input-md','placeholder'=>'Select District first']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('conn_email','Email :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_email', !empty($appData->conn_email) ? $appData->conn_email : '', ['class' => 'form-control email input-md','id'=>'conn_email']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('conn_telephone','Telephone :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_telephone',  !empty($appData->conn_telephone) ? $appData->conn_telephone : '', ['class' => 'form-control input-md onlyNumber telephone','id'=>'conn_telephone']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('conn_mobile','Mobile :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::text('conn_mobile',!empty($appData->conn_mobile) ? $appData->conn_mobile : '', ['class' => 'form-control input-md mobile onlyNumber','id'=>'conn_mobile']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                {!! Form::label('conn_zone :','Select Zone :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::select('conn_zone', [], '', ['class' => 'form-control input-md','id'=>'conn_zone','placeholder'=>'Select from here']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                {!! Form::label('conn_divison :','Select Division :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::select('conn_divison', [], '', ['class' => 'form-control input-md docloader','id'=>'conn_divison','placeholder'=>'Select from here']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                {!! Form::label('conn_area :','Select Area :', ['class' => 'col-md-5 col-xs-12']) !!}
                                                <div class="col-md-7 col-xs-12">
                                                    {!! Form::select('conn_area', [], 'S', ['class' => 'form-control input-md','id'=>'conn_area','placeholder'=>'Select Division First']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                </fieldset>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong style="padding-top: 10px;">Description of
                                    Connection </strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_type','Connection Type :', ['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::select('connection_type', [], '', ['class' => 'form-control input-md docloader','id'=>'connection_type','placeholder'=>'Select from here']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('phase','Phase :',['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::select('phase', [], '', ['class' => 'form-control input-md docloader','id'=>'phase','placeholder'=>'Select from here']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('category','Select Category :',['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::select('category', [], '', ['class' => 'form-control input-md docloader','id'=>'category','placeholder'=>'Select from here']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('no_of_meter','No. of Meter :',['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('no_of_meter', '1', ['class' => 'form-control input-md ','value'=>'1', 'id'=>'no_of_meter','readonly']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('org_or_shop_name','Organization/Shop Name :',['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('org_or_shop_name',!empty($appData->org_or_shop_name) ? $appData->org_or_shop_name : '', ['class' => 'form-control input-md','id'=>'org_or_shop_name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('demand_load','Demand Load per Meter in Kilowatt :',['class' => 'col-md-5 col-xs-12']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::text('demand_load', !empty($appData->demand_load) ? $appData->demand_load : '', ['class' => 'form-control input-md ','id'=>'demand_load']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    {{--Attachments--}}
                    <h3 class="stepHeader">Attachments</h3>
                    <fieldset>
                        <div id="docListDiv">
                            @include('NewConnectionWZPDCL::documents')
                        </div>
                    </fieldset>

                    <h3 class="stepHeader">Declaration</h3>
                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Declaration & Submit</strong>
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
                                    <button type="submit" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn" id="save_as_draft">Save as Draft
                                    </button>
                                </div>
                            @endif
                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md"
                                        value="Submit" name="actionBtn">
                                    @if($appInfo->status_id == 5)
                                        Re Submit
                                    @else
                                        Submit
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>


                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
<script>

    $(document).ready(function () {
        $(function () {
            $('.mobile').trigger('blur')
            $('#national_id').trigger('blur')
        })
        $(document).on('blur', '.mobile', function () {
            var mobile = $(this).val()
            if (mobile) {
                var san_mob = mobile.replace('"', "").replace("-", "").replace("+88", "");
                $(this).val(san_mob)
                if (san_mob.length !== 11) {
                    $(this).addClass('error');
                    return false;
                } else {
                    $(this).removeClass('error');
                    return true;
                }
            }
        })

        $(document).on('blur', '.telephone', function () {
            var telephone = $(this).val()
            if (telephone) {
                if (telephone.length > 11 || telephone.length < 7) {
                    $(this).addClass('error');
                    return false;
                } else {
                    $(this).removeClass('error');
                    return true;
                }
            }
        });


        $(document).on('blur', '.name', function () {
            var name = $(this).val()
            if (name) {
                if (name.length > 100 || name.length < 3) {
                    $(this).addClass('error');
                    return false;
                } else {
                    $(this).removeClass('error');
                    return true;
                }
            }
        });

        $(document).on('keydown', '.mobile', function () {
            var mobile = $(this).val();
            var reg = /^01/;
            if (mobile) {
                if (mobile.length === 2) {
                    if (reg.test(mobile)) {
                        $(this).removeClass('error');
                        return true;
                    } else {
                        $(this).addClass('error')
                        $(this).val('')
                        return false;
                    }
                }
            }


        });

        $(document).on('blur', '#national_id', function () {
            var nid_val = $('#national_id').val()
            var nid = nid_val.length
            if (nid_val) {
                if (nid == 10 || nid == 13 || nid == 17) {
                    $('#national_id').removeClass('error')
                } else {
                    $('#national_id').addClass('error')
                    $('#national_id').val('')
                }
            }
        })


        var form = $("#NewConnection").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
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

    /* Get list from API end */

    $(document).ready(function () {
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });
        $(function () {
            token = "{{$token}}"
            tokenUrl = '/new-connection-wzpdcl/get-refresh-token'

            $('#connection_type').select2()
            $('#phase').select2()
            $('#mail_district').select2()
            $('#conn_district').select2()
            $('#conn_zone').select2()
            $('#conn_divison').select2()
            $('#category').select2()

            $('#applicant_gender').keydown()
            $('#connection_type').keydown()
            $('#phase').keydown()
            $('#mail_district').keydown()
            $('#conn_district').keydown()
            $('#conn_zone').keydown()
            $('#conn_divison').keydown()
            $('#category').keydown()
        });

        $('#applicant_gender').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/genders";
            let selected_value = '{{isset($appData->applicant_gender) ? $appData->applicant_gender : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "value"; //dynamic id for callback
            let element_name = "text"; //dynamic name for callback
            let data = null;
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
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

        })

        $('#connection_type').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/connection-types";
            let selected_value = '{{isset($appData->connection_type) ? $appData->connection_type : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "value"; //dynamic id for callback
            let element_name = "text"; //dynamic name for callback
            let data = null;
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
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

        })

        $('#phase').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/phases";
            let selected_value = '{{isset($appData->phase) ? $appData->phase : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "value"; //dynamic id for callback
            let element_name = "text"; //dynamic name for callback
            let data = null;
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
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

        })

        $('#mail_district').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/districts";
            let selected_value = '{{isset($appData->mail_district) ? $appData->mail_district : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "value"; //dynamic id for callback
            let element_name = "text"; //dynamic name for callback
            let data = null;
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
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

        })

        $("#mail_district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#mail_thana").html('<option value="">Please Wait...</option>');
            var district = $('#mail_district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this)
                var api_url = "{{$wzpdcl_service_url}}/thana-list-by-district-id/" + districtId
                var calling_id = $(this).attr('id')
                var selected_value = '{{isset($appData->mail_thana) ? $appData->mail_thana : ''}}'
                var dependent_section_id = "mail_thana" // for callback
                var element_id = "Id" //dynamic id for callback
                var element_name = "ThanaName" //dynamic name for callback
                var district_id = "DistId"
                var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id]

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
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#mail_thana").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        });

        $('#conn_district').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/districts";
            let selected_value = '{{isset($appData->conn_district) ? $appData->conn_district : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "value"; //dynamic id for callback
            let element_name = "text"; //dynamic name for callback
            let data = null;
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
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

        })

        $("#conn_district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#conn_thana").html('<option value="">Please Wait...</option>');
            var district = $('#conn_district').val();
            var districtId = district.split("@")[0];
            var checkBox = $('#same_as_mailing').is(':checked');
            var selected_value = '{{isset($appData->conn_thana) ? $appData->conn_thana : ''}}'
            if (checkBox == true) {
                if ($("#mail_thana").val() !== '') {
                    selected_value = $("#mail_thana").val();
                }
                // for callback
            }
            if (districtId) {
                var e = $(this);
                var api_url = "{{$wzpdcl_service_url}}/thana-list-by-district-id/" + districtId;
                var calling_id = $(this).attr('id');
                var dependent_section_id = "conn_thana"; // for callback
                var element_id = "Id"; //dynamic id for callback
                var element_name = "ThanaName"; //dynamic name for callback
                var district_id = "DistId";
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
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#conn_thana").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        })

        $('#conn_zone').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/zones";
            let selected_value = '{{isset($appData->conn_zone) ? $appData->conn_zone : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "value"; //dynamic id for callback
            let element_name = "text"; //dynamic name for callback
            let data = null;
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
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

        })

        $('#conn_divison').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/divisions";
            let selected_value = '{{isset($appData->conn_divison) ? $appData->conn_divison : ''}}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "ID"; //dynamic id for callback
            let element_name = "Name"; //dynamic name for callback
            let data = "blcode";
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
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

        })

        $("#conn_divison").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#conn_area").html('<option value="">Please Wait...</option>');
            var division = $('#conn_divison').val();
            var divisionId = division.split("@")[0];
            if (divisionId) {
                var e = $(this);
                var api_url = "{{$wzpdcl_service_url}}/area-list-by-division-id/" + divisionId;
                var selected_value = '{{isset($appData->conn_area) ? $appData->conn_area : ''}}'; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "conn_area"; // for callback
                var element_id = "Id"; //dynamic id for callback
                var element_name = "Name"; //dynamic name for callback
                var district_id = "DivisionId";
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
                apiCallGet(e, options, apiHeaders, dependantCallbackResponseDependentSelect, arrays);

            } else {
                $("#conn_area").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        })

        $('#category').on('keydown', function (el) {
            let key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this)
            let api_url = "{{$wzpdcl_service_url}}/catagories";
            let selected_value = '{{isset($appData->category) ? str_replace('&amp;', '&', $appData->category) : ''}}'; // for callback
            if (selected_value != '') {
                selected_value = selected_value.replace('&amp;', '&')
            }
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "ID"; //dynamic id for callback
            let element_name = "Name"; //dynamic name for callback
            let data = "Tariff";
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name, data]; // for callback

            let apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, categoryCallbackResponse, arrays);

        })

        $('#same_as_mailing').on('change', function () {
            let checkBox = $('#same_as_mailing').is(':checked');
            let mail_house_no = $("#mail_house_no").val()
            let mail_road_no = $("#mail_road_no").val()
            let mail_section = $("#mail_section").val()
            let mail_block = $("#mail_block").val()
            let mail_district = $("#mail_district").val()
            let mail_post_code = $("#mail_post_code").val()
            let mailing_email = $("#mailing_email").val()
            let mail_telephone = $("#mail_telephone").val()
            if (checkBox == true) {
                $("#conn_house_no").val(mail_house_no)
                $("#conn_road_no").val(mail_road_no)
                $("#conn_section").val(mail_section)
                $("#conn_block").val(mail_block)
                $("#conn_district").val(mail_district)
                $("#conn_district").trigger('change')
                $("#conn_post_code").val(mail_post_code)
                $("#conn_email").val(mailing_email)
                $("#conn_telephone").val(mail_telephone)
            } else {
                $("#conn_house_no").val('')
                $("#conn_road_no").val('')
                $("#conn_section").val('')
                $("#conn_block").val('')
                $("#conn_district").val('')
                $("#conn_district").trigger('change')
                $("#conn_post_code").val('')
                $("#conn_email").val('')
                $("#conn_telephone").val('')
            }
        })

    })

    function dependantCallbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id, district_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name] + '@' + row[district_id]
                var value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
    }

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>'
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                if (data == '' || data == null) {
                    var id = row[element_id] + '@' + row[element_name]
                } else {
                    var id = row[element_id] + '@' + row[data] + '@' + row[element_name]
                }
                var value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
        $("#" + calling_id).trigger('change')
    }

    function categoryCallbackResponse(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {

            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[data] + '@' + row[element_name];
                var value = row[element_name] + ' (' + row[data] + ')';
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
        $("#" + calling_id).trigger('change')
    }


</script>