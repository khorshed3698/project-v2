<?php
$accessMode = ACL::getAccsessRight('E-tin');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<style>
    .ml-20 {
        margin-left: 39px !important;
        width: 95%;
        border-color: #ccc;
        border-radius: 4px;
        background-color: inherit;
    }

    .form-group {
        margin-bottom: 2px;
    }

    .img-thumbnail {
        height: 80px;
        width: 100px;
    }

    input[type=radio].error,
    input[type=checkbox].error {
        outline: 1px solid red !important;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .table-striped > tbody#manpower > tr > td, .table-striped > tbody#manpower > tr > th {
        text-align: center;
    }
</style>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                {{--start application form with wizard--}}
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}

                <div class="panel panel-info">
                    <div class="panel-heading">

                        <div class="row" style="margin:15px 0 5px 0">

                            <div class="col-md-7">
                                <h5><strong> Application for E-TIN to National Board of Revenue (NBR) </strong></h5>
                                <div class="heading_img">
                                    <img class="img-responsive pull-left"
                                         src="{{ asset('assets/images/u34.png') }}"/>
                                </div>
                                <div class="heading_text pull-left">
                                    National Board of Revenue (NBR)
                                </div>
                            </div>

                            <div class="col-md-5 wait_for_response"></div>

                        </div>

                        <div class="clearfix"></div>

                    </div>
                    <div class="form-body">
                        {{-- Breadcumb bar --}}
                        @if ($viewMode == 'on' || (isset($appInfo->status_id) && $appInfo->status_id == 5))
                            <section class="content-header">
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                    <li><strong> Date of
                                            Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}
                                    </li>
                                    <li><strong>Current Status : </strong>
                                        @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                        @else {!! $appInfo->status_name !!}
                                        @endif
                                    </li>
                                    <li>
                                        @if($appInfo->desk_id != 0) <strong>Current Desk :</strong>
                                        {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                        @else
                                            <strong>Current Desk :</strong> Applicant
                                        @endif
                                    </li>

                                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                                        <li>
                                            <a href="{{ url($appInfo->certificate_link) }}"
                                               class="btn show-in-view btn-xs btn-info"
                                               title="Download Approval Letter" target="_blank"> <i
                                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                        </li>
                                    @endif
                                </ol>
                            </section>
                        @endif
                        {{-- End of Breadcumb bar --}}

                        @if (isset($appInfo) && $appInfo->status_id > 1)
                            @if(($appInfo->status_id == 25 && Auth::user()->user_type == '5x505') || Auth::user()->user_type != '5x505')
                                <div class="box">
                                    <div class="box-body">
                                        <div class="panel panel-info" id="inputForm">

                                            <div class="panel-body" style="font-size: 14px;">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2">
                                                            <b> Reference Number:</b>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{$appInfo->ref_no}}

                                                        </div>
                                                        <div class="col-md-2">
                                                            <b>Etin Number</b>
                                                        </div>
                                                        <div class="col-md-4">
                                                            {{$appInfo->etin_number}}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="panel-body">
                            <a class="btn btn-sm btn-success" data-toggle="collapse" href="#paymentInfo"
                               role="button" aria-expanded="false" aria-controls="collapseExample" style=" margin-top: -5px; margin-bottom: 7px; ">
                                <i class="fa fa-money"></i> <strong>Payment Info</strong>
                            </a>
                            {!! Form::open(array('url' => '/licence-application/e-tin/add','method' => 'post','id' => 'etinApplicationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}

                            {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
                            {!! Form::hidden('curr_process_status_id', $appInfo->status_id,['class' => 'form-control input-md required', 'id'=>'process_status_id']) !!}

                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>


                            <fieldset>
                                <div class="panel panel-info">
                                    {{--<div class="panel-heading margin-for-preview"><strong>A. Company Information</strong></div>--}}
                                    <div class="panel-body ">
                                        <div id="validationError"></div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 form-group {{$errors->has('taxpayer_status') ? 'has-error': ''}}">
                                                    {!! Form::label('taxpayer_status','Taxpayer\'s Status',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('taxpayer_status', $taxpayerStatus, $appInfo->taxpayer_status, ['class'=>'form-control input-md required', 'id' => 'taxpayer_status', 'required']) !!}
                                                        {!! $errors->first('taxpayer_status','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                    {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('organization_type_id', $eaOrganizationType,$appInfo->organization_type_id, ['class'=>'form-control input-md', 'id' => 'organization_type_id','readonly','required']) !!}
                                                        {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('reg_type') ? 'has-error': ''}}">
                                                    {!! Form::label('reg_type','Registration Type',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('reg_type', $registrationType,$appInfo->reg_type, ['class'=>'form-control input-md', 'id' => 'reg_type', 'required']) !!}
                                                        {!! $errors->first('reg_type','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group cat-2 hide {{$errors->has('existing_tin_no') ? 'has-error': ''}}">
                                                    {!! Form::label('existing_tin_no','Existing(10 Digits) TIN',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('existing_tin_no', $appInfo->existing_tin_no, ['class'=>'form-control input-md ', 'id' => 'existing_tin_no']) !!}
                                                        {!! $errors->first('existing_tin_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group cat-1 hide {{$errors->has('main_source_income') ? 'has-error': ''}}">
                                                    {!! Form::label('main_source_income','Main Source of Income',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('main_source_income', $mainSourceIncome,$appInfo->main_source_income, ['class'=>'form-control input-md ', 'id' => 'main_source_income']) !!}
                                                        {!! $errors->first('main_source_income','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cat-1 hide form-group {{$errors->has('main_source_income_location') ? 'has-error': ''}}">
                                                    {!! Form::label('main_source_income_location','Location of main source of income',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('main_source_income_location', $districts, $appInfo->main_source_income_location, ['class'=>'form-control input-md valid', 'id' => 'main_source_income_location','required']) !!}
                                                        {!! $errors->first('main_source_income_location','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 cat-1 hide form-group {{$errors->has('company_id') ? 'has-error': ''}}">
                                                    {!! Form::label('company_id','Company',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        <select class="form-control input-md valid"
                                                                id="company_id" name="company_id">
                                                            <option value="">Select One</option>
                                                            @foreach($companiesOnLocation as $company)

                                                                <option company_required_status="{{$company->required_status}}"
                                                                        {{ ($appInfo->company_id == $company->id) ? 'selected' : '' }} value="{{$company->id}}">{{$company->value}} </option>
                                                            @endforeach
                                                        </select>
                                                        {!! $errors->first('company_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div id="juri_sub_list_name_div"
                                                     {!! isset($selectedJuriDiction->Juri_sub_list_name_status) && $selectedJuriDiction->Juri_sub_list_name_status == 0 ? 'style="display: none"' : ''!!}
                                                     class="col-md-12 form-group">
                                                    {!! Form::label('juri_sub_list_name','Company Name',['class'=>'col-md-5 required-star text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('juri_sub_list_name', $appInfo->juri_sub_list_name, ['class' => 'form-control input-md required', 'maxlength' => 200, 'required']) !!}

                                                        {!! $errors->first('juri_sub_list_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--Payment info area--}}
                                <div id="paymentInfo" class="collapse">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h5><strong>Payment Info</strong></h5>
                                        </div>
                                        <div class="panel-body">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <strong>Service Fee Payment</strong>
                                                    {{--@if($viewMode == 'on')--}}
                                                    {{--<a class="btn btn-xs btn-info pull-right" data-toggle="collapse" href="#servicePaymentShow" role="button" aria-expanded="false" aria-controls="collapseExample">--}}
                                                    {{--Payment Details--}}
                                                    {{--</a>--}}
                                                    {{--@endif--}}
                                                </div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                                                {!! Form::label('sfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
                                                                <div class="col-md-7">
                                                                    {!! Form::text('sfp_contact_name', $appInfo->sfp_contact_name, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                                    {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                                {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                                                <div class="col-md-7">
                                                                    {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md required email', 'readonly']) !!}
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
                                                                    {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control input-md required phone_or_mobile', 'readonly']) !!}
                                                                    {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                                {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                                                <div class="col-md-7">
                                                                    {!! Form::text('sfp_contact_address', $appInfo->sfp_contact_address, ['class' => 'bigInputField form-control input-md required','readonly']) !!}
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
                                                                    {!! Form::text('sfp_pay_amount', $appInfo->sfp_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                                    {!! $errors->first('sfp_pay_amount','<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 {{$errors->has('sfp_vat_tax') ? 'has-error': ''}}">
                                                                {!! Form::label('sfp_vat_tax','VAT/ TAX',['class'=>'col-md-5 text-left']) !!}
                                                                <div class="col-md-7">
                                                                    {!! Form::text('sfp_vat_tax', $appInfo->sfp_vat_tax, ['class' => 'form-control input-md', 'readonly']) !!}
                                                                    {!! $errors->first('sfp_vat_tax','<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6 {{$errors->has('sfp_bank_charge') ? 'has-error': ''}}">
                                                                {!! Form::label('sfp_bank_charge','Bank Charge',['class'=>'col-md-5 text-left']) !!}
                                                                <div class="col-md-7">
                                                                    {!! Form::text('sfp_bank_charge', $appInfo->sfp_bank_charge, ['class' => 'form-control input-md', 'readonly']) !!}
                                                                    {!! $errors->first('sfp_bank_charge','<span class="help-block">:message</span>') !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                {!! Form::label('sfp_total_amount','Total Amount',['class'=>'col-md-5 text-left']) !!}
                                                                <div class="col-md-7">
                                                                    {!! Form::text('sfp_total_amount', number_format($appInfo->sfp_pay_amount + $appInfo->sfp_vat_tax + $appInfo->sfp_bank_charge, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                                                {!! Form::label('sfp_status','Payment Status',['class'=>'col-md-5 text-left']) !!}
                                                                <div class="col-md-7">
                                                                    @if($appInfo->sfp_payment_status == 0)
                                                                        <span class="label label-warning">Pending</span>
                                                                    @elseif($appInfo->sfp_payment_status == -1)
                                                                        <span class="label label-info">In-Progress</span>
                                                                    @elseif($appInfo->sfp_payment_status == 1)
                                                                        <span class="label label-success">Paid</span>
                                                                    @elseif($appInfo->sfp_payment_status == 2)
                                                                        <span class="label label-danger">-Exception</span>
                                                                    @elseif($appInfo->sfp_payment_status == 3)
                                                                        <span class="label label-warning">Waiting for Payment Confirmation</span>
                                                                    @else
                                                                        <span class="label label-warning">invalid status</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($appInfo->sfp_payment_status != 1)
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="alert alert-danger" role="alert">
                                                                        Vat/ tax and service charge is an approximate
                                                                        amount, it
                                                                        may vary based on the Sonali Bank system.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="panel-footer">
                                                    <div class="pull-left">
                                                        <a href="/spg/stack-holder/counter-payment-voucher/{{ Encryption::encodeId($appInfo->gf_payment_id)}}"
                                                           target="_blank" class="btn btn-info btn-md"
                                                           style="margin-top: -5px;margin-bottom: 7px;">
                                                            <strong> Download voucher</strong>
                                                        </a>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-body ">
                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name','Name of Organization/ Company/ Industrial Project',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('company_name', $appInfo->company_name, ['class' => 'form-control input-md required','readonly', 'required']) !!}
                                                        {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group {{$errors->has('incorporation_certificate_number') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_certificate_number','Incorporation Number',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('incorporation_certificate_number', $appInfo->incorporation_certificate_number, ['class' => 'form-control input-md','id'=>'incorporation_certificate_number','style'=>"display:inline",'required','maxlength' => "100",'required']) !!}

                                                        {!! $errors->first('incorporation_certificate_number','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="date_of_incorpotation_info"
                                                     class="col-md-12 form-group {{$errors->has('incorporation_certificate_date') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_certificate_date','Date of incorporation',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div class="date_of_incorpotation input-group date">
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                            {!! Form::text('incorporation_certificate_date', ($appInfo->incorporation_certificate_date == '0000-00-00' ? '' : date('d-M-Y', strtotime($appInfo->incorporation_certificate_date))), ['class' => 'form-control input-md','id'=>'incorporation_certificate_date','style'=>"display:inline",'placeholder'=>'dd-mm-yyyy', 'required']) !!}
                                                        </div>
                                                        {!! $errors->first('incorporation_certificate_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_designation','Principal Promoter Designation',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_designation', $designationAsEtin, $appInfo->ceo_designation, ['class' => 'form-control required-star input-md ','id'=>'ceo_designation', 'required']) !!}
                                                        {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_full_name', $appInfo->ceo_full_name, ['class' => 'form-control input-md ','id'=>'ceo_full_name','style'=>"display:inline",'maxlength' => "500", 'required']) !!}

                                                        {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-12 form-group {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_mobile_no','Mobile Number',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_mobile_no', $appInfo->ceo_mobile_no, ['class' => 'form-control input-md','id'=>'ceo_mobile_no','style'=>"display:inline",'maxlength' => "100",'required']) !!}

                                                        {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('ceo_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_fax_no','Fax',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_fax_no', $appInfo->ceo_fax_no, ['class' => 'form-control input-md number','id'=>'ceo_fax_no','style'=>"display:inline"]) !!}

                                                        {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_email','Email',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_email', $appInfo->ceo_email, ['class' => 'form-control input-md ','id'=>'ceo_email','style'=>"display:inline",'maxlength' => "100", 'required']) !!}

                                                        {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>


                            <fieldset>
                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>Principal Promoter Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_country_id', [18 => "Bangladesh"], $appInfo->ceo_country_id, ['class' => 'form-control  input-md ','id'=>'ceo_country_id','required']) !!}
                                                        {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div id=""
                                                     class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_thana_id','Police Station',['class'=>'col-md-5 text-left required-star','placeholder'=>'Select district first']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_thana_id',$thana, $appInfo->ceo_thana_id, ['maxlength'=>'80','class' => 'form-control input-md','placeholder' => 'Select district first','required']) !!}
                                                        {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div id=""
                                                     class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_district_id','District ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_district_id',$districts, $appInfo->ceo_district_id, ['maxlength'=>'80','class' => 'form-control input-md','required']) !!}
                                                        {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_post_code','Post Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_post_code', $appInfo->ceo_post_code, ['maxlength'=>'80','class' => 'form-control input-md engOnly ','required', 'maxlength' => "50"]) !!}
                                                        {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 {{$errors->has('ceo_address') ? 'has-error' : ''}}">
                                                    {!! Form::label('ceo_address','Address',['class'=>'col-md-2 text-left  required-star']) !!}
                                                    <div class="col-md-10 ">
                                                        {!! Form::textarea('ceo_address', $appInfo->ceo_address, ['class' => 'form-control input-md bigInputField ml-20' ,'required', 'size' =>'5x2','data-rule-maxlength'=>'240','maxlength' => "500"]) !!}
                                                        {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>Registered Office Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('reg_office_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('reg_office_country_id','Country',['class'=>'text-left col-md-5  required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('reg_office_country_id', [18 => "Bangladesh"], $appInfo->reg_office_country_id, ['class' => 'form-control  imput-md', 'id' => 'reg_office_country_id','required']) !!}
                                                        {!! $errors->first('reg_office_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_thana_id',$thana, $appInfo->office_thana_id, ['class' => 'form-control input-md ','placeholder' => 'Select district first','required']) !!}
                                                        {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_district_id', $districts, $appInfo->office_district_id, ['class' => 'form-control input-md ','required']) !!}
                                                        {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6  {{$errors->has('office_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star','required']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_post_code', $appInfo->office_post_code, ['class' => 'form-control input-md', 'maxlength' => "50"]) !!}
                                                        {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class=" col-md-12 {{$errors->has('office_address') ? 'has-error' : ''}}">
                                                    {!! Form::label('office_address','Address',['class'=>'col-md-2 required-star']) !!}
                                                    <div class="col-md-10">
                                                        {!! Form::textarea('office_address', $appInfo->office_address, ['class' => 'form-control input-md bigInputField ml-20', 'size' =>'5x2','data-rule-maxlength'=>'240','maxlength' => "500",'required']) !!}
                                                        {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading "><strong>Others Address ( Working address/ Business
                                            address)</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('other_address_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_country_id','Country',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('other_address_country_id',  [''=>'Select Country', 18 => "Bangladesh"], $appInfo->other_address_country_id , ['class' => 'form-control  imput-md', 'id' => 'other_address_country_id', 'required']) !!}
                                                        {!! $errors->first('other_address_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('other_address_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_thana_id','Police Station', ['class'=>'col-md-5 text-left  required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('other_address_thana_id',$thana, $appInfo->other_address_mthana_id, ['class' => 'form-control input-md ','placeholder' => 'Select district first','id'=>'other_address_thana_id','required']) !!}
                                                        {!! $errors->first('other_address_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6  {{$errors->has('other_address_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_district_id','District',['class'=>'col-md-5 text-left  required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('other_address_district_id', $districts, $appInfo->other_address_district_id, ['class' => 'form-control input-md ','placeholder' => 'Select One','id'=>'other_address_district_id','required']) !!}
                                                        {!! $errors->first('other_address_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('other_address_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('other_address_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('other_address_post_code', $appInfo->other_address_post_code, ['class' => 'form-control input-md','required', 'maxlength' => "50"]) !!}
                                                        {!! $errors->first('other_address_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">

                                                <div class=" col-md-12 {{$errors->has('other_address') ? 'has-error' : ''}}">
                                                    {!! Form::label('other_address','Address',['class'=>'col-md-2 required-star','style'=>"margin-right: 40px;"]) !!}
                                                    <div class="col-md-9">
                                                        {!! Form::textarea('other_address',$appInfo->other_address, ['class' => 'form-control input-md bigInputField', 'size' =>'5x2','data-rule-maxlength'=>'240','required','maxlength' => "500"]) !!}
                                                        {!! $errors->first('other_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 wait_for_response">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    @if(ACL::getAccsessRight('E-tin','-E-') && $viewMode == "on")

                                        @if($appInfo->sfp_payment_status == 3 && empty($certificateId))
                                            <div class="pull-left">
                                                <a href="/spg/stack-holder/counter-payment-voucher/{{ Encryption::encodeId($appInfo->gf_payment_id)}}"
                                                   target="_blank" class="btn btn-info btn-md">
                                                    <strong> Download voucher</strong>
                                                </a>
                                            </div>

                                            <div class="pull-right">
                                                <a href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(1)}}"
                                                   class="btn btn-primary btn-md">
                                                    <strong> Confirm payment request</strong>
                                                </a>
                                                <a onclick="if (! confirm('Are you sure?')) { return false; }"
                                                   href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(0)}}"
                                                   class="btn btn-danger btn-md">
                                                    <strong> Cancel payment request</strong>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                    @if(ACL::getAccsessRight('E-tin','-E-') && $viewMode == "off")
                                        @if(!empty($certificateId))
                                            <a style="cursor: pointer;" class="btn btn-success btn-md submit"
                                               href="{{url('/licence-applications/show-certificate/'.\App\Libraries\Encryption::encodeId($appInfo->id).'/'.\App\Libraries\Encryption::encodeId($certificateId) ) }}">View
                                                Certificate</a>
                                        @else
                                            @if($appInfo->status_id != 5)
                                                @if($appInfo->sfp_payment_status == 3)
                                                    <div class="pull-left">
                                                        <a href="/spg/stack-holder/counter-payment-voucher/{{ Encryption::encodeId($appInfo->gf_payment_id)}}"
                                                           target="_blank" class="btn btn-info btn-md">
                                                            <strong> Download voucher</strong>
                                                        </a>
                                                    </div>

                                                    <div class="pull-right">
                                                        <a href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(1)}}"
                                                           class="btn btn-primary btn-md">
                                                            <strong> Confirm payment request</strong>
                                                        </a>
                                                        <a onclick="if (! confirm('Are you sure?')) { return false; }"
                                                           href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(0)}}"
                                                           class="btn btn-danger btn-md">
                                                            <strong> Cancel payment request</strong>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="pull-left">
                                                        <button type="submit" class="btn btn-info btn-md cancel"
                                                                value="draft" name="actionBtn">Save as Draft
                                                        </button>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button type="submit"
                                                                id="{{($alreadyPaymentCount > 0) ? 'etin_submit' : ''}}"
                                                                style="cursor: pointer;"
                                                                class="btn btn-success btn-md submit"
                                                                value="Submit"
                                                                name="actionBtn">{{($alreadyPaymentCount > 0) ? 'Submit For Certificate' : 'Submit For Payment'}}
                                                        </button>
                                                    </div>
                                                @endif
                                            @elseif($appInfo->status_id == 5)
                                                <div class="pull-left">
                                                    <button type="submit"
                                                            id="{{($alreadyPaymentCount > 0) ? 'etin_submit' : ''}}"
                                                            style="cursor: pointer;"
                                                            class="btn btn-info btn-md submit"
                                                            value="Submit"
                                                            name="actionBtn">{{($alreadyPaymentCount > 0) ? 'Re-Submit For Certificate' : 'Re-Submit For Payment'}}
                                                    </button>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        <style>
                                            .wizard > .actions {
                                                top: -15px !important;
                                            }
                                        </style>
                                    @endif
                                </div>
                            </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    var redirectFromPaymentFlag = '{{ $redirectFromPaymentFlag }}';

    if (redirectFromPaymentFlag == '1') {

        var submitBtn = $('button#etin_submit[name="actionBtn"][type="submit"]');

        var form = $('#etinApplicationForm');

        generateEtin(form, submitBtn, 'only-get-tin');
    }

    $(document).ready(function () {

        $("#organization_type_id option:not(:selected)").prop('disabled', true);
        $(".readOnlyCl option:not(:selected)").prop('disabled', true);

        $('#reg_type').on('change', function () {
            var cat_id = $(this).val();
            if (cat_id == 1) {
                $('.cat-1').removeClass('hide').slideDown(200);

                $('.cat-1').each(function () {
                    $(this).find('select,input').attr('required', true);
                });

                $('.cat-2').hide();
                $('.cat-2 input').each(function () {
                    $(this).val('');
                    //$(this).val('');
                });
            } else if (cat_id == 2) {
                $('.cat-2').removeClass('hide').slideDown(200);

                $('.cat-1').each(function () {
                    $(this).find('select,input').attr('required', false);
                });
                $('.cat-1').hide();
                $('.cat-1 select').each(function () {
                    $(this).val('');
                    //$(this).val('');
                });
            } else {
                $('.cat-1').hide(100);
                $('.cat-2').hide(100);
            }
        });
        $('#reg_type').trigger('change');
        $('.date_of_incorpotation').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
        });

        $('.commercial_operation_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: 'now',
        });

        $('#EtinFormApplication').validate();
        $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea, .readOnly').attr('readonly', true);


        $('[data-toggle="tooltip"]').tooltip();

        $('input[name=is_registered]:checked').trigger('click');


        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker_registration_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 50),
            maxDate: today,
        });

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
//            minDate: '01/01/'+(yyyy-10),
//            maxDate: '01/01/'+(yyyy+10)
            maxDate: 'now'
        });


        $("#ceo_district_id").change(function () {
            var districtId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/licence-applications/e-tin/get-thana-by-district",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if (id == '{{ $appInfo->ceo_thana_id }}') {
                                option += '<option value="' + id + '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#ceo_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });
        // $('#ceo_district_id').trigger('change');

        $("#office_district_id").change(function () {
            var districtId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/licence-applications/e-tin/get-thana-by-district",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if (id == '{{ $appInfo->office_thana_id }}') {
                                option += '<option value="' + id + '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#office_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });

        });


        $("#other_address_district_id").change(function () {
            var districtId = $(this).val();
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/licence-applications/e-tin/get-thana-by-district",
                data: {
                    districtId: districtId
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            option += '<option value="' + id + '">' + value + '</option>';
                        });
                    }
                    $("#other_address_thana_id").html(option);
                    $(self).next().hide('slow');
                }
            });
        });

        $("#main_source_income_location").change(function (event) {


            $('#juri_sub_list_name_div').hide();

            var mainSourceIncomeLocation = $(this).find(":selected").val();
            var mainSourceIncome = $('#main_source_income').val();

            if (mainSourceIncomeLocation) {

                var mainSourceCompanyUrl = base_url + "/licence-applications/e-tin/get-company-list";

                getApiDataFromTable('company_id', mainSourceCompanyUrl, {
                    'main_souce_income': mainSourceIncome,
                    'main_souce_income_location': mainSourceIncomeLocation,
                });
            }
        });


        $("#company_id").change(function (event) {
            var companyNameRequiredStatus = $(this).find(":selected").attr('company_required_status');
            if (companyNameRequiredStatus == 1) {

                $('#juri_sub_list_name_div input#juri_sub_list_name').val($('#company_name').val());
                $('#juri_sub_list_name_div input#juri_sub_list_name').prop('required', true).addClass('required');
                $('#juri_sub_list_name_div').show();
            } else {

                $('#juri_sub_list_name_div input#juri_sub_list_name').val('');
                $('#juri_sub_list_name_div input#juri_sub_list_name').removeAttr('required').removeClass('required');
                $('#juri_sub_list_name_div').hide();
            }
        });


        $('button#etin_submit[name="actionBtn"][type="submit"]').on('click', function (event) {

            event.preventDefault();
            var validFlag = true;

            $('#etinApplicationForm :input').each(function () {

                if ($(this).prop('required')) {

                    var parentDiv = $(this).parent().parent();
                    parentDiv.find('label.error').remove();
                    parentDiv.find('label.text-danger').remove();

                    if ($(this).val().length === 0 || ($(this).find('option:selected').val() == '' && $(this).find('option:selected').val() != 'undefined')) {
                        var fieldName = parentDiv.find('label').html();
                        fieldName = (fieldName != null && fieldName != undefined && fieldName.length != 0) ? fieldName : 'This field ';
                        parentDiv.find('div').append('<label class="text-danger control-label"><small>' + fieldName + ' is required * </small></label>');
                        validFlag = false;
                    } else {
                        parentDiv.removeClass("has-error")
                        parentDiv.find('label.text-danger').remove();
                    }
                }
            });

            if (validFlag) {

                var form = $(this).closest('form');

                var btn = $(this);

                generateEtin(form, btn, 'update');
            }
        });
    });


    function generateEtin(form, btn, action = 'update') {

        btn.prop('disabled', true);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: form.serialize() + '&' + encodeURI(btn.attr('name')) + "=" + encodeURI(btn.attr('value')) + '&action=' + encodeURI(action),

            success: function (response) {

                if (response.responseCode == 1) {

                    $('.wait_for_response').html('<div class="alert alert-warning text-center"><i class="fa fa-refresh fa-spin fa-2x fa-fw margin-bottom"></i><strong">&nbsp;&nbsp;Please wait for response from NBR Server! <span id="nbr_status_text"></span></strong> </div>');

                    checkgenerator(response.app_id);

                } else if (response.responseCode == 2) {

                    btn.html(btn_content);
                    btn.prop('disabled', false);
                    swal({
                        type: (response.type) ? response.type : 'info',
                        title: (response.title) ? response.title : 'Oops...',
                        text: response.message
                    });
                    $('.wait_for_response').html('');
                    return false;
                } else {
                    btn.html(btn_content);
                    btn.prop('disabled', false);
                    $('.wait_for_response').html('');
                    swal({type: 'error', title: 'Oops...', text: response.message});
                    return false;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                btn.html(btn_content);
                btn.prop('disabled', false);
                $('.wait_for_response').html('');
                swal({type: 'error', title: 'Oops...', text: 'Sorry, There is a problem'});
                console.log(jqXHR.responseText);
                return false;
            },
        });
    }


    function getApiDataFromTable(targetFieldId, urlPath, data) {
        $("#" + targetFieldId).after('<span class="loading_data">Loading...</span>');
        $.ajax({
            type: "GET",
            url: urlPath,
            data: data,
            success: function (response) {
                var option = '<option value="">Select One</option>';
                if (response.responseCode == 1) {
                    $.each(response.data, function (id, value) {

                        option += '<option company_required_status = "' + value.required_status + '" value="' + value.id + '">' + value.value + '</option>';
                    });
                }
                $("#" + targetFieldId).html(option);
                $("#" + targetFieldId).parent().find('span.loading_data').remove();
            }
        });
    }


    function checkgenerator(app_id) {

        $.ajax({
            url: '/licence-applications/e-tin/check-api-request-status',
            type: "POST",
            data: {
                app_id: app_id,
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.responseCode == 1) {
                    window.location.href = '/licence-applications/show-certificate/' + app_id + '/' + response.row_id;
                } else if (response.responseCode == 0) {
                    $('.wait_for_response span#nbr_status_text').html(response.message);
                    myVar = setTimeout(checkgenerator, 1000, app_id);
                } else {

                    var btn = $('button#etin_submit[name="actionBtn"][type="submit"]');
                    btn.prop('disabled', false);
                    btn.find('i.fa-spinner').remove();
                    $('.wait_for_response').html('');

                    swal({type: 'error', title: 'Oops...', text: response.message});
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
        });
        return false; // keeps the page from not refreshing
    }


    @if ($viewMode == 'on')
    $('#EtinFormApplication :input').attr('disabled', true);
    $('#EtinFormApplication h3').hide();
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">' + this.value + '</span>');
    });
    // all radio or checkbox which is not checked, that will be hide
    $('#EtinFormApplication :input[type=radio], input[type=checkbox]').each(function () {
        if (!$(this).is(":checked")) {
            //alert($(this).attr('name'));
            $(this).parent().replaceWith('');
            $(this).replaceWith('');
        }
    });
    $('#EtinFormApplication :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on

</script>
<script src="{{ asset("assets/scripts/custom.js") }}" type="text/javascript"></script>