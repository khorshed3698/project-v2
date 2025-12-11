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
    <table aria-label="Detailed Report Data Table"> <tr> <th aria-hidden="true"  scope="col"> </th> </tr> <td colspan="6" style="text-align: center;"><h3>Bangladesh Investment Development Authority (BIDA) </h3></td></table>
    <table aria-label="Detailed Report Data Table"> <tr> <th aria-hidden="true"  scope="col"> </th> </tr> <td colspan="6"> <h4 style="text-align: center">{{$board_meeting_data->meting_number}}<sup>th</sup> Inter-ministerial meeting agenda</h4><br></td></table>
</div>


<div class="container">
    <div class="row"><br></div>
    <div class="row"><br></div>


    <table width="100%" aria-label="Detailed Report Data Table" >
        <thead>
        <tr>
            <th >Sl No</th>
            <th>Description</th>
            <th >New</th>
            <th>Extension</th>
            <th>Amendment</th>
            <th >Cancellation</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td >1</td>
            <td >Branch Office</td>
            <td >{{$branchNew}}</td>
            <td >{{$branchExt}}</td>
            <td >{{$branchAme}}</td>
            <td >{{$branchCan}}</td>
        </tr>

        <tr>
            <td >2</td>
            <td >Liaison office</td>
            <td >{{$liaison_officeNew}}</td>
            <td >{{$liaison_officeExt}}</td>
            <td >{{$liaison_officeAme}}</td>
            <td >{{$liaison_officeCan}}</td>
        </tr>

        <tr>
            <td >3</td>
            <td >Representative office</td>
            <td >{{$representative_officeNew}}</td>
            <td >{{$representative_officeExt}}</td>
            <td >{{$representative_officeAme}}</td>
            <td >{{$representative_officeCan}}</td>
        </tr>

        <tr>
            <td >4</td>
            <td >Work Permit</td>
            <td >{{$wpAppNew->New}}</td>
            <td >{{$wpAppNew->Extension}}</td>
            <td >{{$wpAppNew->Amendment}}</td>
            <td >{{$wpAppNew->Cancellation}}</td>
        </tr>

        <tr>
            <td >5</td>
            <td >Project Office</td>
            <td >{{ isset($projectOfficeApp->New)?$projectOfficeApp->New:0}}</td>
            <td >0</td>
            <td >0</td>
            <td >0</td>
        </tr>

        </tbody>
        <tfoot >
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
    <div class="row"><br></div>
    <pagebreak></pagebreak>
        @foreach($arrayData as $key=> $dataRow)
            <?php
            $getJsonObjectData   = $dataRow->table_heading_json_format;
            $getTableHeadingName = json_decode($getJsonObjectData, TRUE);
            $totalRow = array_keys($getTableHeadingName);
            ?>

            @if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'A')
                <div>
                    <br style="page-break-before: always">
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table class="table-tes-meeting" aria-label="Detailed Report Data Table" >
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="31%">{{$getTableHeadingName[2]}}</th>
                            <th width="23%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th >{{$getTableHeadingName[6]}}</th> --}}
                            {{-- <th  style="width: 150px;">{{$getTableHeadingName[7]}}</th> --}}
                            {{-- <th >{{$getTableHeadingName[8]}}</th> --}}
                            <th width="23%">{{$getTableHeadingName[9]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        //                    dd($getApplicationData);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td >{{$i++}}</td>
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
                            <td >{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}<br></td>
                            <td >
                                {{$rowData->approved_desired_duration}}
                                w.e.f.<br>
                                {{$rowData->approved_duration_start_date}}
                            </td>
                            <td >
                                AC: {{$rowData->authorized_capital}} US$ PC: {{$rowData->paid_up_capital}} US$
                            </td>
                            {{-- <td >EIE US$ {{$rowData->est_initial_expenses}} EME US$ {{$rowData->est_monthly_expenses}}</td> --}}
                            {{-- <td  style="" @if($ms == 0)style=""@endif > --}}
                            <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?> 
                            {{-- </td> --}}
                            {{-- <td >{{$rowData->manpower_foreign_ratio}}:{{$rowData->manpower_local_ratio}}</td> --}}
                            <td >{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>

                        <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>

            @endif {{-- for Agenga 2 and type A --}}

            @if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'B')
                <div>
                    <br style="page-break-before: always">
                    <br>
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="31%">{{$getTableHeadingName[2]}}</th>
                            <th width="23%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th >{{$getTableHeadingName[6]}}</th> --}}
                            {{-- <th style="width: 150px;">{{$getTableHeadingName[7]}}</th> --}}
                            {{-- <th >{{$getTableHeadingName[8]}}</th> --}}
                            {{-- <th  >{{$getTableHeadingName[9]}}</th> --}}
                            <th width="23%">{{$getTableHeadingName[10]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);

                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td>
                                {{$rowData->approved_desired_duration}}
                                w.e.f.<br>
                                {{$rowData->approved_duration_start_date}}
                            </td>
                            <td> {{$rowData->first_commencement_date}} </td>
                            {{-- <td>EIE US$ {{$rowData->est_initial_expenses}} EME US$ {{$rowData->est_monthly_expenses}}</td> --}}
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                                {{-- </td> --}}
                            {{-- <td>{{$rowData->inward_remittance}}</td> --}}
                            {{-- <td>{{$rowData->manpower_foreign_ratio}}:{{$rowData->manpower_local_ratio}}</td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 2 and type B --}}

            @if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'C')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>

                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="25%">{{$getTableHeadingName[2]}}</th>
                            <th width="20%">{{$getTableHeadingName[3]}}</th>
                            <th width="20%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th  style="width: 150px;">{{$getTableHeadingName[6]}}</th> --}}
                            <th width="22%">{{$getTableHeadingName[7]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        //                    $getOpaInfo = \App\Libraries\CommonFunction::getAgendaWiseWorkPermitNew($rowData->ref_app_tracking_no,'opa_apps');
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},

                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},

                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td>
                                Name of the Local company: {{$rowData->local_company_name}}<br><br>
                                House, Flat/ Apartment, Road: {{$rowData->office_address}} <br>
                                Police Station: {{$thana[$rowData->office_thana_id]}}, District: {{$districts[$rowData->office_district_id]}}, Division: {{$divisions[$rowData->office_division_id]}} <br>
                                Post Office: {{$rowData->office_post_office}}, Post Code: {{$rowData->office_post_code}}<br>
                                {{$rowData->office_thana_id}}

                                Mobile No.: {{$rowData->office_mobile_no}}<br>

                                Email:{{$rowData->office_email}} <br>
                                Telephone No.:{{!empty($rowData->office_telephone_no)?$rowData->office_telephone_no:''}}<br>
                                Fax No.:{{!empty($rowData->office_fax_no)?$rowData->office_fax_no:''}}<br><br>
                                Activities in Bangladesh: {{$rowData->activities_in_bd}}
                            </td>
                            <td>
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
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}

                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}} </td>
                        </tr>

                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 2 and type C --}}

            @if($dataRow->agenda_name == 'AGENDA 2' && $dataRow->type == 'D')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="25%">{{$getTableHeadingName[2]}}</th>
                            <th width="20%">{{$getTableHeadingName[3]}}</th>
                            <th width="20%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th width="10%">{{$getTableHeadingName[6]}}</th> --}}
                            <th width="22%">{{$getTableHeadingName[7]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td>{{$rowData->applicant_remarks}}</td>
                            <td>Date will be given by the Chairperson</td>
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            @endif {{-- for Agenga 2 and type D --}}

            @if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'A')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="31%">{{$getTableHeadingName[2]}}</th>
                            <th width="23%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th style="">{{$getTableHeadingName[6]}}</th> --}}
                            {{-- <th  style="width: 150px;">{{$getTableHeadingName[7]}}</th> --}}
                            {{-- <th style="">{{$getTableHeadingName[8]}}</th> --}}
                            <th width="23%">{{$getTableHeadingName[9]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);

                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}<br></td>

                            <td>
                                {{$rowData->approved_desired_duration}}
                                w.e.f.<br>
                                {{$rowData->approved_duration_start_date}}
                            </td>
                            <td>
                                AC: {{$rowData->authorized_capital}} US$ PC: {{$rowData->paid_up_capital}} US$
                            </td>
                            {{-- <td>EIE US$ {{$rowData->est_initial_expenses}} EME US$ {{$rowData->est_monthly_expenses}}</td> --}}
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            {{-- <td>{{$rowData->manpower_foreign_ratio}}:{{$rowData->manpower_local_ratio}}</td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>

                        </tr>

                        <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenda 3 and type A --}}

            @if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'B')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="31%">{{$getTableHeadingName[2]}}</th>
                            <th width="23%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th style="">{{$getTableHeadingName[6]}}</th> --}}
                            {{-- <th style="width: 150px;">{{$getTableHeadingName[7]}}</th> --}}
                            {{-- <th style="">{{$getTableHeadingName[8]}}</th> --}}
                            {{-- <th style="">{{$getTableHeadingName[9]}}</th> --}}
                            <th width="23%">{{$getTableHeadingName[10]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);

                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td>
                                {{$rowData->approved_desired_duration}}
                                w.e.f.<br>
                                {{$rowData->approved_duration_start_date}}
                            </td>
                            <td>{{$rowData->first_commencement_date}}</td>
                            {{-- <td>EIE US$ {{$rowData->est_initial_expenses}} EME US$ {{$rowData->est_monthly_expenses}}</td> --}}
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            {{-- <td>{{$rowData->inward_remittance}}</td> --}}
                            {{-- <td>{{$rowData->manpower_foreign_ratio}}:{{$rowData->manpower_local_ratio}}</td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 3 and type B --}}

            @if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'C')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="25%">{{$getTableHeadingName[2]}}</th>
                            <th width="20%">{{$getTableHeadingName[3]}}</th>
                            <th width="20%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th width="10%">{{$getTableHeadingName[6]}}</th> --}}
                            <th width="22%">{{$getTableHeadingName[7]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        //                    $getOpaInfo = \App\Libraries\CommonFunction::getAgendaWiseWorkPermitNew($rowData->ref_app_tracking_no,'opa_apps');
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td>
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
                            <td>
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
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}

                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 3 and type C --}}

            @if($dataRow->agenda_name == 'AGENDA 3' && $dataRow->type == 'D')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3">{{$getTableHeadingName[1]}}</th>
                            <th width="40">{{$getTableHeadingName[2]}}</th>
                            <th width="17">{{$getTableHeadingName[3]}}</th>
                            {{-- <th style="">{{$getTableHeadingName[4]}}</th> --}}
                            {{-- <th style="">{{$getTableHeadingName[5]}}</th> --}}
                            {{-- <th  style="width: 150px;">{{$getTableHeadingName[6]}}</th> --}}
                            <th width="40">{{$getTableHeadingName[7]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>

                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            {{-- <td> Not found </td> --}}
                            {{-- <td> not found </td> --}}
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 3 and type D --}}

            @if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'A')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="22%">{{$getTableHeadingName[2]}}</th>
                            <th width="15%">{{$getTableHeadingName[3]}}</th>
                            <th width="15%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            <th width="15%">{{$getTableHeadingName[6]}}</th>
                            {{-- <th style="width: 150px;">{{$getTableHeadingName[7]}}</th> --}}
                            {{-- <th>{{$getTableHeadingName[8]}}</th> --}}
                            <th width="20%">{{$getTableHeadingName[9]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        //                    dd($getApplicationData);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}<br></td>
                            <td>
                                {{$rowData->approved_desired_duration}}
                                w.e.f.<br>
                                {{$rowData->approved_duration_start_date}}
                            </td>
                            <td>
                                AC: {{$rowData->authorized_capital}} US$ PC: {{$rowData->paid_up_capital}} US$
                            </td>
                            <td>EIE US$ {{$rowData->est_initial_expenses}} EME US$ {{$rowData->est_monthly_expenses}}</td>
                            {{-- <td > --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            {{-- <td>{{$rowData->manpower_foreign_ratio}}:{{$rowData->manpower_local_ratio}}</td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>

                        </tr>

                        <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenda 4 and type A --}}

            @if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'B')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th>{{$getTableHeadingName[1]}}</th>
                            <th>{{$getTableHeadingName[2]}}</th>
                            <th>{{$getTableHeadingName[3]}}</th>
                            <th>{{$getTableHeadingName[4]}}</th>
                            <th>{{$getTableHeadingName[5]}}</th>
                            {{-- <th>{{$getTableHeadingName[6]}}</th> --}}
                            {{-- <th style="width: 150px;">{{$getTableHeadingName[7]}}</th> --}}
                            {{-- <th>{{$getTableHeadingName[8]}}</th> --}}
                            {{-- <th>{{$getTableHeadingName[9]}}</th> --}}
                            <th>{{$getTableHeadingName[10]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},
                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td>
                                {{$rowData->approved_desired_duration}}
                                w.e.f.<br>
                                {{$rowData->approved_duration_start_date}}
                            </td>
                            <td>{{$rowData->first_commencement_date}}</td>
                            {{-- <td>EIE US$ {{$rowData->est_initial_expenses}} EME US$ {{$rowData->est_monthly_expenses}}</td> --}}
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            {{-- <td>{{$rowData->inward_remittance}}</td> --}}
                            {{-- <td>{{$rowData->manpower_foreign_ratio}}:{{$rowData->manpower_local_ratio}}</td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 4 and type B --}}

            @if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'C')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="27%">{{$getTableHeadingName[2]}}</th>
                            <th width="20%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            <th width="10%">{{$getTableHeadingName[6]}}</th>
                            <th width="20%">{{$getTableHeadingName[7]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        //                    $getOpaInfo = \App\Libraries\CommonFunction::getAgendaWiseWorkPermitNew($rowData->ref_app_tracking_no,'opa_apps');
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},

                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td>
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
                            <td>
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
                            <td ><?php
                                if (!isset($rowData->DocInfo)) {
                                    echo 'Not Found!';
                                } else {
                                    $docNameFull = explode('@@', $rowData->DocInfo);
                                    $docName = explode('@@', $rowData->DocInfoShortName);
                                    $sl      = 1;
                                    foreach ($docNameFull as $key=>$dn) {

                                        if(isset($docName[$key]) && $docName[$key] !=null){
                                            echo $sl . '. ' .$docName[$key]. "<br>";
                                        }else{
                                            echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                        }

                                        ++$sl;
                                    }
                                }
                                ?>
                                </td>

                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            @endif {{-- for Agenga 3 and type C --}}

            @if($dataRow->agenda_name == 'AGENDA 4' && $dataRow->type == 'D')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="27%">{{$getTableHeadingName[2]}}</th>
                            <th width="20%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            <th width="10%">{{$getTableHeadingName[6]}}</th>
                            <th width="20%">{{$getTableHeadingName[7]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                {{$rowData->local_company_name}}<br>
                                {{$rowData->office_address}},
                                {{$rowData->office_post_office}},
                                {{$rowData->office_post_code}},
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id]:''}},
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id]:''}},
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id]:''}},

                                <br>
                                Country of origin:
                                {{isset($countries[$rowData->c_origin_country_id])?$countries[$rowData->c_origin_country_id] :''}}<br>
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{isset($rowData->activities_in_bd)?$rowData->activities_in_bd:''}}</td>
                            <td> Not found </td>
                            <td> not found </td>
                            <td  ><?php
                                if (!isset($rowData->DocInfo)) {
                                    echo 'Not Found!';
                                } else {
                                    $docNameFull = explode('@@', $rowData->DocInfo);
                                    $docName = explode('@@', $rowData->DocInfoShortName);
                                    $sl      = 1;
                                    foreach ($docNameFull as $key=>$dn) {

                                        if(isset($docName[$key]) && $docName[$key] !=null){
                                            echo $sl . '. ' .$docName[$key]. "<br>";
                                        }else{
                                            echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                        }

                                        ++$sl;
                                    }
                                }
                                ?></td>

                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 4 and type D --}}

        <!--************************************-->
            @if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'A')
                <div>
                    <br style="page-break-before: always">
                    {{--<br>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="20%">{{$getTableHeadingName[2]}}</th>
                            <th width="10%">{{$getTableHeadingName[3]}}</th>
                            <th width="8%">{{$getTableHeadingName[4]}}</th>
                            <th width="15%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th width="10%">{{$getTableHeadingName[6]}}</th> --}}
                            <th width="10%">{{$getTableHeadingName[7]}}</th>
                            <th width="7%">{{$getTableHeadingName[8]}}</th>
                            {{-- <th width="7%">{{$getTableHeadingName[9]}}</th> --}}
                            <th width="7%">{{$getTableHeadingName[10]}}</th>
                            {{-- <th>{{$getTableHeadingName[11]}}</th> --}}
                            <th width="20%">{{$getTableHeadingName[12]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
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

                                {{isset($getAgendaWiseApplicationWiseBasicInfo->major_activities)?$getAgendaWiseApplicationWiseBasicInfo->major_activities.',':''}}
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{$rowData->emp_name}}, {{$rowData->emp_designation}} , {{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}}  <br> {{$rowData->emp_passport_no}}</td>
                            <td>
                                {{isset($WP_visaTypes[$rowData->work_permit_type])?$WP_visaTypes[$rowData->work_permit_type] .' Type':''}}
                            </td>
                            <td>
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
                            <td>USD {{$rowData->basic_salary_from_dd}}</td>
                            <td>
                                @if(!empty($rowData->approved_desired_duration))
                                    {{$rowData->approved_desired_duration}}
                                    w.e.f.<br>
                                    {{$rowData->approved_duration_start_date}}
                                @endif
                            </td>
                            <td>AC: {{$rowData->auth_capital}} PC: {{$rowData->paid_capital}}</td>
                            <td><?php
                                if (!isset($rowData->DocInfo)) {
                                    echo 'Not Found!';
                                } else {
                                    $docNameFull = explode('@@', $rowData->DocInfo);
                                    $docName = explode('@@', $rowData->DocInfoShortName);
                                    $sl      = 1;
                                    foreach ($docNameFull as $key=>$dn) {

                                        if(isset($docName[$key]) && $docName[$key] !=null){
                                            echo $sl . '. ' .$docName[$key]. "<br>";
                                        }else{
                                            echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                        }

                                        ++$sl;
                                    }
                                }
                                ?></td>
                            <td>{{$rowData->received_remittance}}</td>
                            <td>{{isset($rowData->manpower_foreign_ratio)?$rowData->manpower_foreign_ratio:''}}:{{isset($rowData->manpower_local_ratio)?$rowData->manpower_local_ratio:''}}</td>
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}} </td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif {{-- for Agenga 5 and type A --}}

            @if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'B')
                <div>
                    <br style="page-break-before: always">
                    {{--<div class="row"><br></div>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="27%">{{$getTableHeadingName[2]}}</th>
                            <th width="20%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th style="">{{$getTableHeadingName[6]}}</th> --}}
                            <th style="">{{$getTableHeadingName[7]}}</th>
                            <th width="10%">{{$getTableHeadingName[8]}}</th>
                            {{-- <th style="width: 150px;">{{$getTableHeadingName[9]}}</th> --}}
                            {{-- <th style="">{{$getTableHeadingName[10]}}</th> --}}
                            {{-- <th style="">{{$getTableHeadingName[11]}}</th> --}}
                            <th width="20%">{{$getTableHeadingName[12]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i=1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        //                    dump($rowData->company_id);
                        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
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
                                {{isset($getAgendaWiseApplicationWiseBasicInfo->major_activities)?$getAgendaWiseApplicationWiseBasicInfo->major_activities.',':''}}
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{$rowData->emp_name}}, {{$rowData->emp_designation}},
                                {{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}}, {{$rowData->emp_passport_no}}</td>
                            <td>{{isset($WP_visaTypes[$rowData->work_permit_type])?$WP_visaTypes[$rowData->work_permit_type] .'Type' :''}}</td>

                            <td>
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

                                {{--Basic salary: {{isset($currencies[$rowData->basic_local_currency_id])?$currencies[$rowData->basic_local_currency_id]->code:'BDT'}} {{$rowData->basic_local_amount}}<br>--}}
                                {{--House Accommodation: {{isset($currencies[$rowData->house_local_currency_id])?$currencies[$rowData->house_local_currency_id]->code:'BDT'}} {{$rowData->house_local_amount}}<br>--}}
                                {{--Conveyance arrangement: {{isset($currencies[$rowData->conveyance_local_currency_id])?$currencies[$rowData->conveyance_local_currency_id]->code:'BDT'}} {{$rowData->conveyance_local_amount}}<br>--}}
                                {{--Entertainment: {{isset($currencies[$rowData->ent_local_currency_id])?$currencies[$rowData->ent_local_currency_id]->code:'BDT'}} {{$rowData->ent_local_amount}}--}}

                            </td>
                            {{-- <td>USD {{isset($rowData->basic_salary_from_dd)?$rowData->basic_salary_from_dd :''}}</td> --}}
                            <td>
                                @if(!empty($rowData->approved_desired_duration))
                                    {{$rowData->approved_desired_duration}}
                                    w.e.f.<br>
                                    {{$rowData->approved_duration_start_date}}
                                @endif
                            </td>
                            <td>{{$rowData->issue_date_of_first_wp}}</td>
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            {{-- <td>{{$rowData->received_remittance}}</td> --}}
                            {{-- <td>{{isset($rowData->manpower_foreign_ratio)?$rowData->manpower_foreign_ratio:''}}:{{isset($rowData->manpower_local_ratio)?$rowData->manpower_local_ratio:''}}</td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            @endif {{--for agenda 5 and type B--}}

            @if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'C')
                <div>
                    <br style="page-break-before: always">
                    {{--<div class="row"><br></div>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="15%">{{$getTableHeadingName[2]}}</th>
                            <th width="10%">{{$getTableHeadingName[3]}}</th>
                            <th width="5%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            <th width="10%">{{$getTableHeadingName[6]}}</th>
                            <th width="23">{{$getTableHeadingName[7]}}</th>
                            <th width="13">{{$getTableHeadingName[8]}}</th>
                            {{-- <th style="width: 100px">{{$getTableHeadingName[9]}}</th> --}}
                            <th width="13">{{$getTableHeadingName[10]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        $getWpnInfo = \App\Libraries\CommonFunction::getAgendaWiseWorkPermitNew($rowData->ref_app_tracking_no,'wpa_apps');
                        //do later
                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
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
                            <td>{{$rowData->emp_name}}, {{$rowData->emp_designation}}, {{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}}, {{$rowData->emp_passport_no}}</td>
                            <td>{{isset($WP_visaTypes[$rowData->work_permit_type])?$WP_visaTypes[$rowData->work_permit_type] .' Type' :''}}</td>
                            <td>
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
                            <td>
                                @if(!empty($rowData->basic_local_amount) && $rowData->basic_local_amount != 0)
                                    {{isset($currencies[$rowData->basic_local_currency_id])?"Basic salary: ".$currencies[$rowData->basic_local_currency_id]:'BDT'}} {{$rowData->basic_local_amount}}<br>
                                @endif

                                @if(!empty($rowData->house_local_amount) && $rowData->house_local_amount != 0)
                                    {{isset($currencies[$rowData->house_local_currency_id])?"House Accommodation: ".$currencies[$rowData->house_local_currency_id]:'BDT'}} {{$rowData->house_local_amount}}<br>
                                @endif

                                @if(!empty($rowData->conveyance_local_amount) && $rowData->conveyance_local_amount != 0)
                                    {{isset($currencies[$rowData->conveyance_local_currency_id])?"Conveyance arrangement: ".$currencies[$rowData->conveyance_local_currency_id]:'BDT'}} {{$rowData->conveyance_local_amount}}<br>
                                @endif

                                @if(!empty($rowData->ent_local_amount) && $rowData->ent_local_amount != 0)
                                    {{isset($currencies[$rowData->ent_local_currency_id])?"Entertainment: ".$currencies[$rowData->ent_local_currency_id]:'BDT'}} {{$rowData->ent_local_amount}}
                                @endif
                                @if(!empty($rowData->overseas_local_amount) && $rowData->overseas_local_amount != 0)
                                    {{isset($currencies[$rowData->overseas_local_currency_id])?" Overseas allowance: ".$currencies[$rowData->overseas_local_currency_id]:'BDT'}} {{$rowData->overseas_local_amount}}<br>
                                @endif
                                @if(!empty($rowData->medical_local_amount) && $rowData->medical_local_amount != 0)
                                    {{isset($currencies[$rowData->medical_local_currency_id])?"Medical allowance: ".$currencies[$rowData->medical_local_currency_id]:'BDT'}} {{$rowData->medical_local_amount}}<br>
                                @endif
                                @if(!empty($rowData->bonus_local_amount) && $rowData->bonus_local_amount != 0)
                                    {{isset($currencies[$rowData->bonus_local_currency_id])?" Annual Bonus: ".$currencies[$rowData->bonus_local_currency_id]:'BDT'}} {{$rowData->bonus_local_amount}}
                                @endif
                            </td>
                            <td>
                                Full Name: {{$rowData->emp_name}}<br>

                                Designation: {{$rowData->emp_designation}}<br>
                                Passport No.: {{$rowData->emp_passport_no}}<br>
                                Nationality: {{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id] :''}}<br>
                                Start Date: {{$rowData->p_duration_start_date}}<br>
                                End Date: {{$rowData->p_duration_end_date}}<br>
                                Duration (Days): {{$rowData->p_desired_duration}}<br>
                                <b>Compensation and Benefit</b>
                                Basic salary/ Honorarium: Payment:
                                {{isset($paymentType[$rowData->basic_payment_type_id])?$paymentType[$rowData->basic_payment_type_id]:'  '}}
                                Amount: {{$rowData->basic_local_amount}}
                                Currency:  {{isset($currencies[$rowData->basic_local_currency_id])?$currencies[$rowData->basic_local_currency_id]:'BDT'}}

                                @if(!empty($rowData->overseas_local_amount))
                                    Overseas allowance:
                                    {{isset($paymentType[$rowData->overseas_payment_type_id])?$paymentType[$rowData->overseas_payment_type_id]:'  '}}
                                    Amount: {{$rowData->overseas_local_amount}}
                                    Currency:  {{isset($currencies[$rowData->overseas_local_currency_id])?$currencies[$rowData->overseas_local_currency_id]:'BDT'}}
                                @endif

                                @if(!empty($rowData->house_local_amount))
                                    House rent:
                                    {{isset($$paymentType[$rowData->house_payment_type_id])?$paymentType[$rowData->house_payment_type_id]->name :''}}
                                    Amount: {{$rowData->house_local_amount}}
                                    Currency:  {{isset($currencies[$rowData->house_local_currency_id])?$currencies[$rowData->house_local_currency_id]:'BDT'}}
                                @endif

                                @if(!empty($rowData->conveyance_local_amount))
                                    Conveyance:
                                    {{isset($$paymentType[$rowData->conveyance_payment_type_id])?$paymentType[$rowData->conveyance_payment_type_id]->name :''}}
                                    Amount: {{$rowData->conveyance_local_amount}}
                                    Currency:  {{isset($currencies[$rowData->conveyance_local_currency_id])?$currencies[$rowData->conveyance_local_currency_id]:'BDT'}}
                                @endif

                                @if(!empty($rowData->ent_local_amount))
                                    Entertainment allowance:
                                    {{isset($$paymentType[$rowData->ent_payment_type_id])?$paymentType[$rowData->ent_payment_type_id]->name :''}}
                                    Amount: {{$rowData->ent_local_amount}}
                                    Currency:  {{isset($currencies[$rowData->ent_local_currency_id])?$currencies[$rowData->ent_local_currency_id]:'BDT'}}
                                @endif
                            </td>
                            <td>
                                {{isset($rowData->applicant_name)?$rowData->applicant_name :''}}<br>
                                {{isset($rowData->applicant_pass_no)?$rowData->applicant_pass_no :''}}<br>
                                {{isset($rowData->period_of_wp_from)?$rowData->period_of_wp_from :''}}<br>
                                {{isset($nationality[$rowData->applicant_nationality])?$nationality[$rowData->applicant_nationality] :''}}<br>
                                {{isset($rowData->date_of_cancellation)?$rowData->date_of_cancellation :''}}<br>
                                {{isset($rowData->period_of_wp_to)?$rowData->period_of_wp_to :''}}<br>

                                {{isset($rowData->office_address)?$rowData->office_address :''}}<br>
                                {{isset($thana[$rowData->office_thana_id])?$thana[$rowData->office_thana_id] :''}}<br>
                                {{isset($rowData->office_post_code)?$rowData->office_post_code :''}}<br>
                                {{isset($rowData->office_post_office)?$rowData->office_post_office:''}}<br>
                                {{isset($districts[$rowData->office_district_id])?$districts[$rowData->office_district_id] :''}}<br>
                                {{isset($divisions[$rowData->office_division_id])?$divisions[$rowData->office_division_id] :''}}<br>
                                {{isset($rowData->office_mobile_no)?$rowData->office_mobile_no :''}}<br>
                                {{isset($rowData->office_email)?$rowData->office_email :''}}<br>
                                {{isset($countries[$rowData->country_of_origin_id])?$countries[$rowData->country_of_origin_id] :''}}<br>
                            </td>
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif {{--for agenda 5 and type C--}}


            @if($dataRow->agenda_name == 'AGENDA 5' && $dataRow->type == 'D')
                <div >
                    <br style="page-break-before: always">
                    {{--<div class="row"><br></div>--}}
                    <div style="text-align: center" class="text-center">
                        <span style="font-weight: bold">{{$dataRow->agenda_name}}</span><br>
                        <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
                    </div>
                    <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th width="3%">{{$getTableHeadingName[1]}}</th>
                            <th width="27%">{{$getTableHeadingName[2]}}</th>
                            <th width="20%">{{$getTableHeadingName[3]}}</th>
                            <th width="10%">{{$getTableHeadingName[4]}}</th>
                            <th width="10%">{{$getTableHeadingName[5]}}</th>
                            {{-- <th>{{$getTableHeadingName[6]}}</th> --}}
                            {{-- <th>{{$getTableHeadingName[7]}}</th> --}}
                            <th width="10%">{{$getTableHeadingName[8]}}</th>
                            {{-- <th style="width: 150px;">{{$getTableHeadingName[9]}}</th> --}}
                            {{-- <th>{{$getTableHeadingName[10]}}</th> --}}
                            {{-- <th>{{$getTableHeadingName[11]}}</th> --}}
                            <th width="20%">{{$getTableHeadingName[12]}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
                        foreach ($getApplicationData as $rowData) {
                        $getAgendaWiseApplicationWiseBasicInfo = \App\Libraries\CommonFunction::getAgendaWiseBasicInfo($rowData->company_id);
                        $getWpnInfo = \App\Libraries\CommonFunction::getAgendaWiseWorkPermitNew($rowData->ref_app_tracking_no,'wpc_apps');
                        //                $getDocInfo = \App\Libraries\CommonFunction::getDocInfo($rowData->ref_app_tracking_no,'wpc_apps');

                        ?>

                        <tr>
                            <td>{{$i++}}</td>
                            <td>
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
                                {{isset($getAgendaWiseApplicationWiseBasicInfo->major_activities)?$getAgendaWiseApplicationWiseBasicInfo->major_activities.',':''}}
                                (ID#{{$rowData->tracking_no}})
                            </td>
                            <td>{{$rowData->emp_name}}, {{$rowData->emp_designation}}, {{isset($nationality[$rowData->emp_nationality_id])?$nationality[$rowData->emp_nationality_id]:''}}, {{$rowData->emp_passport_no}}</td>
                            <td>{{isset($WP_visaTypes[$rowData->work_permit_type])?$WP_visaTypes[$rowData->work_permit_type] .'Type' :''}}</td>
                            <td>
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
                            {{-- <td>USD {{isset($rowData->basic_salary_from_dd)?$rowData->basic_salary_from_dd :''}}</td> --}}
                            {{-- <td>
                                @if(!empty($rowData->approved_desired_duration))
                                    {{$rowData->approved_desired_duration}}
                                    w.e.f.<br>
                                    {{$rowData->approved_duration_start_date}}
                                @endif
                            </td> --}}
                            <td>{{$rowData->date_of_cancellation}}</td>
                            {{-- <td> --}}
                                <?php
                                // if (!isset($rowData->DocInfo)) {
                                //     echo 'Not Found!';
                                // } else {
                                //     $docNameFull = explode('@@', $rowData->DocInfo);
                                //     $docName = explode('@@', $rowData->DocInfoShortName);
                                //     $sl      = 1;
                                //     foreach ($docNameFull as $key=>$dn) {

                                //         if(isset($docName[$key]) && $docName[$key] !=null){
                                //             echo $sl . '. ' .$docName[$key]. "<br>";
                                //         }else{
                                //             echo $sl . '. ' . str_limit( $dn , 25). "<br>";
                                //         }

                                //         ++$sl;
                                //     }
                                // }
                                ?>
                            {{-- </td> --}}
                            {{-- <td>{{isset($getWpnInfo->received_remittance)?$getWpnInfo->received_remittance :''}}</td> --}}
                            {{-- <td>{{isset($getAgendaWiseApplicationWiseBasicInfo->manpower_foreign_ratio)?$getAgendaWiseApplicationWiseBasicInfo->manpower_foreign_ratio:''}}:{{isset($getAgendaWiseApplicationWiseBasicInfo->manpower_local_ratio)?$getAgendaWiseApplicationWiseBasicInfo->manpower_local_ratio:''}}</td> --}}
                            <td>{{isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd)?$rowData->process_desc_from_dd:''}}</td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            @endif
            {{--for agenda 5 and type D--}}

            @if($dataRow->agenda_name == 'AGENDA 1' && $dataRow->type == 'A' &&  in_array($dataRow->process_type_id, [22, 23, 24, 25]))
                @include('BoardMeting::agenda.agenda-content-pdf-a1-ta', ['dataRow' => $dataRow, 'getTableHeadingName' => $getTableHeadingName])
            @endif
            {{--for agenda 1 and type A--}}

    @endforeach

</div>