<?php
$accessMode = ACL::getAccsessRight('NewConnectionWZPDCL');
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

    @media screen and (max-width: 768px) {
        .form-group {
            margin: 0px !important;
        }

        .col-xs-6 {
            margin-bottom: 10px !important;
        }

    }
</style>
<section class="content" id="applicationForm">

    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">
                        Application For New Connection (WZPDCL)
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
                    <li class="highttext"><strong>WZPDCL Tracking no. : {{$appInfo->wzpdcl_tracking_no}}</strong></li>
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
{{--                    <li><strong>Current Desk:</strong>--}}
{{--                        {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}--}}
{{--                    </li>--}}
                </ol>

                @if($appInfo->status_id == 6)
                    <div class="alert alert-danger">
                        <strong>{{$appInfo->reject_or_shortfall_comment}}</strong>
                    </div>
                @endif
                @if(isset($demandInfo))
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <strong>Demand Note </strong>

                        </div>
                        <div class="panel-body">
                            @if($appInfo->demand_note_report !=null && $appInfo->demand_note_report !='')
                            <div class="col-md-3">
                                <a class="btn btn-md btn-info"
                                   href="{{$appInfo->demand_note_report}}"
                                   role="button" target="_blank">
                                    <i class="far fa-chart-bar"></i>
                                    Demand  Report
                                </a>
                            </div>
                            @endif
                            @if($appInfo->estimation_report !=null && $appInfo->estimation_report !='')
                            <div class="col-md-3">
                                <a class="btn btn-md btn-info"
                                   href="{{$appInfo->estimation_report}}"
                                   role="button" target="_blank">
                                    <i class="far fa-chart-bar"></i>
                                    Estimation Report
                                </a>
                            </div>
                            @endif
                        </div>

                        @if($appInfo->demand_submit != 1)
                            <div class="panel-footer">
                                <div class="pull-right">
                                    <a class="btn btn-md btn-primary"
                                       href="/new-connection-wzpdcl/view/additional-payment/{{ Encryption::encodeId($appInfo->id)}}"
                                       role="button">
                                        <i class="far fa-money-bill-alt"></i>
                                        Demand Fee Pay
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        @endif
                    </div>
                @endif
                @include('SonaliPaymentStackHolder::payment-information')
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Personal Info</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Applicant's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->applicant_name) ?  $appData->applicant_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Name (in Bengali)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->name_in_bengali) ? $appData->name_in_bengali : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Father's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->father_name) ?  $appData->father_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Mother's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mother_name) ? $appData->mother_name : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Spouse Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->spouse_name) ?  $appData->spouse_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Gender</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->applicant_gender) ? explode('@',$appData->applicant_gender)[0] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">National ID </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->national_id) ?  $appData->national_id : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Passport</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->applicant_passport) ? $appData->applicant_passport : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Mobile</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->applicant_mobile) ?  $appData->applicant_mobile : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Date of Birth </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->applicant_dob) ? $appData->applicant_dob : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Signature</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_signature)}}"
                                           title="{{$appData->validate_field_signature}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Photo</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_photo)}}"
                                           title="{{$appData->validate_field_photo}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Mailing Address</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">House/Plot/Dag No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_house_no) ?  $appData->mail_house_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">LANE/Road No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_road_no) ? $appData->mail_road_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Section</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_section) ?  $appData->mail_section : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Block</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_block) ? $appData->mail_block : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">District</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_district) ?  explode('@',$appData->mail_district)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Post Code</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_post_code) ? $appData->mail_post_code : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Thana</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_thana) ?  explode('@',$appData->mail_thana)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mailing_email) ? $appData->mailing_email : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Telephone</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->mail_telephone) ?  $appData->mail_telephone : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Connection Address</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">House/Plot/Dag No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_house_no) ?  $appData->conn_house_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">LANE/Road No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_road_no) ? $appData->conn_road_no : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Section</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_section) ?  $appData->conn_section : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Block</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_block) ? $appData->conn_block : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">District</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_district) ?  explode('@',$appData->conn_district)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Post Code</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_post_code) ? $appData->conn_post_code : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Thana</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_thana) ?  explode('@',$appData->conn_thana)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_email) ? $appData->conn_email : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Telephone</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_telephone) ?  $appData->conn_telephone : ''}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Mobile</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_mobile) ?  $appData->conn_mobile : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Zone</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_zone) ?  explode('@',$appData->conn_zone)[1] : ''}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Division</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_divison) ?  explode('@',$appData->conn_divison)[2] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Area</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-6">
                                        {{!empty($appData->conn_area) ?  explode('@',$appData->conn_area)[1] : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Description of Connection</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Connection Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class=" col-md-7 col-xs-6">
                                        {{!empty($appData->connection_type) ?  explode('@',$appData->connection_type)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-6">
                                        <span class="v_label">Phase</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5 col-xs-6">
                                        {{!empty($appData->phase) ? explode('@',$appData->phase)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Category </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class=" col-md-7 col-xs-6">
                                        {{!empty($appData->category) ?  explode('@',$appData->category)[2].' ('. explode('@',$appData->category)[1].')' : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-6">
                                        <span class="v_label">No. of Meter</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->no_of_meter) ? $appData->no_of_meter : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Organization/Shop Name </span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class=" col-md-7 col-xs-6">
                                        {{!empty($appData->org_or_shop_name) ?  $appData->org_or_shop_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-6">
                                        <span class="v_label">Demand Load per Meter in Kilowatt</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5 col-xs-6">
                                        {{!empty($appData->demand_load) ? $appData->demand_load : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</section>
