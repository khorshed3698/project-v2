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
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h4><b>Form-XIV</b></h4>
                            <p>Declaration before commencing business in case of the Company</p>
                            <p>Filing a Statement in lieu of Prospectus</p>
                            <h5><b>The Companies Act, 1994</b></h5>
                            <P>( Ref. Section 150 )</P>
                        </div>
                    </div><br/><br/>

                    <div class="col-md-12">
                        <p>Name of the Company: {{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</p>
                        <p>Declaration that the conditions of section 150 of the Act have been compiled with.</p>
                        <p> Presented for Filling By: {{$appInfo->name}}</p>
                        <p>I, {{$appInfo->name}} of {{$appInfo->address}} {{$districts[$appInfo->district_id]}} </p>
                        <p>being the Secretary/a Director of {{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</p>
                        <p>do solemnly and sincerely declare:</p>

                        <p>That the amount of share capital of the company subject to the payment of the whole amount thereof in case is TK. {{$totalSubscribedShare * $appInfo->value_of_qualification_share}} </p>
                        <p>That the company being one wich does not issue a prospectus inviting the</p>
                        <p>public to subscribe for its shares, there has been filed with the Registrar a statement</p>
                        <p>in lieu of prospectus.</p>
                        <p>That the amount fixed by the Memorandum or Articles and named in the statement</p>
                        <p>as the minimum subscription upon which the, Directors may proceed to allotment</p>
                        <p>is Tk. {{$appInfo->no_of_qualification_share * $appInfo->value_of_qualification_share}}</p>
                        <p>The shares held subject to the payment of the whole amount thereof in cash have</p>
                        <p>been allotted to the amount of Tk {{$totalSubscribedShare * $appInfo->value_of_qualification_share}}</p>
                        <p>That every Director of the company has paid to the company on each of the</p>
                        <p>shares taken or contracted to be taken by him and for which he is liable to pay in cash,</p>
                        <p>a proportion equal to the proportion payable on application and allotment on the shares</p>
                        <p>payable in cash.</p>
                        <p>I declare that the foregoing statements are true to my knowledge and belief.</p><br/>
                        <p>Signature </p>{{$appInfo->declaration_name}}<br/>

                        <p>Designation</p>
                        <p>Dated of the day of</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>