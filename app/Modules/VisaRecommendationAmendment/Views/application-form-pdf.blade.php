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
                        Application for Visa Recommendation Amendment
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Visa Recommendation Amendment</div>
                    <div class="panel-body">
                        <table aria-label="Detailed Report Data Table" width="100%">
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

                        {{--Basic Information--}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Basic Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Basic Information Report" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Did you receive your approval online OSS? :
                                                <span> {{ (!empty($appInfo->is_approval_online)) ? ucfirst($appInfo->is_approval_online) : ''  }}</span>
                                            </td>
                                        </tr>

                                        @if($appInfo->is_approval_online == 'yes')
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Approved visa recommendation reference no. :
                                                    <span> {{ (!empty($appInfo->ref_app_tracking_no)) ? $appInfo->ref_app_tracking_no : '' }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($appInfo->is_approval_online == 'no')
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Manually approved visa recommendation reference no. :
                                                    <span> {{ (!empty($appInfo->manually_approved_vr_no)) ? $appInfo->manually_approved_vr_no : ''  }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Visa Type :
                                                <span>{{ (!empty($appInfo->app_type_name)) ? $appInfo->app_type_name:''  }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--Previous Information--}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Previous Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Previous Information" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Full Name :
                                                <span>{{ (!empty($appInfo->emp_name)) ? $appInfo->emp_name : '' }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Position/ Designation :
                                                <span>{{ (!empty($appInfo->emp_designation)) ? $appInfo->emp_designation:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Passport No. :
                                                <span>{{ (!empty($appInfo->emp_passport_no)) ? $appInfo->emp_passport_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Nationality :
                                                <span>{{ (!empty($appInfo->emp_nationality_id)) ? $nationality[$appInfo->emp_nationality_id] : '' }}</span>
                                            </td>
                                        </tr>


                                        @if($appInfo->app_type_id == 5)
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    <strong class="text-info">
                                                        Which Airport do you want to receive the visa recommendation in Bangladesh:
                                                    </strong>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Desired Airport :
                                                    <span> {{ (!empty($appInfo->airport_id))? $airports[$appInfo->airport_id]:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Purpose of visit :
                                                    <span> {{ (!empty($appInfo->visa_purpose_id))? ($appInfo->visa_purpose_id != 3 ? $travel_purpose[$appInfo->visa_purpose_id] : $appInfo->visa_purpose_others) :''  }}</span>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    <strong class="text-info">Bangladesh  embassy/high commission in abroad where recommendation letter to be sent:</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;">
                                                    Country :
                                                    <span>{{ (!empty($appInfo->mission_country_id))?$countries[$appInfo->mission_country_id]:''  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;">
                                                    Embassy/HighCommission :
                                                    <span> {{ (!empty($appInfo->high_commision_id))? $embassy_name->name.','.$embassy_name->address:''  }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($appInfo->app_type_id == 5)
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    <strong class="text-info">
                                                        Flight Details of the visiting expatriates
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival date & time :
                                                    <span>
                                                        {{ (!empty($appInfo->arrival_date) ? date('d-M-Y', strtotime($appInfo->arrival_date)) : '') }}
                                                        &nbsp;
                                                        {{ (!empty($appInfo->arrival_time) ? date('H:i', strtotime($appInfo->arrival_time)) : '') }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival Flight No. :
                                                    <span> {{ (!empty($appInfo->arrival_flight_no)) ? $appInfo->arrival_flight_no : ''  }} </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Departure date & time :

                                                    <span>
                                                        {{ (!empty($appInfo->departure_date) ? date('d-M-Y', strtotime($appInfo->departure_date)) : '') }}
                                                        &nbsp;
                                                        {{ (!empty($appInfo->departure_time) ? date('H:i', strtotime($appInfo->departure_time)) : '') }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Departure Flight No. :
                                                    <span> {{ (!empty($appInfo->departure_flight_no)) ? $appInfo->departure_flight_no : ''  }} </span>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">Amendment Information </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Amendment Information Report" width="100%" cellpadding="10">
                                        {{--<tr>--}}
                                            {{--<td colspan="2" style="padding: 5px;">--}}
                                                {{--Visa Type :--}}
                                                {{--<span>{{ (isset($appInfo->n_visa_type_name) ? $appInfo->n_visa_type_name : '')  }}</span>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        <tr>
                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Full Name :
                                                <span>{{ (isset($appInfo->n_emp_name) ? $appInfo->n_emp_name : '') }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Position/ Designation :
                                                <span>{{ (isset($appInfo->n_emp_designation) ? $appInfo->n_emp_designation : '')  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Passport No. :
                                                <span>{{ (isset($appInfo->n_emp_passport_no) ? $appInfo->n_emp_passport_no : '')  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Nationality :
                                                <span>{{ (isset($appInfo->n_emp_nationality_id) ? $nationality[$appInfo->n_emp_nationality_id] : '') }}</span>
                                            </td>
                                        </tr>

                                        @if(!empty($appInfo->app_type_id == 5))
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    <strong class="text-info">
                                                        Which Airport do you want to receive the visa recommendation in Bangladesh:
                                                    </strong>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Desired Airport :
                                                    <span>{{ (isset($appInfo->n_airport_id) ? $airports[$appInfo->n_airport_id] : '')  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Purpose of visit :
                                                    <span>{{ (isset($appInfo->n_visa_purpose_id) ? ($appInfo->n_visa_purpose_id != 3 ? $travel_purpose[$appInfo->n_visa_purpose_id] : $appInfo->n_visa_purpose_others) : '')  }}</span>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    <strong class="text-info">Bangladesh  embassy/high commission in abroad where recommendation letter to be sent:</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;">
                                                    Country :
                                                    <span>{{ (isset($appInfo->n_mission_country_id) ? $countries[$appInfo->n_mission_country_id] : '')  }}</span>
                                                </td>

                                                <td width="50%" style="padding: 5px;">
                                                    Embassy/HighCommission :
                                                    <span>{{ (isset($appInfo->n_high_commision_id) ? $new_embassy_name->name.','.$new_embassy_name->address : '')  }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if(!empty($appInfo->app_type_id == 5))
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    <strong class="text-info">
                                                        Flight Details of the visiting expatriates
                                                    </strong>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival date & time :

                                                    <span>
                                                        {{ (!empty($appInfo->n_arrival_date) ? date('d-M-Y', strtotime($appInfo->n_arrival_date)) : '') }}
                                                        &nbsp;
                                                        {{ (!empty($appInfo->n_arrival_time) ? date('H:i', strtotime($appInfo->n_arrival_time)) : '') }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival Flight No. :
                                                    <span> {{ (isset($appInfo->n_arrival_flight_no) ? $appInfo->n_arrival_flight_no : '') }} </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Departure date & time :
                                                    <span>
                                                        {{ (!empty($appInfo->n_departure_date) ? date('d-M-Y', strtotime($appInfo->n_departure_date)) : '') }}
                                                        &nbsp;
                                                        {{ (!empty($appInfo->n_departure_time) ? date('H:i', strtotime($appInfo->n_departure_time)) : '') }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Departure Flight No. :
                                                    <span> {{ (isset($appInfo->n_departure_flight_no) ? $appInfo->n_departure_flight_no : '') }} </span>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{--Necessary documents to be attached--}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Necessary documents to be attached here (Only PDF file)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Necessary documents Report" class="table table-striped table-bordered table-hover ">
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

                        <div class="panel panel-info">
                            <div class="panel-heading">Payment Info</div>
                            <div class="panel-body" style="padding: 5px">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="padding: 2px 5px;">Service & Government Fee Payment</div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="row" style="padding: 5px">
                                                <table aria-label="Detailed Payment Info Report" class="table table-striped table-bordered">
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
                                    <table aria-label="Detailed Declaration and undertaking Report" width="100%" cellpadding="10">
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
