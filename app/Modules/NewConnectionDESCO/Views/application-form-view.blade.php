<?php
$accessMode = ACL::getAccsessRight('NewConnectionDESCO');
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
                        Application For New Connection (DESCO)
                    </strong>
                </div>
                <div class="pull-right">

                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>


                    @if($appInfo->solar_status == 10)
                        <a class="btn btn-md btn-warning"
                           href="/new-connection-desco/view/solar-documents/{{ Encryption::encodeId($appInfo->id)}}">
                            <i class="far fa-money-bill-alt"></i>
                            Solar Shortfall Attachment
                        </a>
                    @endif

                    @if($appInfo->status_id == 34)
                        <a class="btn btn-md btn-danger" target="_blank"
                           href="/new-connection-desco/view/shortfall-document/{{ Encryption::encodeId($appInfo->id)}}"
                           role="button">
                            <i class="far fa-money-bill-alt"></i>
                            Shortfall

                        </a>
                    @endif
                </div>
            </div>

            <div class="panel-body">

                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    @if(!empty($appInfo->desco_tracking_no))
                        <li class="highttext"><strong>Desco Tracking no. : {{$appInfo->desco_tracking_no}}</strong></li>
                    @endif
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>

                </ol>

                @if(isset($demandInfo))
                    <div class="panel panel-primary">
                        <div class="panel-heading clearfix">
                            <strong>Demand Note </strong>

                        </div>
                        <div class="panel-body">
                            @if($appInfo->material_report !=null && $appInfo->material_report !='')
                                <div class="col-md-3">
                                    <a class="btn btn-md btn-info"
                                       href="{{$appInfo->material_report}}"
                                       role="button" target="_blank">
                                        <i class="far fa-money-bill-alt"></i>
                                        Material Report
                                    </a>
                                </div>
                            @endif
                            @if($appInfo->security_report !=null && $appInfo->security_report !='')
                                <div class="col-md-3">
                                    <a class="btn btn-md btn-info"
                                       href="{{$appInfo->security_report}}"
                                       role="button" target="_blank">
                                        <i class="far fa-money-bill-alt"></i>
                                        Security Report
                                    </a>
                                </div>
                            @endif
                            @if($appInfo->cmo_report_url !=null && $appInfo->cmo_report_url !='')
                                <div class="col-md-3">
                                    <a class="btn btn-md btn-info"
                                       target="_blank" role="button"
                                       href="{{ $appInfo->cmo_report_url}}">
                                        <i class="far fa-chart-bar "></i>
                                        CMO Report
                                    </a>
                                </div>
                            @endif
                        </div>


                        @if($appInfo->demand_submit != 1)
                            <div class="panel-footer">
                                <div class="pull-right">
                                    <a class="btn btn-md btn-primary"
                                       href="/new-connection-desco/view/additional-payment/{{ Encryption::encodeId($appInfo->id)}}"
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

                @if(!empty($solarDocs))
                    <div class="panel panel-info">
                        <div class="panel-heading"><strong>Solar Attachements</strong></div>
                        <div class="panel-body">
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class=" col-md-12">
                                        <table class="table table-bordered table-hover"
                                               id="loadDetails">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Solar Installation Date</th>
                                                <th>Solar Attachement</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($solarDocs as $key => $value)
                                                <tr>

                                                    <td>{{$key+1}} .</td>
                                                    <td>{{ (!empty($value->solarInstall_date)) ? $value->solarInstall_date : '' }}</td>
                                                    <td>{{ (!empty($value->solarAttachment_title)) ? $value->solarAttachment_title : '' }}</td>
                                                    <td>
                                                        @if(!empty($value->solarDocument_url))
                                                            <a target="_blank"
                                                               class="btn btn-xs btn-primary"
                                                               href="{{URL::to('/uploads/'.$value->solarDocument_url)}}"
                                                               title="{{$value->solarAttachment_title}}">
                                                                <i class="fa fa-file-pdf-o"
                                                                   aria-hidden="true"></i>
                                                                Open File
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group" style="margin: 15px;">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-4">
                            <span class="v_label">Application Type</span>
                            <span class="">&#58;</span>
                            {{!empty($appData->application_type) ? explode('@', $appData->application_type)[1] : ''}}

                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Details of Organization</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Authorized Person</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->authorized_person) ?  $appData->authorized_person : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-5">
                                        <span class="v_label">Designation of Authorized Person</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->designation_authorized) ? $appData->designation_authorized : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Organization Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->organization_name) ?  $appData->organization_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-7 col-xs-5">
                                        <span class="v_label">Designation of Authorized Person</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-5">
                                        {{!empty($appData->organization_type) ? explode('@', $appData->organization_type)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!empty($appData->ministry))
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-5">
                                            <span class="v_label">Ministry</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="datepickerDob col-md-7">
                                            {{!empty($appData->ministry) ? explode('@', $appData->ministry)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Description of Connection Place</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">House/Dag Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->house_dag_no) ?  $appData->house_dag_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Plot Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->plot_number) ? $appData->plot_number : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">AV/LANE/Road Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->av_lane_number) ?  $appData->av_lane_number : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Block Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->block_number) ? $appData->block_number : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Thana</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->thana) ? explode('@', $appData->thana)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Section</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->section) ? $appData->section : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Area</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->area) ? explode('@', $appData->area)[1] : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">S&D</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->snd) ? explode('@', $appData->snd)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Wiring Inspector</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->wiring_inspector) ? explode('@', $appData->wiring_inspector)[1] : ''}}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Post Office</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->post_office) ? $appData->post_office : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic Information</strong></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">NID</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->nid_number) ?  $appData->nid_number : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Date of Birth</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_dob) ? $appData->applicant_dob :''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Title of the connection</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->title_of_connection) ?  $appData->title_of_connection : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->applicant_name) ? $appData->applicant_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Father's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->father_name) ? $appData->father_name : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Mother's Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->mother_name) ? $appData->mother_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Spouse Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->spouse_name) ? $appData->spouse_name : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Gender</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        <?php
                                        $gender = explode('@', $appData->gender);
                                        $gender_name = !empty($gender[1]) ? $gender[1] : '';
                                        ?>
                                        {{ $gender_name }}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">BIN (Business Identification Number)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->bin) ? $appData->bin : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">E-TIN</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="datepickerDob col-md-7">
                                        {{!empty($appData->etin) ? $appData->etin : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Trade License No.</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{!empty($appData->trade_license) ? $appData->trade_license : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Photo</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('photo') ? 'has-error': ''}}">
                                        <a target="_blank" class="btn btn-xs btn-primary"
                                           href="{{URL::to('/uploads/'.$appData->validate_field_photo)}}"
                                           title="{{$appData->validate_field_photo}}">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            Open File
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-5">
                                        <span class="v_label">Signature</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('signature') ? 'has-error': ''}}">
                                        @if(!empty($appData->validate_field_signature))
                                            <a target="_blank" class="btn btn-xs btn-primary"
                                               href="{{URL::to('/uploads/'.$appData->validate_field_signature)}}"
                                               title="{{$appData->validate_field_signature}}">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                Open File
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Contact Information</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Mobile No</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                        {{!empty($appData->mobile) ? $appData->mobile : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_2') ? 'has-error': ''}}">
                                        {{!empty($appData->email) ? $appData->email : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Contact Address</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                        {{!empty($appData->contact_address) ? $appData->contact_address : ''}}
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Description of the Connection</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Connection Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                        <?php
                                        $conn_type = explode('@', $appData->conn_type);
                                        $conn_type_name = !empty($conn_type[1]) ? $conn_type[1] : '';
                                        ?>
                                        {{ $conn_type_name }}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Load (KW)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_2') ? 'has-error': ''}}">
                                        {{!empty($appData->load) ? $appData->load : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Phase</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                        <?php
                                        $phase = explode('@', $appData->phase);
                                        $phase_name = !empty($phase[1]) ? $phase[1] : '';
                                        ?>
                                        {{ $phase_name }}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Voltage</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_2') ? 'has-error': ''}}">
                                        <?php
                                        $voltage = explode('@', $appData->voltage);
                                        $voltage_name = !empty($voltage[1]) ? $voltage[1] : '';
                                        ?>
                                        {{ $voltage_name }}

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Tariff Category</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                        <?php
                                        $tariff_category = explode('@', $appData->tariff_category);
                                        $tariff_categorye_name = !empty($tariff_category[1]) ? $tariff_category[1] : '';
                                        ?>
                                        {{ $tariff_categorye_name }}

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Tariff</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_2') ? 'has-error': ''}}">
                                        <?php
                                        $tariff = explode('@', $appData->tariff);
                                        $tariff_name = !empty($tariff_category[1]) ? $tariff[1] : '';
                                        ?>
                                        {{ $tariff_name }}

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Tariff SubCategory</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_1') ? 'has-error': ''}}">
                                        {{!empty($appData->tariff_subcategory) ? explode('@', $appData->tariff_subcategory)[1] :''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-5 col-xs-6">
                                        <span class="v_label">Special Class</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 {{$errors->has('address_line_2') ? 'has-error': ''}}">
                                        {{!empty($appData->special_class) ? explode('@', $appData->special_class)[1] :''}}
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
                                <table class="table table-bordered table-hover"
                                       id="loadDetails">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Document Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($appData->dynamicDocumentsId as $key => $value)
                                        <tr>
                                            <?php
                                            $dynamicDocuments = explode('@', $appData->dynamicDocumentsId[$key]);
                                            $dynamicDocumentsId = !empty($dynamicDocuments[0]) ? $dynamicDocuments[0] : '';
                                            $doc_name = 'doc_name_' . $dynamicDocumentsId;
                                            $doc_path = 'validate_field_' . $dynamicDocumentsId;
                                            ?>
                                            <td>{{$key+1}} .</td>
                                            <td>{{ (!empty($appData->$doc_name)) ? $appData->$doc_name : '' }}</td>

                                            <td>
                                                @if(!empty($appData->$doc_path))
                                                    <a target="_blank"
                                                       class="btn btn-xs btn-primary"
                                                       href="{{URL::to('/uploads/'.$appData->$doc_path)}}"
                                                       title="{{$appData->$doc_name}}">
                                                        <i class="fa fa-file-pdf-o"
                                                           aria-hidden="true"></i>
                                                        Open File
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
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
