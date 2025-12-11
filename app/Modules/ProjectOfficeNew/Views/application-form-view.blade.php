<?php
$accessMode = ACL::getAccsessRight('ProjectOfficeNew');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>
    .row>.col-md-5,
    .row>.col-md-7,
    .row>.col-md-3,
    .row>.col-md-9,
    .row>.col-md-12>strong:first-child {
        padding-bottom: 5px;
        display: block;
    }
</style>

<section class="content" id="applicationForm">

    @if (in_array($appInfo->status_id, [5, 6, 17, 22]))
        @include('ProcessPath::remarks-modal')
    @endif


    <div class="col-md-12">
        {{-- Start if this is applicant user and status is 15, 32 (proceed for payment) --}}
        @if ($viewMode == 'on' && in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [15, 32]))
            @include('ProcessPath::government-payment-information')
        @endif
        {{-- End if this is applicant user and status is 15, 32 (proceed for payment) --}}

        {{-- Start Remarks file for conditional approved status --}}
        @if ($viewMode == 'on' && in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [17, 31]))
            @include('ProcessPath::conditional-approved-form')
        @endif
        {{-- End remarks file for conditional approved status --}}

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong>Application for Project Office New</strong></h5>
                </div>
                <div class="pull-right">
                    @if (
                        $viewMode == 'on' &&
                            isset($appInfo) &&
                            $appInfo->status_id == 25 &&
                            isset($appInfo->certificate_link) &&
                            in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info"
                            title="Download Approval Copy" target="_blank" rel="noopener">
                            <i class="fa  fa-file-pdf"></i>
                            Download Approval Copy
                        </a>
                    @endif
                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if (!in_array($appInfo->status_id, [-1, 5, 6]))
                        <a href="/project-office-new/app-pdf/{{ Encryption::encodeId($appInfo->id) }}" target="_blank"
                            class="btn btn-md btn-danger">
                            <i class="fa fa-download"></i>
                            Application Download as PDF
                        </a>
                    @endif

                    @if (in_array($appInfo->status_id, [5, 6, 17, 22]))
                        <a data-toggle="modal" data-target="#remarksModal">
                            {!! Form::button('<i class="fa fa-eye"></i> Reason of ' . $appInfo->status_name . '', [
                                'type' => 'button',
                                'class' => 'btn btn-md btn-danger',
                            ]) !!}
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <ol class="breadcrumb">
                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no }}</li>
                    <li><strong> Date of
                            Submission: </strong>
                        {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    <li><strong>Current Desk
                            :</strong>
                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}
                    </li>
                </ol>

                {{-- Payment information --}}
                @include('ProcessPath::payment-information')

                {{-- Company basic information --}}
                @include('ProcessPath::basic-company-info-view')

                {{-- conditional_approved_file --}}
                @if ($viewMode == 'on' && !empty($appInfo->conditional_approved_file))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Conditionally approve information</legend>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2">Attachment</div>
                                    <div class="col-md-10">
                                        <a target="_blank" rel="noopener" class="documentUrl btn btn-xs btn-primary"
                                            href="{{ URL::to('/uploads/' . $appInfo->conditional_approved_file) }}">
                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-2">Remarks</div>
                                    <div class="col-md-10">
                                        {{ !empty($appInfo->conditional_approved_remarks) ? $appInfo->conditional_approved_remarks : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
                {{-- conditional_approved_file --}}

                @if (
                    (in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505') ||
                        in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404']))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">
                            <span>
                                Approved Permission Period
                            </span>
                        </legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">
                                            Start Date
                                        </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ !empty($appInfo->approved_duration_start_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_start_date)) : '' }}

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">
                                            End date
                                        </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ !empty($appInfo->approved_duration_end_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_end_date)) : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">
                                            Duration
                                        </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ $appInfo->approved_desired_duration ? $appInfo->approved_desired_duration : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">
                                            Payable amount
                                        </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ !empty($appInfo->approved_duration_amount) ? $appInfo->approved_duration_amount : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                @endif

                {{-- meeting_info --}}
                @if (!empty($metingInformation) && $viewMode == 'on')
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Meeting Info</legend>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Meeting No</div>
                                    <div class="col-md-7">
                                        <span>{{ !empty($metingInformation->meting_number) ? $metingInformation->meting_number : '' }}</span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Meeting Date</div>
                                    <div class="col-md-7">
                                        <span>{{ !empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
                {{-- meeting_info --}}

                {{-- Basic Information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>1. Project Information</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4 col-xs-6">
                                <span class="v_label">
                                    Name of the Project
                                </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-8 col-xs-6">
                                {{ !empty($appInfo->project_name) ? $appInfo->project_name : '' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-xs-6">
                                <span class="v_label">
                                    Project Major Activities In Brief
                                </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-8 col-xs-6">
                                {{ !empty($appInfo->project_major_activities) ? $appInfo->project_major_activities : '' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-xs-6">
                                <span class="v_label">
                                    Project Major In Details
                                </span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-8 col-xs-6">
                                {{ !empty($appInfo->project_major_details) ? $appInfo->project_major_details : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Information View -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>2. Information of the company(s) composing JV/ Consortium/ association office</strong>
                    </div>
                    <div class="panel-body">
                        <div class="company-section">
                            @if (count($ponCompanyOfficeList) > 0)
                                @foreach ($ponCompanyOfficeList as $row)
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Company Information</legend>

                                        <!-- BIDA Approval Section -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">The company Office Permission been
                                                            approved by BIDA?</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->company_office_approved) ? ucfirst($row->company_office_approved) : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Online OSS Approval -->
                                        @if (!empty($row->company_office_approved) && $row->company_office_approved == 'yes')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Did you receive your Office
                                                                Permission New / Office Permission Extension online
                                                                OSS?</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ !empty($row->is_approval_online) ? ucfirst($row->is_approval_online) : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Online OSS Tracking Number -->
                                            @if (!empty($row->is_approval_online) && $row->is_approval_online == 'yes')
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-5 col-xs-6">
                                                                <span class="v_label">Approved Office Permission New /
                                                                    Office Permission Extension Tracking No.</span>
                                                                <span class="pull-right">&#58;</span>
                                                            </div>
                                                            <div class="col-md-7 col-xs-6">
                                                                <a href="#" target="_blank" rel="noopener">
                                                                    <span
                                                                        class="label label-success label_tracking_no">{{ empty($row->ref_app_tracking_no) ? '' : $row->ref_app_tracking_no }}</span>
                                                                </a>
                                                                &nbsp;
                                                                &nbsp;{!! !empty($row->ref_app_tracking_no)
                                                                    ? \App\Libraries\CommonFunction::getCertificateByTrackingNo($row->ref_app_tracking_no)
                                                                    : '' !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-5 col-xs-6">
                                                                <span class="v_label">Approved Date</span>
                                                                <span class="pull-right">&#58;</span>
                                                            </div>
                                                            <div class="col-md-7 col-xs-6">
                                                                {{ !empty($row->ref_app_approve_date) ? date('d-M-Y', strtotime($row->ref_app_approve_date)) : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Manual Approval -->
                                            @if (!empty($row->is_approval_online) && $row->is_approval_online == 'no')
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-5 col-xs-6">
                                                                <span class="v_label">Manually approved Office
                                                                    Permission New / Office Permission Extension Memo
                                                                    Number</span>
                                                                <span class="pull-right">&#58;</span>
                                                            </div>
                                                            <div class="col-md-7 col-xs-6">
                                                                {{ !empty($row->manually_approved_op_no) ? $row->manually_approved_op_no : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-5 col-xs-6">
                                                                <span class="v_label">Approval Copy</span>
                                                                <span class="pull-right">&#58;</span>
                                                            </div>
                                                            <div class="col-md-7 col-xs-6">
                                                                @if (!empty($row->approval_copy))
                                                                    <a href="{{ asset($row->approval_copy) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-info">View Document</a>
                                                                @else
                                                                    No document available
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-5 col-xs-6">
                                                                <span class="v_label">Approved Date</span>
                                                                <span class="pull-right">&#58;</span>
                                                            </div>
                                                            <div class="col-md-7 col-xs-6">
                                                                {{ !empty($row->manually_approved_br_date) ? date('d-M-Y', strtotime($row->manually_approved_br_date)) : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        <hr>

                                        <!-- Company Basic Information -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Name of company</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_company_name) ? $row->c_company_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Country of origin</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_origin_country_name) ? $row->c_origin_country_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Type of the organization</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_org_type_name) ? $row->c_org_type_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Flat/ Apartment/ Floor no.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_flat_apart_floor) ? $row->c_flat_apart_floor : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">House/ Plot/ Holding no.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_house_plot_holding) ? $row->c_house_plot_holding : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Post/ Zip code</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_post_zip_code) ? $row->c_post_zip_code : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Street name/ Street no.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_street) ? $row->c_street : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Email</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_email) ? $row->c_email : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">City</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        @if ($row->c_origin_country_id == 18)
                                                            {{ !empty($row->c_district_name) ? $row->c_district_name : '' }}
                                                        @else
                                                            {{ !empty($row->c_city) ? $row->c_city : '' }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Mobile no.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_mobile_no) ? $row->c_mobile_no : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    @if ($row->c_origin_country_id == 18)
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Police Station/ Town</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ !empty($row->c_thana_name) ? $row->c_thana_name : '' }}
                                                        </div>
                                                    @else
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">State/ Province</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ !empty($row->c_state_province) ? $row->c_state_province : '' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Shareholder percentage</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ isset($row->c_shareholder_percentage) ? $row->c_shareholder_percentage : '' }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Major Activities Section -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Major activities in brief</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ !empty($row->c_major_activity_brief) ? $row->c_major_activity_brief : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </fieldset>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Information about the proposed branch/liaison/representative office --}}
                <div id="ep_form" class="panel panel-info">
                    <div class="panel-heading">
                        <strong>
                            Information about the Project Office
                        </strong>
                    </div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                3. Project Office Address (corporate office)
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Division</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_division_name) ? $appInfo->poa_co_division_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">District</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_district_name) ? $appInfo->poa_co_district_name : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Police station</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_thana_name) ? $appInfo->poa_co_thana_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Post office</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_post_office) ? $appInfo->poa_co_post_office : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Post code</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_post_code) ? $appInfo->poa_co_post_code : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">House, Flat/ Apartment, Road</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_address) ? $appInfo->poa_co_address : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Telephone no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_telephone_no) ? $appInfo->poa_co_telephone_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Mobile no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_mobile_no) ? $appInfo->poa_co_mobile_no : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Fax no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_fax_no) ? $appInfo->poa_co_fax_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Email</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->poa_co_email) ? $appInfo->poa_co_email : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                4. Project Office Address (site office)
                            </legend>

                            @if (count($ponSiteOfficeList) > 0)
                                @foreach ($ponSiteOfficeList as $row)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Division</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_division_name) ? $row->poa_so_division_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">District</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_district_name) ? $row->poa_so_district_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Police station</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_thana_name) ? $row->poa_so_thana_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Post office</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_post_office) ? $row->poa_so_post_office : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Post code</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_post_code) ? $row->poa_so_post_code : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">House, Flat/ Apartment, Road</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_address) ? $row->poa_so_address : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Telephone no.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_telephone_no) ? $row->poa_so_telephone_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Mobile no.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_mobile_no) ? $row->poa_so_mobile_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Fax no.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_fax_no) ? $row->poa_so_fax_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Email</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->poa_so_email) ? $row->poa_so_email : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <label class="scheduler-border" style="font-weight: bold;">Site
                                                        Office Incharge Information :</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->site_office_name) ? $row->site_office_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Designation</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->site_office_designation) ? $row->site_office_designation : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Mobile No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->site_office_mobile_no) ? $row->site_office_mobile_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Email</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ !empty($row->site_office_email) ? $row->site_office_email : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Authorize Letter</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    @if (!empty($row->site_office_authorize_letter))
                                                        <a class="btn btn-xs btn-primary" target="_blank"
                                                            rel="noopener"
                                                            href="{{ URL::to($row->site_office_authorize_letter) }}"
                                                            title="{{ $row->site_office_authorize_letter }}">
                                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                            Open File
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                5. The contact Amount of the Project (in US $)
                            </legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">
                                                The contact Amount of the Project (in US $)
                                            </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ isset($appInfo->project_amount) ? $appInfo->project_amount : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                <span>
                                    6. Proposed Project Duration (as per contract)
                                </span>
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">
                                                Start and effective date
                                            </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->period_start_date) ? date('d-M-Y', strtotime($appInfo->period_start_date)) : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">
                                                End date
                                            </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->period_end_date) ? date('d-M-Y', strtotime($appInfo->period_end_date)) : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">
                                                Period of validity
                                            </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ $appInfo->period_validity ? $appInfo->period_validity : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">
                                                Payable amount
                                            </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->duration_amount) ? $appInfo->duration_amount : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                7. Authorized Person of Procurement Entity
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->authorized_name) ? $appInfo->authorized_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Designation</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->authorized_designation) ? $appInfo->authorized_designation : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Organization / Department</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->authorized_org_dep) ? $appInfo->authorized_org_dep : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Address</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->authorized_address) ? $appInfo->authorized_address : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Mobile No.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->authorized_mobile_no) ? $appInfo->authorized_mobile_no : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Email</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->authorized_email) ? $appInfo->authorized_email : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Authorize Letter</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            @if (!empty($appInfo->authorized_letter))
                                                <a class="btn btn-xs btn-primary" target="_blank" rel="noopener"
                                                    href="{{ URL::to($appInfo->authorized_letter) }}"
                                                    title="{{ $appInfo->authorized_letter }}">
                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                8. Ministry/Department/Organization of the project to be implemented
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->ministry_name) ? $appInfo->ministry_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Address</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ !empty($appInfo->ministry_address) ? $appInfo->ministry_address : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Contract Signing Date</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ empty($appInfo->contract_signing_date) ? '' : date('d-M-Y', strtotime($appInfo->contract_signing_date)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Manpower of the organization --}}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                9. Proposed organizational set up of the Project Office with expatriate and local man
                                power
                            </legend>
                            <div class="table-responsive">
                                <table aria-label="Detailed Proposed organization"
                                    class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="alert alert-info text-center" colspan="3">
                                                Local (a)</th>
                                            <th scope="col" class="alert alert-info text-center" colspan="3">
                                                Foreign (b)</th>
                                            <th scope="col" class="alert alert-info text-center" colspan="1">
                                                Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="col" class="alert alert-info text-center">Technical</th>
                                            <th scope="col" class="alert alert-info text-center">General</th>
                                            <th scope="col" class="alert alert-info text-center">Total</th>
                                            <th scope="col" class="alert alert-info text-center">Technical</th>
                                            <th scope="col" class="alert alert-info text-center">General</th>
                                            <th scope="col" class="alert alert-info text-center">Total</th>
                                            <th scope="col" class="alert alert-info text-center"> (a+b)</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ isset($appInfo->local_technical) ? $appInfo->local_technical : '' }}
                                            </td>
                                            <td>
                                                {{ isset($appInfo->local_general) ? $appInfo->local_general : '' }}
                                            </td>
                                            <td>
                                                {{ isset($appInfo->local_total) ? $appInfo->local_total : '' }}
                                            </td>
                                            <td>
                                                {{ isset($appInfo->foreign_technical) ? $appInfo->foreign_technical : '' }}
                                            </td>
                                            <td>
                                                {{ isset($appInfo->foreign_general) ? $appInfo->foreign_general : '' }}
                                            </td>
                                            <td>
                                                {{ isset($appInfo->foreign_total) ? $appInfo->foreign_total : '' }}
                                            </td>
                                            <td>
                                                {{ isset($appInfo->manpower_total) ? $appInfo->manpower_total : '' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                @if (count($ponForeignDetailList) > 0)
                                    <div id="foreign_details_table">
                                        <legend class="scheduler-border"> Foreign Technical & General Details:
                                        </legend>
                                        <table aria-label="Detailed Report Data Table"
                                            class="table table-striped table-bordered" cellspacing="0"
                                            width="100%">
                                            <thead class="alert alert-info">
                                                <tr>
                                                    <th scope="col" class="alert alert-info text-center">Number of
                                                        Foreign</th>
                                                    <th scope="col" class="alert alert-info text-center">
                                                        Designation</th>
                                                    <th scope="col" class="alert alert-info text-center">Duration
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="foreign_details_table_body">
                                                @foreach ($ponForeignDetailList as $row)
                                                    <tr>
                                                        <td>
                                                            {{ isset($row->foreign_number) ? $row->foreign_number : '' }}
                                                        </td>
                                                        <td>
                                                            {{ !empty($row->foreign_designation) ? $row->foreign_designation : '' }}
                                                        </td>
                                                        <td>
                                                            {{ isset($row->foreign_duration) ? $row->foreign_duration : '' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </fieldset>
                    </div>
                </div>

                {{-- Necessary documents to be attached --}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Necessary documents to be attached here (Only PDF file)</strong>
                    </div>
                    <div class="panel-body">
                        <table aria-label="Detailed Necessary document"
                            class="table table-striped table-bordered table-hover ">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th colspan="6">Required attachments</th>
                                    <th colspan="2">
                                        <a class="btn btn-xs btn-primary" target="_blank" rel="noopener"
                                            href="{{ url('process/open-attachment/' . Encryption::encodeId($appInfo->process_type_id) . '/' . Encryption::encodeId($appInfo->id)) }}"><i
                                                class="fa fa-link" aria-hidden="true"></i> Open all</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @if (count($document) > 0)
                                    @foreach ($document as $row)
                                        <tr>
                                            <td>
                                                <div align="left">{!! $i !!}<?php echo $row->doc_priority == '1' ? "<span class='required-star'></span>" : ''; ?></div>
                                            </td>
                                            <td colspan="6">{!! $row->doc_name !!}</td>
                                            <td colspan="2">
                                                @if (!empty($row->doc_file_path))
                                                    <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                        href="{{ URL::to('/uploads/' . (!empty($row->doc_file_path) ? $row->doc_file_path : '')) }}"
                                                        title="{{ $row->doc_name }}">
                                                        <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                @else
                                                    No file found
                                                @endif
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="9"> No required documents!</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Information about Declaration and undertaking --}}
                <div id="declaration_undertaking" class="mb0 panel panel-info">
                    <div class="panel-heading"><strong>Declaration and undertaking</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ol type="a">
                                    <li>I do hereby declare that the information given above is true to the best of my
                                        knowledge and I shall be liable for any false information/ statement given
                                    </li>
                                    <li>I do hereby undertake full responsibility of the expatriate for whom visa
                                        recommendation is sought during their stay in Bangladesh
                                    </li>
                                </ol>
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Authorized person of the organization</legend>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Full Name</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ !empty($appInfo->auth_full_name) ? $appInfo->auth_full_name : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Designation</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ !empty($appInfo->auth_designation) ? $appInfo->auth_designation : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Mobile No.</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ !empty($appInfo->auth_mobile_no) ? $appInfo->auth_mobile_no : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Email address</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ !empty($appInfo->auth_email) ? $appInfo->auth_email : '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Picture</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            <img class="img-thumbnail"
                                                                src="{{ !empty($appInfo->auth_image) ? url('users/upload/' . $appInfo->auth_image) : url('assets/images/photo_default.png') }}"
                                                                alt="User Photo" width="120px">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">Date</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7 col-xs-6">
                                                            {{ empty($appInfo->created_at) ? '' : date('d-M-Y', strtotime($appInfo->created_at)) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div>
                                    <i class="fa fa-check-square"></i>
                                    I do here by declare that the information given above is true to the best of my
                                    knowledge and I shall be liable for any false information/ statement is given.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>

</html>
