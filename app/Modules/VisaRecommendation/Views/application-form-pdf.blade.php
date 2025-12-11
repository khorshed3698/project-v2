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
                        Application for Visa Recommendation
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Visa Recommendation</div>
                    <div class="panel-body">
                        <table aria-label="Detailed Report Data Table" width="100%">
                            <tr>
                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                            </tr>
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Tracking no. :  <span>{{ $appInfo->tracking_no  }}</span></td>
                                <td style="padding: 5px;">Date of Submission: <span>{{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}</span></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Current Status :<span>{{$appInfo->status_name}}</span></td>
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
                                                Visa Type :
                                                <span>{{ (!empty($appInfo->app_type_name)) ? $appInfo->app_type_name:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Company Name :
                                                <span> {!! \App\Libraries\CommonFunction::getCompanyNameById($appInfo->company_id) !!}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Department :
                                                <span> {{ (!empty($appInfo->department_id)) ? $department[$appInfo->department_id]:''  }}</span>
                                            </td>
                                        </tr>

                                        {{--If visa category Visa On Arrival--}}
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
                                                    <span> {{ (!empty($appInfo->visa_purpose_id))? $travel_purpose[$appInfo->visa_purpose_id]:''  }}</span>
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
                                            <th colspan="2" style="padding: 5px;" class="text-info">
                                                General Information:
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

                                        @if($appInfo->app_type_id != 5)
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Brief job description :
                                                    <span> {{ (!empty($appInfo->brief_job_description)) ? $appInfo->brief_job_description:''  }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($appInfo->business_category == 2)
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Marital Status :
                                                    <span> {{ (!empty($appInfo->emp_marital_status)) ? $appInfo->emp_marital_status:''  }}</span>
                                                </td>
                                            </tr>
                                        @endif

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
                                                <span>{{ ((!empty($appInfo->emp_date_of_birth)) ? date('d-M-Y', strtotime($appInfo->emp_date_of_birth)) : '') }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Place of Birth :
                                                <span> {{ (!empty($appInfo->emp_place_of_birth))?$appInfo->emp_place_of_birth:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of issue :
                                                <span>{{ ((!empty($appInfo->pass_issue_date)) ? date('d-M-Y', strtotime($appInfo->pass_issue_date)) : '') }} </span>
                                            </td>

                                            <td width="50%" style="padding: 5px;" >
                                                Date of expiry :
                                                <span>{{ ((!empty($appInfo->pass_expiry_date)) ? date('d-M-Y', strtotime($appInfo->pass_expiry_date)) : '') }} </span>
                                            </td>
                                        </tr>

                                        {{-- Office address end --}}

                                    </table>
                                </div>
                            </div>
                        </div>

                        @if($appInfo->app_type_id != 5)
                            {{-- Compensation and Benefit --}}
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Compensation and Benefit</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <br/>
                                        <table aria-label="Detailed Report Compensation and Benefit" id="" class="table table-striped table-bordered" cellspacing="10" width="100%">
                                            <tr>
                                                <th class="text-center" style="vertical-align: middle; padding: 5px;">Salary structure</th>
                                                <th class="text-center" style="padding: 5px;">Payment</th>
                                                <th class="text-center" style="padding: 5px;">Amount</th>
                                                <th class="text-center" style="padding: 5px;">Currency</th>
                                                {{--<th></th>--}}
                                                {{--<th class="text-center" colspan="2">Payable Locally</th>--}}
                                                {{--<th class="text-center" colspan="2">Payable Abroad</th>--}}
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
                        @endif

                        <table aria-label="Detailed Report Data Table" width="100%" cellpadding="10">
                            @if($appInfo->app_type_id != 5 && $appInfo->business_category == 1)
                                <tr>
                                    <th colspan="2" style="padding: 5px;" class="text-info">
                                        Others Particular of Organization(If Commercial):
                                    </th>
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
                            @endif
                        </table>
                        @if($appInfo->business_category == 2 && $appInfo->emp_marital_status == "married")
                            <table aria-label="Detailed Report Data Table">
                                <tr>
                                    <th colspan="2" style="padding: 5px;" class="text-info">
                                        Spouse Information:
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="padding: 5px;" >
                                        Spouse Name :
                                        <span> {{ (!empty($appInfo->emp_spouse_name))?$appInfo->emp_spouse_name:''  }}</span>
                                    </td>
                                    <td width="50%" style="padding: 5px;" >
                                        Passport Number:
                                        <span> {{ (!empty($appInfo->emp_spouse_passport_no)) ? $appInfo->emp_spouse_passport_no : ''  }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" style="padding: 5px;" >
                                        Nationality :
                                        <span> {{ (!empty($appInfo->spouse_nationality_name))?$appInfo->spouse_nationality_name:''  }}</span>
                                    </td>
                                    <td width="50%" style="padding: 5px;" >
                                        Does he/she work in Bangladesh?
                                        <span> {{ (!empty($appInfo->emp_spouse_work_status)) ? $appInfo->emp_spouse_work_status : ''  }}</span>
                                    </td>
                                </tr>
                                @if($appInfo->emp_spouse_work_status == 'yes')
                                    <tr>
                                        <td width="50%" style="padding: 5px;" >
                                            Organization Name :
                                            <span> {{ (!empty($appInfo->emp_spouse_nationality)) ? $nationality[$appInfo->emp_spouse_nationality]:''  }}</span>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        @endif

                        <table aria-label="Detailed Report Data Table" width="100%" cellpadding="10">
                            <tr>
                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                            </tr>
                            <tr>
                                <th colspan="2" style="padding: 5px;" class="text-info" scope="col">
                                    Contact address of the expatriate in Bangladesh:
                                </th>
                            </tr>
                            {{-- Office address start --}}
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
                                    House, Flat/ Apartment, Road :
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
                        </table>

                        {{-- if visa type is not Visa On Arrival--}}
                        @if($appInfo->app_type_id != 5)
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Previous Travel history of the expatriate to Bangladesh</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Report Previous Travel history" width="100%" cellpadding="10">
                                            <tr>
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Have you visited to Bangladesh previously?
                                                    <span> {{ (!empty($appInfo->travel_history)) ? ucfirst($appInfo->travel_history) : ''  }}</span>
                                                </td>
                                            </tr>
                                            @if($appInfo->travel_history == 'yes')
                                                <tr>
                                                    <td colspan="2" style="padding: 5px;">
                                                        <strong class="text-default">In which period:</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table aria-label="Detailed Report Data Table" class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Type of visa availed</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if(count($visaRecords) > 0))
                                                            @foreach($visaRecords as $record)
                                                                <tr>
                                                                    <td><span>{{ ((!empty($record->th_emp_duration_from)) ? date('d-M-Y', strtotime($record->th_emp_duration_from)) : '') }} </span></td>
                                                                    <td><span>{{ ((!empty($record->th_emp_duration_to)) ? date('d-M-Y', strtotime($record->th_emp_duration_to)) : '') }} </span></td>
                                                                    <td><span> {{ (!empty($record->th_visa_type_id)) ? $visaTypes[$record->th_visa_type_id] : ''  }}</span>
                                                                        @if (!empty($record->th_visa_type_others))
                                                                            <br/>
                                                                            {{ $record->th_visa_type_others }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="3"> No Visa record</td>
                                                                </tr>
                                                            @endif
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" style="padding: 5px;">
                                                        Have you visited to Bangladesh with Employment Visa?
                                                        <span> {{ (!empty($appInfo->th_visit_with_emp_visa)) ? ucfirst($appInfo->th_visit_with_emp_visa) : ''  }}</span>
                                                    </td>
                                                </tr>
                                                @if($appInfo->th_visit_with_emp_visa == 'yes')
                                                    <tr>
                                                        <td colspan="2" style="padding: 5px;">
                                                            Have you received work permit from Bangladesh?
                                                            <span> {{ (!empty($appInfo->th_emp_work_permit)) ? ucfirst($appInfo->th_emp_work_permit) : ''  }}</span>
                                                        </td>

                                                    </tr>


                                                    @if($appInfo->th_emp_work_permit == 'yes')
                                                        <tr>
                                                            <td colspan="2" style="padding: 5px;">
                                                                <strong class="text-default">Previous work permit information in Bangladesh:</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="50%" style="padding: 5px;" >
                                                                TIN Number :
                                                                <span> {{ (!empty($appInfo->th_emp_tin_no)) ? $appInfo->th_emp_tin_no : ''  }}</span>
                                                            </td>
                                                            <td width="50%" style="padding: 5px;" >
                                                                Last Work Permit Ref. No. :
                                                                <span> {{ (!empty($appInfo->th_emp_wp_no)) ? $appInfo->th_emp_wp_no : ''  }}</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" style="padding: 5px;">
                                                                Name of the employer organization :
                                                                <span> {{ (!empty($appInfo->th_emp_org_name)) ? $appInfo->th_emp_org_name : ''  }}</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2" style="padding: 5px;">
                                                                Address of the organization :
                                                                <span> {{ (!empty($appInfo->th_emp_org_address)) ? $appInfo->th_emp_org_address : ''  }}</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td width="50%" style="padding: 5px;" >
                                                                City/ District :
                                                                <span> {{ (!empty($appInfo->th_org_district_id)) ? $districts[$appInfo->th_org_district_id] : ''  }}</span>
                                                            </td>
                                                            <td width="50%" style="padding: 5px;" >
                                                                Thana/ Upazilla :
                                                                <span> {{ (!empty($appInfo->th_org_thana_id))? $thana_eng[$appInfo->th_org_thana_id]:''  }}</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td width="50%" style="padding: 5px;" >
                                                                Post Office :
                                                                <span> {{ (!empty($appInfo->th_org_post_office))?$appInfo->th_org_post_office:''  }}</span>
                                                            </td>
                                                            <td width="50%" style="padding: 5px;" >
                                                                Post Code :
                                                                <span> {{ (!empty($appInfo->th_org_post_code))?$appInfo->th_org_post_code:''  }}</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td width="50%" style="padding: 5px;" >
                                                                Contact Number :
                                                                <span> {{ (!empty($appInfo->th_org_telephone_no))?$appInfo->th_org_telephone_no:''  }}</span>
                                                            </td>
                                                            <td width="50%" style="padding: 5px;" >
                                                                Email :
                                                                <span> {{ (!empty($appInfo->th_org_email))?$appInfo->th_org_email:''  }}</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="2">
                                                                <table aria-label="Detailed Report Data Table" class="table table-bordered">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th>Required attachments</th>
                                                                        <th>Attached PDF file</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php $i = 1; ?>
                                                                    @if(count($travel_history_document) > 0)
                                                                        @foreach($travel_history_document as $row)
                                                                            <tr>
                                                                                <td style="padding: 5px;">
                                                                                    <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                                                </td>
                                                                                <td
                                                                                        style="padding: 5px;">{!!  $row->doc_name !!}</td>
                                                                                <td style="padding: 5px;">
                                                                                    @if(!empty($row->doc_file_path))

                                                                                        <div class="save_file">
                                                                                            <a target="_blank" rel="noopener" title=""
                                                                                               href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}">
                                                                                                <img width="10" height="10" src="assets/images/pdf.png"  alt="pdf"/> Open File
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
                                                                        <tr>
                                                                            <td>1</td>
                                                                            <td>Copy of the first work permit</td>
                                                                            <td>
                                                                                @if(!empty($appInfo->th_first_work_permit))
                                                                                    <div class="save_file">
                                                                                        <a target="_blank" rel="noopener"
                                                                                           title="Work permit"
                                                                                           href="{{URL::to('/uploads/'. $appInfo->th_first_work_permit)}}">
                                                                                            <img width="10" height="10"
                                                                                                 src="assets/images/pdf.png"
                                                                                                 alt="pdf"/>
                                                                                            Open File
                                                                                        </a>
                                                                                    </div>
                                                                                @else
                                                                                    No file found
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2</td>
                                                                            <td>Copy of the Resignation letter</td>
                                                                            <td>
                                                                                @if(!empty($appInfo->th_resignation_letter))
                                                                                    <div class="save_file">
                                                                                        <a target="_blank" rel="noopener"
                                                                                           title="Resignation letter"
                                                                                           href="{{URL::to('/uploads/'. $appInfo->th_resignation_letter)}}">
                                                                                            <img width="10" height="10"
                                                                                                 src="assets/images/pdf.png"
                                                                                                 alt="pdf"/>
                                                                                            Open File
                                                                                        </a>
                                                                                    </div>
                                                                                @else
                                                                                    No file found
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3</td>
                                                                            <td>Copy of the release order/termination
                                                                                letter/No objection certificate
                                                                            </td>
                                                                            <td>
                                                                                @if(!empty($appInfo->th_release_order))
                                                                                    <div class="save_file">
                                                                                        <a target="_blank" rel="noopener"
                                                                                           title="Release order"
                                                                                           href="{{URL::to('/uploads/'. $appInfo->th_release_order)}}">
                                                                                            <img width="10" height="10"
                                                                                                 src="assets/images/pdf.png"
                                                                                                 alt="pdf"/>
                                                                                            Open File
                                                                                        </a>
                                                                                    </div>
                                                                                @else
                                                                                    No file found
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4</td>
                                                                            <td>Copy of the last extension (if
                                                                                applicable)
                                                                                <span class="required-star"></span></td>
                                                                            <td>
                                                                                @if(!empty($appInfo->th_last_extension))
                                                                                    <div class="save_file">
                                                                                        <a target="_blank" rel="noopener"
                                                                                           title="Last extension"
                                                                                           href="{{URL::to('/uploads/'. $appInfo->th_last_extension)}}">
                                                                                            <img width="10" height="10"
                                                                                                 src="assets/images/pdf.png"
                                                                                                 alt="pdf"/>
                                                                                            Open File
                                                                                        </a>
                                                                                    </div>
                                                                                @else
                                                                                    No file found
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>5</td>
                                                                            <td>Copy of the cancellation of the last
                                                                                work
                                                                                permit <span
                                                                                        class="required-star"></span>
                                                                            </td>
                                                                            <td>
                                                                                @if(!empty($appInfo->th_last_work_permit))
                                                                                    <div class="save_file">
                                                                                        <a target="_blank" rel="noopener"
                                                                                           title="Last Work permit"
                                                                                           href="{{URL::to('/uploads/'. $appInfo->th_last_work_permit)}}">
                                                                                            <img width="10" height="10"
                                                                                                 src="assets/images/pdf.png"
                                                                                                 alt="pdf"/>
                                                                                            Open File
                                                                                        </a>
                                                                                    </div>
                                                                                @else
                                                                                    No file found
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>6</td>
                                                                            <td> Copy of the income tax certificate for
                                                                                the
                                                                                last assessment year of the previous
                                                                                stay
                                                                                <span class="required-star"></span></td>
                                                                            <td>
                                                                                @if(!empty($appInfo->th_income_tax))
                                                                                    <div class="save_file">
                                                                                        <a target="_blank" rel="noopener"
                                                                                           title="Income tax"
                                                                                           href="{{URL::to('/uploads/'. $appInfo->th_income_tax)}}">
                                                                                            <img width="10" height="10"
                                                                                                 src="assets/images/pdf.png"
                                                                                                 alt="pdf"/>
                                                                                            Open File
                                                                                        </a>
                                                                                    </div>
                                                                                @else
                                                                                    No file found
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @if($appInfo->business_category == 1)
                                {{--Manpower of the organization--}}
                                <div id="ep_form" class="panel panel-info">
                                    <div class="panel-heading">Manpower of the organization</div>
                                    <div class="panel-body">
                                        <div class="col-md-12"><br>
                                            <table aria-label="Detailed Manpower of the organization" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
                            @endif

                        @endif

                        {{--if Visa type is Visa on Arrival--}}
                        @if($appInfo->app_type_id == 5)
                            {{-- Flight Details --}}
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Flight Details of the visiting expatriates</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Flight Details" width="100%" cellpadding="10">
                                            <tr>
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival date & time :
                                                    <span>
                                                     {{ ((!empty($appInfo->arrival_date)) ? date('d-M-Y', strtotime($appInfo->arrival_date)) : '') }}
                                                        {{ date('H:i', strtotime($appInfo->arrival_time)) }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Arrival Flight No. :
                                                    <span> {{ (!empty($appInfo->arrival_flight_no)) ? $appInfo->arrival_flight_no:''  }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Departure date & time :
                                                    <span>
                                                        {{ ((!empty($appInfo->departure_date)) ? date('d-M-Y', strtotime($appInfo->departure_date)) : '') }}&nbsp;
                                                        {{  date('H:i', strtotime($appInfo->departure_time)) }}
                                                    </span>
                                                </td>

                                                <td width="50%" style="padding: 5px;" >
                                                    Departure Flight No. :
                                                    <span> {{ (!empty($appInfo->departure_flight_no)) ? $appInfo->departure_flight_no:''  }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            {{-- On Arrival Information --}}
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">On Arrival Information</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed On Arrival Information" width="100%" cellpadding="10">
                                            <tr>
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Type the services required for the visiting expatriate :
                                                    <span> {{ (!empty($appInfo->visiting_service_id)) ? $visiting_service_type[$appInfo->visiting_service_id] :'' }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    On what circumstances the visa on arrival is sought instead of obtaining Visa from Bangladesh mission abroad :
                                                    <span> {{ (!empty($appInfo->visa_on_arrival_sought_id)) ? $visa_on_arrival_sought[$appInfo->visa_on_arrival_sought_id] :'' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                        @endif

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
                                                <table aria-label="Detailed Report Payment Info" class="table table-striped table-bordered">
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
                                    <table aria-label="Detailed Authorized Personnel" width="100%" cellpadding="10">
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
