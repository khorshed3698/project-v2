<style>
    #app-form label.error {
        display: none !important;
    }
</style>
<section class="content" id="">
    <div class="col-md-12">
        <div class="col-md-12" style="padding:0px;">
            <div class="box" >
                <div class="box-body">

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Declaration of Registration of Company</strong>
                                </div>
                                <div class="col-md-6">
                                    <a href="/new-reg/new-reg-form-i-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                                       target="_blank" class="btn btn-danger btn-xs documentUrl pull-right">
                                        <i class="fa fa-download"></i> <strong> Application Download as PDF</strong>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="text-center col-md-4 col-md-offset-4">
                                <h3><b>Form-I</b></h3>
                                <p>Declaration on Registration of Company</p>
                                <h4><b>THE COMPANIES ACT, 1994</b></h4>
                                <P>( See Section 25 )</P>
                            </div>
                            <br>
                            <div class="col-md-8 col-md-offset-2">
                                <table>
                                    <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p style="width: 450px; display: table;">
                                                <span style="display: table-cell; width: 180px;">Name of the Company:</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Name Here</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span style="margin-left: 40px;">Declaration of compliance with the requirements of the companies act, 1994 made</span></td>
                                    </tr>
                                    <tr>
                                        <td> pursuant to section 25 (2) on behalf of a company proposed to be Registered as the</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; width: 150px;">Presented for filing by</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; width: 20px;"> I,</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                                <span style="display: table-cell; width: 20px;"> of</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>do solemnly and sincerely declare that I am an Advocate* / Attorney/ Apleader entitled</td>
                                    </tr>
                                    <tr>
                                        <td>to appear before High Court who is engaged in the formation of the company/ a</td>
                                    </tr>
                                    <tr>
                                        <td>person named in the Articles as a Director/ Manager/ Secretary of the</td>
                                    </tr>
                                    <tr>

                                        <td>
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                                <span style="display: table-cell; width: 230px;">and and that all the requirements of</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>the Companies Act, 1994 in respact of maters precedent to the registration of the said</td>
                                    </tr>
                                    <tr>
                                        <td>company and incidental there to have been complied with, save only the payment to</td>
                                    </tr>
                                    <tr>
                                        <td>the fees and sums payable on registration and I make the solemn declaration</td>
                                    </tr>
                                    <tr>
                                        <td>conscientiously believing the same to be true.</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="margin-top: 40px;margin-bottom: 30px;">
                                                <span style="margin-left: 600px;">Signature</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Node: The declaration need not to be-</td>
                                    </tr>
                                    <tr>
                                        <td><span style="margin-left: 40px;">(a) Signed before a magistrate or an officer competent to administer others or</span></td>
                                    </tr>
                                    <tr>
                                        <td><span style="margin-left: 40px;">(b) Stamps as a affidavit</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p style="width: 100%; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">* Strike out the portion which does not apply</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="">
                                <div class="col-md-6">
                                    {{--<button class="btn btn-info" value="draft" type="submit">Save as Draft</button>--}}
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="{{ url('/new-reg/new-reg-page/notice-of-situation') }}" class="btn btn-info">Continue</a>
                                </div>
                            </div>


                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>