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
                        <h5><strong>Application For New Connection (BPDB)</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'new-connection-bpdb/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>

                    <h3 class="text-center stepHeader"> General Information</h3>
                    <fieldset>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>PERSONAL</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-md-offset-3">
                                            {!! Form::label('application_type','Application Type :',['class'=>'text-left
                                            col-md-4 ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::select('application_type', [''=>'Select Application
                                                Type','1'=>'Personal','2'=>'Organization'], '', ['class' =>
                                                'form-control']) !!}<br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="information" style="display:none;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6" id="connectionName">
                                                {!! Form::label('connection_name','Connection Name :',
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('connection_name', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="organizationName">
                                                {!! Form::label('organization_name','Organization Name :',
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('organization_name', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6" id="applicant_spouse">
                                                {!! Form::label('applicant_spouse_name','Applicant Spouse Name :',
                                                ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_spouse_name', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_name_english','Applicant Name (In English)
                                                :', ['class'=>'col-md-5 required-star','id'=>'name_english']) !!}
                                                {!! Form::label('authorized_person_name_en','Authorized Person’s Name
                                                (In English) ', ['class'=>'col-md-5','id'=>'authorized_person_name_en'])
                                                !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_name_english', '',['class' =>
                                                    'form-control input-sm']) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('nation_id','Nation ID :', ['class'=>'col-md-5
                                                required-star','id'=>'nation_id_label']) !!}
                                                <div class="col-md-7 ">
                                                    {!! Form::text('nation_id', '',['class' => 'form-control input-sm
                                                    onlyNumber', 'id'=>'nation_id']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('applicant_name_bangla','Applicant Name(In Bangla) :',
                                                ['class'=>'col-md-5','id'=>'applicant_name_bangla']) !!}
                                                {!! Form::label('authorized_person_name_bangla','Authorized Person’s
                                                Name(In Bangla) :',
                                                ['class'=>'col-md-5','id'=>'authorized_person_name_bangla']) !!}
                                                <div
                                                        class="col-md-7">
                                                    {!! Form::text('applicant_name_bangla', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                {!! Form::label('applicant_passport_no','Passport No :',
                                                ['class'=>'col-md-5 required-star','id'=>'applicant_passport_label'])
                                                !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('applicant_passport_no', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="">
                                        <div class="row">
                                            <div class="col-md-6" id="app_father_name">
                                                {!! Form::label('father_name','Father’s Name :', ['class'=>'col-md-5'])
                                                !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('father_name', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="designation">
                                                {!! Form::label('authorized_person_designation','Authorized Person’s
                                                Designation :', ['class'=>'col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('authorized_person_designation', '',['class' =>
                                                    'form-control input-sm']) !!}
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                {!! Form::label('applicant_mobile_no','Mobile No :',
                                                ['class'=>'col-md-5']) !!}
                                                <div
                                                        class="col-md-7">
                                                    {!! Form::text('applicant_mobile_no', '',['class' => 'form-control mobile
                                                    input-sm onlyNumber']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="">
                                        <div class="row">
                                            <div class="col-md-6" id="app_mother_name">
                                                {!! Form::label('mother_name','Mother’s Name :', ['class'=>'col-md-5'])
                                                !!}
                                                <div class="col-md-7 ">
                                                    {!! Form::text('mother_name', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sex','Sex :', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('sex', [''=>'Select
                                                Sex','male'=>'Male','female'=>'Female'], '', ['class' =>
                                                'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('date_of_birth','Date of Birth :', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                <div class="datepickerDob input-group date">
                                                    {!! Form::text('date_of_birth', '',['class' => 'form-control
                                                    input-sm']) !!}
                                                    <span class="input-group-addon"><span
                                                                class="fa fa-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('photo','Photo :', ['class'=>'col-md-5 required-star']) !!}
                                            <div class="col-md-7">
                                                <input type="file" name="photo" id="photo" class="required" flag="img"
                                                       onchange="uploadDocument('preview_photo', this.id, 'validate_field_photo',1)">
                                                {!! $errors->first('photo','<span class="help-block">:message</span>')
                                                !!}
                                                <span style="color:#993333;">[N.B. Supported file extension is
                                                    png,jpg,jpeg.Max size less than 150KB.]</span>
                                                <div id="preview_photo">
                                                    <input type="hidden" value="" id="validate_field_photo"
                                                           name="validate_field_photo" class="required">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('signature','Signature :', ['class'=>'col-md-5
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                <input type="file" name="signature" id="signature" class="required"
                                                       flag="img"
                                                       onchange="uploadDocument('preview_signature', this.id, 'validate_field_signature',1)">
                                                {!! $errors->first('signature','<span
                                                    class="help-block">:message</span>') !!}
                                                <span style="color:#993333;">[N.B. Supported file extension is
                                                    png/jpg/jpeg.Max size less than 150KB.]</span>
                                                <div id="preview_signature">
                                                    <input type="hidden" value="" id="validate_field_signature"
                                                           name="validate_field_signature" class="required">
                                                </div>
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
                                            {!! Form::label('house_no','House/Plot No', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('house_no', '',['class' => 'form-control input-sm ']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('lane_no','Lane/Road No', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('lane_no', '',['class' => 'form-control input-sm']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('union','Section/Union', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('union', '',['class' => 'form-control
                                                input-sm']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('block','Block/Village', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('block', '',['class' => 'form-control input-sm']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('district','District',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('district', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('post_code','Post Code', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7 ">
                                                {!! Form::text('post_code', '',['class' => 'form-control input-sm
                                                onlyNumber']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('thana','Thana',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('thana', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('email','Email', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('email', '',['class' => 'form-control input-sm email'])
                                                !!}
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
                            <div class="panel-heading"><strong>PERMANENT ADDRESS</strong></div>
                            <div class="panel-body">

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('permanet_house_no','House/Plot No', ['class'=>'col-md-5'])
                                            !!}
                                            <div
                                                    class="col-md-7">
                                                {!! Form::text('permanet_house_no', '',['class' => 'form-control
                                                input-sm']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('lane_no','Lane/Road No', ['class'=>'col-md-5 ']) !!}
                                            <div
                                                    class="col-md-7">
                                                {!! Form::text('permanet_lane_no', '',['class' => 'form-control input-sm
                                                ']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('permanet_union','Section/Union', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('permanet_union', '',['class' => 'form-control
                                                input-sm']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('permanet_block','Block/Village', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('permanet_block', '',['class' => 'form-control
                                                input-sm']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('permanet_district','District',['class'=>'col-md-5
                                            text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('permanet_district', [],'', ['placeholder' =>
                                                'SelectOne' ,'class' => 'form-control
                                                input-md']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('permanet_post_code','Post Code', ['class'=>'col-md-5']) !!}
                                            <div
                                                    class="col-md-7">
                                                {!! Form::text('permanet_post_code', '',['class' => 'form-control
                                                input-sm onlyNumber']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('permanet_thana','Thana',['class'=>'col-md-5 text-left'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::select('permanet_thana', [],'', ['placeholder' => 'Select
                                                One','class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('permanet_email','Email', ['class'=>'col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('permanet_email', '',['class' => 'form-control input-sm
                                                email']) !!}
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
                            <div class="panel-heading"><strong>CONNECTION ADDRESS</strong></div>
                            <div class="panel-body">

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class=" col-md-6" style="font-size: 17px;">
                                            <div class="col-md-offset-5 col-md-7">
                                                {!! Form::checkbox('same_as_mailing',1,null,
                                                array('id'=>'same_as_mailing', 'onclick'=>'samaAsMailingFunction()'))
                                                !!}
                                                {!! Form::label('same_as_mailing','Same as Mailing Address',['class'=>'col-md-10']) !!}

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_district','District',['class'=>'col-md-5
                                            text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('connection_district', [],'', ['placeholder' => 'Select
                                                One',
                                                'class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_house_no','House/Plot No',
                                            ['class'=>'col-md-5']) !!}
                                            <div
                                                    class="col-md-7">
                                                {!! Form::text('connection_house_no', '',['class' => 'form-control
                                                input-sm ']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_thana','Thana',['class'=>'col-md-5 text-left'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::select('connection_thana', [],'', ['placeholder' => 'Select
                                                One','class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_lane_no','Lane/Road No', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::text('connection_lane_no', '',['class' => 'form-control
                                                input-sm']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('bpdb_zone','BPDBZone :',['class'=>'col-md-5 text-left'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::select('bpdb_zone', [],'', ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_union','Section/Union', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::text('connection_union', '',['class' => 'form-control
                                                input-sm']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('esu','S&D/ESU :',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('esu', [],'', ['placeholder' => 'Select One','class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_block','Block/Village', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::text('connection_block', '',['class' => 'form-control
                                                input-sm']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_area','Connection Area :',['class'=>'col-md-5
                                            text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('connection_area', ['1'=>'Connection Area'],'',
                                                ['placeholder' => 'Select One',
                                                'class' => 'form-control input-md']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('connection_post_code','Post Code', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::text('connection_post_code', '',['class' => 'form-control
                                                input-sm onlyNumber']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('connection_mobile_no','Mobile No', ['class'=>'col-md-5'])
                                            !!}
                                            <div class="col-md-7">
                                                {!! Form::text('connection_mobile_no', '',['class' => 'form-control mobile
                                                input-sm onlyNumber']) !!}
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
                            <div class="panel-heading"><strong>LOAD DETAILS</strong></div>
                            <div class="panel-body">

                                <div class="form-group" style="">
                                    <div class="row">
                                        <div class=" col-md-12">
                                            <table class="table table-bordered table-hover" id="loadDetails">
                                                <thead>
                                                <tr>
                                                    <th class="required-star">Description of Load</th>
                                                    <th class="required-star">Load per Item (Watt)</th>
                                                    <th class="required-star">No. of Item</th>
                                                    <th>Total Load (Watt)</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr id="loadDetailsRow">
                                                    <td>{!! Form::select('description_of_load[]',[],null,['class' =>
                                                            'form-control input-md description_of_load
                                                            required','placeholder' => 'Select One',
                                                            'id'=>'description_of_load_1']) !!}
                                                        {!! $errors->first('','<span
                                                            class="help-block">:message</span>') !!}</td>
                                                    <td>{!! Form::select('load_per_item[]',[],null,['class' =>
                                                            'form-control input-md load_per_item
                                                            required','placeholder' => 'Select One',
                                                            'id'=>'load_per_item_1']) !!}
                                                        {!! $errors->first('','<span
                                                            class="help-block">:message</span>') !!}</td>
                                                    <td> {!! Form::text('no_of_item[]','',['class' => 'col-md-7
                                                            form-control input-md no_of_item onlyNumber
                                                            required','placeholder' => '', 'id'=>'no_of_item_1']) !!}
                                                    </td>
                                                    <td> {!! Form::text('total_load[]','',['class' => 'col-md-7
                                                            form-control input-md total_load','placeholder' => '',
                                                            'id'=>'total_load_1','readonly']) !!}</td>
                                                    <td style="vertical-align: middle; text-align: center">
                                                        <a class="btn btn-sm btn-primary addTableRows"
                                                           title="Add more LOAD DETAILS"
                                                           onclick="addTableRowbpdb('loadDetails', 'loadDetailsRow');">
                                                            <i class="fa fa-plus"></i></a>
                                                    </td>

                                                </tr>

                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="3" style="text-align:right">Total:</td>
                                                    <td colspan="2" style="text-align:center"><input type="text"
                                                                                                     id="sum"
                                                                                                     name="total"
                                                                                                     class="form-control input-md"
                                                                                                     readonly></td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>


                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <!--/panel-body-->
                        </div>
                        <!--/panel-->


                    </fieldset>


                    <h3 class="text-center stepHeader">Attachments</h3>
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="docListDiv">
                                    @include('NewConnectionBPDB::documents')
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

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="pull-left">
                                <button type="submit" id="save_as_draft" class="btn btn-info btn-md cancel"
                                        value="draft" name="actionBtn">Save as Draft
                                </button>
                            </div>

                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md" value="Submit" name="actionBtn">
                                    Submit
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

        var form = $("#NewConnection").show();
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top', '-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                var nid = $('#nation_id').val();
                var passport = $('#applicant_passport_no').val();
                if (nid == '' && passport == '') {
                    alert("Please Input Your Paassport OR NID");
                    return false;
                }
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
            minDate: '01/01/' + (yyyy - 100)
        });

        var calculatedYear = (new Date).getFullYear() - 19;
        var currentMonth = (new Date).getMonth();
        var currentDay = (new Date).getDate();

        $('.datepickerDob').datetimepicker({
            viewMode: 'days',
            format: 'YYYY-MM-DD',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: new Date(calculatedYear, currentMonth, currentDay),
            ignoreReadonly: true
        });

        $("#application_type").on('change', function () {
            var applicationType = $('#application_type').val();
            if (applicationType == 1) {
                $("#information").show();
                $("#organizationName").hide();
                $("#organization_name").removeClass('required');
                $("#authorized_person_name_en").hide();
                $("#authorized_person_name_bangla").hide();
                $("#designation").hide();
                $("#connectionName").show();
                $("#connection_name").addClass('required');
                $("#name_english").show();
                $("#applicant_spouse").show();
                $("#applicant_name_bangla").show();
                $("#app_father_name").show();
                $("#app_mother_name").show();
                $("#authorized_person_designation").removeClass('required');
                $("#father_name").addClass('required');
                $("#mother_name").addClass('required');

            } else if (applicationType == 2) {
                $("#information").show();
                $("#connection_name").removeClass('required');
                $("#organization_name").addClass('required');
                $("#connectionName").hide();
                $("#connectionName").removeClass('required');
                $("#name_english").hide();
                $("#applicant_spouse").hide();
                $("#applicant_name_bangla").hide();
                $("#app_father_name").hide();
                $("#app_mother_name").hide();
                $("#organizationName").show();
                $("#authorized_person_name_en").show();
                $("#authorized_person_name_bangla").show();
                $("#designation").show();
                $("#authorized_person_designation").addClass('required');
                $("#father_name").removeClass('required');
                $("#mother_name").removeClass('required');

            } else {
                $("#information").hide();
                $("#connectionName").hide();
                $("#name_english").hide();
                $("#applicant_spouse").hide();
                $("#applicant_name_bangla").hide();
                $("#app_father_name").hide();
                $("#app_mother_name").hide();
                $("#organizationName").hide();
                $("#authorized_person_name_en").hide();
                $("#authorized_person_name_bangla").hide();
                $("#designation").hide();
            }
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

        function sum_total() {
            var sum = 0;
            $.each($(".total_load"), function () {
                sum += +$(this).val();
            });
            if (sum) {
                $("#sum").val(sum);
            } else {
                $("#sum").val('');
            }

        }

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

        $(document).on('input', '.no_of_item', function () {
            var a = this.id;
            var d_id = a.split("_").pop();
            //alert(d_id);
            var load = $('#load_per_item_' + d_id).val();
            var load_amount = load.split("@").pop();
            var no_of_item = $('#no_of_item_' + d_id).val();
            var total = parseInt(load_amount) * parseInt(no_of_item);
            if (!isNaN(total)) {
                $('#total_load_' + d_id).val(total);
                sum_total();
            } else {
                $('#total_load_' + d_id).val('');
            }
        });

        $(document).on('change', '.load_per_item', function () {
            var a = this.id;
            var d_id = a.split("_").pop();
            var load = $('#load_per_item_' + d_id).val();
            var load_amount = load.split("@").pop();
            var no_of_item = $('#no_of_item_' + d_id).val();
            var total = parseInt(load_amount) * parseInt(no_of_item);
            if (!isNaN(total)) {
                $('#total_load_' + d_id).val(total);
                sum_total();
            } else {
                $('#total_load_' + d_id).val('');
            }

        });

    });


    // Add table Row script
    function addTableRowbpdb(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        $(".description_of_load").select2('destroy');
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
        $('#' + repTextId).next('span').remove();
        $('#' + repTextId).select2();

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
        $('#' + tableID).find(".description_of_load").each(function () {
            $('#' + this.id).select2()
        });
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


    $(document).ready(function () {
        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/new-connection-bpdb/get-refresh-token';

            $('#district').select2();
            $('#permanet_district').select2();
            $('#connection_district').select2();
            $('#bpdb_zone').select2();
            $('#connection_area').select2();
            $('.description_of_load').select2();

            $('#district').keydown();
            $('#permanet_district').keydown();
            $('#connection_district').keydown();
            $('#bpdb_zone').keydown();
            $('#connection_area').keydown();
            $('.description_of_load').keydown();


        });


        $('#district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$bpdb_service_url}}/district";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "DIST_CODE"; //dynamic id for callback
            var element_name = "DIST_DESC"; //dynamic name for callback
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
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#thana").html('<option value="">Please Wait...</option>');
            var district = $('#district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this);
                var api_url = "{{$bpdb_service_url}}/thana" + '/' + districtId;
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "thana"; // for callback
                var element_id = "THANA_CODE"; //dynamic id for callback
                var element_name = "THANA_DESC"; //dynamic name for callback
                // var data = JSON.stringify({thana_id: companyID});
                // var errorLog={logUrl: '/log/api', method: 'get'};
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
                apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

            } else {
                $("#thana").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        });
        $('#permanet_district').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$bpdb_service_url}}/district";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "DIST_CODE"; //dynamic id for callback
            var element_name = "DIST_DESC"; //dynamic name for callback
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
        $("#permanet_district").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#permanet_thana").html('<option value="">Please Wait...</option>');
            var district = $('#permanet_district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this);
                var api_url = "{{$bpdb_service_url}}/thana" + '/' + districtId;
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "permanet_thana"; // for callback
                var element_id = "THANA_CODE"; //dynamic id for callback
                var element_name = "THANA_DESC"; //dynamic name for callback
                // var data = JSON.stringify({thana_id: companyID});
                // var errorLog={logUrl: '/log/api', method: 'get'};
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
                apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

            } else {
                $("#permanet_thana").html('<option value="">Select District First</option>');
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
            var api_url = "{{$bpdb_service_url}}/district";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "DIST_CODE"; //dynamic id for callback
            var element_name = "DIST_DESC"; //dynamic name for callback
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
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#connection_thana").html('<option value="">Please Wait...</option>');
            var checkBox = $('#same_as_mailing').is(':checked');
            var selected_value = '';
            if (checkBox == true) {
                selected_value = $("#thana").val(); // for callback
            }
            var district = $('#connection_district').val();
            var districtId = district.split("@")[0];
            if (districtId) {
                var e = $(this);
                var api_url = "{{$bpdb_service_url}}/thana" + '/' + districtId;
                var calling_id = $(this).attr('id');
                var dependent_section_id = "connection_thana"; // for callback
                var element_id = "THANA_CODE"; //dynamic id for callback
                var element_name = "THANA_DESC"; //dynamic name for callback
                // var data = JSON.stringify({thana_id: companyID});
                // var errorLog={logUrl: '/log/api', method: 'get'};
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
                apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

            } else {
                $("#connection_thana").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        });
        $('#bpdb_zone').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$bpdb_service_url}}/zone";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "ZONE_CODE"; //dynamic id for callback
            var element_name = "ZONE_DESC"; //dynamic name for callback
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

        $('.description_of_load').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            // alert(this.id);

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$bpdb_service_url}}/load-item";
            var selected_value = ''; // for callback
            var calling_id = 'description_of_load'; // for callback
            var element_id = "ITEM_CODE"; //dynamic id for callback
            var element_name = "ITEM_NAME"; //dynamic name for callback
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

            apiCallGet(e, options, apiHeaders, calbackLoadItem, arrays);

        });


        $(document).on('change', ".description_of_load", function () {
            //test
            var a = this.id;
            var d_id = a.split("_").pop();
            //alert(d_id);
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $('#load_per_item_' + d_id).html('<option value="">Please Wait...</option>');
            var desc_load = $("#description_of_load_" + d_id).val();
            //alert(desc_load);
            var desc_load_id = desc_load.split("@")[0];
            if (desc_load_id) {
                var e = $(this);
                var api_url = "{{$bpdb_service_url}}/load-item-details" + '/' + desc_load_id;
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id');

                var dependent_section_id = "load_per_item_" + d_id; // for callback
                var element_id = "ITEM_CODE"; //dynamic id for callback
                var element_name = "LOAD"; //dynamic name for callback
                // var data = JSON.stringify({thana_id: companyID});
                // var errorLog={logUrl: '/log/api', method: 'get'};
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
                apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

            } else {
                $(".load_per_item").html('<option value="">Select Descriptionof load First</option>');
                $(self).next().hide();
            }

        });

        $('#connection_area').on('keydown', function (el) {
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$bpdb_service_url}}/connection-area";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "AREA_ID"; //dynamic id for callback
            var element_name = "AREA_NAME"; //dynamic name for callback
            var location_code = "LOCATION_CODE"; //dynamic name for callback
            // var data = '';
            var data = JSON.stringify({"ZONE_CODE": "2", "CIRCLE_CODE": "01", "DIV_CODE": "01"})
            // var errorLog={logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name, location_code]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },

            ];

            apiCallPost(e, options, apiHeaders, callbackConnectionArea, arrays);

        });

        $("#bpdb_zone").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#esu").html('<option value="">Please Wait...</option>');
            var bpdb_zone = $('#bpdb_zone').val();
            var bpdbZone = bpdb_zone.split("@")[0];
            if (bpdbZone) {
                var e = $(this);
                var api_url = "{{$bpdb_service_url}}/sd-esu" + '/' + bpdbZone;
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "esu"; // for callback
                var element_id = "DIV_CODE"; //dynamic id for callback
                var element_name = "DIV_DESC"; //dynamic name for callback
                // var data = JSON.stringify({thana_id: companyID});
                // var errorLog={logUrl: '/log/api', method: 'get'};
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
                apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

            } else {
                $("#esu").html('<option value="">Select BPDB Zone First</option>');
                $(self).next().hide();
            }

        });

    });

    function districtcallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
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

    function calbackLoadItem(response, [calling_id, selected_value, element_id, element_name]) {


        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }
        $("." + calling_id).html(option);
        $("." + calling_id).next().hide();
    }

    function callbackConnectionArea(response, [calling_id, selected_value, element_id, element_name, location_code]) {


        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name] + '@' + row[location_code];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
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
        // console.log(response.data);
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
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $("#" + dependent_section_id).select2();
        //alert(dependent_section_id);
        $("#" + calling_id).next().hide();
    }

    $(document).on('click', '#same_as_mailing', function () {
        var checkBox = document.getElementById("same_as_mailing");
        var house = $("#house_no").val();
        var district = $("#district").val();
        var thana = $("#thana").val();
        var union = $("#union").val();
        var lane = $("#lane_no").val();
        var block = $("#block").val();
        var postCode = $("#post_code").val();

        if (checkBox.checked == true) {
            $("#connection_house_no").val(house);
            $("#connection_district").val(district);
            $("#connection_district").trigger("change");
            $("#connection_union").val(union);
            $("#connection_lane_no").val(lane);
            $("#connection_block").val(block);
            $("#connection_post_code").val(postCode);
            $("#connection_thana").val(thana);
        } else {
            $("#connection_district").val('');
            $("#connection_thana").val('');
            $("#connection_house_no").val('');
            $("#connection_union").val('');
            $("#connection_lane_no").val('');
            $("#connection_block").val('');
            $("#connection_post_code").val('');
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
        var check = document.getElementById(id).getAttribute("flag")
        if (check == "img") {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 149999) || (extension !== "jpg" && extension !== "jpeg" && extension !== "png")) {
                alert('File size cannot be over 150 KB and file extension should be only jpg, jpeg and png');
                document.getElementById(id).value = "";
                return false;
            }
        } else {
            var fileName = document.getElementById(id).files[0].name;
            var extension = fileName.split('.').pop();
            if (extension !== "pdf") {
                alert('File  extension should be only pdf');
                document.getElementById(id).value = "";
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
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/new-connection-bpdb/upload-document')}}";
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

</script>