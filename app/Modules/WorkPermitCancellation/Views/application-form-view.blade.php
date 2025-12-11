<?php
$accessMode = ACL::getAccsessRight('WorkPermitCancellation');
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
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;"> Application for Work Permit Cancellation</strong>
                </div>
                <div class="pull-right">
                    @if ($viewMode == 'on' && isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link) && in_array(Auth::user()->user_type, ['1x101','2x202', '4x404', '5x505']))
                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-md btn-info" title="Download Approval Copy" target="_blank" rel="noopener">
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
                        <a href="/work-permit-cancellation/app-pdf/{{ Encryption::encodeId($appInfo->id)}}" target="_blank" class="btn btn-danger btn-md">
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
                    <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    <li><strong>Current Desk :</strong> {{ $appInfo->desk_id != 0 ? \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id) : 'Applicant' }}</li>
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

                @if((in_array($appInfo->status_id, [15, 16, 25]) && Auth::user()->user_type == '5x505' && $viewMode == 'on') || (in_array(Auth::user()->user_type, ['1x101', '2x202', '4x404']) && $viewMode == 'on'))
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Cancellation Effect Date</legend>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Start Date</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-6">
                                            {{ (!empty($appInfo->approved_effect_date) ? date('d-M-Y', strtotime($appInfo->approved_effect_date)) : '') }}
                                        </div>
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

                {{--Basic Information--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Basic Information</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-5 col-xs-9">
                                <span class="v_label">Did you receive last work-permit through online OSS?</span>
                                <span class="pull-right">&#58;</span>
                            </div>
                            <div class="col-md-7 col-xs-3">
                                <span> {{ (!empty($appInfo->last_work_permit)) ? ucfirst($appInfo->last_work_permit) : ''  }}</span>
                            </div>
                        </div>

                        @if($appInfo->last_work_permit == 'yes')
                            <div class="row">
                                <div class="col-md-5 col-xs-9">
                                    <span class="v_label">Please give your approved work permit reference No.</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-3">
                                    <a href="{{$data['ref_app_url']}}" target="_blank" rel="noopener">
                                        <span class="label label-success label_tracking_no">{{ (empty($appInfo->ref_app_tracking_no) ? '' : $appInfo->ref_app_tracking_no) }}</span>
                                    </a>
                                    &nbsp;
                                    &nbsp;{!! \App\Libraries\CommonFunction::getCertificateByTrackingNo($appInfo->ref_app_tracking_no) !!}
                                </div>
                            </div>
                        @endif

                        @if($appInfo->last_work_permit == 'no')
                            <div class="row">
                                <div class="col-md-5 col-xs-9">
                                    <span class="v_label">Please give your manually approved work permit reference No.</span>
                                    <span class="pull-right">&#58;</span>
                                </div>
                                <div class="col-md-7 col-xs-3">
                                    <span> {{ (!empty($appInfo->manually_approved_wp_no)) ? $appInfo->manually_approved_wp_no : ''  }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-9">
                                        <span class="v_label">Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-3">
                                        <span> {{ (!empty($appInfo->applicant_name)) ? $appInfo->applicant_name : ''  }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-9">
                                        <span class="v_label">Nationality</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-3">
                                        <span> {{ (!empty($appInfo->applicant_nationality_name)) ? $appInfo->applicant_nationality_name :''  }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-9">
                                        <span class="v_label">Passport Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-3">
                                        <span> {{ (!empty($appInfo->applicant_pass_no)) ? $appInfo->applicant_pass_no : ''  }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-9">
                                        <span class="v_label">Position/ Designation</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-3">
                                        <span> {{ (!empty($appInfo->applicant_position)) ? $appInfo->applicant_position : ''  }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-9">
                                        <span class="v_label">Issue date of last Work Permit</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-3">
                                        <span>{{ (!empty($appInfo->issue_date_of_last_wp) ? date('d-M-Y', strtotime($appInfo->issue_date_of_last_wp)) : '') }} </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-9">
                                        <span class="v_label">Date Of cancellation</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-3">
                                        <span>{{ (!empty($appInfo->date_of_cancellation) ? date('d-M-Y', strtotime($appInfo->date_of_cancellation)) : '') }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if($appInfo->department_id == 1 || $appInfo->department_id == '1')
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-9">
                                            <span class="v_label">Expiry Date of Office Permission</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7 col-xs-3">
                                            <span>{{ (!empty($appInfo->expiry_date_of_op) ? date('d-M-Y', strtotime($appInfo->expiry_date_of_op)) : '') }} </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5 col-xs-9">
                                        <span class="v_label">Remarks</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7 col-xs-3">
                                        <span> {{ (!empty($appInfo->applicant_remarks)) ? $appInfo->applicant_remarks : ''  }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Basic Information--}}
                <div class="panel panel-info">
                    <div class="panel-heading"><strong>Applicant Details</strong></div>
                    <div class="panel-body">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Contact address of the expatriate in Bangladesh:</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Division</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span> {{ (!empty($appInfo->ex_office_division_name)) ? $appInfo->ex_office_division_name :''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">District</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span> {{ (!empty($appInfo->ex_office_district_name)) ? $appInfo->ex_office_district_name :''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Police Station</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span> {{ (!empty($appInfo->ex_office_thana_name)) ? $appInfo->ex_office_thana_name :''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Post Office</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span> {{ (!empty($appInfo->ex_office_post_office)) ? $appInfo->ex_office_post_office:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Post Code</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span>{{ (!empty($appInfo->ex_office_post_code)) ? $appInfo->ex_office_post_code:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4 col-sm-5">
                                                <span class="v_label">House,Flat/Apartment,Road</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8 col-sm-7">
                                                <span> {{ (!empty($appInfo->ex_office_address)) ? $appInfo->ex_office_address:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Telephone No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span>{{ (!empty($appInfo->ex_office_telephone_no)) ? $appInfo->ex_office_telephone_no:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Mobile No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span> {{ (!empty($appInfo->ex_office_mobile_no)) ? $appInfo->ex_office_mobile_no:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Fax No</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-8">
                                                <span>{{ (!empty($appInfo->ex_office_fax_no)) ? $appInfo->ex_office_fax_no:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-4">
                                                <span class="v_label">Email</span>
                                                <span class="pull-right">&#58;</span>
                                            </div>
                                            <div class="col-md-7 col-xs-4">
                                                <span>{{ (!empty($appInfo->ex_office_email)) ? $appInfo->ex_office_email:''  }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Compensation and Benefit:</legend>
                            <div class="form-group">
                                {{-- Compensation and Benefit --}}
                                <div class="table-responsive">
                                    <table  class="table table-striped table-bordered" cellspacing="10" width="100%" aria-label="Detailed Compensation and Benefit Report">
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
                                                <span> {{ (!empty($appInfo->overseas_payment_type_name))? $appInfo->overseas_payment_type_name :''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->overseas_local_amount))? $appInfo->overseas_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->overseas_currency_code))? $appInfo->overseas_currency_code :''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">c. House rent</td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->house_payment_type_name))? $appInfo->house_payment_type_name :''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->house_local_amount))? $appInfo->house_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->house_currency_code))? $appInfo->house_currency_code :''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">d. Conveyance</td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->conveyance_payment_type_name))? $appInfo->conveyance_payment_type_name :''  }}</span>
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
                                                <span> {{ (!empty($appInfo->medical_payment_type_name))? $appInfo->medical_payment_type_name :''  }}</span>
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
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">h. Other fringe benefits (if any)</td>
                                            <td colspan="5" style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->other_benefits))? $appInfo->other_benefits:''  }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                {{--Necessary documents to be attached--}}
                            </div>
                        </fieldset>
                    </div>
                </div>



                {{--attachment--}}
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

