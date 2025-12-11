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
                                <h4 class="text-center"><b>Form XII</b></h4>
                                <h5>PARTICULARS OF THE DIRECTORS,MANAGER AND MANAGING AGENTS AND OF ANY
                                    THEREIN</h5>
                                <p> <b>The Companies Act,1994</b> (see. Section 92)</p><br>
                            </div>
                            <div class="text-left">
                                <p style="display: table;">
                                    <span style="display: table-cell; width: 180px;">Name of the Company:</span>
                                    <span style="display: table-cell";><b>{{\App\Modules\NewReg\Controllers\GetCompanyForeignController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}},</b>
                                    Reg No: , Reg Date: </span>
                                </p>
                                <p style="display: table;">
                                    <span style="display: table-cell; width: 180px;">Entity Address: {{$appInfo->address_entity}}</span>
                                </p>
                                <p style="display: table;">
                                    <span style="display: table-cell; width: 180px;">Presented for Filling by: <b>{{$witnessDataFiled->name}}</b></span>
                                </p>
                            </div>
                            <div id="directorlist">
                                <table id="agreementtable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="col-md-1 text-center" scope="col" style="padding-bottom:45px !important;">SL</th>
                                        <th class="col-md-2 text-center" scope="col" style="padding-bottom:35px !important;"><small>The present Names or Names and Surnames</small></th>
                                        <th class="col-md-1 text-center" scope="col" style="padding-bottom:35px !important; display:block"><small>Nationality</small></th>
                                        <th class="col-md-1 text-center" scope="col"><small>Nationality of Origin (other than the present Nationality)</small></th>
                                        <th class="col-md-3 text-center" scope="col" style="padding-bottom:35px !important; display:block"><small>Usual Residential Address</small></th>
                                        <th class="col-md-1 text-center" scope="col"><small style="font-size:10px">Other business, occupation and Directorship, in any, if none, state so (b)</small></th>
                                        <th class="col-md-1 text-center" scope="col" style="padding-bottom:35px !important;"><small>Date of Appointment or Change</small></th>
                                        <th class="col-md-1 text-center" scope="col" style="padding-bottom:35px !important;"><small>Change (c)</small></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1; ?>
                                    @foreach($subscriberList as $subscriber)
                                        <tr>
                                            <td style="padding: 3px;" class="text-center" scope="row">{{$i}}</td>
                                            <td style="padding: 3px;"><p>{{$subscriber->corporation_body_name}}</p></td>
                                            <td style="padding: 3px;"><p>{{$subscriber->nationality}}</p></td>
                                            <td style="padding: 3px;"><p>{{$subscriber->present_nationality}}</p></td>
                                            <td style="padding: 3px;"><p>{!! $subscriber->usual_residential_address !!}</p></td>
                                            <td style="padding: 3px;"><p>{!! $subscriber->directorship_in_other_company !!}</p></td>
                                            <td style="padding: 3px;"><small>{!! $subscriber->appointment_date !!}</small></td>
                                            <td style="padding: 3px;"><small>{!! $subscriber->dob !!}</small></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div id="agrementfooter" class="row">
                                <div  style="width:45%; float:left">
                                    <span style="display: table-cell; width: 100px;">Date:</span>
                                </div>
                                <div class="col-md-6"  style="width:50%;float:right">
                                    <span style="float:right"> (Signature)</span><br>
                                    <p style="float:right">(State whether Director,Managing or Managing Agents)</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <span class="text-justify ">
                                    <p>
                                        <small>(a) In the case of a corporation its corporate name and registered or principal office
                                    should be shown .</small>
                                    </p>
                                    <p>
                                        <small>(b)In the case of as individual who has no business occupation but
                                    any other directorship or directorships particular of the directorship or same of these
                                    directorship must be entered. A complete list of the Directors, Manager, Managing
                                    Agents shown as existing in the last particulars delivered should always be gives.</small>
                                    </p>
                                    <p>
                                        <small>(c)A note of the change the last list should be made in this columns by placing against a
                                    new directors name the worlds in place of .............. by writing against a new Directors,
                                    name the worlds "deed" resigned to as the case may be.</small>
                                    </p>
                                    <p>
                                        <small>(d) In case of a firm the full name address and nationality of each partner and the date on which each became a partner.</small>
                                    </p>
                                    <p><small>(e) In case of multiple representatives, user comma(,) to separate names .</small></p>

                                </span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>