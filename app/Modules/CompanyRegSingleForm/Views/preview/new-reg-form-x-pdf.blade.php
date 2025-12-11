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
                        <div class="col-md-10   col-md-offset-1">
                            <div id="agreementheader"  class="text-center">
                                <p class="text-center"><b>FORM X</b></p>
                                <p class="text-center"> <b>List of Personal Consenting to be Directors </b>  </p>
                                <p class="text-center"><b>THE COMPANIES ACT, 1994</b></p>
                                <p class="text-center">( See sec. 92 )</p>
                            </div>
                            <div class="text-left">
                                <p style="width: 600px; display: table;">
                                    <span style="display: table-cell; width: 180px;">Name of the Company: <strong>{{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids) }}</strong></span>
                                </p>
                                <p>List of personal who have consented to be Directors of the <strong>{{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids) }}</strong>  to be filled
                                    with the </p>
                                <p>Registrar pursuant to Section 92 (2)</p>
                                <p>Presented for Filling By : <strong>
                                        @if(count($witnessDataFiled)>0)
                                        {{ $witnessDataFiled->name }}
                                            @else
                                            N/A
                                        @endif
                                    </strong></p>
                                <p>
                                    To the Registrar of Joint Atock Companies & Firms.
                                </p>
                                <p>
                                    I/We, the undersigned, hereby give you notice, pursuant to section 92 (2) of the Companies Act, 1994, that
                                    the following persons have consented to be Directors of the <strong>{{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids) }}</strong>
                                </p>
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
                                    {{--rakibul Hasan--}}
                                    @if(count($directors)>0)
                                        @foreach($directors as $director)
                                            <tr>
                                                <th scope="row">{{$director->serial_number}}</th>
                                                <?php /*$path='rjsc_newreg_digital_signature/'.$director->digital_signature*/?>
                                                <td class="text-center">{{$director->corporation_body_name}}</td>
                                                <td>{{$director->usual_residential_address}}{{','.$districts[$director->usual_residential_district_id]}}

                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    {{--Rakibul End--}}

                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-10" style="margin-top: 50px;">
                        <table style="padding: 5px; width: 100%;">
                            <tr>
                                <td style="width: 40%;">
                                    Date:
                                </td>
                                <td class="text-center">
                                    @if($signature!="")
                                        <?php  $path='rjsc_newreg_digital_signature/'.$signature;?>
                                        @if(file_exists($path))
                                            <img height="60px" width="120px" src="{{$path}}" alt=rjsc_newreg_digital_signature">
                                        @endif
                                    @endif



                                </td>
                            </tr>
                            <tr>
                                <td style="width: 40%;">
                                </td>
                                <td class="text-center">
                                    Signature, Address and description<br>
                                    of application for registration
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
</body>
</html>