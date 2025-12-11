<?php
$accessMode = ACL::getAccsessRight('Waiver');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>
    .row > .col-md-5, .row > .col-md-7, .row > .col-md-3, .row > .col-md-9, .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }
</style>

<section class="content" id="applicationForm">

    @if(in_array($appInfo->status_id,[5,6,17,22]))
        @include('ProcessPath::remarks-modal')
    @endif

    <div class="col-md-12">
        <div class="panel panel-info">

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

            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong>Application for Waiver</strong></h5>
                </div>
                <div class="pull-right">
                    @if ($viewMode == 'on' && isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info"
                           title="Download Approval Copy" target="_blank">
                            <i class="fa  fa-file-pdf"></i>
                            Download Approval Copy
                        </a>
                    @endif

{{--                    <a class="btn btn-md btn-primary" data-toggle="collapse" href="#basicCompanyInfo" role="button"--}}
{{--                       aria-expanded="false" aria-controls="collapseExample">--}}
{{--                        <i class="fas fa-info-circle"></i>--}}
{{--                        Basic Company Info--}}
{{--                    </a>--}}

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>

                    @if(!in_array($appInfo->status_id,[-1,5,6]))
                        <a href="/waiver/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank" class="btn btn-md btn-danger">
                            <i class="fa fa-download"></i>
                            Application Download as PDF
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
                    <li><strong> Date of
                            Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    <li><strong>Current Desk
                            :</strong> {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}
                    </li>
                </ol>

                {{--Payment information--}}
                @include('ProcessPath::payment-information')

                {{--Company basic information--}}
                @include('ProcessPath::basic-company-info-view')

                {{--conditional_approved_file--}}
                @if($viewMode == 'on' && !empty($appInfo->conditional_approved_file))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Conditionally approve information</legend>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <span class="v_label">Attachment</span>
                                    </div>
                                    <div class="col-md-10">
                                        <a target="_blank" class="documentUrl btn btn-xs btn-primary" href="{{ URL::to('/uploads/'. $appInfo->conditional_approved_file) }}">
                                            <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-2">
                                        <span class="v_label">Remarks</span>
                                    </div>
                                    <div class="col-md-10">
                                        {{ (!empty($appInfo->conditional_approved_remarks) ? $appInfo->conditional_approved_remarks : '') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                @endif
                {{--conditional_approved_file--}}

                {{--meeting_info--}}
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
                {{--meeting_info--}}

                {{-- Basic Information --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic information</strong></div>
                    <div class="panel-body">
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Approved office permission reference no.</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    <a href="{{$data['ref_app_url']}}" target="_blank">
                                        <span class="label label-success label_tracking_no">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                    </a>
                                    &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                </div>
                            </div>
                        <br>
                    </div>
                </div>

                {{-- Office Type --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Office type</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5 col-xs-6">
                                <span class="v_label">Office type</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-6">
                                {{ (!empty($appInfo->office_type_name)) ? $appInfo->office_type_name : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Approved Permission Period --}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Approved Permission Period:</strong>
                    </div>
                    <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Start date</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->approved_permission_start_date)) ? $appInfo->approved_permission_start_date : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">End date</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->approved_permission_end_date)) ? $appInfo->approved_permission_end_date :''  }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Duration</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->approved_permission_duration)) ? $appInfo->approved_permission_duration : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Payable amount.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->approved_permission_duration_amount)) ? $appInfo->approved_permission_duration_amount:''  }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                {{--Information about the principal company--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Information about the principal company:</strong>
                    </div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                <span>Company information</span>
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Name of the principal company</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->c_company_name)) ? $appInfo->c_company_name : '' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Country of origin of principal office</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->principle_office_name)) ? $appInfo->principle_office_name :''  }}
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
                                            {{ (!empty($appInfo->op_org_name)) ? $appInfo->op_org_name : '' }}
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
                                            {{ (!empty($appInfo->c_flat_apart_floor)) ? $appInfo->c_flat_apart_floor:''  }}
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
                                            {{ (!empty($appInfo->c_house_plot_holding)) ? $appInfo->c_house_plot_holding:''  }}
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
                                            {{ (!empty($appInfo->c_post_zip_code)) ? $appInfo->c_post_zip_code:''  }}
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
                                            {{ (!empty($appInfo->c_street)) ? $appInfo->c_street:''  }}
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
                                            {{ (!empty($appInfo->c_email)) ? $appInfo->c_email:''  }}
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
                                            {{ (!empty($appInfo->c_city)) ? $appInfo->c_city:''  }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Telephone no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->c_telephone)) ? $appInfo->c_telephone:''  }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">State/ Province</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->c_state_province)) ? $appInfo->c_state_province:''  }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Fax no.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->c_fax)) ? $appInfo->c_fax:''  }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Major activities in brief</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->c_major_activity_brief)) ? $appInfo->c_major_activity_brief:''  }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                </div>

                {{--Information about the proposed branch/liaison/representative office--}}
                <div id="ep_form" class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Information about the proposed Branch/ Liaison/ Representative
                            Office</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Name of the local company</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->local_company_name)) ? $appInfo->local_company_name : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Name of the local company(Bangla)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->local_company_name_bn)) ? $appInfo->local_company_name_bn : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Local address of the principal company: (Bangladesh only)
                            </legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Division</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->ex_office_division_name))? $appInfo->ex_office_division_name : ''  }}
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
                                            {{ (!empty($appInfo->ex_office_district_name))? $appInfo->ex_office_district_name :''  }}
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
                                            {{ (!empty($appInfo->ex_office_thana_name))? $appInfo->ex_office_thana_name : ''  }}
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
                                            {{ (!empty($appInfo->ex_office_post_office)) ? $appInfo->ex_office_post_office:''  }}
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
                                            {{ (!empty($appInfo->ex_office_post_code)) ? $appInfo->ex_office_post_code : '' }}
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
                                            {{ (!empty($appInfo->ex_office_address)) ? $appInfo->ex_office_address:''  }}
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
                                            {{ (!empty($appInfo->ex_office_telephone_no)) ? $appInfo->ex_office_telephone_no : '' }}
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
                                            {{ (!empty($appInfo->ex_office_mobile_no)) ? $appInfo->ex_office_mobile_no:''  }}
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
                                            {{ (!empty($appInfo->ex_office_fax_no))? $appInfo->ex_office_fax_no:''  }}
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
                                            {{ (!empty($appInfo->ex_office_email)) ? $appInfo->ex_office_email:''  }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Activities in Bangladesh
                            </legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Activities in Bangladesh through the proposed branch/liaison/representative
                                            office (Max. 250 characters)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->activities_in_bd)) ? $appInfo->activities_in_bd : '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>Financial Statements:</strong>
                            </div>
                            <div class="panel-body">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">
                                        <span>Comprehensive income for the Period</span>
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Start date</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->comprehensive_income_start_date)) ? date("d-M-Y", strtotime($appInfo->comprehensive_income_start_date)) : '' }}
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
                                                    {{ (!empty($appInfo->comprehensive_income_end_date)) ? date("d-M-Y", strtotime($appInfo->comprehensive_income_end_date)) : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">Desired duration</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->comprehensive_income_duration)) ? $appInfo->comprehensive_income_duration : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>

                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Investment</legend>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Total Revenue
                                                </td>
                                                <td>
                                                    {{$appInfo->total_revenue}}
                                                </td>

                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>


                                                <td class="width_14_percent">
                                                    Total Expense
                                                </td>
                                                <td>
                                                    {{$appInfo->total_expense}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                              BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="width_14_percent">
                                                    Total Comprehensive Income
                                                </td>
                                                <td>
                                                    {{$appInfo->total_comprehensive_income}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{--    end total revenue--}}

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Fixed Assets
                                                </td>
                                                <td>
                                                    {{$appInfo->fixed_assets}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                               BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td class="width_14_percent">
                                                    Current Assets
                                                </td>
                                                <td>
                                                    {{$appInfo->current_assets}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                               BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    {{--    end fixed assets--}}
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Bank Balance
                                                </td>
                                                <td>
                                                    {{$appInfo->bank_balance}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td>
                                                    Cash Balance
                                                </td>
                                                <td>
                                                    {{$appInfo->cash_balance}}
                                                </td>

                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                               BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{--    end bank balance --}}
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Fixed Liabilities
                                                </td>
                                                <td>
                                                    {{$appInfo->fixed_liabilities}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                               BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td>
                                                    Current Liabilities
                                                </td>
                                                <td>
                                                    {{$appInfo->current_liabilities}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                               BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{--    end fixed liabilites --}}

                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Equility
                                                </td>

                                                <td>
                                                    {{$appInfo->equility}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    {{--    end equility --}}
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered">
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Accumulated Profit/Loss
                                                </td>
                                                <td>
                                                    {{$appInfo->acc_profit_loss}}
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                               BDT
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    {{--    end accumulated profit/loss --}}
                                </fieldset>

                            </div>
                        </div>

                        {{--Manpower of the organization--}}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Proposed organizational set up of the Office with expatriate and local man power ratio
                            </legend>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th colspan="3">Local (a)</th>
                                        <th colspan="3">Foreign (b)</th>
                                        <th>Grand Total</th>
                                        <th colspan="2">Ratio</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Executive</td>
                                        <td>Supporting staff</td>
                                        <td>Total</td>
                                        <td>Executive</td>
                                        <td>Supporting staff</td>
                                        <td>Total</td>
                                        <td>(a+b)</td>
                                        <td>Local</td>
                                        <td>Foreign</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ (!empty($appInfo->local_executive)) ? $appInfo->local_executive : '' }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->local_stuff)) ? $appInfo->local_stuff : '' }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->local_total)) ? $appInfo->local_total : '' }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->foreign_executive)) ? $appInfo->foreign_executive : '' }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->foreign_stuff)) ? $appInfo->foreign_stuff : '' }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->foreign_total)) ? $appInfo->foreign_total : '' }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->manpower_total)) ? $appInfo->manpower_total : ''  }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->manpower_local_ratio)) ? $appInfo->manpower_local_ratio : ''  }}
                                        </td>
                                        <td>
                                            {{ (!empty($appInfo->manpower_foreign_ratio)) ? $appInfo->manpower_foreign_ratio : ''  }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Establishment expenses and operational expenses of the office (in US Dollar)
                            </legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">(a) Estimated initial expenses</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->est_initial_expenses)) ? $appInfo->est_initial_expenses:''  }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-6">
                                                    <span class="v_label">(b) Estimated monthly expenses</span>
                                                    <span class="pull-right">&#58;</span>
                                                </div>
                                                <div class="col-md-7 col-xs-6">
                                                    {{ (!empty($appInfo->est_monthly_expenses)) ? $appInfo->est_monthly_expenses:''  }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>

                {{--Necessary documents to be attached--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Necessary documents to be attached here (Only PDF file)</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered table-hover ">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th colspan="6">Required attachments</th>
                                <th colspan="2">
                                    <a class="btn btn-xs btn-primary" target="_blank" href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id)) }}"><i class="fa fa-link" aria-hidden="true"></i> Open all</a>
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
                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                   title="{{$row->doc_name}}">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
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
                                    <legend class="scheduler-border">
                                        Authorized person of the organization
                                    </legend>
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
                                                                 src="{{ (!empty($appInfo->auth_image) ? url('uploads/waiver/'.$appInfo->auth_image) : url('assets/images/photo_default.png')) }}"
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
