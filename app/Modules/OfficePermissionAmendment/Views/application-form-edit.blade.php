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
    .blink_me {
        animation: blinker 5s linear infinite;
    }

    @keyframes blinker {
        50% { opacity: .5; }
    }

</style>

<section class="content" id="applicationForm">
    @include('ProcessPath::remarks-modal')
    <div class="col-md-12">
        <div class="box"  id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}

                {{-- if this is applicant user and status is 17 (proceed for payment)--}}
                @if($viewMode == 'on' && in_array(Auth::user()->user_type,['5x505']) && in_array($appInfo->status_id, [15,32]))
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h5><strong>Government Fee Payment</strong></h5>
                        </div>
                        {!! Form::open(array('url' => 'office-permission-amendment/payment','method' => 'post','id' => 'OPAPayment','enctype'=>'multipart/form-data',
                        'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />

                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('sfp_contact_name') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_contact_name','Contact name',['class'=>'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('gfp_contact_name', \App\Libraries\CommonFunction::getUserFullName(), ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('gfp_contact_name','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 {{$errors->has('gfp_contact_email') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::email('gfp_contact_email', Auth::user()->user_email, ['class' => 'form-control input-md email required']) !!}
                                            {!! $errors->first('gfp_contact_email','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('gfp_contact_phone') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_contact_phone','Contact phone',['class'=>'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('gfp_contact_phone', Auth::user()->user_phone, ['class' => 'form-control input-md gfp_contact_phone phone_or_mobile required']) !!}
                                            {!! $errors->first('gfp_contact_phone','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 {{$errors->has('gfp_contact_address') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('gfp_contact_address', Auth::user()->road_no .  (!empty(Auth::user()->house_no) ? ', ' . Auth::user()->house_no : ''), ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('gfp_contact_address','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('gfp_pay_amount') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_pay_amount','Pay amount',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('gfp_pay_amount', $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                            {!! $errors->first('gfp_pay_amount','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6 {{$errors->has('gfp_vat_tax') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_vat_tax','VAT/ TAX',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('gfp_vat_tax', ($payment_config->vat_tax_percent / 100) * $payment_config->amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                            {!! $errors->first('gfp_vat_tax','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <?php
                                    $charge_amount = ($payment_config->trans_charge_percent / 100) * $payment_config->amount;
                                    if ($charge_amount < $payment_config->trans_charge_min_amount) {
                                        $charge_amount = $payment_config->trans_charge_min_amount;
                                    }
                                    if ($charge_amount > $payment_config->trans_charge_max_amount) {
                                        $charge_amount = $payment_config->trans_charge_max_amount;
                                    }
                                    ?>
                                    <div class="col-md-6 {{$errors->has('gfp_bank_charge') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_bank_charge','Bank charge',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('gfp_bank_charge', $charge_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                            {!! $errors->first('gfp_bank_charge','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('gfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            {!! Form::text('gfp_total_amount', number_format($payment_config->amount + (($payment_config->vat_tax_percent / 100) * $payment_config->amount) + $charge_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 {{$errors->has('gfp_status') ? 'has-error': ''}}">
                                        {!! Form::label('gfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
                                        <div class="col-md-7">
                                            <span class="label label-warning">Not Paid</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--Vat/ tax and service charge is an approximate amount--}}
                            @if($appInfo->gfp_payment_status != 1)
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                Vat/ tax and service charge is an approximate amount, it may vary based
                                                on the Sonali Bank system.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="panel-footer">
                            <div class="pull-right">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md pull-right"
                                        value="submit" name="actionBtn">Payment Submit
                                </button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    {!! Form::close() !!}<!-- /.form end -->
                    </div>
                @endif

                {{--Remarks file for conditional approved status--}}
                @if($viewMode == 'on' && in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [17,31]))
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h5><strong>Conditionally approve information</strong></h5>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => 'office-permission-amendment/conditionalApproveStore','method' => 'post','id' => 'OPAPayment','enctype'=>'multipart/form-data',
                                    'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                            <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}"/>

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">

                                    <div class="form-group {{$errors->has('conditional_approved_file') ? 'has-error': ''}}" style="overflow: hidden; margin-bottom: 15px;">
                                        {!! Form::label('conditional_approved_file ','Attachment', ['class'=>'col-md-3 required-star text-left']) !!}
                                        <div class="col-md-9">
                                            <input type="file" id="conditional_approved_file"
                                                   name="conditional_approved_file" onchange="checkPdfDocumentType(this.id, 2)" accept="application/pdf"
                                                   class="form-control input-md required" accept="application/pdf"/>
                                            {!! $errors->first('conditional_approved_file','<span class="help-block">:message</span>') !!}
                                            <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 2MB]</span>
                                            <br>
                                        </div>
                                    </div>

                                    <div class="form-group {{$errors->has('conditional_approved_remarks') ? 'has-error': ''}}" style="overflow: hidden; margin-bottom: 15px;">
                                        {!! Form::label('conditional_approved_remarks','Remarks',['class'=>'text-left col-md-3']) !!}
                                        <div class="col-md-9">
                                            {!! Form::textarea('conditional_approved_remarks', $appInfo->conditional_approved_remarks, ['data-rule-maxlength'=>'1000', 'placeholder'=>'Remarks', 'class' => 'form-control input-md',
                                                'size'=>'5x6','maxlength'=>'1000']) !!}
                                            {!! $errors->first('conditional_approved_remarks','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                    <button type="submit" id="submitForm" style="cursor: pointer;"
                                            class="btn btn-success btn-md pull-right"
                                            value="submit" name="actionBtn">Condition Fulfilled
                                    </button>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                @endif
                {{--End remarks file for conditional approved status--}}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><b>Application for Office Permission Amendment</b></h5>
                        </div>
                        <div class="pull-right">
                            @if (isset($appInfo) && $appInfo->status_id == -1)
                                <a href="{{ asset('assets/images/SampleForm/office_permission_amendment.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
                                    <i class="fas fa-file-pdf"></i>
                                    Download Sample Form
                                </a>
                            @endif
                            @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404', '5x505']))
                                <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info"
                                   title="Download Approval Copy" target="_blank" rel="noopener"> <i class="fa  fa-file-pdf-o"></i> Download Approval Copy</a>
                            @endif
                            &nbsp;
                            @if(!in_array($appInfo->status_id,[-1,5,6,22]))
                                <a href="/office-permission-amendment/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank"
                                   class="btn btn-danger btn-md pull-right">
                                    <i class="fa fa-download"></i> Application Download as PDF
                                </a>
                            @endif

                            @if(in_array($appInfo->status_id,[5,6,17,22]))
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        @if ($viewMode == 'on')
                            <section class="content-header">
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                    <li><strong> Date of Submission : </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }} </li>
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
                                </ol>
                            </section>
                        @endif

                        {!! Form::open(array('url' => 'office-permission-amendment/store','method' => 'post','id' => 'OfficePermissionAmendmentForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />

                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
                        <input type="hidden" name="ref_app_approve_date" value="{{ !empty($appInfo->ref_app_approve_date)  ? date('d-M-Y', strtotime($appInfo->ref_app_approve_date)) : '' }}">

                        @if(!empty($metingInformation) && $viewMode == 'on')
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Meeting Info</legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {!! Form::label('','Meeting No',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            {!! Form::label('','Meeting Date',['class'=>'text-left col-md-5']) !!}
                                            <div class="col-md-7">
                                                <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        @endif
                        <h3 class="stepHeader">Application Info.</h3>
                        <fieldset>
                            <legend class="d-none">Application Info.</legend>
                            @if($appInfo->status_id == 5 && (!empty($appInfo->resend_deadline)))
                                <div class="form-group blink_me">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert btn-danger" role="alert">
                                                You must re-submit the application before <strong>{{ date("d-M-Y", strtotime($appInfo->resend_deadline)) }}</strong>, otherwise, it will be automatically rejected.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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

                                                            @if($appInfo->is_approval_online == 'yes')
                                                            <label class="radio-inline">{!! Form::radio('is_approval_online','yes', ($appInfo->is_approval_online == 'yes' ? true :false), ['class'=>'cusReadonly helpTextRadio', 'id' =>'yes', 'onclick' => 'isApprovalOnline(this.value)']) !!}  Yes
                                                            </label>
                                                            @endif

                                                            @if($appInfo->is_approval_online == 'no')
                                                            <label class="radio-inline">{!! Form::radio('is_approval_online', 'no', ($appInfo->is_approval_online == 'no' ? true :false), ['class'=>'cusReadonly', 'id' =>'no', 'onclick' => 'isApprovalOnline(this.value)']) !!}  No
                                                            </label>
                                                            @endif
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <div class="row">
                                                    
                                                    @if($appInfo->is_approval_online == 'yes')
                                                    <div id="ref_app_tracking_no_div" class="col-md-12 hidden {{$errors->has('ref_app_tracking_no') ? 'has-error': ''}}">
                                                        {!! Form::label('ref_app_tracking_no','Please give your approved office permission reference No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <div class="input-group">
                                                                {!! Form::hidden('ref_app_tracking_no', $appInfo->ref_app_tracking_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm', 'readonly']) !!}
                                                                <span class="label label-success" style="font-size: 15px">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                                            @if ($appInfo->is_approval_online == 'yes')
                                                                    &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                                                @endif
                                                                <br/>

                                                                @if($viewMode != 'on')
                                                                    <small class="text-danger">N.B.: Once you save or submit the application, the Office Permission tracking no cannot be changed anymore.</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($appInfo->is_approval_online == 'no')
                                                    <div id="manually_approved_no_div" class="col-md-12 hidden {{$errors->has('manually_approved_op_no') ? 'has-error': ''}} ">
                                                        {!! Form::label('manually_approved_op_no','Please give your manually approved office permission reference No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('manually_approved_op_no', $appInfo->manually_approved_op_no, ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm']) !!}
                                                            {!! $errors->first('manually_approved_op_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div id="issue_date_of_first_div"
                                                         class="col-md-12 {{$errors->has('date_of_office_permission') ? 'has-error': ''}}">

                                                        {!! Form::label('date_of_office_permission','Effective date of the previous office permission',['class'=>'text-left col-md-5 required-star']) !!}
                                                        <div class="col-md-7">
                                                            <div class="datepicker input-group date">
                                                                {!! Form::text('date_of_office_permission', !empty($appInfo->date_of_office_permission) ? date('d-M-Y', strtotime($appInfo->date_of_office_permission)) : '', ['class' => 'form-control cusReadonly input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}

                                                                <span class="input-group-addon"><span
                                                                            class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('date_of_office_permission','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <!-- {{-- <div id="office_type_div" class="col-md-6 {{$errors->has('office_type') ? 'has-error': ''}}">
                                                        {!! Form::label('office_type','Office Type', ['class' => 'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('office_type', $officeType, $appInfo->office_type, ['placeholder' => 'Select One',
                                                            'class' => 'form-control cusReadonly input-md', 'id' => 'office_type', 'onchange' => "CategoryWiseDocLoad(this.value)"]) !!}
                                                            {!! $errors->first('office_type','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div> --}} -->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--start basic information section--}}
                            @include('OfficePermissionAmendment::basic-info')
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
                                                        {!! Form::text('effective_date', !empty($appInfo->effective_date) ? date('d-M-Y', strtotime($appInfo->effective_date)) : '', ['class' => 'form-control input-md date required', 'placeholder'=>'dd-mm-yyyy']) !!}

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
                                        <table aria-label="Detailed Previous Information of proposed branch" class="table table-bordered">
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
                                                        {!! Form::select('office_type', $officeType, $appInfo->office_type, ['class' => 'form-control cusReadonly input-md', 'id' => 'office_type', 'onchange' => 'CategoryWiseDocLoad(this.value)']) !!}
                                                        {!! $errors->first('office_type','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                    <td class="alert-success">
                                                        <input type="hidden" name="caption[n_office_type]" value="Office Type"/>
                                                        <div class="input-group">
                                                            {!! Form::select('n_office_type', $officeType, $appInfo->n_office_type, ['class' => 'form-control input-md', 'id' => 'n_office_type', 'onchange'=>'CategoryWiseDocLoad(this.value)', (empty($appInfo->n_office_type) ? 'disabled' : '')]) !!}
                                                            <span class="input-group-addon">
                                                                {!! Form::checkbox("toggleCheck[n_office_type]", 1, (empty($appInfo->n_office_type) ? false : true), ['class' => 'field', 'id' => 'n_office_type_check', 'onclick' => "toggleCheckBox('n_office_type_check', 'n_office_type');"]) !!}
                                                            </span>
                                                        </div>
                                                        {!! $errors->first('n_office_type','<span class="help-block">:message</span>') !!}
                                                    </td>
                                                </tr> 
                                            <tr>
                                                {{-- <td>1</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="local_company_name_label">
                                                               {!! Form::label('local_company_name','Name of the local company',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    @if($viewMode != 'on')
                                                        {!! Form::text('local_company_name', $appInfo->local_company_name, ['class'=>'form-control cusReadonly input-md', 'data-rule-maxlength'=>'255', 'id'=>"local_company_name"]) !!}
                                                    @else
                                                        <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">
                                                        {{ $appInfo->local_company_name }}
                                                        </span>
                                                    @endif
                                                    {!! $errors->first('local_company_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_local_company_name]" value="Name of the Local company"/>
                                                    @if($viewMode != 'on')
                                                        <div class="input-group">
                                                            {!! Form::text('n_local_company_name', $appInfo->n_local_company_name, ['class'=>'form-control input-md', 'id'=>"n_local_company_name", 'data-rule-maxlength'=>'255', (empty($appInfo->n_local_company_name) ? 'disabled' : '')]) !!}
                                                            <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_local_company_name]", 1, (empty($appInfo->n_local_company_name) ? false : true), ['class' => 'field', 'id' => 'n_local_company_name_check', 'onclick' => "toggleCheckBox('n_local_company_name_check', 'n_local_company_name');"]) !!}
                                                        </span>
                                                        </div>
                                                    @else
                                                        <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">
                                                        {{ $appInfo->n_local_company_name }}
                                                        </span>
                                                    @endif
                                                    {!! $errors->first('n_local_company_name','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>2</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_division_id_label">
                                                               {!! Form::label('ex_office_division_id','Division',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::select('ex_office_division_id', $divisions, $appInfo->ex_office_division_id, ['class' => 'form-control cusReadonly input-md', 'id' => 'ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('ex_office_division_id', this.value, 'ex_office_district_id', ". $appInfo->ex_office_district_id .")"]) !!}
                                                    {!! $errors->first('ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_division_id]" value="Division"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ex_office_division_id', $divisions, $appInfo->n_ex_office_division_id, ['class' => 'form-control input-md', 'id' => 'n_ex_office_division_id', 'onchange'=>"getDistrictByDivisionId('n_ex_office_division_id', this.value, 'n_ex_office_district_id', ". $appInfo->n_ex_office_district_id .")", (empty($appInfo->n_ex_office_division_id) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_division_id]", 1, (empty($appInfo->n_ex_office_division_id) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_division_id_check', 'onclick' => "toggleCheckBox('n_ex_office_division_id_check', 'n_ex_office_division_id');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_division_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>3</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_district_id_label">
                                                              {!! Form::label('ex_office_district_id','District',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::select('ex_office_district_id', $district_eng, $appInfo->ex_office_district_id, ['class' => 'form-control cusReadonly input-md','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('ex_office_district_id', this.value, 'ex_office_thana_id', ". $appInfo->ex_office_thana_id .")"]) !!}
                                                    {!! $errors->first('ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_district_id]" value="District"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ex_office_district_id', $district_eng, $appInfo->n_ex_office_district_id, ['class' => 'form-control input-md', 'id' => 'n_ex_office_district_id','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('n_ex_office_district_id', this.value, 'n_ex_office_thana_id',". $appInfo->n_ex_office_thana_id .")", (empty($appInfo->n_ex_office_district_id) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_district_id]", 1, (empty($appInfo->n_ex_office_district_id) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_district_id_check', 'onclick' => "toggleCheckBox('n_ex_office_district_id_check', 'n_ex_office_district_id');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_district_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>4</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_thana_id_label">
                                                             {!! Form::label('ex_office_thana_id','Police station', ['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::select('ex_office_thana_id', $thana_eng, $appInfo->ex_office_thana_id, ['class' => 'form-control cusReadonly input-md']) !!}
                                                    {!! $errors->first('ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_thana_id]" value="Police Station"/>
                                                    <div class="input-group">
                                                        {!! Form::select('n_ex_office_thana_id',[], $appInfo->n_ex_office_thana_id, ['class' => 'form-control input-md','id' => 'n_ex_office_thana_id','placeholder' => 'Select district first', (empty($appInfo->n_ex_office_thana_id) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_thana_id]", 1, (empty($appInfo->n_ex_office_thana_id) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_thana_id_check', 'onclick' => "toggleCheckBox('n_ex_office_thana_id_check', 'n_ex_office_thana_id');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_thana_id','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>5</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_post_office_label">
                                                             {!! Form::label('ex_office_post_office','Post office',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_post_office', $appInfo->ex_office_post_office, ['class' => 'form-control input-md cusReadonly']) !!}
                                                    {!! $errors->first('ex_office_post_office','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_post_office]" value="Post Office"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_post_office', $appInfo->n_ex_office_post_office, ['class' => 'form-control input-md', 'id' => 'n_ex_office_post_office', (empty($appInfo->n_ex_office_post_office) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_post_office]", 1, (empty($appInfo->n_ex_office_post_office) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_post_office_check', 'onclick' => "toggleCheckBox('n_ex_office_post_office_check', 'n_ex_office_post_office');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_post_office','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>6</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_post_code_label">
                                                             {!! Form::label('ex_office_post_code','Post code', ['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_post_code', $appInfo->ex_office_post_code, ['class' => 'form-control cusReadonly input-md post_code_bd']) !!}
                                                    {!! $errors->first('ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_post_code]" value="Post Code"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_post_code', $appInfo->n_ex_office_post_code, ['class' => 'form-control input-md post_code_bd', 'id' => 'n_ex_office_post_code', (empty($appInfo->n_ex_office_post_code) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_post_code]", 1, (empty($appInfo->n_ex_office_post_code) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_post_code_check', 'onclick' => "toggleCheckBox('n_ex_office_post_code_check', 'n_ex_office_post_code');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_post_code','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>7</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_address_label">
                                                             {!! Form::label('ex_office_address','House, Flat/ Apartment, Road ',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    @if($viewMode != 'on')
                                                        {!! Form::text('ex_office_address', $appInfo->ex_office_address, ['maxlength'=>'150','class' => 'form-control cusReadonly input-md']) !!}
                                                    @else
                                                        <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">
                                                        {{ $appInfo->ex_office_address }}
                                                        </span>
                                                    @endif
                                                    {!! $errors->first('ex_office_address','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_address]" value="House, Flat/ Apartment, Road"/>
                                                    @if($viewMode != 'on')
                                                        <div class="input-group">
                                                            {!! Form::text('n_ex_office_address', $appInfo->n_ex_office_address, ['maxlength'=>'150','class' => 'form-control input-md', 'id' => 'n_ex_office_address', (empty($appInfo->n_ex_office_address) ? 'disabled' : '')]) !!}
                                                            <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_address]", 1, (empty($appInfo->n_ex_office_address) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_address_check', 'onclick' => "toggleCheckBox('n_ex_office_address_check', 'n_ex_office_address');"]) !!}
                                                        </span>
                                                        </div>
                                                    @else
                                                        <span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">
                                                        {{ $appInfo->n_ex_office_address }}
                                                        </span>
                                                    @endif
                                                    {!! $errors->first('n_ex_office_address','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>8</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_telephone_no_label">
                                                             {!! Form::label('ex_office_telephone_no','Telephone no.') !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning mobile_flag_priority">
                                                    {!! Form::text('ex_office_telephone_no', $appInfo->ex_office_telephone_no, ['maxlength'=>'20','class' => 'form-control cusReadonly input-md phone_or_mobile']) !!}
                                                    {!! $errors->first('ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_telephone_no]" value="Telephone No."/>
                                                    <div class="input-group mobile_flag_priority">
                                                        {!! Form::text('n_ex_office_telephone_no', $appInfo->n_ex_office_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile', 'id' => 'n_ex_office_telephone_no',(empty($appInfo->n_ex_office_telephone_no) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_telephone_no]", 1, (empty($appInfo->n_ex_office_telephone_no) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_telephone_no_check', 'onclick' => "toggleCheckBox('n_ex_office_telephone_no_check', 'n_ex_office_telephone_no');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_telephone_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>9</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_mobile_no_label">
                                                             {!! Form::label('ex_office_mobile_no','Mobile no.',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_mobile_no',$appInfo->ex_office_mobile_no, ['class' => 'form-control cusReadonly input-md helpText15' ,'id' => 'ex_office_mobile_no']) !!}
                                                    {!! $errors->first('ex_office_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_mobile_no]" value="Mobile No."/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_mobile_no', $appInfo->n_ex_office_mobile_no, ['class' => 'form-control input-md phone_or_mobile' ,'id' => 'n_ex_office_mobile_no', (empty($appInfo->n_ex_office_mobile_no) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_mobile_no]", 1, (empty($appInfo->n_ex_office_mobile_no) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_mobile_no_check', 'onclick' => "toggleCheckBox('n_ex_office_mobile_no_check', 'n_ex_office_mobile_no');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_mobile_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>10</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_fax_no_label">
                                                           {!! Form::label('ex_office_fax_no','Fax no. ') !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_fax_no', $appInfo->ex_office_fax_no, ['class' => 'form-control cusReadonly input-md']) !!}
                                                    {!! $errors->first('ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_fax_no]" value="Fax No."/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_fax_no', $appInfo->n_ex_office_fax_no, ['class' => 'form-control input-md', 'id' => 'n_ex_office_fax_no', (empty($appInfo->n_ex_office_fax_no) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_fax_no]", 1, (empty($appInfo->n_ex_office_fax_no) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_fax_no_check', 'onclick' => "toggleCheckBox('n_ex_office_fax_no_check', 'n_ex_office_fax_no');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_fax_no','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>11</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="ex_office_email_label">
                                                           {!! Form::label('ex_office_email','Email ',['class'=>'required-star']) !!}
                                                            </span>
                                                    </div>
                                                </td>
                                                <td class="alert-warning">
                                                    {!! Form::text('ex_office_email', $appInfo->ex_office_email, ['class' => 'form-control cusReadonly email input-md']) !!}
                                                    {!! $errors->first('ex_office_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_ex_office_email]" value="Email"/>
                                                    <div class="input-group">
                                                        {!! Form::text('n_ex_office_email', $appInfo->n_ex_office_email, ['class' => 'form-control email input-md', 'id' => 'n_ex_office_email', (empty($appInfo->n_ex_office_email) ? 'disabled' : '')]) !!}
                                                        <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_ex_office_email]", 1, (empty($appInfo->n_ex_office_email) ? false : true), ['class' => 'field', 'id' => 'n_ex_office_email_check', 'onclick' => "toggleCheckBox('n_ex_office_email_check', 'n_ex_office_email');"]) !!}
                                                        </span>
                                                    </div>
                                                    {!! $errors->first('n_ex_office_email','<span class="help-block">:message</span>') !!}
                                                </td>
                                            </tr>
                                            <tr>
                                                {{-- <td>12</td> --}}
                                                <td>
                                                    <div style="position: relative;">
                                                            <span class="helpTextCom" id="activities_in_bd_label">
                                                    {!! Form::label('activities_in_bd','Activities in Bangladesh through the proposed branch/ liaison/ representative office (Max. 250 characters )', ['class'=>'required-star']) !!}
                                                            </span>
                                                    </div></td>
                                                <td class="alert-warning">
                                                    <div class="">
                                                        {!! Form::textarea('activities_in_bd', $appInfo->activities_in_bd, ['data-charcount-maxlength'=>'250', 'id' => 'activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control cusReadonly bigInputField input-md maxTextCountDown',
                                                                                                                'size'=>'10x3']) !!}
                                                        {!! $errors->first('activities_in_bd','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </td>
                                                <td class="alert-success">
                                                    <input type="hidden" name="caption[n_activities_in_bd]" value="Activities in Bangladesh"/>
                                                    <div class="">
                                                        <div class="input-group">
                                                            {!! Form::textarea('n_activities_in_bd', $appInfo->n_activities_in_bd, ['data-charcount-maxlength'=>'250', 'id' => 'n_activities_in_bd', 'placeholder'=>'Write here', 'class' => 'form-control bigInputField input-md maxTextCountDown',
                                                        'size'=>'10x3', (empty($appInfo->n_activities_in_bd) ? 'disabled' : '')]) !!}
                                                            <span class="input-group-addon">
                                                            {!! Form::checkbox("toggleCheck[n_activities_in_bd]", 1, (empty($appInfo->n_activities_in_bd) ? false : true), ['class' => 'field', 'id' => 'n_activities_in_bd_check', 'onclick' => "toggleCheckBox('n_activities_in_bd_check', 'n_activities_in_bd');"]) !!}
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

                        <h3 class="text-center stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv">
                            </div>
                            @if($viewMode != 'off')
                                @include('OfficePermissionAmendment::doc-tab')
                            @endif
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
                                                            {!! Form::text('auth_full_name', $appInfo->auth_full_name, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_designation','Designation',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_designation', $appInfo->auth_designation, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                            {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_mobile_no','Mobile No.',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'form-control input-sm phone_or_mobile required', 'readonly']) !!}
                                                            {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_email','Email address',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::email('auth_email', $appInfo->auth_email, ['class' => 'form-control input-sm email required', 'readonly']) !!}
                                                            {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                        {!! Form::label('auth_image','Picture',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-7">
                                                            <img class="img-thumbnail img-user"
                                                                 src="{{ (!empty($appInfo->auth_image) ? url('users/upload/'.$appInfo->auth_image) : url('users/upload/'.Auth::user()->user_pic)) }}"
                                                                 alt="User Photo">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="auth_image" value="{{ (!empty($appInfo->auth_image) ? $appInfo->auth_image : Auth::user()->user_pic) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <div class="form-group {{$errors->has('accept_terms') ? 'has-error' : ''}}">
                                        <div class="checkbox">
                                            <label>
                                                {!! Form::checkbox('accept_terms',1, ($appInfo->accept_terms == 1) ? true : false, array('id'=>'accept_terms', 'class'=>'required')) !!}
                                                I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given.
                                            </label>
                                        </div>
                                    </div>

                                    {{--<div class="form-group {{$errors->has('accept_terms') ? 'has-error' : ''}}">--}}
                                    {{--<input id="acceptTerms-2" {{ ($appInfo->accept_terms == 1) ?'checked':'' }} name="accept_terms" type="checkbox" class="required col-md-1 text-left" style="width:3%;">--}}
                                    {{--<label for="acceptTerms-2" class="col-md-11 text-left required-star">I agree with the Terms and Conditions.</label>--}}
                                    {{--<div class="clearfix"></div>--}}
                                    {{--{!! $errors->first('accept_terms','<span class="help-block">:message</span>') !!}--}}
                                    {{--</div>--}}

                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Payment & Submit</h3>
                        <fieldset>
                            <legend class="d-none">Payment & Submit</legend>
                            @if($viewMode != 'on')
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
                                                        {!! Form::text('sfp_contact_name', $appInfo->sfp_contact_name, ['class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('sfp_contact_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_contact_email') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_email','Contact email',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md required email']) !!}
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
                                                        {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control sfp_contact_phone phone_or_mobile input-md required']) !!}
                                                        {!! $errors->first('sfp_contact_phone','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_contact_address') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_contact_address','Contact address',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_contact_address', $appInfo->sfp_contact_address, ['class' => 'bigInputField form-control input-md required']) !!}
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
                                                <div class="col-md-6 {{$errors->has('sfp_vat_on_pay_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_vat_on_pay_amount','VAT on pay amount',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_vat_on_pay_amount', $appInfo->sfp_vat_on_pay_amount, ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('sfp_vat_on_pay_amount','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::label('sfp_total_amount','Total amount',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('sfp_total_amount', number_format($appInfo->sfp_total_amount, 2), ['class' => 'form-control input-md', 'readonly']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('sfp_status') ? 'has-error': ''}}">
                                                    {!! Form::label('sfp_status','Payment status',['class'=>'col-md-5 text-left']) !!}
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
                                                            <strong>Vat/ Tax</strong> and <strong>Transaction charge</strong> is an approximate amount, those may vary based on the Sonali Bank system and those will be visible here after payment submission.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </fieldset>

                        @if(ACL::getAccsessRight('OfficePermissionAmendment','-E-') && $viewMode != "on" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                            
                            @if(!in_array($appInfo->status_id,[5,22]))
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
                            @endif

                            @if(in_array($appInfo->status_id,[5,22])) {{--22 = Observation by MC --}}
                                <div class="pull-left">
                                    <span style="display: block; height: 34px">&nbsp;</span>
                                </div>
                                <div class="pull-left">
                                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-info btn-md"
                                            value="resubmit" name="actionBtn">Re-submit
                                    </button>
                                </div>
                            @endif
                        @else
                            <style>
                                .wizard > .actions{
                                    top: -15px !important;
                                }
                            </style>
                        @endif

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
        } else if(value == 'no') {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#ref_app_tracking_no").removeClass('required');
            $("#manually_approved_no_div").removeClass('hidden');
            $("#manually_approved_op_no").addClass('required');
        } else {
            $("#ref_app_tracking_no_div").addClass('hidden');
            $("#manually_approved_no_div").addClass('hidden');
        }
    }

    var sessionLastOPN = '{{ $appInfo->is_approval_online }}';
    if(sessionLastOPN == 'yes') {
        isApprovalOnline(sessionLastOPN);
        // $("#ref_app_tracking_no").prop('readonly', true);

//        //$(".cusReadonly").prop('readonly', true);
        $(".cusReadonly").attr('readonly', true);
//        //$(".cusReadonly option:not(:selected)").prop('disabled', true);
        $(".cusReadonly option:not(:selected)").remove();
        $(".cusReadonly:radio:not(:checked)").attr('disabled', true);
    } else {
        isApprovalOnline(sessionLastOPN);
        $("#ex_office_division_id").trigger('change');
        $("#ex_office_district_id").trigger('change');
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
        $("#office_type").trigger('change');

        @if(isset($appInfo->n_ex_office_division_id))
        $("#n_ex_office_division_id").trigger('change');
        @endif
        @if(isset($appInfo->n_ex_office_district_id))
        $("#n_ex_office_district_id").trigger('change');
        @endif


        @if ($viewMode != 'on')
        var form = $("#OfficePermissionAmendmentForm").show();
        form.find('#save_as_draft').css('display','none');
        form.find('#submitForm').css('display', 'none');
        form.find('.actions').css('top','-15px !important');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                if(newIndex == 1){}

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
                console.log(currentIndex);
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
                console.log(form.validate());
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
        @endif

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

    @if ($viewMode == 'on')
    $('#OfficePermissionAmendmentForm .stepHeader').hide();
    $('#OfficePermissionAmendmentForm :input').attr('disabled', true);
    $('#OfficePermissionAmendmentForm').find('.MoreInfo').attr('disabled', false);
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        //console.log($(this)[0]['localName']);
        if($(this)[0]['localName'] == 'select'){
            //var text = $(this).find('option:selected').text();
            //var val = jQuery(this).val();
            //$(this).find('option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");

            // This style will not work in mozila firefox, it's bug in firefox, maybe they will update it in next version
            $(this).attr('style', '-webkit-appearance: button; -moz-appearance: button; -webkit-user-select: none; -moz-user-select: none; text-overflow: ellipsis; white-space: pre-wrap; height: auto;');
        }
        else {
            $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">'+this.value+'</span>');
        }
    });
    $('#OfficePermissionAmendmentForm :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on
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

        $(".gfp_contact_phone").intlTelInput({
            hiddenInput: ["gfp_contact_phone"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });

        $(".sfp_contact_phone").intlTelInput({
            hiddenInput: ["sfp_contact_phone"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true
        });
        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});
    });

    //Office type will not be changed when application is resubmitted
    var status_id = '{{ $appInfo->status_id }}';
    if (status_id == 5){ // 5 = shortfall
        $('#office_type').attr("readonly", "readonly");
        $('#office_type option:not(:selected)').remove();
    }
</script>
{{--initail -input plugin script end--}}

@if($viewMode == 'on')
    <script>
        $(document).ready(function () {
            $("#OPAPayment").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endif
