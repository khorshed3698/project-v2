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
                        Apply for Trade License to Bangladesh
                    </div>
                    <div class="panel panel-info" id="inputForm">
                        <div class="panel-heading">Apply for Trade License to Bangladesh</div>
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
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">A. Application for Trade License to Dhaka South City
                                Corporation
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Country:
                                                <span> {{ (!empty($appInfo->country)) ? $countries[$appInfo->country]:'N/A'  }}</span>
                                            </td>

                                            <td width="50%" style="padding: 5px;">
                                                Picture of CEO/MD/Head of Organization :<br>
                                                @if($appInfo->applicant_pic != '')
                                                    <img class="img-thumbnail" width="100" height="auto"
                                                         src="users/upload/{{ $appInfo->applicant_pic}}"
                                                         alt="Applicant Photo"/>
                                                @else
                                                    <img class="img-thumbnail" width="100" height="auto"
                                                         src="assets/images/no_image.png" alt="Image not found"/>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>

                                            <td width="50%" style="padding: 5px;">
                                                Name of Organization :
                                                <span> {{ (!empty($appInfo->organization_name)) ? $appInfo->organization_name :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Spouse Name
                                                <span>{{ (!empty($appInfo->spouse_name)) ? $appInfo->spouse_name :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td width="50%" style="padding: 5px;">
                                                Name of Applicant :
                                                <span> {{ (!empty($appInfo->applicant_name)) ? $appInfo->applicant_name :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Email :
                                                <span>{{ (!empty($appInfo->applicant_email)) ? $appInfo->applicant_email :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td width="50%" style="padding: 5px;">
                                                Father\'s Name :
                                                <span> {{ (!empty($appInfo->applicant_father)) ? $appInfo->applicant_father :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                License Type :
                                                <span>{{ (!empty($appInfo->applicant_license_type)) ? $licenceType[$appInfo->applicant_license_type] :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Mother\'s Name :
                                                <span> {{ (!empty($appInfo->applicant_mother)) ? $appInfo->applicant_mother :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Date of Birth :
                                                <span>{{ (!empty($appInfo->applicant_license_type)) ? (date('d-M-Y', strtotime($appInfo->applicant_dob))) :'N/A'  }}</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">B. Business Information</div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table width="100%" cellpadding="10">
                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Business Name :
                                                <span> {{ (!empty($appInfo->business_name)) ? $appInfo->business_name :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Business Details :
                                                <span>{{ (!empty($appInfo->business_details)) ? $appInfo->business_details :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td width="50%" style="padding: 5px;">
                                                Holding No :
                                                <span> {{ (!empty($appInfo->business_holding)) ? $appInfo->business_holding :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Address :
                                                <span>{{ (!empty($appInfo->business_address)) ? $appInfo->business_address :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Road Name :
                                                <span> {{ (!empty($appInfo->business_road)) ? $appInfo->business_road :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Ward / Market :
                                                <span>{{ (!empty($appInfo->business_ward_value)) ? $appInfo->business_ward_value :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td width="50%" style="padding: 5px;">
                                                Zone / Market Branch :
                                                <span> {{ (!empty($appInfo->business_zone_value)) ? $appInfo->business_zone_value :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Name of Market :
                                                <span>{{ (!empty($appInfo->business_market_name)) ? $appInfo->business_market_name :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Business Area :
                                                <span> {{ (!empty($appInfo->business_area_value)) ? $appInfo->business_area_value :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Shop No :
                                                <span>{{ (!empty($appInfo->business_shop)) ? $appInfo->business_shop :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Floor No :
                                                <span> {{ (!empty($appInfo->business_floor)) ? $appInfo->business_floor :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Nature of Business :

                                                <span>{{ (!empty($appInfo->business_nature)) ? $businessNature[$appInfo->business_nature] :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td width="50%" style="padding: 5px;">
                                                Date of Business Start :
                                                <span> {{ (!empty($appInfo->business_start_date)) ? date('d-M-Y', strtotime($appInfo->business_start_date)) :'N/A'  }}</span>

                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Business Sub-category :
                                                <span>{{ (!empty($appInfo->business_sub_category_value)) ? $appInfo->business_sub_category_value :'N/A'  }}</span>
                                            </td>


                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Business Category :
                                                <span> {{ (!empty($appInfo->business_category_value)) ? $appInfo->business_category_value :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">

                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Signboard (Feet) :

                                                <span> &nbsp;&nbsp;&nbsp;&nbsp;Height : {{ (!empty($appInfo->business_signboard_height)) ? $appInfo->business_signboard_height :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;Width :
                                                <span>{{ (!empty($appInfo->business_signboard_width)) ? $appInfo->business_signboard_width :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Factory :
                                                <span> {{ (!empty($appInfo->business_factory)) ? $appInfo->business_factory :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Chemical / Explosive :
                                                <span>{{ (!empty($appInfo->business_chemical)) ? $appInfo->business_chemical :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td width="50%" style="padding: 5px;">
                                                Plot Type :
                                                <span> {{ (!empty($appInfo->business_plot_type)) ? $plotType[$appInfo->business_plot_type] :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Plot Category :
                                                <span>{{ (!empty($appInfo->business_plot_category)) ? $plotCategory[$appInfo->business_plot_category] :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="50%" style="padding: 5px;">
                                                Place of Business :
                                                <span> {{ (!empty($appInfo->business_place)) ? $placeOfBusiness[$appInfo->business_place] :'N/A'  }}</span>
                                            </td>
                                            <td width="50%" style="padding: 5px;">
                                                Type of Activity :
                                                <span>{{ (!empty($appInfo->business_activity_type)) ? $typeOfActivity[$appInfo->business_activity_type] :'N/A'  }}</span>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">Necessary documents to be attached here (Only PDF file to be
                                attach here)
                            </div>
                            <div class="panel-body">

                                <div id="ep_form" class="panel panel-info">

                                    <div class="panel-body">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-hover ">
                                                <thead>
                                                <tr>
                                                    <th style="padding: 5px;">No.</th>
                                                    <th colspan="6" style="padding: 5px;">Required attachments</th>
                                                    <th colspan="2" style="padding: 5px;">Attached PDF file (Each File
                                                        Maximum
                                                        size 2MB)
                                                        <span>
                                                        <i title="Attached PDF file (Each File Maximum size 2MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>
                                                        </span>
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
                                                        <td colspan="6"
                                                            style="padding: 5px;">{!!  $row->doc_name !!}</td>
                                                        <td colspan="2" style="padding: 5px;">
                                                            @if(!empty($clrDocuments[$row->id]['doc_file_path']))

                                                                <div class="save_file">
                                                                    <a target="_blank" title=""
                                                                       href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['doc_file_path']) ?
                                                            $clrDocuments[$row->id]['doc_file_path'] : ''))}}"> <img
                                                                                width="10" height="10"
                                                                                src="assets/images/pdf.png" alt="pdf"/>
                                                                        Open
                                                                        File
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
