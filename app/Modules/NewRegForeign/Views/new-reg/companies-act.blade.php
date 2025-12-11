
    <style>
        #companiesacttable tbody tr{
            height: 200px;

        }
        #agrementheader{
            margin-top:20px;
            margin-bottom: 20px;
        }
        #agrementheader p,h4{
            margin:0px;
        }
        #directorlist{
            margin-top:20px;
            margin-bottom: 50px;
        }
        #agrementfooter{
            margin-bottom: 50px;
        }
        #signature p{
            margin:0px;
        }

    </style>
    <div class="col-md-12">
        <div class="box"  id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}

                <div class="panel panel-info">
                    <div class="panel-heading margin-for-preview"><strong>COMPANIES ACT, 1994</strong></div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <p class="text-center"><b>FORM IX</b></p>
                                <p class="text-center">Consent of director to Act</p>
                                <p class="text-center"><b>THE COMPANIES ACT, 1994</b></p>
                                <p class="text-center">( See sec. 92 )</p>
                            </div>
                            <div class="row">
                                <div class="col-md-8   col-md-offset-2">
                                    <div id="agrementheader"  class="text-center">

                                    </div>
                                    <div class="text-left">
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 180px;">Name of the Company</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Name Here</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 300px;">Consent of act as Director/Directors of the</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 350px;">to be signed and filled pursuant to section 92 (1) (Ka)</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 180px;">Presented for filling by</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 350px;">To the Registrar of Joint Stock Companies & Firms,</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p>
                                            I/We, under signed, hereby testify my/our consent to act as Director/Directors of the
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p>
                                            Pursuant to section 92(1)(Ka) of the Companies Act, 1994.
                                        </p>
                                    </div>
                                    <div id="directorlist">
                                        <table id="companiesacttable"  class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">Signatures</th>
                                                <th scope="col">Address</th>
                                                <th scope="col">Description</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="agrementfooter" class="row">
                                        <div class="col-md-6">
                                            <p style="width: 700px; display: table;">
                                                <span style="display: table-cell; width: 100px;">Dated this</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                                <span style="display: table-cell; width: 60px;">day of</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                                <span style="display: table-cell; width: 60px;">19</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                            </p>
                                            <p style="width: 700px; display: table;">
                                                <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                            </p>
                                            <p style="width: 700px; display: table;">
                                                Notes--- It a Director signs by "his agent authorized writing" the authority must be produced and
                                                a copy attached.
                                            </p>
                                        </div>


                                    </div>
                                    <div class="">
                                        <div class="col-md-6">
                                            {{--<button class="btn btn-info" value="draft" type="submit">Save as Draft</button>--}}
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <a href="{{ url('/new-reg-page/agreement-page') }}" class="btn btn-info">Continue</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>



                    </div>
                </div>
            </div>
        </div>
    </div>

@section('footer-script')
@endsection
