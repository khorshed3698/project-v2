<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body>

<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        <img src="assets/images/bida_logo.png" style="width: 100px" alt="bida_logo"/><br/>
                        <br>
                        Bangladesh Investment Development Authority (BIDA)<br/>
                        Application for Outward Remittance Approval
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Outward Remittance Approval</div>
                    <div class="panel-body">
                        <table aria-label="Detailed Application for Outward Remittance Approval" width="100%">
                            <tr>
                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                            </tr>
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Tracking no. : <span>{{ $appInfo->tracking_no  }}</span></td>
                                <td style="padding: 5px;">Date of Submission: <span>{{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}</span></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">Current Status :  <span>{{$appInfo->status_name}}</span></td>
                                <td style="padding: 5px;">Current Desk :
                                    <span>@if($appInfo->desk_id != 0) {{$appInfo->desk_name}} @else Applicant @endif</span>
                                </td>
                            </tr>
                        </table>

                        {{--meeting_info--}}
                        @if(!empty($metingInformation))
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Meeting Information</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Meeting Information" width="100%" cellpadding="10">
                                            <tr>
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding: 5px;">
                                                    Meeting No. :
                                                    <span>{{ (!empty($metingInformation->meting_number) ? $metingInformation->meting_number : '') }}</span>
                                                </td>
                                                <td style="padding: 5px;">Meeting Date :
                                                    <span>{{ (!empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '') }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{--meeting_info--}}

                        <div class="panel panel-info">
                            <div class="panel-heading">Company Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Company Information" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Name of Organization/ Company/ Industrial Project :
                                                <span> {{ (!empty($appInfo->company_id)) ? $userCompanyList[0]->company_name : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Name of Organization/ Company/ Industrial Project (বাংলা):
                                                <span> {{ (!empty($appInfo->company_id)) ? $userCompanyList[0]->company_name_bn : 'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country of Origin :
                                                <span> {{ (!empty($appInfo->origin_country_id)) ? $countries[$appInfo->origin_country_id]:'N/A'  }}</span>
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
                                                @if(!empty($appInfo->business_sector_id) == 0)
                                                    <span> {{ ($appInfo->business_sector_others) ? $appInfo->business_sector_others :'N/A'  }}</span>
                                                @else
                                                    <span> {{ (!empty($appInfo->business_sector_id)) ? $sectors[$appInfo->business_sector_id]:'N/A'  }}</span>
                                                @endif
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Sub sector:
                                                @if(!empty($appInfo->business_sub_sector_id) == 0)
                                                    <span> {{ ($appInfo->business_sub_sector_others) ? $appInfo->business_sub_sector_others :'N/A'  }}</span>
                                                @else
                                                    <span> {{ (!empty($appInfo->business_sub_sector_id)) ? $sub_sectors[$appInfo->business_sub_sector_id]:'N/A'  }}</span>
                                                @endif
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
                            <div class="panel-heading">Information of Principal Promoter/ Chairman/ Managing Director/ CEO/ Country</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Information of Principal Promoter" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country :
                                                <span> {{ (!empty($appInfo->ceo_country_id)) ? $countries[$appInfo->ceo_country_id]:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of Birth :
                                                <span>{{ (!empty($appInfo->ceo_dob) and ($appInfo->ceo_dob != '0000-00-00')) ? date('d-M-Y', strtotime($appInfo->ceo_dob)):'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;">
                                                    NID No. :
                                                    <span>{{ (!empty($appInfo->ceo_nid)) ? $appInfo->ceo_nid:'N/A'  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;">
                                                    Passport No. :
                                                    <span>{{ (!empty($appInfo->ceo_passport_no)) ? $appInfo->ceo_passport_no:'N/A'  }}</span>
                                                </td>
                                            @endif

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
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;" >
                                                    District/City/State :
                                                    <span>{{ (!empty($appInfo->ceo_district_id)) ? $districts[$appInfo->ceo_district_id]:'N/A'  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;" >
                                                    District/ City/ State :
                                                    <span>{{ (!empty($appInfo->ceo_city)) ? $appInfo->ceo_city:'N/A'  }}</span>
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;" >
                                                    Police Station/Town :
                                                    <span> {{ (!empty($appInfo->ceo_thana_id)) ? $thana[$appInfo->ceo_thana_id]:'N/A'  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;" >
                                                    State/Province :
                                                    <span>{{ (!empty($appInfo->ceo_state)) ? $appInfo->ceo_state:'N/A'  }}</span>
                                                </td>
                                            @endif

                                            <td width="50%" style="padding: 5px;" >
                                                Post/Zip Code :
                                                <span>{{ (!empty($appInfo->ceo_post_code)) ? $appInfo->ceo_post_code:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->ceo_address)) ? $appInfo->ceo_address:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->ceo_telephone_no)) ? $appInfo->ceo_telephone_no:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->ceo_mobile_no)) ? $appInfo->ceo_mobile_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Father's Name :
                                                <span> {{ (!empty($appInfo->ceo_father_name)) ? $appInfo->ceo_father_name:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->ceo_email)) ? $appInfo->ceo_email:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mother's Name :
                                                <span>{{ (!empty($appInfo->ceo_mother_name)) ? $appInfo->ceo_mother_name:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No :
                                                <span>{{ (!empty($appInfo->ceo_fax_no)) ? $appInfo->ceo_fax_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Spouse name :
                                                <span>{{ (!empty($appInfo->ceo_spouse_name)) ? $appInfo->ceo_spouse_name:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Gender :
                                                <span>{{ (!empty($appInfo->ceo_gender)) ? $appInfo->ceo_gender : '' }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Office Address</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Office Address" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
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
                            <div class="panel-heading">Factory Address</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Factory Address" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
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
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No :
                                                <span>{{ (!empty($appInfo->factory_fax_no)) ? $appInfo->factory_fax_no:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Email :
                                                <span>{{ (!empty($appInfo->factory_email)) ? $appInfo->factory_email:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mouja No :
                                                <span>{{ (!empty($appInfo->factory_mouja)) ? $appInfo->factory_mouja:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">1. Basic Instructions</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Basic Instructions" width="100%">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Type of the Remittance :
                                                <span> {{ (!empty($appInfo->remittance_type_id)) ? $remittanceType[$appInfo->remittance_type_id] : 'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        @if(in_array($appInfo->remittance_type_id, [4,5]))
                                            <tr>
                                                <td colspan="2" style="padding: 5px;" >
                                                    @if(!empty($appInfo->int_property_attachment))
                                                        Copy of Trade Mark Certificate/ Copy of Application for Trade Mark Certificate :
                                                        <a target="_blank" rel="noopener" title=""
                                                           href="{{URL::to('/uploads/'.(!empty($appInfo->int_property_attachment) ? $appInfo->int_property_attachment : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                        </a>
                                                    @else
                                                        No file found
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">2. BIDA's Registration Info</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report BIDA's Registration Info" width="100%" cellpadding="10" class="table table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Registration No</td>
                                            <td>Date</td>
                                            <td>Proposed Investment (BDT)</td>
                                            <td>Actual Investment (BDT)</td>
                                            <td>Copy of registration</td>
                                            <td>Amendment Copy of BIDA Registration</td>
                                        </tr>
                                        @if(count($bidaRegInfo) > 0)
                                            <?php $inc = 0; ?>
                                            @foreach($bidaRegInfo as $bidaReg)
                                                <tr>
                                                    <td>
                                                        <span>{{ (!empty($bidaReg->registration_no)) ? $bidaReg->registration_no:'N/A'  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (($bidaReg->registration_date != '0000-00-00') ? date('d-M-Y', strtotime($bidaReg->registration_date)):'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($bidaReg->proposed_investment)) ? $bidaReg->proposed_investment :'N/A'  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($bidaReg->actual_investment)) ? $bidaReg->actual_investment :'N/A'  }}</span>
                                                    </td>
                                                    <td>
                                                        @if(!empty($bidaReg->registration_copy))

                                                            <div class="save_file">
                                                                <a target="_blank" rel="noopener" title=""
                                                                   href="{{URL::to('/uploads/'.(!empty($bidaReg->registration_copy) ? $bidaReg->registration_copy : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                                </a>
                                                            </div>
                                                        @else
                                                            No file found
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($bidaReg->amendment_copy))

                                                            <div class="save_file">
                                                                <a target="_blank" rel="noopener" title=""
                                                                   href="{{URL::to('/uploads/'.(!empty($bidaReg->amendment_copy) ? $bidaReg->amendment_copy : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                                </a>
                                                            </div>
                                                        @else
                                                            No file found
                                                        @endif
                                                    </td>
                                                </tr>

                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">3. Foreign Collaboration's providing Service/ Intellectual Properties Info:</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Foreign Collaboration's providing Service" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Name of Organization :
                                                <span> {{ (!empty($appInfo->organization_name)) ? $appInfo->organization_name:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Address :
                                                <span> {{ (!empty($appInfo->organization_address)) ? $appInfo->organization_address:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                City/ State :
                                                <span> {{ (!empty($appInfo->property_city)) ? $appInfo->property_city:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code/ Zip code :
                                                <span> {{ (!empty($appInfo->property_post_code)) ? $appInfo->property_post_code:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Country :
                                                <span> {{ (!empty($appInfo->property_country_id)) ? $countries[$appInfo->property_country_id]:'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">4. Effective date of the Agreement:</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Effective date of the Agreement" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                Date of the Agreement :
                                                <span>{{ (($appInfo->effective_agreement_date != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->effective_agreement_date)):'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">5. Duration of the Agreement:</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Duration of the Agreement" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                From :
                                                <span>{{ (($appInfo->agreement_duration_from != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->agreement_duration_from)):'N/A')  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Duration type :
                                                <span>{{ (!empty($appInfo->agreement_duration_type) ? $appInfo->agreement_duration_type :'N/A')  }}</span>
                                            </td>

                                        </tr>
                                        @if($appInfo->agreement_duration_type == 'Fixed Date')
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                To :
                                                <span>{{ (($appInfo->agreement_duration_to != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->agreement_duration_to)):'N/A')  }}</span>
                                            </td>
                                            <td style="padding: 5px;" >
                                                Total Duration :
                                                <span>{{ (!empty($appInfo->agreement_total_duration) ? $appInfo->agreement_total_duration :'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        @endif
                                        @if($appInfo->agreement_duration_type == 'Until Valid Contact')
                                            <tr>
                                               <td style="padding: 5px;" width="50%">
                                                   @if(!empty($appInfo->valid_contact_attachment))
                                                       <div class="save_file">
                                                           Attach valid contact :
                                                           <a target="_blank" rel="noopener" title=""
                                                              href="{{URL::to('/uploads/'.(!empty($appInfo->valid_contact_attachment) ? $appInfo->valid_contact_attachment : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                           </a>
                                                       </div>
                                                   @endif
                                               </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">6. Schedule of payment:</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Schedule of payment" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                <span> {{ (!empty($appInfo->schedule_of_payment)) ? $appInfo->schedule_of_payment :'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">7. Marketing of products (%)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Marketing of products" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Local :
                                                <span>{{ (!empty($appInfo->marketing_of_products_local) ? $appInfo->marketing_of_products_local :'N/A')  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Foreign :
                                                <span>{{ (!empty($appInfo->marketing_of_products_foreign) ? $appInfo->marketing_of_products_foreign :'N/A')  }}</span>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">8. Present status</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Present status" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Present Status :
                                                <span>{{ (!empty($appInfo->present_status_id) ? $remittancePresentStatus[$appInfo->present_status_id] :'N/A')  }}</span>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">9. Brief description of technological service received</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Brief description of technological service received" width="100%" cellpadding="10" class="table table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($briefDescription) > 0)
                                            @foreach($briefDescription as $briefDesc)
                                                <tr>
                                                    <td>
                                                        <span>{{ (!empty($briefDesc->brief_description) ? $briefDesc->brief_description :'N/A')  }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            No Brief Here ...
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">10. Total Amount to be paid as per Agreement:</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Total Amount to be paid as per Agreement" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>(a) Total amount</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Amount type :
                                                <span> {{ (!empty($appInfo->agreement_amount_type)) ? $appInfo->agreement_amount_type :'N/A'  }}</span>
                                            </td>
                                            @if($appInfo->agreement_amount_type == 'Percentage')
                                                <td>
                                                    Percentage of sales% :
                                                    <span> {{ (!empty($appInfo->percentage_of_sales)) ? $appInfo->percentage_of_sales :'N/A'  }}</span>
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Taka (BDT) :
                                                <span> {{ (!empty($appInfo->total_agreement_amount_bdt)) ? $appInfo->total_agreement_amount_bdt :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                USD :
                                                <span> {{ (!empty($appInfo->total_agreement_amount_usd)) ? $appInfo->total_agreement_amount_usd :'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>(b) For which period</td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                From :
                                                <span>{{ (($appInfo->period_from != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->period_from)):'N/A')  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                To :
                                                <span>{{ (($appInfo->period_to != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->period_to)):'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total period :
                                                <span> {{ (!empty($appInfo->total_period)) ? $appInfo->total_period :'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                (c) Name of products/ services and annual production capacity/ value :
                                                <span> {{ (!empty($appInfo->product_name_capacity)) ? $appInfo->product_name_capacity :'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">11. Industrial project status</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Industrial project status" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                <span>{{ (!empty($appInfo->project_status_id) ? $project_status[$appInfo->project_status_id] :'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @if($appInfo->project_status_id == 1)
                            <div class="panel panel-info">
                                <div class="panel-heading">12. Cost + Freight (C&F) Value of Imported Machinery (In case of proposed/under implementation project) (If applicable)</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Report Data Table" width="100%" cellpadding="10" class="table table-bordered">
                                            <thead>
                                                <tr class="d-none">
                                                    {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Year of Import</td>
                                                <td>To</td>
                                                <td>C&F Value (BDT)</td>
                                            </tr>

                                            @if(count($importedMachine) > 0)
                                                @foreach($importedMachine as $importedMachine)
                                                    <tr>
                                                        <td>
                                                            <span>{{ (($importedMachine->import_year_from != '0000-00-00') ? date('d-M-Y', strtotime($importedMachine->import_year_from)):'N/A')  }}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{ (($importedMachine->import_year_to != '0000-00-00') ? date('d-M-Y', strtotime($importedMachine->import_year_to)):'N/A')  }}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{ (!empty($importedMachine->cnf_value) ? $importedMachine->cnf_value :'N/A')  }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($appInfo->project_status_id == 2)
                            <div class="panel panel-info">
                                <div class="panel-heading">12. Previous year’s sales as declared in the Annual Tax Return (in case of industrial unit already in operation) (If applicable)</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Report Data Table" width="100%" cellpadding="10">
                                            <thead>
                                                <tr class="d-none">
                                                    {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Sales year :
                                                    <span>{{ (($appInfo->prev_sales_year_from != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->prev_sales_year_from)):'N/A')  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    To :
                                                    <span>{{ (($appInfo->prev_sales_year_to != '0000-00-00') ? date('d-M-Y', strtotime($appInfo->prev_sales_year_to)):'N/A')  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>(a) Value of sales</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Taka (BDT) :
                                                    <span>{{ (!empty($appInfo->sales_value_bdt) ? $appInfo->sales_value_bdt :'N/A')  }}</span>
                                                </td>

                                                <td>
                                                    USD :
                                                    <span>{{ (!empty($appInfo->sales_value_usd) ? $appInfo->sales_value_usd :'N/A')  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Dollar conversion rate :
                                                    <span>{{ (!empty($appInfo->usd_conv_rate) ? $appInfo->usd_conv_rate :'N/A')  }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>(b) Amount of tax paid:</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Taka (BDT) :
                                                    <span>{{ (!empty($appInfo->tax_amount_bdt) ? $appInfo->tax_amount_bdt :'N/A')  }}</span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="panel panel-info">
                            <div class="panel-heading">13. Percentage of the Total Fees (If applicable)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Percentage of the Total Fees" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                Percentage :
                                                <span>{{ (!empty($appInfo->total_fee_percentage) ? $appInfo->total_fee_percentage :'N/A')  }} %</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">14. Proposed amount of remittances</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Proposed amount of remittances" width="100%" cellpadding="10" class="table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Proposed</td>
                                            <td>Taka (BDT)</td>
                                            <td>USD</td>
                                            <td>Expressed %</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span>{{ (!empty($appInfo->proposed_remittance_type) ? $appInfo->proposed_remittance_type :'N/A')  }}</span>
                                            </td>
                                            <td>
                                                <span>{{ (!empty($appInfo->proposed_amount_bdt) ? $appInfo->proposed_amount_bdt :'N/A')  }}</span>
                                            </td>
                                            <td>
                                                <span>{{ (!empty($appInfo->proposed_amount_usd) ? $appInfo->proposed_amount_usd :'N/A')  }}</span>
                                            </td>
                                            <td>
                                                <span>{{ (!empty($appInfo->proposed_exp_percentage) ? $appInfo->proposed_exp_percentage :'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sub Total</td>
                                            <td>
                                                <span>{{ (!empty($appInfo->proposed_sub_total_bdt) ? $appInfo->proposed_sub_total_bdt :'N/A')  }}</span>
                                            </td>
                                            <td>
                                                <span>{{ (!empty($appInfo->proposed_sub_total_usd) ? $appInfo->proposed_sub_total_usd :'N/A')  }}</span>
                                            </td>
                                            <td>
                                                <span>{{ (!empty($appInfo->proposed_sub_total_exp_percentage) ? $appInfo->proposed_sub_total_exp_percentage :'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">15. Other remittance made/ to be made during the same calendar/ fiscal year (If applicable)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Other remittance made" width="100%" cellpadding="10" class="table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Type of fee</td>
                                            <td>Taka (BDT)</td>
                                            <td>USD</td>
                                            <td>%</td>
                                            <td>Attachment </td>
                                        </tr>
                                        @if(count($otherRemittanceInfo) > 0)
                                            @foreach($otherRemittanceInfo as $otherRemittanceInfo)
                                                <tr>
                                                    <td>
                                                        <span>{{ (!empty($otherRemittanceInfo->remittance_type_id) ? $remittanceType[$otherRemittanceInfo->remittance_type_id] :'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($otherRemittanceInfo->remittance_bdt) ? $otherRemittanceInfo->remittance_bdt :'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($otherRemittanceInfo->remittance_usd) ? $otherRemittanceInfo->remittance_usd :'N/A')  }}</span>
                                                    </td>

                                                    <td>
                                                        <span>{{ (!empty($otherRemittanceInfo->remittance_percentage) ? $otherRemittanceInfo->remittance_percentage :'N/A')  }}</span>
                                                    </td>

                                                    <td>
                                                        @if(!empty($otherRemittanceInfo->attachment))
                                                            <div class="save_file">
                                                                <a target="_blank" rel="noopener" title=""
                                                                   href="{{URL::to('/uploads/'.(!empty($otherRemittanceInfo->attachment) ? $otherRemittanceInfo->attachment : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>Sub Total</td>
                                            <td>
                                                <span>{{ (!empty($appInfo->other_sub_total_bdt) ? $appInfo->other_sub_total_bdt :'N/A')  }}</span>
                                            </td>
                                            <td>
                                                <span>{{ (!empty($appInfo->other_sub_total_usd) ? $appInfo->other_sub_total_usd :'N/A')  }}</span>
                                            </td>
                                            <td>
                                                <span>{{ (!empty($appInfo->other_sub_total_percentage) ? $appInfo->other_sub_total_percentage :'N/A')  }}</span>
                                            </td>
                                            <td></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">16. Percentage of Total Remittances for the year (If applicable)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Percentage of Total Remittances for the year" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                Percentage :
                                                <span>{{ (!empty($appInfo->total_remittance_percentage) ? $appInfo->total_remittance_percentage :'N/A')  }} %</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">17. Brief statement of benefits received/ to be received by the local company/ firm under the agreement</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report  Brief statement of benefits received" width="100%" cellpadding="10" class="table table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($briefStatement) > 0)
                                            @foreach($briefStatement as $value)
                                                <tr>
                                                    <td>
                                                        <span>{{ (!empty($value->brief_statement) ? $value->brief_statement :'N/A')  }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            No Brief Here ...
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">18. Brief Background of the Foreign Service Provider</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Brief Background of the Foreign Service Provider" width="100%" cellpadding="10" class="table table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <span>{{ (!empty($appInfo->brief_background) ? $appInfo->brief_background :'N/A')  }}</span>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">19. Statement of Remittances of such fees for the last 3(three) years (If Applicable))</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Statement of Remittances of such fees" width="100%" cellpadding="10" class="table table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Type</td>
                                            <td>Year of remittance</td>
                                            <td>BIDA's ref. Number</td>
                                            <td>Date</td>
                                            <td>Approval Copy</td>
                                            <td>Amount</td>
                                            <td>%</td>
                                        </tr>

                                        @if(count($statementOfRemittance) > 0)
                                            @foreach($statementOfRemittance as $statementOfRemittance)
                                                <tr>
                                                    <td>
                                                        <span>{{ (!empty($statementOfRemittance->remittance_type_id) ? $remittanceType[$statementOfRemittance->remittance_type_id] :'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfRemittance->remittance_year) ? $statementOfRemittance->remittance_year :'N/A') }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfRemittance->bida_ref_no) ? $statementOfRemittance->bida_ref_no :'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (($statementOfRemittance->date != '0000-00-00') ? date('d-M-Y', strtotime($statementOfRemittance->date)):'') }}</span>
                                                    </td>
                                                    <td>
                                                        @if(!empty($statementOfRemittance->approval_copy))

                                                            <div class="save_file">
                                                                <a target="_blank" rel="noopener" title=""
                                                                   href="{{URL::to('/uploads/'.(!empty($statementOfRemittance->approval_copy) ? $statementOfRemittance->approval_copy : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                                </a>
                                                            </div>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfRemittance->amount) ? $statementOfRemittance->amount :'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfRemittance->percentage) ? $statementOfRemittance->percentage :'N/A')  }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">20. Statement of Actual production/ Services for the last 3(three) years (If Applicable)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Statement of Actual production/ Services" width="100%" cellpadding="10" class="table table-bordered">
                                        <<thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Year of remittance</td>
                                            <td>Item of production/ service</td>
                                            <td>Qty</td>
                                            <td>Sales Value/ Revenue</td>
                                        </tr>

                                        @if(count($statementOfActualProd) > 0)
                                            @foreach($statementOfActualProd as $statementOfActualProd)
                                                <tr>
                                                    <td>
                                                        <span>{{ (!empty($statementOfActualProd->year_of_remittance) ? $statementOfActualProd->year_of_remittance : 'N/A') }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfActualProd->item_of_production) ? $statementOfActualProd->item_of_production :'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfActualProd->quantity) ? $statementOfActualProd->quantity :'N/A')  }}</span>
                                                    </td>

                                                    <td>
                                                        <span>{{ (!empty($statementOfActualProd->sales_value) ? $statementOfActualProd->sales_value :'N/A')  }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><br>

                        <div class="panel panel-info">
                            <div class="panel-heading">21. Statement of Export Earning (If any) (If Applicable)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Statement of Export Earning" width="100%" cellpadding="10" class="table table-bordered">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Year of remittance</td>
                                            <td>Item of export</td>
                                            <td>Qty</td>
                                            <td>C&F/ CIF value</td>
                                        </tr>

                                        @if(count($statementOfExport) > 0)
                                            <?php $inc = 0; ?>
                                            @foreach($statementOfExport as $statementOfExport)
                                                <tr>
                                                    <td>
                                                        <span>{{ (!empty($statementOfExport->year_of_remittance) ? $statementOfExport->year_of_remittance : 'N/A') }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfExport->item_of_export) ? $statementOfExport->item_of_export :'N/A')  }}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{ (!empty($statementOfExport->quantity) ? $statementOfExport->quantity :'N/A')  }}</span>
                                                    </td>

                                                    <td>
                                                        <span>{{ (!empty($statementOfExport->cnf_cif_value) ? $statementOfExport->cnf_cif_value :'N/A')  }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><br>

                        <div class="panel panel-info">
                            <div class="panel-heading">22. Name & address of the nominated local Bank through which remittance to be effected</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Name & address of the nominated local Bank" width="100%" cellpadding="10">
                                        <thead>
                                            <tr class="d-none">
                                                {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                Select Bank :
                                                <span>{{ (!empty($appInfo->local_bank_id) ? $banks[$appInfo->local_bank_id] :'N/A')  }}</span>
                                            </td>

                                            <td>
                                                Branch :
                                                <span>{{ (!empty($appInfo->local_branch) ? $appInfo->local_branch :'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Address :
                                                <span>{{ (!empty($appInfo->local_bank_address) ? $appInfo->local_bank_address :'N/A')  }}</span>
                                            </td>

                                            <td>
                                                City/ State :
                                                <span>{{ (!empty($appInfo->local_bank_city) ? $appInfo->local_bank_city :'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Post Code/ Zip Code :
                                                <span>{{ (!empty($appInfo->local_bank_post_code) ? $appInfo->local_bank_post_code :'N/A')  }}</span>
                                            </td>

                                            <td>
                                                Country :
                                                <span>{{ (!empty($appInfo->local_bank_country_id) ? $countries[$appInfo->local_bank_country_id] :'N/A')  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Necessary documents to be attached here (Only PDF file)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Necessary documents" class="table table-striped table-bordered table-hover ">
                                        <thead>
                                        <tr class="d-none">
                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">No.</td>
                                            <td colspan="6" style="padding: 5px;">Required attachments</td>
                                            <td colspan="2" style="padding: 5px;">Attached PDF file (Each File Maximum size 2MB)
                                                {{--<span>--}}
                                                {{--<i title="Attached PDF file (Each File Maximum size 2MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>--}}
                                                {{--</span>--}}
                                            </td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($document as $row)
                                            <tr>
                                                <td style="padding: 5px;">
                                                    <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                </td>
                                                <td colspan="6" style="padding: 5px;">{!!  $row->doc_name !!}</td>
                                                <td colspan="2" style="padding: 5px;">
                                                    @if(!empty($row->doc_file_path))

                                                        <div class="save_file">
                                                            <a target="_blank" rel="noopener" title=""
                                                               href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ?
                                                               $row->doc_file_path : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                            </a>
                                                        </div>
                                                    @else
                                                        No file found
                                                    @endif
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Inforrmation--}}
                        <div class="panel panel-info">
                            <div class="panel-heading">Payment Info</div>
                            <div class="panel-body" style="padding: 5px">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="padding: 2px 5px;">Service Fee Payment</div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <div class="row" style="padding: 5px">
                                                <table aria-label="Detailed Report Service Fee Payment" class="table table-striped table-bordered">
                                                    <tr>
                                                        {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>Contact name :
                                                            <span>{{ (!empty($appInfo->sfp_contact_name)) ? $appInfo->sfp_contact_name :'N/A'  }}</span>
                                                        </td>
                                                        <td>Contact email :
                                                            <span>{{ (!empty($appInfo->sfp_contact_email)) ? $appInfo->sfp_contact_email :'N/A'  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contact phone :
                                                            <span>{{ (!empty($appInfo->sfp_contact_phone)) ? $appInfo->sfp_contact_phone :'N/A'  }}</span>
                                                        </td>
                                                        <td>Contact address :
                                                            <span>{{ (!empty($appInfo->sfp_contact_address)) ? $appInfo->sfp_contact_address :'N/A'  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pay amount :
                                                            <span>{{ (!empty($appInfo->sfp_pay_amount)) ? $appInfo->sfp_pay_amount :''  }}</span>
                                                        </td>
                                                        <td>VAT on pay amount :
                                                            <span>{{ (!empty($appInfo->sfp_vat_on_pay_amount)) ? $appInfo->sfp_vat_on_pay_amount :''  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Transaction charge :
                                                            <span>{{ (!empty($appInfo->sfp_transaction_charge_amount)) ? $appInfo->sfp_transaction_charge_amount :''  }}</span>
                                                        </td>
                                                        <td>
                                                            VAT on transaction charge:
                                                            <span>{{ (!empty($appInfo->sfp_vat_on_transaction_charge)) ? $appInfo->sfp_vat_on_transaction_charge :''  }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Amount :
                                                            <span>{{ (!empty($appInfo->sfp_total_amount)) ? $appInfo->sfp_total_amount : ''  }}</span>
                                                        </td>
                                                        <td>Payment Status :
                                                            @if($appInfo->sfp_payment_status == 0)
                                                                <span class="label label-warning">Pending</span>
                                                            @elseif($appInfo->sfp_payment_status == -1)
                                                                <span class="label label-info">In-Progress</span>
                                                            @elseif($appInfo->sfp_payment_status == 1)
                                                                <span class="label label-success">Paid</span>
                                                            @elseif($appInfo->sfp_payment_status == 2)
                                                                <span class="label label-danger">-Exception</span>
                                                            @elseif($appInfo->sfp_payment_status == 3)
                                                                <span class="label label-warning">Waiting for Payment Confirmation</span>
                                                            @else
                                                                <span class="label label-warning">invalid status</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Payment Mode :
                                                            <span>{{ (!empty($appInfo->sfp_pay_mode_code)) ? $appInfo->sfp_pay_mode_code :''  }}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($appInfo->gf_payment_id != 0 && !empty($appInfo->gf_payment_id))
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="padding: 2px 5px;">Government Fee Payment</div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="row" style="padding: 5px">
                                                    <table aria-label="Detailed Report Government Fee Payment" class="table table-striped table-bordered">
                                                        <tr>
                                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                                        </tr>
                                                        <tr>
                                                            <td>Contact name :
                                                                <span>{{ (!empty($appInfo->gfp_contact_name)) ? $appInfo->gfp_contact_name :'N/A'  }}</span>
                                                            </td>
                                                            <td>Contact email :
                                                                <span>{{ (!empty($appInfo->gfp_contact_email)) ? $appInfo->gfp_contact_email :'N/A'  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Contact phone :
                                                                <span>{{ (!empty($appInfo->gfp_contact_phone)) ? $appInfo->gfp_contact_phone :'N/A'  }}</span>
                                                            </td>
                                                            <td>Contact address :
                                                                <span>{{ (!empty($appInfo->gfp_contact_address)) ? $appInfo->gfp_contact_address :'N/A'  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Pay amount :
                                                                <span>{{ (!empty($appInfo->gfp_pay_amount)) ? $appInfo->gfp_pay_amount :''  }}</span>
                                                            </td>
                                                            <td>VAT on pay amount :
                                                                <span>{{ (!empty($appInfo->gfp_vat_on_pay_amount)) ? $appInfo->gfp_vat_on_pay_amount :''  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Transaction charge :
                                                                <span>{{ (!empty($appInfo->gfp_transaction_charge_amount)) ? $appInfo->gfp_transaction_charge_amount :''  }}</span>
                                                            </td>
                                                            <td>
                                                                VAT on transaction charge:
                                                                <span>{{ (!empty($appInfo->gfp_vat_on_transaction_charge)) ? $appInfo->gfp_vat_on_transaction_charge :''  }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Amount :
                                                                <span>{{ (!empty($appInfo->gfp_total_amount)) ? $appInfo->gfp_total_amount : ''  }}</span>
                                                            </td>
                                                            <td>Payment Status :
                                                                @if($appInfo->gfp_payment_status == 0)
                                                                    <span class="label label-warning">Pending</span>
                                                                @elseif($appInfo->gfp_payment_status == -1)
                                                                    <span class="label label-info">In-Progress</span>
                                                                @elseif($appInfo->gfp_payment_status == 1)
                                                                    <span class="label label-success">Paid</span>
                                                                @elseif($appInfo->gfp_payment_status == 2)
                                                                    <span class="label label-danger">-Exception</span>
                                                                @elseif($appInfo->gfp_payment_status == 3)
                                                                    <span class="label label-warning">Waiting for Payment Confirmation</span>
                                                                @else
                                                                    <span class="label label-warning">invalid status</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Payment Mode :
                                                                <span>{{ (!empty($appInfo->gfp_pay_mode_code)) ? $appInfo->gfp_pay_mode_code :''  }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Information about Declaration and undertaking --}}
                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Declaration and undertaking</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table aria-label="Detailed Report Declaration and undertaking" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td width="" style="padding: 5px;">
                                                <p>a. I do hereby declare that the information given above is true to
                                                    the best of my knowledge and I shall be liable for any false
                                                    information/ statement given</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">
                                                <p>b. I do hereby undertake full responsibility of the expatriate for
                                                    whom visa recommendation is sought during their stay in
                                                    Bangladesh. </p>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <table aria-label="Detailed Report Data Table" width="100%" cellpadding="10">
                                        <tr>
                                            {{-- <th aria-hidden="true"  scope="col"></th> --}}
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                <strong class="text-info">Authorized Personnel
                                                    of the organization: </strong>
                                            </td>
                                        </tr>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Full Name :
                                                <span> {{ (!empty($appInfo->auth_full_name)) ? $appInfo->auth_full_name:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Designation :
                                                <span>{{ (!empty($appInfo->auth_designation)) ? $appInfo->auth_designation:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->auth_mobile_no)) ? $appInfo->auth_mobile_no:'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Email address :
                                                <span>{{ (!empty($appInfo->auth_email)) ? $appInfo->auth_email:'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Profile Picture :

                                                @if(file_exists("users/upload/".$appInfo->auth_image))
                                                    <img class="img-thumbnail" width="60" height="60"
                                                         src="users/upload/{{ $appInfo->auth_image }}"
                                                         alt="Applicant Photo"/>
                                                @else
                                                    <img class="img-thumbnail" width="60" height="60"
                                                         src="assets/images/no_image.png" alt="Image not found"/>
                                                @endif
                                            </td>
                                            <td colspan="3" style="padding: 5px;">
                                                Date :
                                                <?php echo date('F d,Y')?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12">
                                            &nbsp; @if($appInfo->accept_terms == 1) <img src="assets/images/checked.png"
                                                                                         width="10" height="10" alt="checked_icon"/> @else
                                                <img src="assets/images/unchecked.png" width="10" height="10" alt="unchecked_icon"/> @endif
                                            <label for="acceptTerms-2"
                                                   class="col-md-11 text-left required-star form-control">
                                                <span>
                                                    I do here by declare that the information given above is true to the
                                                best of my knowledge and I shall be liable for any false information/
                                                system is given.
                                                </span>
                                            </label>
                                            <div class="clearfix"></div>
                                        </div>
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
