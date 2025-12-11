<!DOCTYPE html>
<html lang="en">
<head>
    <title>Application for Work Permit Cancellation</title>
    <meta charset="UTF-8">
</head>
<body>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <img src="assets/images/bida_logo.png" style="width: 100px"/ alt="bida_logo.png"><br/>
                        <br>
                        Bangladesh Investment Development Authority (BIDA)<br/>
                        Application for Work Permit Cancellation
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Work Permit Cancellation</div>
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

                        {{--basic company information section--}}
                        @include('ProcessPath::basic-company-info-pdf')

                        {{--meeting_info--}}
                        @if(!empty($metingInformation))
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Meeting Information</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table width="100%" cellpadding="10" aria-label="Detailed Meeting Info Report">
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

                        {{-- Basic Instructions --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Basic Instructions</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Basic Instructions">
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Did you receive last work-permit through online OSS? :
                                                <span> {{ (!empty($appInfo->last_work_permit)) ? ucfirst($appInfo->last_work_permit) : ''  }}</span>
                                            </td>
                                        </tr>

                                        @if($appInfo->last_work_permit == 'yes')
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Approved work permit no. :
                                                    <span> {{ (!empty($appInfo->ref_app_tracking_no)) ? $appInfo->ref_app_tracking_no : ''  }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($appInfo->last_work_permit == 'no')
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Manually approved work permit No. :
                                                    <span> {{ (!empty($appInfo->manually_approved_wp_no)) ? $appInfo->manually_approved_wp_no : ''  }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Name :
                                                <span> {{ (!empty($appInfo->applicant_name)) ? $appInfo->applicant_name : ''  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Nationality :
                                                <span> {{ (!empty($appInfo->applicant_nationality)) ? $nationality[$appInfo->applicant_nationality] :''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Passport Number :
                                                <span> {{ (!empty($appInfo->applicant_pass_no)) ? $appInfo->applicant_pass_no : ''  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Position/ Designation :
                                                <span> {{ (!empty($appInfo->applicant_position)) ? $appInfo->applicant_position : ''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Issue date of last Work Permit :
                                                <span>{{ (!empty($appInfo->issue_date_of_last_wp) ? date('d-M-Y', strtotime($appInfo->issue_date_of_last_wp)) : '') }} </span>
                                            </td>

                                            {{--Show only commercial department--}}
                                            @if($appInfo->department_id == 1 || $appInfo->department_id == '1')
                                                <td width="50%" style="padding: 5px;">
                                                    Expiry Date of Office Permission :
                                                    <span>{{ (!empty($appInfo->expiry_date_of_op) ? date('d-M-Y', strtotime($appInfo->expiry_date_of_op)) : '') }} </span>
                                                </td>
                                            @endif

                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Date Of cancellation :
                                                <span>{{ (!empty($appInfo->date_of_cancellation) ? date('d-M-Y', strtotime($appInfo->date_of_cancellation)) : '') }} </span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Remarks :
                                                <span style="font-size: 15px"> {{ (!empty($appInfo->applicant_remarks)) ? $appInfo->applicant_remarks : ''  }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Contact address of the expatriate in Bangladesh</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Contact address Report">
                                        <tbody>
                                            <tr>
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Division :
                                                    <span> {{ (!empty($appInfo->ex_office_division_id)) ? $divisions[$appInfo->ex_office_division_id]:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    District :
                                                    <span> {{ (!empty($appInfo->ex_office_district_id)) ? $districts[$appInfo->ex_office_district_id]:''  }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Police Station :
                                                    <span> {{ (!empty($appInfo->ex_office_thana_id)) ? $thana_eng[$appInfo->ex_office_thana_id]:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Post Office :
                                                    <span> {{ (!empty($appInfo->ex_office_post_office)) ? $appInfo->ex_office_post_office:''  }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Post Code :
                                                    <span>{{ (!empty($appInfo->ex_office_post_code)) ? $appInfo->ex_office_post_code:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    House,Flat/Apartment,Road :
                                                    <span> {{ (!empty($appInfo->ex_office_address)) ? $appInfo->ex_office_address:''  }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Telephone No :
                                                    <span>{{ (!empty($appInfo->ex_office_telephone_no)) ? $appInfo->ex_office_telephone_no:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Mobile No :
                                                    <span> {{ (!empty($appInfo->ex_office_mobile_no)) ? $appInfo->ex_office_mobile_no:''  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;">
                                                    Fax No :
                                                    <span>{{ (!empty($appInfo->ex_office_fax_no)) ? $appInfo->ex_office_fax_no:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Email :
                                                    <span>{{ (!empty($appInfo->ex_office_email)) ? $appInfo->ex_office_email:''  }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <br>
                        {{-- Compensation and Benefit --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Compensation and Benefit</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <br/>
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
                                                <span> {{ (!empty($appInfo->basic_payment_type_id))? $paymentMethods[$appInfo->basic_payment_type_id]:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->basic_local_amount))? $appInfo->basic_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->basic_local_currency_id))? $currencies[$appInfo->basic_local_currency_id]:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">b. Overseas allowance </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->overseas_payment_type_id))? $paymentMethods[$appInfo->overseas_payment_type_id]:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->overseas_local_amount))? $appInfo->overseas_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->overseas_local_currency_id))? $currencies[$appInfo->overseas_local_currency_id]:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">c. House rent</td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->house_payment_type_id))? $paymentMethods[$appInfo->house_payment_type_id]:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->house_local_amount))? $appInfo->house_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->house_local_currency_id))? $currencies[$appInfo->house_local_currency_id]:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">d. Conveyance</td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->conveyance_payment_type_id))? $paymentMethods[$appInfo->conveyance_payment_type_id]:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->conveyance_local_amount))? $appInfo->conveyance_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->conveyance_local_currency_id))? $currencies[$appInfo->conveyance_local_currency_id]:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">e. Medical allowance </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->medical_payment_type_id))? $paymentMethods[$appInfo->medical_payment_type_id]:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->medical_local_amount))? $appInfo->medical_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->medical_local_currency_id))? $currencies[$appInfo->medical_local_currency_id]:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">f. Entertainment allowance </td>
                                            <td style="padding: 5px;">

                                                <span> {{ (!empty($appInfo->ent_payment_type_id))? $paymentMethods[$appInfo->ent_payment_type_id]:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->ent_local_amount))? $appInfo->ent_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->ent_local_currency_id))? $currencies[$appInfo->ent_local_currency_id]:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">g. Annual Bonus </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->bonus_payment_type_id))? $paymentMethods[$appInfo->bonus_payment_type_id]:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->bonus_local_amount))? $appInfo->bonus_local_amount:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->bonus_local_currency_id))? $currencies[$appInfo->bonus_local_currency_id]:''  }}</span>
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
                            </div>
                        </div>
                        {{--Necessary documents to be attached--}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Necessary documents to be attached here (Only PDF file)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" aria-label="Detailed Necessary documents Report">
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
                                    <div class="panel-heading" style="padding: 2px 5px;">Service & Government Fee Payment</div>
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
                                    <table width="100%" cellpadding="10" aria-label="Detailed Authorized Personnel Report">
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
