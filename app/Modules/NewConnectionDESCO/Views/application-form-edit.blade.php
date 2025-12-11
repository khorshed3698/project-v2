<?php
$accessMode = ACL::getAccsessRight('NewConnectionDESCO');
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
                        <h5><strong>Application For New Connection (DESCO)</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'new-connection-desco/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection', 'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                           id="app_id"/>

                    <h3 class="text-center stepHeader"> General Information</h3>
                    <fieldset>
                        @if($appInfo->status_id == 6)
                            <div class="alert alert-info">
                                {{$appInfo->shortfall_message}}
                            </div>
                        @endif

                        <div class="form-group" style="margin: 15px;">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    {!! Form::label('application_type','Application Type :',['class'=>'text-left col-md-4','style'=>'margin-top:5px;']) !!}
                                    <div class="col-md-8">
                                        {!! Form::select('application_type', [], '', ['class' =>'form-control','placeholder'=>'Select from here','id'=>'application_type']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary" id="organization_panel">
                            <div class="panel-heading"><strong>Details of Organization</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('authorized_person','Authorized Person :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('authorized_person') ? 'has-error': ''}}">
                                                {!! Form::text('authorized_person',!empty($appData->authorized_person) ? $appData->authorized_person : '', ['placeholder' => 'Authorized Person',
                                               'class' => 'form-control input-md','id'=>'authorized_person']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('designation_authorized','Designation of Authorized Person :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('designation_authorized') ? 'has-error': ''}}">
                                                {!! Form::text('designation_authorized',!empty($appData->designation_authorized) ? $appData->designation_authorized : '', ['placeholder' => 'Designation of Authorized Person',
                                               'class' => 'form-control input-md','id'=>'designation_authorized']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('organization_name','Organization Name :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('organization_name') ? 'has-error': ''}}">
                                                {!! Form::text('organization_name',!empty($appData->organization_name) ? $appData->organization_name : '', ['placeholder' => 'Organization Name',
                                               'class' => 'form-control input-md','id'=>'organization_name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('organization_type','Organization Type:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('organization_type') ? 'has-error': ''}}">
                                                {!! Form::select('organization_type',[],'', ['placeholder' => 'Select from here',
                                               'class' => 'form-control input-md','id'=>'organization_type']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="div_ministry">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('ministry','Ministry :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('ministry') ? 'has-error': ''}}">
                                                {!! Form::select('ministry',[],'', ['placeholder' => 'Select from here ',
                                               'class' => 'form-control input-md','id'=>'ministry']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Description of Connection Place</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('house_dag_no','House/Dag Number :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('house_dag_no') ? 'has-error': ''}}">
                                                {!! Form::text('house_dag_no',!empty($appData->house_dag_no) ? $appData->house_dag_no : '', ['placeholder' => 'House/Dag Number',
                                               'class' => 'form-control input-md','id'=>'house_dag_no']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('plot_number','Plot Number :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('plot_number') ? 'has-error': ''}}">
                                                {!! Form::text('plot_number',!empty($appData->plot_number) ? $appData->plot_number : '', ['placeholder' => 'Plot Number',
                                               'class' => 'form-control input-md','id'=>'plot_number']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('av_lane_number','AV/LANE/Road Number :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('av_lane_number') ? 'has-error': ''}}">
                                                {!! Form::text('av_lane_number',!empty($appData->av_lane_number) ? $appData->av_lane_number : '', ['placeholder' => 'AV/LANE/Road Number ',
                                               'class' => 'form-control input-md onlyNumber','id'=>'av_lane_number']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('block_number','Block Number :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('block_number') ? 'has-error': ''}}">
                                                {!! Form::text('block_number',!empty($appData->block_number) ? $appData->block_number : '', ['placeholder' => 'Block Number',
                                               'class' => 'form-control input-md','id'=>'block_number']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('thana','Thana :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('thana') ? 'has-error': ''}}">
                                                {!! Form::select('thana',[],'', ['class' => 'form-control input-md','id'=>'thana']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('section','Section:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('mother_name') ? 'has-error': ''}}">
                                                {!! Form::text('section',!empty($appData->section) ? $appData->section : '', ['placeholder' => 'Section',
                                               'class' => 'form-control input-md','id'=>'section']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('area','Area :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('area') ? 'has-error': ''}}">
                                                {!! Form::select('area',[],'', ['class' => 'form-control input-md','id'=>'area']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('snd','S&D:',['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('snd') ? 'has-error': ''}}">
                                                {!! Form::select('snd',[],'', ['class' => 'form-control input-md','id'=>'snd']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('wiring_inspector','Wiring Inspector :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('wiring_inspector') ? 'has-error': ''}}">
                                                {!! Form::select('wiring_inspector',[],'', ['placeholder' => 'Select from here',
                                               'class' => 'form-control input-md','id'=>'wiring_inspector']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('post_office','Post Office:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('bin') ? 'has-error': ''}}">
                                                {!! Form::text('post_office',!empty($appData->post_office) ? $appData->post_office : '', ['placeholder' => 'Post Office',
                                               'class' => 'form-control input-md','id'=>'post_office']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Possession Owner</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('land_owner_name','Land Owner Name :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('land_owner_name') ? 'has-error': ''}}">
                                                {!! Form::text('land_owner_name',!empty($appData->land_owner_name) ? $appData->land_owner_name : '', ['placeholder' => 'Land Owner Name ',
                                               'class' => 'form-control input-md','id'=>'land_owner_name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('land_owner_father_name','Land Owner\'s Father Name :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('land_owner_father_name') ? 'has-error': ''}}">
                                                {!! Form::text('land_owner_father_name',!empty($appData->land_owner_father_name) ? $appData->land_owner_father_name : '', ['placeholder' => 'Land Owner\'s Father Name',
                                               'class' => 'form-control input-md','id'=>'land_owner_father_name']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Basic Information</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('nid_number','NID :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('nid_number') ? 'has-error': ''}}">
                                                {!! Form::text('nid_number',!empty($appData->nid_number) ? $appData->nid_number : '', ['placeholder' => 'NID',
                                               'class' => 'form-control input-md onlyNumber','id'=>'nid_number',!empty($appData->nid_verification_status) &&$appData->nid_verification_status ==1 ?'readonly':'']) !!}
                                                {!! $errors->first('nid_number','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            {!! Form::label('applicant_dob','Date of Birth:',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="datepickerDob col-md-7 {{$errors->has('applicant_dob') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_dob', '',['class' => 'form-control
                                                input-md','id'=>'applicant_dob','readonly','style'=>'background:white;',!empty($appData->nid_verification_status) &&$appData->nid_verification_status ==1 ?'readonly':'']) !!}
                                                {!! $errors->first('applicant_dob','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <input type="hidden" name="nid_verification_status"
                                                   id="nid_verification_status"
                                                   value="{{!empty($appData->nid_verification_status)?$appData->nid_verification_status:0}}">

                                            <button id="validate_nid"  type="button" class="btn btn-success" style="{{!empty($appData->nid_verification_status) &&$appData->nid_verification_status ==1 ?'display:none':''}}"><i class="fa fa-spinner fa-spin saveLoader" style="display: none;" ></i> Validate NID</button>
                                            <button id="edit_nid"  type="button" class="btn btn-primary" style="{{!empty($appData->nid_verification_status) &&$appData->nid_verification_status ==1 ?'':'display:none'}}"><i class="fa fa-edit"></i> Edit</button>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('title_of_connection','Title of the connection :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('title_of_connection',!empty($appData->title_of_connection) ? $appData->title_of_connection : '', ['placeholder' => 'Title of the connection',
                                               'class' => 'form-control input-md','id'=>'title_of_connection']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('applicant_name','Name :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('applicant_name') ? 'has-error': ''}}">
                                                {!! Form::text('applicant_name',!empty($appData->applicant_name) ? $appData->applicant_name : '', ['placeholder' => 'Name',
                                               'class' => 'form-control input-md','id'=>'applicant_name',!empty($appData->nid_verification_status) &&$appData->nid_verification_status ==1 ?'readonly':'']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('father_name','Father\'s Name :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('father_name') ? 'has-error': ''}}">
                                                {!! Form::text('father_name',!empty($appData->father_name) ? $appData->father_name : '', ['placeholder' => 'Father Name',
                                               'class' => 'form-control input-md','id'=>'father_name']) !!}
                                                {!! $errors->first('father_name','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('mother_name','Mother\'s Name :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('mother_name') ? 'has-error': ''}}">
                                                {!! Form::text('mother_name',!empty($appData->mother_name) ? $appData->mother_name : '', ['placeholder' => 'Mother Name',
                                               'class' => 'form-control input-md','id'=>'mother_name']) !!}
                                                {!! $errors->first('applicant_name','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('spouse_name','Spouse Name :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('spouse_name') ? 'has-error': ''}}">
                                                {!! Form::text('spouse_name',!empty($appData->spouse_name) ? $appData->spouse_name : '', ['placeholder' => 'Spouse Name',
                                               'class' => 'form-control input-md','id'=>'spouse_name']) !!}
                                                {!! $errors->first('spouse_name','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('gender','Gender :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('gender') ? 'has-error': ''}}">
                                                {!! Form::select('gender',['1@Male'=>'Male','2@Female'=>'Female'],!empty($appData->gender) ? $appData->gender : '', ['placeholder' => 'Select',
                                               'class' => 'form-control input-md','id'=>'gender']) !!}
                                                {!! $errors->first('gender','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('bin','BIN (Business Identification Number):',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('bin') ? 'has-error': ''}}">
                                                {!! Form::text('bin',!empty($appData->bin) ? $appData->bin : '', ['placeholder' => 'BIN',
                                               'class' => 'form-control input-md onlyNumber','id'=>'bin']) !!}
                                                {!! $errors->first('bin','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('etin','E-TIN :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('etin') ? 'has-error': ''}}">
                                                {!! Form::text('etin',!empty($appData->etin) ? $appData->etin : '', ['placeholder' => 'E-TIN',
                                               'class' => 'form-control input-md onlyNumber','id'=>'etin']) !!}
                                                {!! $errors->first('etin','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('trade_license','Trade License No. :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('trade_license') ? 'has-error': ''}}">
                                                {!! Form::text('trade_license',!empty($appData->trade_license) ? $appData->trade_license : '', ['placeholder' => 'Trade License',
                                               'class' => 'form-control input-md','id'=>'trade_license']) !!}
                                                {!! $errors->first('trade_license','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('photo','Applicant Photo :', ['class'=>'col-md-5 col-xs-12 required-star']) !!}
                                            <div class="col-md-7 col-xs-12">
                                                {!! Form::file('photo', ['class'=>'form-control input-md '.!empty($appData->validate_field_photo) ? '' : 'required', 'id' => 'photo','flag'=>'img','onchange'=>"uploadDocument('preview_photo', this.id, 'validate_field_photo',1), imagePreview(this)"]) !!}
                                                <span id="span_photo"
                                                      style="font-size: 12px; font-weight: bold;color:#993333">[N.B. Supported file extension is png/jpg/jpeg.Max size less than 150KB]</span>
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
                                        <div class="col-md-6">
                                            {!! Form::label('signature','Applicant Signature :', ['class'=>'col-md-5 col-xs-12 required-star']) !!}
                                            <div class="col-md-7 col-xs-12 ">
                                                {!! Form::file('signature', ['class'=>'form-control input-md'.!empty($appData->validate_field_signature) ? '' : 'required','flag'=>'img','id' => 'signature','onchange'=>"uploadDocument('preview_signature', this.id, 'validate_field_signature',1) , imagePreview(this)"]) !!}
                                                <span id="span_signature"
                                                      style="font-size: 12px; font-weight: bold;color:#993333">[N.B. Supported file extension is png/jpg/jpeg.Max size less than 150KB]</span>
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

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Contact Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('mobile','Mobile No :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('mobile') ? 'has-error': ''}}">
                                                {!! Form::text('mobile',!empty($appData->mobile) ? $appData->mobile : '', ['placeholder' => 'Mobile',
                                               'class' => 'form-control input-md onlyNumber mobile','id'=>'mobile']) !!}
                                                {!! $errors->first('mobile','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('email','Email :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('email') ? 'has-error': ''}}">
                                                {!! Form::text('email',!empty($appData->email) ? $appData->email : '', ['placeholder' => 'Email','class' => 'form-control input-md email','id'=>'email']) !!}
                                                {!! $errors->first('email','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('contact_address','Contact Address',['class'=>'col-md-3']) !!}
                                    <div class="col-md-6">
                                        {!! Form::textarea('contact_address', !empty($appData->contact_address) ? $appData->contact_address : '', ['class' => 'form-control input-md ', 'size' =>'5x2','data-rule-maxlength'=>'200', 'id'=>'contact_address', 'placeholder' => 'Address','maxlength'=>'200']) !!}
                                        {!! $errors->first('contact_address','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Description of the Connection</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('conn_type','Connection Type :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('conn_type') ? 'has-error': ''}}">
                                                {!! Form::select('conn_type', ['1'=>'Permanent','2'=>'Temporary'],!empty($appData->conn_type) ? $appData->conn_type : '', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md','id'=>'conn_type']) !!}
                                                {!! $errors->first('conn_type','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('load','Load (KW) :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('conn_type') ? 'has-error': ''}}">
                                                {!! Form::text('load',!empty($appData->load) ? $appData->load : '', ['placeholder' => 'Load','class' => 'form-control input-md onlyNumber','id'=>'load']) !!}
                                                {!! $errors->first('load','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('phase','Phase :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('phase') ? 'has-error': ''}}">
                                                {!! Form::select('phase', ['1'=>'Single Phase','2'=>'Three Phase'],!empty($appData->phase) ? $appData->phase : '', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md','id'=>'phase']) !!}
                                                {!! $errors->first('phase','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('voltage','Voltage :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('voltage') ? 'has-error': ''}}">
                                                {!! Form::select('voltage', ['1'=>'230V','2'=>'400V'],!empty($appData->voltage) ? $appData->voltage : '', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md','id'=>'voltage']) !!}
                                                {!! $errors->first('voltage','<span
                                                    class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('tariff_category','Tariff Category :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('tariff_category') ? 'has-error': ''}}">
                                                {!! Form::select('tariff_category', [],!empty($appData->tariff_category) ? $appData->tariff_category : '', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md docloader','id'=>'tariff_category']) !!}
                                                {!! $errors->first('tariff_category','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('tariff','Tariff :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('tariff') ? 'has-error': ''}}">
                                                {!! Form::select('tariff', [],!empty($appData->tariff) ? $appData->tariff : '', ['placeholder' => 'Select One',
                                               'class' => 'form-control input-md docloader','id'=>'tariff']) !!}
                                                {!! $errors->first('tariff','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('tariff_subcategory','Tariff SubCategory :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('') ? 'has-error': ''}}">
                                                {!! Form::select('tariff_subcategory', [],'', ['class' => 'form-control input-md','id'=>'tariff_subcategory']) !!}
                                                {!! $errors->first('tariff_subcategory','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('special_class','Special Class :',
                                            ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 {{$errors->has('special_class') ? 'has-error': ''}}">
                                                {!! Form::select('special_class', [],'', ['class' => 'form-control input-md','id'=>'special_class']) !!}
                                                {!! $errors->first('special_class','<span class="help-block">:message</span>') !!}
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
                                    @include('NewConnectionDESCO::documents')
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
                            @if($appInfo->status_id !== 5)
                                <div class="pull-left">
                                    <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                </div>
                            @endif
                            <div class="pull-left" style="padding-left: 1em;">
                                @if($appInfo->status_id == 5)
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md" value="submit" name="actionBtn">Re Submit
                                    </button>
                                @else
                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md" value="Submit" name="actionBtn">
                                        Submit
                                    </button>
                                @endif
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
        var form = $("#NewConnection").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                return true;
                var val = $('#nid_verification_status').val();
                if (val != '1') {
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
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });

        var calculatedYear = (new Date).getFullYear() - 18;
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

        $(document).on('blur', '.mobile', function () {
            var mobile_telephone = $(this).val();

            if (mobile_telephone.length > 15 || mobile_telephone.length < 11) {
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
            tokenUrl = '/new-connection-desco/get-refresh-token'

            $('#ministry').attr('disabled', true);
            $('#div_ministry').hide();

            $("#thana").select2();
            $("#application_type").select2();
            $("#organization_type").select2();
            $("#ministry").select2({width: '100%'});
            $("#conn_type").select2();
            $("#phase").select2();
            $("#tariff_category").select2();
            $("#voltage").select2();
            $("#special_class").select2();

            $('#conn_type').keydown()
            $('#phase').keydown()
            $('#voltage').keydown()
            $('#tariff_category').keydown()
            $('#tariff').keydown()
            //
            $('#application_type').keydown()
            $('#organization_type').keydown()
            $('#ministry').keydown()
            $('#thana').keydown()
            $('#special_class').keydown()
        });

        $('#application_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/application-category";
            var selected_value = '{{isset($appData->application_type) ? $appData->application_type : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

            apiCallGet(e, options, apiHeaders, appTypeCallbackResponse, arrays);


        })

        $('#application_type').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var app_type = $('#application_type').val()
            if (app_type.split('@')[0] == 'Organization') {
                $('#organization_panel').show()
                $('#authorized_person').removeAttr('disabled')
                $('#organization_name').removeAttr('disabled')
                $('#ministry').removeAttr('disabled')
                $('#designation_authorized').removeAttr('disabled')
                $('#organization_type').removeAttr('disabled')
            } else {
                $('#organization_panel').hide()
                $('#authorized_person').attr('disabled', 'true')
                $('#organization_name').attr('disabled', 'true')
                $('#ministry').attr('disabled', 'true')
                $('#designation_authorized').attr('disabled', 'true')
                $('#organization_type').attr('disabled', 'true')


            }


        })

        $('#organization_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/organization";
            var selected_value = '{{isset($appData->organization_type) ? $appData->organization_type : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })

        $(document).on('change', '#organization_type', function () {
            var val = this.value;
            if (val.split('@')[0] == 'Govt') {
                $('#ministry').attr('disabled', false);
                $('#div_ministry').show();
            } else {
                $('#ministry').attr('disabled', true);
                $('#div_ministry').hide();
            }

        })

        $('#ministry').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/ministry";
            var selected_value = '{{isset($appData->ministry) ? $appData->ministry : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })

        $('#thana').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/thana";
            var selected_value = '{{isset($appData->thana) ? $appData->thana : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })

        $('#thana').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide();
            $("#area").html('<option value="">Please Wait...</option>');
            var thana = $('#thana').val();
            var thana_id = thana.split("@")[0];
            //  alert(thana_id)
            if (thana_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$desco_service_url}}/info/area/" + thana_id;
                var selected_value = '{{isset($appData->area) ? $appData->area : ''}}'; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "name"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "area";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#area").html('<option value="">Select Thana First</option>');
                $('#area').trigger('change');
                $(self).next().hide();
            }
        })

        $('#area').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide();
            $("#snd").html('<option value="">Please Wait...</option>');
            var area = $('#area').val();
            var area_id = area.split("@")[0];
            //  alert(thana_id)
            if (area_id) {
                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$desco_service_url}}/info/snd/" + area_id;
                var selected_value = '{{isset($appData->snd) ? $appData->snd : ''}}'; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "sndId"; //dynamic id for callback
                var element_name = "sndName"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "snd";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#snd").html('<option value="">Select Area First</option>');
                $(self).next().hide();
            }
        })

        $('#snd').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide();
            $("#wiring_inspector").html('<option value="">Please Wait...</option>');
            var snd = $('#snd').val();
            var snd_id = snd.split("@")[0];
            //  alert(thana_id)
            if (snd_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$desco_service_url}}/info/wiring-inspector/" + snd_id;
                var selected_value = '{{isset($appData->wiring_inspector) ? $appData->wiring_inspector : ''}}'; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "name"; //dynamic name for callback
                var data = 'sndId';
                var dependent_section_id = "wiring_inspector";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#wiring_inspector").html('<option value="">Select S&D First</option>');
                $(self).next().hide();
            }
        })

        $('#conn_type').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/connection-type";
            var selected_value = '{{isset($appData->conn_type) ? $appData->conn_type : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })

        $('#phase').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/phase";
            var selected_value = '{{isset($appData->phase) ? $appData->phase : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })

        $('#voltage').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/volt";
            var selected_value = '{{isset($appData->voltage) ? $appData->voltage : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })

        $('#tariff_category').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/tariff-category";
            var selected_value = '{{isset($appData->tariff_category) ? $appData->tariff_category : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })

        $('#tariff_category').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide();
            $("#tariff").html('<option value="">Please Wait...</option>');
            var tariff_category = $('#tariff_category').val();
            var tariff_category_id = tariff_category.split("@")[0];
            //  alert(thana_id)
            if (tariff_category_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$desco_service_url}}/info/tariff/" + tariff_category_id;
                var selected_value = '{{isset($appData->tariff) ? $appData->tariff : ''}}'; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "name"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "tariff";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#tariff").html('<option value="">Select Tariff Category First</option>');
                $(self).next().hide();
            }
        })

        $('#tariff_category,#tariff').on('change', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var self = $(this);
            $(self).next().hide();
            $("#tariff_subcategory").html('<option value="">Please Wait...</option>');
            var tariff_category = $('#tariff_category').val();
            var tariff = $('#tariff').val();
            var tariff_category_id = tariff_category.split("@")[0];
            var tariff_id = tariff.split("@")[0];
            //  alert(thana_id)
            if (tariff_category_id && tariff_id) {

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$desco_service_url}}/info/tariff-sub-category/tariff/" + tariff_id + '/category/' + tariff_category_id;
                var selected_value = '{{isset($appData->tariff_subcategory) ? $appData->tariff_subcategory : ''}}'; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "id"; //dynamic id for callback
                var element_name = "name"; //dynamic name for callback
                var data = '';
                var dependent_section_id = "tariff_subcategory";
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id]; // for callback

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

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#tariff_subcategory").html('<option value="">Select Tariff Category and Tariff First</option>');
                $(self).next().hide();
            }
        })

        $('#special_class').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$desco_service_url}}/info/special-service";
            var selected_value = '{{isset($appData->special_class) ? $appData->special_class : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
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

        })
    })


    function appTypeCallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name]
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

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';

        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $("#" + calling_id).trigger('change');

    }

    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        // $("#" + calling_id).next().hide();
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        $("#" + dependent_section_id).select2();
        // $("#" + calling_id).find('.loading_data').hide();
        $("#" + calling_id).next().hide();
        $('.loading_data').hide();
        $('.select2').css('display', 'block');

    }


    /*document upload start*/
    function uploadDocument(targets, id, vField, isRequired) {
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
            var action = "{{URL::to('/new-connection-desco/upload-document')}}";
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

    //Doc and image upload section
    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = ''
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            var isReq = $('#' + abc).attr('data-required')
            if (isReq == 'required') {
                $('#' + abc).addClass('required error')
            }
            $('#span_' + abc).show()
            let img = $('#old_image_' + abc).val()
            let old_img = $('#old_image_' + abc).attr('data-img')
            $('#validate_field_' + abc).val(img)
            $('#photo_viewer_' + abc).attr('src', old_img)
        } else {
            return false;
        }
    });

    function imagePreview(input) {
        if (input.files && input.files[0]) {
            var calling_id = input.id;
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#photo_viewer_" + calling_id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('click', '#edit_nid', function () {
        $("#validate_nid").css("display", 'block');
        $("#edit_nid").css("display", 'none');
        $("#nid_number").prop("readonly", false);
        $('#applicant_name').val('');
        $('#father_name').val('');
        $('#mother_name').val('');
        $('#applicant_dob').val('');
        $('#applicant_name').prop("readonly", false);
        $('#father_name').prop("readonly", false);
        $('#mother_name').prop("readonly", false);
        $('#applicant_dob').prop("readonly", false);

    });
    $(document).on('click', '#validate_nid', function () {
        var nid_number = $("#nid_number").val();
        var _token = "{{ csrf_token() }}";
        var ln = parseInt(nid_number.length)
        if (nid_number == '') {
            $("#nid_number").addClass('error');
            return false;
        }

        if (ln == 10 || ln == 17 || ln == 13) {
            $("#nid_number").removeClass('error')
        } else {
            $("#nid_number").addClass('error')
            return false;
        }

        var dob = $("#applicant_dob").val()
        if (dob == '') {
            $("#applicant_dob").addClass('error');
            return false;
        }


        if (nid_number != '' && dob != '') {
            $.ajax({
                type: "POST",
                url: '/new-connection-desco/validate-nid',
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
                    if (result.responseCode == 1) {
                        $('#nid_verification_status').val('1');
                        $("#nid_number").prop("readonly", true);
                        $("#applicant_dob").attr("readonly");
                        $('#applicant_name').val(result.verify_nid.name);
                        $('#father_name').val(result.verify_nid.father);
                        $('#mother_name').val(result.verify_nid.mother);
                        $('#father_name').prop("readonly", true);
                        $('#mother_name').prop("readonly", true);
                        $("#applicant_name").prop("readonly", true);
                        $("#applicant_dob").prop("readonly", true);
                        $("#validate_nid").css("display", 'none');
                        $("#edit_nid").css("display", 'block');
                        $('.saveLoader').hide()
                        // alert('Nid Verified Successfully');
                    } else {
                        alert('Nid Not Verified')
                        $("#edit_nid").css("display", 'none');
                        $('#nid_verification_status').val('0');
                        $("#nid_number").val('');
                        $("#nid_number").addClass('error')

                    }
                },
            });
        }
    });

</script>