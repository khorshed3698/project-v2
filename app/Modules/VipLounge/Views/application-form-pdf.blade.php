<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="UTF-8">
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
                        Application for VIP Lounge
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for VIP Lounge</div>
                    <div class="panel-body">
                        <table aria-label="Detailed Report Data Table" width="100%">
                            <tr>
                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                            </tr>
                            <tr>
                                <td style="padding: 5px 5px 5px 15px;">Tracking no. :  <span>{{ $appInfo->tracking_no  }}</span></td>
                                <td style="padding: 5px;">Date of Submission: <span>{{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 5px 5px 15px;">Current Status :<span>{{$appInfo->status_name}}</span></td>
                                <td style="padding: 5px;">Current Desk :
                                    <span>@if($appInfo->desk_id != 0) {{$appInfo->desk_name}} @else Applicant @endif</span>
                                </td>
                            </tr>
                        </table>

                        {{--basic company information section--}}
                        @include('ProcessPath::basic-company-info-pdf')

                        {{-- Basic Information --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Basic Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Basic Information" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Purpose for VIP/CIP longue :
                                                <span>{{ (!empty($appInfo->vip_longue_purpose_name)) ? $appInfo->vip_longue_purpose_name:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Company Name :
                                                <span> {!! \App\Libraries\CommonFunction::getCompanyNameById($appInfo->company_id) !!}</span>
                                            </td>
                                        </tr>
 
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    Reference Number:
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Reference number type:
                                                <span> {{ (!empty($appInfo->ref_no_type)) ? $appInfo->ref_no_type : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Reference number:
                                                <span> {{ (!empty($appInfo->reference_number))? $appInfo->reference_number :'' }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">Which Airport do you want to receive the VIP lounge in Bangladesh:</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Desired airport :
                                                <span>{{ (!empty($appInfo->airport_name)) ? $appInfo->airport_name : '' }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;">
                                                Purpose of visit :
                                                <span> {{ (!empty($appInfo->visa_purpose))? $appInfo->visa_purpose :'' }}</span>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Information of Expatriate / Investor / Employee --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Information of Expatriate / Investor / Employee</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Information of Expatriate" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">
                                                    General Information:
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Full Name :
                                                <span> {{ (!empty($appInfo->emp_name)) ? $appInfo->emp_name:''  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Investor Photo :
                                                @if(file_exists("uploads/".$appInfo->investor_photo))
                                                    <img class="img-thumbnail" width="100" height="auto" src="uploads/{{ $appInfo->investor_photo }}" alt="Applicant Photo" />
                                                @elseif(file_exists("users/upload/".$appInfo->investor_photo))
                                                    <img class="img-thumbnail" width="100" height="auto" src="users/upload/{{ $appInfo->investor_photo }}" alt="Applicant Photo" />
                                                @else
                                                    <img class="img-thumbnail" width="100" height="auto" src="assets/images/no_image.png" alt="Image not found" />
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Position / Designation :
                                                <span> {{ (!empty($appInfo->emp_designation)) ? $appInfo->emp_designation:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Brief job description :
                                                <span> {{ (!empty($appInfo->brief_job_description)) ? $appInfo->brief_job_description:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">Passport Information:</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Passport No. :
                                                <span> {{ (!empty($appInfo->emp_passport_no))?$appInfo->emp_passport_no:''  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Personal No. :
                                                <span> {{ (!empty($appInfo->emp_personal_no))?$appInfo->emp_personal_no:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Surname :
                                                <span> {{ (!empty($appInfo->emp_surname))?$appInfo->emp_surname:''  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Issuing authority :
                                                <span> {{ (!empty($appInfo->place_of_issue))?$appInfo->place_of_issue:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Given Name:
                                                <span> {{ (!empty($appInfo->emp_given_name))?$appInfo->emp_given_name:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of Birth :
                                                <span>{{ !empty($appInfo->emp_date_of_birth) ? date('d-M-Y', strtotime($appInfo->emp_date_of_birth)) : '' }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Place of Birth :
                                                <span> {{ (!empty($appInfo->emp_place_of_birth)) ? $appInfo->emp_place_of_birth:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of issue :
                                                <span>{{ !empty($appInfo->pass_issue_date) ? date('d-M-Y', strtotime($appInfo->pass_issue_date)) : '' }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Date of expiry :
                                                <span>{{ !empty($appInfo->pass_expiry_date) ? date('d-M-Y', strtotime($appInfo->pass_expiry_date)) : '' }} </span>
                                            </td>
                                        </tr>
                                        {{-- Office address end --}}
                                    </table>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <strong class="text-info">
                                            Spouse/child Information: 
                                        </strong>
                                        <label class="col-md-12 text-left"></label>
                                        <table aria-label="Detailed Report Spouse/child Information" id="productionCostTbl"
                                               class="table table-striped table-bordered dt-responsive" cellspacing="0"
                                               width="100%">
                                            <thead class="alert alert-info">
                                                <tr>
                                                    <th valign="top" class="text-center valigh-middle">Spouse/child</th>

                                                    <th valign="top" class="text-center valigh-middle">Name</th>

                                                    <th valign="top" class="text-center valigh-middle">Passport/Personal No.</th>

                                                    <th valign="top" class="text-center valigh-middle">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($spouse_child_info)>0)
                                                    @foreach($spouse_child_info as $spouse_child)
                                                        <tr>
                                                            <td>{{ (!empty($spouse_child->spouse_child_type)) ? $spouse_child->spouse_child_type:''  }}</td>
                                                            <td>{{ (!empty($spouse_child->spouse_child_name)) ? $spouse_child->spouse_child_name:''  }}</td>
                                                            <td>{{ (!empty($spouse_child->spouse_child_passport_per_no)) ? $spouse_child->spouse_child_passport_per_no:''  }}</td>
                                                            <td>{{ (!empty($spouse_child->spouse_child_remarks)) ? $spouse_child->spouse_child_remarks:''  }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <strong class="text-info">
                                            To whom, the p- pass will be issued: 
                                        </strong>
                                        <label class="col-md-12 text-left"></label>
                                        <table aria-label="Detailed Report Data Table" id="productionCostTbl"
                                               class="table table-striped table-bordered dt-responsive" cellspacing="0"
                                               width="100%">
                                            <thead class="alert alert-info">
                                            <tr>
                                                <th valign="top" class="text-center valigh-middle">Name</th>
                                                <th valign="top" class="text-center valigh-middle">Designation</th>
                                                <th valign="top" class="text-center valigh-middle">Mobile Number</th>
                                                <th valign="top" class="text-center valigh-middle">NID/Passport Number</th>
                                                <th valign="top" class="text-center valigh-middle">NID/Passport Copy</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($passport_holder_info)>0)
                                                @foreach($passport_holder_info as $passport_holder)
                                                    <tr>
                                                        <td>{{ (!empty($passport_holder->passport_holder_name)) ? $passport_holder->passport_holder_name:''  }}</td>
                                                        <td>{{ (!empty($passport_holder->passport_holder_designation)) ? $passport_holder->passport_holder_designation:''  }}</td>
                                                        <td>{{ (!empty($passport_holder->passport_holder_mobile)) ? $passport_holder->passport_holder_mobile:''  }}</td>
                                                        <td>{{ (!empty($passport_holder->passport_holder_passport_no)) ? $passport_holder->passport_holder_passport_no:''  }}</td>
                                                        <td>
                                                            @if(!empty($passport_holder->passport_holder_attachment))
                                                                <a target="_blank" rel="noopener"
                                                                class="btn btn-xs btn-primary documentUrl"
                                                                href="{{URL::to('/uploads/'.(!empty($passport_holder->passport_holder_attachment) ? $passport_holder->passport_holder_attachment : ''))}}"
                                                                title="{{$passport_holder->passport_holder_attachment}}">
                                                                    <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Flight Details --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Flight Details of the visiting expatriates</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Flight Details" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        
                                        @if ($appInfo->vip_longue_purpose_id  !== 2)
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival date & time :
                                                    <span>
                                                        {{ !empty($appInfo->arrival_date)  ? date('d-M-Y', strtotime($appInfo->arrival_date)) : '' }}
                                                        {{ date('H:i', strtotime($appInfo->arrival_time)) }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival Flight No. :
                                                    <span> {{ (!empty($appInfo->arrival_flight_no)) ? $appInfo->arrival_flight_no:''  }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($appInfo->vip_longue_purpose_id  !== 1)
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Departure date & time :
                                                    <span>
                                                        {{ !empty($appInfo->departure_date) ? date('d-M-Y', strtotime($appInfo->departure_date)) : '' }}&nbsp;
                                                        {{  date('H:i', strtotime($appInfo->departure_time)) }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Departure Flight No. :
                                                    <span> {{ (!empty($appInfo->departure_flight_no)) ? $appInfo->departure_flight_no:''  }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Paper's/ documents needed for recommendation of Visa   in favor of the expatriate(s) to be employed in branch/ liaison/ representative office and other private and public enterprise.</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered table-hover ">
                                        <thead>
                                        <tr>
                                            <th style="padding: 5px;">No.</th>
                                            <th colspan="6" style="padding: 5px;">Required Attachments</th>
                                            <th colspan="2" style="padding: 5px;">Attached PDF file
                                                <span>
                                                    <i title="Attached PDF file (Each File Maximum size 2MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>
                                                </span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @if(count($document) > 0)
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
                                        @else
                                            <tr class="text-center">
                                                <td colspan="9"> No required documents! </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Payment Info</div>
                            <div class="panel-body" style="padding: 5px">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="padding: 2px 5px;">Service Fee Payment</div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="row" style="padding: 5px">
                                                <table aria-label="Detailed Payment Info" class="table table-striped table-bordered">
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
                            </div>
                        </div>

                        {{-- Information about Declaration and undertaking --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Declaration and undertaking</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Declaration and undertaking" width="100%" cellpadding="10">
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
                                    <table aria-label="Detailed Report Data Table" width="100%" cellpadding="10">
                                        <tr>
                                            <th colspan="2" style="padding: 5px;" class="text-info">
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
                                            &nbsp; @if($appInfo->accept_terms == 1)
                                                <img src="assets/images/checked.png" width="10" height="10" alt="checked_icon"/>
                                            @else
                                                <img src="assets/images/unchecked.png" width="10" height="10" alt="unchecked_icon"/>
                                            @endif
                                            <label for="acceptTerms-2" class="col-md-11 text-left required-star form-control">
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
