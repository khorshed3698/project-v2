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
                        Application for E-TIN to National Board of Revenue (NBR)
                    </div>
                    <div class="panel panel-info" id="inputForm">
                        <div class="panel-heading">
                                    <img class="img-responsive pull-left"
                                          src='assets/images/u34.png'width="50px" height="50px"/>
                            National Board of Revenue (NBR)
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
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">

                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Taxpayer\'s Status :
                                                <span> {{ (!empty($appInfo->taxpayer_status)) ? $taxpayerStatus[$appInfo->taxpayer_status] : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Type of the organization:
                                                <span> {{ (!empty($appInfo->organization_type_id)) ? $eaOrganizationType[$appInfo->organization_type_id] : 'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Registration Type :
                                                <span> {{ (!empty($appInfo->reg_type)) ? $registrationType[$appInfo->reg_type]:'N/A'  }}</span>
                                            </td>

                                        </tr>
                                        @if(!empty($appInfo->reg_type) && $appInfo->reg_type == '2')
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Existing(10 Digits) TIN :
                                                <span> {{ (!empty($appInfo->existing_tin_no)) ? $appInfo->existing_tin_no:'N/A'  }}</span>
                                            </td>

                                        </tr>
                                        @endif

                                        @if(!empty($appInfo->reg_type) && $appInfo->reg_type == '1')
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Main Source of Income :
                                                <span> {{ (!empty($appInfo->main_source_income)) ? $mainSourceIncome[$appInfo->main_source_income]:'N/A'  }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Location of main source of income :
                                                <span>{{ (!empty($appInfo->main_source_income_location)) ? $districts[$appInfo->main_source_income_location] :'N/A'  }}</span>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Company :
                                                <span>{{ (!empty($appInfo->company_id)) ? $companies[$appInfo->company_id] :'N/A'  }}</span>
                                            </td>

                                        </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                            <div class="panel panel-info">


                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table width="100%" cellpadding="10">

                                            <tbody>
                                            <tr>
                                                <td colspan="2" style="padding: 5px;" >
                                                    Name of Organization/ Company/ Industrial Project :
                                                    <span> {{ (!empty($appInfo->company_name)) ? $appInfo->company_name : 'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 5px;" >
                                                    Incorporation Number :
                                                    <span> {{ (!empty($appInfo->incorporation_certificate_number)) ?$appInfo->incorporation_certificate_number : 'N/A'  }}</span>
                                                </td>

                                            </tr>

                                            <tr>
                                                <td colspan="2" style="padding: 5px;" >
                                                    Date of incorporation :
                                                    <span> {{ (empty($appInfo->incorporation_certificate_date)  ? '': $appInfo->incorporation_certificate_date )   }}</span>

                                                </td>

                                            </tr>

                                                <tr>
                                                    <td colspan="2" style="padding: 5px;" >
                                                        Principal Promoter Designation :
                                                        <span> {{ (!empty($appInfo->ceo_designation)) ? $appInfo->ceo_designation:'N/A'  }}</span>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td colspan="2" style="padding: 5px;" >
                                                        Full Name :
                                                        <span> {{ (!empty($appInfo->ceo_full_name)) ?$appInfo->ceo_full_name:'N/A'  }}</span>
                                                    </td>


                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding: 5px;" >
                                                        Mobile Number :
                                                        <span>{{ (!empty($appInfo->ceo_mobile_no)) ? $appInfo->ceo_mobile_no :'N/A'  }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding: 5px;" >
                                                        Fax :
                                                        <span>{{ (!empty($appInfo->ceo_fax_no)) ? $appInfo->ceo_fax_no :'N/A'  }}</span>

                                                    </td>

                                                </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 5px;" >
                                                    Email :
                                                    <span>{{ (!empty($appInfo->ceo_email)) ? $appInfo->ceo_email :'N/A'  }}</span>

                                                </td>

                                            </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <div class="panel panel-info">
                            <div class="panel-heading ">Principal Promoter Address</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country :
                                                <span> {{ (!empty($appInfo->ceo_country_id)) ? $countries[$appInfo->ceo_country_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->ceo_thana_id)) ? $thana[$appInfo->ceo_thana_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->ceo_district_id)) ? $districts[$appInfo->ceo_district_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span> {{ (!empty($appInfo->ceo_post_code)) ? $appInfo->ceo_post_code:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Address :
                                                <span>{{ (!empty($appInfo->ceo_address)) ? $appInfo->ceo_address:'N/A'  }}</span>
                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading ">Registered Office Address</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country :
                                                <span> {{ (!empty($appInfo->reg_office_country_id)) ? $countries[$appInfo->reg_office_country_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->office_thana_id)) ? $thana[$appInfo->office_thana_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->office_district_id)) ? $districts[$appInfo->office_district_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span> {{ (!empty($appInfo->office_post_code)) ? $appInfo->office_post_code:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Address :
                                                <span>{{ (!empty($appInfo->office_address)) ? $appInfo->office_address:'N/A'  }}</span>
                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading ">Others Address ( Working address/ Business address)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country :
                                                <span> {{ (!empty($appInfo->other_address_country_id)) ? $countries[$appInfo->other_address_country_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->other_address_thana_id)) ? $thana[$appInfo->other_address_thana_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->other_address_district_id)) ? $districts[$appInfo->other_address_district_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span> {{ (!empty($appInfo->other_address_post_code)) ? $appInfo->other_address_post_code:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Address :
                                                <span>{{ (!empty($appInfo->other_address)) ? $appInfo->other_address:'N/A'  }}</span>
                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
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
