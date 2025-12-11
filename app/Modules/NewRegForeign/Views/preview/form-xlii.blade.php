<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/style.css") }}"/>
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
                    <div class="col-md-12" style="text-align: center">

                        <br>
                    </div>
                   <pre style="border: 0;background-color: transparent;">To
   THE REGISTRAR OF JOINT STOCK COMPANIES,
   BANGLADESH

   The ,{{$appInfo->name_of_entity}} Limited
incorporated in and
having places of business in Bangladesh, hereby give you notice, in
accordance with clause (e) of sub-section (1) of Section 379 that the office
situated at {{$appInfo->address_entity}}
in Bangladesh shall be deemed to be the principal place of business of the
Company in Bangladesh.

</pre>

<pre style="border: 0;background-color: transparent;">
Signature or Signatures of any one or	…
    <?php
    $path="";
    if ($authorizedPerson->digital_signature !=""){
        $path = 'rjsc_newreg_digital_signature/'. $authorizedPerson->digital_signature;
    }
    ?>
    @if($path !="")
        @if(file_exists(public_path().'/'.$path) )
            <img height="80px" width="120px" src="{{$path}}">
        @endif
    @endif
more of the persons authorized under
Section 379 (I) (d) of the Companies	....................................................................................
Act, 1941, or some other person in
Bangladesh duly authorised by the	....................................................................................
Company.
</pre>

                </div>
            </div>
        </div>
    </div>
</section>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
</body>
</html>