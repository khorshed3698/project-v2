<!DOCTYPE html>
<html lang="en">
<head>
    <title>Import Permission Certificate</title>
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
                        <strong>Date:</strong> {{ date('F j, Y', strtotime($appInfo->approved_date)) }}
                    </td>
                </tr>
                
                </tbody>
            </table>

            <table  style="margin-bottom: 10px;" aria-label="Detailed Report Data Table">
                <tr>
                    {{-- <th aria-hidden="true"  scope="col"></th> --}}
                </tr>
                <tr>
                    <td style="width: 10%;" valign="top"><b>Subject:</b></td>
                    <td style="width: 90%">Recommendation for Issuance of an IMPORT PERMIT for clearance of directly imported capital machinery and spare parts. </td>
                </tr>
            </table>

            <p>
                Dear Sir/Madam,<br>
                Attention is being drawn to the abovementioned subject. Please be informed that the following company is an industrial entity registered with Bangladesh Investment Development Authority (Bida-Former BOI):
            </p>

            {{-- Company Information --}}
            @if (!empty($appInfo->company_name) || !empty($appInfo->company_office_address) || !empty($appInfo->ref_app_tracking_no))
                <table class="table table-bordered" aria-label="Detailed Report Data Table">
                    <thead>
                    <tr class="d-none">
                        {{-- <th aria-hidden="true"  scope="col"></th> --}}
                    </tr>
                    <tr>
                        <td width="30%" style="font-weight: bold;text-align: center;">Name of the Entity</td>
                        <td width="35%" style="font-weight: bold;text-align: center;">Address of the Entity</td>
                        <td width="35%" style="font-weight: bold;text-align: center;">Registration Number</td>
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
                                {{ !empty($appInfo->reg_no) ? $appInfo->reg_no : '' }}
                            </td>
                        </tr>
                        <tr>
                            <td> Factory: {{ !empty($appInfo->company_factory_address) ? $appInfo->company_factory_address : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <p>
                Following machinery and spare parts have been directly imported by the foreign investor(s) of the above mentioned entity for installation at the Factory referred above:
            </p>

            {{-- List Of Mechinery Imported Spare Parts --}}
            <table class="table table-bordered" aria-label="Detailed Report Data Table">
                <thead>
                    <tr>
                        <th scope="col" rowspan="2">Sl. </th>
                        <th scope="col" colspan="5">Description of the Machinery and Spare Parts</th>
                        <th scope="col" rowspan="2">HS CODE</th>
                        <th scope="col" rowspan="2">Invoice Number and Date</th>
                        <th scope="col" rowspan="2"> Bill of Landing Number and Date </th>
                    </tr>
                    <tr>
                        <th scope="col"> Name of machineries with Standard Accessories </th>
                        <th scope="col"> Machinery Type </th>
                        <th scope="col"> Quantity </th>
                        <th scope="col"> Price </th>
                        <th scope="col"> Total Price(BDT) </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($listOfMechineryImportedSpare) > 0)
                            <?php $i = 1; ?>
                        @foreach($listOfMechineryImportedSpare as $importedSpare)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $importedSpare->name }}</td>
                                <td>{{ ucfirst($importedSpare->machinery_type) }}</td>
                                <td>{{ $importedSpare->required_quantity }}</td>
                                <td>{{ $importedSpare->total_value_equivalent_usd }} ({{ $importedSpare->currency_code }})</td>
                                <td>{{ $importedSpare->total_value_as_per_invoice }}</td>
                                <td>{{ $importedSpare->hs_code }}</td>
                                <td>{{ $importedSpare->invoice_no }} , {{\Carbon\Carbon::parse($importedSpare->invoice_date)->format('d-M-Y')}}</td>
                                <td>{{ $importedSpare->bill_loading_no }}, {{ \Carbon\Carbon::parse($importedSpare->bill_loading_date)->format('d-M-Y')}}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <p>
                3. The machinery and spare parts to be cleared have been approved by BIDA as per the eligibility and 
                specific provision of the Import Policy Order.
            </p>

            <p>
                4. The undersigned is issuing this recommendation for clearing the capital machinery and 
                spare parts directly imported according to the Clause no. 8(4)(CHA) of the Import Policy Order 2021-2024.
            </p>
            @if(!empty($appInfo->approval_copy_remarks))
                <p>
                    5. {{ $appInfo->approval_copy_remarks }}
                </p>
            @endif

        </div>
        <br>
    </div>

    <br>
    <div class="row">
        <div class="col-md-12">
            <div style="float: left;width: 55%;">
                <div style="text-align:left">
                    Chief Controller<br>
                    Office of the Chief Controller of Imports and Exports<br>
                    NSC Tower (14th Floor)<br>
                    62/3 Purana Paltan, Dhaka 1000<br><br>
                </div>
            </div>

            <div style="text-align: center; ">
                <div style="padding-left: 45%;">
                    Your Faithfully <br>
                    <img src="{{ $director_signature }}" width="70" alt="Director Signature" /><br>
                    ({{ $director->signer_name }})<br>
                    {{ $director->signer_designation }}<br>
                    Phone: {{ $director->signer_mobile }}<br>
                    Email: {{ $director->signer_email }}<br>
                </div>
            </div>

            <br><br>

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
                        <strong>Date:</strong> {{ date('F j, Y', strtotime($appInfo->approved_date)) }}
                    </td>
                </tr>
                </tbody>
            </table>

            <div>
                Copy forwarded for information and necessary action :<br>
                1. General Manager, Statistical Division, Bangladesh Bank, Head Office, Motijheel C/A, Dhaka<br>
                2. Commissioner, Custom’s House, Kurmitola, Dhaka. / Commissioner, Custom’s House, Chattogram.<br>
                3. {{ $appInfo->ceo_full_name }}, {{ $appInfo->ceo_designation }}, {{ $appInfo->company_name }}
            </div>
        </div>
    </div>
</div>
</body>
</html>