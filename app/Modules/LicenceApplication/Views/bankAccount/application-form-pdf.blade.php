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
                        Apply for Bank Account opening to Bangladesh
                    </div>
                    <div class="panel panel-info" id="inputForm">
                        <div class="panel-heading">
                            <img class="img-responsive pull-left"
                                 src="assets/images/u39.png" width="50px" height="50px"/>
                            Sonali Bank Ltd.
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
                                <table width="100%" cellpadding="10">
                                    <tr>
                                        <td width="50%" style="padding: 5px;" >
                                            Select Bank :
                                            <span> {{ (!empty($appInfo->bank_id)) ? $banks[$appInfo->bank_id]:'N/A'  }}</span>
                                        </td>
                                        <td width="50%" style="padding: 5px;" >
                                            Select Branch :
                                            <span>{{ (!empty($appInfo->bank_branch_id)) ? $bankBranches[$appInfo->bank_branch_id]:'N/A'  }}</span>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                            </div>

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
                                <div class="panel-heading">B. Information of Principal Promoter/Chairman/Managing Director/CEO/Country Manager</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table width="100%" cellpadding="10">
                                            <tbody>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Country  :
                                                    <span> {{ (!empty($appInfo->ceo_country_id)) ? $countries[$appInfo->ceo_country_id]:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Date of Birth :
                                                    <span> {{ (!empty($appInfo->ceo_dob)) ? $appInfo->ceo_dob :'N/A'  }}</span>
                                                </td>
                                            </tr>

                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    NID No. :
                                                    <span> {{ (!empty($appInfo->ceo_nid)) ? $appInfo->ceo_nid:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Designation :
                                                    <span>{{ (!empty($appInfo->ceo_designation)) ? $appInfo->ceo_designation:'N/A'  }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Full Name :
                                                    <span> {{ (!empty($appInfo->ceo_full_name)) ? $appInfo->ceo_full_name:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    District/City/State  :
                                                    <span>{{ (!empty($appInfo->ceo_district_id)) ? $districts[$appInfo->ceo_district_id]:'N/A'  }}</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Police Station/Town  :
                                                    <span>{{ (!empty($appInfo->ceo_thana_id)) ? $thana[$appInfo->ceo_thana_id]:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Post/Zip Code :
                                                    <span> {{ (!empty($appInfo->ceo_post_code)) ? $appInfo->ceo_post_code:'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;">
                                                    House,Flat/Apartment,Road :
                                                    <span>{{ (!empty($appInfo->ceo_address)) ? $appInfo->ceo_address:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Telephone No. :
                                                    <span>{{ (!empty($appInfo->ceo_telephone_no)) ? $appInfo->ceo_telephone_no:'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;">
                                                    Mobile No. :
                                                    <span>{{ (!empty($appInfo->ceo_mobile_no)) ? $appInfo->ceo_mobile_no:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Father\'s Name :
                                                    <span>{{ (!empty($appInfo->ceo_father_name)) ? $appInfo->ceo_father_name:'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;">
                                                    Email :
                                                    <span>{{ (!empty($appInfo->ceo_email)) ? $appInfo->ceo_email:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Mother\'s Name :
                                                    <span>{{ (!empty($appInfo->ceo_mother_name)) ? $appInfo->ceo_mother_name:'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px;">
                                                    Fax No. :
                                                    <span>{{ (!empty($appInfo->ceo_fax_no)) ? $appInfo->ceo_fax_no:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Spouse name :
                                                    <span>{{ (!empty($appInfo->ceo_spouse_name)) ? $appInfo->ceo_spouse_name:'N/A'  }}</span>
                                                </td>

                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">C. Office Address</div>
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
                                <div class="panel-heading">D. Factory Address (Optional)</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table width="100%" cellpadding="10">
                                            <tbody>
                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    District :
                                                    <span> {{ (!empty($appInfo->factory_district_id)) ? $districts[$appInfo->factory_district_id]:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Police Station :
                                                    <span> {{ (!empty($appInfo->factory_thana_id)) ? $thana[$appInfo->factory_thana_id]:'N/A'  }}</span>
                                                </td>
                                            </tr>

                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    Post Office :
                                                    <span> {{ (!empty($appInfo->factory_post_office)) ? $appInfo->factory_post_office:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Post Code :
                                                    <span>{{ (!empty($appInfo->factory_post_code)) ? $appInfo->factory_post_code:'N/A'  }}</span>
                                                </td>
                                            </tr>

                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    House,Flat/Apartment,Road :
                                                    <span> {{ (!empty($appInfo->factory_address)) ? $appInfo->factory_address:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Telephone No :
                                                    <span>{{ (!empty($appInfo->factory_telephone_no)) ? $appInfo->factory_telephone_no:'N/A'  }}</span>
                                                </td>

                                            </tr>

                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    Mobile No :
                                                    <span> {{ (!empty($appInfo->factory_mobile_no)) ? $appInfo->factory_mobile_no:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Fax No :
                                                    <span>{{ (!empty($appInfo->factory_fax_no)) ? $appInfo->factory_fax_no:'N/A'  }}</span>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td width="50%" style="padding: 5px;">
                                                    Email :
                                                    <span>{{ (!empty($appInfo->factory_email)) ? $appInfo->factory_email:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Mouja No. :
                                                    <span>{{ (!empty($appInfo->factory_mouja)) ? $appInfo->factory_mouja:'N/A'  }}</span>
                                                </td>
                                            </tr>

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

                                                <td width="50%" style="padding: 5px;" >
                                                    TIN Number :
                                                    <span> {{ (!empty($appInfo->tin_no)) ? $appInfo->tin_no:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    TIN Certificate Attached :
                                                    <span>
                                                        @if(!empty($appInfo->tin_file_name))
                                                            <a href="/uploads/{{$appInfo->tin_file_name}}"
                                                               target="_blank">Open file</a>
                                                        @else
                                                            Not found!
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    Trade Licence :
                                                    <span> {{ (!empty($appInfo->trade_licence)) ? $appInfo->trade_licence:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Trade Licence Attached :
                                                    <span>
                                                        @if(!empty($appInfo->trade_file_name))
                                                            <a href="/uploads/{{$appInfo->trade_file_name}}"
                                                               target="_blank">Open file</a>
                                                        @else
                                                            Not found!
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    Incorporation No :
                                                    <span> {{ (!empty($appInfo->incorporation_no)) ? $appInfo->incorporation_no:'N/A'  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Incorporation Attached :
                                                    <span>
                                                        @if(!empty($appInfo->incorporation_file_name))
                                                            <a href="/uploads/{{$appInfo->incorporation_file_name}}"
                                                               target="_blank">Open file</a>
                                                        @else
                                                            Not found!
                                                        @endif
                                                    </span>
                                                </td>

                                            </tr>

                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    Memorandum of Association :
                                                    <span>
                                                        @if(!empty($appInfo->mem_association_file_name))
                                                            <a href="/uploads/{{$appInfo->mem_association_file_name}}"
                                                               target="_blank">Open file</a>
                                                        @else
                                                            Not found!
                                                        @endif
                                                    </span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    Resolution for Bank Account :
                                                    @if(!empty($appInfo->resolution_bank_file_name))
                                                        <a href="/uploads/{{$appInfo->resolution_bank_file_name}}"
                                                           target="_blank">Open file</a>
                                                    @else
                                                        Not found!
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>

                                                <td width="50%" style="padding: 5px;" >
                                                    Article of Association :
                                                    <span>
                                                        @if(!empty($appInfo->art_association_file_name))
                                                            <a href="/uploads/{{$appInfo->art_association_file_name}}"
                                                               target="_blank">Open file</a>
                                                        @else
                                                            Not found!
                                                        @endif
                                                    </span>
                                                </td>
                                                <td width="50%" style="padding: 5px;">
                                                    List of share holder & Director :
                                                    @if(!empty($appInfo->list_share_holder_n_director_file_name))
                                                        <a href="/uploads/{{$appInfo->list_share_holder_n_director_file_name}}"
                                                           target="_blank">Open file</a>
                                                    @else
                                                        Not found!
                                                    @endif
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
    </div>
</section>
<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
</body>
</html>
