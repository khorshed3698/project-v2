<!DOCTYPE html>
<html lang="en">
<head>
    <title>VIP Lounge Certificate</title>
    <meta charset="UTF-8">
</head>

<body>
<div class="content">
    <br>
    <div class="row">
        <div class="col-md-12">
            <table width="100%" style="margin-bottom: 10px;" aria-label="Detailed Report Data Table">
                <thead>
                    <tr class="d-none">
                        {{-- <th aria-hidden="true"  scope="col"></th> --}}
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="75%" style="padding: 0">
                        <strong>Ref No: </strong> {{ !empty($appInfo->tracking_no) ? $appInfo->tracking_no : '' }}
                    </td>
                    <td width="25%" style="padding: 0; text-align: right">
                        <strong>Date:</strong> {{ date('F j, Y', strtotime($appInfo->submitted_at)) }}
                    </td>
                </tr>
                <br><br>
                <tr>
                    <td width="75%" style="padding: 0">
                        {{ !empty($appInfo->AirExecutiveDesignation) ? $appInfo->AirExecutiveDesignation : 'Executive Director' }}<br>
                        {{ !empty($appInfo->AirName) ? $appInfo->AirName : '' }}<br>
                        {{ !empty($appInfo->AirLocation) ? $appInfo->AirLocation . ',' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}<br>
                        {{-- @if(!empty($appInfo->AirFax))
                            Fax : {{ !empty($appInfo->AirFax) ? $appInfo->AirFax : '' }}<br>
                        @endif
                        @if($appInfo->AirEmail)
                            Email: {{ !empty($appInfo->AirEmail) ? $appInfo->AirEmail : '' }} <br>
                        @endif --}}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0 0 0"><strong>Subject:</strong> &nbsp;&nbsp; Recommendation for Issuance of P-Pass & use of VIP/CIP Lounge of {{ !empty($appInfo->AirName) ? $appInfo->AirName . ', ' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName . ', ': '' }} {{ !empty($appInfo->AirCountryName) ? $appInfo->AirCountryName : '' }}</td>
                </tr>
                </tbody>
            </table>

            <p>
                Dear Sir/Madam,<br>
                You are kindly requested to allow the following delegate(s) to use VIP/CIP lounge and allow the  applicant
                office/company to take their vehicle inside the parking area at the {{ !empty($appInfo->AirName) ? $appInfo->AirName : '' }}:
            </p>

            <table width="100%" style="margin-bottom: 5px;" aria-label="Detailed Report Data Table">
                <thead>
                    <tr class="d-none">
                        {{-- <th aria-hidden="true"  scope="col"></th> --}}
                    </tr>
                </thead>
                <tbody>
                <tr style="text-justify: inter-word;">
                    <td width="50%"><span>a. Requesting office/company with address </span></td>
                    <td><span>: </span></td>
                    <td width="50%"><span>{{ (!empty($appInfo->company_name) ? $appInfo->company_name . ', ' : '')}} {{ (!empty($appInfo->company_office_address) ? $appInfo->company_office_address : '') }} </span> </td>
                </tr>
                <tr>
                    <td width="50%"><span>b. Type of Industry / Office </span></td>
                    <td><span>: </span></td>
                    <td width="50%"><span> {{ (!empty($appInfo->ref_no_type) ? $appInfo->ref_no_type : '') }} </span> </td>
                </tr>
                <tr>
                    <td width="50%"><span>c. BIDA Registration /Office Permission/ Incorporation number </span></td>
                    <td><span>: </span></td>
                    <td width="50%"><span>{{ (!empty($appInfo->reference_number) ? $appInfo->reference_number : '') }} </span> </td>
                </tr>
                </tbody>
            </table>

            <table class="table table-bordered" style="margin-bottom: 5px;" aria-label="Detailed Report Data Table">
                <tr>
                    <td colspan="4"><strong>d. Particulars of the delegate(s)</strong></td>
                </tr>

                <tr class="border-secondary">
                    <th scope="col">Name</th>
                    <th scope="col">Designation</th>
                    <th scope="col">Passport No.</th>
                    <th scope="col">Nationality</th>
                </tr>

                <tr>
                    <td>{{ (!empty($appInfo->DelegateName) ? $appInfo->DelegateName : '') }}</td>
                    <td>{{ (!empty($appInfo->DelegateDesignation) ? $appInfo->DelegateDesignation : '') }}</td>
                    <td>{{ (!empty($appInfo->DelegatePassport) ? $appInfo->DelegatePassport : '') }}</td>
                    <td>{{ (!empty($appInfo->DelegateNationality) ? $appInfo->DelegateNationality : '') }}</td>
                </tr>

            </table>

            <table width="100%" style="margin-bottom: 10px;" aria-label="Detailed Report Data Table">
                <thead>
                    <tr class="d-none">
                        {{-- <th aria-hidden="true"  scope="col"></th> --}}
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td width="50%"><span>e. Purpose of visit of the delegate </span></td>
                    <td><span>: </span></td>
                    <td width="50%"><span> {{ (!empty($appInfo->visa_purpose) ? $appInfo->visa_purpose : '') }} </span> </td>
                </tr>
                <tr>
                    <td width="50%"><span>f. Type of service required at the airport </span></td>
                    <td><span>: </span></td>
                    <td width="50%"><span>P-pass & use of VIP/CIP Lounge</span> </td>
                </tr>

                <?php
                $flag = true;
                $title = 'g. Flight details';
                ?>

                @if ($appInfo->vip_longue_purpose_id  !== 2)
                    <tr>
                        <td width="50%">
                                <span>
                                {{ $flag == true ? $title : '' }}  &nbsp;&nbsp;&nbsp; i) Arrival
                            </span>
                        </td>
                        <td><span>: </span></td>
                        <td width="50%"><span> {{ (!empty($appInfo->ArrivalInfo) ? $appInfo->ArrivalInfo : '') }} </span> </td>
                    </tr>

                    <?php
                    $flag = false;
                    ?>
                @endif
                @if ($appInfo->vip_longue_purpose_id  !== 1)
                    <tr>
                        <td width="50%" style="padding-left: {{ $flag == true ? '0' : '14%' }};">
                                <span>    
                                    {{ $flag == true ? $title . '&nbsp;&nbsp;&nbsp;' : '' }}
                                    {{ ($appInfo->vip_longue_purpose_id  == 2) ? 'i)': 'ii)' }} Departure 
                                </span>
                        </td>
                        <td><span>: </span></td>
                        <td width="50%"><span>{{ (!empty($appInfo->DepartureInfo) ? $appInfo->DepartureInfo : '') }} </span> </td>
                    </tr>
                @endif
                </tbody>
            </table>

            @if (count($spouseChildInfo) > 0)
                <table class="table table-bordered " width="100%" aria-label="Detailed Report Data Table">
                    <tr>
                        <td colspan="4"><strong>h. Spouse and Child Information</strong></td>
                    </tr>

                    <tr>
                        <th scope="col">Spouse/child</th>
                        <th scope="col">Name</th>
                        <th scope="col">Passport/Personal No.</th>
                        <th scope="col">Remarks</th>
                    </tr>

                    @foreach ($spouseChildInfo as $spouse_child)
                        <tr>
                            <td>{{ $spouse_child->spouse_child_type }}</td>
                            <td>{{ $spouse_child->spouse_child_name }}</td>
                            <td>{{ $spouse_child->spouse_child_passport_per_no }}</td>
                            <td>{{ $spouse_child->spouse_child_remarks }}</td>
                        </tr>
                    @endforeach

                </table>
            @endif

            @if (count($viplPassportHolderInfo) > 0)
                <table class="table table-bordered" width="100%" aria-label="Detailed Report Data Table">
                    <tr>
                        <td colspan="4"><strong> {{ (!count($spouseChildInfo) > 0) ? 'h.': 'I.' }} To whom, the Protocol- Pass (P-Pass) will be issued</strong></td>
                    </tr>

                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Designation</th>
                        <th scope="col">Mobile Number</th>
                        <th scope="col">NID/Passport Number</th>
                    </tr>

                    @foreach ($viplPassportHolderInfo as $passport_holder)
                        <tr>
                            <td>{{ $passport_holder->passport_holder_name }}</td>
                            <td>{{ $passport_holder->passport_holder_designation }}</td>
                            <td>{{ $passport_holder->passport_holder_mobile }}</td>
                            <td>{{ $passport_holder->passport_holder_passport_no }}</td>
                        </tr>
                    @endforeach

                </table>
            @endif
        </div>

        @if(!empty($appInfo->approval_copy_remarks))
            <div class="col-md-12">
                <p> <strong>Additional Conditions: </strong> &nbsp;&nbsp; {{ $appInfo->approval_copy_remarks }} .</p>
            </div>
        @endif

        <div class="col-md-12">
            <p>Your kind cooperation in this regard will be highly appreciated.</p>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-12">
            <div>
                Ref no: <br>
                Copy forwarded for kind information and necessary action:<br>
                @if($appInfo->AirId == 1)
                    1. Chairman, Civil Aviation, Kurmitola, Dhaka-1229, <br>
                    2. Director ({{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}) Bangladesh Investment Development Authority. <br>
                    3. Officer-In-Charge, Immigration, {{ !empty($appInfo->AirName) ? $appInfo->AirName . ', ' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}. <br>
                    4. {{ !empty($appInfo->ceo_designation) ? $appInfo->ceo_designation . ', ' : ''}}
                    {{ !empty($appInfo->company_name) ? $appInfo->company_name . ', ' : ''}}
                    {{ !empty($appInfo->company_office_address) ? $appInfo->company_office_address . ', ' : ''}} <br><br>

                @elseif($appInfo->AirId == 2)
                    1. Chairman, Civil Aviation, Kurmitola, Dhaka-1229, <br>
                    2. Director ({{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}) Bangladesh Investment Development Authority. <br>
                    3. Officer-In-Charge, Immigration, {{ !empty($appInfo->AirName) ? $appInfo->AirName . ', ' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}. <br>
                    4. {{ !empty($appInfo->ceo_designation) ? $appInfo->ceo_designation . ', ' : ''}}
                    {{ !empty($appInfo->company_name) ? $appInfo->company_name . ', ' : ''}}
                    {{ !empty($appInfo->company_office_address) ? $appInfo->company_office_address . ', ' : ''}} <br><br>

                @elseif($appInfo->AirId == 3)
                    1. 	Chairman, Civil Aviation, Kurmitola, Dhaka-1229, <br>
                    2.	Director (AVSEC), {{ !empty($appInfo->AirName) ? $appInfo->AirName . ', ' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}.<br>
                    3. Officer-In-Charge, Immigration, {{ !empty($appInfo->AirName) ? $appInfo->AirName . ', ' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}. <br>
                    4.	Assistant Director,  (AVSEC ID permit), {{ !empty($appInfo->AirName) ? $appInfo->AirName . ', ' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}.<br>
                    5.	Officer-in-Charge, Welcome Service Booth of BIDA, {{ !empty($appInfo->AirName) ? $appInfo->AirName . ', ' : '' }} {{ !empty($appInfo->AirCityName) ? $appInfo->AirCityName : '' }}. [He is requested to provide necessary services to the delegate(s). He may also contact the applicant company (the contact details are mentioned below) if necessary.]. <br>
                    6.	{{ !empty($appInfo->ceo_designation) ? $appInfo->ceo_designation . ', ' : ''}}
                    {{ !empty($appInfo->company_name) ? $appInfo->company_name . ', ' : ''}}
                    {{ !empty($appInfo->company_office_address) ? $appInfo->company_office_address . ', ' : ''}} <br>
                    [Please contact the Officer-in-Charge of the Welcome Service Booth of BIDA at the HSIA for support (if required)]. <br><br>
                @endif
            </div>

            <div style="text-align: center; ">
                <div style="padding-left: 75%;">
                    <img src="{{ $director_signature }}" width="70" alt="Director Signature" /><br>
                    ({{ $director->signer_name }})<br>
                    {{ $director->signer_designation }}<br>
                </div>
            </div>

            <br><br><br>
        </div>
    </div>
</div>
</body>
</html>