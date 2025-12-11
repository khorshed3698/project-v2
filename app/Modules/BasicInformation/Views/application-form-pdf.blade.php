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
                        <img src="assets/images/bida_logo.png" style="width: 100px" alt="BIDA Logo"/><br/>
                        <br>
                        Bangladesh Investment Development Authority (BIDA)<br/>
                        Application for Basic Information
                    </div>
                </div>
                <div class="panel panel-info"  id="inputForm">
                    <div class="panel-heading">Application for Basic Information</div>
                    <div class="panel-body">
                        <table width="100%" aria-label="Detailed Basic Info Data Table">
                            <tr>
                                <th aria-hidden="true" scope="col"></th>
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

                        <div class="panel panel-info">
                            <div class="panel-heading">Company Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Company Info Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Name of Organization/ Company/ Industrial Project :
                                                <span> {{ (!empty($appInfo->company_id)) ? $userCompanyList[0]->company_name : ''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Name of Organization/ Company/ Industrial Project (বাংলা):
                                                <span> {{ (!empty($appInfo->company_id)) ? $userCompanyList[0]->company_name_bn : ''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Department :
                                                <span>{{ (!empty($appInfo->department_id)) ? $departmentList[$appInfo->department_id]:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country of Origin :
                                                <span> {{ (!empty($appInfo->country_of_origin_id)) ? $countries[$appInfo->country_of_origin_id]:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Type of the organization :
                                                <span>{{ (!empty($appInfo->organization_type_id)) ? $eaOrganizationType[$appInfo->organization_type_id] : $appInfo->organization_type_other  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Status of the organization :
                                                <span> {{ (!empty($appInfo->organization_status_id)) ? $eaOrganizationStatus[$appInfo->organization_status_id] : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Ownership status :
                                                <span>{{ (!empty($appInfo->ownership_status_id)) ? $eaOwnershipStatus[$appInfo->ownership_status_id] : $appInfo->ownership_status_other }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Business sector :
                                                <span> {{ (!empty($appInfo->business_sector_id)) ? $sectors[$appInfo->business_sector_id] : $appInfo->business_sector_others  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Sub sector:
                                                <span>{{ (!empty($appInfo->business_sub_sector_id)) ? $sub_sectors[$appInfo->business_sub_sector_id] : $appInfo->business_sub_sector_others  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;" >
                                                Major activities in brief :
                                                <span>{{ (!empty($appInfo->major_activities)) ? $appInfo->major_activities :''  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Registration Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Reg Info Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>  
                                        <tr>
                                            <td colspan="2" style="text-align: center; padding: 5px;">
                                                @if($appInfo->is_registered == 'yes')
                                                    <img src="assets/images/checked.png" width="10" height="10" alt="Checked Icon"/> Registered
                                                @else
                                                    <img src="assets/images/checked.png" width="10" height="10" alt="Checked Icon"/> Non-registered
                                                @endif
                                            </td>
                                        </tr>

                                        @if($appInfo->is_registered == 'yes')
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Registered by :
                                                    <span> {{ (!empty($appInfo->registered_by_id)) ? $eaRegistrationType[$appInfo->registered_by_id]:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Date :
                                                    <span>{{ (!empty($appInfo->registration_date)) ? date('d-M-Y', strtotime($appInfo->registration_date)):''  }}</span>
                                                </td>
                                            </tr>

                                            @if(in_array($appInfo->registered_by_id, [1,3,4]))
                                                <tr>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Registration No. :
                                                        <span> {{ (!empty($appInfo->registration_no)) ? $appInfo->registration_no:''  }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;" >
                                                        Attach the copy of Registration/ Permission No. :
                                                        @if(!empty($appInfo->registration_copy))
                                                            <div class="save_file">
                                                                <a target="_blank" rel="noopener" title="" href="{{URL::to('/uploads/'.(!empty($appInfo->registration_copy) ?
                                                            $appInfo->registration_copy : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                                </a>
                                                            </div>
                                                        @else
                                                            No file found
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif

                                            @if(in_array($appInfo->registered_by_id, [12]))
                                                <tr>
                                                    <td colspan="2" style="padding: 5px;" >
                                                        Details :
                                                        <span> {{ (!empty($appInfo->registration_other)) ? $appInfo->registration_other:''  }}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td width="50%" style="padding: 5px;" >
                                                    Registered by others :
                                                    <span> {{ (!empty($appInfo->registered_by_other)) ? $appInfo->registered_by_other:''  }}</span>
                                                </td>
                                                <td width="50%" style="padding: 5px;" >
                                                    Attach the copy of Registration/Permission No. :
                                                    @if(!empty($appInfo->registration_copy))
                                                        <div class="save_file">
                                                            <a target="_blank" rel="noopener" title="" href="{{URL::to('/uploads/'.(!empty($appInfo->registration_copy) ?
                                                            $appInfo->registration_copy : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                            </a>
                                                        </div>
                                                    @else
                                                        No file found
                                                    @endif
                                                </td>
                                            </tr>
                                            {{--<tr>--}}
                                                {{--<td width="50%" style="padding: 5px;" >--}}
                                                    {{--Incorporation Certificate Number :--}}
                                                    {{--<span> {{ (!empty($appInfo->incorporation_certificate_number)) ? $appInfo->incorporation_certificate_number:''  }}</span>--}}
                                                {{--</td>--}}
                                                {{--<td width="50%" style="padding: 5px;" >--}}
                                                    {{--Date :--}}
                                                    {{--<span>{{ (!empty($appInfo->incorporation_certificate_date)) ? date('d-M-Y', strtotime($appInfo->incorporation_certificate_date)):''  }}</span>--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Information of Principal Promoter/Chairman/Managing Director/CEO</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Principal Info Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Country :
                                                <span> {{ (!empty($appInfo->ceo_country_id)) ? $countries[$appInfo->ceo_country_id] : ''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Date of Birth :
                                                <span>{{ !empty($appInfo->ceo_dob) ? date('d-M-Y', strtotime($appInfo->ceo_dob)) : ''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;">
                                                    NID No. :
                                                    <span>{{ (!empty($appInfo->ceo_nid)) ? $appInfo->ceo_nid:''  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;">
                                                    Passport No. :
                                                    <span>{{ (!empty($appInfo->ceo_passport_no)) ? $appInfo->ceo_passport_no:''  }}</span>
                                                </td>
                                            @endif

                                            <td width="50%" style="padding: 5px;" >
                                                Designation :
                                                <span>{{ (!empty($appInfo->ceo_designation)) ? $appInfo->ceo_designation:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Full Name :
                                                <span> {{ (!empty($appInfo->ceo_full_name)) ? $appInfo->ceo_full_name:''  }}</span>
                                            </td>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;" >
                                                    District/City/State :
                                                    <span>{{ (!empty($appInfo->ceo_district_id)) ? $districts[$appInfo->ceo_district_id]:''  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;" >
                                                    District/ City/ State :
                                                    <span>{{ (!empty($appInfo->ceo_city)) ? $appInfo->ceo_city:''  }}</span>
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if($appInfo->ceo_country_id == 18)
                                                <td width="50%" style="padding: 5px;" >
                                                    Police Station/Town :
                                                    <span> {{ (!empty($appInfo->ceo_thana_id)) ? $thana_eng[$appInfo->ceo_thana_id]:''  }}</span>
                                                </td>
                                            @else
                                                <td width="50%" style="padding: 5px;" >
                                                    State/Province :
                                                    <span>{{ (!empty($appInfo->ceo_state)) ? $appInfo->ceo_state:''  }}</span>
                                                </td>
                                            @endif

                                            <td width="50%" style="padding: 5px;" >
                                                Post/Zip Code :
                                                <span>{{ (!empty($appInfo->ceo_post_code)) ? $appInfo->ceo_post_code:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->ceo_address)) ? $appInfo->ceo_address:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->ceo_telephone_no)) ? $appInfo->ceo_telephone_no:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->ceo_mobile_no)) ? $appInfo->ceo_mobile_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Father's Name :
                                                <span> {{ (!empty($appInfo->ceo_father_name)) ? $appInfo->ceo_father_name:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->ceo_email)) ? $appInfo->ceo_email:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mother's Name :
                                                <span>{{ (!empty($appInfo->ceo_mother_name)) ? $appInfo->ceo_mother_name:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No :
                                                <span>{{ (!empty($appInfo->ceo_fax_no)) ? $appInfo->ceo_fax_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Spouse name :
                                                <span>{{ (!empty($appInfo->ceo_spouse_name)) ? $appInfo->ceo_spouse_name:''  }}</span>
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
                                    <table width="100%" cellpadding="10" aria-label="Detailed Office Info Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Division :
                                                <span> {{ (!empty($appInfo->office_division_id)) ? $divisions[$appInfo->office_division_id]:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->office_district_id)) ? $districts[$appInfo->office_district_id]:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->office_thana_id)) ? $thana_eng[$appInfo->office_thana_id]:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Office :
                                                <span> {{ (!empty($appInfo->office_post_office)) ? $appInfo->office_post_office:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span>{{ (!empty($appInfo->office_post_code)) ? $appInfo->office_post_code:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->office_address)) ? $appInfo->office_address:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->office_telephone_no)) ? $appInfo->office_telephone_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->office_mobile_no)) ? $appInfo->office_mobile_no:''  }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Fax No :
                                                <span>{{ (!empty($appInfo->office_fax_no)) ? $appInfo->office_fax_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->office_email)) ? $appInfo->office_email:''  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Factory Address (Optional)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Factory Info Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                District :
                                                <span> {{ (!empty($appInfo->factory_district_id)) ? $districts[$appInfo->factory_district_id]:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Police Station :
                                                <span> {{ (!empty($appInfo->factory_thana_id)) ? $thana_eng[$appInfo->factory_thana_id]:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Office :
                                                <span> {{ (!empty($appInfo->factory_post_office)) ? $appInfo->factory_post_office:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Post Code :
                                                <span>{{ (!empty($appInfo->factory_post_code)) ? $appInfo->factory_post_code:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                House,Flat/Apartment,Road :
                                                <span> {{ (!empty($appInfo->factory_address)) ? $appInfo->factory_address:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Telephone No :
                                                <span>{{ (!empty($appInfo->factory_telephone_no)) ? $appInfo->factory_telephone_no:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->factory_mobile_no)) ? $appInfo->factory_mobile_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Fax No :
                                                <span>{{ (!empty($appInfo->factory_fax_no)) ? $appInfo->factory_fax_no:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Email :
                                                <span>{{ (!empty($appInfo->factory_email)) ? $appInfo->factory_email:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Mouja No :
                                                <span>{{ (!empty($appInfo->factory_mouja)) ? $appInfo->factory_mouja:''  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <br/>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered" aria-label="Detailed Manpower Info Data Table" width="100%">
                                        <tr>
                                            <th class="text-center" colspan="9" style="padding: 5px;" scope="col">Manpower of the office</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" colspan="3" style="padding: 5px;" scope="col">Local (Bangladesh only)</th>
                                            <th class="text-center" colspan="3" style="padding: 5px;" scope="col">Foreign (Abroad country)</th>
                                            <th class="text-center" colspan="1" style="padding: 5px;" scope="col">Grand total</th>
                                            <th class="text-center" colspan="2" style="padding: 5px;" scope="col">Ratio</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" style="padding: 5px;" scope="col">Executive</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">Supporting staff</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">Total (a)</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">Executive</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">Supporting staff</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">Total (b)</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">(a+b)</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">Local</th>
                                            <th class="text-center" style="padding: 5px;" scope="col">Foreign</th>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->local_executive))? $appInfo->local_executive:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->local_stuff))? $appInfo->local_stuff:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->local_total))? $appInfo->local_total:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->foreign_executive))? $appInfo->foreign_executive:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->foreign_stuff))? $appInfo->foreign_stuff:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->foreign_total))? $appInfo->foreign_total:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->manpower_total))? $appInfo->manpower_total:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->manpower_local_ratio))? $appInfo->manpower_local_ratio:''  }}</span>
                                            </td>
                                            <td style="padding: 5px;">
                                                <span> {{ (!empty($appInfo->manpower_foreign_ratio))? $appInfo->manpower_foreign_ratio:''  }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Necessary documents to be attached here (Only PDF file to be attach here)</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover" aria-label="Detailed Attachment Info Data Table">
                                        <thead>
                                        <tr>
                                            <th style="padding: 5px;" scope="col">No.</th>
                                            <th colspan="6" style="padding: 5px;" scope="col">Required attachments</th>
                                            <th colspan="2" style="padding: 5px;" scope="col">Attached PDF file (Each File Maximum size 2MB)
                                                {{--<span>--}}
                                                {{--<i title="Attached PDF file (Each File Maximum size 2MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>--}}
                                                {{--</span>--}}
                                            </th>
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
                                                    @if(!empty($clrDocuments[$row->id]['doc_file_path']))

                                                        <div class="save_file">
                                                            <a target="_blank" rel="noopener" title=""
                                                               href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['doc_file_path']) ?
                                                            $clrDocuments[$row->id]['doc_file_path'] : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
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

                        <div class="panel panel-info">
                            <div class="panel-heading">Authorized Persons Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Authorized Persons Info Data Table">
                                        <thead>
                                            <tr class="d-none">
                                                <th aria-hidden="true" scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Full Name :
                                                <span> {{ (!empty($appInfo->auth_full_name)) ? $appInfo->auth_full_name:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Designation :
                                                <span>{{ (!empty($appInfo->auth_designation)) ? $appInfo->auth_designation:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Mobile No :
                                                <span> {{ (!empty($appInfo->auth_mobile_no)) ? $appInfo->auth_mobile_no:''  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Email address :
                                                <span>{{ (!empty($appInfo->auth_email)) ? $appInfo->auth_email:''  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;" >
                                                Authorization Letter :
                                                @if(!empty($appInfo->auth_letter))
                                                    <div class="save_file">
                                                        <a target="_blank" rel="noopener" title="" href="{{URL::to('users/upload/'.(!empty($appInfo->auth_letter) ?
                                                                $appInfo->auth_letter : ''))}}"> <img width="10" height="10" src="assets/images/pdf.png" alt="pdf" /> Open File
                                                        </a>
                                                    </div>
                                                @else
                                                    No file found
                                                @endif
                                            </td>
                                            <td width="50%" style="padding: 5px;" >
                                                Profile Picture :

                                                @if(file_exists("users/upload/".$appInfo->auth_image))
                                                    <img class="img-thumbnail" width="50" height="50" src="users/upload/{{ $appInfo->auth_image }}" alt="Applicant Photo" />
                                                @else
                                                    <img class="img-thumbnail" width="50" height="50" src="assets/images/no_image.png" alt="Image not found" />
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                Signature :
                                                @if(file_exists("users/signature/".$appInfo->auth_signature))
                                                    <img class="img-thumbnail" width="50" height="50" src="users/signature/{{ $appInfo->auth_signature }}" alt="Applicant Photo" />
                                                @else
                                                    <img class="img-thumbnail" width="50" height="50" src="assets/images/no_image.png" alt="Image not found" />
                                                @endif
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="ep_form" class="panel panel-info">
                            <div class="panel-heading">Terms and Conditions</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10" aria-label="Detailed Terms Data Table">
                                        <tr>
                                            <th aria-hidden="true" scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">
                                                @if($appInfo->acceptTerms == 1)
                                                    <img src="assets/images/checked.png" width="10" height="10" alt="Checked Icon"/>
                                                    I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given.
                                                @endif
                                            </td>
                                        </tr>
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
