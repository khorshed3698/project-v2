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

<?php
//    $totalAmount= $paymentInfo->pay_amount + $paymentInfo->transaction_charge_amount + $paymentInfo->vat_amount;
$totalAmount= ($paymentInfo->pay_amount + $paymentInfo->transaction_charge_amount);

?>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">

                <!--Bank Copy-->
                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="30%" style="text-align: left;">
                            <img src="assets/images/bida_logo.png" style="width: 120px"/>
                        </td>
                        <td width="70%" style="text-align: right;">
                            <h4 style="font-size: 18px; color: #092270;">Bangladesh Investment Development Authority(BIDA)</h4>
                            <strong style="font-size: 12px;">{{ trans('messages.authority_text') }}</strong>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: center;">
                            <h4 class="text-center">One Stop Service(OSS)</h4>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="65%" style="text-align: right;">
                            <img src="{{ $barcode_url }}" width="200px" height="20px"/>
                        </td>
                        <td width="35%" style="text-align: right">
                            <p style="border: 1px solid black;">&nbsp;&nbsp; Bank Copy &nbsp;&nbsp;</p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <b style="padding: 5px;">Payment Information:
                            </b>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding-bottom: 1px;">Payment ID : {{$paymentInfo->request_id}}</p>
                        </td>
                        <td style="text-align: right;">
                            <p style="padding-bottom: 1px;">Date: {{date('d-M-Y', strtotime($paymentInfo->payment_date))}}</p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding-bottom: 1px;">Bank Name : Sonali Bank Limited</p>
                        </td>
                    </tr>
                </table>

                <table class="table table-striped table-bordered" cellspacing="0" width="100%" style="padding-bottom: 5px">
                    <thead>
                    <tr>
                        <th style="padding: 5px;">Payment Summary</th>
                        <th class="text-center" style="padding: 5px;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="text-align: left;">
                            <span >Pay Amount</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->pay_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >VAT on pay amount</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->vat_on_pay_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >Transaction charge</span>
                        </td>
                        <td style="text-align: center;">
                            <span> {{$paymentInfo->transaction_charge_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >VAT on transaction charge</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->vat_on_transaction_charge}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >Total Fees</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->total_amount}}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding: 5px;">Amount in words(Taka)
                                : {{ ucfirst(\App\Libraries\CommonFunction::convert_number_to_words($paymentInfo->total_amount)) }} only
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding-bottom: 1px;">Organization Name
                                : {{ $companyName }}
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="50%">
                            <p style="padding-bottom: 1px;">Depositor Name
                                : {{$paymentInfo->contact_name}}
                            </p>
                        </td>
                        <td width="50%">
                            <p style="padding-bottom: 1px;">Depositor Mobile Number
                                : {{$paymentInfo->contact_no}}
                            </p>
                        </td>
                    </tr>
                </table>
                
                @if(in_array($paymentInfo->process_type_id, [127]))
                    <table aria-label="Detailed Report Data Table" width="100%">
                        <tr>
                            <th aria-hidden="true" scope="col"></th>
                        </tr>
                        <tr>
                            <td width="100%" style="text-align: left">
                                <p style="padding: 5px;">Name and Passport No. : {{isset($emp_info) && !empty($emp_info->emp_name) ? $emp_info->emp_name.' , ' : ''}} {{ isset($emp_info) && !empty($emp_info->emp_passport_no) ? $emp_info->emp_passport_no : '' }} </p>
                            </td>
                        </tr>
                    </table>
                @endif
                <br/>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="45%">
                            <span>_____________________</span><br/>
                            <span>Depositor signature</span>
                        </td>
                        <td width="40%">
                            <span>____________</span><br/>
                            <span>Received By</span>
                        </td>
                        <td width="15%">
                            <span style="text-align: right;">______________</span><br/>
                            <span style="text-align: right;">Approved By</span>
                        </td>
                    </tr>
                </table>
                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            <strong>
                                <small>Note: Payment could only be made through any branches of Sonali Bank Ltd.(Online
                                    Branch).
                                </small>
                            </strong>
                        </td>
                    </tr>
                </table>


                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: center;">
                            <small>Voucher generated by BIDA One Stop Service System & Manage by Business Automation Ltd.</small>
                        </td>
                    </tr>
                </table>

                <img src="assets/images/divider.png" style="width: 100%"/>

                <!--Applicent Copy-->
                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="30%" style="text-align: left;">
                            <img src="assets/images/bida_logo.png" style="width: 120px"/>
                        </td>
                        <td width="70%" style="text-align: right;">
                            <h4 style="font-size: 18px; color: #092270;">Bangladesh Investment Development Authority(BIDA)</h4>
                            <strong style="font-size: 12px;">{{ trans('messages.authority_text') }}</strong>
                        </td>
                    </tr>
                </table>


                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: center;">
                            <h4 class="text-center">One Stop Service(OSS)</h4>

                        </td>
                    </tr>
                </table>


                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="65%" style="text-align: right;">
                            <img src="{{ $barcode_url }}" width="200px" height="20px"/>
                        </td>
                        <td width="35%" style="text-align: right">
                            <p style="border: 1px solid black;">&nbsp;&nbsp; Applicant Copy &nbsp;&nbsp;</p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <b >Payment Information:
                            </b>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding-bottom: 1px;">Payment ID : {{$paymentInfo->request_id}}</p>
                        </td>
                        <td style="text-align: right;">
                            <p style="padding-bottom: 1px;">Date: {{date('d-M-Y', strtotime($paymentInfo->payment_date))}}</p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding-bottom: 1px;">Bank Name : Sonali Bank Limited</p>
                        </td>
                    </tr>
                </table>

                <table class="table table-striped table-bordered" cellspacing="0" width="100%" style="padding-bottom: 5px">
                    <thead>
                    <tr>
                        <th style="padding: 5px;">Payment Summary</th>
                        <th class="text-center" style="padding: 5px;">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="text-align: left;">
                            <span >Pay Amount</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->pay_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >VAT on pay amount</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->vat_on_pay_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >Transaction charge</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->transaction_charge_amount}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >VAT on transaction charge</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->vat_on_transaction_charge}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <span >Total Fees</span>
                        </td>
                        <td style="text-align: center;">
                            <span > {{$paymentInfo->total_amount}}</span>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding-bottom: 1px;">Amount in words(Taka)
                                : {{ ucfirst(\App\Libraries\CommonFunction::convert_number_to_words($paymentInfo->total_amount)) }} only
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: left;">
                            <p style="padding-bottom: 1px;">Organization Name
                                : {{ $companyName }}
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="50%">
                            <p style="padding-bottom: 1px;">Depositor Name
                                : {{$paymentInfo->contact_name}}
                            </p>
                        </td>
                        <td width="50%">
                            <p style="padding-bottom: 1px;">Depositor Mobile Number
                                : {{$paymentInfo->contact_no}}
                            </p>
                        </td>
                    </tr>
                </table>
                @if(in_array($paymentInfo->process_type_id, [127]))
                    <table aria-label="Detailed Report Data Table" width="100%">
                        <tr>
                            <th aria-hidden="true" scope="col"></th>
                        </tr>
                        <tr>
                            <td width="100%" style="text-align: left">
                                <p style="padding: 5px;">Name and Passport No. : {{isset($emp_info) && !empty($emp_info->emp_name) ? $emp_info->emp_name.' , ' : ''}} {{ isset($emp_info) && !empty($emp_info->emp_passport_no) ? $emp_info->emp_passport_no : '' }} </p>
                            </td>
                        </tr>
                    </table>
                @endif
                <br/>

                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td width="45%">
                            <span>_____________________</span><br/>
                            <span>Depositor signature</span>
                        </td>
                        <td width="40%">
                            <span>____________</span><br/>
                            <span>Received By</span>
                        </td>
                        <td width="15%">
                            <span style="text-align: right;">______________</span><br/>
                            <span style="text-align: right;">Approved By</span>
                        </td>
                    </tr>
                </table>



                <table class="" cellspacing="0" width="100%">
                    <tr>
                        <td>
                            <strong>
                                <small>Note: Payment could only be made through any branches of Sonali Bank Ltd.(Online
                                    Branch).
                                </small>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                            <small>Voucher generated by BIDA One Stop Service System & Manage by Business Automation Ltd.</small>

                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>

</body>
</html>