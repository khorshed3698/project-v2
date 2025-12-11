<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
</head>

<body>
    <div class="content">
        <br>
        <div class="row">
            <div class="col-md-12">

                <table width="100%" style="width: 100%; margin-bottom: 10px;" aria-label="Detailed Report Data Table">
                    <thead>
                        <tr class="d-none"></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="75%" style="padding: 0">
                                <b>Ref. no.: </b>
                                {{ !empty($appInfo->tracking_no) ? $appInfo->tracking_no : '' }}
                            </td>
                            <td width="25%" style="padding: 0; text-align: right">
                                <b>Dated:</b> {{ date('j F Y', strtotime($appInfo->approved_date)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="width: 100%;" aria-label="Detailed Report Data Table">
                    <thead>
                        <tr class="d-none"></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{ !empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : '' }}<br>
                                {{ !empty($appInfo->project_name) ? $appInfo->project_name : '' }}<br>
                                {{ !empty($appInfo->poa_co_address) ? $appInfo->poa_co_address : '' }} ,<br>
                                {{ !empty($appInfo->poa_co_post_office) ? $appInfo->poa_co_post_office : '' }} ,
                                {{ !empty($appInfo->poa_co_thana_name) ? $appInfo->poa_co_thana_name : '' }} ,
                                {{ !empty($appInfo->poa_co_district_name) ? $appInfo->poa_co_district_name : '' }} ,
                                {{ !empty($appInfo->poa_co_post_code) ? $appInfo->poa_co_post_code : '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table style="margin-bottom: 10px;" aria-label="Detailed Report Data Table">
                    <tr>
                        <td style="width: 10%;" valign="top"><b>Subject:</b></td>
                        <td style="width: 90%"><b>Approval of the opening of a new project office</b></td>
                    </tr>
                </table>

                <p>
                    Dear Sir,
                </p>
                <p>
                    In response to your applications received on
                    {{ !empty($paymentData[0]->payment_date) ? date('j F Y', strtotime($paymentData[0]->payment_date)) : '' }}
                    and
                    {{ !empty($paymentData[1]->payment_date) ? date('j F Y', strtotime($paymentData[1]->payment_date)) : '' }}
                    regarding the above-mentioned subject, we would like to inform you that, as per the decision of the
                    {{ !empty($metingInformation->meting_number) ? $metingInformation->meting_number : '' }}<sup>th</sup>
                    Interministerial Committee meeting held on
                    {{ !empty($metingInformation->meting_date) ? date('j F Y', strtotime($metingInformation->meting_date)) : '' }},
                    the Bangladesh Investment Development Authority (BIDA) has granted the approval for the opening of a
                    Project Office in Dhaka for a period of
                    {{ !empty($appInfo->approved_desired_duration) ? $appInfo->approved_desired_duration : '' }},
                    effective from
                    {{ !empty($appInfo->approved_duration_start_date) ? date('j F Y', strtotime($appInfo->approved_duration_start_date)) : '' }},
                    under the following terms and conditions:
                </p>

                <ol style="margin: 0; padding-left: 15px;">
                    <li>
                        The activities of the project office shall be limited to the local conduct of Upgrading of a
                        consultancy contract between
                        {{ !empty($appInfo->ministry_name) ? $appInfo->ministry_name : '' }} and {{ $companyNames }},
                        signed on
                        {{ !empty($appInfo->contract_signing_date) ? date('j F Y', strtotime($appInfo->contract_signing_date)) : '' }},
                        for the implementation of the
                        “{{ !empty($appInfo->project_major_activities) ? $appInfo->project_major_activities : '' }}”.
                        <br>
                        No activities are permissible other than the activities approved in this permission letter.
                        Prior permission must be taken following due process for changing the activities;
                    </li>
                    <li>
                        The office is not permitted to open any commercial establishment not mentioned in this approval
                        letter. Prior permission must be obtained from BIDA for opening any additional establishment;
                    </li>
                    <li>
                        The project office must follow the clause no. 6.4, clause no. 6.5 and other applicable clauses
                        of the
                        <b>“Guideline for granting permission to foreign commercial offices, recommending visas for
                            foreign workers, and issuing work permits to foreign workers, 2023”;</b>
                    </li>
                    <li>
                        The office shall submit the letter of notification to Bangladesh Bank according to the section
                        no 18B of the Foreign Exchange Regulation Act, 1947 and any amendment there.
                    </li>
                    <li>
                        Permission must be taken from BIDA to employ any foreign national(s) in the office. The clause
                        no. 8 of the
                        <b>“Guideline for granting permission to foreign commercial offices, recommending visas for
                            foreign workers, and issuing work permits to foreign workers, 2023”</b>
                        must be followed in engaging foreign workers in the office;
                    </li>
                    <li>
                        The office shall comply with all existing policies, acts, ordinances, rules, regulations,
                        guidelines, and orders of the country applicable to the project office;
                    </li>
                    <li>
                        The office must meet all operational, functional, and establishment costs and pay salary &
                        allowances to the <b>foreign workers</b> and local employees with the remittance received from
                        parent companies and/or from the approved sources mentioned in the contract;
                    </li>
                    <li>
                        Outward remittance of profit of the Joint Venture/Consortium/association of the project office
                        can be repatriated by individual member complying with the appropriate regulations of the
                        country. Prior approval must be taken from BIDA for any kind of outward remittance other than
                        the profit;
                    </li>
                    <li>
                        The office must bring inward remittance of at least US$ 50,000.00 (fifty thousand) within 2
                        (two) months from the date of issuing the permission letter as establishment cost and
                        operational expenses for six (6) months. If the office fails to bring the said remittance within
                        the stipulated time, 5% additional remittance must be brought for the delay of each month;
                    </li>
                    <li>
                        The office shall open an account with any scheduled bank in Bangladesh for all kind of financial
                        transactions of the project office;
                    </li>
                    <li>
                        A quarterly return of income and expenditure must be submitted to BIDA, the respective Deputy
                        Commissioner of Taxes of Companies and Bangladesh Bank;
                    </li>
                    <li>
                        The office must not change any information provided in this approval letter without prior
                        permission of BIDA;
                    </li>
                    <li>
                        This permission does not exempt the project office from taking any
                        clearance/permission/merit/license according to existing acts, ordinances, rules, regulations,
                        guidelines, and government orders of the country;
                    </li>
                    <li>
                        The project office formed for the implementation of a project can only receive the income
                        related to that project. The income received may be shared/transferred in proportion to the
                        investment/participation mentioned in the project document among the organizations participating
                        in the joint venture/consortium/association. However, the parties of the joint
                        venture/consortium/association agreement, individually or from their joint account or through
                        their authorized branch office as applicable can repatriate the surplus money following the
                        existing procedures in the foreign exchange transaction system subject to payment of all types
                        of liabilities and taxes. Partners/members of the Joint Venture/Consortium/Association shall be
                        jointly & severally liable for any activities of the project office.
                    </li>
                    <li>
                        Provisions of Rule 54 of Chapter 10 of Public Procurement Rules, 2008 shall be applicable for
                        joint venture/consortium/association. Provided that, all foreign contractors/sub-contractors and
                        organizations under the joint venture/consortium/association agreement shall separately obtain
                        approval from the Inter-Ministerial Committee of BIDA for establishment of branch/project office
                        for implementation of the contract;
                    </li>
                    <li>
                        Tax, VAT, duties, and fees must be paid as per existing pertinent acts, ordinances, rules,
                        regulations, SROs, guidelines, and government orders issued from time to time;
                    </li>
                    <li>
                        The project office must apply to BIDA to extend the term of the office in a prescribed form two
                        months before the expiry of the existing term;
                    </li>
                    <li>
                        This permission may either in part or in whole be revoked, suspended, and altered without
                        showing any cause. Also, new condition(s) can be imposed without giving any reason;
                    </li>
                    <li>
                        The permission letter shall be cancelled on completion of the project or termination of the
                        joint venture/consortium/association agreement. In such a case, the project office must complete
                        the procedure for closure of the office.
                    </li>
                </ol>

            </div>
            <br>
        </div>

        <br>

        <table width="100%" aria-label="Detailed Report Data Table">
            <tr>
                <td width="70%"></td>
                <td width="30%;" style="text-align: center; ">
                    Sincerely yours, <br>
                    <img src="{{ $director_signature }}" width="70" alt="Director Signature" /><br>
                    ({{ $director->signer_name }})<br>
                    {{ $director->signer_designation }}<br>
                    Phone: {{ $director->signer_mobile }}<br>
                    Email: {{ $director->signer_email }}
                </td>
            </tr>
        </table>

        <br><br>

        <div class="row">
            <div class="col-md-12">
                <u>Copy forwarded for information and necessary action (not according to seniority):</u>
                <ol style="margin: 0; padding-left: 15px;">
                    <li>
                        Governor, Bangladesh Bank, Motijheel, Dhaka
                    </li>
                    <li>
                        Chairman, National Board of Revenue, Segunbagicha, Dhaka
                    </li>
                    <li>
                        Secretary (Senior Secretary), Ministry of Foreign Affairs, Segunbagicha, Dhaka
                    </li>
                    <li>
                        Secretary, Security Services Division, Bangladesh Secretariat, Dhaka
                    </li>
                    <li>
                        Registrar, Registrar of Joint Stock Companies & Firms, TCB Bhaban, Karwan Bazar, Dhaka

                    </li>
                    <li>
                        General Manger, Statistics Department, Bangladesh Bank, Motijheel, Dhaka
                    </li>
                    <li>
                        {{ !empty($appInfo->ceo_full_name) ? $appInfo->ceo_full_name : '' }} ,
                        {{ !empty($appInfo->ceo_designation) ? $appInfo->ceo_designation : '' }} ,
                        {{ !empty($appInfo->project_name) ? $appInfo->project_name : '' }} ,
                        {{ !empty($appInfo->poa_co_district_name) ? $appInfo->poa_co_district_name : '' }}
                    </li>
                </ol>
            </div>
        </div>



        <table width="100%" aria-label="Detailed Report Data Table">
            <tr>
                <td width="70%"></td>
                <td width="30%;" style="text-align: center; ">
                    <img src="{{ $director_signature }}" width="70" alt="Director Signature" /><br>
                    ({{ $director->signer_name }})<br>
                    {{ $director->signer_designation }}
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
