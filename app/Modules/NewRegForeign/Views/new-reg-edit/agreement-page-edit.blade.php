
    <style>
        #agreementtable tbody tr{
            height:50px;
        }

        #agreementheader{
            margin-top:20px;
            margin-bottom: 20px;
        }

        #agreementheader p,h4{
            margin:0px;
        }
        #directorlist{
            margin-top:20px;
            margin-bottom: 30px;
        }
        #agreementfooter{
            margin-bottom: 50px;
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
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 pull-left">
                                <h5><b>Agreement to take qualification shares in proposed company</b></h5></div>
                            </div>
                            <div class="col-md-6 pull-right">
                                <a href="/new-reg/new-reg-form-xi-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                                   target="_blank" class="btn btn-danger btn-xs documentUrl pull-right">
                                    <i class="fa fa-download"></i> <strong> Application Download as PDF</strong>
                                </a>
                            </div>
                        </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-10   col-md-offset-1">
                                    <div id="agreementheader"  class="text-center">
                                        <p class="text-center"><b>Form XI</b></p>
                                        <p>Agreement to take qualification shares in proposed company</p>
                                        <h4 style="margin: 0px;">The Companies Act,1994</h4>
                                        <p>(see. Section 92)</p>
                                    </div>
                                    <div class="text-left">
                                        <p>Contract By Directors to take and pay for qualification shares in to be signed and
                                            filed pursuant to section 92 (III)(IV) of the companies Act 1994</p>
                                        <p>Presented for Filling By</p>
                                        <p>We, the undersigned,having consented to act as Directors by the , do each hereby agree to
                                            take from the said Company and pay for the shares of each,being the number of shares prescribed
                                            qualification for the office of director of said Company</p>
                                    </div>
                                    <div id="directorlist">
                                        <table id="agreementtable" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col" style="text-align: center">sl.</th>
                                                <th scope="col" style="text-align: center">Signatures</th>
                                                <th scope="col" style="text-align: center">Address</th>
                                                <th scope="col" style="text-align: center">Description</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">3</th>
                                                <td >Larry the Bird</td>
                                                <td>@twitter</td>
                                                <td>@fat</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="agreementfooter" class="row">
                                        <div class="col-md-2">Date</div>
                                        <div class="col-md-4">
                                            <p>Witness to the above signature</p>
                                            <p>Address</p>
                                        </div>
                                    </div>
                                </div>





                                <div class="">
                                    <div class="col-md-6">
                                        {{--<button class="btn btn-info" value="draft" type="submit">Save as Draft</button>--}}
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="{{ url('/new-reg-page/particulars-page') }}" class="btn btn-info">Continue</a>
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
    @yield('footer-script2')
@endsection