<?php
$accessMode = ACL::getAccsessRight('ProjectOfficeNew');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
{{--Datepicker css--}}
<link rel="stylesheet" href="{{ asset("vendor/datepicker/datepicker.min.css") }}">

<style>
    /* Desktop styling */
    @media (min-width: 768px) {
        .label-activity {
            width: 20.66666667% !important;
        }
        
        .content-activity {
            width: 79.33333333% !important;
        }
    }

    /* Mobile styling */
    @media (max-width: 991px) {
        .label-activity {
            width: 100% !important;
            text-align: left !important;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .content-activity {
            width: 100% !important;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        textarea.form-control {
            min-height: 100px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
    }
    input[type=file] {
    /* display: block; */
    width: 100%;
    }

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

</style>


<style>
    .office-section {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .office-section:not(:first-child) {
        border-top: 2px dashed #ccc;
        margin-top: 20px;
    }
    .section-header {
        margin-bottom: 15px;
    }
    .required-star:after {
        content: " *";
        color: red;
    }

    /* .iti {
        width: 100% !important;
    } */


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

                {{-- if this is applicant user and status is 15, 32 (proceed for payment)--}}
                

                {{--Remarks file for conditional approved status--}}
                
                {{--End remarks file for conditional approved status--}}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><b>Application for Project Office New</b></h5>
                        </div>
                        <div class="pull-right">
                            @if (isset($appInfo) && $appInfo->status_id == -1)
                                <a href="{{ asset('assets/images/SampleForm/project_office_new.pdf') }}" target="_blank" rel="noopener" class="btn btn-warning">
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
                                <a href="/project-office-new/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank"
                                   class="btn btn-danger btn-md pull-right">
                                    <i class="fa fa-download"></i> Application Download as PDF
                                </a>
                            @endif

                            @if(in_array($appInfo->status_id,[5,6,17,22,31]))
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        {{-- viewMode breadcrumb --}}
                       
                        {!! Form::open(array('url' => 'project-office-new/store','method' => 'post','id' => 'ProjectOfficeNewForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />

                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />

                        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

                        {{-- Conditional Approved Remarks --}}
                       
                        <h3 class="stepHeader">Application Info.</h3>

                        {{-- Approved Permission Period --}}
                       
                        {{-- Meeting Info --}}
                       
                        <fieldset>
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

                            {{--start basic information section--}}
                            @include('ProjectOfficeNew::basic-info')

                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>1. Name of the Project</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">

                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('project_name') ? 'has-error': ''}}">
                                                {!! Form::label('project_name','1. Name of the Project', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::text('project_name', !empty($appInfo->project_name)?$appInfo->project_name:'', ['placeholder' => 'Name of the Project', 'class' => 'form-control input-md required', 'id' => 'project_name']) !!}
                                                    {!! $errors->first('project_name','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('project_major_activities') ? 'has-error': ''}}" style="margin-top: 10px;">
                                                {!! Form::label('project_major_activities','Project Major Activities In Brief', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('project_major_activities', !empty($appInfo->project_major_activities)?$appInfo->project_major_activities:'', [
                                                                    'class' => 'form-control input-md bigInputField maxTextCountDown required',
                                                                    'id' => 'project_major_activities',
                                                                    'rows' => '3',
                                                                    'data-rule-maxlength'=>'250',
                                                                    'placeholder' => 'Maximum 250 characters',
                                                                    "data-charcount-maxlength" => "250"
                                                                ]) !!}
                                                    {!! $errors->first('project_major_activities','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 {{$errors->has('project_major_details') ? 'has-error': ''}}">
                                                {!! Form::label('project_major_details','Project Major In Details', ['class' => 'col-md-5 text-left required-star']) !!}
                                                <div class="col-md-7">
                                                    {!! Form::textarea('project_major_details', !empty($appInfo->project_major_details)?$appInfo->project_major_details:'', [
                                                                    'class' => 'form-control input-md bigInputField maxTextCountDown required',
                                                                    'id' => 'project_major_details',
                                                                    'rows' => '3',
                                                                    'data-rule-maxlength'=>'3000',
                                                                    'placeholder' => 'Maximum 3000 characters',
                                                                    "data-charcount-maxlength" => "3000"
                                                                ]) !!}
                                                    {!! $errors->first('project_major_details','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>2. Information of the company(s) composing JV/ Consortium/ association office</strong>
                                    <button type="button" class="btn btn-success btn-sm pull-right" id="add-company-btn">
                                        <i class="fa fa-plus"></i> Add Another Company
                                    </button>
                                </div>
                                <div class="panel-body">
                                    <div id="companies-container">
                                        @if(count($ponCompanyOfficeList) > 0)
                                        <?php $inc = 0; ?>
                                        @foreach($ponCompanyOfficeList as $key => $row)
                                        
                                        <?php
                                            $company_office_approved = !empty($row->company_office_approved)?$row->company_office_approved:'';
                                            $is_approval_online = !empty($row->is_approval_online)?$row->is_approval_online:'';
                                            $isEditableCompany =  $company_office_approved == 'yes' &&  $is_approval_online == 'yes' ? false : true;
                                        ?>

                                        <div class="company-section" data-company-index="{{$key}}">
                                            <br>
                                            <div class="panel-heading" style="position: relative;">
                                                <button type="button" class="btn btn-danger btn-xs remove-company-btn" style="{{ $inc == 1 ? 'display:block;':'display:none;'}} position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                                    <i class="fa fa-times"></i> Remove
                                                </button>
                                            </div>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">
                                                    Company Information
                                                </legend>

                                                <div class="panel-body">
                                                    <!-- BIDA Approval Question -->
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12 {{$errors->has('company_office_approved.'.$key) ? 'has-error': ''}}">
                                                                        <!-- app_id -->
                                                                        {!! Form::hidden("pon_company_office_record_id[$key]", $row->id) !!}
                                                                        {!! Form::label("company_office_approved[$key]", 'The company Office Permission been approved by BIDA?', ['class' => 'col-md-5 text-left required-star']) !!}
                                                                        <div class="col-md-7">
                                                                            <label class="radio-inline">{!! Form::radio("company_office_approved[$key]", 'yes', (!empty($row->company_office_approved) && $row->company_office_approved == 'yes'), ['class' => 'company-office-approved required', 'id' => 'yes' ]) !!} Yes</label>
                                                                            <label class="radio-inline">{!! Form::radio("company_office_approved[$key]", 'no', (!empty($row->company_office_approved) && $row->company_office_approved == 'no'), ['class' => 'company-office-approved required', 'id' => 'no', 'onclick' => 'handleCompanyOfficeApproved(this)']) !!} No</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- OPN/OPE Approval Question -->
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="form-group online-oss-question" style="{{ !empty($row->company_office_approved) && $row->company_office_approved == 'yes' ? 'display: block' : 'display: none' }}">
                                                                <div class="row">
                                                                    <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                                        {!! Form::label("is_approval_online[$key]",'Did you receive your Office Permission New / Office Permission Extension online OSS?',['class'=>'col-md-5 text-left']) !!}
                                                                        <div class="col-md-7">
                                                                            <label class="radio-inline">{!! Form::radio("is_approval_online[$key]",'yes', (!empty($row->is_approval_online) && $row->is_approval_online == 'yes'), ['class'=>'cusReadonly helpTextRadio', $isEditableCompany ? '' : 'disabled']) !!}  Yes</label>
                                                                            <label class="radio-inline">{!! Form::radio("is_approval_online[$key]", 'no', (!empty($row->is_approval_online) && $row->is_approval_online == 'no'), ['class'=>'cusReadonly', $isEditableCompany ? '' : 'disabled']) !!}  No</label>
                                                                            {{-- Hidden input to store the selected value when disabled --}}
                                                                            @if (!$isEditableCompany)
                                                                            {!! Form::hidden("is_approval_online[$key]", !empty($row->is_approval_online) ? $row->is_approval_online : '', ['id' => 'is_approval_online_hidden_'.$key]) !!}
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                
                                                           
                                                            <div class="form-group">
                                                                <div class="row">
                                                                     {{-- if yes --}}
                                                                    <div id="ref_app_tracking_no_div" class="col-md-12 {{$errors->has('ref_app_tracking_no.'.$key) ? 'has-error': ''}} {{ !empty($row->is_approval_online) && $row->is_approval_online == 'yes' ? '' : 'hidden' }}">
                                                                        {!! Form::label("ref_app_tracking_no[$key]",'Please give your approved Office Permission New / Office Permission Extension Tracking No.',['class'=>'col-md-5 text-left']) !!}
                                                                        <div class="col-md-7">
                                                                            <div class="input-group">
                                                                                {!! Form::text("ref_app_tracking_no[$key]", !empty($row->ref_app_tracking_no)?$row->ref_app_tracking_no:'', ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm cusReadonly '. ($company_office_approved == 'yes' && $is_approval_online == 'yes' ? 'required' : ''), 'placeholder'=>'OPN-01Jan2022-00001/OPE-01Jan2022-00001', 'readonly' => $isEditableCompany ? null : 'readonly']) !!}
                                                                                {!! $errors->first('ref_app_tracking_no.'.$key,'<span class="help-block">:message</span>') !!}
                                                                                <span class="input-group-btn">
                                                                                    @if(Session::get('opaInfo'))
                                                                                        <button type="submit" class="btn btn-danger btn-sm" value="clean_load_data" name="actionBtn">Clear Loaded Data</button>
                                                                                        <a href="{{ Session::get('opaInfo.certificate_link') }}" target="_blank" rel="noopener" class="btn btn-success btn-sm">View Certificate</a>
                                                                                    @else
                                                                                        <button type="button" class="btn btn-success btn-sm" value="searchOPinfo" name="searchOPinfo" id="searchOPinfo" onclick="loadOfficePermissionData(this)">Load OPN/OPE Data</button>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                            <small class="text-danger">
                                                                                N.B.: Once you save or submit the application, the Office Permission tracking no cannot be changed anymore.
                                                                            </small>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('ref_app_approve_date.'.$key) ? 'has-error': ''}}">
                                                                                {!! Form::label("ref_app_approve_date[$key]",'Approved Date', ['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                    {!! Form::text("ref_app_approve_date[$key]", !empty($row->ref_app_approve_date) ? date('d-M-Y', strtotime($row->ref_app_approve_date)) : null,
                                                                                        [
                                                                                            'class' => "form-control cusReadonly input-md date " . ($company_office_approved == 'yes' && $is_approval_online == 'yes' ? 'required' : ''),
                                                                                            'id' => 'ref_app_approve_date_'.$key,
                                                                                            'readonly'
                                                                                        ]
                                                                                    ) !!}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    {{-- if no --}}
                                                                    <div id="manually_approved_no_div" class="col-md-12 {{$errors->has('manually_approved_op_no.'.$key) ? 'has-error': ''}} {{ !empty($row->is_approval_online) && $row->is_approval_online == 'no' ? '' : 'hidden' }}">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                {!! Form::label("anually_approved_op_no[$key]",'Please give your manually approved Office Permission New / Office Permission Extension Memo Number.',['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                    {!! Form::text("manually_approved_op_no[$key]", !empty($row->manually_approved_op_no)?$row->manually_approved_op_no:'', ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm '. ($company_office_approved == 'yes' && $is_approval_online == 'no' ? 'required' : '')]) !!}
                                                                                    {!! $errors->first('manually_approved_op_no.'.$key,'<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                {!! Form::label("approval_copy[$key]", 'Approval Copy', ['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                    <input type="file" name="approval_copy[{{$key}}]"
                                                                                    class="form-control input-md {{ ($company_office_approved == 'yes' && $is_approval_online == 'no' && empty($row->approval_copy) ? 'required' : '') }}"/>
                                                                                    {{-- {!! Form::file('approval_copy[0]', ['class' => 'form-control input-md required']) !!} --}}
                                                                                    @if(!empty($row->approval_copy))
                                                                                        <a href="{{ asset($row->approval_copy) }}" class="btn btn-primary btn-xs" target="_blank">
                                                                                            <i class="fa fa-download"></i> Download
                                                                                        </a>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                {!! Form::label("manually_approved_br_date[$key]",'Approved Date', ['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group date">
                                                                                        {!! Form::text("manually_approved_br_date[$key]", !empty($row->manually_approved_br_date)?  date('d-m-Y', strtotime($row->manually_approved_br_date)): null, ['class'=>'form-control input-md datepicker '. ($company_office_approved == 'yes' && $is_approval_online == 'no' ? 'required' : ''), 'id' => 'manually_approved_br_date_'.$key, 'placeholder'=>'Pick from datepicker']) !!}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- Company name -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('c_company_name.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_company_name[$key]",'Name of company:',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_company_name[$key]", !empty($row->c_company_name)?$row->c_company_name:'', ['class'=>'form-control input-md required', 'data-rule-maxlength'=>'255', 'id'=>"c_company_name_0", 'readonly' => $isEditableCompany ? null : 'readonly']) !!}
                                                                {!! $errors->first('c_company_name.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!-- Country & House/ Plot/ Holding No -->
                                                        <div class="col-md-6 {{$errors->has('c_origin_country_id.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_origin_country_id[$key]",'Country of origin',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("c_origin_country_id[$key]", $countries, !empty($row->c_origin_country_id)?$row->c_origin_country_id:'', ['placeholder' => 'Select One',
                                                                'class' => 'form-control input-md required', 'id'=>"c_origin_country_id_".$key, $isEditableCompany ? '' : 'disabled']) !!}
                                                                {{-- Hidden input to store the selected value when disabled --}}
                                                                @if (!$isEditableCompany)
                                                                    {!! Form::hidden("c_origin_country_id[$key]", !empty($row->c_origin_country_id) ? $row->c_origin_country_id : '', ['id' => 'c_origin_country_id_hidden_'.$key]) !!}
                                                                @endif
                                                                {!! $errors->first('c_origin_country_id.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Country & House/ Plot/ Holding No -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('c_org_type.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_org_type[$key]",'Type of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("c_org_type[$key]", $organizationTypes, !empty($row->c_org_type)?$row->c_org_type:'',
                                                                ['class' => 'form-control input-md required','placeholder' => 'Select One', 'id' => 'c_org_type_'.$key, $isEditableCompany ? '' : 'disabled' ]) !!}
                                                                {{-- Hidden input to store the selected value when disabled --}}
                                                                @if (!$isEditableCompany)
                                                                    {!! Form::hidden("c_org_type[$key]", !empty($row->c_org_type) ? $row->c_org_type : '', ['id' => 'c_org_type_hidden_'.$key]) !!}
                                                                @endif
                                                                {!! $errors->first("c_org_type.".$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 has-feedback {{ $errors->has('c_flat_apart_floor.'.$key) ? 'has-error' : ''}}">
                                                            {!! Form::label("c_flat_apart_floor[$key]",'Flat/ Apartment/ Floor no.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_flat_apart_floor[$key]", !empty($row->c_flat_apart_floor)?$row->c_flat_apart_floor:'', ['class'=>'form-control input-md required', 'data-rule-maxlength'=>'40', 'id'=>"c_flat_apart_floor_".$key, 'readonly' => $isEditableCompany ? null : 'readonly' ]) !!}
                                                                {!! $errors->first('c_flat_apart_floor.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Flat & Street -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('c_house_plot_holding.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_house_plot_holding[$key]",'House/ Plot/ Holding no.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_house_plot_holding[$key]", !empty($row->c_house_plot_holding)?$row->c_house_plot_holding:'', ['class'=>'form-control input-md required', 'data-rule-maxlength'=>'40', 'id'=>"c_house_plot_holding_".$key, 'readonly' => $isEditableCompany ? null : 'readonly' ]) !!}
                                                                {!! $errors->first('c_house_plot_holding.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_post_zip_code.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_post_zip_code[$key]",'Post/ Zip code',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_post_zip_code[$key]", !empty($row->c_post_zip_code)?$row->c_post_zip_code:'', ['data-rule-maxlength'=>'80',
                                                                'class' => 'form-control input-md required', 'id' => 'c_post_zip_code_'.$key, 'readonly' => $isEditableCompany ? null : 'readonly' ]) !!}
                                                                {!! $errors->first('c_post_zip_code.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Telephone and fax -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 has-feedback {{ $errors->has('c_street.'.$key) ? 'has-error' : ''}}">
                                                            {!! Form::label("c_street[$key]",'Street name/ Street no.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_street[$key]", !empty($row->c_street)?$row->c_street:'', ['class'=>'form-control input-md required','data-rule-maxlength'=>'40', 'id' => 'c_street_0', 'readonly' => $isEditableCompany ? null : 'readonly' ]) !!}
                                                                {!! $errors->first('c_street.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_email.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_email[$key]",'Email',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_email[$key]", !empty($row->c_email)?$row->c_email:'', ['class' => 'form-control input-md email required', 'id' => 'c_email_'.$key, 'readonly' => $isEditableCompany ? null : 'readonly' ]) !!}
                                                                {!! $errors->first("c_email.".$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- State and city -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 c_city_div {{$errors->has('c_city.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_city[$key]",'City',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_city[$key]", !empty($row->c_city)?$row->c_city:'',['class' => 'form-control input-md', 'id' => 'c_city_'.$key, 'readonly' => $isEditableCompany ? null : 'readonly' ]) !!}
                                                                {!! $errors->first("c_city.".$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 c_district_id_div hidden {{$errors->has('c_district_id.'.$key) ? 'has-error': ''}}" id="c_district_id_div_".$key>
                                                            {!! Form::label("c_district_id[$key]",'District/ City',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("c_district_id[$key]", $district_eng, !empty($row->c_district_id)?$row->c_district_id:'',
                                                                ['class' => 'form-control input-md c_district_id','placeholder' => 'Select One', 'id' => 'c_district_id_'.$key,
                                                                "onchange" => "getThanaByDistrictId('c_district_id_$key', this.value, 'c_thana_id_$key', ".$row->c_thana_id.")"
                                                                 ]) !!}
                                                                {!! $errors->first("c_district_id.".$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_mobile_no.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_mobile_no[$key]",'Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_mobile_no[$key]", !empty($row->c_mobile_no)?$row->c_mobile_no:'', ['class' => 'mobile-plugin phone_or_mobile form-control input-md helpText15 required', 'id' => 'c_mobile_no_'.$key]) !!}
                                                                {!! $errors->first("c_mobile_no.".$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Email and Post -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 c_state_province_div {{$errors->has('c_state_province.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_state_province[$key]",'State/ Province',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_state_province[$key]", !empty($row->c_state_province)?$row->c_state_province:'',['class' => 'form-control input-md required', 'id' => 'c_state_province_'.$key, 'readonly' => $isEditableCompany ? null : 'readonly' ]) !!}
                                                                {!! $errors->first('c_state_province.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 c_thana_id_div hidden {{$errors->has('c_thana_id.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_thana_id[$key]",'Police Station/ Town',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("c_thana_id[$key]", [''], '',
                                                                ['class' => 'form-control input-md','placeholder' => 'Select district first', 'id' => 'c_thana_id_'.$key ]) !!}
                                                                {!! $errors->first("c_thana_id.".$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_shareholder_percentage.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("c_shareholder_percentage[$key]",'Shareholder percentage',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("c_shareholder_percentage[$key]", isset($row->c_shareholder_percentage)?$row->c_shareholder_percentage:'', ['class' => 'form-control number input-md required', 'id' => 'c_shareholder_percentage_'.$key]) !!}
                                                                {!! $errors->first("c_shareholder_percentage.".$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Major activities in brief -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('c_major_activity_brief.'.$key) ? 'has-error' : ''}}">
                                                            {!! Form::label("c_major_activity_brief[$key]",'Major activities in brief',['class'=>'col-md-2 text-left required-star label-activity']) !!}
                                                            <div class="col-md-10 content-activity">
                                                                {!! Form::textarea("c_major_activity_brief[$key]", !empty($row->c_major_activity_brief)?$row->c_major_activity_brief:'', [
                                                                    'class' => 'form-control input-md bigInputField maxTextCountDown required',
                                                                    'id' => 'c_major_activity_brief_'.$key,
                                                                    'rows' => '3',
                                                                    'data-rule-maxlength'=>'250',
                                                                    'placeholder' => 'Maximum 250 characters',
                                                                    "data-charcount-maxlength" => "250",
                                                                    "readonly" => $isEditableCompany ? null : "readonly"
                                                                ]) !!}
                                                                {!! $errors->first('c_major_activity_brief.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </fieldset>
                                        </div>
                                        <!-- /.company-section (If data exist)-->
                                        <?php $inc++; ?>
                                        @endforeach
                                        @else
                                        <div class="company-section" data-company-index="0">
                                            <br>
                                            <div class="panel-heading" style="position: relative;">
                                                <button type="button" class="btn btn-danger btn-xs remove-company-btn" style="display:none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                                    <i class="fa fa-times"></i> Remove
                                                </button>
                                            </div>
                                            <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">
                                                    Company Information
                                                </legend>

                                                <div class="panel-body">
                                                    <!-- BIDA Approval Question -->
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-12 {{$errors->has('company_office_approved.0') ? 'has-error': ''}}">
                                                                        {!! Form::label('company_office_approved[0]', 'The company Office Permission been approved by BIDA?', ['class' => 'col-md-5 text-left required-star']) !!}
                                                                        <div class="col-md-7">
                                                                            <label class="radio-inline">{!! Form::radio('company_office_approved[0]', 'yes', false, ['class' => 'company-office-approved required', 'id' => 'yes' ]) !!} Yes</label>
                                                                            <label class="radio-inline">{!! Form::radio('company_office_approved[0]', 'no', false, ['class' => 'company-office-approved required', 'id' => 'no', 'onclick' => 'handleCompanyOfficeApproved(this)']) !!} No</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- OPN/OPE Approval Question -->
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="form-group online-oss-question" style="display: none">
                                                                <div class="row">
                                                                    <div class="col-md-12 {{$errors->has('is_approval_online') ? 'has-error': ''}}">
                                                                        {!! Form::label('is_approval_online[0]','Did you receive your Office Permission New / Office Permission Extension online OSS?',['class'=>'col-md-5 text-left']) !!}
                                                                        <div class="col-md-7">
                                                                            <label class="radio-inline">{!! Form::radio('is_approval_online[0]','yes', (Session::get('opaInfo.is_approval_online') == 'yes' ? true :false), ['class'=>'cusReadonly helpTextRadio']) !!}  Yes</label>
                                                                            <label class="radio-inline">{!! Form::radio('is_approval_online[0]', 'no', (Session::get('opaInfo.is_approval_online') == 'no' ? true :false), ['class'=>'cusReadonly']) !!}  No</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                
                                                            {{-- if yes --}}
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div id="ref_app_tracking_no_div" class="col-md-12 hidden {{$errors->has('ref_app_tracking_no.0') ? 'has-error': ''}}">
                                                                        {!! Form::label('ref_app_tracking_no[0]','Please give your approved Office Permission New / Office Permission Extension Tracking No.',['class'=>'col-md-5 text-left']) !!}
                                                                        <div class="col-md-7">
                                                                            <div class="input-group">
                                                                                {!! Form::text('ref_app_tracking_no[0]', Session::get('opaInfo.ref_app_tracking_no'), ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm cusReadonly', 'placeholder'=>'OPN-01Jan2022-00001/OPE-01Jan2022-00001']) !!}
                                                                                {!! $errors->first('ref_app_tracking_no.0','<span class="help-block">:message</span>') !!}
                                                                                <span class="input-group-btn">
                                                                                    @if(Session::get('opaInfo'))
                                                                                        <button type="submit" class="btn btn-danger btn-sm" value="clean_load_data" name="actionBtn">Clear Loaded Data</button>
                                                                                        <a href="{{ Session::get('opaInfo.certificate_link') }}" target="_blank" rel="noopener" class="btn btn-success btn-sm">View Certificate</a>
                                                                                    @else
                                                                                        <button type="button" class="btn btn-success btn-sm" value="searchOPinfo" name="searchOPinfo" id="searchOPinfo" onclick="loadOfficePermissionData(this)">Load OPN/OPE Data</button>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                            <small class="text-danger">
                                                                                N.B.: Once you save or submit the application, the Office Permission tracking no cannot be changed anymore.
                                                                            </small>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('ref_app_approve_date.0') ? 'has-error': ''}}">
                                                                                {!! Form::label('ref_app_approve_date[0]','Approved Date', ['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                        {!! Form::text('ref_app_approve_date[0]', !empty(Session::get('opaInfo.approved_duration_start_date')) ? date('d-M-Y', strtotime(Session::get('opaInfo.approved_duration_start_date'))) : '', ['class'=>'form-control cusReadonly input-md date required ', 'id' => 'ref_app_approve_date_0', 'readonly']) !!}
                            
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    
                                                                    <div id="manually_approved_no_div" class="col-md-12 hidden {{$errors->has('manually_approved_op_no.0') ? 'has-error': ''}} ">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                {!! Form::label('manually_approved_op_no[0]','Please give your manually approved Office Permission New / Office Permission Extension Memo Number.',['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                    {!! Form::text('manually_approved_op_no[0]', '', ['data-rule-maxlength'=>'100', 'class' => 'form-control input-sm']) !!}
                                                                                    {!! $errors->first('manually_approved_op_no.0','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                {!! Form::label('approval_copy[0]', 'Approval Copy', ['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                    <input type="file" name="approval_copy[0]" class="form-control input-md required">
                                                                                    {{-- {!! Form::file('approval_copy[0]', ['class' => 'form-control input-md required']) !!} --}}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                {!! Form::label('manually_approved_br_date[0]','Approved Date', ['class'=>'col-md-5 text-left required-star']) !!}
                                                                                <div class="col-md-7">
                                                                                    <div class="input-group date">
                                                                                        {!! Form::text('manually_approved_br_date[0]', '', ['class'=>'form-control input-md datepicker', 'id' => 'manually_approved_br_date', 'placeholder'=>'Pick from datepicker']) !!}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <!-- Company name -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('c_company_name.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_company_name[0]','Name of company:',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_company_name[0]','', ['class'=>'form-control input-md required', 'data-rule-maxlength'=>'255', 'id'=>"c_company_name_0" ]) !!}
                                                                {!! $errors->first('c_company_name.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <!-- Country & House/ Plot/ Holding No -->
                                                        <div class="col-md-6 {{$errors->has('c_origin_country_id.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_origin_country_id[0]','Country of origin',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('c_origin_country_id[0]', $countries,'', ['placeholder' => 'Select One',
                                                                'class' => 'form-control input-md required', 'id'=>"c_origin_country_id_0"]) !!}
                                                                {!! $errors->first('c_origin_country_id.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Country & House/ Plot/ Holding No -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('c_org_type.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_org_type[0]','Type of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('c_org_type[0]', $organizationTypes, '',
                                                                ['class' => 'form-control input-md required','placeholder' => 'Select One', 'id' => 'c_org_type_0']) !!}
                                                                {!! $errors->first('c_org_type.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 has-feedback {{ $errors->has('c_flat_apart_floor.0') ? 'has-error' : ''}}">
                                                            {!! Form::label('c_flat_apart_floor[0]','Flat/ Apartment/ Floor no.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_flat_apart_floor[0]', '', ['class'=>'form-control input-md required', 'data-rule-maxlength'=>'40', 'id'=>"c_flat_apart_floor_0"]) !!}
                                                                {!! $errors->first('c_flat_apart_floor.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Flat & Street -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('c_house_plot_holding.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_house_plot_holding[0]','House/ Plot/ Holding no.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_house_plot_holding[0]', '', ['class'=>'form-control input-md required', 'data-rule-maxlength'=>'40', 'id'=>"c_house_plot_holding_0"]) !!}
                                                                {!! $errors->first('c_house_plot_holding.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_post_zip_code.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_post_zip_code[0]','Post/ Zip code',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_post_zip_code[0]', '', ['data-rule-maxlength'=>'80',
                                                                'class' => 'form-control input-md required', 'id' => 'c_post_zip_code_0']) !!}
                                                                {!! $errors->first('c_post_zip_code.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Telephone and fax -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 has-feedback {{ $errors->has('c_street.0') ? 'has-error' : ''}}">
                                                            {!! Form::label('c_street[0]','Street name/ Street no.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_street[0]', '', ['class'=>'form-control input-md required','data-rule-maxlength'=>'40', 'id' => 'c_street_0']) !!}
                                                                {!! $errors->first('c_street.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_email.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_email[0]','Email',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_email[0]', '', ['class' => 'form-control input-md email required', 'id' => 'c_email_0']) !!}
                                                                {!! $errors->first('c_email.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- State and city -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 c_city_div {{$errors->has('c_city.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_city[0]','City',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_city[0]', '',['class' => 'form-control input-md', 'id' => 'c_city_0']) !!}
                                                                {!! $errors->first('c_city.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 c_district_id_div hidden {{$errors->has('c_district_id.0') ? 'has-error': ''}}">
                                                            {!! Form::label("c_district_id[0]",'District/ City',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("c_district_id[0]", $district_eng, '',
                                                                ['class' => 'form-control input-md','placeholder' => 'Select One', 'id' => 'c_district_id_0',
                                                                "onchange" => "getThanaByDistrictId('c_district_id_0', this.value, 'c_thana_id_0')"
                                                                ]) !!}
                                                                {!! $errors->first("c_district_id.0",'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_mobile_no.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_mobile_no[0]','Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_mobile_no[0]', '', ['class' => 'mobile-plugin phone_or_mobile form-control input-md helpText15 required', 'id' => 'c_mobile_no_0']) !!}
                                                                {!! $errors->first('c_mobile_no.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Email and Post -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 c_state_province_div {{$errors->has('c_state_province.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_state_province[0]','State/ Province',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_state_province[0]', '',['class' => 'form-control input-md', 'id' => 'c_state_province_0']) !!}
                                                                {!! $errors->first('c_state_province.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 c_thana_id_div hidden {{$errors->has('c_thana_id.0') ? 'has-error': ''}}">
                                                            {!! Form::label("c_thana_id[0]",'Police Station/ Town',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("c_thana_id[0]", [''], '',
                                                                ['class' => 'form-control input-md','placeholder' => 'Select One', 'id' => 'c_thana_id_0']) !!}
                                                                {!! $errors->first("c_thana_id.0",'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('c_shareholder_percentage.0') ? 'has-error': ''}}">
                                                            {!! Form::label('c_shareholder_percentage[0]','Shareholder percentage',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('c_shareholder_percentage[0]', '', ['class' => 'form-control number input-md required', 'id' => 'c_shareholder_percentage_0']) !!}
                                                                {!! $errors->first('c_shareholder_percentage.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Major activities in brief -->
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('c_major_activity_brief.0') ? 'has-error' : ''}}">
                                                            {!! Form::label('c_major_activity_brief[0]','Major activities in brief',['class'=>'col-md-2 text-left required-star label-activity']) !!}
                                                            <div class="col-md-10 content-activity">
                                                                {!! Form::textarea('c_major_activity_brief[0]', '', [
                                                                    'class' => 'form-control input-md bigInputField maxTextCountDown required', 
                                                                    'id' => 'c_major_activity_brief_0',
                                                                    'rows' => '3',
                                                                    'data-rule-maxlength'=>'200',
                                                                    'placeholder' => 'Maximum 200 characters',
                                                                    "data-charcount-maxlength" => "200"
                                                                ]) !!}
                                                                {!! $errors->first('c_major_activity_brief.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </fieldset>
                                        </div>
                                        <!-- /.company-section (If data not exist)-->
                                        @endif
                                    </div>
                                    <!-- /#companies-container-->
                                </div>
                                <!-- /.panel-body-->
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Project Office Info.</h3>
                        <fieldset>
                            <div class="panel panel-info">
                                <div class="panel-heading"><strong>Information about the Project Office</strong></div>
                                <div class="panel-body">
                                    {{-- 3. Project Office Address (corporate office) --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">
                                            3. Project Office Address (corporate office)
                                        </legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('poa_co_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_division_id','Division',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('poa_co_division_id', $divisions, !empty($appInfo->poa_co_division_id)?$appInfo->poa_co_division_id:'', ['class' => 'form-control input-md required', 'id' => 'poa_co_division_id', 'onchange'=>"getDistrictByDivisionId('poa_co_division_id', this.value, 'poa_co_district_id', ".$appInfo->poa_co_district_id.")"]) !!}
                                                        {!! $errors->first('poa_co_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('poa_co_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('poa_co_district_id', $district_eng, !empty($appInfo->poa_co_district_id)?$appInfo->poa_co_district_id:'', ['class' => 'form-control input-md required','placeholder' => 'Select division first', 'id' => 'poa_co_district_id', 'onchange'=>"getThanaByDistrictId('poa_co_district_id', this.value, 'poa_co_thana_id', ". $appInfo->poa_co_thana_id .")"]) !!}
                                                        {!! $errors->first('poa_co_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('poa_co_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_thana_id','Police station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('poa_co_thana_id',[''], !empty($appInfo->poa_co_thana_id)?$appInfo->poa_co_thana_id:'', ['class' => 'form-control input-md required','placeholder' => 'Select district first', 'id' => 'poa_co_thana_id']) !!}
                                                        {!! $errors->first('poa_co_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('poa_co_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_post_office','Post office',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('poa_co_post_office', !empty($appInfo->poa_co_post_office)?$appInfo->poa_co_post_office:'', ['class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('poa_co_post_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('poa_co_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_post_code','Post code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('poa_co_post_code', !empty($appInfo->poa_co_post_code)?$appInfo->poa_co_post_code:'', ['class' => 'form-control input-md post_code_bd required']) !!}
                                                        {!! $errors->first('poa_co_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('poa_co_address') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('poa_co_address', !empty($appInfo->poa_co_address)?$appInfo->poa_co_address:'', ['maxlength'=>'150','class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('poa_co_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('poa_co_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_telephone_no','Telephone no.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('poa_co_telephone_no', !empty($appInfo->poa_co_telephone_no)?$appInfo->poa_co_telephone_no:'', ['maxlength'=>'20','class' => 'form-control input-md mobile-plugin phone_or_mobile']) !!}
                                                        {!! $errors->first('poa_co_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('poa_co_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_mobile_no','Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('poa_co_mobile_no', !empty($appInfo->poa_co_mobile_no)?$appInfo->poa_co_mobile_no:'', ['class' => 'form-control mobile-plugin phone_or_mobile input-md helpText15 required' ,'id' => 'poa_co_mobile_no']) !!}
                                                        {!! $errors->first('poa_co_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('poa_co_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_fax_no','Fax no. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('poa_co_fax_no', !empty($appInfo->poa_co_fax_no)?$appInfo->poa_co_fax_no:'', ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('poa_co_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('poa_co_email') ? 'has-error': ''}}">
                                                    {!! Form::label('poa_co_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('poa_co_email', !empty($appInfo->poa_co_email)?$appInfo->poa_co_email:'', ['class' => 'form-control input-md email required']) !!}
                                                        {!! $errors->first('poa_co_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    {{-- 4. Project Office Address (site office) --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">
                                            4. Project Office Address (site office)
                                        </legend>
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success btn-sm" id="add-office-section" style="margin-right: 14px;margin-top: 16px;">
                                                <i class="fa fa-plus"></i> Add More
                                            </button>
                                        </div>

                                        <div id="office-sections">
                                            @if(count($ponSiteOfficeList) > 0)
                                            <?php $inc = 0; ?>
                                            @foreach($ponSiteOfficeList as $key => $row)
                                            <div class="office-section" data-index="{{$key}}">
                                                <div class="section-header clearfix">
                                                    <div class="pull-right">
                                                        <button type="button" class="btn btn-danger btn-sm remove-office-section" style="{{ $inc > 0 ? 'display:block;':'display:none;'}}">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Division and District --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_division_id.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::hidden("pon_site_office_record_id[$key]", $row->id) !!}
                                                            {!! Form::label("poa_so_division_id[$key]", 'Division', ['class' => 'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("poa_so_division_id[$key]", $divisions, !empty($row->poa_so_division_id)?$row->poa_so_division_id:'', [
                                                                    'class' => 'form-control input-md required poa_so_division_id',
                                                                    'id' => 'poa_so_division_id_'.$key,
                                                                    'onchange' => "getDistrictByDivisionId('poa_so_division_id_$key', this.value, 'poa_so_district_id_$key', ".$row->poa_so_district_id.")"
                                                                ]) !!}
                                                                {!! $errors->first('poa_so_division_id.'.$key, '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6 {{$errors->has('poa_so_district_id.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_district_id[$key]",'District',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("poa_so_district_id[$key]", $district_eng, !empty($row->poa_so_district_id)?$row->poa_so_district_id:'', [
                                                                    'class' => 'form-control input-md required poa_so_district_id',
                                                                    'id' => 'poa_so_district_id_'.$key,
                                                                    'placeholder' => 'Select division first',
                                                                    'onchange'=>"getThanaByDistrictId('poa_so_district_id_$key', this.value, 'poa_so_thana_id_$key', ".$row->poa_so_thana_id.")"]) !!}
                                                                {!! $errors->first('poa_so_district_id.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Police Station and Post Office --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_thana_id.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_thana_id[$key]",'Police station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select("poa_so_thana_id[$key]",[''], !empty($row->poa_so_thana_id)?$row->poa_so_thana_id:'', ['class' => 'form-control input-md required','id' => 'poa_so_thana_id_'.$key,'placeholder' => 'Select district first']) !!}
                                                                {!! $errors->first('poa_so_thana_id.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_post_office.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_post_office[$key]",'Post office',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("poa_so_post_office[$key]", !empty($row->poa_so_post_office)?$row->poa_so_post_office:'', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('poa_so_post_office.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Post Code and Address --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_post_code.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_post_code[$key]",'Post code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("poa_so_post_code[$key]", !empty($row->poa_so_post_code)?$row->poa_so_post_code:'', ['class' => 'form-control input-md post_code_bd required']) !!}
                                                                {!! $errors->first('poa_so_post_code.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_address.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_address[$key]",'House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("poa_so_address[$key]", !empty($row->poa_so_address)?$row->poa_so_address:'', ['maxlength'=>'150','class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('poa_so_address.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Telephone and Mobile --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_telephone_no.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_telephone_no[$key]",'Telephone no.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("poa_so_telephone_no[$key]", !empty($row->poa_so_telephone_no)?$row->poa_so_telephone_no:'', ['maxlength'=>'20','class' => 'form-control input-md mobile-plugin phone_or_mobile']) !!}
                                                                {!! $errors->first('poa_so_telephone_no.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_mobile_no.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_mobile_no[$key]",'Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("poa_so_mobile_no[$key]", !empty($row->poa_so_mobile_no)?$row->poa_so_mobile_no:'', ['class' => 'mobile-plugin phone_or_mobile form-control input-md helpText15 required']) !!}
                                                                {!! $errors->first('poa_so_mobile_no.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Fax and Email --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_fax_no.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_fax_no[$key]",'Fax no. ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("poa_so_fax_no[$key]", !empty($row->poa_so_fax_no)?$row->poa_so_fax_no:'', ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('poa_so_fax_no.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_email.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("poa_so_email[$key]",'Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("poa_so_email[$key]", !empty($row->poa_so_email)?$row->poa_so_email:'', ['class' => 'form-control input-md email required']) !!}
                                                                {!! $errors->first('poa_so_email.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <hr>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5" style="margin-left: 10px;">
                                                            <label class="scheduler-border" style="font-weight: bold;">Site Office Incharge Information :</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('site_office_name.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("site_office_name[$key]",'Name',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("site_office_name[$key]", !empty($row->site_office_name)?$row->site_office_name:'',['class' => 'form-control input-md required ', 'id'=>'site_office_name_0']) !!}
                                                                {!! $errors->first('site_office_name.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('site_office_designation.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("site_office_designation[$key]",'Designation',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("site_office_designation[$key]", !empty($row->site_office_designation)?$row->site_office_designation:'',['class' => 'form-control input-md required ', 'id'=>'site_office_designation_'.$key]) !!}
                                                                {!! $errors->first('site_office_designation.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('site_office_mobile_no.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("site_office_mobile_no[$key]",'Mobile No.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("site_office_mobile_no[$key]", !empty($row->site_office_mobile_no)?$row->site_office_mobile_no:'',['class' => 'mobile-plugin phone_or_mobile form-control input-md required ', 'id'=>'site_office_mobile_no_'.$key]) !!}
                                                                {!! $errors->first('site_office_mobile_no.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('site_office_email.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label("site_office_email[$key]",'Email',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text("site_office_email[$key]", !empty($row->site_office_email)?$row->site_office_email:'',['class' => 'form-control input-md required ', 'id'=>'site_office_email_'.$key]) !!}
                                                                {!! $errors->first('site_office_email.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('site_office_authorize_letter.'.$key) ? 'has-error': ''}}">
                                                            {!! Form::label('site_office_authorize_letter[0]','Authorize Letter',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {{-- {!! Form::file('site_office_authorize_letter[0]', '',['class' => 'form-control input-md required ', 'id'=>'site_office_authorize_letter_0']) !!} --}}
                                                                <input type="file" name="site_office_authorize_letter[{{$key}}]" id="site_office_authorize_letter_".$key class="form-control input-md {{ empty($row->site_office_authorize_letter) ? 'required' : ''}}"/>
                                                                @if(!empty($row->site_office_authorize_letter))
                                                                    <a href="{{ asset($row->site_office_authorize_letter) }}" class="btn btn-xs btn-primary" target="_blank">
                                                                        <i class="fa fa-download"></i> Download
                                                                    </a>
                                                                @endif
                                                                {!! $errors->first('site_office_authorize_letter.'.$key,'<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.office-section (If data exist)-->
                                            <?php $inc++; ?>
                                            @endforeach
                                            @else
                                            <div class="office-section" data-index="0">
                                                <div class="section-header clearfix">
                                                    <div class="pull-right">
                                                        <button type="button" class="btn btn-danger btn-sm remove-office-section" style="display:none;">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Division and District --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_division_id.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_division_id[0]', 'Division', ['class' => 'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('poa_so_division_id[0]', $divisions, '', [
                                                                    'class' => 'form-control input-md required',
                                                                    'id' => 'poa_so_division_id_0',
                                                                    'onchange' => "getDistrictByDivisionId('poa_so_division_id_0', this.value, 'poa_so_district_id_0')"
                                                                ]) !!}
                                                                {!! $errors->first('poa_so_division_id.0', '<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6 {{$errors->has('poa_so_district_id.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_district_id[0]','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('poa_so_district_id[0]', [], '', ['class' => 'form-control input-md required','id' => 'poa_so_district_id_0', 'placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('poa_so_district_id_0', this.value, 'poa_so_thana_id_0')"]) !!}
                                                                {!! $errors->first('poa_so_district_id.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Police Station and Post Office --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_thana_id.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_thana_id[0]','Police station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::select('poa_so_thana_id[0]',[''], '', ['class' => 'form-control input-md required','id' => 'poa_so_thana_id_0','placeholder' => 'Select district first']) !!}
                                                                {!! $errors->first('poa_so_thana_id.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_post_office.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_post_office[0]','Post office',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('poa_so_post_office[0]', '', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('poa_so_post_office.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Post Code and Address --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_post_code.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_post_code[0]','Post code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('poa_so_post_code[0]', '', ['class' => 'form-control input-md post_code_bd required']) !!}
                                                                {!! $errors->first('poa_so_post_code.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_address.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_address[0]','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('poa_so_address[0]', '', ['maxlength'=>'150','class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('poa_so_address.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Telephone and Mobile --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_telephone_no.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_telephone_no[0]','Telephone no.',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('poa_so_telephone_no[0]', '', ['maxlength'=>'20','class' => 'form-control input-md mobile-plugin phone_or_mobile']) !!}
                                                                {!! $errors->first('poa_so_telephone_no.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_mobile_no.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_mobile_no[0]','Mobile no. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('poa_so_mobile_no[0]', '', ['class' => 'mobile-plugin phone_or_mobile form-control input-md helpText15 required']) !!}
                                                                {!! $errors->first('poa_so_mobile_no.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Fax and Email --}}
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('poa_so_fax_no.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_fax_no[0]','Fax no. ',['class'=>'col-md-5 text-left']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('poa_so_fax_no[0]', '', ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('poa_so_fax_no.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('poa_so_email.0') ? 'has-error': ''}}">
                                                            {!! Form::label('poa_so_email[0]','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('poa_so_email[0]', '', ['class' => 'form-control input-md email required']) !!}
                                                                {!! $errors->first('poa_so_email.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <hr>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5" style="margin-left: 10px;">
                                                            <label class="scheduler-border" style="font-weight: bold;">Site Office Incharge Information :</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('site_office_name.0') ? 'has-error': ''}}">
                                                            {!! Form::label('site_office_name[0]','Name',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('site_office_name[0]', '',['class' => 'form-control input-md required ', 'id'=>'site_office_name_0']) !!}
                                                                {!! $errors->first('site_office_name.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('site_office_designation.0') ? 'has-error': ''}}">
                                                            {!! Form::label('site_office_designation[0]','Designation',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('site_office_designation[0]', '',['class' => 'form-control input-md required ', 'id'=>'site_office_designation_0']) !!}
                                                                {!! $errors->first('site_office_designation.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('site_office_mobile_no.0') ? 'has-error': ''}}">
                                                            {!! Form::label('site_office_mobile_no[0]','Mobile No.',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('site_office_mobile_no[0]', '',['class' => 'mobile-plugin phone_or_mobile form-control input-md required ', 'id'=>'site_office_mobile_no_0']) !!}
                                                                {!! $errors->first('site_office_mobile_no.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 {{$errors->has('site_office_email.0') ? 'has-error': ''}}">
                                                            {!! Form::label('site_office_email[0]','Email',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {!! Form::text('site_office_email[0]', '',['class' => 'form-control input-md required ', 'id'=>'site_office_email_0']) !!}
                                                                {!! $errors->first('site_office_email.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 {{$errors->has('site_office_authorize_letter.0') ? 'has-error': ''}}">
                                                            {!! Form::label('site_office_authorize_letter[0]','Authorize Letter',['class'=>'text-left col-md-5 required-star']) !!}
                                                            <div class="col-md-7">
                                                                {{-- {!! Form::file('site_office_authorize_letter[0]', '',['class' => 'form-control input-md required ', 'id'=>'site_office_authorize_letter_0']) !!} --}}
                                                                <input type="file" name="site_office_authorize_letter[0]" id="site_office_authorize_letter_0" class="form-control input-md required"/>
                                                                {!! $errors->first('site_office_authorize_letter.0','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </fieldset>


                                    {{-- 5. Capital of parent company --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">5. The contact Amount of the Project (in US $)</legend>
                                        {{-- Authorized and paid capital --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('project_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('project_amount','The contact Amount of the Project (in US $)', ['class' => 'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('project_amount', isset($appInfo->project_amount)?$appInfo->project_amount:'', ['placeholder' => 'The contact Amount of the Project (in US $)', 'class' => 'form-control input-md number required', 'id' => 'project_amount']) !!}
                                                        {!! $errors->first('project_amount','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    {{-- 6. Proposed Project Duration (as per contract) --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">6. Proposed Project Duration (as per contract)</legend>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-3 {{$errors->has('period_start_date') ? 'has-error': ''}}">
                                                    {!! Form::label('period_start_date','Start and effective date',['class'=>'text-left col-md-12 required-star']) !!}
                                                    <div class="col-md-12">
                                                        <div id="duration_start_datepicker" class="input-group date ">
                                                            {!! Form::text('period_start_date', !empty($appInfo->approved_duration_start_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_start_date)): '', ['class' => 'form-control input-md date required', 'placeholder'=>'dd-mm-yyyy', 'id' => 'period_start_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('period_start_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 {{$errors->has('period_end_date') ? 'has-error': ''}}">
                                                    {!! Form::label('period_end_date','End date',['class'=>'text-left col-md-12 required-star']) !!}
                                                    <div class="col-md-12">
                                                        <div id="duration_end_datepicker" class="input-group date ">
                                                            {!! Form::text('period_end_date', !empty($appInfo->approved_duration_end_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_end_date)): '', ['class' => 'form-control input-md date required', 'placeholder'=>'dd-mm-yyyy', 'id' => 'period_end_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        <span class="text-danger" style="font-size: 12px; font-weight: bold" id="date_compare_error"></span>
                                                        {!! $errors->first('period_end_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 {{$errors->has('period_validity') ? 'has-error': ''}}">
                                                    {!! Form::label('period_validity','Period of validity',['class'=>'col-md-12 text-left required-star']) !!}
                                                    <div class="col-md-12">
                                                        {!! Form::text('period_validity', isset($appInfo->period_validity)?$appInfo->period_validity:'', ['class' => 'form-control input-md', 'readonly', 'id' => 'period_validity']) !!}
                                                        {!! $errors->first('period_validity','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-3 {{$errors->has('duration_amount') ? 'has-error': ''}}">
                                                    {!! Form::label('duration_amount','Payable amount',['class'=>'text-left col-md-12 required-star']) !!}
                                                    <div class="col-md-12">
                                                        {!! Form::text('duration_amount', isset($appInfo->duration_amount)?$appInfo->duration_amount:'', ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('duration_amount','<span class="help-block">:message</span>') !!}
                                                        <span class="text-danger" style="font-size: 12px; font-weight: bold" id="duration_year"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    
                                    {{-- 7. Authorized Person of Procurement Entity:  --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">7. Authorized Person of Procurement Entity: </legend>
                                        {{-- Authorized and paid capital --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('authorized_name') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_name','Name',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('authorized_name', !empty($appInfo->authorized_name) ? $appInfo->authorized_name : '',['class' => 'form-control input-md required ', 'id'=>'authorized_name']) !!}
                                                        {!! $errors->first('authorized_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('authorized_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_designation','Designation',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('authorized_designation', !empty($appInfo->authorized_designation)?$appInfo->authorized_designation:'',['class' => 'form-control input-md required ', 'id'=>'authorized_designation']) !!}
                                                        {!! $errors->first('authorized_designation','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('authorized_org_dep') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_org_dep','Organization / Department',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('authorized_org_dep', !empty($appInfo->authorized_org_dep)?$appInfo->authorized_org_dep:'',['class' => 'form-control input-md required ', 'id'=>'authorized_org_dep']) !!}
                                                        {!! $errors->first('authorized_org_dep','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('authorized_address') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_address','Address',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('authorized_address', !empty($appInfo->authorized_address)?$appInfo->authorized_address:'',['class' => 'form-control input-md required ', 'id'=>'authorized_address']) !!}
                                                        {!! $errors->first('authorized_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('authorized_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_mobile_no','Mobile No.',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('authorized_mobile_no', !empty($appInfo->authorized_mobile_no)?$appInfo->authorized_mobile_no:'',['class' => 'mobile-plugin phone_or_mobile form-control input-md required ', 'id'=>'authorized_mobile_no']) !!}
                                                        {!! $errors->first('authorized_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('authorized_email') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_email','Email',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('authorized_email', !empty($appInfo->authorized_email)?$appInfo->authorized_email:'',['class' => 'form-control input-md required ', 'id'=>'authorized_email']) !!}
                                                        {!! $errors->first('authorized_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('authorized_letter') ? 'has-error': ''}}">
                                                    {!! Form::label('authorized_letter','Authorize Letter',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {{-- {!! Form::file('authorized_letter', '',['class' => 'form-control input-md required ', 'id'=>'authorized_letter']) !!} --}}
                                                        <input type="file" name="authorized_letter" id="authorized_letter" class="form-control input-md {{ empty($appInfo->authorized_letter) ? 'required' : ''}}"/>
                                                        @if(!empty($appInfo->authorized_letter))
                                                            <a href="{{ asset($appInfo->authorized_letter) }}" class="btn btn-xs btn-primary" target="_blank">
                                                                <i class="fa fa-download"></i> Download
                                                            </a>
                                                        @endif
                                                        {!! $errors->first('authorized_letter','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    {{-- 8. Ministry/Department/Organization of the project to be implemented:  --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">8. Ministry/Department/Organization of the project to be implemented</legend>
                                        {{-- Authorized and paid capital --}}
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ministry_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ministry_name','Name',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ministry_name', !empty($appInfo->ministry_name)?$appInfo->ministry_name:'',['class' => 'form-control input-md required ', 'id'=>'ministry_name']) !!}
                                                        {!! $errors->first('ministry_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ministry_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ministry_address','Address',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ministry_address', !empty($appInfo->ministry_address)?$appInfo->ministry_address:'',['class' => 'form-control input-md required ', 'id'=>'ministry_address']) !!}
                                                        {!! $errors->first('ministry_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('contract_signing_date') ? 'has-error': ''}}">
                                                    {!! Form::label('contract_signing_date','Contract signing Date',['class'=>'text-left col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        <div id="contract_signing_datepicker" class="input-group date datepicker">
                                                            {!! Form::text('contract_signing_date', !empty($appInfo->contract_signing_date) ? date('d-M-Y', strtotime($appInfo->contract_signing_date)): null, ['class' => 'form-control input-md required', 'placeholder'=>'dd-mm-yyyy', 'id' => 'contract_signing_date']) !!}
                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('contract_signing_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    {{-- 9. Proposed organizational set up of the project Office with expatriate and local man power --}}
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">9. Proposed organizational set up of the project Office with expatriate and local man power</legend>
                                            <div class="form-group clearfix">
                                                <div class="col-md-12" style="padding: 0">
                                                    <div class="table-responsive">
                                                        <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead class="alert alert-info">
                                                            <tr>
                                                                <th scope="col" class="alert alert-info text-center" colspan="3">Local (a)</th>
                                                                <th scope="col" class="alert alert-info text-center" colspan="3">Foreign (b)</th>
                                                                <th scope="col" class="alert alert-info text-center" colspan="1">Grand total</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="manpower">
                                                            <tr>
                                                                <th scope="col" class="alert alert-info text-center required-star">Technical</th>
                                                                <th scope="col" class="alert alert-info text-center required-star">General</th>
                                                                <th scope="col" class="alert alert-info text-center">Total</th>
                                                                <th scope="col" class="alert alert-info text-center required-star">Technical</th>
                                                                <th scope="col" class="alert alert-info text-center required-star">General</th>
                                                                <th scope="col" class="alert alert-info text-center">Total</th>
                                                                <th scope="col" class="alert alert-info text-center"> (a+b)</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    {!! Form::text('local_technical', !empty($appInfo->local_technical)?$appInfo->local_technical:'', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'local_technical']) !!}
                                                                    {!! $errors->first('local_technical','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('local_general', isset($appInfo->local_general)?$appInfo->local_general:'', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'local_general']) !!}
                                                                    {!! $errors->first('local_general','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('local_total', isset($appInfo->local_total)?$appInfo->local_total:'', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative required','id'=>'local_total','readonly']) !!}
                                                                    {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_technical', isset($appInfo->foreign_technical)?$appInfo->foreign_technical:'', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'foreign_technical']) !!}
                                                                    {!! $errors->first('foreign_technical','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_general', isset($appInfo->foreign_general)?$appInfo->foreign_general:'', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'foreign_general']) !!}
                                                                    {!! $errors->first('foreign_general','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_total', isset($appInfo->foreign_total)?$appInfo->foreign_total:'', ['data-rule-maxlength'=>'40','class' => 'form-control input-md numberNoNegative required','id'=>'foreign_total','readonly']) !!}
                                                                    {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('manpower_total',  isset($appInfo->manpower_total)?$appInfo->manpower_total:'', ['data-rule-maxlength'=>'40','class' => 'form-control input-md number required','id'=>'mp_total','readonly']) !!}
                                                                    {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>

                                                        <div id="foreign_details_table" style="display: none;">
                                                            <legend class="scheduler-border"> Foreign Technical & General Details: </legend>
                                                            <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                <thead class="alert alert-info">
                                                                    <tr>
                                                                        <th scope="col" class="alert alert-info text-center required-star">Number of Foreign</th>
                                                                        <th scope="col" class="alert alert-info text-center required-star">Designation</th>
                                                                        <th scope="col" class="alert alert-info text-center required-star">Duration</th>
                                                                        <th scope="col" class="alert alert-info text-center">#</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="foreign_details_table_body">
                                                                    @if(count($ponForeignDetailList) > 0)
                                                                    <?php $inc = 0; ?>
                                                                    @foreach($ponForeignDetailList as $key => $row)
                                                                    <tr>
                                                                        <td>
                                                                            {!! Form::text("foreign_number[$key]", isset($row->foreign_number)?$row->foreign_number:'', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md number', 'id'=>'foreign_number_'.$key]) !!}
                                                                            {!! $errors->first('foreign_number','<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td>
                                                                            {!! Form::text("foreign_designation[$key]", !empty($row->foreign_designation)?$row->foreign_designation:'', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md', 'id'=>'foreign_designation_'.$key]) !!}
                                                                            {!! $errors->first('foreign_designation','<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td>
                                                                            {!! Form::text("foreign_duration[$key]", isset($row->foreign_duration)?$row->foreign_duration:'', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md', 'id'=>'foreign_duration_'.$key]) !!}
                                                                            {!! $errors->first('foreign_duration','<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td style="text-align: center;">
                                                                            <?php if ($inc == 0) { ?>
                                                                                <a class="btn btn-sm btn-primary addTableRowsForeign" title="Add more" onclick="addTableRowForeign('foreign_details_table_body');">
                                                                                    <i class="fa fa-plus text-center"></i>
                                                                                </a>
                                                                            <?php } else { ?>
                                                                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger removeTableRow" title="Remove">
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </a>
                                                                            <?php } ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $inc++; ?>
                                                                    @endforeach
                                                                    @else
                                                                    <tr>
                                                                        <td>
                                                                            {!! Form::text('foreign_number[0]', '', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md number', 'id'=>'foreign_number_0']) !!}
                                                                            {!! $errors->first('foreign_number','<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td>
                                                                            {!! Form::text('foreign_designation[0]', '', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md', 'id'=>'foreign_designation_0']) !!}
                                                                            {!! $errors->first('foreign_designation','<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td>
                                                                            {!! Form::text('foreign_duration[0]', '', ['data-rule-maxlength'=>'40', 'class' => 'form-control input-md', 'id'=>'foreign_duration_0']) !!}
                                                                            {!! $errors->first('foreign_duration','<span class="help-block">:message</span>') !!}
                                                                        </td>
                                                                        <td style="text-align: center;">
                                                                            <a class="btn btn-sm btn-primary addTableRowsForeign" title="Add more" onclick="addTableRowForeign('foreign_details_table_body');">
                                                                                <i class="fa fa-plus text-center"></i>
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </fieldset>
                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Attachments</h3>
                        <fieldset>
                            <legend class="d-none">Attachments</legend>
                            <div id="docListDiv">
                            </div>
                            @if($viewMode != 'off')
                                @include('ProjectOfficeNew::doc-tab')
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

                                </div>
                            </div>
                        </fieldset>

                        <h3 class="stepHeader">Payment & Submit</h3>
                        <fieldset>
                            <legend class="d-none">Payment & Submit</legend>
                            @if($viewMode != 'on')
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <strong>Service fee payment</strong>
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
                                                        {!! Form::email('sfp_contact_email', $appInfo->sfp_contact_email, ['class' => 'form-control input-md email required']) !!}
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
                                                        {!! Form::text('sfp_contact_phone', $appInfo->sfp_contact_phone, ['class' => 'form-control input-md sfp_contact_phone required phone_or_mobile']) !!}
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

                        @if(ACL::getAccsessRight('ProjectOfficeNew','-E-') && $viewMode != "on" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                            
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

{{--initial- input plugin script start--}}
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
<script src="{{ asset("vendor/character-counter/jquery.character-counter_v1.0.min.js") }}" src="" type="text/javascript"></script>

{{--Datepicker js--}}
<script src="{{ asset("vendor/datepicker/datepicker.min.js") }}"></script>


<script>
    
    function CategoryWiseDocLoad() {

        var attachment_key = "pon_branch";

        var _token = $('input[name="_token"]').val();
        var app_id = $("#app_id").val();
        var viewMode = $("#viewMode").val();

        $.ajax({
            type: "POST",
            url: '/project-office-new/getDocList',
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
            var action = "{{url('/office-permission-new/upload-document')}}";

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

    $(document).ready(function(){

        $("#poa_co_division_id").trigger('change');
        $("#poa_co_district_id").trigger('change');
        $(".poa_so_division_id").trigger('change');
        $(".poa_so_district_id").trigger('change');
        $(".c_district_id").trigger('change');
        
        CategoryWiseDocLoad();

        // start -:- Step form code
            var form = $("#ProjectOfficeNewForm").show();
            // form.find('#save_as_draft').css('display','none');
            form.find('#submitForm').css('display', 'none');
            form.find('.actions').css('top','-15px !important');
            form.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex) {
                    
                    if (currentIndex === 0 && newIndex === 1) { // Step 1 to Step 2
                    let total = 0;
                    $('input[name^="c_shareholder_percentage"]').each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        total += value;
                    });

                    if (total !== 100) {
                        alert("Total shareholder percentage must be exactly 100%");
                        return false; // Prevent moving to next step
                    }
                    }

                    if(newIndex == 1){}

                    if(newIndex == 2){}

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
                    // if (currentIndex != 0) {
                    //     form.find('#save_as_draft').css('display','block');
                    //     form.find('.actions').css('top','-42px');
                    // } else {
                    //     form.find('#save_as_draft').css('display','none');
                    //     form.find('.actions').css('top','-15px');
                    // }
                    //console.log(currentIndex);
                    if(currentIndex == 4) {
                        form.find('#submitForm').css('display','block');

                        $('#submitForm').on('click', function (e) {
                            form.validate().settings.ignore = ":disabled";
                            //console.log('onStepChanged => form.validate().errors()', form.validate().errors()); // show hidden errors in last step
                            return form.valid();
                        });

                    } else {
                        form.find('#submitForm').css('display','none');
                    }
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled";
                    //console.log('onFinishing => form.validate()', form.validate());
                    return form.valid();
                },
                onFinished: function (event, currentIndex) {
                    errorPlacement: function errorPlacement(error, element) {
                        element.before(error);
                    }
                }
            });
        // end -:- Step form code

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

        // CategoryWiseDocLoad();
        var popupWindow = null;
        $('.finish').on('click', function (e) {
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('/project-office-new/preview'); ?>', 'Sample', '');
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
            maxDate: '01/01/' + (yyyy + 150),
            minDate: '01/01/' + (yyyy - 150)
        });
        
        //max text count down
        $('.maxTextCountDown').characterCounter();

        $("#pon_mobile_no").intlTelInput({
            hiddenInput: ["pon_mobile_no"],
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $(".mobile-plugin").intlTelInput({
            hiddenInput: "mobile-plugin",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#auth_cell_number").intlTelInput({
            hiddenInput: "auth_cell_number",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#sfp_contact_phone").intlTelInput({
            hiddenInput: "sfp_contact_phone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#c_telephone").intlTelInput({
            hiddenInput: "c_telephone",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#applicationForm").find('.iti').css({"width":"-moz-available", "width":"-webkit-fill-available"});

        var process_id = '{{ $process_type_id }}';
        var dd_startDateDivID = 'duration_start_datepicker';
        var dd_startDateValID = 'period_start_date';
        var dd_endDateDivID = 'duration_end_datepicker';
        var dd_endDateValID = 'period_end_date';
        var dd_show_durationID = 'period_validity';
        var dd_show_amountID = 'duration_amount';
        var dd_show_yearID = 'duration_year';

        $("#"+dd_startDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY'
        });
        $("#"+dd_endDateDivID).datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
        });
        $("#"+dd_startDateDivID).on("dp.change", function (e) {
            var startDateVal = $("#"+dd_startDateValID).val();

            // var date_format = $("#"+dd_startDateValID).val().replace(/-/g, ' ');
            // var actualDate = new Date(date_format); // convert to actual date
            //for next three year after date
            // var nextThreeYearDate = new Date(actualDate.getFullYear(), actualDate.getMonth() + 36, actualDate.getDate() - 1);

            if (startDateVal != '') {
                // Min value set for end date
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(e.date);
                // $("#"+dd_endDateDivID).data("DateTimePicker").maxDate(nextThreeYearDate);

                var endDateVal = $("#"+dd_endDateValID).val();
                if (endDateVal != '') {
                    getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID, dd_show_amountID, dd_show_yearID);
                } else {
                    $("#"+dd_endDateValID).addClass('error');
                }
            } else {
                $("#"+dd_show_durationID).val('');
                $("#"+dd_show_amountID).val('');
                $("#"+dd_show_yearID).text('');
            }
        });
        $("#"+dd_endDateDivID).on("dp.change", function (e) {
            // Max value set for start date
           // $("#"+dd_startDateDivID).data("DateTimePicker").maxDate(e.date);

            var startDateVal = $("#"+dd_startDateValID).val();

            if (startDateVal === '') {
                $("#"+dd_startDateValID).addClass('error');
            } else {
                var day = moment(startDateVal, ['DD-MMM-YYYY']);
                //var minStartDate = moment(day).add(1, 'day');
                $("#"+dd_endDateDivID).data("DateTimePicker").minDate(day);
            }

            var endDateVal = $("#"+dd_endDateValID).val();

            if (startDateVal != '' && endDateVal != '') {
                getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID, dd_show_amountID, dd_show_yearID);
            }else{
                $("#"+dd_show_durationID).val('');
                $("#"+dd_show_amountID).val('');
                $("#"+dd_show_yearID).text('');
            }
        });


        let sectionCounter = 1;
        
        // Add new office section when the button is clicked
        $('#add-office-section').on('click', function() {
            let template = $('.office-section').first().clone();
            template.attr('data-index', sectionCounter);
            
            // Update the name and id attributes to ensure unique field names
            template.find('input, select, textarea, label').each(function() {
                let elem = $(this);
                
                // Update name attribute
                if (elem.attr('name')) {
                    let newName = elem.attr('name').replace(/\[0\]/g, '[' + sectionCounter + ']');
                    elem.attr('name', newName);
                }
                
                // Update id attribute
                if (elem.attr('id')) {
                    let newId = elem.attr('id');
                    if (newId === 'poa_so_division_id') {
                        newId = 'poa_so_division_id_' + sectionCounter;
                    } else {
                        newId = newId.replace(/_0/g, '_' + sectionCounter);
                    }
                    elem.attr('id', newId);
                }
                
                // Update for attribute for labels
                if (elem.attr('for')) {
                    let newFor = elem.attr('for').replace(/\[0\]/g, '[' + sectionCounter + ']');
                    elem.attr('for', newFor);
                }
                
                // Update onchange attributes to reference the new IDs
                if (elem.attr('onchange')) {
                    let newOnchange = elem.attr('onchange');
                    
                    // Replace the first parameter in getDistrictByDivisionId with the new division ID
                    if (newOnchange.includes('getDistrictByDivisionId')) {
                        newOnchange = newOnchange.replace(
                            /getDistrictByDivisionId\(['"]poa_so_division_id(_\d+)?['"]/,
                            'getDistrictByDivisionId(\'poa_so_division_id_' + sectionCounter + '\''
                        );
                    }
                    
                    // Update target element IDs
                    newOnchange = newOnchange.replace(/poa_so_district_id_\d+/g, 'poa_so_district_id_' + sectionCounter);
                    newOnchange = newOnchange.replace(/poa_so_thana_id_\d+/g, 'poa_so_thana_id_' + sectionCounter);
                    
                    elem.attr('onchange', newOnchange);
                }
            });
            
            // Clear values for inputs and other fields
            template.find('input:not([type="radio"]), select, textarea').val('');
            template.find('input[type="radio"]').prop('checked', false);
            
            // Reset select dropdowns to initial state
            let districtSelect = template.find('select[id="poa_so_district_id_' + sectionCounter + '"]');
            districtSelect.empty().append($('<option>').text('Select division first').val(''));
                
            let thanaSelect = template.find('select[id="poa_so_thana_id_' + sectionCounter + '"]');
            thanaSelect.empty().append($('<option>').text('Select district first').val(''));
            
            // Show remove button
            template.find('.remove-office-section').show();
            
            // Append the new section to the container
            $('#office-sections').append(template);
            sectionCounter++;
        });
        
        // Use event delegation for the remove button
        $('#office-sections').on('click', '.remove-office-section', function() {
            $(this).closest('.office-section').remove();
        });


        // Calculate totals on input change for manpower section
        $('#manpower').find('input').on('input', function () {
            var local_technical = $('#local_technical').val() ? parseFloat($('#local_technical').val()) : 0;
            var local_general = $('#local_general').val() ? parseFloat($('#local_general').val()) : 0;
            var local_total = local_technical + local_general;
            $('#local_total').val(local_total);

            var foreign_technical = $('#foreign_technical').val() ? parseFloat($('#foreign_technical').val()) : 0;
            var foreign_general = $('#foreign_general').val() ? parseFloat($('#foreign_general').val()) : 0;
            var foreign_total = foreign_technical + foreign_general;
            $('#foreign_total').val(foreign_total);

            var mp_total = local_total + foreign_total;
            $('#mp_total').val(mp_total);
        });

        // Function to toggle the visibility of the Foreign Details table
        function toggleForeignDetails() {
            let foreignTechnical = $('#foreign_technical').val();
            let foreignGeneral = $('#foreign_general').val();

            if (foreignTechnical > 0 || foreignGeneral > 0) {
                $('#foreign_details_table').show();  // Show the table

                // Add required attributes and class to inputs
                $('#foreign_details_table').find('input').each(function () {
                    $(this).addClass('required');
                });
            } else {
                $('#foreign_details_table').hide();  // Hide the table

                // Remove required attributes and class from inputs
                $('#foreign_details_table').find('input').each(function () {
                    $(this).removeClass('required');
                });
            }
        }

        // Check on page load
        toggleForeignDetails();

        // Check when input values change
        $('#foreign_technical, #foreign_general').on('input', function () {
            toggleForeignDetails();
        });

        var foreignIndex = 1;  // Start from index 1 for the new rows

        // Function to add a new row to the Foreign Technical & General Details table
        function addTableRowForeign(tableId) {
            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="foreign_number[${foreignIndex}]" class="form-control input-md number required" data-rule-maxlength="40">
                    </td>
                    <td>
                        <input type="text" name="foreign_designation[${foreignIndex}]" class="form-control input-md required" data-rule-maxlength="40">
                    </td>
                    <td>
                        <input type="text" name="foreign_duration[${foreignIndex}]" class="form-control input-md required" data-rule-maxlength="40">
                    </td>
                    <td style="text-align: center;">
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger removeTableRow" title="Remove">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `;
            $('#' + tableId).append(newRow);
            foreignIndex++;  // Increment the index after each new row
        }

        // Function to remove a table row in the Foreign Technical & General Details section
        $(document).on('click', '.removeTableRow', function () {
            $(this).closest('tr').remove();  // Remove the clicked row
        });

        // Attach the addTableRowForeign function to the Add More button for Foreign Technical & General Details
        $('.addTableRowsForeign').on('click', function () {
            addTableRowForeign('foreign_details_table_body'); // Table body ID to add row to
        });

        // Counter to keep track of the number of company sections
        let companyCounter = 0;

        // Get the original company section for cloning
        const originalCompany = $('.company-section').first().clone();

        // Store the original organization types HTML for later use
        const originalOrgTypeOptions = $('#c_org_type_0').html();
        const originalCountryOptions = $('#c_origin_country_id_0').html();

        // Function to clear form values
        function clearFormValues(element) {
            //element.find('input[type="text"], input[type="file"], textarea').val('');
            
            element.find('input[type="text"], input[type="file"], textarea, input[type="hidden"]').each(function() {
                $(this).val('').attr('value', '');
            });

            element.find('select').each(function() {
                $(this).val('');
                // Reset select elements to their original options
                if ($(this).attr('id') && $(this).attr('id').includes('c_org_type')) {
                    $(this).html(originalOrgTypeOptions);
                }
                if ($(this).attr('id') && $(this).attr('id').includes('c_origin_country_id')) {
                    $(this).html(originalCountryOptions);
                }
            });
            element.find('input[type="radio"]').prop('checked', false);
            element.find('.help-block').remove();
            element.find('.has-error').removeClass('has-error');
        }

        // Function to update indices in name and id attributes
        function updateIndices(element, index) {
            element.attr('data-company-index', index);

            // Update all form element names and IDs
            element.find('input, select, textarea').each(function() {
                let name = $(this).attr('name');
                if (name) {
                    // Replace the index in array-style names: name[0] to name[index]
                    name = name.replace(/\[\d+\]/g, `[${index}]`);
                    $(this).attr('name', name);
                }

                // Update IDs carefully to maintain consistency
                let id = $(this).attr('id');
                if (id) {
                    // Handle different ID formats
                    if (id.match(/_\d+$/)) {
                        // Format like c_origin_country_id_0
                        id = id.replace(/_\d+$/, `_${index}`);
                    } else if (id.match(/\d+$/)) {
                        // Format like elementname0
                        id = id.replace(/\d+$/, index);
                    } else {
                        // Add index if no index is present
                        id = `${id}_${index}`;
                    }
                    $(this).attr('id', id);
                }
            });

            // Update label for attributes
            element.find('label').each(function() {
                let forAttr = $(this).attr('for');
                if (forAttr) {
                    if (forAttr.includes('[')) {
                        // Handle array notation in for attributes
                        forAttr = forAttr.replace(/\[\d+\]/g, `[${index}]`);
                    }
                    
                    // Handle different ID formats in for attributes
                    if (forAttr.match(/_\d+$/)) {
                        forAttr = forAttr.replace(/_\d+$/, `_${index}`);
                    } else if (forAttr.match(/\d+$/)) {
                        forAttr = forAttr.replace(/\d+$/, index);
                    }
                    
                    $(this).attr('for', forAttr);
                }
            });
            
            // Update div IDs that contain indices
            element.find('div[id]').each(function() {
                let divId = $(this).attr('id');
                if (divId) {
                    if (divId.includes('_div')) {
                        // Handle div IDs that might have indices
                        if (divId.match(/_\d+_/)) {
                            divId = divId.replace(/_\d+_/, `_${index}_`);
                        }
                        $(this).attr('id', divId);
                    }
                }
            });
        }
        
        // Function to initialize datepickers
        function initializeDatepickers(element) {
            // First, destroy any existing datepickers to avoid duplicates
            element.find('.datepicker').each(function() {
                if ($(this).data('datepicker')) {
                    $(this).datepicker('destroy');
                }
            });
            
            // Initialize datepickers with proper options
            element.find('.datepicker').datepicker({
                outputFormat: 'dd-MMM-y',
                theme: 'blue',
            });
        }

        // Add company button click handler
        $('#add-company-btn').on('click', function() {
            companyCounter++;

            // Clone the original company section
            let newCompany = originalCompany.clone(true, true);

            // Clear values and update indices
            clearFormValues(newCompany);
            updateIndices(newCompany, companyCounter);
            removeAttributes(newCompany);

            // Always show remove button for added companies
            newCompany.find('.remove-company-btn').show();

            // Make sure any hidden divs stay hidden in the clone
            newCompany.find('.online-oss-question').hide();
            newCompany.find('[id$="ref_app_tracking_no_div"]').addClass('hidden');
            newCompany.find('[id$="manually_approved_no_div"]').addClass('hidden');

            // Reset the organization type dropdown in the clone
            let orgTypeSelect = newCompany.find('[id^="c_org_type"]');
            orgTypeSelect.html(originalOrgTypeOptions);
        
            // Also update the country select ID to ensure unique IDs
            let countrySelect = newCompany.find('[id^="c_origin_country_id"]');
            countrySelect.html(originalCountryOptions);
            
            newCompany.find('select').each(function() {
                let elem = $(this);
                if (elem.attr('onchange')) {
                    let newOnchange = elem.attr('onchange');
                    newOnchange = newOnchange.replace(/c_district_id_\d+/g, 'c_district_id_' + sectionCounter);
                    newOnchange = newOnchange.replace(/c_thana_id_\d+/g, 'c_thana_id_' + sectionCounter);
                    elem.attr('onchange', newOnchange);
                }
            });
            let originalDistrictOptions = $('#c_district_id_0').html();
            let districtSelect = newCompany.find('select[id="c_district_id_' + sectionCounter + '"]');
            districtSelect.html(originalDistrictOptions);
            let thanaSelect = newCompany.find('select[id="c_thana_id_' + sectionCounter + '"]');
            thanaSelect.empty().append($('<option>').text('Select district first').val(''));


            // Append the new company section
            $('#companies-container').append(newCompany);

            // Initialize datepickers in the new section
            initializeDatepickers(newCompany);
        });
        
        // Remove company button click handler (using event delegation)
        $('#companies-container').on('click', '.remove-company-btn', function() {
            // Don't allow removing the first section
            let companySection = $(this).closest('.company-section');
            let index = parseInt(companySection.attr('data-company-index'));

            if (index > 0) {
                companySection.remove();
            }
        });

        // Event delegation for BIDA approval radio buttons in cloned sections
        $('#companies-container').on('change', '.company-office-approved', function() {
            let value = $(this).val();
            let companySection = $(this).closest('.company-section');
            
            if (value === 'yes') {
                companySection.find('.online-oss-question').show();
            } else if (value === 'no') {
                companySection.find('.online-oss-question').hide();
                companySection.find('[id$="ref_app_tracking_no_div"], [id$="manually_approved_no_div"]').addClass('hidden');
                
                companySection.find('input[name^="is_approval_online"]').prop('checked', false);
                companySection.find('[id$="ref_app_tracking_no_div"] input, [id$="manually_approved_no_div"] input').val('');
                companySection.find('[id$="approval_copy"]').val('');
            }
        });

        // Event delegation for online/offline approval radio buttons
        $('#companies-container').on('change', 'input[name^="is_approval_online"]', function() {
            let ossValue = $(this).val();
            let companySection = $(this).closest('.company-section');
            
            if (ossValue === 'yes') {
                companySection.find('[id$="ref_app_tracking_no_div"]').removeClass('hidden');
                companySection.find('[id$="manually_approved_no_div"]').addClass('hidden');

                companySection.find("input[name^='ref_app_tracking_no']").addClass('required');
                companySection.find("input[name^='ref_app_approve_date']").addClass('required');

                companySection.find("input[name^='manually_approved_op_no']").removeClass('required');
                companySection.find("input[name^='manually_approved_br_date']").removeClass('required');

            } else if (ossValue === 'no') {
                companySection.find('[id$="manually_approved_no_div"]').removeClass('hidden');
                companySection.find('[id$="ref_app_tracking_no_div"]').addClass('hidden');

                companySection.find("input[name^='manually_approved_op_no']").addClass('required');
                companySection.find("input[name^='manually_approved_br_date']").addClass('required');

                companySection.find("input[name^='ref_app_tracking_no']").removeClass('required');
                companySection.find("input[name^='ref_app_approve_date']").removeClass('required');
            }
        });

         // Function to handle country selection changes
        function handleCountryChange() {
            $('#companies-container').on('change', '[id^="c_origin_country_id"]', function() {
                let selectedCountry = $(this).val();
                let companySection = $(this).closest('.company-section');
                let orgTypeSelect = companySection.find('[id^="c_org_type"]');
                
                // Reset to original options first
                orgTypeSelect.html(originalOrgTypeOptions);
                
                // Remove 'Proprietorship organization' option for non-Bangladesh countries
                if (selectedCountry != 18) {
                    orgTypeSelect.find('option').each(function() {
                        if ($(this).text().trim() === 'Proprietorship organization') {
                            $(this).remove();
                        }
                    });
                    companySection.find('.c_city_div').removeClass('hidden');
                    companySection.find('.c_state_province_div').removeClass('hidden');
                    companySection.find("input[name^='c_city']").addClass('required');
                    companySection.find("input[name^='c_state_province']").addClass('required');
                    companySection.find('.c_district_id_div').addClass('hidden');
                    companySection.find('.c_thana_id_div').addClass('hidden');
                    companySection.find("select[name^='c_district_id']").removeClass('required');
                    companySection.find("select[name^='c_thana_id']").removeClass('required');
                }else{
                    companySection.find('.c_city_div').addClass('hidden');
                    companySection.find('.c_state_province_div').addClass('hidden');
                    companySection.find("input[name^='c_city']").removeClass('required');
                    companySection.find("input[name^='c_state_province']").removeClass('required');
                    companySection.find('.c_district_id_div').removeClass('hidden');
                    companySection.find('.c_thana_id_div').removeClass('hidden');
                    companySection.find("select[name^='c_district_id']").addClass('required');
                    companySection.find("select[name^='c_thana_id']").addClass('required');
                }
            });
        }

        // Initialize country change handler
        handleCountryChange();

        // Trigger change event for all existing country selects on page load
        $('[id^="c_origin_country_id"]').each(function() {
            $(this).trigger('change');
        });

        // Initialize datepickers on the first company section
        initializeDatepickers($('.company-section').first());
          
    }); // end of document.ready

    function loadOfficePermissionData(el)
    {
        let companySection = $(el).closest(".company-section");
        let companySectionIndex = $(el).closest(".company-section").attr('data-company-index');
        let ref_app_tracking_no = companySection.find("input[name^='ref_app_tracking_no']").val();
        if (ref_app_tracking_no) {
            $.ajax({
                type: "POST",
                url: "{{ url('/project-office-new/load-office-permission-data') }}",
                dataType: "json",
                data: {
                    ref_app_tracking_no: ref_app_tracking_no
                },
                beforeSend: function() {
                    companySection.find("button[name^='searchOPinfo']").html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                    companySection.find("button[name^='searchOPinfo']").prop('disabled', true);
                },
                success: function(response) {
                    console.log(response.data);
                    if (response.success === true) {
                        companySection.find("input[name^='is_approval_online']").prop('disabled', true);
                        let selectedApprovalValue = companySection.find("input[name^='is_approval_online']:checked").val();
                        let is_approval_online_hidden_id = companySection.find('#c_origin_country_id_hidden_' + companySectionIndex);
                        if(is_approval_online_hidden_id){
                            is_approval_online_hidden_id.remove();
                        }
                        companySection.find("input[name^='is_approval_online']").after(
                            '<input type="hidden" name="is_approval_online[' + companySectionIndex + ']" value="' + selectedApprovalValue + '" id="c_origin_country_id_hidden_'+companySectionIndex+'"/>'
                        );

                        companySection.find("input[name^='c_company_name']").val(response.data.c_company_name).prop('readonly', true);

                        companySection.find("select[name^='c_origin_country_id']").val(response.data.c_origin_country_id).prop('disabled', true);
                        let c_origin_country_id_hidden_id = companySection.find('#c_origin_country_id_hidden_' + companySectionIndex);
                        if(c_origin_country_id_hidden_id){
                            c_origin_country_id_hidden_id.remove();
                        }
                        companySection.find("select[name^='c_origin_country_id']").after(
                            '<input type="hidden" name="c_origin_country_id[' + companySectionIndex + ']" value="' + response.data.c_origin_country_id + '" id="c_origin_country_id_hidden_'+companySectionIndex+'"/>'
                        );

                        companySection.find("select[name^='c_org_type']").val(response.data.c_org_type).prop('disabled', true);
                        let c_org_type_hidden_id = companySection.find('#c_org_type_hidden_' + companySectionIndex);
                        if(c_org_type_hidden_id){
                            c_org_type_hidden_id.remove();
                        }
                        companySection.find("select[name^='c_org_type']").after(
                            '<input type="hidden" name="c_org_type[' + companySectionIndex + ']" value="' + response.data.c_org_type + '" id="c_org_type_hidden_'+companySectionIndex+'"/>'
                        );
                        
                        companySection.find("input[name^='c_flat_apart_floor']").val(response.data.c_flat_apart_floor).prop('readonly', true);
                        companySection.find("input[name^='c_house_plot_holding']").val(response.data.c_house_plot_holding).prop('readonly', true);
                        companySection.find("input[name^='c_post_zip_code']").val(response.data.c_post_zip_code).prop('readonly', true);
                        companySection.find("input[name^='c_street']").val(response.data.c_street).prop('readonly', true);
                        companySection.find("input[name^='c_email']").val(response.data.c_email).prop('readonly', true);
                        companySection.find("input[name^='c_city']").val(response.data.c_city).prop('readonly', true);
                        //companySection.find("input[name^='c_mobile_no']").val(response.data.c_mobile_no).prop('readonly', true);
                        companySection.find("input[name^='c_state_province']").val(response.data.c_state_province).prop('readonly', true);
                        companySection.find("textarea[name^='c_major_activity_brief']").val(response.data.c_major_activity_brief).prop('readonly', true);
                        companySection.find("input[name^='ref_app_approve_date']").val(response.data.ref_app_approve_date).prop('readonly', true);
                        companySection.find("button[name^='searchOPinfo']").prop('disabled', true);
                    }else{
                        companySection.find("button[name^='searchOPinfo']").prop('disabled', false);
                        alert(response.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //console.log(errorThrown);
                    companySection.find("button[name^='searchOPinfo']").prop('disabled', false);
                    alert('An unknown error occurred. Please try again after reloading the page.');
                },
                complete: function() {
                    companySection.find("button[name^='searchOPinfo']").html('Load OPN/OPE Data');
                },
            });
        }else{
            alert('Please give your approved Office Permission New / Office Permission Extension Tracking No.');
        }
    }// end loadOfficePermissionData()

    function handleCompanyOfficeApproved(el)
    {
        let companySection = $(el).closest(".company-section");
        let companySectionIndex = $(el).closest(".company-section").attr('data-company-index');

        companySection.find("input[name^='ref_app_tracking_no']").val('').prop('readonly', false);
        companySection.find("input[name^='is_approval_online']").prop('disabled', false);
        let selectedApprovalValue = companySection.find("input[name^='is_approval_online']:checked").val();
        let is_approval_online_hidden_id = companySection.find('#c_origin_country_id_hidden_' + companySectionIndex);
        if(is_approval_online_hidden_id){
            is_approval_online_hidden_id.remove();
        }
        companySection.find("input[name^='c_company_name']").val('').prop('readonly', false);
        companySection.find("select[name^='c_origin_country_id']").val('').prop('disabled', false);
        let c_origin_country_id_hidden_id = companySection.find('#c_origin_country_id_hidden_' + companySectionIndex);
        if(c_origin_country_id_hidden_id){
            c_origin_country_id_hidden_id.remove();
        }
        companySection.find("select[name^='c_org_type']").val('').prop('disabled', false);
        let c_org_type_hidden_id = companySection.find('#c_org_type_hidden_' + companySectionIndex);
        if(c_org_type_hidden_id){
            c_org_type_hidden_id.remove();
        }
        companySection.find("input[name^='c_flat_apart_floor']").val('').prop('readonly', false);
        companySection.find("input[name^='c_house_plot_holding']").val('').prop('readonly', false);
        companySection.find("input[name^='c_post_zip_code']").val('').prop('readonly', false);
        companySection.find("input[name^='c_street']").val('').prop('readonly', false);
        companySection.find("input[name^='c_email']").val('').prop('readonly', false);
        companySection.find("input[name^='c_city']").val('').prop('readonly', false);
        //companySection.find("input[name^='c_mobile_no']").val(''').prop('readonly', false);
        companySection.find("input[name^='c_state_province']").val('').prop('readonly', false);
        companySection.find("textarea[name^='c_major_activity_brief']").val('').prop('readonly', false);
        companySection.find("input[name^='ref_app_approve_date']").val('');
        companySection.find("button[name^='searchOPinfo']").prop('disabled', false);

    }// end handleCompanyOfficeApproved()

    function removeAttributes(element)
    {
        element.find('input, select, textarea').each(function() {
            var excludedPrefixes = ['ref_app_approve_date'];
            var elementName = $(this).attr('name');
            var exclude = excludedPrefixes.some(function(prefix) {
                return elementName.startsWith(prefix);
            });
            if (!exclude) {
                $(this).removeAttr('readonly').removeAttr('disabled');
            }
        });
    }// end removeAttributes()
</script>
