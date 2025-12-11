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
                    <div class="text-center">
                        <h4><b>Form-VI</b></h4>
                        <p>Notice of situation of Registered office of any change therein</p>
                        <h5><b>THE COMPANIES ACT, 1994</b></h5>
                        <P>( See Section 77 )</P>
                    </div><br><br>

                    <p>Name of the Company: <strong>{{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</strong></p>
                    <p>Presented for filing by: <strong>{{ $witnessDataFiled->name }}</strong></p><br/>

                    <p>To,</p>
                    <p>The Register of Joint Stock Companies</p>
                    <p>{{$nocRjscOffice}}</p><br>
                    <p><strong>{{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</strong>, hereby gives you notice, in accordance with Section 77 of the Companies</p>
                    <p>Act, 1994 that the Registered Office of the Company</p>
                    <p>(a) is situated -</p>
                    <strong>{{ $appInfo->address_entity }}, {{$districts[$appInfo->entity_district_id]}}</strong><br/><br/>
                    <p>(b) was moved from -</p>
                    <p>to</p>
                    <p>on the</p><br/><br/>

                    <div class="row">
                        <div class="col-md-5 pull-right">
                            <div class="text-center">
                                <p>Signature</p>
                                <p>Designation Chairman</p>
                                <P>(State Whether Director, Manager or Secretary)</P>
                            </div>
                        </div>
                    </div><br/>

                    <p>Date............................................. day of...................................................20.........................................................</p><br/>
                    <p>N.B. - The notice must be filed with the Registrar within 28 days of incorporation or of any change, as the</p>
                    <p>case may be.</p>
                    <p>(a) Strike out the portion which does not apply</p>
                </div>
            </div>
        </div>
    </div>
</section>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
</body>
</html>