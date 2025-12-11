<?php
$accessMode = ACL::getAccsessRight('SBaccount');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}

?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    .row > .col-md-5,
    .row > .col-md-7,
    .row > .col-md-3,
    .row > .col-md-9,
    .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    legend.scheduler-border {
        font-weight: normal !important;
    }

    .table {
        margin: 0;
    }

    .table > tbody > tr > td,
    .table > tbody > tr > th,
    .table > tfoot > tr > td,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > thead > tr > th {
        padding: 5px;
    }

    .mb5 {
        margin-bottom: 5px;
    }

    .mb0 {
        margin-bottom: 0;
    }
</style>
<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">
                        Application For Sonali Bank Online Account
                    </strong>
                </div>
                <div class="pull-right">
                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>
                </div>

            </div>

            <div class="panel-body">
                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    @if(!empty($appInfo->sb_account_no))
                        <li><strong>Account No. : </strong>{{ $appInfo->sb_account_no}}</li>
                    @endif
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                        <li>
                            <a href="{{ url($appInfo->certificate_link) }}"
                               class="btn show-in-view btn-xs btn-info"
                               title="Download Approval Letter" target="_blank"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                        </li>
                    @endif
                </ol>

                {{--Payment information--}}
                @include('SonaliPaymentStackHolder::payment-information')
                @if($appInfo->status_id == 6)
                    <div class="panel panel-danger">
                    <div class="panel-heading"><strong>Reject Reason</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <h4 style="text-align: center;">{{$appInfo->process_desc}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Bank Information</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Name of Bank</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->bank_name) ? $appData->bank_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label"> Name of District </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->bank_district) ? explode('@',$appData->bank_district)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6" >
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Name of Branch </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->bank_branch) ? explode('@',$appData->bank_branch)[1] : ''}}
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
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Title of Account (In Bangla)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->account_title_bn) ? $appData->account_title_bn : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Title of Account (In English: Block Letter) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->account_title_en) ? $appData->account_title_en : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Nature of Account: </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->ac_nature) ? explode('@',$appData->ac_nature)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Category: </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->customer_category) ? explode('@',$appData->customer_category)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Sub Category : </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->customer_sub_category) ? explode('@',$appData->customer_sub_category)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Currency </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->currency) ? explode('@',$appData->currency)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Method of Account Operation </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->account_operation) ? explode('@',$appData->account_operation)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Entity Type  </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->entity_type) ? explode('@',$appData->entity_type)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

{{--                <div class="panel panel-info">--}}
{{--                    <div class="panel-heading"><strong>Deposit Info</strong></div>--}}
{{--                    <div class="panel-body">--}}
{{--                        <div class="form-group">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label">Amount of Initial Deposit (In numbers) </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->ini_amount_deposit) ? $appData->ini_amount_deposit : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label"> Amount of Initial Deposit (In words) </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->ini_amount_deposit_world) ? $appData->ini_amount_deposit_world : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Organization Info</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label"> Name of the Organization (In English) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->organization_name_en) ? $appData->organization_name_en : ''}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Nature of Organization </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->nature_of_organization) ? explode('@',$appData->nature_of_organization)[1] : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Tax ID Number (E-TIN) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->tax_no) ? $appData->tax_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">VAT Registration Number/BIN </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->vat_reg_no) ? $appData->vat_reg_no : ''}}
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
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Trade License Number </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->tl_no) ? $appData->tl_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label"> Date of Trade License </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->tl_date) ? $appData->tl_date : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Expired  Date </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->expiry_date) ? $appData->expiry_date : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Issuing Authority </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->issue_authority) ? $appData->issue_authority : ''}}
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
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Registration number  </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->registration_no) ? $appData->registration_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label"> Registration Date </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->registration_date) ? $appData->registration_date : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Expiry Date </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->reg_expiry_date) ? $appData->reg_expiry_date : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Registration Country </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->registration_country) ? explode('@',$appData->registration_country)[1]: ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Registration Authority </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->registration_authority) ? $appData->registration_authority : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Registered Address </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->registration_address) ? $appData->registration_address : ''}}
                                    </div>
                                </div>
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label">Road/Village </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->registration_road) ? $appData->registration_road: ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label">Post Office </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->registration_post) ? $appData->registration_post : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label">Thana/PS </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->registration_dsp) ? $appData->registration_dsp: ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label">District/State/Provence</span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->registration_dsp) ? $appData->registration_dsp : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Office Address Info</strong></div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"> Office Address

                            </legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Country </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->office_country) ? explode('@',$appData->office_country)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Post Office </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->office_post) ? $appData->office_post : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Thana/PS </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->office_thana) ? $appData->office_thana : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">District/State/Provence</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->office_dsp) ? $appData->office_dsp : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Road/Village </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->office_road) ? $appData->office_road : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Phone/Mobile Number </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->office_phone) ? $appData->office_phone : ''}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Email </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->office_email) ? $appData->office_email : ''}}
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
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Type of Business </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->type_of_business) ? explode('@',$appData->type_of_business)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Nature of Business </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->nature_of_bus) ? explode('@',$appData->nature_of_bus)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Yearly Turnover </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->yearly_turnover) ? $appData->yearly_turnover : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Net assets of the organization </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->net_of_org) ? $appData->net_of_org : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Number of human resource </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->human_resource) ? $appData->human_resource : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Personal Information</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label">Name of account operating person (In Bangla) </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->account_oper_person_bn) ? $appData->account_oper_person_bn : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Name of account operating person (In English) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->account_oper_person_en) ? $appData->account_oper_person_en : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Profession (Details) </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->profession) ? $appData->profession : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Date of Birth </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->date_of_birth) ? $appData->date_of_birth : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Fathers Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->father_name) ? $appData->father_name : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Mothers Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->mothers_name) ? $appData->mothers_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Spouse Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->spouse_name) ? $appData->spouse_name : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Nationality </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->nationality_personal) ? explode('@',$appData->nationality_personal)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Gender </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->sex) ? explode('@',$appData->sex)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="col-md-5 col-xs-12">--}}
{{--                                        <span class="v_label">Resident Status  </span>--}}
{{--                                        <span class="pull-right">&#58;</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-7">--}}
{{--                                        {{!empty($appData->resident) ? $appData->resident : ''}}--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Relation with Organization </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->relation_with_org) ? $appData->relation_with_org : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Tax ID Number (E-TIN)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->tax_id_no) ? $appData->tax_id_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Occupation </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->occupation_code) ? explode('@',$appData->occupation_code)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Monthly Income </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->monthly_income) ? $appData->monthly_income : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-12">
                                        <span class="v_label">Source of Fund</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->source_of_fund) ? $appData->source_of_fund : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Address Info</strong></div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"> Present Address
                            </legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Country </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_country) ? explode('@',$appData->present_country)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Post Office </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_post) ? $appData->present_post : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Thana/PS </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_thana) ? $appData->present_thana : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">District/State/Provence</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_dsp) ? $appData->present_dsp : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Country </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_country) ? explode('@',$appData->present_country)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Phone/Mobile Number </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_phone) ? $appData->present_phone : ''}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Email </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_email) ? $appData->present_email : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border"> Permanent Address
                            </legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Country </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->permanent_country) ? explode('@',$appData->permanent_country)[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Post Office </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->permanent_post) ? $appData->permanent_post : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Thana/PS </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->permanent_thana) ? $appData->permanent_thana : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">District/State/Provence</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->permanent_dsp) ? $appData->permanent_dsp : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Road/Village </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->present_road) ? $appData->present_road : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Phone/Mobile Number </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->permanent_phone) ? $appData->permanent_phone : ''}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-7 col-xs-12">
                                            <span class="v_label">Email </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-5">
                                            {{!empty($appData->permanent_email) ? $appData->permanent_email : ''}}
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
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Identification Document</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->identification_doc) ? explode('@',$appData->identification_doc)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Country </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->identification_country) ? explode('@',$appData->identification_country)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Identification Document Number </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->identification_doc_no) ? $appData->identification_doc_no : ''}}
                                    </div>
                                </div>
                                @if(!empty($appData->identification_doc_date))
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Issue Date </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->identification_doc_date) ? $appData->identification_doc_date: ''}}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">

                                @if(!empty($appData->identification_doc_date_exp))
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-12">
                                            <span class="v_label">Issue Date </span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->identification_doc_date_exp) ? $appData->identification_doc_date_exp: ''}}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Others Info</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">The purpose of opening an account of a foreign company / institution (If applicable)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->purpose_account) ?$appData->purpose_account: ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-12">
                                        <span class="v_label">Name of the concerned regulatory authority </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->regulatory_authority) ? $appData->regulatory_authority : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @if(!empty($appData->dynamicDocumentsId))
                    <div class="form-group" style="">
                        <div class="row">
                            <div class=" col-md-12">
                                <table class="table table-bordered table-hover" id="loadDetails">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Document Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($appData->dynamicDocumentsId as $key => $value)
                                        <?php
                                        $dynamicDocuments = explode('@', $appData->dynamicDocumentsId[$key]);
                                        $dynamicDocumentsId = !empty($dynamicDocuments[0]) ? $dynamicDocuments[0] : '';
                                        $doc_name = 'doc_name_' . $dynamicDocumentsId;
                                        $doc_path = 'validate_field_' . $dynamicDocumentsId;
                                        ?>
                                        @if(!empty($appData->$doc_path))
                                           <tr>
                                            <td>{{$key+1}} .</td>
                                            <td>{{ (!empty($appData->$doc_name)) ? $appData->$doc_name : '' }}</td>

                                            <td>
                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                   href="{{URL::to('/uploads/'.$appData->$doc_path)}}"
                                                   title="{{$appData->$doc_name}}">
                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    Open File
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>
