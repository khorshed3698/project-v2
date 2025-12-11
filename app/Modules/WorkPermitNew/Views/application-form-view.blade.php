<?php
$accessMode = ACL::getAccsessRight('WorkPermitNew');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }
    .row > .col-md-5, .row > .col-md-7, .row > .col-md-3, .row > .col-md-9, .row> .col-md-12>strong:first-child {
        padding-bottom: 5px;
        display: block;
    }
    .img-thumbnail {
        height: 120px;
        max-width: 120px;
    }
    legend.scheduler-border {
        font-weight: normal !important;
    }
    .table {
        margin: 0;
    }

    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 5px;
    }

    .mb5 {
        margin-bottom: 5px;
    }
    .mb0 {
        margin-bottom: 0;
    }
    #investorPhotoViewerDiv img{
        height: 120px;
        width: 120px;
    }
</style>
<section class="content" id="applicationForm">

    @if(in_array($appInfo->status_id,[5,6,17,22]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        {{-- Start if this is applicant user and status is 15, 32 (proceed for payment) --}}
        @if($viewMode == 'on' && in_array(Auth::user()->user_type,['5x505']) && in_array($appInfo->status_id, [15, 32]))
            @include('ProcessPath::government-payment-information')
        @endif
        {{-- End if this is applicant user and status is 15, 32 (proceed for payment) --}}

        {{--Start Remarks file for conditional approved status--}}
        @if($viewMode == 'on' && in_array(Auth::user()->user_type, ['5x505']) && in_array($appInfo->status_id, [17,31]))
            @include('ProcessPath::conditional-approved-form')
        @endif
        {{--End remarks file for conditional approved status--}}


        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">Application for Work Permit New</strong>
                </div>
                <div class="pull-right">
                    @if ($viewMode == 'on' && isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-sm btn-info"
                           title="Download Approval Copy" target="_blank" rel="noopener">
                            <i class="fa  fa-file-pdf"></i>
                            Download Approval Copy
                        </a>
                    @endif

                    {{--                    <a class="btn btn-sm btn-primary" data-toggle="collapse" href="#basicCompanyInfo" role="button"--}}
                    {{--                       aria-expanded="false" aria-controls="collapseExample">--}}
                    {{--                        <i class="fas fa-info-circle"></i>--}}
                    {{--                        Basic Company Info--}}
                    {{--                    </a>--}}

                    <a class="btn btn-sm btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(!in_array($appInfo->status_id,[-1,5,6]))
                        <a href="/work-permit-new/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank" class="btn btn-danger btn-sm">
                            <i class="fa fa-download"></i>
                            Application Download as PDF
                        </a>
                    @endif

                    @if(in_array($appInfo->status_id,[5,6,17,22]))
                        <a data-toggle="modal" data-target="#remarksModal">
                            {!! Form::button('<i class="fa fa-eye"></i> Reason of '.$appInfo->status_name.'', array('type' => 'button', 'class' => 'btn btn-sm btn-danger')) !!}
                        </a>
                    @endif
                </div>

            </div>
            <div class="panel-body">
                <ol class="breadcrumb">
                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    {{-- <li><strong>Current Desk :</strong> {{ $appInfo->desk_name }}</li> --}}
                    <li><strong>Current Desk :</strong> {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }} </li>
                </ol>

                {{--Payment information--}}
                @include('ProcessPath::payment-information')

                @if(!empty($appInfo->divisional_office_name))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Application Approval:</legend>
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Office name</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ $appInfo->divisional_office_name }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <span class="v_label">Office address</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-9 col-xs-6">
                                {{ $appInfo->divisional_office_address }}
                            </div>
                        </div>
                    </fieldset>
                @endif

                {{--Company basic information--}}
                @include('ProcessPath::basic-company-info-view')

                @if($viewMode == 'on' && !empty($appInfo->conditional_approved_file))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Conditionally approve information</legend>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2 v_label">Attachment</div>
                                    <div class="col-md-10">
                                        <a target="_blank" rel="noopener" class="documentUrl btn btn-xs btn-primary" href="{{ URL::to('/uploads/'. $appInfo->conditional_approved_file) }}">
                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-2 v_label">Remarks</div>
                                    <div class="col-md-10">
                                        {{ (!empty($appInfo->conditional_approved_remarks) ? $appInfo->conditional_approved_remarks : '') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif

                @if((in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505' && $viewMode == 'on') || (in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404']) && $viewMode == 'on'))
                    @if($appInfo->basic_salary > 0)
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Basic Salary</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5"><span class="v_label">Minimum range of basic salary</span></div>
                                        <div class="col-md-7">
                                            {{ $appInfo->basic_salary }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    @endif

                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Approved Permission Period</legend>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Start Date<span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        {{ (!empty($appInfo->approved_duration_start_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_start_date)) : '') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">End Date<span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        {{ (!empty($appInfo->approved_duration_end_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_end_date)) : '') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Duration<span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        {{ $appInfo->approved_desired_duration }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Payable amount (BDT)<span class="pull-right">&#58;</span></div>
                                    <div class="col-md-7">
                                        {{ $appInfo->approved_duration_amount }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif

                @if(!empty($metingInformation) && $viewMode == 'on')
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Meeting Info</legend>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Meeting No</div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 v_label">Meeting Date</div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif

                {{-- Basic Information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic Information</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Did you receive Visa Recommendation through online OSS?</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                <span> {{ (!empty($appInfo->last_vr)) ? ucfirst($appInfo->last_vr) : ''  }}</span>
                            </div>
                        </div>
                        <div class="row">
                            @if($appInfo->last_vr == 'yes')
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Approved Visa Recommendation No.</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    <a href="{{$data['ref_app_url']}}" target="_blank" rel="noopener">
                                        <span class="label label-success label_tracking_no">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                    </a>
                                    &nbsp; &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                </div>
                            @else
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Manually approved work permit No.</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    <span> {{ (!empty($appInfo->manually_approved_wp_no)) ? $appInfo->manually_approved_wp_no : ''  }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Date of arrival in Bangladesh</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <span>{{ (!empty($appInfo->date_of_arrival) ? date('d-M-Y', strtotime($appInfo->date_of_arrival)) : '') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Type of visa</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    <span> {{ (!empty($appInfo->visa_type_name)) ? $appInfo->visa_type_name :''  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Show only commercial department--}}
                        @if($appInfo->department_id == 1 || $appInfo->department_id == '1')
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Expiry Date of Office Permission</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    <span>{{ (!empty($appInfo->expiry_date_of_op) ? date('d-M-Y', strtotime($appInfo->expiry_date_of_op)) : '') }} </span>
                                </div>
                            </div>
                        @endif

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Desired duration for work permit</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Start Date</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span>{{ (!empty($appInfo->duration_start_date) ? date('d-M-Y', strtotime($appInfo->duration_start_date)) : '') }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">End Date</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span>{{ (!empty($appInfo->duration_end_date) ? date('d-M-Y', strtotime($appInfo->duration_end_date)) : '') }} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Desired Duration</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->desired_duration)) ? $appInfo->desired_duration :''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Payable amount (BDT)</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->duration_amount)) ? $appInfo->duration_amount :''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                {{-- Information of Expatriate/ Investor/ Employee --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Information of Expatriate/ Investor/ Employee</strong></div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">General Information:</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6">
                                                <span class="v_label">Full Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-9 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_name)) ? $appInfo->emp_name:''  }}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6">
                                                <span class="v_label">Position/ Designation</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-9 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_designation)) ? $appInfo->emp_designation:''  }}</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6">
                                                <span class="v_label">Brief job description</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-9 col-xs-6">
                                                <span> {{ (!empty($appInfo->brief_job_description)) ? $appInfo->brief_job_description:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div id="investorPhotoViewerDiv">
                                            <?php
                                            if (!empty($appInfo->investor_photo)) {
                                                $userPic = file_exists('users/upload/'.$appInfo->investor_photo) ? url('users/upload/'.$appInfo->investor_photo) : url('uploads/'.$appInfo->investor_photo);
                                            } else {
                                                $userPic = url('assets/images/photo_default.png');
                                            }
                                            ?>
                                            <img class="img-thumbnail" height="auto" src="{{ $userPic }}" alt="Investor Photo" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Passport Information:</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Passport No.</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_passport_no))?$appInfo->emp_passport_no:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Personal No.</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_personal_no))?$appInfo->emp_personal_no:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Surname</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_surname))?$appInfo->emp_surname:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Issuing authority</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->place_of_issue))?$appInfo->place_of_issue:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Given Name</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_given_name))?$appInfo->emp_given_name:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Nationality</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_nationality_name))? $appInfo->emp_nationality_name :''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Date of Birth</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span>{{ (!empty($appInfo->emp_date_of_birth) ? date('d-M-Y', strtotime($appInfo->emp_date_of_birth)) : '') }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Place of Birth</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span> {{ (!empty($appInfo->emp_place_of_birth))?$appInfo->emp_place_of_birth:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Date of issue</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span>{{ (!empty($appInfo->pass_issue_date) ? date('d-M-Y', strtotime($appInfo->pass_issue_date)) : '') }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-6">
                                                <span class="v_label">Date of expiry</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-6">
                                                <span>{{ (!empty($appInfo->pass_expiry_date) ? date('d-M-Y', strtotime($appInfo->pass_expiry_date)) : '') }} </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Compensation and Benefit:</legend>
                            <div class="form-group">
                                <table class="table table-striped table-bordered table-responsive" cellspacing="10" width="100%" aria-label="Detailed Compensation and Benefit Data Table">
                                    <tr>
                                        <th class="text-center" style="vertical-align: middle; padding: 5px;">Salary structure</th>
                                        <th class="text-center" style="padding: 5px;">Payment</th>
                                        <th class="text-center" style="padding: 5px;">Amount</th>
                                        <th class="text-center" style="padding: 5px;">Currency</th>
                                    </tr>

                                    <tr>
                                        <td style="padding: 5px;">a. Basic salary / Honorarium </td>
                                        <td style="padding: 5px;">

                                            <span> {{ (!empty($appInfo->basic_payment_type_name))? $appInfo->basic_payment_type_name :''  }}</span>

                                        </td>
                                        <td style="padding: 5px;">

                                            <span> {{ (!empty($appInfo->basic_local_amount))? $appInfo->basic_local_amount:''  }}</span>

                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->basic_currency_code))? $appInfo->basic_currency_code :''  }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;">b. Overseas allowance </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->overseas_payment_type_name))? $appInfo->overseas_payment_type_name:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->overseas_local_amount))? $appInfo->overseas_local_amount:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->overseas_currency_code))? $appInfo->overseas_currency_code:''  }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;">c. House rent</td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->house_payment_type_name))? $appInfo->house_payment_type_name:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->house_local_amount))? $appInfo->house_local_amount:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->house_currency_code))? $appInfo->house_currency_code:''  }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;">d. Conveyance</td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->conveyance_payment_type_name))? $appInfo->conveyance_payment_type_name : ''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->conveyance_local_amount))? $appInfo->conveyance_local_amount:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->conveyance_currency_code))? $appInfo->conveyance_currency_code :''  }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;">e. Medical allowance </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->medical_payment_type_name))? $appInfo->medical_payment_type_name:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->medical_local_amount))? $appInfo->medical_local_amount:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->medical_currency_code))? $appInfo->medical_currency_code :''  }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;">f. Entertainment allowance </td>
                                        <td style="padding: 5px;">

                                            <span> {{ (!empty($appInfo->ent_payment_type_name))? $appInfo->ent_payment_type_name :''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->ent_local_amount))? $appInfo->ent_local_amount:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->ent_currency_code))? $appInfo->ent_currency_code:''  }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;">g. Annual Bonus </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->bonus_payment_type_name))? $appInfo->bonus_payment_type_name :''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->bonus_local_amount))? $appInfo->bonus_local_amount:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->bonus_currency_code))? $appInfo->bonus_currency_code :''  }}</span>
                                        </td>
                                    <tr>
                                        <td style="padding: 5px;">h. Other fringe benefits (if any)</td>
                                        <td colspan="5" style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->other_benefits))? $appInfo->other_benefits:''  }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </fieldset>
                        @if($appInfo->business_category == 1)
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Contact address of the expatriate in Bangladesh:</legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Division</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->ex_office_division_name))? $appInfo->ex_office_division_name:''  }}</span>
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
                                                    <span> {{ (!empty($appInfo->ex_office_district_name))? $appInfo->ex_office_district_name :''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Police Station</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->ex_office_thana_name))? $appInfo->ex_office_thana_name :''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Post Office</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->ex_office_post_office))? $appInfo->ex_office_post_office :''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Post Code</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->ex_office_post_code))?$appInfo->ex_office_post_code:''  }}</span>
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
                                                    <span> {{ (!empty($appInfo->ex_office_address))? $appInfo->ex_office_address:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Telephone No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->ex_office_telephone_no))? $appInfo->ex_office_telephone_no:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Mobile No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->ex_office_mobile_no))? $appInfo->ex_office_mobile_no:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Fax No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->ex_office_fax_no))? $appInfo->ex_office_fax_no:''  }}</span>
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
                                                    <span> {{ (!empty($appInfo->ex_office_email))? $appInfo->ex_office_email:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Others Particular of Organization:</legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Nature of Business</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->nature_of_business))?$appInfo->nature_of_business:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Remittance received during the last twelve months (USD)</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->received_remittance))?$appInfo->received_remittance:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12"><strong class="text-success">Capital Structure:</strong></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">(i) Authorized Capital (USD)</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->auth_capital))?$appInfo->auth_capital:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">(ii) Paid-up Capital (USD)</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <span> {{ (!empty($appInfo->paid_capital))?$appInfo->paid_capital:''  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                        @endif
                    </div>
                </div>

                {{-- Previous Travel history of the expatriate to Bangladesh --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Previous Travel history of the expatriate to Bangladesh</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Have you visited to Bangladesh previously?</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                <span> {{ (!empty($appInfo->travel_history)) ? ucfirst($appInfo->travel_history) : ''  }}</span>
                            </div>
                        </div>
                        @if($appInfo->travel_history == 'yes')
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">In which period:</legend>
                                <div class="form-group">
                                    <table class="table table-bordered" aria-label="Detailed Previous Travel history Data Table">
                                        <thead>
                                        <tr>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Type of visa availed</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($previous_travel_history) > 0)
                                            @foreach($previous_travel_history as $record)
                                                <tr>
                                                    <td><span>{{ (!empty($record->th_emp_duration_from) ? date('d-M-Y', strtotime($record->th_emp_duration_from)) : '') }} </span></td>
                                                    <td><span>{{ (!empty($record->th_emp_duration_to) ? date('d-M-Y', strtotime($record->th_emp_duration_to)) : '') }} </span></td>
                                                    <td><span> {{ (!empty($record->type)) ? $record->type : ''  }}</span>
                                                        @if (!empty($record->th_visa_type_others))
                                                            <br/>
                                                            {{ $record->th_visa_type_others }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3"> No Visa record</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>

                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Have you visited to Bangladesh with Employment Visa?</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    <span> {{ (!empty($appInfo->th_visit_with_emp_visa)) ? ucfirst($appInfo->th_visit_with_emp_visa) : ''  }}</span>
                                </div>
                            </div>
                            @if($appInfo->th_visit_with_emp_visa == 'yes')
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Have you received work permit from Bangladesh?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <span> {{ (!empty($appInfo->th_emp_work_permit)) ? ucfirst($appInfo->th_emp_work_permit) : ''  }}</span>
                                    </div>
                                </div>
                            @endif

                            @if($appInfo->th_emp_work_permit == 'yes')
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Previous work permit information in Bangladesh:</legend>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">TIN Number</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_emp_tin_no)) ? $appInfo->th_emp_tin_no : ''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Last Work Permit Ref. No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_emp_wp_no)) ? $appInfo->th_emp_wp_no : ''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Name of the employer organization</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_emp_org_name)) ? $appInfo->th_emp_org_name : ''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Address of the organization</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_emp_org_address)) ? $appInfo->th_emp_org_address : ''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">City/ District</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_org_district_name)) ? $appInfo->th_org_district_name : ''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Thana/ Upazilla</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_org_thana_name))? $appInfo->th_org_thana_name:''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Post Office</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_org_post_office))?$appInfo->th_org_post_office:''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Post Code </span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_org_post_code))?$appInfo->th_org_post_code:''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Contact Number</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        <span> {{ (!empty($appInfo->th_org_telephone_no))?$appInfo->th_org_telephone_no:''  }}</span>
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
                                                        <span> {{ (!empty($appInfo->th_org_email))?$appInfo->th_org_email:''  }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Previous travel history attachment upload section-->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered" aria-label="Detailed Previous travel history attachment Data Table">
                                                    <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th>Required attachments</th>
                                                        <th> @if(count($travel_history_document) > 0)
                                                                <a class="btn btn-xs btn-primary" target="_blank" rel="noopener"
                                                                   href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id).'/'.Encryption::encodeId('type2')) }}"><i
                                                                            class="fa fa-file-pdf"
                                                                            aria-hidden="true"></i>
                                                                    Open all</a>
                                                            @else
                                                                Attached PDF file
                                                            @endif
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i = 1; ?>
                                                    @if(count($travel_history_document) > 0)
                                                        @foreach($travel_history_document as $row)
                                                            <tr>
                                                                <td>
                                                                    <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                                </td>
                                                                <td>
                                                                    {!!  $row->doc_name !!}
                                                                </td>
                                                                <td>
                                                                    @if(!empty($row->doc_file_path))
                                                                        <a target="_blank" rel="noopener"
                                                                           class="btn btn-xs btn-primary"
                                                                           href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                                           title="{{$row->doc_name}}">
                                                                            <i class="fa fa-file-pdf"
                                                                               aria-hidden="true"></i>
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
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Copy of the first work permit</td>
                                                            <td>
                                                                @if(!empty($appInfo->th_first_work_permit))
                                                                    <div class="save_file">
                                                                        <a target="_blank" rel="noopener" title="Work permit"
                                                                           href="{{URL::to('/uploads/'. $appInfo->th_first_work_permit)}}">
                                                                            <img width="10" height="10"
                                                                                 src="{{ url('assets/images/pdf.png') }}"
                                                                                 alt="pdf"/>
                                                                            Open File
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    No file found
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Copy of the Resignation letter</td>
                                                            <td>
                                                                @if(!empty($appInfo->th_resignation_letter))
                                                                    <div class="save_file">
                                                                        <a target="_blank" rel="noopener" title="Resignation letter"
                                                                           href="{{URL::to('/uploads/'. $appInfo->th_resignation_letter)}}">
                                                                            <img width="10" height="10"
                                                                                 src="{{ url('assets/images/pdf.png') }}"
                                                                                 alt="pdf"/>
                                                                            Open File
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    No file found
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Copy of the release order/termination letter/No
                                                                objection certificate
                                                            </td>
                                                            <td>
                                                                @if(!empty($appInfo->th_release_order))
                                                                    <div class="save_file">
                                                                        <a target="_blank" rel="noopener" title="Release order"
                                                                           href="{{URL::to('/uploads/'. $appInfo->th_release_order)}}">
                                                                            <img width="10" height="10"
                                                                                 src="{{ url('assets/images/pdf.png') }}"
                                                                                 alt="pdf"/>
                                                                            Open File
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    No file found
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>Copy of the last extension (if applicable) <span
                                                                        class="required-star"></span></td>
                                                            <td>
                                                                @if(!empty($appInfo->th_last_extension))
                                                                    <div class="save_file">
                                                                        <a target="_blank" rel="noopener" title="Last extension"
                                                                           href="{{URL::to('/uploads/'. $appInfo->th_last_extension)}}">
                                                                            <img width="10" height="10"
                                                                                 src="{{ url('assets/images/pdf.png') }}"
                                                                                 alt="pdf"/>
                                                                            Open File
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    No file found
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Copy of the cancellation of the last work permit <span
                                                                        class="required-star"></span></td>
                                                            <td>
                                                                @if(!empty($appInfo->th_last_work_permit))
                                                                    <div class="save_file">
                                                                        <a target="_blank" rel="noopener" title="Last Work permit"
                                                                           href="{{URL::to('/uploads/'. $appInfo->th_last_work_permit)}}">
                                                                            <img width="10" height="10"
                                                                                 src="{{ url('assets/images/pdf.png') }}"
                                                                                 alt="pdf"/>
                                                                            Open File
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    No file found
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>6</td>
                                                            <td> Copy of the income tax certificate for the last
                                                                assessment year of the previous stay <span
                                                                        class="required-star"></span></td>
                                                            <td>
                                                                @if(!empty($appInfo->th_income_tax))
                                                                    <div class="save_file">
                                                                        <a target="_blank" rel="noopener" title="Income tax"
                                                                           href="{{URL::to('/uploads/'. $appInfo->th_income_tax)}}">
                                                                            <img width="10" height="10"
                                                                                 src="{{ url('assets/images/pdf.png') }}"
                                                                                 alt="pdf"/>
                                                                            Open File
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    No file found
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endif
                        @endif
                    </div>
                </div>

                @if($appInfo->business_category == 1)
                    {{-- Manpower of the organization --}}
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong>Manpower of the organization</strong></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" aria-label="Detailed Manpower of the organization Data Table" width="100%">
                                    <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true"></th>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="padding: 5px;" colspan="9">Manpower of the organization</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="padding: 5px;" colspan="3">Local (a)</td>
                                            <td class="text-center" style="padding: 5px;" colspan="3">Foreign (b)</td>
                                            <td class="text-center" style="padding: 5px;">Grand Total</td>
                                            <td class="text-center" style="padding: 5px;" colspan="2">Ratio</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-center" style="padding: 5px;">Executive</td>
                                        <td class="text-center" style="padding: 5px;">Supporting Staff</td>
                                        <td class="text-center" style="padding: 5px;">Total</td>
                                        <td class="text-center" style="padding: 5px;">Executive</td>
                                        <td class="text-center" style="padding: 5px;">Supporting Staff</td>
                                        <td class="text-center" style="padding: 5px;">Total</td>
                                        <td class="text-center" style="padding: 5px;">(a+b)</td>
                                        <td class="text-center" style="padding: 5px;">Local</td>
                                        <td class="text-center" style="padding: 5px;">Foreign</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->local_executive))? $appInfo->local_executive:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->local_stuff))? $appInfo->local_stuff:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->local_total))? $appInfo->local_total:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->foreign_executive))? $appInfo->foreign_executive:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->foreign_stuff))? $appInfo->foreign_stuff:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->foreign_total))? $appInfo->foreign_total:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->manpower_total))? $appInfo->manpower_total:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->manpower_local_ratio))? $appInfo->manpower_local_ratio:''  }}</span>
                                        </td>
                                        <td style="padding: 5px;">
                                            <span> {{ (!empty($appInfo->manpower_foreign_ratio))? $appInfo->manpower_foreign_ratio:''  }}</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{--attachment--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Necessary documents to be attached here (Only PDF file)</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered table-hover" aria-label="Detailed Required attachments Table">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th colspan="6">Required attachments</th>
                                <th colspan="2">
                                    <a class="btn btn-xs btn-primary" target="_blank" rel="noopener" href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id).'/'.Encryption::encodeId('master')) }}"><i class="fa fa-link" aria-hidden="true"></i> Open all</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @if(count($document) > 0)
                                @foreach($document as $row)
                                    <tr>
                                        <td>
                                            <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                        </td>
                                        <td colspan="6">{!!  $row->doc_name !!}</td>
                                        <td colspan="2">
                                            @if(!empty($row->doc_file_path))
                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                   title="{{$row->doc_name}}">
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


                {{--Declaration and undertaking--}}
                <div id="declaration_undertaking" class="mb0 panel panel-info">
                    <div class="panel-heading"><strong>Declaration and undertaking</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ol type="a">
                                    <li>I do hereby declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement given</li>
                                    <li>I do hereby undertake full responsibility of the expatriate for whom visa recommendation is sought during their stay in Bangladesh</li>
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
                                                            {{ (!empty($appInfo->auth_full_name)) ? $appInfo->auth_full_name : '' }}
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
                                                            {{ (!empty($appInfo->auth_designation)) ? $appInfo->auth_designation : '' }}
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
                                                            {{ (!empty($appInfo->auth_mobile_no)) ? $appInfo->auth_mobile_no : '' }}
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
                                                            {{ (!empty($appInfo->auth_email)) ? $appInfo->auth_email : '' }}
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
                                                                 src="{{ (!empty($appInfo->auth_image) ? url('users/upload/'.$appInfo->auth_image) : url('assets/images/photo_default.png')) }}"
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
                                                            {{ (!empty($appInfo->created_at) ? date('d-M-Y', strtotime($appInfo->created_at)) : '') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div>
                                    <img style="width: 10px; height: auto;" src="{{ asset('assets/images/checked.png') }}" alt="checked_icon"/>
                                    I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>