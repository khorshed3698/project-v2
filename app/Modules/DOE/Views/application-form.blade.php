<?php
$accessMode = ACL::getAccsessRight('DOE');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>

    .wizard > .steps > ul > li {
        width: 33.3% !important;
    }

    .wizard > .actions {
        top: -15px !important;
    }

    .text-title {
        font-size: 16px !important
    }

    .text-sm {
        font-size: 9px !important
    }

    .calender-icon {
        border: none;
        padding-top: 8px ! important;
    }

    input[type=radio].error, input[type=checkbox].error {
        outline: 1px solid red !important;
    }

    .wizard > .content > .body input {
        display: inline;
    }

    .kg {
        margin: 0px;
        padding: 0px;
    }

    .daily {
        padding-left: 0px;
    }

    .production {
        padding-right: 0px;
    }

    .radio_hover {
        cursor: pointer;
    }

    .addbank {
        margin-left: -15px;
    }

    /*#totalfee {*/
    /*    margin-left: -12px;*/
    /*}*/
    @media screen and (max-width: 800px) {
        .kg {
            padding: 15px;
        }

        .daily {
            padding: 15px;
        }

        .production {
            padding-right: 15px;
        }
    }
</style>
{{--step css --}}
<link rel="stylesheet" href="{{ url("assets/plugins/select2.min.css") }}">
<script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">

<section class="content">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <div class="panel panel-red" id="inputForm">
                    <div class="panel-heading"><b> Application for Environmental Clearance</b></div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => '/doe/store','enctype'=>'multipart/form-data','method' => 'post','id' => 'DOEForm','role'=>'form')) !!}
                        <input type="hidden" name="selected_file" id="selected_file"/>
                        <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                        <input type="hidden" name="isRequired" id="isRequired"/>
                        <h3 class="stepHeader">General Information</h3>
                        <fieldset>
                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>1. General Information</strong></div>
                                <div class="panel-body">
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('certificate_type') ? 'has-error': ''}}">
                                                {!! Form::label('certificate_type','Application Type :',['class'=>'col-md-5 text-left ']) !!}
                                                <div class="col-md-7">

                                                    {!! Form::select('certificate_type', [], null,['class' => 'form-control input-sm', 'placeholder' => 'Select One','id'=>'certificate_type']) !!}
                                                    {!! $errors->first('certificate_type','<span class="help-block">:message</span>') !!}

                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('industry_id') ? 'has-error': ''}}">
                                                {!! Form::label('industry_id',' Type of Industry :',['class'=>'col-md-5 text-left ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('industry_id', [], null,['class' => 'form-control input-sm', 'placeholder' => 'Select One','id'=>'industry_id', 'onchange'=>"industryTypeSelectedOthersDiv(this.value)"]) !!}
                                                    {!! $errors->first('industry_id','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. If Industry type does not exits Please select "Other" type from the select box]</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix" id="othersDiv" style="display: none">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('industry_other') ? 'has-error': ''}} pull-right">
                                                {!! Form::label('industry_other','Other :',['class'=>'col-md-5 text-left ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('industry_other', null,['class' => 'form-control input-sm', 'placeholder' => 'Others Type']) !!}
                                                    {!! $errors->first('industry_other','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('application_type') ? 'has-error': ''}}">
                                                {!! Form::label('application_type','Is this proposed/existing Industry :',['class'=>'col-md-5 text-left ']) !!}
                                                <div class="col-md-7">
                                                    <label class="radio_hover radio-inline">
                                                        {!! Form::radio('application_type', 'proposed', false, ['class'=>'application_type', 'id' => 'application_type', 'onchange' => 'industryWiseEMPReportShow(this.value)']) !!}
                                                        Proposed
                                                    </label>
                                                    <label class="radio_hover radio-inline">
                                                        {!! Form::radio('application_type', 'existing', false, ['class'=>'application_type', 'id' => 'application_type', 'onchange' => 'industryWiseEMPReportShow(this.value)']) !!}
                                                        Existing
                                                    </label>
                                                    {!! $errors->first('application_type','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('category_id') ? 'has-error': ''}}">
                                                {!! Form::label('category_id',' Category Selection :',['class'=>'col-md-5 text-left ']) !!}
                                                <div class="col-md-7">
                                                    <td> {!! Form::select('category_id', [], (isset($alreadyExistApplicant->colors) ?
                                                       $alreadyExistApplicant->colors : ''),
                                                       ['class' => 'form-control input-sm','style'=>'margin-bottom:5px;','id'=>'category_id', 'onchange'=>"categoryWiseFileShow(this.value)",'placeholder' => 'Select One', 'readonly']) !!}
                                                        <div id="change_colours"
                                                             style="width: 100%;"> &nbsp;
                                                        </div>
                                                    </td>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('entrepreneur_name') ? 'has-error': ''}}">
                                                {!! Form::label('entrepreneur_name ','Entrepreneur Name  :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('entrepreneur_name','',
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm','id'=>'entrepreneur_name']) !!}
                                                    {!! $errors->first('entrepreneur_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('entrepreneur_designation') ? 'has-error': ''}}">
                                                {!! Form::label('entrepreneur_designation ','Entrepreneur Designation :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('entrepreneur_designation','',
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm','id'=>'entrepreneur_designation']) !!}
                                                    {!! $errors->first('entrepreneur_designation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('phone_number') ? 'has-error': ''}}">
                                                {!! Form::label('phone_number ','Phone :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('phone_number','',
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber',]) !!}
                                                    {!! $errors->first('phone_number','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('email') ? 'has-error': ''}}">
                                                {!! Form::label('email ','Email :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">

                                                    {!! Form::text('email', Auth::user()->user_email,
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm email','id'=>'txtEmail','onkeyup'=>'ValidateEmail();']) !!}
                                                    <span id="lblError" style="color: red;font-size: 14px"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('mobile') ? 'has-error': ''}}">
                                                {!! Form::label('mobile ','Mobile :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('mobile', Auth::user()->user_phone,
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber',]) !!}
                                                    {!! $errors->first('mobile','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('total_investment') ? 'has-error': ''}}">
                                                {!! Form::label('total_investment ','Total Investment :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7 input-group"
                                                     style="padding-right: 10px;padding-left: 10px;">
                                                    {!! Form::text('total_investment','',
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber','id'=>'total_investment']) !!}
                                                    <span class="input-group-addon">BDT</span>
                                                    {!! $errors->first('total_investment','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('land') ? 'has-error': ''}}">
                                                {!! Form::label('land ','Land:',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-4 production">
                                                    {!! Form::text('land','',
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber','id'=>'land']) !!}
                                                    {!! $errors->first('land','<span class="help-block">:message</span>') !!}
                                                </div>
                                                <div class="col-md-3 daily">
                                                    {!! Form::select('land_unit', $land_unit,'', ['class' => 'form-control input-sm','id'=>'land_unit']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('total_manpower') ? 'has-error': ''}}">
                                                {!! Form::label('total_manpower ','Total Manpower:',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('total_manpower','',
                                                    ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber','id'=>'total_manpower']) !!}
                                                    {!! $errors->first('total_manpower','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="fee_bank_chllan">
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('fee_category_id') ? 'has-error': ''}}">
                                                    {!! Form::label('fee_category_id','Fee Category :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('fee_category_id', [], null, ['class' => 'form-control input-sm', 'placeholder' => 'Select One', 'id'=>'fee_category_id']) !!}
                                                        {!! $errors->first('fee_category_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('fee_id') ? 'has-error': ''}}">
                                                    {!! Form::label('fee_id ','Fee :',['class'=>'text-left col-md-5 ']) !!}
                                                    <div class="col-md-7">

                                                        {!! Form::select('fee_id', [], null,['class' => 'form-control input-sm', 'placeholder' => 'Select One','id'=>'fee_id']) !!}
                                                        {!! $errors->first('fee_id','<span class="help-block">:message</span>') !!}

                                                        <div id="spinner2">
                                                            <br>
                                                            <strong>New Fee:</strong>
                                                            <label class="radio-inline">{!! Form::radio('fee_type','new', 0,['class'=>'fee_cat', 'id'=>'new_fee newfees']) !!}
                                                                <span style="font-size: 12px;"
                                                                      id="new_fee_label">Tk.0</span> </label>
                                                            <br>
                                                            <strong>Renew Fee:</strong>
                                                            <label class="radio-inline">{!! Form::radio('fee_type','renew', 0,['class'=>'fee_cat', 'id'=>'renew_fee newfees']) !!}
                                                                <span style="font-size: 12px;"
                                                                      id="renew_fee_label">Tk.0</span> </label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6" style="display: none">
                                                    <fieldset class="scheduler-border">
                                                        <legend class="scheduler-border">Bank Challan:</legend>
                                                        <table class="table table-responsive table-bordered"
                                                               id="bankChallanTable">
                                                            <thead>
                                                            <tr>
                                                                <td width="40%">Attached File <br><span
                                                                            style="color:#993333;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                                </td>
                                                                <td width="60%">Fee</td>
                                                                <td>#</td>
                                                            </tr>
                                                            </thead>

                                                            <tbody>
                                                            <tr id="bankChallanRow">
                                                                <td>
                                                                    <input type="file" name="voucher_path[]"
                                                                           class="bank_challan"
                                                                           style="border-style: none;">
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('voucher_amount[]', '', ['class' => 'form-control input-sm feenew bank-challan', 'placeholder' => 'Fee', 'id' => 'voucher_amount_id']) !!}
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-primary addTableRows"
                                                                       title="Add more Visa record"
                                                                       onclick="addTableRow('bankChallanTable', 'bankChallanRow');">
                                                                        <i class="fa fa-plus"></i></a>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                            <tfoot>
                                                            <tr>
                                                                <td><p class="text-right"><strong>Total Fee :</strong>
                                                                    </p></td>
                                                                <td colspan="3">
                                                                    {!! Form::text('total_fee','', ['class' => 'form-control input-sm', 'id'=>'totalfee', 'readonly']) !!}
                                                                    <span style="color: red; font-size: 14px"
                                                                          id="bank_challan_error"></span>
                                                                </td>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('vat_Paper') ? 'has-error': ''}} hidden">
                                                    {!! Form::label('vat_Paper ','VAT Paper :',['class'=>'text-left col-md-5  ']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="vat_Paper" class="" id="vat_Paper"
                                                               onchange="uploadDocument('preview_vat_Paper', this.id, 'validate_field_vat_Paper',1)">
                                                        {!! $errors->first('vat_Paper','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_vat_Paper">
                                                            <input type="hidden" value="" id="validate_field_vat_Paper"
                                                                   name="validate_field_vat_Paper">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('trade_license') ? 'has-error': ''}}">
                                                    {!! Form::label('trade_license ','Trade license :',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="trade_license" id="trade_license"
                                                               class="required"
                                                               onchange="uploadDocument('preview_trade_license', this.id, 'validate_field_trade_license',1)">
                                                        {!! $errors->first('trade_license','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_trade_license">
                                                            <input type="hidden" value=""
                                                                   id="validate_field_trade_license"
                                                                   name="validate_field_trade_license" class="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="nocertificate">
                                                    {!! Form::label('noc ','No Objection Certificate (NOC) :',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="noc" id="noc" class="required"
                                                               onchange="uploadDocument('preview_noc', this.id, 'validate_field_noc',1)">
                                                        {!! $errors->first('noc','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_noc">
                                                            <input type="hidden" value="" id="validate_field_noc"
                                                                   name="validate_field_noc" class="required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('old_trade_license') ? 'has-error': ''}}"
                                                     id="old_trade" style="display: none;">
                                                    {!! Form::label('old_trade_license ','Old Certificate :',['class'=>'text-left col-md-5 ']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="old_trade_license"
                                                               id="old_trade_license"
                                                               onchange="uploadDocument('preview_mouza_map', this.id, 'validate_field_old_trade_license',1)">
                                                        {!! $errors->first('old_trade_license','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_old_trade_license">
                                                            <input type="hidden" value=""
                                                                   id="validate_field_old_trade_license"
                                                                   name="validate_field_old_trade_license"
                                                                   class="required">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('project_name') ? 'has-error': ''}}">
                                                {!! Form::label('project_name ','Project Name :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('project_name', (!empty($alreadyExistApplicant->incumbent_designation) ? $alreadyExistApplicant->incumbent_designation : ''),
                                                    ['class' => 'form-control textOnly input-sm','id'=>'project_name']) !!}
                                                    {!! $errors->first('project_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('project_activity') ? 'has-error': ''}}">
                                                {!! Form::label('project_activity ','Product Name ( Project Activity) :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('project_activity',null,
                                                         ['class'=>'form-control input-sm', 'rows' => 2, 'cols' => 60,'id'=>'project_activity']) !!}
                                                    {!! $errors->first('project_activity','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('district_id') ? 'has-error': ''}}">
                                                {!! Form::label('district_id ','Project District :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('district_id', [], (isset($alreadyExistApplicant->district_id) ?
                                                       $alreadyExistApplicant->district_id : ''),
                                                       ['class' => 'form-control input-sm search-box','id'=>'district_id', 'placeholder'=>'Select One']) !!}
                                                    {!! $errors->first('district_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('thana') ? 'has-error': ''}}">
                                                {!! Form::label('thana ','Project Thana :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('thana',[],'',$attributes = array('class'=>'form-control search-box', 'placeholder' => 'Select District First',
                                                        'data-rule-maxlength'=>'100','id'=>"thana")) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('submitting_office') ? 'has-error': ''}}">
                                                {!! Form::label('submitting_office ','Submitting Office :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('submitting_office', [], (isset($alreadyExistApplicant->district) ?
                                                       $alreadyExistApplicant->district : ''),
                                                       ['class' => 'form-control input-sm','id'=>'submitting_office', 'placeholder'=>'Select Thana First']) !!}
                                                    {!! $errors->first('submitting_office','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('project_address') ? 'has-error': ''}}">
                                                {!! Form::label('project_address ','Project Address :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('project_address',null,
                                                         ['class'=>'form-control input-sm', 'rows' => 2, 'cols' => 60,'id'=>'project_address']) !!}
                                                    {!! $errors->first('project_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> {{-----end panel body-----}}
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Necessary Papers for ECC</h3>
                        <fieldset>
                            <div class="panel panel-primary">
                                <div class="panel-heading"><strong>2. Necessary Papers for ECC</strong></div>
                                <div class="panel-body">
                                    <div id="proposed">
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('start_construction') ? 'has-error': ''}}">
                                                    {!! Form::label('start_construction','Start Construction for Proposed industrial unit or project:',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="col-md-12 datepicker input-group date">
                                                            {!! Form::text('start_construction', '',['class' => 'form-control input-sm','id'=>'start_construction']) !!}
                                                            <span class="input-group-addon calender-icon">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                            {!! $errors->first('start_construction','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('completion_construction') ? 'has-error': ''}}">
                                                    {!! Form::label('completion_construction','Completion Construction for Proposed industrial unit or project :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="col-md-12 datepicker input-group date">
                                                            {!! Form::text('completion_construction', '',['class' => 'form-control input-sm','id'=>'completion_construction']) !!}
                                                            <span class="input-group-addon calender-icon">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                            {!! $errors->first('completion_construction','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('trial_production') ? 'has-error': ''}}">
                                                    {!! Form::label('trial_production','Trial Production for Proposed industrial unit or project:',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="col-md-12 datepicker input-group date">
                                                            {!! Form::text('trial_production', '', ['class' => 'form-control input-sm','id'=>'trial_production']) !!}
                                                            <span class="input-group-addon calender-icon">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                            {!! $errors->first('trial_production','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('start_operation') ? 'has-error': ''}}">
                                                    {!! Form::label('start_operation','Start Operation for Proposed industrial unit or project :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="col-md-12 datepicker input-group date">
                                                            {!! Form::text('start_operation', '',['class' => 'form-control input-sm','id'=>'start_operation']) !!}
                                                            <span class="input-group-addon calender-icon">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                            {!! $errors->first('start_operation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="existing">
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('estart_operation') ? 'has-error': ''}}">
                                                    {!! Form::label('estart_operation','Start Operation for Existing industrial unit or project:',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="col-md-12 datepicker input-group date">
                                                            {!! Form::text('estart_operation', '', ['class' => 'form-control input-sm','id'=>'estart_operation']) !!}
                                                            <span class="input-group-addon calender-icon">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                            {!! $errors->first('estart_operation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('etrial_production') ? 'has-error': ''}}">
                                                    {!! Form::label('etrial_production','Trial Production for Existing industrial unit or project :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <div class="col-md-12 datepicker input-group date">
                                                            {!! Form::text('etrial_production', '',['class' => 'form-control input-sm','id'=>'etrial_production']) !!}
                                                            <span class="input-group-addon calender-icon">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                            {!! $errors->first('etrial_production','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('production_quantity') ? 'has-error': ''}}">
                                                {!! Form::label('production_quantity ','Production Quantity to be Produced  :',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-4 production">
                                                            {!! Form::text('production_quantity','',['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber','id'=>'production_quantity']) !!}
                                                            {!! $errors->first('production_quantity','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 kg">
                                                            {!! Form::select('name_production_quantity_unit', $production__unit, '',['class' => 'form-control input-sm required', 'id' => 'name_production_quantity_unit']) !!}
                                                        </div>
                                                        <div class="col-md-5 col-xs-6 daily">
                                                            {!! Form::select('name_production_quantity_duration', $durations, '',['class' => 'form-control input-sm required', 'id' => 'name_production_quantity_duration']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 {{$errors->has('raw_materials') ? 'has-error': ''}}">
                                                {!! Form::label('raw_materials ','Raw Materials Quantity  :',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-4 production">
                                                            {!! Form::text('raw_materials', '', ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber','id'=>'raw_materials']) !!}
                                                            {!! $errors->first('raw_materials','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 kg">
                                                            {!! Form::select('raw_materials_unit', $production__unit, '',['class' => 'form-control input-sm requried', 'id' => 'raw_materials_unit']) !!}
                                                        </div>
                                                        <div class="col-md-5 col-xs-6 daily">
                                                            {!! Form::select('raw_materials_duration', $durations, '',['class' => 'form-control input-sm requried', 'id' => 'raw_materials_duration']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('source_raw') ? 'has-error': ''}}">
                                                {!! Form::label('source_raw ','Source Raw Material :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('source_raw','',
                                                      ['data-rule-maxlength'=>'100','class' => 'form-control input-sm','id'=>'source_raw']) !!}
                                                    {!! $errors->first('source_raw','<span class="help-block">:message</span>') !!}
                                                </div>

                                            </div>
                                            <div class="col-md-6 {{$errors->has('quantity_water') ? 'has-error': ''}}">
                                                {!! Form::label('quantity_water ','Quantity Water to be used daily :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-4 production">
                                                    {!! Form::text('quantity_water','',
                                                      ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber','id'=>'quantity_water']) !!}
                                                    {!! $errors->first('quantity_water','<span class="help-block">:message</span>') !!}
                                                </div>
                                                <div class="col-md-3 col-xs-6 daily">
                                                    {!! Form::select('quantity_water_unit', $water_unit, '',['class' => 'form-control input-sm required', 'id' => 'quantity_water_unit']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('source_of_water') ? 'has-error': ''}}">
                                                {!! Form::label('source_of_water ','Source of Water :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('source_of_water',null,
                                                         ['class'=>'form-control input-sm', 'rows' => 2, 'cols' => 40,'id'=>'source_of_water']) !!}
                                                    {!! $errors->first('source_of_water','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('name_of_fuel') ? 'has-error': ''}}">
                                                {!! Form::label('name_of_fuel ','Name of Fuel :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('name_of_fuel', null,
                                                    ['class' => 'form-control textOnly input-sm','id'=>'name_of_fuel']) !!}
                                                    {!! $errors->first('name_of_fuel','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('fuel_quantity') ? 'has-error': ''}}">

                                                {!! Form::label('fuel_quantity ','Fuel Quantity  :',['class'=>'text-left col-md-5 required-star']) !!}

                                                <div class="col-md-7">
                                                    <div class="row">
                                                        <div class="col-md-4 production">
                                                            {!! Form::text('fuel_quantity','',
                                                              ['data-rule-maxlength'=>'100','class' => 'form-control input-sm onlyNumber','id'=>'fuel_quantity']) !!}
                                                            {!! $errors->first('fuel_quantity','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-3 col-xs-6 kg">
                                                            {!! Form::select('fuel_quantity_unit', $water_unit, '',['class' => 'form-control input-sm required', 'id' => 'fuel_quantity_unit']) !!}
                                                        </div>
                                                        <div class="col-md-5 col-xs-6 daily">
                                                            {!! Form::select('fuel_quantity_duration', $durations, '',['class' => 'form-control input-sm required', 'id' => 'fuel_quantity_duration']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6 {{$errors->has('source_of_fuel') ? 'has-error': ''}}">
                                                {!! Form::label('source_of_fuel ','Source of fuel :',['class'=>'text-left col-md-5 ']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('source_of_fuel', null,
                                                    ['class' => 'form-control input-sm','id'=>'source_of_fuel']) !!}
                                                    {!! $errors->first('source_of_fuel','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('quantity_of_daily') ? 'has-error': ''}}">
                                                {!! Form::label('quantity_of_daily ','Quantity of daily Liquid Waste  :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('quantity_of_daily','',
                                                      ['data-rule-maxlength'=>'100','class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('quantity_of_daily','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('location_waste_discharge') ? 'has-error': ''}}">
                                                {!! Form::label('location_waste_discharge ','Location Waste Discharge :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('location_waste_discharge', null,['class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('location_waste_discharge','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('quantity_of_daily_emission') ? 'has-error': ''}}">
                                                {!! Form::label('quantity_of_daily_emission ','Quantity of daily emission of gaseous substances :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('quantity_of_daily_emission','',['data-rule-maxlength'=>'100','class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('quantity_of_daily_emission','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('mode_emission_gaseous') ? 'has-error': ''}}">
                                                {!! Form::label('mode_emission_gaseous ','Mode emission of gaseous substances :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('mode_emission_gaseous', null,['class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('mode_emission_gaseous','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('mouza_map') ? 'has-error': ''}}">
                                                {!! Form::label('mouza_map ','Mouza (Village) Map indicating "Daag"(plot) number
                                                            and "Khatiayan"(land tax account) number :',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="mouza_map" id="mouza_map" class="required"
                                                           onchange="uploadDocument('preview_mouza_map', this.id, 'validate_field_mouza_map',1)">
                                                    {!! $errors->first('mouza_map','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_mouza_map">
                                                        <input type="hidden" value="" id="validate_field_mouza_map"
                                                               name="validate_field_mouza_map">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('design_time') ? 'has-error': ''}}"
                                                 id="etp_file_div">
                                                {!! Form::label('design_time ','Design & time schedule of proposed ETP :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="design_time" id="design_time"
                                                           onchange="uploadDocument('preview_design_time', this.id, 'validate_field_design_time',1)">
                                                    {!! $errors->first('design_time','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_design_time">
                                                        <input type="hidden" value="" id="validate_field_design_time"
                                                               name="validate_field_design_time">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('fund_allocation') ? 'has-error': ''}}"
                                                 id="fund_allocation_div">
                                                {!! Form::label('fund_allocation ','Fund allocation for ETP  :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('fund_allocation','',
                                                      ['data-rule-maxlength'=>'100','class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('fund_allocation','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('area_for_etp') ? 'has-error': ''}}"
                                                 id="area_for_etp_div">
                                                {!! Form::label('area_for_etp ','Area for ETP :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('area_for_etp', null,['class' => 'form-control input-sm']) !!}
                                                    {!! $errors->first('area_for_etp','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('approval_of_rajuk') ? 'has-error': ''}}">
                                                {!! Form::label('approval_of_rajuk ','Approval of RAJUK/KDA/RDA :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="approval_of_rajuk" id="approval_of_rajuk"
                                                           onchange="uploadDocument('preview_approval_of_rajuk', this.id, 'validate_field_approval_of_rajuk',1)">
                                                    {!! $errors->first('approval_of_rajuk','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_approval_of_rajuk">
                                                        <input type="hidden" value=""
                                                               id="validate_field_approval_of_rajuk"
                                                               name="validate_field_approval_of_rajuk">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('rent_agreement') ? 'has-error': ''}}">
                                                {!! Form::label('rent_agreement ','Rent Agreement / Land ownership documen :',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="rent_agreement" id="rent_agreement"
                                                           class="required"
                                                           onchange="uploadDocument('preview_rent_agreement', this.id, 'validate_field_rent_agreement',1)">
                                                    {!! $errors->first('rent_agreement','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_rent_agreement">
                                                        <input type="hidden" value="" id="validate_field_rent_agreement"
                                                               name="validate_field_rent_agreement">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('process_flow') ? 'has-error': ''}}">
                                                {!! Form::label('process_flow ','Process Flow :',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="process_flow" id="process_flow"
                                                           class="required"
                                                           onchange="uploadDocument('preview_process_flow', this.id, 'validate_field_process_flow',1)">
                                                    {!! $errors->first('process_flow','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_process_flow">
                                                        <input type="hidden" value="" id="validate_field_process_flow"
                                                               name="validate_field_process_flow">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('location_map') ? 'has-error': ''}}">
                                                {!! Form::label('location_map ','Location Map :',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="location_map" id="location_map"
                                                           class="required"
                                                           onchange="uploadDocument('preview_location_map', this.id, 'validate_field_location_map',1)">
                                                    {!! $errors->first('location_map','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_location_map">
                                                        <input type="hidden" value="" id="validate_field_location_map"
                                                               name="validate_field_location_map">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('layout_plan') ? 'has-error': ''}}"
                                                 id="layout_plan_div">
                                                {!! Form::label('layout_plan ','Layout Plan with location of ETP  :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="layout_plan" id="layout_plan"
                                                           onchange="uploadDocument('preview_layout_plan', this.id, 'validate_field_layout_plan',1)">
                                                    {!! $errors->first('layout_plan','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_layout_plan">
                                                        <input type="hidden" value="" id="validate_field_layout_plan"
                                                               name="validate_field_layout_plan">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('iee_report') ? 'has-error': ''}}"
                                                 id="iee_report_div">
                                                {!! Form::label('iee_report ','IEE/EIA report :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="iee_report" id="iee_report"
                                                           onchange="uploadDocument('preview_iee_report', this.id, 'validate_field_iee_report',1)">
                                                    {!! $errors->first('iee_report','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_iee_report">
                                                        <input type="hidden" value="" id="validate_field_iee_report"
                                                               name="validate_field_iee_report">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('emp_report') ? 'has-error': ''}}"
                                                 id="emp_report_div" style="display: none">
                                                {!! Form::label('emp_report ','EMP Report  :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="emp_report" class="emp-report"
                                                           id="emp_report"
                                                           onchange="uploadDocument('preview_emp_report', this.id, 'validate_field_emp_report',1)">
                                                    {!! $errors->first('emp_report','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_emp_report">
                                                        <input type="hidden" value="" id="validate_field_emp_report"
                                                               name="validate_field_emp_report">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('feasibility_report') ? 'has-error': ''}}"
                                                 id="feasibility_report_div">
                                                {!! Form::label('feasibility_report ','Feasibility Report :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    <input type="file" name="feasibility_report" id="feasibility_report"
                                                           onchange="uploadDocument('preview_feasibility_report', this.id, 'validate_field_feasibility_report',1)">
                                                    {!! $errors->first('feasibility_report','<span class="help-block">:message</span>') !!}
                                                    <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                    <div id="preview_feasibility_report">
                                                        <input type="hidden" value=""
                                                               id="validate_field_feasibility_report"
                                                               name="validate_field_feasibility_report">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="highrise">
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('file_city_corporation') ? 'has-error': ''}}"
                                                     id="file_city_corporation_div">
                                                    {!! Form::label('file_city_corporation ','City Corporation  :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="file_city_corporation" class=""
                                                               id="file_city_corporation"
                                                               onchange="uploadDocument('preview_file_city_corporation', this.id, 'validate_field_file_city_corporation',1)">
                                                        {!! $errors->first('file_city_corporation','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_file_city_corporation">
                                                            <input type="hidden" value=""
                                                                   id="validate_field_file_city_corporation"
                                                                   name="validate_field_file_city_corporation">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('file_metropoliton') ? 'has-error': ''}}"
                                                     id="file_metropoliton_div">
                                                    {!! Form::label('file_metropoliton ','Metropoliton Police :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="file_metropoliton"
                                                               id="file_metropoliton"
                                                               onchange="uploadDocument('preview_file_metropoliton', this.id, 'validate_field_file_metropoliton',1)">
                                                        {!! $errors->first('file_metropoliton','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_file_metropoliton">
                                                            <input type="hidden" value=""
                                                                   id="validate_field_file_metropoliton"
                                                                   name="validate_field_file_metropoliton">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('file_fire_service') ? 'has-error': ''}}"
                                                     id="file_fire_service_div">
                                                    {!! Form::label('file_fire_service ','Fire Service & Civil Defence :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="file_fire_service" class=""
                                                               id="file_fire_service"
                                                               onchange="uploadDocument('preview_file_fire_service', this.id, 'validate_field_file_fire_service',1)">
                                                        {!! $errors->first('file_fire_service','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_file_fire_service">
                                                            <input type="hidden" value=""
                                                                   id="validate_field_file_fire_service"
                                                                   name="validate_field_file_fire_service">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('file_owasa') ? 'has-error': ''}}"
                                                     id="file_owasa_div">
                                                    {!! Form::label('file_owasa ','OWASA :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="file_owasa" id="file_owasa"
                                                               onchange="uploadDocument('preview_file_owasa', this.id, 'validate_field_file_owasa',1)">
                                                        {!! $errors->first('file_owasa','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_file_owasa">
                                                            <input type="hidden" value="" id="validate_field_file_owasa"
                                                                   name="validate_field_file_owasa">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('file_titas_gas') ? 'has-error': ''}}"
                                                     id="file_titas_gas_div">
                                                    {!! Form::label('file_titas_gas ','Titas Gas transmission & Distribution Co :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="file_titas_gas" class=""
                                                               id="file_titas_gas"
                                                               onchange="uploadDocument('preview_file_titas_gas', this.id, 'validate_field_file_titas_gas',1)">
                                                        {!! $errors->first('file_titas_gas','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_file_titas_gas">
                                                            <input type="hidden" value=""
                                                                   id="validate_field_file_titas_gas"
                                                                   name="validate_field_file_titas_gas">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('file_civil_aviation') ? 'has-error': ''}}"
                                                     id="file_civil_aviation_div">
                                                    {!! Form::label('file_civil_aviation ','NOC of Civil Aviation :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="file_civil_aviation"
                                                               id="file_civil_aviation"
                                                               onchange="uploadDocument('preview_file_civil_aviation', this.id, 'validate_field_file_civil_aviation',1)">
                                                        {!! $errors->first('file_civil_aviation','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_file_civil_aviation">
                                                            <input type="hidden" value=""
                                                                   id="validate_field_file_civil_aviation"
                                                                   name="validate_field_file_civil_aviation">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('file_bidut') ? 'has-error': ''}}"
                                                     id="file_bidut_div">
                                                    {!! Form::label('file_bidut ','Electricity Distribution Authrority :',['class'=>'text-left col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="file_bidut" class="" id="file_bidut"
                                                               onchange="uploadDocument('preview_file_bidut', this.id, 'validate_field_file_bidut',1)">
                                                        {!! $errors->first('file_bidut','<span class="help-block">:message</span>') !!}
                                                        <span style="color:#993333; font-size: 9px;">[N.B. Supported file extension is pdf,png,jpg,jpeg.Max file size less than 2MB]</span>
                                                        <div id="preview_file_bidut">
                                                            <input type="hidden" value="" id="validate_field_file_bidut"
                                                                   name="validate_field_file_bidut">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div> {{---end panel body---}}
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Declaration & Submit</h3>
                        <fieldset>
                            <div class="panel panel-primary hiddenDiv">
                                <div class="panel-heading"><strong>3. Terms and Conditions</strong></div>
                                <div class="panel-body">
                                    <div class="col-md-12" style="margin: 12px 0;">
                                        <input id="acceptTerms" type="checkbox" name="acceptTerms"
                                               class="col-md-1 text-left required" style="width:3%;">
                                        <label for="acceptTerms" class="col-md-11 text-left">I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given.</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="pull-left">
                            <button type="submit" class="btn btn-info btn-md cancel"
                                    value="draft" name="actionBtn" id="save_as_draft">Save as Draft
                            </button>
                        </div>
                        <div class="pull-left" style="padding-left: 1em;">
                            <button type="submit" id="submitForm" style="cursor: pointer;"
                                    class="btn btn-success btn-md"
                                    value="submit" name="actionBtn"> Submit
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div> {{---end box body--}}
        </div>
    </div>
</section>

{{--step js--}}
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>


<script>
    {{----step js calling here---}}
    $(document).ready(function () {

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/doe/get-refresh-token';
            <?php
            $testmode = 1;
            if($testmode ==1){
            ?>
            $('#certificate_type').select2()
            $('#industry_id').select2()
            $('#district_id').select2()
            $('#certificate_type').click()
            $('#industry_id').click()
            $('#district_id').keydown()
            $('#fee_category_id').keydown()
            <?php
            }
            ?>

        });



        var form = $("#DOEForm").show();
        form.find('#submitForm').css('display', 'none');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // bank challan fee

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // return true;
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
                if (currentIndex == 2) {
                    form.find('#submitForm').css('display', 'block');
                } else {
                    form.find('#submitForm').css('display', 'none');
                    form.find('#save_as_draft').css('display', 'block');
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
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=DOEForm@2'); ?>');
            } else {
                return false;
            }
        });
        $(function () {
            $('#district_id').keydown();
        });
    });

    {{----end step js---}}

    /**
     * @function industryTypeSelectedOthersDiv
     * @param typeOfIndustry
     */
    function industryTypeSelectedOthersDiv(typeOfIndustry) {

        var targer_value = typeOfIndustry.split("@")[0];

        if (targer_value !== '') {

            //industry type wise other box show
            if (targer_value == 12) {
                document.getElementById('othersDiv').style.display = 'block';
                otherCategoryLoad();
            } else {
                document.getElementById('othersDiv').style.display = 'none';
                industryTypeWiseCategoryLoad(typeOfIndustry);
            }

            if (targer_value == 13) {
                document.getElementById('etp_file_div').style.display = 'none';
                document.getElementById('fund_allocation_div').style.display = 'none';
                document.getElementById('area_for_etp_div').style.display = 'none';
                document.getElementById('highrise').style.display = 'block';

            } else {
                document.getElementById('etp_file_div').style.display = 'block';
                document.getElementById('fund_allocation_div').style.display = 'block';
                document.getElementById('area_for_etp_div').style.display = 'block';
                document.getElementById('highrise').style.display = 'none';

            }
        } else {
            var option = "<option value=''>Select One</option>";
            $('#category_id').html(option);
            $('#change_colours').hide();

        }


    }

    // end function
    /**
     * load other category
     */
    function otherCategoryLoad() {
        $('#industry_id').next().hide();
        $('#industry_id').after('<span class="loading_data">Loading...</span>');
        $("#category_id").html('<option value="">Please Wait...</option>');

        let e = $(this)
        let api_url = "{{$doe_api_url}}category/"
        let calling_id = 'industry_id';
        let selected_value = ''
        let dependent_section_id = "category_id" // for callback
        let element_id = "id" //dynamic id for callback
        let element_name = "name" //dynamic name for callback
        let element_color = "color_code"
        let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}
        let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, element_color]
        let apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: 'OSS_BIDA'
            },
        ]

        apiCallGet(e, options, apiHeaders, otherDependantCallbackResponse, arrays);



    }
    /**
     * load industry type wise category
     *@param industryId
     */

    function industryTypeWiseCategoryLoad() {
        $('#industry_id').next().hide();
        $('#industry_id').after('<span class="loading_data">Loading...</span>');
        $("#category_id").html('<option value="">Please Wait...</option>');
        let industry = $('#industry_id').val();
        let industry_id = industry.split("@")[0];
        if (industry_id) {
            let e = $(this)
            let api_url = "{{$doe_api_url}}industry/category/" + industry_id
            let calling_id = 'industry_id';
            let selected_value = ''
            let dependent_section_id = "category_id" // for callback
            let element_id = "id" //dynamic id for callback
            let element_name = "name" //dynamic name for callback
            let element_color = "color_code"
            let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}
            let arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id, element_color]
            let apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ]

            apiCallGet(e, options, apiHeaders, industryDependantCallbackResponse, arrays);

        } else {
            $("#category_id").html('<option value="">Select District First</option>');
            $("#category_id").next().hide();
        }
    }

    /**
     * @function categoryWiseFileShow
     * @param category
     */


    function categoryWiseFileShow(categoryWithColor) {

        /**
         * category wise file show anKd hide
         * ETP file
         * Layout plan file
         * Feasibility report file
         */

        var category = categoryWithColor.split("@")[0];
        var certType = $("#certificate_type").val();
        var certTypeId = certType.split('@')[0];
        var industry = $('#industry_id').val().split("@")[1];

        var color = categoryWithColor.split("@")[1];
        $("#change_colours").css("background-color", '#' + color);
        if (category == '7' || category == '8') {
            $('#layout_plan_div').hide();
            $('#feasibility_report_div').hide();
            $('#iee_report_div').hide();
            $('#emp_report_div').hide();
            $('#area_for_etp_div').hide();
            $('#etp_file_div').hide();
            $('#fund_allocation_div').hide();

        } else {
            $('#layout_plan_div').show();
            $('#feasibility_report_div').show();
            $('#iee_report_div').show();
            $('#emp_report_div').show();
            if (industry == 'Highrise Building') {
                $('#area_for_etp_div').hide();
                $('#etp_file_div').hide();
                $('#fund_allocation_div').hide();

            } else {
                $('#area_for_etp_div').show();
                $('#etp_file_div').show();
                $('#fund_allocation_div').show();

            }

        }


        var proposed_industry = $('input[name="application_type"]:checked').val();
        industryWiseEMPReportShow(proposed_industry);


    }


    /**
     * @function industryWiseEMPReportShow
     * @param industryType
     */
    function industryWiseEMPReportShow(industryType) {
        //industry type wise EMP report show and hide
        // var category = $('#category_id').val().split("@")[0];
        // // if (industryType == 'existing' && (category == 9 || category == 10)) { //10 = red, 9 = orange B
        // if  (category == 9 || category == 10) { //10 = red, 9 = orange B
        //     var type = $('#certificate_type').val().split("@")[1];
        //     if (type != 'environment_clearance') {
        //         document.getElementById('emp_report_div').style.display = 'block';
        //         document.getElementById('iee_report_div').style.display = 'block';
        //     }
        //
        // } else {
        //     document.getElementById('emp_report_div').style.display = 'none';
        //     document.getElementById('iee_report_div').style.display = 'none';
        //
        // }

        //industry type and category wise IEE/EIA report file show and hide
        // if (industryType == 'proposed' && (category == 9 || category == 10)) { //10 = red, 9 = orange B
        //     document.getElementById('iee_report_div').style.display = 'block';
        // } else {
        //     document.getElementById('iee_report_div').style.display = 'none';
        // }
    }

    function ValidateEmail() {
        var email = document.getElementById("txtEmail").value;
        var lblError = document.getElementById("lblError");
        lblError.innerHTML = "";
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (!expr.test(email)) {
            lblError.innerHTML = "Invalid email address.";
        }
    }

    $(document).ready(function () {
        $("form#DOEForm").validate({
            errorPlacement: function () {
                return false;
            }
        });

        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            widgetPositioning: {
                vertical: 'bottom',
                horizontal: 'left'
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
        });


        $('#district_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            // $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$doe_api_url}}district";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            var apiendpoint = 'district'
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl,apiendpoint:apiendpoint};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $("#district_id").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            $("#thana").html('<option value="">Please Wait...</option>');
            let district = $('#district_id').val();
            let district_id = district.split("@")[0];
            if (district_id) {
                let e = $(this)
                let api_url = "{{$doe_api_url}}thana/" + district_id
                let calling_id = $(this).attr('id')
                let selected_value = ''
                let dependent_section_id = "thana" // for callback
                let element_id = "id" //dynamic id for callback
                let element_name = "name" //dynamic name for callback
                let element_calling_id = "" //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}
                let arrays = [calling_id, selected_value, element_id, element_name,element_calling_id, dependent_section_id]
                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#thana").html('<option value="">Select District First</option>');
                $(self).next().hide();
            }

        });
        $("#district_id").trigger('change');

        $("#thana").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $("#submitting_office").html('<option value="">Please Wait...</option>');
            var district = $('#district_id').val();
            var thana = $('#thana').val();
            var districtId = district.split("@")[0];
            var thanaId = thana.split("@")[0];
            if (districtId !== '' && thanaId !== '' ) {
                let e = $(this)
                let api_url = "{{$doe_api_url}}submitting-office/" + districtId +'/'+thanaId
                let calling_id = $(this).attr('id')
                let selected_value = ''
                let dependent_section_id = "submitting_office" // for callback
                let element_id = "id" //dynamic id for callback
                let element_name = "name" //dynamic name for callback
                let element_calling_id = "" //dynamic name for callback
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl}
                let arrays = [calling_id, selected_value, element_id, element_name,element_calling_id, dependent_section_id]
                apiCallGet(e, options, apiHeaders, submitDependantCallbackResponse, arrays);

            } else {
                $("#submitting_office").html('<option value="">Select Thana First</option>');
                $(self).next().hide();
            }

        });


        $('#fee_category_id').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$doe_api_url}}fee-category";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "name";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            apiCallGet(e, options, apiHeaders, feeCallbackResponse, arrays);

        })

        $("#fee_category_id").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $("#fee_id").html('<option value="">Please Wait...</option>');
            var fee_category = $('#fee_category_id').val();
            var fee_category_id = fee_category.split("@")[0];
            if (fee_category_id !== '') {
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");
                let e = $(this)
                let api_url = "{{$doe_api_url}}fee/" + fee_category_id
                let calling_id = $(this).attr('id')
                let selected_value = ''
                let dependent_section_id = "fee_id" // for callback
                let element_id = "id" //dynamic id for callback
                let element_from_amount = "from_amount" //dynamic name for callback
                let element_to_amount = "to_amount" //dynamic name for callback
                var apiendpoint = 'application-type/'+fee_category_id
                let options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl,apiendpoint:apiendpoint}
                let arrays = [calling_id, selected_value, element_id, element_from_amount,element_to_amount, dependent_section_id]
                apiCallGet(e, options, apiHeaders, feeDependantCallbackResponse, arrays);

            } else {
                $("#fee_id").html('<option value="">Select Fee category First</option>');
                $(self).next().hide();
            }

        });


        $("#certificate_type").on('change', function () {
            var type = $(this).val().split("@")[1];
            var categoryData = $('#category_id').val().split("@")[0];
            var industry = $('#industry_id').val().split("@")[1];


            if (type == 'environment_clearance') {
                $("#fee_bank_chllan").show();
                $("#old_trade").hide();
                $("#old_trade_license").removeClass('required');
                $("#old_trade").find('label').removeClass('required-star');
                $("#nocertificate").show();
                $("#noc").addClass('required');
                $("#nocertificate").find('label').addClass('required-star');

            } else if (type == 'site_clearance') {
                $("#fee_bank_chllan").show();
                $("#old_trade").hide();
                $("#old_trade_license").removeClass('required');
                $("#old_trade").find('label').removeClass('required-star');
                $("#nocertificate").show();

                $("#noc").addClass('required');
                $("#nocertificate").find('label').addClass('required-star');


            } else if (type == 'EIA_Approval' || type == 'TOR_Approval' || type == 'Zero_discharged_Approval') {

                $("#fee_bank_chllan").hide();
                $("#nocertificate").hide();
                $("#old_trade").hide();
                $("#old_trade_license").removeClass('required');
                $("#old_trade").find('label').removeClass('required-star');
                $("#noc").removeClass('required');
                $("#nocertificate").find('label').removeClass('required-star');


            } else if (type == 'renew') {
                $("#fee_bank_chllan").show();
                $("#old_trade").show();
                $("#nocertificate").hide();

                $("#old_trade_license").addClass('required');
                $("#old_trade").find('label').addClass('required-star');
                $("#noc").removeClass('required');
                $("#nocertificate").find('label').removeClass('required-star');

            } else {
                $("#fee_bank_chllan").show();
                $("#nocertificate").show();
                $("#old_trade").hide();
                $("#old_trade_license").removeClass('required');
                $("#old_trade").find('label').removeClass('required-star');
                $("#noc").removeClass('required');
                $("#nocertificate").find('label').removeClass('required-star');
            }

        });

        $(".application_type").on('change', function () {
            var applicationType = $('.application_type:checked').val();
            if (applicationType == 'proposed') {
                $("#proposed").show();
                $("#existing").hide();
            } else if (applicationType == 'existing') {
                $("#proposed").hide();
                $("#existing").show();
            }
        });
        $(".application_type").trigger('change');


        $("#fee_id").on('change', function () {
            var char = '@';
            var str = $(this).val();
            console.log(str);
            if (str != '' && str != 0) {
                $('#spinner2').show();
                $(".fee_cat").prop("checked", false);
                $('#fee_cat').addClass('required');
                var renewfee = $('option:selected', this).attr('renewfee');
                var fee = $('option:selected', this).attr('fee');
                $('#renew_fee_label').text(renewfee);
                $('#new_fee_label').text(fee);

                // var str2 = renewfee.substring(3,renewfee.length).replace(/,/g, "");
                // var str3 = fee.substring(3,fee.length).replace(/,/g, "");
                // $('#renew_fee').val(str2);
                // $('#new_fee').val(str3);
            } else {
                $('.fee_cat').removeClass('required');
                $('#spinner2').hide();
                $(".fee_cat").prop("checked", false);
            }
        });


        //fee represent for bank challan total fee
        $('input[name=fee_type]').on('change', function () {
            var fee_type = $(this).val();
            var fee = $('#' + fee_type + '_fee_label').text();
            $("#totalfee").val(fee.slice(3).replace(/,/g, ""));
        });

        /*document upload start*/

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: 'OSS_BIDA'
            },
        ]
        $('#certificate_type').one('click', function (el) {
            let key = el.which
            // if (typeof key !== "undefined") {
            //     return false
            // }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$doe_api_url}}application-type";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "typeName";//dynamic name for callback
            let element_calling_id = "typeId";//dynamic name for callback
            let data = '';//Third option to make id
            var apiendpoint = 'application-type';
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl,apiendpoint:apiendpoint};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })

        $('#industry_id').one('click', function (el) {
            let key = el.which
            // if (typeof key !== "undefined") {
            //     return false
            // }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$doe_api_url}}industry";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "industry_type";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            var apiendpoint = 'industry'
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl,apiendpoint:apiendpoint};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        })



        // $('#industry_id').select2();

    });
    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
    }
    function feeCallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            if(response.data == ''){
                response.data = [{'id':1,'name':'Industry'},{'id':2,'name':'Brick'}];
            }
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name]
                option += '<option value="' + id + '">' + value + '</option>'
            })
        } else {
            console.log(response.status)
        }
        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
    }
    function feeDependantCallbackResponse(response, [calling_id, selected_value, element_id, element_to_amount, element_from_amount, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_to_amount]+' '+row[element_from_amount];
                let value =row[element_to_amount]+' '+row[element_from_amount];
                option += '<option value="' + id + '" renewfee= "'+ row['renew_fee'] +'" fee= "'+ row['fee'] +'">' + value + '</option>';
            })
        } else {
            console.log(response.responseCode)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + dependant_select_id).select2();
        $("#" + calling_id).next().hide()
    }

    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = (element_calling_id === '' || element_calling_id == null) ? (row[element_id] + '@' + row[element_name]) : (row[element_id] + '@' + row[element_calling_id] + '@' + row[element_name]);
                let value = row[element_name]
                option += '<option value="' + id + '">' + value + '</option>'
            })
        } else {
            console.log(response.responseCode)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + dependant_select_id).select2();
        $("#" + calling_id).next().hide()
    }

    function submitDependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            let id = response.data[element_id] + '@' + response.data[element_name];
            let value = response.data[element_name];
            option += '<option value="' + id + '">' + value + '</option>'
        } else {
            console.log(response.responseCode)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + dependant_select_id).select2();
        $("#" + calling_id).next().hide()
    }
    function industryDependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, dependent_section_id,element_color]) {
        var option ='';
        if (response.responseCode === 200) {
            var id = response.data[element_id] + '@' + response.data[element_name]+ '@' + response.data[element_color];
            var value = response.data[element_name];
            option += '<option value="' + id + '">' + value + '</option>'
            $("#change_colours").css("background-color", '#'+response.data[element_color]);
            $('#category_id').attr('readonly', true);
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        // $("#" + dependent_section_id).select2();
        $("#" + calling_id).next().hide();
    }

    function otherDependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, dependent_section_id,element_color]) {
        var option ='';
        console.log(response)
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name]+'@' + row[element_color];
                var value = row[element_name];
                option += '<option value="' + id + '">' + value + '</option>'
            });

        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id + " option[value]").remove();
        $("#" + dependent_section_id).append(option).trigger('change');
        // $("#" + dependent_section_id).select2();
        $("#change_colours").css("background-color", '');
        $('#category_id').attr('readonly', 'false');
        $("#" + calling_id).next().hide();
    }

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
            var action = "{{URL::to('/doe/upload-document')}}";
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

