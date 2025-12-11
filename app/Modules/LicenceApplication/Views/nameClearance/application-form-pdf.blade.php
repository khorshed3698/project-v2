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

                    <div class="col-md-12" style="text-align: center">
                        <img src="assets/images/bida_logo.png" style="width: 100px"/><br/>
                        <br>

                        Bangladesh Investment Development Authority (BIDA)<br/>
                        Apply for Name Clearance to Bangladesh

                    </div>
                    <div class="panel panel-info" id="inputForm">
                        <div class="panel-heading">
                            <img class="img-responsive pull-left"
                                 src='assets/images/u34.png' width="50px" height="50px"/>
                            Registrar of Joint Stock Companies And Firms (RJSC)
                        </div>

                        <div class="panel-body">
                            <table width="100%">
                                <tr>
                                    <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">
                                        Tracking no. : <span>{{ $appInfo->tracking_no  }}</span></td>
                                    <td style="padding: 5px;">Date of Submission:
                                        <span> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }} </span>
                                    </td>
                                </tr>
                                <tr>

                                    <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">
                                        Current Status : <span>{{$appInfo->status_name}}</span></td>
                                    <td style="padding: 5px;">Current Desk :
                                        @if($appInfo->desk_id != 0)
                                            <span>  {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }} </span>
                                        @else
                                            <span>Applicant</span>
                                        @endif

                                    </td>
                                </tr>
                            </table>



                        <div class="panel panel-info">


                            <div class="panel-body">
                                <fieldset class="col-md-11">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <br>
                                            RJSC Office:  {{!empty($appInfo->rjsc_office_name)?$appInfo->rjsc_office_name:'N/A'}}
                                            <br>
                                            <br>
                                            To,
                                            <br>
                                            The Registrar,
                                            <br>
                                            Joint Stock Companies & Firms,
                                            <br>
                                            TCB Bhaban (6th Floor),1 Kawran Bazar, Dhaka - 1215.
                                            <br>
                                            <br>
                                            <br>
                                            Sub :- Clearance of Name for formation of a {{!empty($appInfo->rjsc_type_name)?$appInfo->rjsc_type_name:'N/A'}}
                                            <br>
                                            <br>
                                            <br>
                                            Dear Sir,
                                            <br>
                                            <br>
                                            <br>
                                            I have gone through the conditions of name clearance and in full agreement with those conditions ,I, the undersigned, on behalf of the promoters request to examine and clear one of the names specified hereunder in order of priority for registration.

                                            <br>
                                            <br>
                                            <br>
                                            <h4> RISC Name Clearance Certificate Terms and Condition as follows:</h4>
                                            <ol>
                                                <li> Same company name is not acceptable, its hearing sound, written style
                                                    etc.
                                                </li>
                                                <li> Similar name of international company, organization, social & Cultural
                                                    organization are not acceptable.
                                                </li>
                                                <li> Not acceptable existing company, business body, Social, Cultural,
                                                    Entertainment & Sporting organization's name.
                                                </li>
                                                <li> Name could not same Govt. Organization or Company.</li>
                                                <li> Nationally fame person's name or famous family's name need to permission
                                                    from particular person and take permission to Government.
                                                </li>
                                                <li> To take freedom fighter related name for your company must be essential
                                                    approval of Freedom Fighter Ministry.
                                                </li>
                                                <li> Not acceptable similar of Govt. development program or development
                                                    organization.
                                                </li>
                                                <li> Existing political party's slogan, name and program not acceptable.</li>
                                                <li> Must avoid Rebuke, Slang word ....</li>
                                                <li> Name could not harm Social, Religious and national harmony.</li>
                                                <li> In case of long established (at least 10 years) Social institutions, if
                                                    they want to register after their current name, they have to apply for
                                                    name clearance appearing personally along with board of committee's
                                                    resolution.
                                                </li>
                                                <li> Must be taken Ministry permission of Social, cultural & sporting
                                                    Organization for Limited company.
                                                </li>
                                                <li> Name clearance is not final name for Company Registration, RISC holds
                                                    power to change.
                                                </li>
                                            </ol>
                                            <br>
                                            <br>

                                            1.  Click the checkbox confirming agreement to the aforementioned conditions
                                            @if($appInfo->is_accept == 1)
                                                <img src="assets/images/checked.png" style="" width="12px">
                                            @else
                                                <img src="assets/images/unchecked.png" style="" width="12px">
                                            @endif
                                            <table class="table table-bordered ">
                                                <thead >
                                                <tr >
                                                    <th class="text-center">SL No.</th>
                                                    <th class="text-center">Name</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td>
                                                        <div class="col-md-11">
                                                            {{!empty($appInfo->company_name)?$appInfo->company_name:'N/A'}}
                                                        </div>
                                                        {{--<div class="col-md-1">--}}
                                                            {{--<button class="btn btn-info pull-right">Search</button>--}}
                                                        {{--</div>--}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <br>
                                            <br>
                                            <br>
                                            Thanking you and with best regards.
                                            <br>
                                            <br>

                                            Yours Sincerely,
                                            <br>

                                            Name              : {{!empty($appInfo->applicant_name)?$appInfo->applicant_name:'N/A'}}<br>
                                            Position          : {{!empty($appInfo->rjsc_position_name)?$appInfo->rjsc_position_name:'N/A'}}<br>
                                            @if($appInfo->designation == "17")
                                            Organization Name          : {{!empty($appInfo->organization_name_id)?$appInfo->organization_name_id:'N/A'}}<br>
                                            @endif
                                            Mobile Phone      : {{!empty($appInfo->mobile_number)?$appInfo->mobile_number:'N/A'}}<br>
                                            E-mail            : {{!empty($appInfo->email)?$appInfo->email:'N/A'}}<br>
                                            Address           : {{!empty($appInfo->address)?$appInfo->address:'N/A'}}<br>
                                            District           : {{!empty($appInfo->rjsc_dis_name)?$appInfo->rjsc_dis_name:'N/A'}}<br>


                                            <br>
                                            <br>
                                            <p style="display: none;"><small style="color:red">Please do not tick if you do not have digital signature certificate from an authorized certifying authority</small></p>
                                            <br>
                                            @if($appInfo->is_signature == 1)
                                                <img  src="assets/images/checked.png" style=" display: none;" width="12px">
                                            @else
                                                <img src="assets/images/unchecked.png" style="display: none;" width="12px">
                                            @endif
                                            <br>
                                            <p style="display: none;"><small>(If you want to insert digital signature please ensure java version 1.6.0_45 is installed in your computer and set security level medium in browser)</small></p>
                                            <div class="col-md-8 upFile" style="display: none;">
                                                @if($appInfo->is_signature == 1 && $appInfo->digital_signature !='')
                                                    <a href="/uploads/{{$appInfo->digital_signature}}" class="btn btn-success btn-small">Open file</a>
                                                @else
                                                    No file is found!
                                                @endif
                                            </div>
                                            {{--<input type="file" class="upFile">--}}

                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
</body>
</html>
