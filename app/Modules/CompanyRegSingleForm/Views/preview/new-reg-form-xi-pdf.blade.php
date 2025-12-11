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
                        <div id="agreementheader" class="text-center">
                            <p class="text-center"><b>Form XI</b></p>
                            <p>Agreement to take qualification shares in proposed company</p>
                            <h4 style="margin: 0px;">The Companies Act,1994</h4>
                            <p>(see. Section 92)</p>
                        </div>
                        <div class="text-left">
                            <p>Contract By Directors to take and pay for qualification shares in
                                <strong>{{$appInfo->verified_company_name}}</strong>
                                to be signed and
                                filed pursuant to section 92 (III)(IV) of the companies Act 1994</p>
                            <p>Presented for Filling By : <strong> {{ $witnessDataFiled->name }}</strong></p>
                            <p>We, the undersigned,having consented to act as Directors by the
                                <strong>{{$appInfo->verified_company_name}}</strong>,
                                do each hereby agree to
                                take from the said Company and pay for the
                                <strong>{{ $appInfo->no_of_qualification_share }}</strong> shares of
                                <strong>{{ $appInfo->value_of_qualification_share }}</strong> each,being the number of
                                shares prescribed
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

                                {{--rakibul Hasan--}}

                                @foreach($directors as $director)
                                    <tr>

                                        <th scope="row">{{$director->serial_number}}</th>
                                        <?php
                                        $path="";
                                        if ($director->digital_signature !=""){
                                            $path = 'rjsc_newreg_digital_signature/'. $director->digital_signature;
                                        }
                                        ?>
                                        <td class="text-center">


                                            @if($path !="")
                                                @if(file_exists(public_path().'/'.$path) )
                                                    <img height="80px" width="120px" src="{{$path}}" alt=rjsc_newreg_digital_signature">
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{$director->usual_residential_address}}{{','.$director->usual_residential_district_name}}

                                        </td>
                                        <td>{{$director->other_occupation}}</td>
                                    </tr>
                                @endforeach
                                {{--Rakibul End--}}

                                </tbody>
                            </table>
                        </div>
                        <div id="agreementfooter" class="row">
                            <div class="col-md-2">Date</div>
                            <br/>
                            <div class="col-md-6">
                                <p>Witness to the above signature:
                                    <strong>{{  $appInfo->agreement_witness_name }}</strong></p>
                                <p>Address: <strong>{{  $appInfo->agreement_witness_address }}
                                        , {{$appInfo->agreement_witness_district_name}}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
