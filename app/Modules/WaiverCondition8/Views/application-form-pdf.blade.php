<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <img src="assets/images/bida_logo.png" style="width: 100px" alt="bida_logo.png"/><br/>
                        <br>
                        Bangladesh Investment Development Authority (BIDA)<br/>
                        Application for Office Permission Extension
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Office Permission Extension</div>
                    <div class="panel-body">
                        <table width="100%" aria-label="Detailed Report Data Table">
                            <tr>
                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                            </tr>
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Tracking no. : <span>{{ $appInfo->tracking_no  }}</span></td>
                                <td style="padding: 5px;">Date of Submission : <span>{{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</span></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Current Status :  <span>{{$appInfo->status_name}}</span></td>
                                <td style="padding: 5px;">Current Desk :
                                    <span>@if($appInfo->desk_id != 0) {{$appInfo->desk_name}} @else Applicant @endif</span>
                                </td>
                            </tr>
                        </table>

                        {{--Basic Information--}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Basic Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Basic Information Report">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Did you receive your approval online? :
                                                <span> {{ (!empty($appInfo->is_approval_online)) ? ucfirst($appInfo->is_approval_online) : ''  }}</span>
                                            </td>
                                        </tr>

                                        @if($appInfo->is_approval_online == 'yes')
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Approved office permission reference no. :
                                                    <span> {{ (!empty($appInfo->ref_app_tracking_no)) ? $appInfo->ref_app_tracking_no : ''  }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($appInfo->is_approval_online == 'no')
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Manually approved office permission reference no. :
                                                    <span> {{ (!empty($appInfo->manually_approved_op_no)) ? $appInfo->manually_approved_op_no : ''  }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Desired Statement">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Desired Statement
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> Is outward remittance of any kind from Bangladesh sources will be allowed ?: {{ (!empty($appInfo->is_remittance_allowed)) ? ucfirst($appInfo->is_remittance_allowed) : ''  }}</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Start Date :
                                                <span>{{ (!empty($appInfo->desired_start_date)) ? $appInfo->desired_start_date : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                End Date:
                                                <span>{{ (!empty($appInfo->desired_end_date)) ? $appInfo->desired_end_date:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Extension Year :
                                                <span>{{ (!empty($appInfo->extension_year)) ? $appInfo->extension_year : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Payable Amount :
                                                <span>{{ (!empty($appInfo->duration_amount)) ? $appInfo->duration_amount : '' }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Report Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Inward remittance of last 1 year (Encashment)
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Last Remittance Year :
                                                <span>{{ (!empty($appInfo->last_remittance_year)) ? $appInfo->last_remittance_year : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Inward Remittance:
                                                <span>{{ (!empty($appInfo->inward_remittance)) ? $appInfo->inward_remittance:''  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--basic company information section--}}
                        @include('ProcessPath::basic-company-info-pdf')

                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Office Type</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Office Type Report">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Office Type :
                                                <span>{{ (!empty($appInfo->office_type)) ? $appInfo->office_type : '' }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--meeting_info--}}
                        @if(!empty($metingInformation))
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Meeting Information</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table width="100%" cellpadding="10" aria-label="Detailed Meeting Information Report">
                                            <tr>
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Meeting No. :
                                                    <span>{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>
                                                </td>
                                                <td style="padding: 5px;">Meeting Date :
                                                    <span>{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--meeting_info--}}

                        {{--Information about the principal company--}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Information about the principal company</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Information about the principal company">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">Company information: </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Name of the principal company :
                                                <span>{{ (!empty($appInfo->company_name)) ? $appInfo->company_name : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Country of Origin of Principal Office :
                                                <span>{{ (!empty($appInfo->c_origin_country_id)) ? $countries[$appInfo->c_origin_country_id]:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            {{--<td width="50%" style="padding: 5px;" >--}}
                                            {{--Country :--}}
                                            {{--<span>{{ (!empty($appInfo->c_country_id)) ? $countries[$appInfo->c_country_id] : '' }}</span>--}}
                                            {{--</td>--}}
                                            <td width="50%" style="padding: 5px;" >
                                                Type of the Organization :
                                                <span>{{ (!empty($appInfo->c_org_type)) ? $organizationTypes[$appInfo->c_org_type] : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Flat/ Apartment/ Floor No. :
                                                <span>{{ (!empty($appInfo->c_flat_apart_floor)) ? $appInfo->c_flat_apart_floor:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                House/ Plot/ Holding No :
                                                <span>{{ (!empty($appInfo->c_house_plot_holding)) ? $appInfo->c_house_plot_holding : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post / Zip Code. :
                                                <span>{{ (!empty($appInfo->c_post_zip_code)) ? $appInfo->c_post_zip_code:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Street Name/ Street No :
                                                <span>{{ (!empty($appInfo->c_street)) ? $appInfo->c_street : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Email :
                                                <span>{{ (!empty($appInfo->c_email)) ? $appInfo->c_email:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                City :
                                                <span>{{ (!empty($appInfo->c_city)) ? $appInfo->c_city : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No. :
                                                <span>{{ (!empty($appInfo->c_telephone)) ? $appInfo->c_telephone:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                State / Province :
                                                <span>{{ (!empty($appInfo->c_state_province)) ? $appInfo->c_state_province : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No. :
                                                <span>{{ (!empty($appInfo->c_fax)) ? $appInfo->c_fax:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Major Activities in Brief :
                                                <span>{{ (!empty($appInfo->c_major_activity_brief)) ? $appInfo->c_major_activity_brief:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <strong class="text-info">Capital of Principal Company: (in US $):
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                (i) Authorized capital :
                                                <span>{{ (!empty($appInfo->authorized_capital)) ? $appInfo->authorized_capital : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                (ii) Paid up capital :
                                                <span>{{ (!empty($appInfo->paid_up_capital)) ? $appInfo->paid_up_capital:''  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--Information about the proposed branch/liaison/representative office--}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Information about the proposed Branch/ Liaison/ Representative Office</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Information about the proposed Branch">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Name of the Local company :
                                                <span>{{ (!empty($appInfo->local_company_name)) ? $appInfo->local_company_name : '' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Name of the Local company(Bangla) :
                                                <span>{{ (!empty($appInfo->local_company_name_bn)) ? $appInfo->local_company_name_bn : '' }}</span>
                                            </td>
                                        </tr>

                                        {{--Local Address of the Principal Company: (Bangladesh only)--}}
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Local Address of the Principal Company: (Bangladesh only)
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Division :
                                                <span> {{ (!empty($appInfo->office_division_id))? $divisions[$appInfo->office_division_id]:''  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->office_district_id))? $district_eng[$appInfo->office_district_id]:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->office_thana_id))? $thana_eng[$appInfo->office_thana_id]:''  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Post Office :
                                                <span> {{ (!empty($appInfo->office_post_office))? $appInfo->office_post_office:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span> {{ (!empty($appInfo->office_post_code))?$appInfo->office_post_code:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                House, Flat/ Apartment, Road :
                                                <span> {{ (!empty($appInfo->office_address))? $appInfo->office_address:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No. :
                                                <span> {{ (!empty($appInfo->office_telephone_no))? $appInfo->office_telephone_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No. :
                                                <span> {{ (!empty($appInfo->office_mobile_no))? $appInfo->office_mobile_no:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No. :
                                                <span> {{ (!empty($appInfo->office_fax_no))? $appInfo->office_fax_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Email :
                                                <span> {{ (!empty($appInfo->office_email))? $appInfo->office_email:''  }}</span>
                                            </td>
                                        </tr>

                                        {{--Activities in Bangladesh--}}
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Activities in Bangladesh
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Activities in Bangladesh through the proposed branch/liaison/representative office :
                                                <span>{{ (!empty($appInfo->activities_in_bd)) ? $appInfo->activities_in_bd:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Effective Date of First Office Permission :
                                                <span>{{ (!empty($appInfo->first_commencement_date)) ? date('d-M-Y', strtotime($appInfo->first_commencement_date)) : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Target date of operation of the proposed office :
                                                <span>{{ (!empty($appInfo->operation_target_date)) ? date('d-M-Y', strtotime($appInfo->operation_target_date)) :''  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered" aria-label="Detailed Report Data Table" width="100%">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td class="text-center" style="padding: 5px;" colspan="9">Proposed Organizational Set up of the Office with Expatriate and Local Man Power Ratio</td>
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
                                <br>
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Report Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Establishment Expenses and Operational Expenses of the Office (in US Dollar)
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                (a) Estimated Initial Expenses :
                                                <span>{{ (!empty($appInfo->est_initial_expenses)) ? $appInfo->est_initial_expenses : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                (b) Estimated Monthly Expenses :
                                                <span>{{ (!empty($appInfo->est_monthly_expenses)) ? $appInfo->est_monthly_expenses:''  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--Necessary documents to be attached--}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Necessary documents to be attached here (Only PDF file)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" aria-label="Detailed Necessary documents Report">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <th style="padding: 5px;" scope="col">No.</th>
                                                <th colspan="6" style="padding: 5px;" scope="col">Required attachments</th>
                                                <th colspan="2" style="padding: 5px;" scope="col">Attached PDF file</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($document as $row)
                                            <tr>
                                                <td style="padding: 5px;">
                                                    <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                </td>
                                                <td colspan="6" style="padding: 5px;">{!!  $row->doc_name !!}</td>
                                                <td colspan="2" style="padding: 5px;">
                                                    @if(!empty($row->doc_file_path))

                                                        <div class="save_file">
                                                            <a target="_blank" rel="noopener" title=""
                                                               href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ?
                                                               $row->doc_file_path : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                            </a>
                                                        </div>
                                                    @else
                                                        No file found
                                                    @endif
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Inforrmation--}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Payment Info</div>
                            <div class="panel-body" style="padding: 5px">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="padding: 2px 5px;">Service Fee Payment</div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="row" style="padding: 5px">
                                                <table class="table table-striped table-bordered" aria-label="Detailed Payment Info Report">
                                                    <tr>
                                                        {{-- <th aria-hidden="true" scope="col"></th> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>Contact name :
                                                            <span>{{ (!empty($appInfo->sfp_contact_name)) ? $appInfo->sfp_contact_name :''  }}</span>
                                                        </td>
                                                        <td>Contact email :
                                                            <span>{{ (!empty($appInfo->sfp_contact_email)) ? $appInfo->sfp_contact_email :''  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contact phone :
                                                            <span>{{ (!empty($appInfo->sfp_contact_phone)) ? $appInfo->sfp_contact_phone :''  }}</span>
                                                        </td>
                                                        <td>Contact address :
                                                            <span>{{ (!empty($appInfo->sfp_contact_address)) ? $appInfo->sfp_contact_address :''  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pay amount :
                                                            <span>{{ (!empty($appInfo->sfp_pay_amount)) ? $appInfo->sfp_pay_amount :''  }}</span>
                                                        </td>
                                                        <td>VAT on pay amount :
                                                            <span>{{ (!empty($appInfo->sfp_vat_on_pay_amount)) ? $appInfo->sfp_vat_on_pay_amount :''  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Transaction charge :
                                                            <span>{{ (!empty($appInfo->sfp_transaction_charge_amount)) ? $appInfo->sfp_transaction_charge_amount :''  }}</span>
                                                        </td>
                                                        <td>
                                                            VAT on transaction charge:
                                                            <span>{{ (!empty($appInfo->sfp_vat_on_transaction_charge)) ? $appInfo->sfp_vat_on_transaction_charge :''  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Amount :
                                                            <span>{{ (!empty($appInfo->sfp_total_amount)) ? $appInfo->sfp_total_amount : ''  }}</span>
                                                        </td>
                                                        <td>Payment Status :
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
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Payment Mode :
                                                            <span>{{ (!empty($appInfo->sfp_pay_mode_code)) ? $appInfo->sfp_pay_mode_code :''  }}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($appInfo->gf_payment_id != 0 && !empty($appInfo->gf_payment_id))
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="padding: 2px 5px;">Government Fee Payment</div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="row" style="padding: 5px">
                                                    <table class="table table-striped table-bordered" aria-label="Detailed Government Fee Payment Report">
                                                        <tr>
                                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                                        </tr>
                                                        <tr>
                                                            <td>Contact name :
                                                                <span>{{ (!empty($appInfo->gfp_contact_name)) ? $appInfo->gfp_contact_name :''  }}</span>
                                                            </td>
                                                            <td>Contact email :
                                                                <span>{{ (!empty($appInfo->gfp_contact_email)) ? $appInfo->gfp_contact_email :''  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Contact phone :
                                                                <span>{{ (!empty($appInfo->gfp_contact_phone)) ? $appInfo->gfp_contact_phone :''  }}</span>
                                                            </td>
                                                            <td>Contact address :
                                                                <span>{{ (!empty($appInfo->gfp_contact_address)) ? $appInfo->gfp_contact_address :''  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Pay amount :
                                                                <span>{{ (!empty($appInfo->gfp_pay_amount)) ? $appInfo->gfp_pay_amount :''  }}</span>
                                                            </td>
                                                            <td>VAT on pay amount :
                                                                <span>{{ (!empty($appInfo->gfp_vat_on_pay_amount)) ? $appInfo->gfp_vat_on_pay_amount :''  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Transaction charge :
                                                                <span>{{ (!empty($appInfo->gfp_transaction_charge_amount)) ? $appInfo->gfp_transaction_charge_amount :''  }}</span>
                                                            </td>
                                                            <td>
                                                                VAT on transaction charge:
                                                                <span>{{ (!empty($appInfo->gfp_vat_on_transaction_charge)) ? $appInfo->gfp_vat_on_transaction_charge :''  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Amount :
                                                                <span>{{ (!empty($appInfo->gfp_total_amount)) ? $appInfo->gfp_total_amount : ''  }}</span>
                                                            </td>
                                                            <td>Payment Status :
                                                                @if($appInfo->gfp_payment_status == 0)
                                                                    <span class="label label-warning">Pending</span>
                                                                @elseif($appInfo->gfp_payment_status == -1)
                                                                    <span class="label label-info">In-Progress</span>
                                                                @elseif($appInfo->gfp_payment_status == 1)
                                                                    <span class="label label-success">Paid</span>
                                                                @elseif($appInfo->gfp_payment_status == 2)
                                                                    <span class="label label-danger">-Exception</span>
                                                                @elseif($appInfo->gfp_payment_status == 3)
                                                                    <span class="label label-warning">Waiting for Payment Confirmation</span>
                                                                @else
                                                                    <span class="label label-warning">invalid status</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Payment Mode :
                                                                <span>{{ (!empty($appInfo->gfp_pay_mode_code)) ? $appInfo->gfp_pay_mode_code :''  }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Information about Declaration and undertaking --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Declaration and undertaking</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Declaration and undertaking Report">
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td width="" style="padding: 5px;">
                                                <p>a. I do hereby declare that the information given above is true to
                                                    the best of my knowledge and I shall be liable for any false
                                                    information/ statement given</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">
                                                <p>b. I do hereby undertake full responsibility of the expatriate for
                                                    whom visa recommendation is sought during their stay in
                                                    Bangladesh. </p>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table width="100%" cellpadding="10" aria-label="Detailed Report Data Table">
                                        <tr>
                                            <th colspan="2" style="padding: 5px;" class="text-info" scope="col">
                                                Authorized Personnel of the organization:
                                            </th>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Full Name :
                                                <span> {{ (!empty($appInfo->auth_full_name)) ? $appInfo->auth_full_name:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Designation :
                                                <span>{{ (!empty($appInfo->auth_designation)) ? $appInfo->auth_designation:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->auth_mobile_no)) ? $appInfo->auth_mobile_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Email address :
                                                <span>{{ (!empty($appInfo->auth_email)) ? $appInfo->auth_email:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Profile Picture :

                                                @if(file_exists("users/upload/".$appInfo->auth_image))
                                                    <img class="img-thumbnail" width="60" height="60"
                                                         src="users/upload/{{ $appInfo->auth_image }}"
                                                         alt="Applicant Photo"/>
                                                @else
                                                    <img class="img-thumbnail" width="60" height="60"
                                                         src="assets/images/no_image.png" alt="Image not found"/>
                                                @endif
                                            </td>
                                            <td colspan="3" style="padding: 5px;">
                                                Date :
                                                <?php echo date('F d,Y')?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            &nbsp; @if($appInfo->accept_terms == 1) <img src="assets/images/checked.png"
                                                                                         width="10" height="10" alt="checked_icon"/> @else
                                                <img src="assets/images/unchecked.png" width="10" height="10" alt="unchecked_icon"/> @endif
                                            <label for="acceptTerms-2"
                                                   class="col-md-11 text-left required-star form-control">
                                                <span>
                                                    I do here by declare that the information given above is true to the
                                                best of my knowledge and I shall be liable for any false information/
                                                system is given.
                                                </span>
                                            </label>
                                            <div class="clearfix"></div>
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
</section>
</body>
</html>
