<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
          integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body>
<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h3><b>Form-I</b></h3>
                            <p>Declaration on Registration of Company</p>
                            <h4><b>THE COMPANIES ACT, 1994</b></h4>
                            <P>( Ref. Section 25 )</P>
                        </div>
                    </div><br>
                    <table width="100%" cellpadding="10">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    <p style="width: 450px; display: table;">
                                        <span style="display: table-cell; width: 180px;">Name of the Company:</span>
                                        <span style="display: table-cell;"><strong>{{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</strong></span>
                                    </p>
                                </td>
                            </tr>
                            <tr style="margin-left: 40px;">
                                <td>
                                    <span>Declaration of compliance with the requirements of the companies act, 1994 made pursuant to section 25 (2) on behalf of a company proposed to be Registered as the
                                        {{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}} Presented for filing by {{ $appInfo->declaration_name }} </span>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <p style="width: 100%; display: table;">
                                        <span style="text-align: justify;display: table-cell; width: 20px;"> I, {{ $appInfo->declaration_name}} of {{$appInfo->declaration_address }}, {{$appInfo->area_nm}}
                                            o solemnly and sincerely declare that I am an Advocate / Attorney/ A pleader entitled to appear before High Court who is engaged in the formation of the company/ a person named in the Articles as a {{$appInfo->rcptitle}} of the {{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}} and and that all the requirements of the Companies Act,
                                            1994 in respact of maters precedent to the registration of the said company and incidental there to have been complied with, save only the payment to the fees and sums payable on registration and I make the solemn declaration conscientiously believing the same to be true.</span>

                                    </p>
                                </td>
                            </tr>



                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>

                            <tr>
                                <td>
                                    <p style="margin-top: 40px;margin-bottom: 30px;">
                                        <span style="margin-left: 600px;">Signature</span>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Note: The declaration need not to be-</td>
                            </tr>
                            <tr>
                                <td><span style="margin-left: 40px;">(a) Signed before a magistrate or an officer competent to administer others or</span></td>
                            </tr>
                            <tr>
                                <td><span style="margin-left: 40px;">(b) Stamps as a affidavit</span></td>
                            </tr>
                            <tr>
                                <td>

                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td class="text-center">* Strike out the portion which does not apply</td>
                            </tr>
                            -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>