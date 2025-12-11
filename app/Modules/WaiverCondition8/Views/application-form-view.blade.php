<?php
$accessMode = ACL::getAccsessRight('WaiverCondition7');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
<style>
    .row > .col-md-5, .row > .col-md-7, .row > .col-md-3, .row > .col-md-9, .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    .float-left{
        float: left;
    }
    .margin_left_110{
        margin-left: 110px !important;
    }
    .margin_left_10{
        margin-left: 10px !important;
    }
    .margin_left_40{
        margin-left: 40px !important;
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

                    @if(!in_array($appInfo->status_id,[-1,5,6]))
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm" title="Download Approval Copy" id="html2pdf">
                            <i class="fa fa-download"></i> Application Download as PDF
                        </a>
                    @endif

                    @if(in_array($appInfo->status_id,[5,6]))
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
                                        <a target="_blank" rel="noopener" class="documentUrl btn btn-xs btn-primary" href="{{ URL::to('/uploads/'. $appInfo->conditional_approved_file) }}">
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

                {{-- Memo Information -- Memo Info show if application is Approved or Rejected --}}
                @if(in_array($appInfo->status_id,[6, 25]) && Auth::user()->user_type == '5x505' || in_array(Auth::user()->user_type, ['1x101','2x202', '4x404'])) 
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong>Memo Information</strong></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Memo Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->memo_no)) ? $appInfo->memo_no : '' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Memo Date</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->memo_date)) ? $appInfo->memo_date :''  }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Memo Attachment</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        @if(!empty($appInfo->memo_attachment))
                                            <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
                                                href="{{URL::to((!empty($appInfo->memo_attachment) ? $appInfo->memo_attachment : ''))}}"
                                                title="{{$appInfo->memo_no}}">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @else
                                            No file found
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                

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
                                    <a href="{{$ref_app_url}}" target="_blank" rel="noopener">
                                        <span class="label label-success label_tracking_no">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                    </a>
                                    &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-xs-6">
                                    <span class="v_label">Approved Date</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-6">
                                    {{ (!empty($appInfo->ref_app_approve_date) ? date("d-M-Y", strtotime($appInfo->ref_app_approve_date)) : '')  }}
                                </div>
                            </div>
                        <br>
                    </div>
                </div>

                {{-- Office Type --}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Office Info</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Approved office permission reference no.</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ref_office_app_tracking_no)) ? $appInfo->ref_office_app_tracking_no : '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Office Permission Approved date</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{ (!empty($appInfo->ref_office_app_approved_date) ? date("d-M-Y", strtotime($appInfo->ref_office_app_approved_date)) : '')  }}
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                        <table class="table table-striped table-bordered" aria-label="Detailed Investment Report">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Total Revenue
                                                </td>
                                                <td>
                                                    {{$appInfo->total_revenue}}
                                                </td>

                                                <td>
                                                    <table aria-label="Detailed Report Data Table">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                                    <table aria-label="Detailed Report Data Table">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                                    <table aria-label="Detailed Total Comprehensive Income Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Fixed Assets
                                                </td>
                                                <td>
                                                    {{$appInfo->fixed_assets}}
                                                </td>
                                                <td>
                                                    <table aria-label="Detailed Fixed Assets Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                                    <table aria-label="Detailed Current Assets Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Bank Balance
                                                </td>
                                                <td>
                                                    {{$appInfo->bank_balance}}
                                                </td>
                                                <td>
                                                    <table aria-label="Detailed Bank Balance Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                                    <table aria-label="Detailed Cash Balance Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                            <thead>
                                                <tr>
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Fixed Liabilities
                                                </td>
                                                <td>
                                                    {{$appInfo->fixed_liabilities}}
                                                </td>
                                                <td>
                                                    <table aria-label="Detailed Fixed Liabilities Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                                    <table aria-label="Detailed Current Liabilities Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="width_14_percent">
                                                    Equility
                                                </td>

                                                <td>
                                                    {{$appInfo->equility}}
                                                </td>
                                                <td>
                                                    <table aria-label="Detailed Report Data Table">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                        <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table">
                                            <thead>
                                                <tr class="d-none">
                                                    <th aria-hidden="true" scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>

                                                <td class="width_14_percent">
                                                    Accumulated  {{$appInfo->profit_loss}}
                                                </td>
                                                <td>
                                                    {{$appInfo->acc_profit_loss}}
                                                </td>
                                                <td>
                                                    <table aria-label="Detailed Accumulated Report">
                                                        <tr>
                                                            <th aria-hidden="true" scope="col"></th>
                                                        </tr>
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
                                <table class="table table-striped table-bordered" aria-label="Detailed Manpower of the organization Report" width="100%">
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
                        <strong>Necessary documents for waiver condition 7</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered table-hover" aria-label="Detailed Necessary documents Report">
                            <thead>
                            <tr>
                                <th width="10%">No.</th>
                                <th width="80%">Required attachments</th>
                                <th width="10%">
                                    <a class="btn btn-xs btn-primary" target="_blank" rel="noopener" href="{{ url('process/open-attachment/'.Encryption::encodeId($appInfo->process_type_id).'/'.Encryption::encodeId($appInfo->id)) }}"><i class="fa fa-link" aria-hidden="true"></i> Open all</a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @if(count($waiver7Doc) > 0)
                                @foreach($waiver7Doc as $row)
                                    <tr>
                                        <td>
                                            <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                        </td>
                                        <td>{!!  $row->doc_name !!}</td>
                                        <td>
                                            @if(!empty($row->doc_file_path))
                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
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
                </div>

                {{--Necessary documents to be attached--}}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Necessary documents to be attached here (Only PDF file)</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered table-hover" aria-label="Detailed Necessary documents Report">
                            <thead>
                            <tr>
                                <th width="10%">No.</th>
                                <th width="80%">Required attachments</th>
                                <th width="10%">
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
                                        <td>{!!  $row->doc_name !!}</td>
                                        <td>
                                            @if(!empty($row->doc_file_path))
                                                <a target="_blank" rel="noopener" class="btn btn-xs btn-primary"
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
                                    <td> No required documents!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                </div>
                </div>

                {{-- Information about the organization --}}

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
                                                                 src="{{ (!empty($appInfo->auth_image) ? url('users/upload/'.$appInfo->auth_image) : url('assets/images/photo_default.png')) }}"
                                                                 alt="User Photo" width="100px">
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

<script src="{{ asset("vendor/html2pdf/html2pdf.bundle.js") }}"></script>

<script>
    var is_generate_pdf = false;
    document.getElementById("html2pdf").addEventListener("click", function(e) {
        if (!is_generate_pdf) {
            $('#html2pdf').children().removeClass('fa-download').addClass('fa-spinner fa-pulse');
            generatePDF();
        }
    });

    function generatePDF(){
        var element = $('#applicationForm').html();
        var downloadTime = 'Download time: ' + moment(new Date()).format('DD-MMM-YYYY h:mm a');
        var opt = {
            margin:       [0.80,0.50,0.80,0.50], //top, left, bottom, right
            // filename:     'myfile.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
            enableLinks:  true,
        };

        var html = '<div style="margin-top: 60px">' + element + '</div>';

        html2pdf().from(html).set(opt).toPdf().get('pdf').then(function (pdf) {
            var pageCount = pdf.internal.getNumberOfPages();

            pdf.setPage(1);
            pageWidth = pdf.internal.pageSize.getWidth();
            pageHeight = pdf.internal.pageSize.getHeight();

            var image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKIAAAA2CAYAAABEBUJOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAFjFJREFUeNrsXQlcVFXb/88MM8POiGzK4uAGuAEmSoZ7Si6fYGpuFZKWWhZimi0aaraYmVqZfpoab5mZqViZZZq4g6UgaioqIqAgm6zDDLPc99yZMzri7IDyvu88/u5vLvdsz73P/z7bOfcI2MhGNrKRjWxkIxvZyEY2spGNbGQj/cRpro4XfrvW60DOhaElNZXd+Bxue5lC4SXgctVl9SoVhHZ2xXJGlePp7HY+ukPX40umvJJrE4cNiE1Cy3ds8vgu6+QUO+CZcHHnyACRB/fo5bNoI2qN2IiBGNOr3926FbXVyC0twrn8HKRfv4SsG1ezK6WSneH+HZKTZ75z2SYaGxAtpvh1y/zT866+3d1XHDcpcohDdPfesBcIkHzkV2TcuIrO3r4oq6lCgIcPnusXDS6H+0AfDMPg5JXz+C7tIHM0O2tfB3fvpbtf/yjdJqL/DeI1pnHq2XRhpgezUOTs+v0nE2c+/uqwsfzgtgGQyOqwN/MkaqR18HRxw983stGnYxf8dDYNI3r0gcCOf7eP/LLb+JVcb+PmjqC27TAiNJIzOvyJTpeK8qdJQnw6T4h/9kT6nv01NlHZNKJemvJ5Uter5cXfvTliYo/YXv3V17LyruLIxUy4ObkgunsE9mScwO8EZOcL85C64FMs2rkJG6cveKCv4ooyvPnDBvi28kCQjz+e7ffU3f7e2rGxTKaQTz/45qqUxt5swKRth5rgmR0mRyb7HuZtm1RhYryp5CfORH/JpJ+vG7QLIz+rrOCN9bNvmMufBc+N5SXMjKpjrB3TzppGQz5MjK0H882ehGXOrg5OKCgrxo70Qwht1xEnrv0DEoDAi2jC+H7D8TMB49xhY3HoYgYYPX1N/nIpXOwdkHnzOj57LgEDlifC38MbA0LC0SOgI3YnLGudtHPzrurFM99PS1r3LpfDYRrxTAc2gVy0fVQQAa0hD36xkbpiM8Y8rOeaqCl4JfyxLy/LY2oj+mB5mWPBs7FKYXAtbdB/6SvxXX3FP26ducjZm5jTz3/bgTlbP8eEx4fgRkkh+geFYvrAUfiFmObko/vQw1cMF74Q/xQVoG+nbqgkQYouJcXGwd3JFSOJyb5ENOek3oNQeKcMSTs24nZludqMfzhhBuelgaMW9l40fUOtTMpFyyBWQElEUBkt2OLFkuMQawnIIX4IL2+MtYxaJNSoJS9P6NOx61ern31V7Vuu2rcd/YJD0c2/g1rj9QsOw5nrl/H6trVgzXUw8flKSJBSUS+FmJjdFwaMVJttXWL9Qi6Xi7gnokmgcgCTIgfDSWgPFQloSivvYOXPW4mvKcE00nb+yMnTB7w3+7MWJuwwarpaMrFgyqAm31KKsRD4VpHZpnnEx/Oj2rb2+tdHE2dw6xVyrNi7DX6tPLGFaL0N097A3K1fwN/dExumv3EvEibBSu8QCZQXLkNZUICavWkax9TZCTw/P/C6BsEuvAcWxjwPB4EQId7+IP4grhbfwriIAdidcRzl1RVYu38XXhoSg2cISMtqq1/h8fm56UnrPmlBgp5DzXRuCwajiGrHQYTPzGYCl4j0H0v6T2kWIL761QqPqxUl29dMmS1giIf2XkoyimqrIFcp0crRBcezz2H5hJlII/4hS6rycsi+3Q7Zj3vB1MmMR0sOQvCf7Afl5HF4cYjm5RPY2aGaaMG0KxcQ6O0LCQF0/IYPse2VdzGL1LlSlP+R20dz0/e/+enRFiToJDaT1cI1IwvG3QQs4eYEFVSDiqzQoBYD0SzTfDLvyhcrJ85q60hM5qZDP6ELMachHm1IQCIiGqoKpVV3IK2XoV/HrpB+uw1VY+Ig/WaXSRCqtSapU//zAVRNmQXJso/BVFdjLNGGUUE98Mu85XiBRNAnci8ToM9A3IaPkF9ahGXjpvMYDif5XwdSHJtYUGz0uqTBsZoNTB5SIPQwiPUVt5hZN86K/q0yzyaBSEzyUAKMCSEk6NhyZB/2XTiNiPbBYKPlzPwcNu+HGOIPOtfLUT17PurWbAbHxYoIXsVAtucPAsgZ8Lxdqr7Ezrqs3r8Tu197D9vT/kRnH3+kX7sI9oVYFPNc4KepP7/dxEJiUymLGxyJ5HogTY2YEvDDJDY9w9Ee5O9w9pq5YCHabmAzvVwia3xRo0BUqVQcqVK+PGHYWMjkctwqL4FEJsWKfT+gWFKNz56djaeJ9lKVlqL6xTlQ/H1OY+87VYH/eCl4nSzPQ6sKS1D90jwoMjLh5SrC+qlzsXjXZni7tUIn77ZIOX0MxUQD9yPRee/A4LkJm1a0aW6JUzOW3JLVHOv3kWOQBWYxwYRZFpuZO2wSTco1rg3njRjfe1A4G0gczT6rNr8q4heufX4OREJHvLX9/6GSSFCT8BaUuTc1IAyqgl1IOfjdSyHoVQyujxT8qBIykvnpP0YiRc3cJHiUV6r9w17iIEjI2JGBIRjaPQJrD+zW+K5Dn3ZIzbmY+JBk7WaiPLOFYDLeTFci1kRKx5iJNWUdYpsUiOVSyazn+j6pBqCnkyuulhZiTvR4FJTdxoGLZ7Dq2ddQ98lnUGbr8OWgJEjSTNhwXOphPzoX/C5l4DiqLGKMqZGgdsFSuBMzrGSU6rnoID8xpvQdijqilW/dKUU3//bo7hc47dg/GcLmlCxN6k41UW1PC9GMFdTXbaw/Zyxts8SUm2KpeTYYNS/f8ZVXsVz2lAMBwrLdycgngp/WfwSC2wRgR/qfeH/cdGKKT5NA4+B97RSZrQAJD/zIYnDsFfeiYyEBU41lU9vK6wWQfv0doiePxbHsLHV0/uEvW9Vg3JtxHC8OjsGo8L7u7+zcNIpU39kEcozT4zu5URAaix4raFDTUoh1I8yZDRmgj2/64g00ovlTzAh44iyxEgY14g9Z6aOiu0fw2JUyfh7e8Cb+2qlr/6jni7v4BpJDDOmXm/W2VVxxheynAOLw3ZvK5npLAYGOeWZNtRkz3bKtu9BaqULsY/3gRFwEP3cvpJw5pjbVLA3v3gfldbWjm0iAU2kaRveYYwYIBzXVvG5T+YsWRNCWaso99F5NBUYDm0Qj2nG5Mex0HUvXigqIeVShuKYSW156S31NfvwEFOevPIjsVnLwI0rAC6i+zy8URN2CoC8XjIynNt0cezmYenKu5KrPVaUOqE/3hKrI4YH0jvRf2+Hw2kzklBRieNdecCeBiwPPDn/lXCQRfAgJWoJGnZLXcx34AtVDljkrjEQLE8QPkzdTYAgzoimN9cvSYRP9szNOYnOT/AaB2MO/Q4SQL8DPRPuMDo2Eh2srtPfxu1te/9ufetsJhtwC173OgP5VgeNwDyu6plt50wmqYge9zep/PwSHV2diUEg4Wjm7Yvqmj4l2tMf43prn1blNgPvSrV92IqcPc0GtOufYwmdTrCVDGrFCZwFFCrUYpvoxy2XRa5rTLp4VuTk5t6mXy1FSVYGFKcn4/GAKZm5egd2nNItFFGey9Jvl8yKN1rPIGeRCmeNCTLmBlE5xOVQFeWoQstTayQXnbubiTN5VDRC9/XDgxuXghyws1oxfJ2/9FupT/VcQ9ZFFJrSh1vybckfMTuPoBeKuEwd8/dw9IeDzUSWrw+Q+g8CoVJg9bBzGEC3EEHCqiu/oB+IlN0i/7wBlvot5ecNiR8j+9IWqXGAcq1evq39vk6CpZ2AQegZ0QHZhPnKLb8G/tRd4SqXvI5KdFpDi/xIsGouWGy5ZM5WzDDP3ueg1zel52e7xHTUKhp1LXrX/R2yOn8f6jRrwlJYaT73IST0zlg1Kf2kHVSExx2akGFVlZerpvfWH9yKQBCxPdo/AzdLbEHu1hbIwD3ckNa2bQAj63nIRTCd21XO40MxutBQaaOb9WhKosFmFGDOCHYvNs14g3pTV8fhcjXmN6/cUBoeEqSNnjjbMlSmMdspxVoDnV2MiTyiA6pbGJ+T5S8BxkxOzbjhnzMjk8Pfwwftjpz1QxvJapVTwmkB4ifoWkdKc2CET0XOYtStPmsG8mpvDy23QTmwCXNbMtAwwB4h6TbOv0EHJrqzREgsAjm6uRWh80Q5TZQdFtnG3ieMoV4OP5cAutAz88FKj6RyOkG+wjOXVlWenbOZ0SKKZD70lkLm+2WELtKHVgY85PrReIPZp17msQiox3MjDw3T8ke9sYmQGgqjbJHJWgde2Vp3CMTYNyG1teMwKaR1xIZzLmlm45kTHYY8agVSrTTWzeoq1wUUTReHGgRgbOfhmQXmJYe3kKgLXq5VJraj3uoSvDlAYOU8NQOHoG7RTwoyX4WVjvE6GLUZ+WTGUPN7NFuBzPWoQan1Vc6L4VN3UE23bXC9SjFVA7NslvLKytqbwPgDV3Z8btOsZahyIBlw2RiJQjyrP8IDsgB85uWeP7TpW6WfSyx1c3wCDvGTfLsBQcdCl5hIuOeaYkTMDzFts0FwgjKV+rLlgWvIQzLLZ5tmgs5eVf+20UqUaxaORsuwH9kVTQRgzAhyROwRD+qH+t1TjGpHBPb9PSfrhqYhvWK9J1SjYWRYu5Gc9IBxcoAGcu1RvX/whUZp+iC8o25UC5dUbcHxz7t3ynOKblaufS7jyQdycxj4wdil9Y9qffZhRMeHV2i8aU/QEZTHNza+xdI9BINYp6g9l5GaP6tU+WJ1Mlh9LJ5FuFYSTxmvAEfU4uD4eUBUZSOUoOVDedgTPR0K1mB04DgrIz7VWJ65Vd2jUTPDJ9BaQSLseHCc90TiXA+HTo+g5D4qL2VCcyoL8r7/Aj4iAXKFgd5M48gim98zxuVoisVo73kLXI9zYNCb9ftvUIgijnxAYXPQQEdDp11+zNDt+cP0C4DAjDi5fryOajILFjg/7aZOMm+cKIZQlTlCRX/lpT8gvtILirAiKcyKobtLpPAIf5W16Lnww8BUM6w+eOJCCWwnHxNlwXDRXDUKW2L115CrV3hYg4NQWOufcEIQPLNCgZt2Q6aww475SGxuwGATiphlvXzqefe4MMc8a3PXqCY7QnphWp3tZnNEjYdcjyHBa5XRr1P/uC+kuMRSXXSBP89TPBNGG6nilwYocjqsTHF6boROx8IjmdAG/d8Q9FXTmeP3kHpE7W4CQE/9DQJhpYdrJJMho0GMqqyCigLcMiCyVS6rX7zub1kDN6fwSU+m09C1w3PSnaphaOxKckCEUxtd7yc94Qpnngvq/PYl5Vt41yU7vzgXX0/P+MXWI/QD/aHbWjvnjp5U+YiHHt3BtyJrEQCM8Gl321YRuSYxVQHx/9PPfbj68t5BdHa0lVWEB6r5Yj/o/9ms68PWF88olRFPaW/2UlMRXVBY6EbPdSrN4luDWMfFF8Ado9tRR5lxB3ZcbID9y5L52aw/sZrr5+K98lOaYapmvWzAAWf4M7klDZ2HEjTS7LB1ujHk2OkUS/VhU3RNLX16641Tqumf6DFJfU1y6TCLXfcQsDyG3SPxbvgB2oT3g/OVy1M5LgqrUigwGCWzUc87qKIgHxwWzSXQ+6l4xO+YPvwATGRIk9SUm2g7XiwuxL+vUD38v3ZBhIXAaa97OUjOUasYSsFwzxsw1ME5qI/izZBMmsZGxKsxd5sZObRJQm+TZ0BpFk2uki+6U8catTTr1U8L7PbXLsOSpqeAPfDDIYhdDSD78lGiuv6ySMq+9HxyT5sOuS5cHzbfOmAz59/z6D2rsOJwuX898Jx82+o8ns7alG7/qnVCRq1v6hhfmm/WRkvxkGqQbvyHRcbZZTHD9fWA/ZSzRgiPV0bgpWn9wD7ae/GPGsUVrN9hE+D9GUUtfmbHy1+2M2aRSMfKMDEaydgNT9fLrTMX/TWDuDBypPthz9hpbxtZh65pLRy9nMeELp2215h5Yf0ib4dc9t7CPRu+x2FI2baLPYFVT8szmFK35wN6ijTp7v/vS6sSnnkmY+PgQg3VulhWrV3NP7z8cHX38jfbHfqZqLxCirLoSe84cw3jih7rYG95F5EJBDqZtWnFiVt/ooVOHjZFY8eCnkh92bnINOQ4RXyVQ67NQUIqpzybW8bkqtL4WXVDArtANbOCXQbcO7S+sof+nUydMZ5xc9jq7Mpqd7dApE2l9KZ0+dcdnzzNpW7GBunf5g2b6b402sNL5Ui+B/TDfQB/s82I/xB9Ex6vQ8R0b3q82ob1EW66PF0N+q0VAlNTLOFGLZ62bM/yZGc9FReuto1Aq8cb36zAwOAw/ZRxHn/Yh8PXwhriVp3pDTjYFUy2rQ15FKb4/eRCRgcHEGvPx9bHfcHThFwYZOpObjdnJq/4K8w4Yum7W25WN0AIZ1Dk/Sx8uC8wwmgdMoA8ySUeI8fShHqIAmUrrsKkI9rPNOFqPbb+bni+hfeVSYbPBQxgLfB2tytbZQstyKQ/xFDDJlC8RfWmS6Lm2zSBabzWNRLV5vBs67Q7T/KCI9sfyo95ShYIwg46tratvvFTK/yA6rm6f2nq696uNnlNpmfZl0223RN+aTYv2R3QUCJlTyzbO+uyPXR+zO4Jpk9269HtWGpQEbewmnf6tvfHCwJHIu30LC3ZsxD+3C5B8Yj8mbvwApXfK1GnBEWGROHPjCh5r1xlpV87rT2SdPorpmz85MDiwS6NASIl9gLFUMwygwk+B/sUCa3TeYFZDxTfIr4noIaZHshZ0tE4yFcYaA9GxtiyM9pdAhTiA9hem1cq62kw7T0z35RHTrUbEDdppQaHlUzeCD6P3vEYnof3AeFr+G0S52j5DG9zv4QYpHO3zEjVoF2Zx+kZvAy6Pxc8Cp2XCrKyCnPWfTJjp3M7z3vYzLg7OmNR7MCqltXB3dEb/Za9hfK/+6OIXiOKKchRWliOsbSAy869hWNfH2JkRlNZWIcjLF9vS/8Tjnbrd7atWWofFu7cwBy9mfLptxsI3g/3EiiZwjXJ1QKHVcGH0wcfA8OoVkZ6P7yuoIJJ1BHrYSr5SqJZLpFpWu1e3FuAJZPxMHRfBUPrmcIOXoWGqRlsvjGrQhu3EOhpURO9bny8dpid/2E6nT5GJD7Gs14j3ZS8Xfr7VgWcXPuazd//8eO829f8kwFL/4FBEduqKW8RXjOjQBa8MicHFojx08PDBxVs3ENDai/iGUnB5PGK+Q1FUfQfzop+Br7sXng5/QpMGYhjsOHUIw1e+ce1c/rXojPe+mtdEINSCTwucRK2ZoeZijU55cgMtFq9jwlNpnm41FYSYCnEP/c3UaZ/cYEzoK6NaZw3tW6t5RRSgWh7ZsjF0vCU6Ppm2T912qQ2ORB1fVctfhZ52uuMtpuVa867b35oG97uagjCV3tMYatbH6OGleWjwBwnjIpe8fGH5L1uZojul6ui2TiZV/y7cvp45mX2OOXLprPrvGqmEWfTjV3ejYLZMqVSq69eSsm+O/c4MW/56yWOLXpz/08mD9rCRLY9oCdUrFNzYlQueyq0ofbGHX/vhg7v0FA4N6Yl23m0NDsLa+FziP6bl/INDFzOZjBvZaRwOd/NbQ8dufTpqWJ1NPDYgNop+PLrfZeXBXdHlUskwIZfXrYNX2/ZCgdCLz+Gqx5MzKkZWLyu+VnwrR6ZSnncVOpwYFRT6R9Lkl2/aRGIjG9nIRjaykY1s9Mjp3wIMAKmroILpKWZSAAAAAElFTkSuQmCC";
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
            var url = pdf.output('bloburl');
            $('#html2pdf').children().removeClass('fa-spinner fa-pulse').addClass('fa-download');
            $('#html2pdf').attr({href: url, target: "_blank"});
            is_generate_pdf = true;
            window.open(url, '_blank');
        });
    }

  /*  function openModal(btn) {
        var this_action = btn.getAttribute('data-action');

        if(this_action != ''){
            $('#IRCModal .load_modal').html('');
            $.get(this_action, function(data, success) {
                if(success === 'success'){
                    $('#IRCModal .load_modal').html(data);
                }else{
                    $('#IRCModal .load_modal').html('Unknown Error!');
                }
                $('#IRCModal').modal('show', {backdrop: 'static'});
            });
        }
    }*/
</script>
