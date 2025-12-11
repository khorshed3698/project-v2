<!DOCTYPE html>
<html lang="en">
<head>
    <title>IRC Regular Certificate</title>
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
                        <strong>Tracking No: </strong> {{ !empty($appInfo->tracking_no) ? $appInfo->tracking_no : '' }}
                    </td>
                    <td width="25%" style="padding: 0; text-align: right">
                        <strong>Date:</strong> {{ !empty($appInfo->app_approved_date) ? \Carbon\Carbon::parse($appInfo->app_approved_date)->format('d-M-Y') : '' }}
                    </td>
                </tr>
                </tbody>
            </table>
            <table style="margin-bottom: 10px;" aria-label="Detailed Report Data Table">
                <tr>
                    {{-- <th aria-hidden="true"  scope="col"></th> --}}
                </tr>
                <tr>
                    <td  width="10%"  valign="top"><strong>Sub:</strong></td>
                    <td  width="90%">
                        Recommendation for regularization of {{ $ircAdhocName }} Ad Hoc Industrial Import Registration Certificate (IRC) in favor of {{ !empty($appInfo->company_name) ? $appInfo->company_name : '' }}.
                        @if ($appInfo->irc_regular_purpose_id == 2)
                            Increasing the six-monthly import entitlemen.
                        @elseif ($appInfo->irc_regular_purpose_id == 3)
                            Decreasing the six-monthly import entitlemen.
                        @else
                        @endif
                    </td>
                </tr>
                <tr style="margin-top: 10px;">
                    <td width="10%"  valign="top"><strong>Ref:</strong></td>
                    <td  width="90%">(1) Memo no. {{ $appInfo->irc_ref_app_tracking_no ? $appInfo->irc_ref_app_tracking_no : $appInfo->irc_manually_approved_no }} issued on {{ $appInfo->irc_ref_app_approve_date ? $appInfo->irc_ref_app_approve_date : $appInfo->irc_manually_approved_date }} by BIDA<br>(2) Memo no. {{  $appInfo->irc_ccie_no }} issued on {{ $appInfo->irc_ccie_approve_date }} by CCI&E</td>
                </tr>
            </table>
            <p>
                &nbsp; &nbsp;&nbsp; &nbsp;  Kind attention is being drawn to the abovementioned subject and references. Please be informed that Bangladesh Investment Development Authority (BIDA-Former BOI) has registered the following industrial entity vide Memorandum no. 2 referred above:
            </p>
            {{-- Company Information --}}
            @if (!empty($appInfo->company_name) || !empty($appInfo->company_office_address))
                <table class="table table-bordered" aria-label="Detailed Report Data Table">
                    <thead>
                        <tr class="d-none">
                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
                        </tr>
                        <tr>
                            <td width="20%" style="text-align: center;">Name of the Entity</td>
                            <td width="25%" style="text-align: center;">Address of the Entity</td>
                            <td width="30%" style="text-align: center;">Industrial Sector</td>
                            <td width="25%" style="text-align: center;">Registration Number</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">
                                {{ !empty($appInfo->company_name) ? $appInfo->company_name : '' }}
                            </td>
                            <td>
                                Office: {{ !empty($appInfo->company_office_address) ? $appInfo->company_office_address : '' }}
                            </td>
                            <td rowspan="2">
                                {{ !empty($appInfo->industrial_sector) ? $appInfo->industrial_sector : '' }}
                            </td>
                            <td rowspan="2">
                                {{ (isset($appInfo->last_br) && $appInfo->last_br == 'yes') ? $appInfo->reg_no : $appInfo->br_manually_approved_no }}
                            </td>
                        </tr>
                        <tr>
                            <td> Factory: {{ !empty($appInfo->company_factory_address) ? $appInfo->company_factory_address : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
            <p>
                2. &nbsp;&nbsp; <span style="margin-left: 90px;">BIDA, fixing the six-monthly import entitlement at BDT 
                    @if ($appInfo->irc_purpose_id == 1)
                        {{ $appInfo->ins_apc_half_yearly_import_total.' ('.$appInfo->ins_apc_half_yearly_import_total_in_word.')' }}
                    @elseif ($appInfo->irc_purpose_id == 2)
                        {{  $appInfo->first_em_lc_total_five_percent.' ('.$appInfo->first_em_lc_total_five_percent_in_word.')' }}                       
                    @else
                        {{  $appInfo->ins_apc_half_yearly_import_total.' ('.$appInfo->ins_apc_half_yearly_import_total_in_word.')'.' and BDT '.$appInfo->first_em_lc_total_five_percent.' ('.$appInfo->first_em_lc_total_five_percent_in_word.')' }}                       
                    @endif
                    </span>, provides recommendation for issuing {{ $ircAdhocName }} Ad Hoc industrial IRC for importing {{ $appInfo->irc_purpose_id == 1 ?'raw materials' : $appInfo->irc_purpose_id == 2 ? 'spare parts': $appInfo->irc_purpose_id == 3 ? 'Raw Materials and Spare Parts':'' }} in favor of the above-mentioned industrial entity for using in the factory mentioned in the abovementioned paragraph vide Memorandum no. 1 referred above. The Office of the Chief Controller of Imports and Exports Issues {{ $ircAdhocName }} Ad Hoc Industrial IRC (No. {{ $appInfo->irc_ccie_no }}) in favor of the industrial entity based on the recommendation of BIDA.
            </p>
            <p>
               3. &nbsp; &nbsp; <span style="margin-left: 90px;">Kind attention is being drawn to the abovementioned subject and references.</span> Please be informed that Bangladesh Investment Development Authority (BIDA-Former BOI) has registered the following industrial entity vide Memorandum no. 2 referred above:
            </p>
            <p>
                4. &nbsp;&nbsp; <span style="margin-left: 90px;">Accordingly, BIDA recommends that the {{ $ircAdhocName }} Ad Hoc Industrial Import Registration Certificate (IRC) of the</span> industrial entity be regularized in order to maintain continuity of production @if ($appInfo->irc_regular_purpose_id == 2)
                Increasing
                @elseif ($appInfo->irc_regular_purpose_id == 3)
                    Decreasing
                @else
                @endif
                its six-monthly entitlement from BDT 
                @if ($appInfo->irc_purpose_id == 1)
                    {{ $appInfo->ins_apc_half_yearly_import_total }}
                @elseif ($appInfo->irc_purpose_id == 2)
                    {{  $appInfo->first_em_lc_total_five_percent }}                       
                @else
                    {{  'Raw Materials '.$appInfo->ins_apc_half_yearly_import_total }}                       
                @endif
                @if ($appInfo->irc_regular_purpose_id != 1)
                    to BDT
                    @if ($appInfo->irc_purpose_id == 1)
                        {{ floatval(isset($appInfo->apc_half_yearly_import_total) ? $appInfo->apc_half_yearly_import_total : 0) + floatval(isset($appInfo->apc_half_yearly_import_other) ? $appInfo->apc_half_yearly_import_other : 0) }}
                    @elseif ($appInfo->irc_purpose_id == 2)
                        {{  $appInfo->em_lc_total_taka_mil }}                       
                    @else
                        {{  floatval(isset($appInfo->apc_half_yearly_import_total) ? $appInfo->apc_half_yearly_import_total : 0) + floatval(isset($appInfo->apc_half_yearly_import_other) ? $appInfo->apc_half_yearly_import_other : 0).' and Spare Parts BDT '.$appInfo->first_em_lc_total_five_percent.' to BDT '.$appInfo->em_lc_total_taka_mil }}                       
                    @endif
                @endif
                subject to compliance with the Import Policy Order 2021-24 (except for prohibited goods and controlled goods) and other applicable laws and regulations.
            </p>
            @if ($appInfo->chnage_bank_info == 'yes')
                <p>
                    5. &nbsp;&nbsp; <span style="margin-left: 90px;"> It is further recommended to change the lien bank from {{ $appInfo->bank_name }}, Branch name: {{ $appInfo->bank_branch_name }} to {{ $appInfo->n_name }}, Branch name: {{ $appInfo->n_branch_name }}.</span>
                </p>
            @endif
            @if(!empty($appInfo->approval_copy_remarks))
                <p>
                    {{ $appInfo->chnage_bank_info == 'yes' ? '6. &nbsp;&nbsp;' : '5. &nbsp;&nbsp;' }} {{ $appInfo->approval_copy_remarks }}
                </p>
            @endif
        </div>
        <br>
    </div>

    <br>
    <div class="row">
        <div class="col-md-12">
            <div style="width: 55%;">
                <div style="text-align:left">
                    Chief Controller<br>
                    Office of the Chief Controller of Imports and Exports<br>
                    NSC Tower (14th Floor)<br>
                    62/3 Purana Paltan, Dhaka 1000<br><br>
                </div>
            </div>
            <br>
            <table width="100%" style="margin-bottom: 10px;" aria-label="Detailed Report Data Table">
                <thead>
                    <tr class="d-none">
                        {{-- <th aria-hidden="true"  scope="col"></th> --}}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="75%" style="padding: 0">
                            <strong>Tracking No: </strong> {{ !empty($appInfo->tracking_no) ? $appInfo->tracking_no : '' }}
                        </td>
                        <td width="25%" style="padding: 0; text-align: right">
                            <strong>Date:</strong> {{ !empty($appInfo->app_approved_date) ? \Carbon\Carbon::parse($appInfo->app_approved_date)->format('d-M-Y') : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div>
                Copy forwarded for information and necessary action :<br>
                1. General Manager, Statistical Division, Bangladesh Bank, Head Office, Motijheel C/A, Dhaka<br>
                2. Commissioner, Custom’s House, Dhaka<br>
                3. Director, Monitoring & Evaluation Compliance, BIDA.<br>
                4. {{ $appInfo->ceo_designation }}, {{ $appInfo->company_name }}
            </div>
            
            <div>
                @if(!empty($director))
                <div style="text-align: right;">
                    @if(!empty($director_signature))
                        <img src="{{ $director_signature }}" width="70" alt="Director Signature" /><br>
                    @endif
                    {{ !empty($director->signer_name) ? '('.$director->signer_name.')' : '' }}<br>
                    {{ !empty($director->signer_designation) ? $director->signer_designation : '' }}<br>
                </div>
                @endif
            </div>
            
        </div>
    </div>
</div>
</body>
</html>