<?php
$accessMode = ACL::getAccsessRight('SBaccount');
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

    .form-horizontal .form-group {
        margin-right: 0px;
        margin-left: 0px;
    }

    .form-group {
        margin-bottom: 0px;
    }

    legend.scheduler-border {
        padding: 0 15px;
        margin-left: 20px;
    }

    .select2 {
        display: block !important;
    }

    .wizard > .steps > ul > li {
        width: 20% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
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

    .none-pointer {
        pointer-events: none;
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
                        <h5><strong>Application For New Bank Account</strong></h5>
                    </div>

                    {!! Form::open(array('url' => 'sb-account/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'SbAccount',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"
                           id="app_id"/>
                    <h3 class="text-center stepHeader"> Bank Information</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Bank Information</strong></div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('bank_name','Name of Bank :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('bank_name','Sonali Bank Limited',['class' => 'form-control input-md ','id'=>'bank_name','readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('bank_district','Name of District :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('bank_district',[],'',['class' => 'form-control input-md search-box','id'=>'bank_district']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('bank_branch','Name of Branch :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('bank_branch',[],'',['class' => 'form-control input-md search-box','id'=>'bank_branch']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Account info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('account_title_bn','Title of Account (In Bangla) :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('account_title_bn',$appData->account_title_bn,['class' => 'form-control input-md ','id'=>'account_title_bn','placeholder'=>'Title of Account (In Bangla)']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('account_title_en','Title of Account (In English: Block Letter) :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('account_title_en',$appData->account_title_en,['class' => 'form-control input-md ','id'=>'account_title_en','placeholder'=>'Title of Account (In English) ']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('bank_nature','Nature of Account:',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('ac_nature',[],'',['class' => 'form-control input-md search-box','id'=>'ac_nature']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('bank_nature','Category:',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('customer_category',[],'',['class' => 'form-control input-md ','id'=>'customer_category']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('customer_sub_category','Sub Category :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('customer_sub_category',[''=>'Select Category First'],'',['class' => 'form-control input-md ','id'=>'customer_sub_category']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('currency','Currency :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('currency',[],'',['class' => 'form-control input-md search-box','id'=>'currency']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('account_operation','Method of Account Operation :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('account_operation',[],'',['class' => 'form-control input-md search-box','id'=>'account_operation']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('entity_type','Entity Type :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('entity_type',[],'',['class' => 'form-control input-md search-box','id'=>'entity_type']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('account_type','Account Type:',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('account_type',[],'',['class' => 'form-control input-md search-box','id'=>'account_type']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                    <h3 class="text-center stepHeader"> Institutional Information</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Organization Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('organization_name_en','Name of the Organization (In English) :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('organization_name_en',$appData->organization_name_en,['class' => 'form-control input-md ','id'=>'organization_name_en','placeholder'=>'Name of the Organization (In English)']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('nature_of_organization','Nature of Organization :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('nature_of_organization',[],'',['class' => 'form-control input-md search-box','id'=>'nature_of_organization']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('vat_reg_no','VAT Registration Number/BIN :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('vat_reg_no',$appData->vat_reg_no,['class' => 'form-control input-md ','id'=>'vat_reg_no','placeholder'=>'Enter VAT Registration Number/BIN']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('tax_no','Tax ID Number (E-TIN) :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('tax_no',$appData->tax_no,['class' => 'form-control input-md ','id'=>'tax_no','placeholder'=>'Enter Tax ID Number (E-TIN)']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Trade License Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('tl_no','Trade License Number :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('tl_no',$appData->tl_no,['class' => 'form-control input-md ','id'=>'tl_no','placeholder'=>'Enter Trade License Number']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('tl_date','Date of Trade License :',['class'=>'text-left col-md-5']) !!}
                                            <div class="datepicker col-md-7">
                                                {!! Form::text('tl_date', $appData->tl_date,['class' => 'form-control
                                                input-md','id'=>'tl_date','style'=>'background:white;']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('expiry_date','Expired  Date :',['class'=>'text-left col-md-5']) !!}
                                            <div class="datepickerFuture col-md-7">
                                                {!! Form::text('expiry_date',!empty($appData->expiry_date) ? $appData->expiry_date : '',['class' => 'form-control
                                                input-md','id'=>'expiry_date','style'=>'background:white;']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('issue_authority','Issuing Authority :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('issue_authority',$appData->issue_authority,['class' => 'form-control input-md ','id'=>'issue_authority','placeholder'=>'Enter Issuing Authority']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Registration Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('registration_no','Registration number :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('registration_no',$appData->registration_no,['class' => 'form-control input-md ','id'=>'registration_no','placeholder'=>'Enter Registration number']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('registration_date','Registration Date :',['class'=>'text-left col-md-5']) !!}
                                            <div class="datepicker col-md-7">
                                                {!! Form::text('registration_date',$appData->registration_date,['class' => 'form-control
                                                input-md','id'=>'registration_date','style'=>'background:white;']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('reg_expiry_date','Expired Date :',['class'=>'text-left col-md-5']) !!}
                                            <div class="datepickerFuture col-md-7">
                                                {!! Form::text('reg_expiry_date',!empty($appData->reg_expiry_date)?$appData->reg_expiry_date:"",['class' => 'form-control
                                                input-md','id'=>'reg_expiry_date','style'=>'background:white;']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('registration_country','Registration Country :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('registration_country',[],'',['class' => 'form-control input-md search-box','id'=>'registration_country']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('registration_authority','Registration Authority :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('registration_authority',$appData->registration_authority,['class' => 'form-control input-md ','id'=>'registration_authority','placeholder'=>'Enter Registration Authority']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('registration_address','Registered Address :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('registration_address',$appData->registration_address,['class' => 'form-control input-md ','id'=>'registration_address','placeholder'=>'Enter Registered Address ']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Office Address Info</strong></div>
                            <div class="panel-body">
                                {!! Form::hidden('office_thana_code',!empty($appData->office_thana_code)?$appData->office_thana_code:'',['class' => 'form-control input-md ','id'=>'office_thana_code']) !!}
                                {!! Form::hidden('office_dsp_code',!empty($appData->office_dsp_code)?$appData->office_dsp_code:'',['class' => 'form-control input-md ','id'=>'office_dsp_code']) !!}
                                {!! Form::hidden('office_division',!empty($appData->office_division)?$appData->office_division:'',['class' => 'form-control input-md ','id'=>'office_division']) !!}
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border"> Office Address
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('office_country','Country :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('office_country',[],'',['class' => 'form-control input-md search-box','id'=>'office_country']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('office_post','Post Office :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_post',!empty($appData->office_post)?$appData->office_post:'',['class' => 'form-control input-md ','id'=>'office_post','placeholder'=>'Enter Post Office']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('office_thana','Thana/PS :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_thana',!empty($appData->office_thana)?$appData->office_thana:'',['class' => 'form-control input-md ','id'=>'office_thana','placeholder'=>'Enter Thana/PS','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('office_dsp','District/State/Provence :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_dsp',!empty($appData->office_dsp)?$appData->office_dsp:'',['class' => 'form-control input-md ','id'=>'office_dsp','placeholder'=>'District/State/Provence :','readonly']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('office_road','Road/Village :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_road',!empty($appData->office_road)?$appData->office_road:'',['class' => 'form-control input-md ','id'=>'office_road','placeholder'=>'Enter Road/Village']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('office_phone','Phone/Mobile Number :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_phone',!empty($appData->office_phone)?$appData->office_phone:'',['class' => 'form-control input-md ','id'=>'office_phone','placeholder'=>'Enter Phone/Mobile Number']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('office_email','Email :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('office_email',!empty($appData->office_email)?$appData->office_email:'',['class' => 'form-control input-md email','id'=>'office_email','placeholder'=>'Enter email Adddress']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Business Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('type_of_business','Type of Business :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('type_of_business',[],'',['class' => 'form-control input-md ','id'=>'type_of_business']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('nature_of_bus','Nature of Business :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('nature_of_bus',[],'',['class' => 'form-control input-md search-box','id'=>'nature_of_bus']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('yearly_turnover','Yearly Turnover :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('yearly_turnover',$appData->yearly_turnover,['class' => 'form-control input-md onlyNumber','id'=>'yearly_turnover','placeholder'=>'Enter Yearly Turnover']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('human_resource','Number of human resource :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('human_resource',$appData->human_resource,['class' => 'form-control input-md onlyNumber','id'=>'human_resource','placeholder'=>'Enter Number of human resource']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('net_of_org','Net assets of the organization :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('net_of_org',$appData->net_of_org,['class' => 'form-control input-md ','id'=>'net_of_org','placeholder'=>'Enter Net assets of the organization']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h3 class="text-center stepHeader"> Personal Information</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Personal Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('account_oper_person_en','Name of account operating person (In English) :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('account_oper_person_en',$appData->account_oper_person_en,['class' => 'form-control input-md ','id'=>'account_oper_person_en','placeholder'=>'Account operating person Name(In English)']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('profession','Profession (Details) :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('profession',$appData->profession,['class' => 'form-control input-md ','id'=>'profession','placeholder'=>'Enter Profession (Details)']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('date_of_birth','Date of Birth :',['class'=>'text-left col-md-5']) !!}
                                            <div class="datepicker col-md-7">
                                                {!! Form::text('date_of_birth',$appData->date_of_birth,['class' => 'form-control
                                                input-md','id'=>'date_of_birth','readonly','style'=>'background:white;','placeholder'=>'dd/mm/yyyy']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('father_name','Fathers Name :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('father_name',$appData->father_name,['class' => 'form-control input-md ','id'=>'father_name','placeholder'=>'Enter Father Name']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('mothers_name','Mothers Name :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('mothers_name',$appData->mothers_name,['class' => 'form-control input-md ','id'=>'mothers_name','placeholder'=>'Enter Mothers Name']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('spouse_name','Spouse Name :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('spouse_name',$appData->spouse_name,['class' => 'form-control input-md ','id'=>'spouse_name','placeholder'=>'Enter Spouse Name']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('nationality_personal','Nationality :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('nationality_personal',[],'',['class' => 'form-control input-md search-box','id'=>'nationality_personal']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('sex','Gender  :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('sex',[],$appData->sex,['class' => 'form-control input-md ','id'=>'sex']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('resident','Resident Status  :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('resident',[],'',['class' => 'form-control input-md ','id'=>'resident']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('relation_with_org','Relation with Organization  :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('relation_with_org',$appData->relation_with_org,['class' => 'form-control input-md ','id'=>'relation_with_org','placeholder'=>'Enter Relation with Organization']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('tax_id_no','Tax Id/ Tax Prayer :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('tax_id_no',$appData->tax_id_no,['class' => 'form-control input-md ','id'=>'tax_id_no','placeholder'=>'Enter Profession (Details)']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('occupation_code','Occupation :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('occupation_code',[],'',['class' => 'form-control input-md search-box','id'=>'occupation_code']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('monthly_income','Monthly Income :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('monthly_income',$appData->source_of_fund,['class' => 'form-control input-md ','id'=>'monthly_income','placeholder'=>'Enter Monthly Income']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('source_of_fund','Source of Fund :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('source_of_fund',$appData->source_of_fund,['class' => 'form-control input-md ','id'=>'source_of_fund','placeholder'=>'Enter Source of Fund']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Address Info</strong></div>
                            <div class="panel-body">
                                {!! Form::hidden('present_thana_code',!empty($appData->present_thana_code)?$appData->present_thana_code:'',['class' => 'form-control input-md ','id'=>'present_thana_code']) !!}
                                {!! Form::hidden('present_dsp_code',!empty($appData->present_dsp_code)?$appData->present_dsp_code:'',['class' => 'form-control input-md ','id'=>'present_dsp_code']) !!}
                                {!! Form::hidden('present_division',!empty($appData->present_division)?$appData->present_division:'',['class' => 'form-control input-md ','id'=>'present_division']) !!}
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border"> Present Address
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_country','Present Country :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('present_country',[],'',['class' => 'form-control input-md search-box','id'=>'present_country']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_post','Post Office :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('present_post',$appData->present_post,['class' => 'form-control input-md ','id'=>'present_post','placeholder'=>'Enter Post Office']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_thana','Thana/PS :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('present_thana',$appData->present_thana,['class' => 'form-control input-md ','id'=>'present_thana','placeholder'=>'Enter Thana/PS','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_dsp','District/State/Provence :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('present_dsp',$appData->present_dsp,['class' => 'form-control input-md ','id'=>'present_dsp','placeholder'=>'District/State/Provence','readonly']) !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_road','Road/Village :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('present_road',$appData->present_road,['class' => 'form-control input-md ','id'=>'present_road','placeholder'=>'Enter Road/Village']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_phone','Phone/Mobile Number :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('present_phone',$appData->present_phone,['class' => 'form-control input-md ','id'=>'present_phone','placeholder'=>'Enter Phone/Mobile Number']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('present_email','Email :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('present_email',$appData->present_email,['class' => 'form-control input-md email','id'=>'present_email','placeholder'=>'Enter email Adddress']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                {!! Form::hidden('permanent_thana_code',!empty($appData->permanent_thana_code)?$appData->permanent_thana_code:'',['class' => 'form-control input-md ','id'=>'permanent_thana_code']) !!}
                                {!! Form::hidden('permanent_dsp_code',!empty($appData->permanent_dsp_code)?$appData->permanent_dsp_code:'',['class' => 'form-control input-md ','id'=>'permanent_dsp_code']) !!}
                                {!! Form::hidden('permanent_division',!empty($appData->permanent_division)?$appData->permanent_division:'',['class' => 'form-control input-md ','id'=>'permanent_division']) !!}
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border"> Permanent Address
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('permanent_country','Permanent Country :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::select('permanent_country',[],'',['class' => 'form-control input-md search-box','id'=>'permanent_country']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('permanent_post','Post Office :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('permanent_post',$appData->permanent_post,['class' => 'form-control input-md ','id'=>'permanent_post','placeholder'=>'Enter Post Office']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('permanent_thana','Thana/PS :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('permanent_thana',$appData->permanent_thana,['class' => 'form-control input-md ','id'=>'permanent_thana','placeholder'=>'Enter Thana/PS','readonly']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('permanent_dsp','District/State/Provence :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('permanent_dsp',$appData->permanent_dsp,['class' => 'form-control input-md ','id'=>'permanent_dsp','placeholder'=>'District/State/Provence :','readonly']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('permanent_road','Road/Village :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('permanent_road',$appData->permanent_road,['class' => 'form-control input-md ','id'=>'permanent_road','placeholder'=>'Enter Road/Village']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('permanent_phone','Phone/Mobile Number :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('permanent_phone',$appData->permanent_phone,['class' => 'form-control input-md ','id'=>'permanent_phone','placeholder'=>'Enter Phone/Mobile Number']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 col-xs-12">
                                                {!! Form::label('permanent_email','Email :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('permanent_email',$appData->permanent_email,['class' => 'form-control input-md email','id'=>'permanent_email','placeholder'=>'Enter email Adddress']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Identification Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('identification_country','Country :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('identification_country',[],'',['class' => 'form-control input-md search-box','id'=>'identification_country']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('identification_doc','Identification Document :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::select('identification_doc',[],'',['class' => 'form-control input-md ','id'=>'identification_doc','readonly']) !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-6 col-xs-12">
                                            {!! Form::label('identification_doc_no','Identification Document Number :',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('identification_doc_no',$appData->identification_doc_no,['class' => 'form-control input-md search-box','id'=>'identification_doc_no','placeholder'=>'Identification Document Number']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-12" id="identification_doc_date_issue_div">
                                            {!! Form::label('identification_doc_date_issue','Issue Date :',['class'=>'text-left col-md-5']) !!}
                                            <div class="datepicker col-md-7">
                                                {!! Form::text('identification_doc_date_issue', !empty($appData->identification_doc_date_issue)?$appData->identification_doc_date_issue:'',['class' => 'form-control
                                                input-md','id'=>'identification_doc_date_issue','style'=>'background:white;']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12" id="identification_doc_date_exp_div">
                                            {!! Form::label('identification_doc_date_exp','Expiry Date :',['class'=>'text-left col-md-5']) !!}
                                            <div class="datepicker col-md-7">
                                                {!! Form::text('identification_doc_date_exp',!empty($appData->identification_doc_date_exp)?$appData->identification_doc_date_exp:'',['class' => 'form-control
                                                input-md','id'=>'identification_doc_date_exp','style'=>'background:white;']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Others Info</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('purpose_account','The purpose of opening an account of a foreign company / institution (If applicable) :',['class'=>'text-left col-md-4']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('purpose_account',$appData->purpose_account,['class' => 'form-control input-md ','id'=>'purpose_account','placeholder'=>'The purpose of opening an account of a foreign company / institution (If applicable)']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('regulatory_authority','Name of the concerned regulatory authority :',['class'=>'text-left col-md-4']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('regulatory_authority',$appData->regulatory_authority,['class' => 'form-control input-md ','id'=>'regulatory_authority','placeholder'=>'Name of the concerned regulatory authority']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>


                    <h3 class="text-center stepHeader">Declaration & submit</h3>
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading" style="padding-bottom: 4px;">
                                <strong>Declaration and undertaking</strong>
                            </div>
                            <div class="panel-body">

                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border"> Authorization information
                                    </legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8 col-xs-12" style="margin: 10px;">
                                                {!! Form::label('authorization_info','Authorization information :',['class'=>'text-left col-md-5']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('authorization_info',$appData->authorization_info,['class' => 'form-control input-md ','id'=>'authorization_info','placeholder'=>'Enter Authorization information']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('accept_terms',1,null, array('id'=>'accept_terms',
                                            'class'=>'required')) !!}
                                            I do here by declare that the information given above is true to the best of
                                            my knowledge and I shall be liable for any false information/ system is
                                            given.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Service fee payment</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(),
                                                ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::email('sfp_contact_email', Auth::user()->user_email, ['class' =>
                                                'form-control input-md email required']) !!}
                                                {!! $errors->first('sfp_contact_email','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('sfp_contact_phone') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_phone', Auth::user()->user_phone, ['class' =>
                                                'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                            {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left
                                            required-star']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_contact_address', Auth::user()->road_no .
                                                (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' =>
                                                'form-control input-md required']) !!}
                                                {!! $errors->first('sfp_contact_address','<span class="help-block">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
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
                                                {!! Form::text('sfp_vat_on_pay_amount', number_format($payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                {!! Form::text('sfp_total_amount', number_format($payment_config->amount + $payment_config->vat_on_pay_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
                                            <div class="col-md-7">
                                                <span class="label label-warning">Not Paid</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{--Vat/ tax and service charge is an approximate amount--}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                Vat/ tax and service charge is an approximate amount, it may vary based
                                                on the
                                                Sonali Bank system.
                                            </div>
                                        </div>
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
@include('SBaccount::sb_scripts_edit')