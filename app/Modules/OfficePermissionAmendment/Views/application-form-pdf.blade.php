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
                        <img src="assets/images/bida_logo.png" style="width: 100px" alt="bida_logo"/><br/>
                        <br>
                        Bangladesh Investment Development Authority (BIDA)<br/>
                        Application for Office Permission Amendment
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Office Permission Amendment</div>
                    <div class="panel-body">
                        <table aria-label="Detailed Report Application for Office Permission Amendment" width="100%">
                            <tr>
                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
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
                        <div class="panel panel-info">
                            <div class="panel-heading">Basic Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Basic Information" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Did you receive your Office Permission approval from the online OSS? :
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

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Effective date of the previous office permission :
                                                <span>{{ (!empty($metingInformation->date_of_office_permission) ? date('d-M-Y', strtotime($metingInformation->date_of_office_permission)) : '') }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Office Type :
                                                <span>{{ (!empty($appInfo->office_type)) ? $appInfo->office_type :'' }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Name of the Local company:
                                                <span> {{ (!empty($appInfo->local_company_name)) ? $appInfo->local_company_name : ''  }}</span>
                                            </td>
                                        </tr>

                                        {{--Local address--}}
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Local address of the principal company: (Bangladesh only)
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Division:
                                                <span> {{ (!empty($appInfo->office_division_id)) ? $divisions[$appInfo->office_division_id] : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                District:
                                                <span> {{ (!empty($appInfo->office_district_id)) ? $district_eng[$appInfo->office_district_id] : ''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Police Station:
                                                <span> {{ (!empty($appInfo->office_thana_id)) ? $thana_eng[$appInfo->office_thana_id] : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                Post Office:
                                                <span> {{ (!empty($appInfo->office_post_office)) ? $appInfo->office_post_office : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Post Code:
                                                <span> {{ (!empty($appInfo->office_post_code)) ? $appInfo->office_post_code : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                House, Flat/ Apartment, Road:
                                                <span> {{ (!empty($appInfo->office_address)) ? $appInfo->office_address : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Telephone No:
                                                <span> {{ (!empty($appInfo->office_telephone_no)) ? $appInfo->office_telephone_no : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                Mobile No:
                                                <span> {{ (!empty($appInfo->office_mobile_no)) ? $appInfo->office_mobile_no : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Fax No:
                                                <span> {{ (!empty($appInfo->office_fax_no)) ? $appInfo->office_fax_no : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                Email:
                                                <span> {{ (!empty($appInfo->office_email)) ? $appInfo->office_email : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Activities in Bangladesh
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">
                                                Activities in Bangladesh through the proposed branch/ liaison/ representative office :
                                                <span> {{ (!empty($appInfo->activities_in_bd)) ? $appInfo->activities_in_bd : ''  }}</span>
                                            </td>
                                        </tr>
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
                                        <table aria-label="Detailed Report Meeting Information" width="100%" cellpadding="10">
                                            <tr>
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
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

                        {{--basic company information section--}}
                        @include('ProcessPath::basic-company-info-pdf')

                        <div class="panel panel-info">
                            <div class="panel-heading">Amendment Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Amendment Information" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Name of the Local company:
                                                <span> {{ (!empty($appInfo->n_local_company_name)) ? $appInfo->n_local_company_name : ''  }}</span>
                                            </td>
                                        </tr>

                                        {{--Local address--}}
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Local address of the principal company: (Bangladesh only)
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Division:
                                                <span> {{ (!empty($appInfo->n_office_division_id)) ? $divisions[$appInfo->n_office_division_id] : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                District:
                                                <span> {{ (!empty($appInfo->n_office_district_id)) ? $district_eng[$appInfo->n_office_district_id] : ''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Police Station:
                                                <span> {{ (!empty($appInfo->n_office_thana_id)) ? $thana_eng[$appInfo->n_office_thana_id] : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                Post Office:
                                                <span> {{ (!empty($appInfo->n_office_post_office)) ? $appInfo->n_office_post_office : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Post Code:
                                                <span> {{ (!empty($appInfo->n_office_post_code)) ? $appInfo->n_office_post_code : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                House, Flat/ Apartment, Road:
                                                <span> {{ (!empty($appInfo->n_office_address)) ? $appInfo->n_office_address : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Telephone No:
                                                <span> {{ (!empty($appInfo->n_office_telephone_no)) ? $appInfo->n_office_telephone_no : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                Mobile No:
                                                <span> {{ (!empty($appInfo->n_office_mobile_no)) ? $appInfo->n_office_mobile_no : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px">
                                                Fax No:
                                                <span> {{ (!empty($appInfo->n_office_fax_no)) ? $appInfo->n_office_fax_no : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px">
                                                Email:
                                                <span> {{ (!empty($appInfo->n_office_email)) ? $appInfo->n_office_email : ''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Activities in Bangladesh
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">
                                                Activities in Bangladesh through the proposed branch/ liaison/ representative office :
                                                <span> {{ (!empty($appInfo->n_activities_in_bd)) ? $appInfo->n_activities_in_bd : ''  }}</span>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--Necessary documents to be attached--}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Necessary documents to be attached here (Only PDF file)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Necessary documents to be attached" class="table table-striped table-bordered table-hover ">
                                        <thead>
                                        <tr>
                                            <th style="padding: 5px;">No.</th>
                                            <th colspan="6" style="padding: 5px;">Required attachments</th>
                                            <th colspan="2" style="padding: 5px;">Attached PDF file</th>
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
                                                <table aria-label="Detailed Report Service Fee Payment" class="table table-striped table-bordered">
                                                    <tr>
                                                        {{-- <th aria-hidden="true"  scope="col"></th> --}}
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
                                                    <table aria-label="Detailed Report Government Fee Payment" class="table table-striped table-bordered">
                                                        <tr>
                                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
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
                                    <table aria-label="Detailed Report Declaration and undertaking" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
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
                                    <table aria-label="Detailed Report Data Table" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">Authorized Personnel
                                                    of the organization: </strong>
                                            </td>
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
                                                statement is given.
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
