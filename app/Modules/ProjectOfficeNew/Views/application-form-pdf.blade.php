<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset(' assets/stylesheets/styles.css') }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"
        integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous" />
</head>

<body>
    <section class="content" id="applicationForm">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <img src="assets/images/bida_logo.png" style="width: 100px" alt="bida_logo" /><br />
                            <br>
                            Bangladesh Investment Development Authority (BIDA)<br />
                            Application for Project Office New
                        </div>
                    </div>
                    <div class="panel panel-info" id="inputForm">
                        <div class="panel-heading">Application for Project Office New</div>
                        <div class="panel-body">
                            <table aria-label="Detailed Application for Project Office New" width="100%">
                                <tr>
                                    {{-- <th aria-hidden="true" scope="col"></th> --}}
                                </tr>
                                <tr>
                                    <td
                                        style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">
                                        Tracking no. : <span>{{ $appInfo->tracking_no }}</span></td>
                                    <td style="padding: 5px;">Date of Submission :
                                        <span>{{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding-top: 5px; padding-right: 5px; padding-left: 15px; padding-bottom: 5px;">
                                        Current Status : <span>{{ $appInfo->status_name }}</span></td>
                                    <td style="padding: 5px;">Current Desk :
                                        <span>
                                            @if ($appInfo->desk_id != 0)
                                                {{ $appInfo->desk_name }}
                                            @else
                                                Applicant
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            {{-- basic company information section --}}
                            @include('ProcessPath::basic-company-info-pdf')

                            {{-- meeting_info --}}
                            @if (!empty($metingInformation))
                                <div id="ep_form" class="panel panel-info">
                                    <div class="panel-heading">Meeting Information</div>
                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <table aria-label="Detailed Report Meeting Information" width="100%"
                                                cellpadding="10">
                                                <tr>
                                                    {{-- <th aria-hidden="true" scope="col"></th> --}}
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="padding: 5px;">
                                                        Meeting No. :
                                                        <span>{{ !empty($metingInformation->meting_number) ? $metingInformation->meting_number : '' }}</span>
                                                    </td>
                                                    <td style="padding: 5px;">Meeting Date :
                                                        <span>{{ !empty($metingInformation->meting_date) ? date('d-M-Y', strtotime($metingInformation->meting_date)) : '' }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- meeting_info --}}

                            {{-- 1. Project Information --}}
                            <div id="ep_form" class="panel panel-info">
                                <div class="panel-heading">Project Information</div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Report Project Information"
                                            style="width: 100%; border-collapse: separate;">
                                            <thead>
                                                <tr class="d-none">
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Name of the Project</td>
                                                    <td>:</td>
                                                    <td>
                                                        <span>{{ !empty($appInfo->project_name) ? $appInfo->project_name : '' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Project Major Activities In Brief</td>
                                                    <td>:</td>
                                                    <td>
                                                        <span>{{ !empty($appInfo->project_major_activities) ? $appInfo->project_major_activities : '' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Project Major In Details</td>
                                                    <td>:</td>
                                                    <td>
                                                        <span>{{ !empty($appInfo->project_major_details) ? $appInfo->project_major_details : '' }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- 1. Project Information --}}

                            {{-- 2. Information of the company(s) composing JV/ Consortium/ association office --}}
                            <div class="panel panel-info">
                                <div class="panel-heading">2. Information of the company(s) composing JV/ Consortium/
                                    association office</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            @if (count($ponCompanyOfficeList) > 0)
                                                @foreach ($ponCompanyOfficeList as $row)
                                                    <table style="width: 100%; border-collapse: separate;"
                                                        aria-label="Information of the company(s) composing JV/ Consortium/ association office">
                                                        <caption
                                                            style="caption-side: top; font-weight: bold; text-align: left; padding: 10px 0;">
                                                            Company Information
                                                        </caption>
                                                        <thead>
                                                            <tr class="d-none">
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>The company Office Permission been approved by BIDA?
                                                                </td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->company_office_approved) ? ucfirst($row->company_office_approved) : '' }}</span>
                                                                </td>
                                                            </tr>

                                                            <!-- Online OSS Approval -->
                                                            @if (!empty($row->company_office_approved) && $row->company_office_approved == 'yes')
                                                                <tr>
                                                                    <td>Did you receive your Office
                                                                        Permission New / Office Permission Extension
                                                                        online
                                                                        OSS?</td>
                                                                    <td>:</td>
                                                                    <td>
                                                                        <span>
                                                                            {{ !empty($row->is_approval_online) ? ucfirst($row->is_approval_online) : '' }}</span>
                                                                    </td>
                                                                </tr>
                                                                <!-- Online OSS Tracking Number -->
                                                                @if (!empty($row->is_approval_online) && $row->is_approval_online == 'yes')
                                                                    <tr>
                                                                        <td>Approved Office Permission New /
                                                                            Office Permission Extension Tracking No.
                                                                        </td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <span>{{ !empty($row->ref_app_tracking_no) ? ucfirst($row->ref_app_tracking_no) : '' }}</span>
                                                                        </td>
                                                                        <td>
                                                                            {!! !empty($row->ref_app_tracking_no)
                                                                                ? \App\Libraries\CommonFunction::getCertificateByTrackingNo($row->ref_app_tracking_no)
                                                                                : '' !!}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Approved Date</td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <span>{{ !empty($row->ref_app_approve_date) ? date('d-M-Y', strtotime($row->ref_app_approve_date)) : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endif

                                                            <!-- Manual Approval -->
                                                            @if (!empty($row->is_approval_online) && $row->is_approval_online == 'no')
                                                                <tr>
                                                                    <td>Manually approved Office
                                                                        Permission New / Office Permission Extension
                                                                        Memo
                                                                        Number</td>
                                                                    <td>:</td>
                                                                    <td>
                                                                        <span>{{ !empty($row->manually_approved_op_no) ? $row->manually_approved_op_no : '' }}</span>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Approval Copy</td>
                                                                    <td>:</td>
                                                                    <td>
                                                                        @if (!empty($row->approval_copy))
                                                                            <a href="{{ asset($row->approval_copy) }}"
                                                                                target="_blank"
                                                                                class="btn btn-sm btn-info">View
                                                                                Document</a>
                                                                        @else
                                                                            No document available
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Approved Date</td>
                                                                    <td>:</td>
                                                                    <td>
                                                                        <span>{{ !empty($row->manually_approved_br_date) ? date('d-M-Y', strtotime($row->manually_approved_br_date)) : '' }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endif



                                                            <tr>
                                                                <td>Name of company</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_company_name) ? $row->c_company_name : '' }}</span>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>Country of origin</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_origin_country_name) ? $row->c_origin_country_name : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Type of the organization</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_org_type_name) ? $row->c_org_type_name : '' }}</span>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>Flat/ Apartment/ Floor no.</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_flat_apart_floor) ? $row->c_flat_apart_floor : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>House/ Plot/ Holding no.</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_house_plot_holding) ? $row->c_house_plot_holding : '' }}</span>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>Post/ Zip code</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_post_zip_code) ? $row->c_post_zip_code : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Street name/ Street no.</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_street) ? $row->c_street : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Email</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_email) ? $row->c_email : '' }}</span>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>District/ City</td>
                                                                <td>:</td>
                                                                <td>
                                                                    @if ($row->c_origin_country_id == 18)
                                                                        <span>{{ !empty($row->c_district_name) ? $row->c_district_name : '' }}</span>
                                                                    @else
                                                                        <span>{{ !empty($row->c_city) ? $row->c_city : '' }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                @if ($row->c_origin_country_id == 18)
                                                                    <td>Police Station/ Town</td>
                                                                    <td>:</td>
                                                                    <td>
                                                                        <span>{{ !empty($row->c_thana_name) ? $row->c_thana_name : '' }}</span>
                                                                    </td>
                                                                @else
                                                                    <td>State/ Province</td>
                                                                    <td>:</td>
                                                                    <td>
                                                                        <span>{{ !empty($row->c_state_province) ? $row->c_state_province : '' }}</span>
                                                                    </td>
                                                                @endif
                                                            </tr>

                                                            <tr>
                                                                <td>Mobile no.</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ !empty($row->c_mobile_no) ? $row->c_mobile_no : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shareholder percentage</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ isset($row->c_shareholder_percentage) ? $row->c_shareholder_percentage : '' }}%</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Major activities in brief</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <span>{{ isset($row->c_major_activity_brief) ? $row->c_major_activity_brief : '' }}</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- 2. Information of the company(s) composing JV/ Consortium/ association office --}}

                            {{-- Information about the Project Office --}}
                            <div class="panel panel-info">
                                <div class="panel-heading">Information about the Project Office</div>
                                <div class="panel-body" style="padding: 5px">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">3. Project Office Address (corporate office)
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table aria-label="Project Office Address (corporate office)"
                                                        style="width: 100%; border-collapse: separate;">
                                                        <thead>
                                                            <tr class="d-none">
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="width: 15%;">Division</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_division_name) ? $appInfo->poa_co_division_name : '' }}</span>
                                                                </td>
                                                                <td style="width: 15%;">District</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_district_name) ? $appInfo->poa_co_district_name : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 15%;">Police station</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_thana_name) ? $appInfo->poa_co_thana_name : '' }}</span>
                                                                </td>
                                                                <td style="width: 15%;">Post office</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_post_office) ? $appInfo->poa_co_post_office : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 15%;">Post code</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_post_code) ? $appInfo->poa_co_post_code : '' }}</span>
                                                                </td>
                                                                <td style="width: 15%;">House, Flat/ Apartment, Road
                                                                </td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_address) ? $appInfo->poa_co_address : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 15%;">Telephone no</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_telephone_no) ? $appInfo->poa_co_telephone_no : '' }}</span>
                                                                </td>
                                                                <td style="width: 15%;">Mobile no.</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_mobile_no) ? $appInfo->poa_co_mobile_no : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 15%;">Fax no.</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_fax_no) ? $appInfo->poa_co_fax_no : '' }}</span>
                                                                </td>
                                                                <td style="width: 15%;">Email</td>
                                                                <td style="width: 1%;">:</td>
                                                                <td style="width: 34%;">
                                                                    <span>{{ !empty($appInfo->poa_co_email) ? $appInfo->poa_co_email : '' }}</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">4. Project Office Address (site office)
                                                </div>
                                                <div class="panel-body">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            @if (count($ponSiteOfficeList) > 0)
                                                                @foreach ($ponSiteOfficeList as $row)
                                                                    <table
                                                                        aria-label="Project Office Address (site office)"
                                                                        style="width: 100%; border-collapse: separate;">
                                                                        <caption
                                                                            style="caption-side: top; font-weight: bold; text-align: left; padding: 10px 0;">
                                                                            Site Office
                                                                        </caption>
                                                                        <thead>
                                                                            <tr class="d-none">
                                                                                <th scope="col"></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="width: 15%;">Division</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>{{ !empty($row->poa_so_division_name) ? $row->poa_so_division_name : '' }}</span>
                                                                                </td>
                                                                                <td style="width: 15%;">District</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>{{ !empty($row->poa_so_district_name) ? $row->poa_so_district_name : '' }}</span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width: 15%;">Police station
                                                                                </td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>{{ !empty($row->poa_so_thana_name) ? $row->poa_so_thana_name : '' }}</span>
                                                                                </td>
                                                                                <td style="width: 15%;">Post office
                                                                                </td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->poa_so_post_office) ? $row->poa_so_post_office : '' }}</span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width: 15%;">Post code</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>{{ !empty($row->poa_so_thana_name) ? $row->poa_so_thana_name : '' }}</span>
                                                                                </td>
                                                                                <td style="width: 15%;">House, Flat/
                                                                                    Apartment,
                                                                                    Road</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->poa_so_address) ? $row->poa_so_address : '' }}</span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width: 15%;">Telephone no.
                                                                                </td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>{{ !empty($row->poa_so_telephone_no) ? $row->poa_so_telephone_no : '' }}</span>
                                                                                </td>
                                                                                <td style="width: 15%;">Mobile no.</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->poa_so_mobile_no) ? $row->poa_so_mobile_no : '' }}</span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width: 15%;">Fax no.</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->poa_so_fax_no) ? $row->poa_so_fax_no : '' }}
                                                                                    </span>
                                                                                </td>
                                                                                <td style="width: 15%;">Email</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->poa_so_email) ? $row->poa_so_email : '' }}
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="6"
                                                                                    style="font-weight: bold;">Site
                                                                                    Office Incharge Information :</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width: 15%;">Name</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->site_office_name) ? $row->site_office_name : '' }}
                                                                                    </span>
                                                                                </td>
                                                                                <td style="width: 15%;">Designation
                                                                                </td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->site_office_designation) ? $row->site_office_designation : '' }}
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width: 15%;">Mobile No.</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->site_office_mobile_no) ? $row->site_office_mobile_no : '' }}
                                                                                    </span>
                                                                                </td>
                                                                                <td style="width: 15%;">Email</td>
                                                                                <td style="width: 1%;">:</td>
                                                                                <td style="width: 34%;">
                                                                                    <span>
                                                                                        {{ !empty($row->site_office_email) ? $row->site_office_email : '' }}
                                                                                    </span>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="1">Authorize Letter
                                                                                </td>
                                                                                <td colspan="1">:</td>
                                                                                <td colspan="4">
                                                                                    @if (!empty($row->site_office_authorize_letter))
                                                                                        <a target="_blank"
                                                                                            rel="noopener"
                                                                                            title=""
                                                                                            href="{{ URL::to($row->site_office_authorize_letter) }}">
                                                                                            <img width="10"
                                                                                                height="10"
                                                                                                src="assets/images/pdf.png"
                                                                                                alt="pdf" />
                                                                                            Open File
                                                                                        </a>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                @endforeach
                                                            @endif

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">5. The contact Amount of the Project (in US
                                                    $)</div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table
                                                                aria-label="The contact Amount of the Project (in US $)"
                                                                style="width: 100%; border-collapse: separate;">
                                                                <thead>
                                                                    <tr class="d-none">
                                                                        <th scope="col"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>The contact Amount of the Project (in US $)
                                                                        </td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <span>{{ isset($appInfo->project_amount) ? $appInfo->project_amount : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">6. Proposed Project Duration (as per
                                                    contract)</div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table
                                                                aria-label="Proposed Project Duration (as per contract)"
                                                                style="width: 100%; border-collapse: separate;">
                                                                <thead>
                                                                    <tr class="d-none">
                                                                        <th scope="col"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="width: 15%;">Start and effective
                                                                            date
                                                                        </td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->period_start_date) ? date('d-M-Y', strtotime($appInfo->period_start_date)) : '' }}</span>
                                                                        </td>
                                                                        <td style="width: 15%;">End date</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->period_end_date) ? date('d-M-Y', strtotime($appInfo->period_end_date)) : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 15%;">Period of validity</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ $appInfo->period_validity ? $appInfo->period_validity : '' }}</span>
                                                                        </td>
                                                                        <td style="width: 15%;">Payable amount</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->duration_amount) ? $appInfo->duration_amount : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">7. Authorized Person of Procurement Entity
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table aria-label="Authorized Person of Procurement Entity"
                                                                style="width: 100%; border-collapse: separate;">
                                                                <thead>
                                                                    <tr class="d-none">
                                                                        <th scope="col"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="width: 15%;">Name</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>
                                                                                {{ !empty($appInfo->authorized_name) ? $appInfo->authorized_name : '' }}
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 15%;">Designation</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->authorized_designation) ? $appInfo->authorized_designation : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 15%;">Organization /
                                                                            Department</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->authorized_org_dep) ? $appInfo->authorized_org_dep : '' }}</span>
                                                                        </td>
                                                                        <td style="width: 15%;">Address</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->authorized_address) ? $appInfo->authorized_address : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 15%;">Mobile No.</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->authorized_mobile_no) ? $appInfo->authorized_mobile_no : '' }}</span>
                                                                        </td>
                                                                        <td style="width: 15%;">Email</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->authorized_email) ? $appInfo->authorized_email : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="1">Authorize Letter</td>
                                                                        <td colspan="1">:</td>
                                                                        <td colspan="4">
                                                                            @if (!empty($row->authorized_letter))
                                                                                <a target="_blank" rel="noopener"
                                                                                    title=""
                                                                                    href="{{ URL::to($row->authorized_letter) }}">
                                                                                    <img width="10" height="10"
                                                                                        src="assets/images/pdf.png"
                                                                                        alt="pdf" />
                                                                                    Open File
                                                                                </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="panel panel-default">
                                                <div class="panel-heading">8. Ministry/Department/Organization of the
                                                    project to be implemented
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table
                                                                aria-label="Ministry/Department/Organization of the project to be implemented"
                                                                style="width: 100%; border-collapse: separate;">
                                                                <thead>
                                                                    <tr class="d-none">
                                                                        <th scope="col"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="width: 15%;">Name</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>
                                                                                {{ !empty($appInfo->ministry_name) ? $appInfo->ministry_name : '' }}
                                                                            </span>
                                                                        </td>
                                                                        <td style="width: 15%;">Designation</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->authorized_designation) ? $appInfo->authorized_designation : '' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width: 15%;">Address</td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ !empty($appInfo->ministry_address) ? $appInfo->ministry_address : '' }}</span>
                                                                        </td>
                                                                        <td style="width: 15%;">Contract Signing Date
                                                                        </td>
                                                                        <td style="width: 1%;">:</td>
                                                                        <td style="width: 34%;">
                                                                            <span>{{ empty($appInfo->contract_signing_date) ? '' : date('d-M-Y', strtotime($appInfo->contract_signing_date)) }}</span>
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
                            {{-- Information about the Project Office --}}

                            {{-- 9. Proposed organizational set up of the Project Office with expatriate and local man power --}}
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    9. Proposed organizational set up of the Project Office with expatriate and local
                                    man power
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table style="width: 100%; border-collapse: separate;"
                                                aria-label="Report Detail Proposed organizational set up of the Project Office"
                                                class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="text-center" style="padding: 5px;"
                                                            colspan="3">
                                                            Local (a)</th>
                                                        <th scope="col" class="text-center" style="padding: 5px;"
                                                            colspan="3">
                                                            Foreign (b)</th>
                                                        <th scope="col" class="text-center" style="padding: 5px;"
                                                            colspan="1">
                                                            Grand Total</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center" style="padding: 5px;">Technical</td>
                                                        <td class="text-center" style="padding: 5px;">General</td>
                                                        <td class="text-center" style="padding: 5px;">Total</td>
                                                        <td class="text-center" style="padding: 5px;">Technical</td>
                                                        <td class="text-center" style="padding: 5px;">General</td>
                                                        <td class="text-center" style="padding: 5px;">Total</td>
                                                        <td class="text-center" style="padding: 5px;">(a+b)</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center" style="padding: 5px;">
                                                            {{ isset($appInfo->local_technical) ? $appInfo->local_technical : '' }}
                                                        </td>
                                                        <td class="text-center" style="padding: 5px;">
                                                            {{ isset($appInfo->local_general) ? $appInfo->local_general : '' }}
                                                        </td>
                                                        <td class="text-center" style="padding: 5px;">
                                                            {{ isset($appInfo->local_total) ? $appInfo->local_total : '' }}
                                                        </td>
                                                        <td class="text-center" style="padding: 5px;">
                                                            {{ isset($appInfo->foreign_technical) ? $appInfo->foreign_technical : '' }}
                                                        </td>
                                                        <td class="text-center" style="padding: 5px;">
                                                            {{ isset($appInfo->foreign_general) ? $appInfo->foreign_general : '' }}
                                                        </td>
                                                        <td class="text-center" style="padding: 5px;">
                                                            {{ isset($appInfo->foreign_total) ? $appInfo->foreign_total : '' }}
                                                        </td>
                                                        <td class="text-center" style="padding: 5px;">
                                                            {{ isset($appInfo->manpower_total) ? $appInfo->manpower_total : '' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @if (count($ponForeignDetailList) > 0)
                                                <table style="width: 100%; border-collapse: separate;"
                                                    aria-label="Foreign Technical & General Details"
                                                    class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center"
                                                                style="padding: 5px;">
                                                                Number of Foreign</th>
                                                            <th scope="col" class="text-center"
                                                                style="padding: 5px;">
                                                                Designation</th>
                                                            <th scope="col" class="text-center"
                                                                style="padding: 5px;">
                                                                Duration</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($ponForeignDetailList as $row)
                                                            <tr>
                                                                <td>
                                                                    {{ isset($row->foreign_number) ? $row->foreign_number : '' }}
                                                                </td>
                                                                <td>
                                                                    {{ !empty($row->foreign_designation) ? $row->foreign_designation : '' }}
                                                                </td>
                                                                <td>
                                                                    {{ isset($row->foreign_duration) ? $row->foreign_duration : '' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- 9. Proposed organizational set up of the Project Office with expatriate and local man power --}}

                            {{-- Necessary documents to be attached here (Only PDF file) --}}
                            <div class="panel panel-info">
                                <div class="panel-heading">Necessary documents to be attached here (Only PDF file)
                                </div>
                                <div class="panel-body">
                                    <div class="col-md-12">
                                        <table aria-label="Detailed Report Necessary Document"
                                            class="table table-striped table-bordered table-hover ">
                                            <thead>
                                                <tr>
                                                    <th style="padding: 5px;" scope="col">No.</th>
                                                    <th colspan="6" style="padding: 5px;" scope="col">Required
                                                        attachments</th>
                                                    <th colspan="2" style="padding: 5px;" scope="col">Attached
                                                        PDF file
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($document as $row)
                                                    <tr>
                                                        <td style="padding: 5px;">
                                                            <div align="left">{!! $i !!}
                                                                <?php echo $row->doc_priority == '1' ? "<span class='required-star'></span>" : ''; ?>
                                                            </div>
                                                        </td>
                                                        <td colspan="6" style="padding: 5px;">
                                                            {!! $row->doc_name !!}</td>
                                                        <td colspan="2" style="padding: 5px;">
                                                            @if (!empty($row->doc_file_path))
                                                                <div class="save_file">
                                                                    <a target="_blank" rel="noopener" title=""
                                                                        href="{{ URL::to('/uploads/' . (!empty($row->doc_file_path) ? $row->doc_file_path : '')) }}">
                                                                        <img width="10" height="10"
                                                                            src="assets/images/pdf.png"
                                                                            alt="pdf" />
                                                                        Open File
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
                            {{-- Necessary documents to be attached here (Only PDF file) --}}

                            {{-- Payment Inforrmation --}}
                            <div class="panel panel-info">
                                <div class="panel-heading">Payment Info</div>
                                <div class="panel-body" style="padding: 5px">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="padding: 2px 5px;">Service Fee Payment</div>
                                        <div class="panel-body">
                                            <div class="col-md-12">
                                                <div class="row" style="padding: 5px">
                                                    <table aria-label="Detailed Report Payment Info"
                                                        class="table table-striped table-bordered">
                                                        <tr>
                                                            {{-- <th aria-hidden="true" scope="col"></th> --}}
                                                        </tr>
                                                        <tr>
                                                            <td>Contact name :
                                                                <span>{{ !empty($appInfo->sfp_contact_name) ? $appInfo->sfp_contact_name : '' }}</span>
                                                            </td>
                                                            <td>Contact email :
                                                                <span>{{ !empty($appInfo->sfp_contact_email) ? $appInfo->sfp_contact_email : '' }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Contact phone :
                                                                <span>{{ !empty($appInfo->sfp_contact_phone) ? $appInfo->sfp_contact_phone : '' }}</span>
                                                            </td>
                                                            <td>Contact address :
                                                                <span>{{ !empty($appInfo->sfp_contact_address) ? $appInfo->sfp_contact_address : '' }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Pay amount :
                                                                <span>{{ !empty($appInfo->sfp_pay_amount) ? $appInfo->sfp_pay_amount : '' }}</span>
                                                            </td>
                                                            <td>VAT on pay amount :
                                                                <span>{{ !empty($appInfo->sfp_vat_on_pay_amount) ? $appInfo->sfp_vat_on_pay_amount : '' }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Transaction charge :
                                                                <span>{{ !empty($appInfo->sfp_transaction_charge_amount) ? $appInfo->sfp_transaction_charge_amount : '' }}</span>
                                                            </td>
                                                            <td>
                                                                VAT on transaction charge:
                                                                <span>{{ !empty($appInfo->sfp_vat_on_transaction_charge) ? $appInfo->sfp_vat_on_transaction_charge : '' }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Amount :
                                                                <span>{{ !empty($appInfo->sfp_total_amount) ? $appInfo->sfp_total_amount : '' }}</span>
                                                            </td>
                                                            <td>Payment Status :
                                                                @if ($appInfo->sfp_payment_status == 0)
                                                                    <span class="label label-warning">Pending</span>
                                                                @elseif($appInfo->sfp_payment_status == -1)
                                                                    <span class="label label-info">In-Progress</span>
                                                                @elseif($appInfo->sfp_payment_status == 1)
                                                                    <span class="label label-success">Paid</span>
                                                                @elseif($appInfo->sfp_payment_status == 2)
                                                                    <span class="label label-danger">-Exception</span>
                                                                @elseif($appInfo->sfp_payment_status == 3)
                                                                    <span class="label label-warning">Waiting for
                                                                        Payment
                                                                        Confirmation</span>
                                                                @else
                                                                    <span class="label label-warning">invalid
                                                                        status</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Payment Mode :
                                                                <span>{{ !empty($appInfo->sfp_pay_mode_code) ? $appInfo->sfp_pay_mode_code : '' }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($appInfo->gf_payment_id != 0 && !empty($appInfo->gf_payment_id))
                                        <div class="panel panel-default">
                                            <div class="panel-heading" style="padding: 2px 5px;">Government Fee
                                                Payment
                                            </div>
                                            <div class="panel-body">
                                                <div class="col-md-12">
                                                    <div class="row" style="padding: 5px">
                                                        <table aria-label="Detailed Report Government Fee Payment"
                                                            class="table table-striped table-bordered">
                                                            <tr>
                                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                                            </tr>
                                                            <tr>
                                                                <td>Contact name :
                                                                    <span>{{ !empty($appInfo->gfp_contact_name) ? $appInfo->gfp_contact_name : '' }}</span>
                                                                </td>
                                                                <td>Contact email :
                                                                    <span>{{ !empty($appInfo->gfp_contact_email) ? $appInfo->gfp_contact_email : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Contact phone :
                                                                    <span>{{ !empty($appInfo->gfp_contact_phone) ? $appInfo->gfp_contact_phone : '' }}</span>
                                                                </td>
                                                                <td>Contact address :
                                                                    <span>{{ !empty($appInfo->gfp_contact_address) ? $appInfo->gfp_contact_address : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Pay amount :
                                                                    <span>{{ !empty($appInfo->gfp_pay_amount) ? $appInfo->gfp_pay_amount : '' }}</span>
                                                                </td>
                                                                <td>VAT on pay amount :
                                                                    <span>{{ !empty($appInfo->gfp_vat_on_pay_amount) ? $appInfo->gfp_vat_on_pay_amount : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Transaction charge :
                                                                    <span>{{ !empty($appInfo->gfp_transaction_charge_amount) ? $appInfo->gfp_transaction_charge_amount : '' }}</span>
                                                                </td>
                                                                <td>
                                                                    VAT on transaction charge:
                                                                    <span>{{ !empty($appInfo->gfp_vat_on_transaction_charge) ? $appInfo->gfp_vat_on_transaction_charge : '' }}</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Amount :
                                                                    <span>{{ !empty($appInfo->gfp_total_amount) ? $appInfo->gfp_total_amount : '' }}</span>
                                                                </td>
                                                                <td>Payment Status :
                                                                    @if ($appInfo->gfp_payment_status == 0)
                                                                        <span
                                                                            class="label label-warning">Pending</span>
                                                                    @elseif($appInfo->gfp_payment_status == -1)
                                                                        <span
                                                                            class="label label-info">In-Progress</span>
                                                                    @elseif($appInfo->gfp_payment_status == 1)
                                                                        <span class="label label-success">Paid</span>
                                                                    @elseif($appInfo->gfp_payment_status == 2)
                                                                        <span
                                                                            class="label label-danger">-Exception</span>
                                                                    @elseif($appInfo->gfp_payment_status == 3)
                                                                        <span class="label label-warning">Waiting for
                                                                            Payment
                                                                            Confirmation</span>
                                                                    @else
                                                                        <span class="label label-warning">invalid
                                                                            status</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Payment Mode :
                                                                    <span>{{ !empty($appInfo->gfp_pay_mode_code) ? $appInfo->gfp_pay_mode_code : '' }}</span>
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
                                        <table aria-label="Detailed Report Declaration and undertaking" width="100%"
                                            cellpadding="10">
                                            <tr>
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
                                            </tr>
                                            <tr>
                                                <td width="" style="padding: 5px;">
                                                    <p>a. I do hereby declare that the information given above is true
                                                        to
                                                        the best of my knowledge and I shall be liable for any false
                                                        information/ statement given</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px;">
                                                    <p>b. I do hereby undertake full responsibility of the expatriate
                                                        for
                                                        whom visa recommendation is sought during their stay in
                                                        Bangladesh. </p>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        <table aria-label="Detailed Report Data Table" width="100%"
                                            cellpadding="10">
                                            <tr>
                                                {{-- <th aria-hidden="true" scope="col"></th> --}}
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
                                                        <span>
                                                            {{ !empty($appInfo->auth_full_name) ? $appInfo->auth_full_name : 'N/A' }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;">
                                                        Designation :
                                                        <span>{{ !empty($appInfo->auth_designation) ? $appInfo->auth_designation : 'N/A' }}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;">
                                                        Mobile No :
                                                        <span>
                                                            {{ !empty($appInfo->auth_mobile_no) ? $appInfo->auth_mobile_no : 'N/A' }}</span>
                                                    </td>
                                                    <td width="50%" style="padding: 5px;">
                                                        Email address :
                                                        <span>{{ !empty($appInfo->auth_email) ? $appInfo->auth_email : 'N/A' }}</span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td width="50%" style="padding: 5px;">
                                                        Profile Picture :

                                                        @if (file_exists('users/upload/' . $appInfo->auth_image))
                                                            <img class="img-thumbnail" width="60" height="60"
                                                                src="users/upload/{{ $appInfo->auth_image }}"
                                                                alt="Applicant Photo" />
                                                        @else
                                                            <img class="img-thumbnail" width="60" height="60"
                                                                src="assets/images/no_image.png"
                                                                alt="Image not found" />
                                                        @endif
                                                    </td>
                                                    <td colspan="3" style="padding: 5px;">
                                                        Date :
                                                        <?php echo date('F d,Y'); ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-12">
                                                &nbsp; @if ($appInfo->accept_terms == 1)
                                                    <img src="assets/images/checked.png" width="10"
                                                        height="10" alt="checked_icon" />
                                                @else
                                                    <img src="assets/images/unchecked.png" width="10"
                                                        height="10" alt="unchecked_icon" />
                                                @endif
                                                <label for="acceptTerms-2"
                                                    class="col-md-11 text-left required-star form-control">
                                                    <span>
                                                        I do here by declare that the information given above is true to
                                                        the
                                                        best of my knowledge and I shall be liable for any false
                                                        information/
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
</body>

</html>
