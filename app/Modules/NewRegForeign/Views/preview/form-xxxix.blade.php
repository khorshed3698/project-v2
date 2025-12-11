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
                        <strong>Form XXXIX</strong><br/>
                        Return of persons authorised to accept service under Section 379<br>
                        <strong>THE COMPANIES ACT, 1994</strong><br>
                        (See Section 379)
                    </div>
                   <pre style="border: 0;background-color: transparent;">Name of the Company : {{$appInfo->name_of_entity}}
Return pursuant to Section 379 (I) by -
         The in corporated
in which has a place of business in
Bangladesh at    of
the names and address of some one or more persons resident in Bangladesh
authorised to accept on behalf of the company service of process and any
notices required to be served on the company.

    Present for filing by : {{$witnessDataFiled->name}}
    List of persons authorised to accept service of behalf of the company</pre>
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

<pre style="border: 0;background-color: transparent;">Signature or Signatures of any one or 	....<?php
    $path="";
    if ($authorizedPerson->digital_signature !=""){
        $path = 'rjsc_newreg_digital_signature/'. $authorizedPerson->digital_signature;
    }
    ?>
    @if($path !="")
        @if(file_exists(public_path().'/'.$path) )
            <img height="80px" width="120px" src="{{$path}}">
        @endif
    @endif..............
more of the persons authorized under Section
379 (1) (d) of the Companies Act, 1941	...............................................................................
 or some other person in Bangladesh
Bangladesh duly authorised by the company.	...............................................................................

       Dated the .......................day of ...............................20</pre>

                </div>
            </div>
        </div>
    </div>
</section>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
</body>
</html>