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
                        <strong>Form XXXVII</strong><br/>
                        Notice of the address of the Registered of principal Office of the Company<br>
                        <strong>THE COMPANIES ACT, 1994</strong><br><br>
                    </div>
                   <pre style="border: 0;background-color: transparent;">                      Name of the Company: {{$appInfo->name_of_entity}}
                      Presented for Filing by:  {{$witnessDataFiled->name}}

Notice is hereby given{...............} section 379 (1) (b) of the Companies
Act, 1941 by the   {{$appInfo->name_of_entity}},
incorporated in
{{$appInfo->country_origin_name}} having a place of business in Bangladesh
,
that the situation of the registered or principal office of the company ( in Country
of origin) in :{{$appInfo->country_origin_name}},{{$appInfo->address_entity_origin}}
</pre>
                    <table>

                        <tr>
                            <th>Sl.</th>
                            <th>Name of Persons.</th>
                            <th>Residential
                                Address
                            </th>
                            <th>Nationality</th>
                            <th>Description of
                                occupations
                            </th>
                        </tr>
                        <?php  $i=0; ?>
                        @foreach($nameOfPersons as $nameOfPerson)
                            <?php
                            $i=$i+1; ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>{{$nameOfPerson->corporation_body_name}}</td>
                            <td>{{$nameOfPerson->usual_residential_address}}</td>
                            <td>{{$nameOfPerson->present_nationality_id}}</td>
                            <td>{{$nameOfPerson->other_occupation}}</td>
                        </tr>
                        @endforeach
                    </table>

<pre style="border: 0;background-color: transparent;">
Signature or Signatures of any one or 	  <?php
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
more of the persons authorised under
Section 379 (1) (d) of the Companies
Act, 1941, or some other person in
Bangladesh duly authorised by the
Company



 Dated the .......................day of ...............................20

N.B. This notice must be filed within one month from the establishment of a place
       of business in Bangladesh
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