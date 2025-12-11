<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row text-center">
                    <h4 class="text-center"><b>FORM IX</b></h4>
                    <p class="text-center">Consent of director to Act</p>
                    <p class="text-center"><b>THE COMPANIES ACT, 1994</b></p>
                    <p class="text-center">( See sec. 92 )</p>
                </div><br/><br/>

                <div class="row">
                    <p>Name of the Company: <strong>{{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</strong></p>
                    <p>Consent of act as Director/Directors of the <strong>{{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</strong> to be</p>
                    <p>signed and filled pursuant to section 92 (1) (Ka)</p>
                    <p>Presented for filling by: <strong>{{ $witnessDataFiled->name }}</strong></p>
                    <p>To the Registrar of Joint Stock Companies & Firms,</p>
                    <p>I/We, under signed, hereby testify my/our consent to act as Director/Directors</p>
                    <p>of the <strong>{{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</strong></p>
                    <p>Pursuant to section 92(1)(Ka) of the Companies Act, 1994.</p>
                </div><br/>

                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="8%" class="text-center" style="padding: 7px;border: 1px solid black;">SI</th>
                                <th width="20%" class="text-center" style="padding: 7px;border: 1px solid black;">Signature</th>
                                <th width="30%" class="text-center" style="padding: 7px;border: 1px solid black;">Address</th>
                                <th scope="22%" class="text-center" style="padding: 7px;border: 1px solid black;">Description</th>
                                <th width="20%" class="text-center" style="padding: 7px;border: 1px solid black;">Photo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($directors as $key =>$v_dir)
                                <tr>
{{--                                    <td class="text-center" style="padding: 7px;border: 1px solid black;">{{ $v_dir->serial_number }}</td>--}}
                                    <td class="text-center" style="padding: 7px;border: 1px solid black;">{{ $key+1 }}</td>

                                    {{--<td style="padding: 7px;border: 1px solid black;">{{ $v_dir->corporation_body_name }}</td>--}}

                                    <td style="padding: 7px;border: 1px solid black;">
                                        <?php
                                        $path="";
                                        if ($v_dir->digital_signature !=""){
                                            $path = 'rjsc_newreg_digital_signature/'. $v_dir->digital_signature;
                                        }
                                        ?>
                                        @if($path !="")
                                            @if(file_exists(public_path().'/'.$path) )
                                                <img height="80px" width="120px" src="{{$path}}">
                                            @endif
                                        @endif
                                            <br>
                                            <br>
                                            <span>({{ $v_dir->corporation_body_name }})</span>
                                    </td>
                                    <td style="padding: 7px;border: 1px solid black;">{{ $v_dir->usual_residential_address }},
                                        @if(isset($v_dir->usual_residential_district_id))
                                            {{$districts[$v_dir->usual_residential_district_id]}}
                                            @else

                                        @endif
                                    </td>
                                    <td class="text-center" style="padding: 7px;border: 1px solid black;"></td>
                                    <td style="padding: 7px;border: 1px solid black; text-align: center;">
                                        <?php
                                        $path="";
                                        if ($v_dir->subscriber_photo !=""){
                                            $path = 'subscriber_photo/'. $v_dir->subscriber_photo;
                                        }
                                        ?>
                                        @if($path !="")
                                            @if(file_exists(public_path().'/'.$path) )
                                                <img height="100px" width="90px" src="{{$path}}">
                                            @endif
                                        @endif
                                        <br>
                                        <br>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><br/>

                <div class="row">
                    <p>Dated this .......................................................................... day of ....................................................................... 20</p>
                    <p>________________________________________________________________________________________________________________</p>
                    <p><b>NOTES-</b> If a Director signs by "his agent authorised writing" the authority must be produced and a copy attached</p>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>