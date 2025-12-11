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
                        Application for Company Registration to Bangladesh
                    </div>
                    <div class="panel panel-info" id="inputForm">
                        <div class="panel-heading">
                                    <img class="img-responsive"
                                         src='assets/images/u34.png' width="50px" height="50px"/>
                                    Registrar of Joint Stock Companies And Firms  (RJSC)
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

                            <div class="panel-heading">A. Company Information
                            </div>
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
                                                Name of Organization/ Company/ Industrial Project (বাংলা):
                                                <span> {{ (!empty($appInfo->company_name_bn)) ? $appInfo->company_name_bn : 'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country of Origin :
                                                <span> {{ (!empty($appInfo->country_of_origin_id)) ? $countries[$appInfo->country_of_origin_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Type of the organization :
                                                <span>{{ (!empty($appInfo->organization_type_id)) ? $eaOrganizationType[$appInfo->organization_type_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Status of the organization :
                                                <span> {{ (!empty($appInfo->organization_status_id)) ? $eaOrganizationStatus[$appInfo->organization_status_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Ownership status :
                                                <span>{{ (!empty($appInfo->ownership_status_id)) ? $eaOwnershipStatus[$appInfo->ownership_status_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Business sector :
                                                <span> {{ (!empty($appInfo->business_sector_id)) ? $sectors[$appInfo->business_sector_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Sub sector:
                                                <span>{{ (!empty($appInfo->business_sub_sector_id)) ? $sub_sectors[$appInfo->business_sub_sector_id] :'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Major activities in brief :
                                                <span>{{ (!empty($appInfo->major_activities)) ? $appInfo->major_activities :'N/A'  }}</span>
                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">B. Office Address</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Division :
                                                <span> {{ (!empty($appInfo->office_division_id)) ? $divisions[$appInfo->office_division_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->office_district_id)) ? $districts[$appInfo->office_district_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->office_thana_id)) ? $thana[$appInfo->office_thana_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Office :
                                                <span> {{ (!empty($appInfo->office_post_office)) ? $appInfo->office_post_office:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span>{{ (!empty($appInfo->office_post_code)) ? $appInfo->office_post_code:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->office_address)) ? $appInfo->office_address:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->office_telephone_no)) ? $appInfo->office_telephone_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->office_mobile_no)) ? $appInfo->office_mobile_no:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Fax No :
                                                <span>{{ (!empty($appInfo->office_fax_no)) ? $appInfo->office_fax_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->office_email)) ? $appInfo->office_email:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">C. General Information (as of Memorandum and Articles of Association, Form-VI)
                            </div>
                            <div class="panel-body">


                                        <div class="col-md-12">
                                            <table  width="100%" cellpadding="10">
                                                <tbody>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Main Business Objective :
                                                        <span> {{ (!empty($appInfo->business_objective)) ? $appInfo->business_objective:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Minimum No. of Directors :
                                                        <span> {{ (!empty($appInfo->min_no_director)) ? $appInfo->min_no_director:'N/A'  }}</span>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Authorized Capital (BDT) :
                                                        <span> {{ (!empty($appInfo->authorized_capital)) ? $appInfo->authorized_capital:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Maximum No. of Directors :
                                                        <span> {{ (!empty($appInfo->max_no_director)) ? $appInfo->max_no_director:'N/A'  }}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Number of Shares :
                                                        <span>{{ (!empty($appInfo->number_of_shares)) ? $appInfo->number_of_shares:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Quorum of AGM/EGM :
                                                        <span> {{ (!empty($appInfo->quorum_agm_egm)) ? $appInfo->quorum_agm_egm:'N/A'  }}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Quorum of Board of Director\'s Meeting :
                                                        <span>{{ (!empty($appInfo->quorum_bod_meeting)) ? $appInfo->quorum_bod_meeting:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Duration for Chairmanship (year) :
                                                        <span> {{ (!empty($appInfo->duration_chairman)) ? $appInfo->duration_chairman:'N/A'  }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;">
                                                        Duration for Managing Directorship (year) :
                                                        <span>{{ (!empty($appInfo->duration_md)) ? $appInfo->duration_md:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;">
                                                        Value of each Share (BDT) :
                                                        <span>{{ (!empty($appInfo->value_each_share)) ? $appInfo->value_each_share:'N/A'  }}</span>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">D. Qualification Shares of Each Director
                            </div>
                            <div class="panel-body">

                                        <div class="col-md-12">
                                            <table  width="100%" cellpadding="10">
                                                <tbody>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        1. Number :
                                                        <span> {{ (!empty($appInfo->q_shares_number)) ? $appInfo->q_shares_number:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        2. Value :
                                                        <span> {{ (!empty($appInfo->q_shares_value)) ? $appInfo->q_shares_value:'N/A'  }}</span>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td width="100%" style="padding: 5px;" colspan="2" >
                                                        3. Witness to the agreement of taking qualification Shares :
                                                        <span> {{ (!empty($appInfo->q_shares_witness_agreement)) ? $appInfo->q_shares_witness_agreement:'N/A'  }}</span>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td width="100%" style="padding: 5px;" colspan="2">
                                                        &nbsp;&nbsp;&nbsp; a. Name of Witness :
                                                        <span>{{ (!empty($appInfo->q_shares_witness_name)) ? $appInfo->q_shares_witness_name:'N/A'  }}</span>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td width="100%" style="padding: 5px;" colspan="2">
                                                        &nbsp;&nbsp;&nbsp; b. Address of Witness :
                                                        <span>{{ (!empty($appInfo->q_shares_witness_address)) ? $appInfo->q_shares_witness_address:'N/A'  }}</span>
                                                    </td>

                                                </tr>


                                                </tbody>
                                            </table>
                                </div>
                            </div>


                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">E. Particulars of Body Corporate Subscribers
                                (if any, as of Memorandum and Articles of Association)
                            </div>
                            <div class="panel-body">

                                        <div class="col-md-12">
                                            <table  class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    {{--<th class="text-center col-md-1">SL. No--}}
                                                    </th>
                                                    <th class="text-center col-md-2">Name (of the
                                                        corporate body)
                                                        <span class="required-star"></span>
                                                    </th>
                                                    <th class="text-center col-md-2">Represented By
                                                        (name of the
                                                        representative)
                                                        <span class="required-star"></span>
                                                    </th>

                                                    <th class="text-center col-md-2">Number of
                                                        Subscribed Shares
                                                        <span class="required-star"></span>
                                                    </th>
                                                    <th class="text-center  col-md-2" colspan="2">District
                                                        <span class="required-star"></span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($crCorporateSubscriber)>0)
                                                    @foreach($crCorporateSubscriber as $corporateSubscriber)
                                                        <tr>
                                                            <td>{{!empty($corporateSubscriber->cs_name)?$corporateSubscriber->cs_name:'N/A'}}</td>
                                                            <td>{{!empty($corporateSubscriber->cs_represented_by)?$corporateSubscriber->cs_represented_by:'N/A'}}</td>
                                                            <td>{{!empty($corporateSubscriber->cs_subscribed_share_no)?$corporateSubscriber->cs_subscribed_share_no:'N/A'}}</td>
                                                            <td>{{!empty($corporateSubscriber->cs_district)?$districts[$corporateSubscriber->cs_district]:'N/A'}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif


                                                </tbody>
                                            </table>

                                </div>

                            </div>


                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">F. List of
                                Subscribers/Directors/Managers/Managing Agents</div>
                            <div class="panel-body">

                                        <div class="col-md-12">
                                            <table  class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    {{--<th class="text-center col-md-1">SL. No</th>--}}
                                                    <th class="text-center col-md-4">Name <span
                                                                class="required-star"></span></th>
                                                    <th class="text-center col-md-4">Position <span
                                                                class="required-star"></span></th>
                                                    <th class="text-center col-md-3" colspan="2">Number of
                                                        Subscribed Shares <span
                                                                class="required-star"></span></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($subscribersAgentList)>0)
                                                    @foreach($subscribersAgentList as $subscribersAgent)
                                                        <tr>
                                                            <td>{{!empty($subscribersAgent->lsa_name)?$subscribersAgent->lsa_name:'N/A'}}</td>
                                                            <td>{{!empty($subscribersAgent->lsa_position)?$subscribersAgent->lsa_position:'N/A'}}</td>
                                                            <td>{{!empty($subscribersAgent->lsa_no_subs_share)?$subscribersAgent->lsa_no_subs_share:'N/A'}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif


                                                </tbody>
                                            </table>

                                </div>

                            </div>


                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">G. Witnesses
                            </div>
                            <div class="panel-body">

                                        <div class="col-md-12">
                                            <table  width="100%" cellpadding="10">
                                                <tbody>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        1. Name :
                                                        <span> {{ (!empty($appInfo->witnesses_name)) ? $appInfo->witnesses_name:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        2. Address :
                                                        <span> {{ (!empty($appInfo->witnesses_address)) ? $appInfo->witnesses_address:'N/A'  }}</span>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        3. Phone :
                                                        <span> {{ (!empty($appInfo->witnesses_phone)) ? $appInfo->witnesses_phone:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        4. National ID :
                                                        <span> {{ (!empty($appInfo->witnesses_national_id)) ? $appInfo->witnesses_national_id:'N/A'  }}</span>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>

                                </div>
                            </div>


                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">H. Declaration on Registration of the Company Signed By (as of Form-I)
                            </div>
                            <div class="panel-body">

                                        <div class="col-md-12">
                                            <table  width="100%" cellpadding="10">
                                                <tbody>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Country :
                                                        <span> {{ (!empty($appInfo->declaration_signed_country)) ? $countries[$appInfo->declaration_signed_country]:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Designation :
                                                        <span> {{ (!empty($appInfo->declaration_signed_designation)) ? $appInfo->declaration_signed_designation:'N/A'  }}</span>
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Declaration Signed District :
                                                        <span> {{ (!empty($appInfo->declaration_signed_district)) ? $districts[$appInfo->declaration_signed_district]:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Maximum No. of Directors :
                                                        <span> {{ (!empty($appInfo->max_no_director)) ? $appInfo->max_no_director:'N/A'  }}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Full Name :
                                                        <span>{{ (!empty($appInfo->declaration_signed_full_name)) ? $appInfo->declaration_signed_full_name:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Post/Zip Code :
                                                        <span> {{ (!empty($appInfo->declaration_signed_zip_code)) ? $appInfo->declaration_signed_zip_code:'N/A'  }}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Police Station/Town :
                                                        <span>{{ (!empty($appInfo->declaration_signed_town)) ? $thana[$appInfo->declaration_signed_town]:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Telephone No :
                                                        <span> {{ (!empty($appInfo->declaration_signed_telephone)) ? $appInfo->declaration_signed_telephone:'N/A'  }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;">
                                                        House,Flat/Apartment,Road :
                                                        <span>{{ (!empty($appInfo->declaration_signed_house)) ? $appInfo->declaration_signed_house:'N/A'  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;">
                                                        Fax No :
                                                        <span>{{ (!empty($appInfo->declaration_signed_fax)) ? $appInfo->declaration_signed_fax:'N/A'  }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;">
                                                        Upload Momorandum of Association :
                                                        <span><a target="_blank" class="documentUrl show-in-view" title=""
                                                                 href="{{URL::to('/uploads/'. $appInfo->declaration_signed_momorandum)}}">
                                                            <i
                                                                    class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            Open File
                                                        </a></span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;">
                                                        Email :
                                                        <span>{{ (!empty($appInfo->declaration_signed_email)) ? $appInfo->declaration_signed_email:'N/A'  }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="50%" style="padding: 5px;">
                                                        Upload Article :
                                                        <span><a target="_blank" class="documentUrl show-in-view" title=""
                                                                 href="{{URL::to('/uploads/'. $appInfo->declaration_signed_article)}}">
                                                            <i
                                                                    class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            Open File
                                                        </a></span>
                                                    </td>
                                                    {{--<td width="50%" style="padding: 5px;">--}}
                                                        {{--Email :--}}
                                                        {{--<span>{{ (!empty($appInfo->declaration_signed_email)) ? $appInfo->declaration_signed_email:'N/A'  }}</span>--}}
                                                    {{--</td>--}}
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
    </div>
</section>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
</body>
</html>
