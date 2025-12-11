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
                        Application for Work Permit Extension
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Work Permit Extension</div>
                    <div class="panel-body">
                        <table width="100%" aria-label="Detailed Application for Work Permit Extension">
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
                                        <table width="100%" cellpadding="10" aria-label="Detailed Meeting Info">
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
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Basic Instructions</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Basic Instructions Report">
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
                                                Effective date of the first Work Permit :
                                                <span>{{ (!empty($appInfo->issue_date_of_first_wp) ? date('d-M-Y', strtotime($appInfo->issue_date_of_first_wp)) : '') }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Type of visa :
                                                <span> {{ (!empty($appInfo->work_permit_type)) ? $WP_visaTypes[$appInfo->work_permit_type] :''  }}</span>
                                            </td>
                                        </tr>

                                        {{--Show only commercial department--}}
                                        @if($appInfo->department_id == 1 || $appInfo->department_id == '1')
                                            <tr>
                                                <td colspan="2" style="padding: 5px;" >
                                                    Expiry Date of Office Permission :
                                                    <span>{{ (!empty($appInfo->expiry_date_of_op) ? date('d-M-Y', strtotime($appInfo->expiry_date_of_op)) : '') }} </span>
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- Desired Duration --}}
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">Desired duration for work permit</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Start Date :
                                                <span>{{ (!empty($appInfo->duration_start_date) ? date('d-M-Y', strtotime($appInfo->duration_start_date)) : '') }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                End Date :
                                                <span>{{ (!empty($appInfo->duration_end_date) ? date('d-M-Y', strtotime($appInfo->duration_end_date)) : '') }} </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;" >
                                                Desired Duration :
                                                <span> {{ (!empty($appInfo->desired_duration)) ? $appInfo->desired_duration :''  }}</span>
                                            </td>

                                            <td style="padding: 5px;" >
                                                Payable amount (BDT) :
                                                <span> {{ (!empty($appInfo->duration_amount)) ? $appInfo->duration_amount :''  }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Information of Expatriate / Investor / Employee --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Information of Expatriate/ Investor/ Employee</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Information of Expatriate Report">
                                        {{--General Information--}}
                                        <tr>
                                            <th colspan="2" style="padding: 5px;" class="text-info">
                                                General Information:
                                            </th>
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
                                                Position/ Designation :
                                                <span> {{ (!empty($appInfo->emp_designation)) ? $appInfo->emp_designation:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Brief job description :
                                                <span> {{ (!empty($appInfo->brief_job_description)) ? $appInfo->brief_job_description:''  }}</span>
                                            </td>
                                        </tr>
                                        {{-- Passport Information --}}
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

                                            <td width="50%" style="padding: 5px;" >
                                                Nationality :
                                                <span> {{ (!empty($appInfo->emp_nationality_id))? $nationality[$appInfo->emp_nationality_id] :''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of Birth :
                                                <span>{{ (!empty($appInfo->emp_date_of_birth) ? date('d-M-Y', strtotime($appInfo->emp_date_of_birth)) : '') }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Place of Birth :
                                                <span> {{ (!empty($appInfo->emp_place_of_birth))?$appInfo->emp_place_of_birth:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of issue :
                                                <span>{{ (!empty($appInfo->pass_issue_date) ? date('d-M-Y', strtotime($appInfo->pass_issue_date)) : '') }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Date of expiry :
                                                <span>{{ (!empty($appInfo->pass_expiry_date) ? date('d-M-Y', strtotime($appInfo->pass_expiry_date)) : '') }} </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Compensation and Benefit --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Compensation and Benefit</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <br/>
                                    <table class="table table-striped table-bordered" cellspacing="10" width="100%" aria-label="Detailed Compensation and Benefit Report">
                                        <tr>
                                            <th scope="col" class="text-center" style="vertical-align: middle; padding: 5px;">Salary structure</th>
                                            <th scope="col" class="text-center" style="padding: 5px;">Payment</th>
                                            <th scope="col" class="text-center" style="padding: 5px;">Amount</th>
                                            <th scope="col" class="text-center" style="padding: 5px;">Currency</th>
                                            {{--<th scope="col"></th>--}}
                                            {{--<th scope="col" class="text-center" colspan="2">Payable Locally</th>--}}
                                            {{--<th scope="col" class="text-center" colspan="2">Payable Abroad</th>--}}
                                        </tr>
                                        {{--<tr>--}}
                                        {{--<th scope="col" class="text-center">Payment</th>--}}
                                        {{--<th scope="col" class="text-center">Amount</th>--}}
                                        {{--<th scope="col" class="text-center">Currency</th>--}}
                                        {{--<th scope="col" class="text-center">Amount</th>--}}
                                        {{--<th scope="col" class="text-center">Currency</th>--}}
                                        {{--</tr>--}}

                                        <tr>
                                            <td style="padding: 5px;">a. Basic salary/ Honorarium </td>
                                            <td style="padding: 5px;">

                                                <span> {{ (!empty($appInfo->basic_payment_type_id))? $paymentMethods[$appInfo->basic_payment_type_id]:''  }}</span>

                                            </td>
                                            <td style="padding: 5px;">

                                                <span> {{ (!empty($appInfo->basic_local_amount))? $appInfo->basic_local_amount:''  }}</span>

                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->basic_local_currency_id))? $currencies[$appInfo->basic_local_currency_id]:''  }}</span>
                                            </td>
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->basic_foreign_amount))? $appInfo->basic_foreign_amount:''  }}</span>--}}
                                            {{--</td>--}}
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->basic_foreign_currency_id))? $currencies[$appInfo->basic_foreign_currency_id]:''  }}</span>--}}
                                            {{--</td>--}}
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
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->overseas_foreign_amount))? $appInfo->overseas_foreign_amount:''  }}</span>--}}
                                            {{--</td>--}}
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->overseas_foreign_currency_id))? $currencies[$appInfo->overseas_foreign_currency_id]:''  }}</span>--}}
                                            {{--</td>--}}
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
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->house_foreign_amount))? $appInfo->house_foreign_amount:''  }}</span>--}}
                                            {{--</td>--}}
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->house_foreign_currency_id))? $currencies[$appInfo->house_foreign_currency_id]:''  }}</span>--}}
                                            {{--</td>--}}
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
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->conveyance_foreign_amount))? $appInfo->conveyance_foreign_amount:''  }}</span>--}}
                                            {{--</td>--}}
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->conveyance_foreign_currency_id))? $currencies[$appInfo->conveyance_foreign_currency_id]:''  }}</span>--}}
                                            {{--</td>--}}
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
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->medical_foreign_amount))? $appInfo->medical_foreign_amount:''  }}</span>--}}
                                            {{--</td>--}}
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->medical_foreign_currency_id))? $currencies[$appInfo->medical_foreign_currency_id]:''  }}</span>--}}
                                            {{--</td>--}}
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
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->ent_foreign_amount))? $appInfo->ent_foreign_amount:''  }}</span>--}}
                                            {{--</td>--}}
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->ent_foreign_currency_id))? $currencies[$appInfo->ent_foreign_currency_id]:''  }}</span>--}}
                                            {{--</td>--}}
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
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->bonus_foreign_amount))? $appInfo->bonus_foreign_amount:''  }}</span>--}}
                                            {{--</td>--}}
                                            {{--<td style="padding: 5px;">--}}
                                            {{--<span> {{ (!empty($appInfo->bonus_foreign_currency_id))? $currencies[$appInfo->bonus_foreign_currency_id]:''  }}</span>--}}
                                            {{--</td>--}}
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">h. Other fringe benefits (if any)</td>
                                            <td colspan="5" style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->other_benefits))? $appInfo->other_benefits:''  }}</span>
                                            </td>
                                        </tr>
                                        {{--<tr>--}}
                                        {{--<th scope="col">i. Any particular comments or remarks</th>--}}
                                        {{--<td colspan="5">--}}
                                        {{--<span> {{ (!empty($appInfo->salary_remarks))? $appInfo->salary_remarks:''  }}</span>--}}
                                        {{--</td>--}}
                                        {{--</tr>--}}
                                    </table>
                                </div>
                                {{--<br/>--}}
                                {{--<div class="col-md-12">--}}
                                {{--<table class="table table-striped table-bordered" aria-label="Detailed Report Data Table" width="100%">--}}
                                {{--<tr>--}}
                                {{--<th scope="col" class="text-left" colspan="9">Manpower of the office</th>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                {{--<th scope="col" class="text-center" colspan="3">Local (a)</th>--}}
                                {{--<th scope="col" class="text-center" colspan="3">Foreign (b)</th>--}}
                                {{--<th scope="col" class="text-center" colspan="1">Grand Total</th>--}}
                                {{--<th scope="col" class="text-center" colspan="2">Ratio</th>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                {{--<th scope="col" class="text-center">Executive</th>--}}
                                {{--<th scope="col" class="text-center">Supporting Staff</th>--}}
                                {{--<th scope="col" class="text-center">Total</th>--}}
                                {{--<th scope="col" class="text-center">Executive</th>--}}
                                {{--<th scope="col" class="text-center">Supporting Staff</th>--}}
                                {{--<th scope="col" class="text-center">Total</th>--}}
                                {{--<th scope="col" class="text-center"> (a+b)</th>--}}
                                {{--<th scope="col" class="text-center">Local</th>--}}
                                {{--<th scope="col" class="text-center">Foreign</th>--}}
                                {{--</tr>--}}
                                {{--<tr>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->local_executive))? $appInfo->local_executive:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->local_stuff))? $appInfo->local_stuff:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->local_total))? $appInfo->local_total:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->foreign_executive))? $appInfo->foreign_executive:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->foreign_stuff))? $appInfo->foreign_stuff:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->foreign_total))? $appInfo->foreign_total:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->manpower_total))? $appInfo->manpower_total:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->manpower_local_ratio))? $appInfo->manpower_local_ratio:''  }}</span>--}}
                                {{--</td>--}}
                                {{--<td style="padding: 5px;">--}}
                                {{--<span> {{ (!empty($appInfo->manpower_foreign_ratio))? $appInfo->manpower_foreign_ratio:''  }}</span>--}}
                                {{--</td>--}}
                                {{--</tr>--}}
                                {{--</table>--}}
                                {{--</div>--}}
                            </div>
                        </div>

                        <table width="100%" cellpadding="10" aria-label="Detailed Contact address Report">
                            <tr>
                                <th colspan="2" style="padding: 5px;" class="text-info">
                                    Contact address of the expatriate in Bangladesh:
                                </th>
                            </tr>

                            <tr>
                                <td width="50%" style="padding: 5px;" >
                                    Division :
                                    <span> {{ (!empty($appInfo->ex_office_division_id))? $divisions[$appInfo->ex_office_division_id]:''  }}</span>
                                </td>

                                <td width="50%" style="padding: 5px;" >
                                    District :
                                    <span> {{ (!empty($appInfo->ex_office_district_id))? $district_eng[$appInfo->ex_office_district_id]:''  }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td width="50%" style="padding: 5px;" >
                                    Police Station :
                                    <span> {{ (!empty($appInfo->ex_office_thana_id))? $thana_eng[$appInfo->ex_office_thana_id]:''  }}</span>
                                </td>

                                <td width="50%" style="padding: 5px;" >
                                    Post Office :
                                    <span> {{ (!empty($appInfo->ex_office_post_office))? $appInfo->ex_office_post_office:''  }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td width="50%" style="padding: 5px;" >
                                    Post Code :
                                    <span> {{ (!empty($appInfo->ex_office_post_code))?$appInfo->ex_office_post_code:''  }}</span>
                                </td>
                                <td width="50%" style="padding: 5px;" >
                                    House, Flat/ Apartment, Road :
                                    <span> {{ (!empty($appInfo->ex_office_address))? $appInfo->ex_office_address:''  }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td width="50%" style="padding: 5px;" >
                                    Telephone No. :
                                    <span> {{ (!empty($appInfo->ex_office_telephone_no))? $appInfo->ex_office_telephone_no:''  }}</span>
                                </td>
                                <td width="50%" style="padding: 5px;" >
                                    Mobile No. :
                                    <span> {{ (!empty($appInfo->ex_office_mobile_no))? $appInfo->ex_office_mobile_no:''  }}</span>
                                </td>
                            </tr>

                            <tr>
                                <td width="50%" style="padding: 5px;" >
                                    Fax No. :
                                    <span> {{ (!empty($appInfo->ex_office_fax_no))? $appInfo->ex_office_fax_no:''  }}</span>
                                </td>
                                <td width="50%" style="padding: 5px;" >
                                    Email :
                                    <span> {{ (!empty($appInfo->ex_office_email))? $appInfo->ex_office_email:''  }}</span>
                                </td>
                            </tr>
                        </table>

                        <table width="100%" cellpadding="10" aria-label="Detailed Others Particular of Organization Report">
                            {{--Others Particular of Organization--}}
                            <tr>
                                <th colspan="2" style="padding: 5px;" class="text-info">
                                    Others Particular of Organization:
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;" >
                                    Nature of Business :
                                    <span> {{ (!empty($appInfo->nature_of_business))?$appInfo->nature_of_business:''  }}</span>
                                </td>
                                <td width="50%" style="padding: 5px;" >
                                    Remittance received during the last twelve months (USD):
                                    <span> {{ (!empty($appInfo->received_remittance))?$appInfo->received_remittance:''  }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 5px;">
                                    <strong class="text-default">Capital Structure:</strong>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;" >
                                    (i) Authorized Capital (USD):
                                    <span> {{ (!empty($appInfo->auth_capital))?$appInfo->auth_capital:''  }}</span>
                                </td>
                                <td width="50%" style="padding: 5px;" >
                                    (ii) Paid-up Capital (USD):
                                    <span> {{ (!empty($appInfo->paid_capital))?$appInfo->paid_capital:''  }}</span>
                                </td>
                            </tr>
                        </table>

                        {{--Manpower of the organization--}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Manpower of the organization</div>
                            <div class="panel-body">
                                <div class="col-md-12"><br>
                                    <table class="table table-striped table-bordered" aria-label="Detailed Manpower of the organization Report" width="100%">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td class="text-center" style="padding: 5px;" colspan="9">Manpower of the organization</td>
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

                        <div class="panel panel-info">
                            <div class="panel-heading">Payment Info</div>
                            <div class="panel-body" style="padding: 5px">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="padding: 2px 5px;">Service Fee Payment</div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="row" style="padding: 5px">
                                                <table class="table table-striped table-bordered" aria-label="Detailed Service Fee Payment Report">
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
                                                        <td>Payment Mode :
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
                                                                <span>{{ (!empty($appInfo->sfp_pay_mode_code)) ? $appInfo->sfp_pay_mode_code :''  }}</span>
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
                                            <td style="padding: 5px;">
                                                <p>a. I do hereby undertake full responsibility of the expatriate for
                                                    whom visa recommendation is sought during their stay in
                                                    Bangladesh. </p>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table width="100%" cellpadding="10" aria-label="Detailed Authorized Personnel Report">
                                        <tr>
                                            <th colspan="2" style="padding: 5px;" class="text-info">
                                                Authorized Personnel
                                                    of the organization: 
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
                                                     I do hereby declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement given.
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
