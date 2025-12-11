@if($ms == 1)
    <style type="text/css">
        body {
            font-family: "Times New Roman", serif;
            font-size: 8pt;
            margin:0px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
            font-weight: normal;
        }
    </style>
@endif
<div style="text-align: center" class="text-center">
    <h3>Bangladesh Investment Development Authority (BIDA)</h3>
    <h4>{{$board_meeting_data->meting_number}}<sup>th</sup> Inter-ministerial meeting minutes</h4><br>

</div>


<div class="row"><br></div>
<div class="row"><br></div>

<table width="100%" cellspacing="0" style=";" aria-label="Detailed Report Data Table">
    <thead>
    <tr>
        <th style="">Sl No.</th>
        <th style="">Description</th>
        <th style="">New</th>
        <th style="">Extension</th>
        <th style="">Amendment</th>
        <th style="">Cancellation</th>
    </tr>
    </thead>
    <tbody>



    <?php
    $i        = 1;
    $totalApp = 0;
    ?>
    <tr>
        <td style="">1</td>
        <td style="">Branch Office</td>
        <td style="">{{$branchNew}}</td>
        <td style="">{{$branchExt}}</td>
        <td style="">{{$branchAme}}</td>
        <td style="">{{$branchCan}}</td>
    </tr>

    <tr>
        <td style="">2</td>
        <td style="">Liaison office</td>
        <td style="">{{$liaison_officeNew}}</td>
        <td style="">{{$liaison_officeExt}}</td>
        <td style="">{{$liaison_officeAme}}</td>
        <td style="">{{$liaison_officeCan}}</td>
    </tr>

    <tr>
        <td style="">3</td>
        <td style="">Representative office</td>
        <td style="">{{$representative_officeNew}}</td>
        <td style="">{{$representative_officeExt}}</td>
        <td style="">{{$representative_officeAme}}</td>
        <td style="">{{$representative_officeCan}}</td>
    </tr>

    <tr>
        <td style="">4</td>
        <td style="">Work Permit</td>
        <td style="">{{$wpAppNew->New}}</td>
        <td style="">{{$wpAppNew->Extension}}</td>
        <td style="">{{$wpAppNew->Amendment}}</td>
        <td style="">{{$wpAppNew->Cancellation}}</td>
    </tr>

    <tr>
        <td style="">5</td>
        <td style="">Project Office</td>
        <td style="">{{ isset($projectOfficeApp->New) ? $projectOfficeApp->New : 0}}</td>
        <td style="">0</td>
        <td style="">0</td>
        <td style="">0</td>
    </tr>

    </tbody>
    <tfoot>
    <tr>
        <td @if($ms == 1) colspan="4"  style="font-weight:bold;border: none;" @else style="font-weight:bold;" @endif><strong>Total Application</strong> </td>
        @if($ms != 1)
            <td></td>
            <td></td>
            <td></td>
        @endif
        <td @if($ms == 1) colspan="2" style="font-weight:bold;border: none;" @else style="font-weight:bold;" @endif><strong>{{$countAllApplication}}</strong></td>
        @if($ms != 1)
            <td></td>
        @endif
    </tr>
    </tfoot>


</table>


    <div class="row"><br></div>
<pagebreak></pagebreak>
@foreach($arrayData as $key=> $dataRow)
    <?php

    $getJsonObjectData = $dataRow->table_heading_json_format;
    $getTableHeadingName = json_decode($getJsonObjectData, TRUE);
//    dump($getTableHeadingName);
    $totalRow = array_keys($getTableHeadingName);

    if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'A'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table  cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
//        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
//                dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                AC: {{$rowData->authorized_capital}} US$ PC: {{$rowData->paid_up_capital}} US$

            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 2 type A

    if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'B'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
//                dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                {{$rowData->first_commencement_date}}
            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 2 type B

    if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'C'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //                dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>


                        <td style="">
                            Name of the Local company: {{$rowData->local_company_name}}<br><br>
                            House, Flat/ Apartment, Road: {{$rowData->office_address}} <br>
                            Post Office: {{$rowData->office_post_office}}, Post Code: {{$rowData->office_post_code}}<br>
                            Police Station: {{$thana[$rowData->office_thana_id]}}, District: {{$districts[$rowData->office_district_id]}}, Division: {{$divisions[$rowData->office_division_id]}} <br>
                            Mobile No.: {{$rowData->office_mobile_no}}<br>
                            Email:{{$rowData->office_email}} <br>
                            Telephone No.:{{!empty($rowData->office_telephone_no)?$rowData->office_telephone_no:''}}<br>
                            Fax No.:{{!empty($rowData->office_fax_no)?$rowData->office_fax_no:''}}<br><br>
                            Activities in Bangladesh: {{$rowData->activities_in_bd}}
                        </td>
                        <td style="">
                            @if(!empty($rowData->n_local_company_name))
                            Name of the Local company: {{$rowData->n_local_company_name}}<br><br>
                            @endif
                            @if(!empty($rowData->n_office_address))
                            House, Flat/ Apartment, Road: {{$rowData->n_office_address}} <br>
                            @endif
                            @if(!empty($rowData->n_office_post_office))
                            Post Office: {{$rowData->n_office_post_office}}<br>
                            @endif
                            @if(!empty($rowData->n_office_post_code))
                            Post Code: {{$rowData->n_office_post_code}}<br>
                            @endif
                            @if(!empty($rowData->n_office_thana_id))
                            Police Station: {{$thana[$rowData->n_office_thana_id]}}<br>
                            @endif
                            @if(!empty($rowData->n_office_district_id))
                            District: {{$districts[$rowData->n_office_district_id]}}<br>
                            @endif
                            @if(!empty($rowData->n_office_division_id))
                            Division: {{$divisions[$rowData->n_office_division_id]}} <br>
                            @endif
                            @if(!empty($rowData->n_office_mobile_no))
                            Mobile No.: {{$rowData->n_office_mobile_no}}<br>
                            @endif
                            @if(!empty($rowData->n_office_email))
                            Email:{{$rowData->n_office_email}} <br>
                            @endif
                            @if(!empty($rowData->n_office_telephone_no))
                            Telephone No.:{{$rowData->n_office_telephone_no}}<br>
                            @endif
                            @if(!empty($rowData->n_office_fax_no))
                            Fax No.:{{$rowData->n_office_fax_no}}<br><br>
                            @endif
                            @if(!empty($rowData->n_activities_in_bd))
                            Activities in Bangladesh: {{$rowData->n_activities_in_bd}}
                            @endif

                        </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 2 type C


    if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'D'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
//                        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>


        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->applicant_remarks}}
            </td>
            <td style="">
                Date will be given by the Chairperson
            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 2 type D



    if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'A'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        //        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //                dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                AC: {{$rowData->authorized_capital}} US$ PC: {{$rowData->paid_up_capital}} US$

            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 3 type A

    if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'B'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //                dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        //        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>


        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                {{$rowData->first_commencement_date}}
            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 3 type B

    if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'C'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        <thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //                dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>


                        <td style="">
                            Name of the Local company: {{$rowData->local_company_name}}<br><br>
                            House, Flat/ Apartment, Road: {{$rowData->office_address}} <br>
                            Post Office: {{$rowData->office_post_office}}, Post Code: {{$rowData->office_post_code}}<br>
                            Police Station: {{$thana[$rowData->office_thana_id]}}, District: {{$districts[$rowData->office_district_id]}}, Division: {{$divisions[$rowData->office_division_id]}} <br>
                            Mobile No.: {{$rowData->office_mobile_no}}<br>
                            Email:{{$rowData->office_email}} <br>
                            Telephone No.:{{!empty($rowData->office_telephone_no)?$rowData->office_telephone_no:''}}<br>
                            Fax No.:{{!empty($rowData->office_fax_no)?$rowData->office_fax_no:''}}<br><br>
                            Activities in Bangladesh: {{$rowData->activities_in_bd}}
                        </td>
                        <td style="">
                            @if(!empty($rowData->n_local_company_name))
                            Name of the Local company: {{$rowData->n_local_company_name}}<br><br>
                            @endif
                            @if(!empty($rowData->n_office_address))
                            House, Flat/ Apartment, Road: {{$rowData->n_office_address}} <br>
                            @endif
                            @if(!empty($rowData->n_office_post_office))
                            Post Office: {{$rowData->n_office_post_office}}<br>
                            @endif
                            @if(!empty($rowData->n_office_post_code))
                            Post Code: {{$rowData->n_office_post_code}}<br>
                            @endif
                            @if(!empty($rowData->n_office_thana_id))
                            Police Station: {{$thana[$rowData->n_office_thana_id]}}<br>
                            @endif
                            @if(!empty($rowData->n_office_district_id))
                            District: {{$districts[$rowData->n_office_district_id]}}<br>
                            @endif
                            @if(!empty($rowData->n_office_division_id))
                            Division: {{$divisions[$rowData->n_office_division_id]}} <br>
                            @endif
                            @if(!empty($rowData->n_office_mobile_no))
                            Mobile No.: {{$rowData->n_office_mobile_no}}<br>
                            @endif
                            @if(!empty($rowData->n_office_email))
                            Email:{{$rowData->n_office_email}} <br>
                            @endif
                            @if(!empty($rowData->n_office_telephone_no))
                            Telephone No.:{{$rowData->n_office_telephone_no}}<br>
                            @endif
                            @if(!empty($rowData->n_office_fax_no))
                            Fax No.:{{$rowData->n_office_fax_no}}<br><br>
                            @endif
                            @if(!empty($rowData->n_activities_in_bd))
                            Activities in Bangladesh: {{$rowData->n_activities_in_bd}}
                            @endif

                        </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 2 type C


    if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'D'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        <thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //                        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->applicant_remarks}}
            </td>
            <td style="">
                Date will be given by the Chairperson
            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 4 type D



    if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'A'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        <thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        //        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //                dd($getApplicationData);
        ?>


        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                AC: {{$rowData->authorized_capital}} US$ PC: {{$rowData->paid_up_capital}} US$

            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 4 type A

    if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'B'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        <thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //                dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        //        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                {{$rowData->first_commencement_date}}
            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 4 type B

    if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'C'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        <thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //                dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>


                        <td style="">
                            Name of the Local company: {{$rowData->local_company_name}}<br><br>
                            House, Flat/ Apartment, Road: {{$rowData->office_address}} <br>
                            Post Office: {{$rowData->office_post_office}}, Post Code: {{$rowData->office_post_code}}<br>
                            Police Station: {{$thana[$rowData->office_thana_id]}}, District: {{$districts[$rowData->office_district_id]}}, Division: {{$divisions[$rowData->office_division_id]}} <br>
                            Mobile No.: {{$rowData->office_mobile_no}}<br>
                            Email:{{$rowData->office_email}} <br>
                            Telephone No.:{{!empty($rowData->office_telephone_no)?$rowData->office_telephone_no:''}}<br>
                            Fax No.:{{!empty($rowData->office_fax_no)?$rowData->office_fax_no:''}}<br><br>
                            Activities in Bangladesh: {{$rowData->activities_in_bd}}
                        </td>
                        <td style="">
                            @if(!empty($rowData->n_local_company_name))
                            Name of the Local company: {{$rowData->n_local_company_name}}<br><br>
                            @endif
                            @if(!empty($rowData->n_office_address))
                            House, Flat/ Apartment, Road: {{$rowData->n_office_address}} <br>
                            @endif
                            @if(!empty($rowData->n_office_post_office))
                            Post Office: {{$rowData->n_office_post_office}}<br>
                            @endif
                            @if(!empty($rowData->n_office_post_code))
                            Post Code: {{$rowData->n_office_post_code}}<br>
                            @endif
                            @if(!empty($rowData->n_office_thana_id))
                            Police Station: {{$thana[$rowData->n_office_thana_id]}}<br>
                            @endif
                            @if(!empty($rowData->n_office_district_id))
                            District: {{$districts[$rowData->n_office_district_id]}}<br>
                            @endif
                            @if(!empty($rowData->n_office_division_id))
                            Division: {{$divisions[$rowData->n_office_division_id]}} <br>
                            @endif
                            @if(!empty($rowData->n_office_mobile_no))
                            Mobile No.: {{$rowData->n_office_mobile_no}}<br>
                            @endif
                            @if(!empty($rowData->n_office_email))
                            Email:{{$rowData->n_office_email}} <br>
                            @endif
                            @if(!empty($rowData->n_office_telephone_no))
                            Telephone No.:{{$rowData->n_office_telephone_no}}<br>
                            @endif
                            @if(!empty($rowData->n_office_fax_no))
                            Fax No.:{{$rowData->n_office_fax_no}}<br><br>
                            @endif
                            @if(!empty($rowData->n_activities_in_bd))
                            Activities in Bangladesh: {{$rowData->n_activities_in_bd}}
                            @endif

                        </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 4 type C


    if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'D'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        <thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //                        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
//        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        //        dd($getApplicationData);
        ?>

        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{$rowData->local_company_name}}<br>
                {{$rowData->office_address}},
                {{$rowData->office_post_office}},
                {{$rowData->office_post_code}},
                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}}<br>
                Country of origin:
                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>

            <td style="">
                {{$rowData->applicant_remarks}}
            </td>
            <td style="">
                Date will be given by the Chairperson
            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }
    // agenda 4 type D




    //***************************************************
    if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'A'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>

        </tr>
        <thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
//        dd($getApplicationData);
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            <td style="">
                {{isset($getAgendaWiseApplicationWiseBasicInfo->company_name)?$getAgendaWiseApplicationWiseBasicInfo->company_name:''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_address)?$getAgendaWiseApplicationWiseBasicInfo->office_address.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_thana_id) && isset($thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]))
                    {{$thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_code)?$getAgendaWiseApplicationWiseBasicInfo->office_post_code.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_office)?$getAgendaWiseApplicationWiseBasicInfo->office_post_office.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_district_id) && isset($districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]))
                    {{$districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]}},
                @endif
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_division_id) && isset($divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]))
                    {{$divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_mobile_no)?$getAgendaWiseApplicationWiseBasicInfo->office_mobile_no.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_email)?$getAgendaWiseApplicationWiseBasicInfo->office_email.',' :''}}

                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id) && isset($countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]))
                    {{$countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]}},
                @endif

                {{isset($getAgendaWiseApplicationWiseBasicInfo->major_activities)?$getAgendaWiseApplicationWiseBasicInfo->major_activities:''}}
                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{$rowData->emp_name}}<br> {{$rowData->emp_designation}} <br>{{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}}  <br> {{$rowData->emp_passport_no}}</td>
            <td style="">
                @if(!empty($rowData->basic_local_amount) && $rowData->basic_local_amount != 0)
                    Basic salary{{isset($paymentType[$rowData->basic_payment_type_id])?'('.$paymentType[$rowData->basic_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->basic_local_currency_id])?$currencies[$rowData->basic_local_currency_id]:'BDT'}} {{$rowData->basic_local_amount}}<br>
                @endif
                @if(!empty($rowData->house_local_amount) && $rowData->house_local_amount != 0)
                    House Accommodation{{isset($paymentType[$rowData->house_payment_type_id])?'('.$paymentType[$rowData->house_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->house_local_currency_id])?$currencies[$rowData->house_local_currency_id]:'BDT'}} {{$rowData->house_local_amount}}<br>
                @endif
                @if(!empty($rowData->conveyance_local_amount) && $rowData->conveyance_local_amount != 0)
                    Conveyance arrangement{{isset($paymentType[$rowData->conveyance_payment_type_id])?'('.$paymentType[$rowData->conveyance_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->conveyance_local_currency_id])?$currencies[$rowData->conveyance_local_currency_id]:'BDT'}} {{$rowData->conveyance_local_amount}}<br>
                @endif
                @if(!empty($rowData->ent_local_amount) && $rowData->ent_local_amount != 0)
                    Entertainment{{isset($paymentType[$rowData->ent_payment_type_id])?'('.$paymentType[$rowData->ent_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->ent_local_currency_id])?$currencies[$rowData->ent_local_currency_id]:'BDT'}} {{$rowData->ent_local_amount}}
                @endif
                @if(!empty($rowData->overseas_local_amount) && $rowData->overseas_local_amount != 0)
                    Overseas allowance{{isset($paymentType[$rowData->overseas_payment_type_id])?'('.$paymentType[$rowData->overseas_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->overseas_local_currency_id])?$currencies[$rowData->overseas_local_currency_id]:'BDT'}} {{$rowData->overseas_local_amount}}<br>
                @endif
                @if(!empty($rowData->medical_local_amount) && $rowData->medical_local_amount != 0)
                    Medical allowance{{isset($paymentType[$rowData->medical_payment_type_id])?'('.$paymentType[$rowData->medical_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->medical_local_currency_id])?$currencies[$rowData->medical_local_currency_id]:'BDT'}} {{$rowData->medical_local_amount}}<br>
                @endif
                @if(!empty($rowData->bonus_local_amount) && $rowData->bonus_local_amount != 0)
                    Annual Bonus{{isset($paymentType[$rowData->bonus_payment_type_id])?'('.$paymentType[$rowData->bonus_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->bonus_local_currency_id])?$currencies[$rowData->bonus_local_currency_id]:'BDT'}} {{$rowData->bonus_local_amount}}
                @endif

            </td>
              <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <?php
            if(isset($getTableHeadingName[6])){
                if($getTableHeadingName[6] == 'Date of First Appointment'){?>
                <td style="">{{$rowData->issue_date_of_first_wp}}</td>
            <?php }}?>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
            {{--<td style="">w</td>--}}
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }

    if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'B'){
    ?>
    {{--{{dd('555')}}--}}
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>

            <th style="">{{$getTableHeadingName[6]}}</th>

            <th style="">{{$getTableHeadingName[7]}}</th>

            <th style="">{{$getTableHeadingName[8]}}</th>


        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);

        ?>



        <tr>
            <td style="">{{$i++}}</td>

            <td style="">
                {{isset($getAgendaWiseApplicationWiseBasicInfo->company_name)?$getAgendaWiseApplicationWiseBasicInfo->company_name:''}}<br>
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_address)?$getAgendaWiseApplicationWiseBasicInfo->office_address.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_thana_id) && isset($thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]))
                    {{$thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_code)?$getAgendaWiseApplicationWiseBasicInfo->office_post_code.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_office)?$getAgendaWiseApplicationWiseBasicInfo->office_post_office.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_district_id) && isset($districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]))
                    {{$districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]}},
                @endif
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_division_id) && isset($divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]))
                    {{$divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_mobile_no)?$getAgendaWiseApplicationWiseBasicInfo->office_mobile_no.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_email)?$getAgendaWiseApplicationWiseBasicInfo->office_email.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id) && isset($countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]))
                    {{$countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]}},
                @endif

                {{isset($getAgendaWiseApplicationWiseBasicInfo->major_activities)?$getAgendaWiseApplicationWiseBasicInfo->major_activities:''}}
                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{$rowData->emp_name}}<br> {{$rowData->emp_designation}}<br>{{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}} <br>{{$rowData->emp_passport_no}}</td>
            <td style="">

                @if(!empty($rowData->basic_local_amount) && $rowData->basic_local_amount != 0)
                    Basic salary{{isset($paymentType[$rowData->basic_payment_type_id])?'('.$paymentType[$rowData->basic_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->basic_local_currency_id])?$currencies[$rowData->basic_local_currency_id]:'BDT'}} {{$rowData->basic_local_amount}}<br>
                @endif
                @if(!empty($rowData->house_local_amount) && $rowData->house_local_amount != 0)
                    House Accommodation{{isset($paymentType[$rowData->house_payment_type_id])?'('.$paymentType[$rowData->house_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->house_local_currency_id])?$currencies[$rowData->house_local_currency_id]:'BDT'}} {{$rowData->house_local_amount}}<br>
                @endif
                @if(!empty($rowData->conveyance_local_amount) && $rowData->conveyance_local_amount != 0)
                    Conveyance arrangement{{isset($paymentType[$rowData->conveyance_payment_type_id])?'('.$paymentType[$rowData->conveyance_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->conveyance_local_currency_id])?$currencies[$rowData->conveyance_local_currency_id]:'BDT'}} {{$rowData->conveyance_local_amount}}<br>
                @endif
                @if(!empty($rowData->ent_local_amount) && $rowData->ent_local_amount != 0)
                    Entertainment{{isset($paymentType[$rowData->ent_payment_type_id])?'('.$paymentType[$rowData->ent_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->ent_local_currency_id])?$currencies[$rowData->ent_local_currency_id]:'BDT'}} {{$rowData->ent_local_amount}}
                @endif
                @if(!empty($rowData->overseas_local_amount) && $rowData->overseas_local_amount != 0)
                    Overseas allowance{{isset($paymentType[$rowData->overseas_payment_type_id])?'('.$paymentType[$rowData->overseas_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->overseas_local_currency_id])?$currencies[$rowData->overseas_local_currency_id]:'BDT'}} {{$rowData->overseas_local_amount}}<br>
                @endif
                @if(!empty($rowData->medical_local_amount) && $rowData->medical_local_amount != 0)
                    Medical allowance{{isset($paymentType[$rowData->medical_payment_type_id])?'('.$paymentType[$rowData->medical_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->medical_local_currency_id])?$currencies[$rowData->medical_local_currency_id]:'BDT'}} {{$rowData->medical_local_amount}}<br>
                @endif
                @if(!empty($rowData->bonus_local_amount) && $rowData->bonus_local_amount != 0)
                    Annual Bonus{{isset($paymentType[$rowData->bonus_payment_type_id])?'('.$paymentType[$rowData->bonus_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->bonus_local_currency_id])?$currencies[$rowData->bonus_local_currency_id]:'BDT'}} {{$rowData->bonus_local_amount}}
                @endif


            </td>
            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>

            <?php
//            if(isset($getTableHeadingName[6])){
//            if($getTableHeadingName[6] == 'Date of First Appointment'){?>
            <td style="">{{isset($rowData->issue_date_of_first_wp)?$rowData->issue_date_of_first_wp:''}}</td>

<!--            --><?php //}}?>

            <td style="">{{isset($rowData->bm_status_name)?$rowData->bm_status_name:''}}</td>
            <td style="">{{isset($rowData->bm_remarks)?$rowData->bm_remarks:''}}</td>
            {{--<td style="">w</td>--}}

        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }

    if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'C'){
    ?>
        <div >
            <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>
            <th style="">{{$getTableHeadingName[8]}}</th>
            <th style="">{{$getTableHeadingName[9]}}</th>

        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        $getWpnInfo = \App\Libraries\CommonFunction::getAgendaWiseWorkPermitNew($rowData->ref_app_tracking_no,'wpa_apps');
        ?>



        <tr>
            <td style="">{{$i++}}</td>
            {{--<td style="">not found</td>--}}
            <td style="">
                {{isset($getAgendaWiseApplicationWiseBasicInfo->company_name)?$getAgendaWiseApplicationWiseBasicInfo->company_name:''}}<br>
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_address)?$getAgendaWiseApplicationWiseBasicInfo->office_address.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_thana_id) && isset($thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]))
                    {{$thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_code)?$getAgendaWiseApplicationWiseBasicInfo->office_post_code.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_office)?$getAgendaWiseApplicationWiseBasicInfo->office_post_office.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_district_id) && isset($districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]))
                    {{$districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]}},
                @endif
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_division_id) && isset($divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]))
                    {{$divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_mobile_no)?$getAgendaWiseApplicationWiseBasicInfo->office_mobile_no.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_email)?$getAgendaWiseApplicationWiseBasicInfo->office_email.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id) && isset($countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]))
                    {{$countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->major_activities)?$getAgendaWiseApplicationWiseBasicInfo->major_activities:''}}
                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{$rowData->emp_name}}, {{$rowData->emp_designation}}, {{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}}, {{$rowData->emp_passport_no}}</td>
            <td style="">{{isset($WP_visaTypes[$rowData->work_permit_type])?$WP_visaTypes[$rowData->work_permit_type]->type :''}}</td>
            <td style="">


                @if(!empty($getWpnInfo->basic_local_amount) && $getWpnInfo->basic_local_amount != 0)
                    Basic salary{{isset($paymentType[$getWpnInfo->basic_payment_type_id])?'('.$paymentType[$getWpnInfo->basic_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->basic_local_currency_id])?$currencies[$getWpnInfo->medical_local_currency_id]:'BDT'}} {{isset($getWpnInfo->basic_local_amount)?"Basic salary: BDT ". $getWpnInfo->basic_local_amount :''}}<br>
                @endif

                @if(!empty($getWpnInfo->house_local_amount) && $getWpnInfo->house_local_amount != 0)
                    House Accommodation{{isset($paymentType[$getWpnInfo->house_payment_type_id])?'('.$paymentType[$getWpnInfo->house_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->house_local_currency_id])?$currencies[$getWpnInfo->house_local_currency_id]:'BDT'}} {{isset($getWpnInfo->house_local_amount)?$getWpnInfo->house_local_amount :''}}<br>
                @endif

                @if(!empty($getWpnInfo->conveyance_local_amount) && $getWpnInfo->conveyance_local_amount != 0)
                    Conveyance arrangement{{isset($paymentType[$getWpnInfo->conveyance_payment_type_id])?'('.$paymentType[$getWpnInfo->conveyance_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->conveyance_local_currency_id])?$currencies[$getWpnInfo->conveyance_local_currency_id]:'BDT'}} {{isset($getWpnInfo->conveyance_local_amount)?$getWpnInfo->conveyance_local_amount :''}}<br>
                @endif

                @if(!empty($getWpnInfo->ent_local_amount) && $getWpnInfo->ent_local_amount != 0)
                    Entertainment{{isset($paymentType[$getWpnInfo->ent_payment_type_id])?'('.$paymentType[$getWpnInfo->ent_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->ent_local_currency_id])?$currencies[$getWpnInfo->ent_local_currency_id]:'BDT'}} {{isset($getWpnInfo->ent_local_amount)?$getWpnInfo->ent_local_amount :''}}
                @endif

                @if(!empty($getWpnInfo->overseas_local_amount) && $getWpnInfo->overseas_local_amount != 0)
                    Overseas allowance{{isset($paymentType[$getWpnInfo->overseas_payment_type_id])?'('.$paymentType[$getWpnInfo->overseas_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->overseas_local_currency_id])?$currencies[$getWpnInfo->overseas_local_currency_id]:'BDT'}} {{isset($getWpnInfo->overseas_local_amount)?$getWpnInfo->overseas_local_amount :''}}
                @endif
                @if(!empty($getWpnInfo->medical_local_amount) && $getWpnInfo->medical_local_amount != 0)
                    Medical allowance{{isset($paymentType[$getWpnInfo->medical_payment_type_id])?'('.$paymentType[$getWpnInfo->medical_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->medical_local_currency_id])?$currencies[$getWpnInfo->medical_local_currency_id]:'BDT'}} {{isset($getWpnInfo->medical_local_amount)?$getWpnInfo->medical_local_amount :''}}
                @endif
                @if(!empty($getWpnInfo->bonus_local_amount) && $getWpnInfo->bonus_local_amount != 0)
                    Annual Bonus{{isset($paymentType[$getWpnInfo->bonus_payment_type_id])?'('.$paymentType[$getWpnInfo->bonus_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->bonus_local_currency_id])?$currencies[$getWpnInfo->bonus_local_currency_id]:'BDT'}} {{isset($getWpnInfo->bonus_local_amount)?$getWpnInfo->bonus_local_amount :''}}
                @endif



            </td>
            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                @if(!empty($rowData->basic_local_amount) && $rowData->basic_local_amount != 0)
                    Basic salary{{isset($paymentType[$rowData->basic_payment_type_id])?'('.$paymentType[$rowData->basic_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->basic_local_currency_id])?$currencies[$rowData->basic_local_currency_id]:'BDT'}} {{$rowData->basic_local_amount}}<br>
                @endif
                @if(!empty($rowData->house_local_amount) && $rowData->house_local_amount != 0)
                    House Accommodation{{isset($paymentType[$rowData->house_payment_type_id])?'('.$paymentType[$rowData->house_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->house_local_currency_id])?$currencies[$rowData->house_local_currency_id]:'BDT'}} {{$rowData->house_local_amount}}<br>
                @endif
                @if(!empty($rowData->conveyance_local_amount) && $rowData->conveyance_local_amount != 0)
                    Conveyance arrangement{{isset($paymentType[$rowData->conveyance_payment_type_id])?'('.$paymentType[$rowData->conveyance_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->conveyance_local_currency_id])?$currencies[$rowData->conveyance_local_currency_id]:'BDT'}} {{$rowData->conveyance_local_amount}}<br>
                @endif
                @if(!empty($rowData->ent_local_amount) && $rowData->ent_local_amount != 0)
                    Entertainment{{isset($paymentType[$rowData->ent_payment_type_id])?'('.$paymentType[$rowData->ent_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->ent_local_currency_id])?$currencies[$rowData->ent_local_currency_id]:'BDT'}} {{$rowData->ent_local_amount}}
                @endif
                @if(!empty($rowData->overseas_local_amount) && $rowData->overseas_local_amount != 0)
                    Overseas allowance{{isset($paymentType[$rowData->overseas_payment_type_id])?'('.$paymentType[$rowData->overseas_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->overseas_local_currency_id])?$currencies[$rowData->overseas_local_currency_id]:'BDT'}} {{$rowData->overseas_local_amount}}<br>
                @endif
                @if(!empty($rowData->medical_local_amount) && $rowData->medical_local_amount != 0)
                    Medical allowance{{isset($paymentType[$rowData->medical_payment_type_id])?'('.$paymentType[$rowData->medical_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->medical_local_currency_id])?$currencies[$rowData->medical_local_currency_id]:'BDT'}} {{$rowData->medical_local_amount}}<br>
                @endif
                @if(!empty($rowData->bonus_local_amount) && $rowData->bonus_local_amount != 0)
                    Annual Bonus{{isset($paymentType[$rowData->bonus_payment_type_id])?'('.$paymentType[$rowData->bonus_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->bonus_local_currency_id])?$currencies[$rowData->bonus_local_currency_id]:'BDT'}} {{$rowData->bonus_local_amount}}
                @endif



            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
        </div>
    <?php }

    if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'D'){
    ?>
    <br style="page-break-before: always">
    <div style="text-align: center" class="text-center">
        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
    </div>
    <table cellspacing="0" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <?php
            $i = 0;
            ?>
            <th rowspan="1">{{$getTableHeadingName[1]}}</th>
            <th style="">{{$getTableHeadingName[2]}}</th>
            <th style="">{{$getTableHeadingName[3]}}</th>
            <th style="">{{$getTableHeadingName[4]}}</th>
            <th style="">{{$getTableHeadingName[5]}}</th>
            <th style="">{{$getTableHeadingName[6]}}</th>
            <th style="">{{$getTableHeadingName[7]}}</th>
            <th style="">{{$getTableHeadingName[8]}}</th>
            <th style="">{{$getTableHeadingName[9]}}</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationDataOfMeetingMinutes($dataRow->agenda_name,$dataRow->type,$meeting_id, $dataRow->process_type_id);
        //        dd($getApplicationData);
        $i = 1;
        foreach ($getApplicationData as $rowData){
        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
        $getWpnInfo = \App\Libraries\CommonFunction::getAgendaWiseWorkPermitNew($rowData->ref_app_tracking_no,'wpc_apps');

        ?>


        <tr>
            <td style="">{{$i++}}</td>
            <td style="">
                {{isset($getAgendaWiseApplicationWiseBasicInfo->company_name)?$getAgendaWiseApplicationWiseBasicInfo->company_name:''}}<br>
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_address)?$getAgendaWiseApplicationWiseBasicInfo->office_address.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_thana_id) && isset($thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]))
                    {{$thana[$getAgendaWiseApplicationWiseBasicInfo->office_thana_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_code)?$getAgendaWiseApplicationWiseBasicInfo->office_post_code.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_post_office)?$getAgendaWiseApplicationWiseBasicInfo->office_post_office.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_district_id) && isset($districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]))
                    {{$districts[$getAgendaWiseApplicationWiseBasicInfo->office_district_id]}},
                @endif
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->office_division_id) && isset($divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]))
                    {{$divisions[$getAgendaWiseApplicationWiseBasicInfo->office_division_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_mobile_no)?$getAgendaWiseApplicationWiseBasicInfo->office_mobile_no.',' :''}}
                {{isset($getAgendaWiseApplicationWiseBasicInfo->office_email)?$getAgendaWiseApplicationWiseBasicInfo->office_email.',' :''}}
                @if(!empty($getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id) && isset($countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]))
                    {{$countries[$getAgendaWiseApplicationWiseBasicInfo->country_of_origin_id]}},
                @endif
                {{isset($getAgendaWiseApplicationWiseBasicInfo->major_activities)?$getAgendaWiseApplicationWiseBasicInfo->major_activities:''}}
                (ID#{{$rowData->tracking_no}})

            </td>
            <td style="">{{$rowData->emp_name}}, {{$rowData->emp_designation}}, {{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}}, {{$rowData->emp_passport_no}}</td>
            <td style="">{{isset($WP_visaTypes[$rowData->work_permit_type])?$WP_visaTypes[$rowData->work_permit_type]->type :''}}</td>
            <td style="">

                @if(!empty($getWpnInfo->basic_local_amount) && $getWpnInfo->basic_local_amount != 0)
                    Basic salary{{isset($paymentType[$getWpnInfo->basic_payment_type_id])?'('.$paymentType[$getWpnInfo->basic_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->basic_local_currency_id])?$currencies[$getWpnInfo->medical_local_currency_id]:'BDT'}} {{isset($getWpnInfo->basic_local_amount)?"Basic salary: BDT ". $getWpnInfo->basic_local_amount :''}}<br>
                @endif

                @if(!empty($getWpnInfo->house_local_amount) && $getWpnInfo->house_local_amount != 0)
                    House Accommodation{{isset($paymentType[$getWpnInfo->house_payment_type_id])?'('.$paymentType[$getWpnInfo->house_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->house_local_currency_id])?$currencies[$getWpnInfo->house_local_currency_id]:'BDT'}} {{isset($getWpnInfo->house_local_amount)?$getWpnInfo->house_local_amount :''}}<br>
                @endif

                @if(!empty($getWpnInfo->conveyance_local_amount) && $getWpnInfo->conveyance_local_amount != 0)
                    Conveyance arrangement{{isset($paymentType[$getWpnInfo->conveyance_payment_type_id])?'('.$paymentType[$getWpnInfo->conveyance_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->conveyance_local_currency_id])?$currencies[$getWpnInfo->conveyance_local_currency_id]:'BDT'}} {{isset($getWpnInfo->conveyance_local_amount)?$getWpnInfo->conveyance_local_amount :''}}<br>
                @endif

                @if(!empty($getWpnInfo->ent_local_amount) && $getWpnInfo->ent_local_amount != 0)
                    Entertainment{{isset($paymentType[$getWpnInfo->ent_payment_type_id])?'('.$paymentType[$getWpnInfo->ent_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->ent_local_currency_id])?$currencies[$getWpnInfo->ent_local_currency_id]:'BDT'}} {{isset($getWpnInfo->ent_local_amount)?$getWpnInfo->ent_local_amount :''}}
                @endif

                @if(!empty($getWpnInfo->overseas_local_amount) && $getWpnInfo->overseas_local_amount != 0)
                    Overseas allowance{{isset($paymentType[$getWpnInfo->overseas_payment_type_id])?'('.$paymentType[$getWpnInfo->overseas_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->overseas_local_currency_id])?$currencies[$getWpnInfo->overseas_local_currency_id]:'BDT'}} {{isset($getWpnInfo->overseas_local_amount)?$getWpnInfo->overseas_local_amount :''}}
                @endif
                @if(!empty($getWpnInfo->medical_local_amount) && $getWpnInfo->medical_local_amount != 0)
                    Medical allowance{{isset($paymentType[$getWpnInfo->medical_payment_type_id])?'('.$paymentType[$getWpnInfo->medical_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->medical_local_currency_id])?$currencies[$getWpnInfo->medical_local_currency_id]:'BDT'}} {{isset($getWpnInfo->medical_local_amount)?$getWpnInfo->medical_local_amount :''}}
                @endif
                @if(!empty($getWpnInfo->bonus_local_amount) && $getWpnInfo->bonus_local_amount != 0)
                    Annual Bonus{{isset($paymentType[$getWpnInfo->bonus_payment_type_id])?'('.$paymentType[$getWpnInfo->bonus_payment_type_id].')':''}}:
                    {{isset($currencies[$getWpnInfo->bonus_local_currency_id])?$currencies[$getWpnInfo->bonus_local_currency_id]:'BDT'}} {{isset($getWpnInfo->bonus_local_amount)?$getWpnInfo->bonus_local_amount :''}}
                @endif
            </td>
            <td style="">
                {{$rowData->approved_desired_duration}}
                w.e.f.<br>
                {{$rowData->approved_duration_start_date}}
            </td>
            <td style="">
                @if(!empty($rowData->basic_local_amount) && $rowData->basic_local_amount != 0)
                    Basic salary{{isset($paymentType[$rowData->basic_payment_type_id])?'('.$paymentType[$rowData->basic_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->basic_local_currency_id])?$currencies[$rowData->basic_local_currency_id]:'BDT'}} {{$rowData->basic_local_amount}}<br>
                @endif
                @if(!empty($rowData->house_local_amount) && $rowData->house_local_amount != 0)
                    House Accommodation{{isset($paymentType[$rowData->house_payment_type_id])?'('.$paymentType[$rowData->house_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->house_local_currency_id])?$currencies[$rowData->house_local_currency_id]:'BDT'}} {{$rowData->house_local_amount}}<br>
                @endif
                @if(!empty($rowData->conveyance_local_amount) && $rowData->conveyance_local_amount != 0)
                    Conveyance arrangement{{isset($paymentType[$rowData->conveyance_payment_type_id])?'('.$paymentType[$rowData->conveyance_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->conveyance_local_currency_id])?$currencies[$rowData->conveyance_local_currency_id]:'BDT'}} {{$rowData->conveyance_local_amount}}<br>
                @endif
                @if(!empty($rowData->ent_local_amount) && $rowData->ent_local_amount != 0)
                    Entertainment{{isset($paymentType[$rowData->ent_payment_type_id])?'('.$paymentType[$rowData->ent_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->ent_local_currency_id])?$currencies[$rowData->ent_local_currency_id]:'BDT'}} {{$rowData->ent_local_amount}}
                @endif
                @if(!empty($rowData->overseas_local_amount) && $rowData->overseas_local_amount != 0)
                    Overseas allowance{{isset($paymentType[$rowData->overseas_payment_type_id])?'('.$paymentType[$rowData->overseas_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->overseas_local_currency_id])?$currencies[$rowData->overseas_local_currency_id]:'BDT'}} {{$rowData->overseas_local_amount}}<br>
                @endif
                @if(!empty($rowData->medical_local_amount) && $rowData->medical_local_amount != 0)
                    Medical allowance{{isset($paymentType[$rowData->medical_payment_type_id])?'('.$paymentType[$rowData->medical_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->medical_local_currency_id])?$currencies[$rowData->medical_local_currency_id]:'BDT'}} {{$rowData->medical_local_amount}}<br>
                @endif
                @if(!empty($rowData->bonus_local_amount) && $rowData->bonus_local_amount != 0)
                    Annual Bonus{{isset($paymentType[$rowData->bonus_payment_type_id])?'('.$paymentType[$rowData->bonus_payment_type_id].')':''}}:
                    {{isset($currencies[$rowData->bonus_local_currency_id])?$currencies[$rowData->bonus_local_currency_id]:'BDT'}} {{$rowData->bonus_local_amount}}
                @endif
            </td>

            <td style="">{{$rowData->bm_status_name}}</td>
            <td style="">{{$rowData->bm_remarks}}</td>
        </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
    <?php }?>

    <?php
    // agenda 1 type A
    if($dataRow->agenda_name == 'AGENDA 1' && $dataRow->type == 'A' &&  in_array($dataRow->process_type_id, [22, 23, 24, 25])) {
    ?>
        @include('BoardMeting::agenda.agenda-content-doc-a1-ta', ['dataRow' => $dataRow, 'getTableHeadingName' => $getTableHeadingName])
    <?php } ?>

@endforeach
