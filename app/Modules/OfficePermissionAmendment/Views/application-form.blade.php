<?php
$accessMode = ACL::getAccsessRight('OfficePermissionAmendment');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<style>
    .form-group{
        margin-bottom: 2px;
    }
    .img-thumbnail{
        height: 80px;
        width: 100px;
    }
    textarea{
        resize: vertical;
    }
    .wizard > .steps > ul > li{
        width: 20% !important;
    }
    .wizard > .steps > ul > li a {
        padding: 0.5em 0.5em !important;
    }
    .wizard > .actions {
        top: -15px;
    }
    .wizard {
        overflow: visible;
    }
    .wizard > .content {
        overflow: visible;
    }

    .iti__flag-container{
        z-index: 999;
    }

    .mobile_flag_priority > .iti--separate-dial-code > .iti__flag-container {
        z-index: 9999;
    }


</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box"  id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>Application for Office Permission Amendment</strong></h5>
                        </div>
                        <div class="pull-right">
                            <a href="{{ asset('assets/images/SampleForm/office_permission_amendment.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                <i class="fas fa-file-pdf"></i>
                                Download Sample Form
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {!! Form::open(array('url' => 'office-permission-amendment/store','method' => 'post','id' => 'OfficePermissionAmendmentForm','enctype'=>'multipart/form-data',
                                'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        <input type="hidden" name="ref_app_approve_date" value="{{ (Session::get('opaInfo.approved_date') ? Session::get('opaInfo.approved_date') : '') }}">

                        <h3 class="stepHeader">Application Info.</h3>
                        <fieldset>
                            <legend class="d-none">Application Info.</legend>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Basic information</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                        {!! Form::label('is_approval_online','Did you receive your Office Permission approval from the online OSS?',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <label class="radio-inline">{!! Form::radio('is_approval_online','yes', (Session::get('opaInfo.is_approval_online') == 'yes' ? true :false), ['class'=>'cusReadonly helpTextRadio', 'id' => 'yes', 'onclick' => 'isApprovalOnline(this.value)']) !!}  Yes</label>
                                                            <label class="radio-inline">{!! Form::radio('is_approval_online', 'no', (Session::get('opaInfo.is_approval_online') == 'no' ? true :false), ['class'=>'cusReadonly', 'id' => 'no', 'onclick' => 'isApprovalOnline(this.value)']) !!}  No</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="ref_app_tracking_no_div" class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ref_app_tracking_no','Please give your approved office permission reference No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                {!! Form::text('ref_app_tracking_no', Session::get('opaInfo.ref_app_tracking_no'), ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm cusReadonly', 'placeholder'=>'OPN-01Jan2022-00001']) !!}
                                                                {!! $errors->first('ref_app_tracking_no','<span class="help-block">:message</span>') !!}
                                                                <span class="input-group-btn">
                                                                    @if(Session::get('opaInfo'))
                                                                        <button type="submit" class="btn btn-danger btn-sm" value="clean_load_data" name="actionBtn">Clear Loaded Data</button>
                                                                        <a href="{{ Session::get('opaInfo.certificate_link') }}" target="_blank" rel="noopener" class="btn btn-success btn-sm">View Certificate</a>
                                                                    @else
                                                                        <button type="submit" class="btn btn-success btn-sm" value="searchOPinfo" name="searchOPinfo" id="searchOPinfo">Load office permission data</button>
                                                                    @endif
                                                                </span>
                                                            </div>

                                                            <small class="text-danger">N.B.: Once you save or submit the application, the Office Permission tracking no cannot be changed anymore.</small>
                                                        </div>
                                                    </div>
                                                    <div id="manually_approved_no_div" class="col-md-12 hidden {{$errors->has('manually_approved_op_no') ? 'has-error': ''}} ">
                                                        {!! Form::label('manually_approved_op_no','Please give your manually approved office permission reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('manually_approved_op_no', '', ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm']) !!}
                                                            {!! $errors->first('manually_approved_op_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="issue_date_of_first_div"
                                                         class="col-md-12 {{$errors->has('date_of_office_permission') ? 'has-error': ''}}">

                                                        {!! Form::label('date_of_office_permission','Effective date of the previous office permission',['class'=>'text-left col-md-5 required-star']) !!}
                                                        <div class="col-md-7">
                                                            <div class="datepicker input-group date">
                                                                {!! Form::text('date_of_office_permission', (Session::get('opaInfo.approved_duration_start_date') ? date('d-M-Y', strtotime(Session::get('opaInfo.approved_duration_start_date'))) : ''), ['class' => 'form-control cusReadonly input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}

                                                                <span class="input-group-addon"><span
                                                                            class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('date_of_office_permission','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    {{-- <div id="office_type_div" class="col-md-6 hidden {{$errors->has('office_type') ? 'has-error': ''}}">
                                                        {!! Form::label('office_type','Office Type', ['class' => 'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_type', $officeType, Session::get('opaInfo.office_type'), ['placeholder' => 'Select One',
                                                            'class' => 'form-control cusReadonly input-md', 'id' => 'office_type', 'onchange' => "CategoryWiseDocLoad(this.value)"]) !!}
                                                            {!! $errors->first('office_type','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div> --}}

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Common Basic Information By Company Id --}}
                            @include('ProcessPath::basic-company-info')
                        </fieldset>

                        <h3 class="stepHeader">Office Info.</h3>
                        <fieldset>
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Effective date of amendment</legend>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('effective_date') ? 'has-error': ''}}">

                                                {!! Form::label('effective_date','Effective date',['class'=>'text-left col-md-5 required-star']) !!}
                                                <div class="col-md-7">
                                                    <div class="datepicker input-group date">
                                                        {!! Form::text('effective_date', '', ['class' => 'form-control input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}

                                                        <span class="input-group-addon"><span
                                                                    class="fa fa-calendar"></span></span>
                                                    </div>
                                                    {!! $errors->first('effective_date','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Previous Information of proposed branch/ liaison/ representative office</strong></div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table aria-label="Detailed Report Data Table" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                {{-- <th width="5%">#</th> --}}
                                                <th width="25%">Field name</th>
                                                <th width="35%" class="alert-warning text-center"
                                                    style="color: #fff; background-color: #f6d10f;">Existing information
                                                </th>
                                                <th width="35%" class="alert-success text-center"
                                                    style="color: #fff; background-color: #67db38;">Proposed information
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                {{-- <td>1</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="office_type_label">
                                                                {!! Form::label('office_type','Office Type',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::select('office_type', $officeType, Session::get('opaInfo.office_type'), ['placeholder' => 'Select Office Type','class' => 'form-control cusReadonly input-md', 'id' => 'office_type', 'onchange' => "CategoryWiseDocLoad(this.value)"]) !!}
                                                    {!! $errors->first('office_type','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                <input type="hidden" name="caption[n_office_type]" value="Office Type"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_office_type', $officeType, '', ['placeholder' => 'Select Office Type','class' => 'form-control input-md', 'id' => 'n_office_type', 'onchange' => "CategoryWiseDocLoad(this.value)", 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_office_type]", 1, null, ['class' => 'field', 'id' => 'n_office_type_check', 'onclick' => "toggleCheckBox('n_office_type_check', 'n_office_type');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_office_type','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>2</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                        <span class="helpTextCom" id="local_company_name_label">
                                                            {!! Form::label('local_company_name','Name of the local company',['class'=>'required-star']) !!}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('local_company_name', Session::get('opaInfo.local_company_name'), ['class'=>'form-control cusReadonly input-md', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name"]) !!}
                                                    {!! $errors->first('local_company_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_local_company_name]" value="Name of the Local company"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_local_company_name', '', ['class'=>'form-control input-md', 'id'=>"n_local_company_name", 'data-rule-maxlength'=>'255', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_local_company_name]", 1, null, ['class' => 'field', 'id' => 'n_local_company_name_check', 'onclick' => "toggleCheckBox('n_local_company_name_check', 'n_local_company_name');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_local_company_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>3</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_division_id_label">
                                                               {!! Form::label('ex_office_division_id','Division',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::select('ex_office_division_id', $divisions, Session::get('opaInfo.ex_office_division_id'), ['class' => 'form-control cusReadonly input-md', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id', ". Session::get('opaInfo.ex_office_district_id') .")"]) !!}
                                                    {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_division_id]" value="Division"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ex_office_division_id', $divisions, '', ['class' => 'form-control input-md', 'id' => 'n_ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('n_ex_office_division_id', this.value, 'n_ex_office_district_id')", 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_division_id]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_division_id_check', 'onclick' => "toggleCheckBox('n_ex_office_division_id_check', 'n_ex_office_division_id');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>4</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_district_id_label">
                                                               {!! Form::label('ex_office_district_id','District',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::select('ex_office_district_id', $district_eng, Session::get('opaInfo.ex_office_district_id'), ['class' => 'form-control cusReadonly input-md','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id', ". Session::get('opaInfo.ex_office_thana_id') .")"]) !!}
                                                    {!! $errors->first('ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_district_id]" value="District"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ex_office_district_id', [], '', ['class' => 'form-control input-md', 'id' => 'n_ex_office_district_id','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('n_ex_office_district_id', this.value, 'n_ex_office_thana_id')", 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_district_id]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_district_id_check', 'onclick' => "toggleCheckBox('n_ex_office_district_id_check', 'n_ex_office_district_id');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>5</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_thana_id_label">
                                                               {!! Form::label('ex_office_thana_id','Police station', ['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::select('ex_office_thana_id', $thana_eng, Session::get('opaInfo.ex_office_thana_id'), ['class' => 'form-control cusReadonly input-md','placeholder' => 'Select district first']) !!}
                                                    {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_thana_id]" value="Police Station"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ex_office_thana_id',[''], '', ['class' => 'form-control input-md', 'id' => 'n_ex_office_thana_id','placeholder' => 'Select district first', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_thana_id]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_thana_id_check', 'onclick' => "toggleCheckBox('n_ex_office_thana_id_check', 'n_ex_office_thana_id');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>6</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_post_office_label">
                                                                {!! Form::label('ex_office_post_office','Post office',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_post_office', Session::get('opaInfo.ex_office_post_office'), ['class' => 'form-control input-md cusReadonly']) !!}
                                                    {!! $errors->first('ex_office_post_office','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_post_office]" value="Post Office"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_post_office', '', ['class' => 'form-control input-md', 'id' => 'n_ex_office_post_office', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_post_office]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_post_office_check', 'onclick' => "toggleCheckBox('n_ex_office_post_office_check', 'n_ex_office_post_office');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_post_office','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>7</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_post_code_label">
                                                               {!! Form::label('ex_office_post_code','Post code', ['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_post_code', Session::get('opaInfo.ex_office_post_code'), ['class' => 'form-control cusReadonly input-md post_code_bd']) !!}
                                                    {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_post_code]" value="Post Code"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_post_code', '', ['class' => 'form-control input-md post_code_bd', 'id' => 'n_ex_office_post_code', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_post_code]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_post_code_check', 'onclick' => "toggleCheckBox('n_ex_office_post_code_check', 'n_ex_office_post_code');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>8</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_address_label">
                                                               {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_address', Session::get('opaInfo.ex_office_address'), ['maxlength'=>'150','class' => 'form-control cusReadonly input-md']) !!}
                                                    {!! $errors->first('ex_office_address','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_address]" value="House, Flat/ Apartment, Road"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_address', '', ['maxlength'=>'150','class' => 'form-control input-md', 'id' => 'n_ex_office_address', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_address]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_address_check', 'onclick' => "toggleCheckBox('n_ex_office_address_check', 'n_ex_office_address');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_address','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>9</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_telephone_no_label">
                                                               {!! Form::label('ex_office_telephone_no','Telephone no.') !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning mobile_flag_priority">
                                                    {!! Form::text('ex_office_telephone_no', Session::get('opaInfo.ex_office_telephone_no'), ['maxlength'=>'20','class' => 'form-control cusReadonly input-md phone_or_mobile']) !!}
                                                    {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_telephone_no]" value="Telephone No."/>
                                                    <div class="input-group mobile_flag_priority">
                                                        {!! Form::text('n_ex_office_telephone_no', '', ['maxlength'=>'20', 'id' => 'n_ex_office_telephone_no' ,'class' => 'form-control input-md phone_or_mobile haha', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_telephone_no]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_telephone_no_check', 'onclick' => "toggleCheckBox('n_ex_office_telephone_no_check', 'n_ex_office_telephone_no');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>10</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_mobile_no_label">
                                                                {!! Form::label('ex_office_mobile_no','Mobile no. ',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_mobile_no', Session::get('opaInfo.ex_office_mobile_no'), ['class' => 'form-control cusReadonly input-md helpText15' ,'id' => 'ex_office_mobile_no']) !!}
                                                    {!! $errors->first('ex_office_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_mobile_no]" value="Mobile No."/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_mobile_no', '', ['class' => 'form-control input-md phone_or_mobile' ,'id' => 'n_ex_office_mobile_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_mobile_no]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_mobile_no_check', 'onclick' => "toggleCheckBox('n_ex_office_mobile_no_check', 'n_ex_office_mobile_no');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>11</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_fax_no_label">
                                                                 {!! Form::label('ex_office_fax_no','Fax no. ') !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_fax_no', Session::get('opaInfo.ex_office_fax_no'), ['class' => 'form-control cusReadonly input-md']) !!}
                                                    {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_fax_no]" value="Fax No."/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_fax_no', '', ['class' => 'form-control input-md', 'id' => 'n_ex_office_fax_no', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_fax_no]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_fax_no_check', 'onclick' => "toggleCheckBox('n_ex_office_fax_no_check', 'n_ex_office_fax_no');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>12</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_email_label">
                                                                 {!! Form::label('ex_office_email','Email ',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_email', Session::get('opaInfo.ex_office_email'), ['class' => 'form-control cusReadonly email input-md']) !!}
                                                    {!! $errors->first('ex_office_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_email]" value="Email"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_email', '', ['class' => 'form-control email input-md', 'id' => 'n_ex_office_email', 'disabled' => 'disabled']) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_email]", 1, null, ['class' => 'field', 'id' => 'n_ex_office_email_check', 'onclick' => "toggleCheckBox('n_ex_office_email_check', 'n_ex_office_email');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>13</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="activities_in_bd_label">
                                                                 {!! Form::label('activities_in_bd','Activities in Bangladesh through the proposed branch/ liaison/ representative office (Max. 250 characters )', ['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    <div class="">
                                                        {!! Form::textarea('activities_in_bd', Session::get('opaInfo.activities_in_bd'), ['data-charcount-maxlength'=>'250', 'id' => 'activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control cusReadonly bigInputField input-md maxTextCountDown ',
                                                        'size'=>'10x3']) !!}
                                                        {!! $errors->first('activities_in_bd','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_activities_in_bd]" value="Activities in Bangladesh"/>
                                                    <div class="">
                                                        <div class="input-group">
                                                            {!! Form::textarea('n_activities_in_bd', '', ['data-charcount-maxlength'=>'250', 'id' => 'n_activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                        'size'=>'10x3', 'disabled' => 'disabled']) !!}
                                                            <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_activities_in_bd]", 1, null, ['class' => 'field', 'id' => 'n_activities_in_bd_check', 'onclick' => "toggleCheckBox('n_activities_in_bd_check', 'n_activities_in_bd');"]) !!}
                                                        </span>
                                                        </div>
                                                        {!! $errors->first('n_activities_in_bd','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv">

                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Declaration</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading" style="padding-bottom: 4px;">
                                    <strong>Declaration and undertaking</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ol type="a">
                                                    <li>
                                                        <p>I do hereby declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement given</p>
                                                    </li>
                                                    <li>
                                                        <p>I do hereby undertake full responsibility of the expatriate for whom visa recommendation is sought during their stay in Bangladesh. </p>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Authorized person of the organization</legend>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_full_name') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_full_name','Full Name',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_full_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', Auth::user()->designation, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', Auth::user()->user_phone, ['class' => 'form-control input-sm phone_or_mobile required', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', Auth::user()->user_email, ['class' => 'form-control input-sm email required', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail"
                                                                 src="{{ (!empty(Auth::user()->user_pic) ? url('users/upload/'.Auth::user()->user_pic) : url('assets/images/photo_default.png')) }}"
                                                                 alt="User Photo">
                                                        </div>
                                                        <input type="hidden" name="auth_image" value="{{ Auth::user()->user_pic }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                                I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Payment & Submit</h3>
                        
                        <fieldset>
                            <legend class="d-none">Payment & Submit</legend>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Service Fee Payment</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                                    {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md email required']) !!}
                                                    {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md phone_or_mobile required']) !!}
                                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
                                                    {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('sfp_pay_amount') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_pay_amount','Pay amount',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_pay_amount', $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                    {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_vat_on_pay_amount', $payment_config->vat_on_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                    {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('sfp_total_amount','Total Amount',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('sfp_total_amount', number_format($payment_config->amount + $payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                                {!! Form::label('sfp_status','Payment Status',['class'=>'col-md-5 text-left']) !!}
                                                <div class="col-md-7">
                                                    <span class="label label-warning">Not Paid</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" role="alert">
                                                    <b>Vat/ Tax</b> and <b>Transaction charge</b> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
                                                </div>
                                            </div>
                                        </div>
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
                            <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md"
                                    value="submit" name="actionBtn">Payment & Submit
                                <i class="fa fa-question-circle" style="cursor: pointer" data-toggle="tooltip" title="" data-original-title="After clicking this button will take you in the payment portal for providing the required payment. After payment, the application will be automatically submitted and you will get a confirmation email with necessary info." aria-describedby="tooltip"></i>
                            </button>
                        </div>

                    {!! Form::close() !!}<!-- /.form end -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script>
    function CategoryWiseDocLoad(office_type) {
        var attachment_key = "opa_";
        if (office_type == 1) {
            attachment_key += "branch";
        } else if (office_type == 2) {
            attachment_key += "liaison";
        } else {
            attachment_key += "representative";
        }

        if(office_type != 0 && office_type != ''){
            var _token = $('input[name="_token"]').val();
            var app_id = $("#app_id").val();
            var viewMode = $("#viewMode").val();

            $.ajax({
                type: "POST",
                url: '/office-permission-amendment/getDocList',
                dataType: "json",
                data: {_token : _token, attachment_key : attachment_key, app_id:app_id, viewMode:viewMode},
                success: function(result) {
                    if (result.html != undefined) {
                        $('#docListDiv').html(result.html);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //console.log(errorThrown);
                    alert('Unknown error occured. Please, try again after reload');
                },
            });
        }else{
            //console.log('Unknown Visa Type');
        }
    }

    function uploadDocument(targets, id, vField, isRequired) {
        var file_id = document.getElementById(id);
        var file = file_id.files;
        if (file && file[0]) {
            if (!(file[0].type == 'application/pdf')) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'The file format is not valid! Please upload in pdf format.'
                });
                file_id.value = '';
                return false;
            }

            var file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
            if (!(file_size <= 2)) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Max file size 2MB. You have uploaded ' + file_size + 'MB'
                });
                file_id.value = '';
                return false;
            }
        }

        var inputFile =  $("#" + id).val();
        if(inputFile == ''){
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="'+vField+'" name="'+vField+'">';
            if ($('#label_' + id).length) $('#label_' + id).remove();
            return false;
        }

        try{
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{url('/office-permission-amendment/upload-document')}}";

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
                url:action,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response){
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = parseInt(id.substring(4));
                    var newInput = $('<label class="saved_file_'+doc_id+'" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                        ' <a href="javascript:void(0)" onclick="EmptyFile('+ doc_id
                        +', '+ isRequired +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field != '') {
                        $("#"+id).removeClass('required');
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    function isApprovalOnline(value) {
        if (value == 'yes') {
            $("#ref_app_tracking_no_div").removeClass('hidden');
            $("#ref_app_tracking_no").addClass('required');
            $("#manually_approved_no_div").addClass('hidden');
            $("#manually_approved_op_no").removeClass('required');
            $("#office_type_div").removeClass('hidden');
        } else if(value == 'no') {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
            $("#manually_approved_no_div").removeClass('hidden');
            $("#manually_approved_op_no").addClass('required');
            $("#office_type_div").removeClass('hidden');
            $("#office_type").addClass('required');
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
            $("#office_type_div").addClass('hidden');
        }
    }
    var sessionLastOPN = '{{ Session::get('opaInfo.is_approval_online') }}';
    if(sessionLastOPN == 'yes') {
        isApprovalOnline(sessionLastOPN);
        // $("#ref_app_tracking_no").prop('readonly', true);

//        $("#local_c_city_district").trigger('change');
//        //$(".cusReadonly").prop('readonly', true);
        $(".cusReadonly").attr('readonly', true);
//        //$(".cusReadonly option:not(:selected)").prop('disabled', true);
        $(".cusReadonly option:not(:selected)").remove();
        $(".cusReadonly:radio:not(:checked)").attr('disabled', true);
    }

    function toggleCheckBox(boxId, newFieldId) {
        if (document.getElementById(boxId).checked) {
            document.getElementById(newFieldId).disabled = false;
            var field = document.getElementById(newFieldId);
            $(field).addClass("required");
        } else {
            document.getElementById(newFieldId).disabled = true;
            var field = document.getElementById(newFieldId);
            $(field).removeClass("required");
            $(field).removeClass("error");
            $(field).val("");
        }
    }

    $(document).ready(function(){

        $('#office_type').trigger('change');
        var form = $("#OfficePermissionAmendmentForm").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if(newIndex == 1){
                    var is_approval_online = $("input[name='is_approval_online']:checked").val();
                    if(is_approval_online == 'yes') {
                        if(sessionLastOPN == 'yes') {
                            return true;
                        } else {
                            alert('Please, load Office Permission data.');
                            return false;
                        }
                    }
                }

                if(newIndex == 2){
                    var atLeastOneChecked = $('input:checkbox.field').is(':checked');

                    if(atLeastOneChecked){
                        return form.valid();
                    }else{
                        alert('In order to Proceed please select atleast one field for amendment.');
                        return false;
                    }
                }

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex){
                    return true;
                }
                // Forbid next action on "Warning" step if the user is to young
                if (newIndex === 3 && Number($("#age-2").val()) < 18)
                {
                    return false;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex)
                {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex != 0) {
                    form.find('#save_as_draft').css('display','block');
                    form.find('.actions').css('top','-42px');
                } else {
                    form.find('#save_as_draft').css('display','none');
                    form.find('.actions').css('top','-15px');
                }

                if(currentIndex == 4) {
                    form.find('#submitForm').css('display','block');

                    $('#submitForm').on('click', function (e) {
                        form.validate().settings.ignore = ":disabled";
                        //console.log(form.validate().errors()); // show hidden errors in last step
                        return form.valid();
                    });
                } else {
                    form.find('#submitForm').css('display','none');
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

        $('#submitForm, #save_as_draft').on('click', function (e) {
            let $submitButton = $(this);
            let buttonId = $submitButton.attr('id');
            if (buttonId == 'submitForm' && !form.valid()) {
                alert('All inputs are not valid! Please fill in all the required fields.');
                return false;
            }
            // Check if the button was already clicked
            if ($submitButton.attr('data-clicked') === 'true') {
                e.preventDefault(); // Prevent double submission
                return false;
            }
            // Mark the button as clicked by setting an attribute
            $submitButton.attr('data-clicked', 'true');
            // Allow form submission to continue
            return true;
        });

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/office-permission-amendment/preview'); ?>', 'Sample', '');
            } else {
                return false;
            }
        });

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100)
        });
    });
</script>

{{--initail -input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
{{--//textarea count down--}}
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" src="" type="text/javascript"></script>
<script>
    $(function () {
        //max text count down
        $('.maxTextCountDown').characterCounter();

        $("#ex_office_mobile_no").intlTelInput({
            hiddenInput: ["ex_office_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#n_ex_office_mobile_no").intlTelInput({
            hiddenInput: ["n_ex_office_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#ex_office_telephone_no").intlTelInput({
            hiddenInput: ["ex_office_telephone_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#n_ex_office_telephone_no").intlTelInput({
            hiddenInput: ["n_ex_office_telephone_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#auth_mobile_no").intlTelInput({
            hiddenInput: ["auth_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: ["sfp_contact_phone"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
    });
</script>
{{--initail -input plugin script end--}}
