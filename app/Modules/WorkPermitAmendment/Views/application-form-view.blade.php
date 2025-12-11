<?php
$accessMode = ACL::getAccsessRight('WorkPermitAmendment');
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
        /*height: 180px;*/
        /*max-width: 180px;*/
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

    .bg-green{
        background-color: rgba(103, 219, 56, 1);
    }
    .bg-yellow{
        background-color: rgba(246, 209, 15, 1);
    }
    .light-green{
        background-color: rgba(223, 240, 216, 1);
    }
    .light-yellow{
        background-color: rgba(252, 248, 227, 1);
    }
</style>
<section class="content" id="applicationForm">

    @if(in_array($appInfo->status_id,[5,6,17,22]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        {{-- Start if this is applicant user and status is 15, 32 (proceed for payment) --}}
        @if(Auth::user()->user_type == '5x505' && in_array($appInfo->status_id, [15, 32]))
            @include('ProcessPath::government-payment-information')
        @endif
        {{-- End if this is applicant user and status is 15, 32 (proceed for payment) --}}

        {{--Start Remarks file for conditional approved status--}}
        @if(Auth::user()->user_type == '5x505' && in_array($appInfo->status_id, [17,31]))
            @include('ProcessPath::conditional-approved-form')
        @endif
        {{--End remarks file for conditional approved status--}}

        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;"> Application for Work Permit Amendment</strong>
                </div>
                <div class="pull-right" data-html2canvas-ignore="true">
                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-sm btn-info" title="Download Approval Copy" target="_blank" rel="noopener">
                            <i class="fa  fa-file-pdf"></i>
                            Download Approval Copy
                        </a>
                    @endif

                    {{--                    <a class="btn btn-md btn-primary" data-toggle="collapse" href="#basicCompanyInfo" role="button"--}}
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
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm" title="Application Download as PDF" id="html2pdf">
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

                @if(!empty($appInfo->conditional_approved_file))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Conditionally approve information</legend>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2"><span class="v_label">Attachment</span></div>
                                    <div class="col-md-10">
                                        <a target="_blank" rel="noopener" class="documentUrl btn btn-xs btn-primary" href="{{ URL::to('/uploads/'. $appInfo->conditional_approved_file) }}">
                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-2"><span class="v_label">Remarks</span></div>
                                    <div class="col-md-10">
                                        {{ (!empty($appInfo->conditional_approved_remarks) ? $appInfo->conditional_approved_remarks : '') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif

                @if(((in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505') || (in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404']))))
                    @if(($appInfo->n_duration_start_date != '' and $appInfo->n_duration_start_date != '') ||
                        ($appInfo->n_duration_end_date != '' and $appInfo->n_duration_end_date != '') ||
                        ($appInfo->n_desired_duration != '' and $appInfo->n_desired_duration != null))

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Approved Permission Period</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5"><span class="v_label">Start Date<span class="pull-right">&#58;</span></span></div>
                                        <div class="col-md-7">
                                            {{ (!empty($appInfo->approved_duration_start_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_start_date)) : '') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-5"><span class="v_label">End Date<span class="pull-right">&#58;</span></span></div>
                                        <div class="col-md-7">
                                            {{ (!empty($appInfo->approved_duration_end_date) ? date('d-M-Y', strtotime($appInfo->approved_duration_end_date)) : '') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5"><span class="v_label">Duration<span class="pull-right">&#58;</span></span></div>
                                        <div class="col-md-7">
                                            {{ $appInfo->approved_desired_duration }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-5"><span class="v_label">Payable amount<span class="pull-right">&#58;</span></span></div>
                                        <div class="col-md-7">
                                            {{ $appInfo->approved_duration_amount }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    @endif
                @endif

                @if(!empty($metingInformation))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Meeting Info</legend>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5"><span class="v_label">Meeting No</span></div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5"><span class="v_label">Meeting Date</span></div>
                                    <div class="col-md-7">
                                        <span>{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif

                {{--(Start) Basic Company Information--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Basic Company Information (Non editable info. pulled from the basic information provided at the first
                            time by your company)</strong>
                    </div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border" style="margin: 5px !important;">
                            <legend class="scheduler-border">Company Information</legend>
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Department</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($basicInfo->department)) ? $basicInfo->department : '' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Name of Organization in English (Proposed)</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($basicInfo->company_name)) ? $basicInfo->company_name : '' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Name of Organization in Bangla (Proposed)</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($basicInfo->company_name_bn)) ? $basicInfo->company_name_bn : '' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Desired Service from BIDA</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($basicInfo->service_name)) ? $basicInfo->service_name : '' }}
                                </div>
                            </div>

                            @if($basicInfo->service_type == 5)
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Commercial office type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($basicInfo->reg_commercial_office_name)) ? $basicInfo->reg_commercial_office_name : '' }}
                                    </div>
                                </div>
                            @endif

                            @if($basicInfo->business_category === 1)
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Ownership status</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($basicInfo->ea_ownership_status)) ? $basicInfo->ea_ownership_status : '' }}
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Type of the organization</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($basicInfo->ea_organization_type)) ? $basicInfo->ea_organization_type : '' }}
                                    @if($basicInfo->ea_organization_type_id ==14 && $basicInfo->business_category == 2)
                                        ({{ (!empty($basicInfo->organization_type_other)) ? $basicInfo->organization_type_other : '' }})
                                    @endif

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Major activities in brief</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($basicInfo->major_activities)) ? $basicInfo->major_activities : '' }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Organization type</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    @if($basicInfo->business_category === 2)
                                        Government
                                    @else
                                        Private
                                    @endif

                                </div>
                            </div>
                        </fieldset>

                        <div class="text-center panel-title">
                            <a data-toggle="collapse" href="#basicCompanyInfo" role="button" id="basicCompanyInfo_btn"
                               aria-expanded="false" aria-controls="collapseExample" style="font-size: 16px; font-weight: 600; color: #337ab7">Click
                                here to see details of the company information
                            </a>
                        </div>
                        <div class="text-center">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </div>

                        <div id="basicCompanyInfo" class="collapse">
                            {{-- Start business category --}}
                            @if($basicInfo->business_category == 2)
                                {{--Information of Responsible Person--}}
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Information of Responsible Person</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Country</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_country)) ? $basicInfo->ceo_country : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Full Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_full_name)) ? $basicInfo->ceo_full_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if($basicInfo->ceo_country_id == 18)
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">NID No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_nid)) ? $basicInfo->ceo_nid : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Passport No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_passport_no)) ? $basicInfo->ceo_passport_no : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Designation</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_designation)) ? $basicInfo->ceo_designation : '' }}
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
                                                    {{ (!empty($basicInfo->ceo_mobile_no)) ? $basicInfo->ceo_mobile_no : '' }}
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
                                                    {{ (!empty($basicInfo->ceo_email)) ? $basicInfo->ceo_email : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Gender</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_gender)) ? $basicInfo->ceo_gender : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Authorization Letter</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    <a target="_blank" rel="noopener" class="btn btn-xs btn-primary" title="Authorization Letter"
                                                       href="{{ URL::to('users/upload/'.$basicInfo->ceo_auth_letter) }}">
                                                        <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                        Authorization Letter
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @else
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Information of Principal Promoter/ Chairman/ Managing Director/ State CEO</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Country</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_country)) ? $basicInfo->ceo_country : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Date of Birth</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_dob) ? date('d-M-Y', strtotime($basicInfo->ceo_dob)) : '') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if($basicInfo->ceo_country_id == 18)
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">NID No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_nid)) ? $basicInfo->ceo_nid : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Passport No.</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_passport_no)) ? $basicInfo->ceo_passport_no : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Designation</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_designation)) ? $basicInfo->ceo_designation : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Full Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_full_name)) ? $basicInfo->ceo_full_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($basicInfo->ceo_country_id == 18)
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">District/ City/ State</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_district_name)) ? $basicInfo->ceo_district_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">District/ City/ State</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_city)) ? $basicInfo->ceo_city : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        @if($basicInfo->ceo_country_id == 18)
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">Police Station/ Town</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_thana_name)) ? $basicInfo->ceo_thana_name : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5 col-xs-6">
                                                        <span class="v_label">State/ Province</span>
                                                        <span class="pull-right">&#58;</span>
                                                    </div>
                                                    <div class="col-md-7 col-xs-6">
                                                        {{ (!empty($basicInfo->ceo_state)) ? $basicInfo->ceo_state : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Post/ Zip Code</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_post_code)) ? $basicInfo->ceo_post_code : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">House, Flat/ Apartment, Road</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_address)) ? $basicInfo->ceo_address : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Telephone No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_telephone_no)) ? $basicInfo->ceo_telephone_no : '' }}
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
                                                    {{ (!empty($basicInfo->ceo_mobile_no)) ? $basicInfo->ceo_mobile_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Father's Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_father_name)) ? $basicInfo->ceo_father_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Email</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_email)) ? $basicInfo->ceo_email : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Mother's Name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_mother_name)) ? $basicInfo->ceo_mother_name : '' }}
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
                                                    {{ (!empty($basicInfo->ceo_fax_no)) ? $basicInfo->ceo_fax_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Spouse name</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_spouse_name)) ? $basicInfo->ceo_spouse_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Gender</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->ceo_gender)) ? $basicInfo->ceo_gender : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endif

                            {{--2 = industrial department --}}
                            @if($basicInfo->business_category != 2 && $basicInfo->department_id == 2)
                                <fieldset class="scheduler-border" style="margin-bottom: 0 !important;">
                                    <legend class="scheduler-border">Factory Address</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">District</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_district_name)) ? $basicInfo->factory_district_name : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Police Station</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_thana_name)) ? $basicInfo->factory_thana_name : '' }}
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
                                                    {{ (!empty($basicInfo->factory_post_office)) ? $basicInfo->factory_post_office : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Post Code</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_post_office)) ? $basicInfo->factory_post_office : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">House, Flat/ Apartment, Road</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_address)) ? $basicInfo->factory_address : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Telephone No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_telephone_no)) ? $basicInfo->factory_telephone_no : '' }}
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
                                                    {{ (!empty($basicInfo->factory_mobile_no)) ? $basicInfo->factory_mobile_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Fax No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_fax_no)) ? $basicInfo->factory_fax_no : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Email</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_email)) ? $basicInfo->factory_email : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Mouja No.</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($basicInfo->factory_mouja)) ? $basicInfo->factory_mouja : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            @endif
                        </div>
                    </div>
                </div>
                {{--(End) Basic Company Information--}}

                {{--Basic Information--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic Information</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Did you receive last work-permit through online OSS?</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <span> {{ (!empty($appInfo->is_approval_online)) ? ucfirst($appInfo->is_approval_online) : ''  }}</span>
                                    </div>
                                </div>

                                @if($appInfo->is_approval_online == 'yes')
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Please give your approved work permit reference No.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <a href="{{$data['ref_app_url']}}" target="_blank" rel="noopener">
                                                <span class="label label-success label_tracking_no">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                            </a>

                                            &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                        </div>
                                    </div>
                                @endif

                                @if($appInfo->is_approval_online == 'no')
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Manually approved work permit reference No.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            <span> {{ (!empty($appInfo->manually_approved_wp_no)) ? $appInfo->manually_approved_wp_no : ''  }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Effective date of the previous work permit</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <span> {{ (!empty($appInfo->issue_date_of_first_wp) ? date("d-M-Y", strtotime($appInfo->issue_date_of_first_wp)) : '')  }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Start CEO information--}}
{{--                <div class="panel panel-info">--}}
{{--                    <div class="panel-heading"><strong>Information of Principal Promoter/ Chairman/ Managing Director/ CEO/ Country Manager</strong></div>--}}
{{--                    <div class="panel-body">--}}
{{--                        <table class="table table-responsive table-bordered" aria-label="Detailed CEO information Report">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th width="30%">Field name</th>--}}
{{--                                <th class="bg-yellow">Existing information (Latest BIDA Reg. Info.)</th>--}}
{{--                                <th class="bg-green">Proposed information</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            <tr>--}}
{{--                                <td>Country</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_country_id) ? $countries[$appInfo->ceo_country_id] : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_country_id) ? $countries[$appInfo->n_ceo_country_id] : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Date of Birth</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ ($appInfo->ceo_dob != '0000-00-00') ? date("d-M-Y", strtotime($appInfo->ceo_dob)) : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_dob) ? date("d-M-Y", strtotime($appInfo->n_ceo_dob)) : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>NID/ Passport No.</td>--}}
{{--                                @if($appInfo->ceo_country_id == 18)--}}
{{--                                    <td class="light-yellow">--}}
{{--                                        {{ !empty($appInfo->ceo_nid) ? $appInfo->ceo_nid : '' }}--}}
{{--                                    </td>--}}
{{--                                @else--}}
{{--                                    <td class="light-yellow">--}}
{{--                                        {{ !empty($appInfo->ceo_passport_no) ? $appInfo->ceo_passport_no : '' }}--}}
{{--                                    </td>--}}
{{--                                @endif--}}

{{--                                @if($appInfo->n_ceo_country_id == 18)--}}
{{--                                    <td class="light-green">--}}
{{--                                        {{ !empty($appInfo->n_ceo_nid) ? $appInfo->n_ceo_nid : '' }}--}}
{{--                                    </td>--}}
{{--                                @else--}}
{{--                                    <td class="light-green">--}}
{{--                                        {{ !empty($appInfo->n_ceo_passport_no) ? $appInfo->n_ceo_passport_no : '' }}--}}
{{--                                    </td>--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Designation</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_designation) ? $appInfo->n_ceo_designation : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Full Name</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_full_name) ? $appInfo->n_ceo_full_name : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>District/ City/ State</td>--}}
{{--                                @if($appInfo->ceo_country_id == 18)--}}
{{--                                    <td class="light-yellow">--}}
{{--                                        {{ !empty($appInfo->ceo_district_id) ? $districts[$appInfo->ceo_district_id] : '' }}--}}
{{--                                    </td>--}}
{{--                                @else--}}
{{--                                    <td class="light-yellow">--}}
{{--                                        {{ !empty($appInfo->ceo_city) ? $appInfo->ceo_city : '' }}--}}
{{--                                    </td>--}}
{{--                                @endif--}}

{{--                                @if($appInfo->n_ceo_country_id == 18)--}}
{{--                                    <td class="light-green">--}}
{{--                                        {{ !empty($appInfo->n_ceo_district_id) ? $districts[$appInfo->n_ceo_district_id] : '' }}--}}
{{--                                    </td>--}}
{{--                                @else--}}
{{--                                    <td class="light-green">--}}
{{--                                        {{ !empty($appInfo->n_ceo_city) ? $appInfo->n_ceo_city : '' }}--}}
{{--                                    </td>--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>State/ Province/ Police station/ Town</td>--}}

{{--                                @if($appInfo->ceo_country_id == 18)--}}
{{--                                    <td class="light-yellow">--}}
{{--                                        {{ !empty($appInfo->ceo_thana_id) ? $thana[$appInfo->ceo_thana_id] : '' }}--}}
{{--                                    </td>--}}
{{--                                @else--}}
{{--                                    <td class="light-yellow">--}}
{{--                                        {{ !empty($appInfo->ceo_state) ? $appInfo->ceo_state : '' }}--}}
{{--                                    </td>--}}
{{--                                @endif--}}

{{--                                @if($appInfo->n_ceo_country_id == 18)--}}
{{--                                    <td class="light-green">--}}
{{--                                        {{ !empty($appInfo->n_ceo_thana_id) ? $thana[$appInfo->n_ceo_thana_id] : '' }}--}}
{{--                                    </td>--}}
{{--                                @else--}}
{{--                                    <td class="light-green">--}}
{{--                                        {{ !empty($appInfo->n_ceo_state) ? $appInfo->n_ceo_state : '' }}--}}
{{--                                    </td>--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Post/ Zip Code</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_post_code) ? $appInfo->ceo_post_code : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_post_code) ? $appInfo->n_ceo_post_code : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>House, Flat/ Apartment, Road</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_address) ? $appInfo->ceo_address : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_address) ? $appInfo->n_ceo_address : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Telephone No.</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_telephone_no) ? $appInfo->ceo_telephone_no : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_telephone_no) ? $appInfo->n_ceo_telephone_no : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Mobile No.</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_mobile_no) ? $appInfo->ceo_mobile_no : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_mobile_no) ? $appInfo->n_ceo_mobile_no : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Email</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_email) ? $appInfo->ceo_email : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_email) ? $appInfo->n_ceo_email : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Fax No.</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_fax_no) ? $appInfo->ceo_fax_no : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_fax_no) ? $appInfo->n_ceo_fax_no : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Father's Name</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_father_name) ? $appInfo->ceo_father_name : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_father_name) ? $appInfo->n_ceo_father_name : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Mother's Name</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_mother_name) ? $appInfo->ceo_mother_name : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_mother_name) ? $appInfo->n_ceo_mother_name : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Spouse name</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_spouse_name) ? $appInfo->ceo_spouse_name : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_ceo_spouse_name) ? $appInfo->n_ceo_spouse_name : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Gender</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->ceo_gender) ? $appInfo->ceo_gender : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ (!empty($appInfo->n_ceo_gender) && $appInfo->n_ceo_gender != "Not defined") ? $appInfo->n_ceo_gender : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
                {{--End CEO information--}}

                {{--Start office information--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Office Address</strong></div>
                    <div class="panel-body">
                        <table class="table table-responsive table-bordered" aria-label="Detailed Office Address Report">
                            <thead>
                            <tr>
                                <th width="30%">Field name</th>
                                <th class="bg-yellow" width="35%">Existing information (Latest BIDA Reg. Info.)</th>
                                <th class="bg-green" width="35%">Proposed information</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Division <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_division_id) ? $divisions[$appInfo->office_division_id] : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_division_id) ? $divisions[$appInfo->n_office_division_id] : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>District <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_district_id) ? $districts[$appInfo->office_district_id] : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_district_id) ? $districts[$appInfo->n_office_district_id] : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Police Station <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_thana_id) ? $thana[$appInfo->office_thana_id] : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_thana_id) ? $thana[$appInfo->n_office_thana_id] : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Post Office <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_post_office) ? $appInfo->office_post_office : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_post_office) ? $appInfo->n_office_post_office : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Post Code <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_post_code) ? $appInfo->office_post_code : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_post_code) ? $appInfo->n_office_post_code : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Address <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_address) ? $appInfo->office_address : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_address) ? $appInfo->n_office_address : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Telephone No. <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_telephone_no) ? $appInfo->office_telephone_no : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_telephone_no) ? $appInfo->n_office_telephone_no : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Mobile No. <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_mobile_no) ? $appInfo->office_mobile_no : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_mobile_no) ? $appInfo->n_office_mobile_no : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Fax No. <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_fax_no) ? $appInfo->office_fax_no : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_fax_no) ? $appInfo->n_office_fax_no : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Email <span class="required-star"></span></td>
                                <td class="light-yellow">
                                    {{ !empty($appInfo->office_email) ? $appInfo->office_email : '' }}
                                </td>
                                <td class="light-green">
                                    {{ !empty($appInfo->n_office_email) ? $appInfo->n_office_email : '' }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{--End office information--}}

                {{--Start factory information--}}
{{--                <div class="panel panel-info">--}}
{{--                    <div class="panel-heading"><strong>Factory Address</strong></div>--}}
{{--                    <div class="panel-body">--}}
{{--                        <table class="table table-responsive table-bordered" aria-label="Detailed factory info">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th width="30%">Field name</th>--}}
{{--                                <th class="bg-yellow" width="35%">Existing information (Latest BIDA Reg. Info.)</th>--}}
{{--                                <th class="bg-green" width="35%">Proposed information</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            <tr>--}}
{{--                                <td>District</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_district_id) ? $districts[$appInfo->factory_district_id] : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_district_id) ? $districts[$appInfo->n_factory_district_id] : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Police Station</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_thana_id) ? $thana[$appInfo->factory_thana_id] : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_thana_id) ? $thana[$appInfo->n_factory_thana_id] : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Post Office</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_post_office) ? $appInfo->factory_post_office : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_post_office) ? $appInfo->n_factory_post_office : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Post Code</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_post_code) ? $appInfo->factory_post_code : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_post_code) ? $appInfo->n_factory_post_code : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Address</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_address) ? $appInfo->factory_address : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_address) ? $appInfo->n_factory_address : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Telephone No.</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_telephone_no) ? $appInfo->factory_telephone_no : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_telephone_no) ? $appInfo->n_factory_telephone_no : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Mobile No.</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_mobile_no) ? $appInfo->factory_mobile_no : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_mobile_no) ? $appInfo->n_factory_mobile_no : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>Fax No.</td>--}}
{{--                                <td class="light-yellow">--}}
{{--                                    {{ !empty($appInfo->factory_fax_no) ? $appInfo->factory_fax_no : '' }}--}}
{{--                                </td>--}}
{{--                                <td class="light-green">--}}
{{--                                    {{ !empty($appInfo->n_factory_fax_no) ? $appInfo->n_factory_fax_no : '' }}--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
                {{--End factory information--}}

                {{--  Information of Expatriate/ Investor/ Employee --}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>
                            Information of Expatriate/ Investor/ Employee
                        </strong>
                    </div>
                    <div class="panel-body">
                        {{--General information--}}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><strong>General information</strong></legend>
                            <table class="table table-responsive table-bordered" aria-label="Detailed General info">
                                <thead>
                                <tr>
                                    <th width="30%">Field name</th>
                                    <th class="bg-yellow">Existing information (Latest Work Permit Info.)</th>
                                    <th class="bg-green">Proposed information</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Full Name <span class="required-star"></span></td>
                                    <td class="light-yellow">{{ (!empty($appInfo->emp_name)) ? $appInfo->emp_name : '' }}</td>
                                    <td class="light-green">{{ (!empty($appInfo->n_emp_name)) ? $appInfo->n_emp_name : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Position/ Designation <span class="required-star"></span></td>
                                    <td class="light-yellow">{{ (!empty($appInfo->emp_designation)) ? $appInfo->emp_designation : '' }}</td>
                                    <td class="light-green">{{ (!empty($appInfo->n_emp_designation)) ? $appInfo->n_emp_designation : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Passport No. <span class="required-star"></span></td>
                                    <td class="light-yellow">{{ (!empty($appInfo->emp_passport_no)) ? $appInfo->emp_passport_no : '' }}</td>
                                    <td class="light-green">{{ (!empty($appInfo->n_emp_passport_no)) ? $appInfo->n_emp_passport_no : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Nationality <span class="required-star"></span></td>
                                    <td class="light-yellow">{{ (!empty($appInfo->emp_nationality_name)) ? $appInfo->emp_nationality_name : '' }}</td>
                                    <td class="light-green">{{ (!empty($appInfo->n_emp_nationality_name)) ? $appInfo->n_emp_nationality_name : '' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>

                        {{--Previous work permit duration--}}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><strong>Previous work permit duration</strong></legend>
                            <table class="table table-responsive table-bordered" aria-label="Detailed Report Data Table">
                                <thead>
                                    <tr class="d-none">
                                        <th aria-hidden="true" scope="col"></th>
                                    </tr>
                                    <tr>
                                        <td>Information</td>
                                        <td>Start Date <span class="required-star"></span></td>
                                        <td>End Date <span class="required-star"></span></td>
                                        <td>Duration (Days)</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr class="light-yellow">
                                    <td class="bg-yellow">
                                        Existing information (Latest Work Permit Info.)
                                    </td>
                                    <td>
                                        {{ (!empty($appInfo->p_duration_start_date) ? date('d-M-Y', strtotime($appInfo->p_duration_start_date)) : '') }}
                                    </td>
                                    <td>
                                        {{ (!empty($appInfo->p_duration_end_date) ? date('d-M-Y', strtotime($appInfo->p_duration_end_date)) : '') }}
                                    </td>
                                    <td>
                                        {{ (!empty($appInfo->p_desired_duration)) ? $appInfo->p_desired_duration : ''  }}
                                    </td>
                                </tr>
                                <tr class="light-green">
                                    <td class="bg-green">
                                        Proposed information
                                    </td>
                                    <td>
                                        {{ (!empty($appInfo->n_duration_start_date) ? date('d-M-Y', strtotime($appInfo->n_duration_start_date)) : '') }}
                                    </td>
                                    <td>
                                        {{ (!empty($appInfo->n_duration_end_date ) ? date('d-M-Y', strtotime($appInfo->n_duration_end_date)) : '') }}
                                    </td>
                                    <td>
                                        {{ (!empty($appInfo->n_desired_duration)) ? $appInfo->n_desired_duration : ''  }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </fieldset>

                        {{--Compensation and Benefit--}}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"><strong>Compensation and Benefit</strong></legend>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" aria-label="Detailed Compensation and Benefit">
                                    <thead>
                                        <tr class="d-none">
                                            <th aria-hidden="true" scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td rowspan="3"  width="28%">Salary structure</td>
                                            <td colspan="3" class="bg-yellow">Existing Information</td>
                                            <td colspan="3" class="bg-green">Proposed Information</td>
                                        </tr>
                                        <tr>
                                            <td class="light-yellow" width="13%">Payment</td>
                                            <td class="light-yellow" width="13%">Amount</td>
                                            <td class="light-yellow" width="10%">Currency</td>
                                            <td class="light-green" width="13%">Payment</td>
                                            <td class="light-green" width="13%">Amount</td>
                                            <td class="light-green" width="10%">Currency</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>a. Basic salary/ Honorarium</td>
                                        <td class="light-yellow">{{ $appInfo->basic_payment_type_name }}</td>
                                        <td class="light-yellow">{{ (!empty($appInfo->basic_local_amount) ? $appInfo->basic_local_amount : '') }}</td>
                                        <td class="light-yellow">{{ $appInfo->basic_currency_code }}</td>

                                        <td class="light-green">{{ (!empty($appInfo->n_basic_payment_type_name) ? $appInfo->n_basic_payment_type_name : '') }}</td>
                                        <td class="light-green">{{ (!empty($appInfo->n_basic_local_amount) ? $appInfo->n_basic_local_amount : '') }}</td>
                                        <td class="light-green">{{ (!empty($appInfo->n_basic_currency_code) ? $appInfo->n_basic_currency_code : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td>b. Overseas allowance</td>
                                        <td class="light-yellow">{{ (!empty($appInfo->overseas_payment_type_name) ? $appInfo->overseas_payment_type_name : '') }}</td>
                                        <td class="light-yellow">{{ (!empty($appInfo->overseas_local_amount) ? $appInfo->overseas_local_amount : '') }}</td>
                                        <td class="light-yellow">{{ (!empty($appInfo->overseas_currency_code) ? $appInfo->overseas_currency_code : '') }}</td>

                                        <td class="light-green">{{ (!empty($appInfo->n_overseas_payment_type_name) ? $appInfo->n_overseas_payment_type_name : '') }}</td>
                                        <td class="light-green">{{ (!empty($appInfo->n_overseas_local_amount) ? $appInfo->n_overseas_local_amount : '') }}</td>
                                        <td class="light-green">{{ (!empty($appInfo->n_overseas_currency_code) ? $appInfo->n_overseas_currency_code : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td>c. House rent</td>
                                        <td class="light-yellow">{{ (!empty($appInfo->house_payment_type_name) ? $appInfo->house_payment_type_name : '') }}</td>
                                        <td class="light-yellow">{{ (!empty($appInfo->house_local_amount) ? $appInfo->house_local_amount : '') }}</td>
                                        <td class="light-yellow">{{ (!empty($appInfo->house_currency_code) ? $appInfo->house_currency_code : '') }}</td>

                                        <td class="light-green">{{ (!empty($appInfo->n_house_payment_type_name) ? $appInfo->n_house_payment_type_name : '') }}</td>
                                        <td class="light-green">{{ (!empty($appInfo->n_house_local_amount) ? $appInfo->n_house_local_amount : '') }}</td>
                                        <td class="light-green">{{ (!empty($appInfo->n_house_currency_code) ? $appInfo->n_house_currency_code : '') }}</td>
                                    </tr>
                                    <tr>
                                        <td>d. Conveyance</td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->conveyance_payment_type_name) ? $appInfo->conveyance_payment_type_name : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->conveyance_local_amount) ? $appInfo->conveyance_local_amount : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->conveyance_currency_code) ? $appInfo->conveyance_currency_code : '') }}
                                        </td>

                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_conveyance_payment_type_name) ? $appInfo->n_conveyance_payment_type_name : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_conveyance_local_amount) ? $appInfo->n_conveyance_local_amount : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_conveyance_currency_code) ? $appInfo->n_conveyance_currency_code : '') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            e. Medical allowance
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->medical_payment_type_name) ? $appInfo->medical_payment_type_name : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->medical_local_amount) ? $appInfo->medical_local_amount : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->medical_currency_code) ? $appInfo->medical_currency_code : '') }}
                                        </td>

                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_medical_payment_type_name) ? $appInfo->n_medical_payment_type_name : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_medical_local_amount) ? $appInfo->n_medical_local_amount : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_medical_currency_code) ? $appInfo->n_medical_currency_code : '') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            f. Entertainment allowance
                                        </td>

                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->ent_payment_type_name) ? $appInfo->ent_payment_type_name : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->ent_local_amount) ? $appInfo->ent_local_amount : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->ent_currency_code) ? $appInfo->ent_currency_code : '') }}
                                        </td>

                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_ent_payment_type_name) ? $appInfo->n_ent_payment_type_name : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_ent_local_amount) ? $appInfo->n_ent_local_amount : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_ent_currency_code) ? $appInfo->n_ent_currency_code : '') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            g. Annual Bonus
                                        </td>

                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->bonus_payment_type_name) ? $appInfo->bonus_payment_type_name : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->bonus_local_amount) ? $appInfo->bonus_local_amount : '') }}
                                        </td>
                                        <td class="light-yellow">
                                            {{ (!empty($appInfo->bonus_currency_code) ? $appInfo->bonus_currency_code : '') }}
                                        </td>

                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_bonus_payment_type_name) ? $appInfo->n_bonus_payment_type_name : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_bonus_local_amount) ? $appInfo->n_bonus_local_amount : '') }}
                                        </td>
                                        <td class="light-green">
                                            {{ (!empty($appInfo->n_bonus_currency_code) ? $appInfo->n_bonus_currency_code : '') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            h. Other fringe benefits (if any)
                                        </td>
                                        <td colspan="3" class="light-yellow">
                                            {{ (!empty($appInfo->other_benefits) ? $appInfo->other_benefits : '') }}
                                        </td>
                                        <td colspan="3" class="light-green">
                                            {{ (!empty($appInfo->n_other_benefits) ? $appInfo->n_other_benefits : '') }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                        {{--Effective date of Compensation and Benefit--}}
                        @if($appInfo->effective_date != '')
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border"><strong>Effective date of Compensation and Benefit</strong></legend>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-5"><span class="v_label">Effective date of amendment</span></div>
                                            <div class="col-md-7">
                                                <span>{{ (!empty($appInfo->effective_date) ? date('d-M-Y', strtotime($appInfo->effective_date)) : '') }}</span>
                                            </div>
                                        </div>
                                        @if((in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505') || (in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404'])))
                                            <div class="col-md-6">
                                                <div class="col-md-5 v_label">Approved effective date</div>
                                                <div class="col-md-7">
                                                    <span>{{ (!empty($appInfo->approved_effective_date) ? date('d-M-Y', strtotime($appInfo->approved_effective_date)) : '') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>
                        @endif
                    </div>
                </div>

                {{--Attachment--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Necessary documents to be attached here (Only PDF file)</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered table-hover" aria-label="Detailed Necessary documents">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th colspan="6">Required attachments</th>
                                <th colspan="2">
                                    <a class="btn btn-xs btn-primary" target="_blank" rel="noopener" href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id)) }}"><i class="fa fa-link" aria-hidden="true"></i> Open all</a>
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

<script src="{{ asset("vendor/html2pdf/html2pdf.bundle.js") }}"></script>
<script>
    let is_generate_pdf = false;
    document.getElementById("html2pdf").addEventListener("click", function(e) {
        if (!is_generate_pdf) {
            $('#html2pdf').children().removeClass('fa-download').addClass('fa-spinner fa-pulse');
            generatePDF();
        }
    });
    function generatePDF(){
        let element = $('#applicationForm').html();
        let downloadTime = 'Download time: ' + moment(new Date()).format('DD-MMM-YYYY h:mm a');
        let opt = {
            margin:       [0.80,0.50,0.80,0.50], //top, left, bottom, right
            // filename:     'myfile.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
            enableLinks:  true,
            pagesplit: true
        };

        let html = '<div style="margin-top: 60px">' + element + '</div>';

        html2pdf().from(html).set(opt).toPdf().get('pdf').then(function (pdf) {
            let pageCount = pdf.internal.getNumberOfPages();

            pdf.setPage(1);
            pageWidth = pdf.internal.pageSize.getWidth();
            pageHeight = pdf.internal.pageSize.getHeight();

            let image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKIAAAA2CAYAAABEBUJOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAFjFJREFUeNrsXQlcVFXb/88MM8POiGzK4uAGuAEmSoZ7Si6fYGpuFZKWWhZimi0aaraYmVqZfpoab5mZqViZZZq4g6UgaioqIqAgm6zDDLPc99yZMzri7IDyvu88/u5vLvdsz73P/z7bOfcI2MhGNrKRjWxkIxvZyEY2spGNbGQj/cRpro4XfrvW60DOhaElNZXd+Bxue5lC4SXgctVl9SoVhHZ2xXJGlePp7HY+ukPX40umvJJrE4cNiE1Cy3ds8vgu6+QUO+CZcHHnyACRB/fo5bNoI2qN2IiBGNOr3926FbXVyC0twrn8HKRfv4SsG1ezK6WSneH+HZKTZ75z2SYaGxAtpvh1y/zT866+3d1XHDcpcohDdPfesBcIkHzkV2TcuIrO3r4oq6lCgIcPnusXDS6H+0AfDMPg5JXz+C7tIHM0O2tfB3fvpbtf/yjdJqL/DeI1pnHq2XRhpgezUOTs+v0nE2c+/uqwsfzgtgGQyOqwN/MkaqR18HRxw983stGnYxf8dDYNI3r0gcCOf7eP/LLb+JVcb+PmjqC27TAiNJIzOvyJTpeK8qdJQnw6T4h/9kT6nv01NlHZNKJemvJ5Uter5cXfvTliYo/YXv3V17LyruLIxUy4ObkgunsE9mScwO8EZOcL85C64FMs2rkJG6cveKCv4ooyvPnDBvi28kCQjz+e7ffU3f7e2rGxTKaQTz/45qqUxt5swKRth5rgmR0mRyb7HuZtm1RhYryp5CfORH/JpJ+vG7QLIz+rrOCN9bNvmMufBc+N5SXMjKpjrB3TzppGQz5MjK0H882ehGXOrg5OKCgrxo70Qwht1xEnrv0DEoDAi2jC+H7D8TMB49xhY3HoYgYYPX1N/nIpXOwdkHnzOj57LgEDlifC38MbA0LC0SOgI3YnLGudtHPzrurFM99PS1r3LpfDYRrxTAc2gVy0fVQQAa0hD36xkbpiM8Y8rOeaqCl4JfyxLy/LY2oj+mB5mWPBs7FKYXAtbdB/6SvxXX3FP26ducjZm5jTz3/bgTlbP8eEx4fgRkkh+geFYvrAUfiFmObko/vQw1cMF74Q/xQVoG+nbqgkQYouJcXGwd3JFSOJyb5ENOek3oNQeKcMSTs24nZludqMfzhhBuelgaMW9l40fUOtTMpFyyBWQElEUBkt2OLFkuMQawnIIX4IL2+MtYxaJNSoJS9P6NOx61ern31V7Vuu2rcd/YJD0c2/g1rj9QsOw5nrl/H6trVgzXUw8flKSJBSUS+FmJjdFwaMVJttXWL9Qi6Xi7gnokmgcgCTIgfDSWgPFQloSivvYOXPW4mvKcE00nb+yMnTB7w3+7MWJuwwarpaMrFgyqAm31KKsRD4VpHZpnnEx/Oj2rb2+tdHE2dw6xVyrNi7DX6tPLGFaL0N097A3K1fwN/dExumv3EvEibBSu8QCZQXLkNZUICavWkax9TZCTw/P/C6BsEuvAcWxjwPB4EQId7+IP4grhbfwriIAdidcRzl1RVYu38XXhoSg2cISMtqq1/h8fm56UnrPmlBgp5DzXRuCwajiGrHQYTPzGYCl4j0H0v6T2kWIL761QqPqxUl29dMmS1giIf2XkoyimqrIFcp0crRBcezz2H5hJlII/4hS6rycsi+3Q7Zj3vB1MmMR0sOQvCf7Afl5HF4cYjm5RPY2aGaaMG0KxcQ6O0LCQF0/IYPse2VdzGL1LlSlP+R20dz0/e/+enRFiToJDaT1cI1IwvG3QQs4eYEFVSDiqzQoBYD0SzTfDLvyhcrJ85q60hM5qZDP6ELMachHm1IQCIiGqoKpVV3IK2XoV/HrpB+uw1VY+Ig/WaXSRCqtSapU//zAVRNmQXJso/BVFdjLNGGUUE98Mu85XiBRNAnci8ToM9A3IaPkF9ahGXjpvMYDif5XwdSHJtYUGz0uqTBsZoNTB5SIPQwiPUVt5hZN86K/q0yzyaBSEzyUAKMCSEk6NhyZB/2XTiNiPbBYKPlzPwcNu+HGOIPOtfLUT17PurWbAbHxYoIXsVAtucPAsgZ8Lxdqr7Ezrqs3r8Tu197D9vT/kRnH3+kX7sI9oVYFPNc4KepP7/dxEJiUymLGxyJ5HogTY2YEvDDJDY9w9Ee5O9w9pq5YCHabmAzvVwia3xRo0BUqVQcqVK+PGHYWMjkctwqL4FEJsWKfT+gWFKNz56djaeJ9lKVlqL6xTlQ/H1OY+87VYH/eCl4nSzPQ6sKS1D90jwoMjLh5SrC+qlzsXjXZni7tUIn77ZIOX0MxUQD9yPRee/A4LkJm1a0aW6JUzOW3JLVHOv3kWOQBWYxwYRZFpuZO2wSTco1rg3njRjfe1A4G0gczT6rNr8q4heufX4OREJHvLX9/6GSSFCT8BaUuTc1IAyqgl1IOfjdSyHoVQyujxT8qBIykvnpP0YiRc3cJHiUV6r9w17iIEjI2JGBIRjaPQJrD+zW+K5Dn3ZIzbmY+JBk7WaiPLOFYDLeTFci1kRKx5iJNWUdYpsUiOVSyazn+j6pBqCnkyuulhZiTvR4FJTdxoGLZ7Dq2ddQ98lnUGbr8OWgJEjSTNhwXOphPzoX/C5l4DiqLGKMqZGgdsFSuBMzrGSU6rnoID8xpvQdijqilW/dKUU3//bo7hc47dg/GcLmlCxN6k41UW1PC9GMFdTXbaw/Zyxts8SUm2KpeTYYNS/f8ZVXsVz2lAMBwrLdycgngp/WfwSC2wRgR/qfeH/cdGKKT5NA4+B97RSZrQAJD/zIYnDsFfeiYyEBU41lU9vK6wWQfv0doiePxbHsLHV0/uEvW9Vg3JtxHC8OjsGo8L7u7+zcNIpU39kEcozT4zu5URAaix4raFDTUoh1I8yZDRmgj2/64g00ovlTzAh44iyxEgY14g9Z6aOiu0fw2JUyfh7e8Cb+2qlr/6jni7v4BpJDDOmXm/W2VVxxheynAOLw3ZvK5npLAYGOeWZNtRkz3bKtu9BaqULsY/3gRFwEP3cvpJw5pjbVLA3v3gfldbWjm0iAU2kaRveYYwYIBzXVvG5T+YsWRNCWaso99F5NBUYDm0Qj2nG5Mex0HUvXigqIeVShuKYSW156S31NfvwEFOevPIjsVnLwI0rAC6i+zy8URN2CoC8XjIynNt0cezmYenKu5KrPVaUOqE/3hKrI4YH0jvRf2+Hw2kzklBRieNdecCeBiwPPDn/lXCQRfAgJWoJGnZLXcx34AtVDljkrjEQLE8QPkzdTYAgzoimN9cvSYRP9szNOYnOT/AaB2MO/Q4SQL8DPRPuMDo2Eh2srtPfxu1te/9ufetsJhtwC173OgP5VgeNwDyu6plt50wmqYge9zep/PwSHV2diUEg4Wjm7Yvqmj4l2tMf43prn1blNgPvSrV92IqcPc0GtOufYwmdTrCVDGrFCZwFFCrUYpvoxy2XRa5rTLp4VuTk5t6mXy1FSVYGFKcn4/GAKZm5egd2nNItFFGey9Jvl8yKN1rPIGeRCmeNCTLmBlE5xOVQFeWoQstTayQXnbubiTN5VDRC9/XDgxuXghyws1oxfJ2/9FupT/VcQ9ZFFJrSh1vybckfMTuPoBeKuEwd8/dw9IeDzUSWrw+Q+g8CoVJg9bBzGEC3EEHCqiu/oB+IlN0i/7wBlvot5ecNiR8j+9IWqXGAcq1evq39vk6CpZ2AQegZ0QHZhPnKLb8G/tRd4SqXvI5KdFpDi/xIsGouWGy5ZM5WzDDP3ueg1zel52e7xHTUKhp1LXrX/R2yOn8f6jRrwlJYaT73IST0zlg1Kf2kHVSExx2akGFVlZerpvfWH9yKQBCxPdo/AzdLbEHu1hbIwD3ckNa2bQAj63nIRTCd21XO40MxutBQaaOb9WhKosFmFGDOCHYvNs14g3pTV8fhcjXmN6/cUBoeEqSNnjjbMlSmMdspxVoDnV2MiTyiA6pbGJ+T5S8BxkxOzbjhnzMjk8Pfwwftjpz1QxvJapVTwmkB4ifoWkdKc2CET0XOYtStPmsG8mpvDy23QTmwCXNbMtAwwB4h6TbOv0EHJrqzREgsAjm6uRWh80Q5TZQdFtnG3ieMoV4OP5cAutAz88FKj6RyOkG+wjOXVlWenbOZ0SKKZD70lkLm+2WELtKHVgY85PrReIPZp17msQiox3MjDw3T8ke9sYmQGgqjbJHJWgde2Vp3CMTYNyG1teMwKaR1xIZzLmlm45kTHYY8agVSrTTWzeoq1wUUTReHGgRgbOfhmQXmJYe3kKgLXq5VJraj3uoSvDlAYOU8NQOHoG7RTwoyX4WVjvE6GLUZ+WTGUPN7NFuBzPWoQan1Vc6L4VN3UE23bXC9SjFVA7NslvLKytqbwPgDV3Z8btOsZahyIBlw2RiJQjyrP8IDsgB85uWeP7TpW6WfSyx1c3wCDvGTfLsBQcdCl5hIuOeaYkTMDzFts0FwgjKV+rLlgWvIQzLLZ5tmgs5eVf+20UqUaxaORsuwH9kVTQRgzAhyROwRD+qH+t1TjGpHBPb9PSfrhqYhvWK9J1SjYWRYu5Gc9IBxcoAGcu1RvX/whUZp+iC8o25UC5dUbcHxz7t3ynOKblaufS7jyQdycxj4wdil9Y9qffZhRMeHV2i8aU/QEZTHNza+xdI9BINYp6g9l5GaP6tU+WJ1Mlh9LJ5FuFYSTxmvAEfU4uD4eUBUZSOUoOVDedgTPR0K1mB04DgrIz7VWJ65Vd2jUTPDJ9BaQSLseHCc90TiXA+HTo+g5D4qL2VCcyoL8r7/Aj4iAXKFgd5M48gim98zxuVoisVo73kLXI9zYNCb9ftvUIgijnxAYXPQQEdDp11+zNDt+cP0C4DAjDi5fryOajILFjg/7aZOMm+cKIZQlTlCRX/lpT8gvtILirAiKcyKobtLpPAIf5W16Lnww8BUM6w+eOJCCWwnHxNlwXDRXDUKW2L115CrV3hYg4NQWOufcEIQPLNCgZt2Q6aww475SGxuwGATiphlvXzqefe4MMc8a3PXqCY7QnphWp3tZnNEjYdcjyHBa5XRr1P/uC+kuMRSXXSBP89TPBNGG6nilwYocjqsTHF6boROx8IjmdAG/d8Q9FXTmeP3kHpE7W4CQE/9DQJhpYdrJJMho0GMqqyCigLcMiCyVS6rX7zub1kDN6fwSU+m09C1w3PSnaphaOxKckCEUxtd7yc94Qpnngvq/PYl5Vt41yU7vzgXX0/P+MXWI/QD/aHbWjvnjp5U+YiHHt3BtyJrEQCM8Gl321YRuSYxVQHx/9PPfbj68t5BdHa0lVWEB6r5Yj/o/9ms68PWF88olRFPaW/2UlMRXVBY6EbPdSrN4luDWMfFF8Ado9tRR5lxB3ZcbID9y5L52aw/sZrr5+K98lOaYapmvWzAAWf4M7klDZ2HEjTS7LB1ujHk2OkUS/VhU3RNLX16641Tqumf6DFJfU1y6TCLXfcQsDyG3SPxbvgB2oT3g/OVy1M5LgqrUigwGCWzUc87qKIgHxwWzSXQ+6l4xO+YPvwATGRIk9SUm2g7XiwuxL+vUD38v3ZBhIXAaa97OUjOUasYSsFwzxsw1ME5qI/izZBMmsZGxKsxd5sZObRJQm+TZ0BpFk2uki+6U8catTTr1U8L7PbXLsOSpqeAPfDDIYhdDSD78lGiuv6ySMq+9HxyT5sOuS5cHzbfOmAz59/z6D2rsOJwuX898Jx82+o8ns7alG7/qnVCRq1v6hhfmm/WRkvxkGqQbvyHRcbZZTHD9fWA/ZSzRgiPV0bgpWn9wD7ae/GPGsUVrN9hE+D9GUUtfmbHy1+2M2aRSMfKMDEaydgNT9fLrTMX/TWDuDBypPthz9hpbxtZh65pLRy9nMeELp2215h5Yf0ib4dc9t7CPRu+x2FI2baLPYFVT8szmFK35wN6ijTp7v/vS6sSnnkmY+PgQg3VulhWrV3NP7z8cHX38jfbHfqZqLxCirLoSe84cw3jih7rYG95F5EJBDqZtWnFiVt/ooVOHjZFY8eCnkh92bnINOQ4RXyVQ67NQUIqpzybW8bkqtL4WXVDArtANbOCXQbcO7S+sof+nUydMZ5xc9jq7Mpqd7dApE2l9KZ0+dcdnzzNpW7GBunf5g2b6b402sNL5Ui+B/TDfQB/s82I/xB9Ex6vQ8R0b3q82ob1EW66PF0N+q0VAlNTLOFGLZ62bM/yZGc9FReuto1Aq8cb36zAwOAw/ZRxHn/Yh8PXwhriVp3pDTjYFUy2rQ15FKb4/eRCRgcHEGvPx9bHfcHThFwYZOpObjdnJq/4K8w4Yum7W25WN0AIZ1Dk/Sx8uC8wwmgdMoA8ySUeI8fShHqIAmUrrsKkI9rPNOFqPbb+bni+hfeVSYbPBQxgLfB2tytbZQstyKQ/xFDDJlC8RfWmS6Lm2zSBabzWNRLV5vBs67Q7T/KCI9sfyo95ShYIwg46tratvvFTK/yA6rm6f2nq696uNnlNpmfZl0223RN+aTYv2R3QUCJlTyzbO+uyPXR+zO4Jpk9269HtWGpQEbewmnf6tvfHCwJHIu30LC3ZsxD+3C5B8Yj8mbvwApXfK1GnBEWGROHPjCh5r1xlpV87rT2SdPorpmz85MDiwS6NASIl9gLFUMwygwk+B/sUCa3TeYFZDxTfIr4noIaZHshZ0tE4yFcYaA9GxtiyM9pdAhTiA9hem1cq62kw7T0z35RHTrUbEDdppQaHlUzeCD6P3vEYnof3AeFr+G0S52j5DG9zv4QYpHO3zEjVoF2Zx+kZvAy6Pxc8Cp2XCrKyCnPWfTJjp3M7z3vYzLg7OmNR7MCqltXB3dEb/Za9hfK/+6OIXiOKKchRWliOsbSAy869hWNfH2JkRlNZWIcjLF9vS/8Tjnbrd7atWWofFu7cwBy9mfLptxsI3g/3EiiZwjXJ1QKHVcGH0wcfA8OoVkZ6P7yuoIJJ1BHrYSr5SqJZLpFpWu1e3FuAJZPxMHRfBUPrmcIOXoWGqRlsvjGrQhu3EOhpURO9bny8dpid/2E6nT5GJD7Gs14j3ZS8Xfr7VgWcXPuazd//8eO829f8kwFL/4FBEduqKW8RXjOjQBa8MicHFojx08PDBxVs3ENDai/iGUnB5PGK+Q1FUfQfzop+Br7sXng5/QpMGYhjsOHUIw1e+ce1c/rXojPe+mtdEINSCTwucRK2ZoeZijU55cgMtFq9jwlNpnm41FYSYCnEP/c3UaZ/cYEzoK6NaZw3tW6t5RRSgWh7ZsjF0vCU6Ppm2T912qQ2ORB1fVctfhZ52uuMtpuVa867b35oG97uagjCV3tMYatbH6OGleWjwBwnjIpe8fGH5L1uZojul6ui2TiZV/y7cvp45mX2OOXLprPrvGqmEWfTjV3ejYLZMqVSq69eSsm+O/c4MW/56yWOLXpz/08mD9rCRLY9oCdUrFNzYlQueyq0ofbGHX/vhg7v0FA4N6Yl23m0NDsLa+FziP6bl/INDFzOZjBvZaRwOd/NbQ8dufTpqWJ1NPDYgNop+PLrfZeXBXdHlUskwIZfXrYNX2/ZCgdCLz+Gqx5MzKkZWLyu+VnwrR6ZSnncVOpwYFRT6R9Lkl2/aRGIjG9nIRjaykY1s9Mjp3wIMAKmroILpKWZSAAAAAElFTkSuQmCC";
            pdf.addImage(image, 'PNG', pageWidth / 2 - 0.60, 0.50, 1.20, 0.40);
            pdf.setFontSize(14);
            pdf.text("Bangladesh Investment Development Authority (BIDA)", 1.80, 1.20);

            pdf.setFontType("italic");
            pdf.setFontSize(8);
            pdf.setTextColor(32, 32, 32);

            for (let j = 1; j < pageCount + 1 ; j++) {
                pdf.setPage(j);
                pdf.text(`${j} / ${pageCount}`, pageWidth - 1, pageHeight - 0.50);
                pdf.text(downloadTime, 0.60, pageHeight - 0.50);
            }

            //generated url
            let url = pdf.output('bloburl');
            $('#html2pdf').children().removeClass('fa-spinner fa-pulse').addClass('fa-download');
            $('#html2pdf').attr({href: url, target: "_blank"});
            is_generate_pdf = true;
            window.open(url, '_blank');
        });
    }

    let basicCompanyInfo = $('#basicCompanyInfo');
    basicCompanyInfo.on('shown.bs.collapse', function () {
        $(".fa").removeClass("fa-angle-down").addClass("fa-angle-up");
    });
    basicCompanyInfo.on('hidden.bs.collapse', function () {
        $(".fa").removeClass("fa-angle-up").addClass("fa-angle-down");
    });
</script>