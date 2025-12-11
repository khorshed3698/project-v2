
    <style>
        #tablepersonellist tbody tr{
            height: 200px;
        }

        #personellistheader p,h4{
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
                    <div class="panel-heading margin-for-preview"><strong><b>List of Personal Consenting to be Directors </b> </strong></div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row" id="personellistheader">
                                <p class="text-center"><b>FORM X</b></p>
                                <p class="text-center"> <b>List of Personal Consenting to be Directors </b>  </p>
                                <p class="text-center"><b>THE COMPANIES ACT, 1994</b></p>
                                <p class="text-center">( See sec. 92 )</p>
                            </div>
                            <div class="row">
                                <div class="col-md-8  col-md-offset-2">
                                    <div class="text-left">
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 180px;">Name of the Company</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Name Here</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span>List of personal who have consented to be Directors</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 300px;">to be filled with the Registrar pursuant to Section 92 (2)</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; width: 180px;">Presented for filling by</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                        <p>
                                            To the Registrar of Joint Atock Companies,
                                        </p>
                                        <p>
                                            I/We, the undersigned, hereby give you notice, pursuant to section 92 (2) of the Companies Act, 1994, that
                                            the following persons have consented to be Directors of the
                                        </p>
                                        <p style="width: 600px; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                        </p>
                                    </div>
                                    <div id="directorlist">
                                        <table id="tablepersonellist" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col" style="text-align: center">Name</th>
                                                <th scope="col" style="text-align: center">Address</th>
                                                <th scope="col" style="text-align: center">Description</th>
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
                                            <p style="width: 400px; display: table;">
                                                <span style="display: table-cell; width: 100px;">Dated this</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                            </p>
                                            <p style="width: 400px; display: table;">
                                                <span style="display: table-cell; width: 60px;">Day of</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                                <span style="display: table-cell; width: 60px;">19</span>
                                                <span style="display: table-cell; border-bottom: 1px solid black;">test</span>
                                            </p>
                                        </div>
                                        <div id="signature" class="col-md-6 text-center">
                                            <p>Signature, Address and description</p>
                                            <p>of application for registration</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-md-offset-5">
                                    <a href="{{ url('/new-reg-page/agreement-page') }}" class="btn btn-info">Continue</a>
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
