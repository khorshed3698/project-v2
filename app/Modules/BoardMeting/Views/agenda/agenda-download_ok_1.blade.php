
<div  style="text-align: center" class="text-center">
   <h3>{{$board_meeting_data->org_name}}</h3>
    <h4>{{$board_meeting_data->org_address}}</h4>
</div>

<ta class="container">

    <div class="row"><br></div>
    <div class="row"><br></div>

    <table width="100%" cellspacing="0" style="border: 1 px solid #5f5f5f;" aria-label="Detailed Report Data Table">
        <tr>
            <th aria-hidden="true"  scope="col"></th>
        </tr>
        <tr>
            <td style="border: 1px solid #5f5f5f"> <span style="font-weight: bold">Location</span> </td>
            <td style="border: 1px solid #5f5f5f"> {{$meetingInfo[0]->bmlocation}}</td>
        </tr>

        <tr>
            <td style="border: 1px solid #5f5f5f"> <span style="font-weight: bold"> Date & Time</span> </td>
            <td style="border: 1px solid #5f5f5f"> {{$meetingInfo[0]->meting_date}}</td>
        </tr>

        <tr>
            <td style="border: 1px solid #5f5f5f"><span style="font-weight: bold">Subject</span></td>
            <td style="border: 1px solid #5f5f5f">{{$meetingInfo[0]->meting_subject}}</td>
        </tr>

        <tr>
            <td style="border: 1px solid #5f5f5f"><span style="font-weight: bold">Meeting No.</span></td>
            <td style="border: 1px solid #5f5f5f">{{$meetingInfo[0]->meting_number}}</td>
        </tr>

    </table>
    <div class="row"><br></div>
    <div class="row"><br></div>

    <table width="100%" cellspacing="0" style="border: 1 px solid #5f5f5f;" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <th>Sl No.</th>
            <th>Description</th>
            <th>New</th>
            <th>Extension</th>
            <th>Amendment</th>
            <th>Cancellation</th>
        </tr>
        </thead>
        <tbody>

            <?php $i = 1; $totalApp = 0;?>
            @foreach($processWiseTotalApplication as $row)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$row->Module_Name}}</td>
                    <td>{{$row->New}}</td>
                    <td>{{$row->Extension}}</td>
                    <td>{{$row->Amendment}}</td>
                    <td>{{$row->Cancellation}}</td>
                </tr>
                <?php $totalApp+=$row->New+$row->Extension+$row->Amendment+$row->Cancellation;?>
            @endforeach

        </tbody>
        <tfoot>
        <tr>
            <td colspan="2">Total Application</td>
            <td>{{$totalApp}}</td>
        </tr>
        </tfoot>


    </table>


    <div class="row"><br></div>
    <div class="row"><br></div>

    @foreach($arrayData as $key=> $dataRow)
        <?php
            $getJsonObjectData = $dataRow->table_heading_json_format;
            $getTableHeadingName = json_decode($getJsonObjectData, TRUE);
            ?>
            <div style="text-align: center" class="text-center">
                <span style="font-weight: bolder">{{$dataRow->agenda_name}}</span><br>
                <span  style="font-weight: bold">{{$dataRow->type}}.{{$dataRow->agenda_heading_title}}</span>
            </div>
            <table border="1" aria-label="Detailed Report Data Table">
            <tr>

                <th>{{$getTableHeadingName[1]}}</th>


                <th>{{$getTableHeadingName[2]}}</th>


                <th>{{$getTableHeadingName[3]}}</th>


                 <th>{{$getTableHeadingName[4]}}</th>


                <th>{{$getTableHeadingName[5]}}</th>


                <th>{{$getTableHeadingName[6]}}</th>


                <th>{{$getTableHeadingName[7]}}</th>


                <th>{{$getTableHeadingName[8]}}</th>


                <th>{{$getTableHeadingName[9]}}</th>


                <th>{{$getTableHeadingName[10]}}</th>

                <th>{{$getTableHeadingName[11]}}</th>

                @if(isset($getTableHeadingName[12]))
                <th>{{$getTableHeadingName[12]}}</th>
           @endif

            </tr>
                <tbody>

                <?php
                $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name,$dataRow->type,$meeting_id, $key);
                foreach ($getApplicationData as $rowData){
//                    dd();
                    ?>


                <tr>

                    <td>2</td>



                    <td>not found</td>



                    <td>{{$rowData->emp_name}}, {{$rowData->emp_designation}} ,{{isset($rowData->emp_nationality_id)?$nationality[$rowData->emp_nationality_id]->nationality :''}}  , {{$rowData->emp_passport_no}}</td>



                    <td>{{isset($WP_visaTypes[$rowData->work_permit_type])?$WP_visaTypes[$rowData->work_permit_type]->type :''}}</td>


                    @if(isset($getTableHeadingName[5]))
                        @if($getTableHeadingName[5] == 'Minimum range of Basic Salary')
                            <td>{{isset($currency[$rowData->basic_local_currency_id])?$currency[$rowData->basic_local_currency_id]->code :''}}: {{$rowData->basic_local_amount}}</td>
                        @else
                    <td>
                        @if($rowData->basic_payment_type_id == 3)
                            Basic salary: {{$rowData->basic_local_amount}}<br>
                            House Accommodation:{{$rowData->house_local_amount}}<br>
                            Conveyance arrangement:{{$rowData->conveyance_local_amount}}<br>
                            Entertainment:{{$rowData->ent_local_amount}}
                        @endif
                    </td>
                        @endif
                    @endif

                    @if(isset($getTableHeadingName[6]))
                        @if($getTableHeadingName[6] == 'Proposed duration and effective date & Expire date of Branch/Liaison/ Representative Office')
                            <td>not found</td>
                        @else
                            <td>{{isset($currency[$rowData->basic_local_currency_id])?$currency[$rowData->basic_local_currency_id]->code :''}}: {{$rowData->basic_local_amount}}</td>
                        @endif
                    @endif


                    @if(isset($getTableHeadingName[7]))
                            @if($getTableHeadingName[7] == 'Date of First Appointment')
                                <td>{{$rowData->issue_date_of_first_wp}}</td>
                            @else
                    <td>not found</td>
                            @endif
                    @endif

                    @if(isset($getTableHeadingName[8]))
                            @if($getTableHeadingName[8] == 'Documents submitted')
                                <td><?php if(!isset($rowData->DocInfo)) {
                                        echo   'Not Found!';
                                    }else{
                                        $docName = explode(',',$rowData->DocInfo);
                                        $sl = 1;
                                        foreach($docName as $dn){
                                            echo $sl.'. '.$dn."<br>";
                                            ++$sl;
                                        }
                                    }?></td>
                            @else

                    <td>AC: {{$rowData->auth_capital}} PC: {{$rowData->paid_capital}}</td>
                    @endif

                    @endif

                    @if(isset($getTableHeadingName[9]))
                            @if($getTableHeadingName[9] == 'Inward Remittance (Encashment)')
                                <td>not found</td>
                            @else
                    <td><?php if(!isset($rowData->DocInfo)) {
                     echo   'Not Found!';
                        }else{
                        $docName = explode(',',$rowData->DocInfo);
                        $sl = 1;
                        foreach($docName as $dn){
                            echo $sl.'. '.$dn."<br>";
                            ++$sl;
                        }
                        }?></td>
                        @endif
                     @endif


                    <td>not found</td>


                    @if(isset($getTableHeadingName[11]))
                        @if($getTableHeadingName[11] == 'Remarks/Required')
                        <td>{{isset($rowData->process_desc) && !empty($rowData->process_desc)?$rowData->process_desc:''}}</td>
                        @else
                    <td>not found</td>
                    @endif
                    @endif

                    @if(isset($getTableHeadingName[12]))
                    <td>{{isset($rowData->process_desc) && !empty($rowData->process_desc)?$rowData->process_desc:''}}</td>
                    @endif
                    {{--<td>w</td>--}}
                </tr>
                <?php
                }
                ?>
                </tbody>
            </table>

    @endforeach
    {{dd(444)}}





{{dd(55)}}
    <table border="1" style="border:1px solid #5f5f5f" width="100%" aria-label="Detailed Report Data Table">
        <tr>
            <th aria-hidden="true"  scope="col"></th>
        </tr>
        <tr>
            <td style="border: 1px solid #5f5f5f">#</td>
            <td style="border: 1px solid #5f5f5f">Tracking ID</td>
            <td style="border: 1px solid #5f5f5f">Headline</td>
            <td style="border: 1px solid #5f5f5f">Description</td>
            <td style="border: 1px solid #5f5f5f">Remarks</td>
        </tr>
        {{dd(4)}}
        <?php
        $count1 = 1;
//        dd($meetingInfo);
        ?>
        @foreach($meetingInfo as $details)
            <?php
//                dump( $details->pbmdatas);
                $count2=1;
                $process_list_data = explode("@@", $details->pbmdatas);
            if($count2 > 1 ){
                foreach ($process_list_data as $key=>$specificRow ){
                    $rowDataSplit = explode("AAAAA",$specificRow);

                }

            }
            ?>
                {{--@if($count1 == 1)--}}
                    {{--<tr>--}}
                        {{--<td></td>--}}
                        {{--<td>Tracking ID</td>--}}
                        {{--<td>Title</td>--}}
                        {{--<td>Details</td>--}}
                        {{--<td>Remarks</td>--}}
                    {{--</tr>--}}
                {{--@endif--}}
            <tr>
                <td style="border: 1px solid #5f5f5f">{{$count1}}</td>
                <td style="border: 1px solid #5f5f5f; text-align: center;" colspan="4">
                    {{$details->name}}
                </td>
            </tr>


            @foreach($process_list_data as $specificRow)

                <?php $rowDataSplit = explode("AAAAA",$specificRow);


                    if(count($process_list_data) >= 1 && $rowDataSplit[0] != null){
                        ?>
            <tr>
                <td style="border: 1px solid #5f5f5f">{{$count1}}.{{$count2}}</td>
                <td style="border: 1px solid #5f5f5f">{{$rowDataSplit[0]}}</td>
                {{--@if(isset($rowDataSplit[1]))--}}
                @if(isset($rowDataSplit[1]))
                    <?php
                    $getJsonObject = json_decode($rowDataSplit[1]);
//                    dd($getJsonObject->name_local_address_country_office_country);
                    ?>

                <td style="border: 1px solid #5f5f5f">
                    {{$getJsonObject->name_address_country_office_country}}
                </td>
                <td style="border: 1px solid #5f5f5f">
                    {{$getJsonObject->nature_of_the_Business_office}}
                </td>
            <td style="border: 1px solid #5f5f5f">{{$getJsonObject->proposed_duration_effective_date_of_commencement}}</td>
                @else
            <td style="border: 1px solid #5f5f5f"></td>
                @endif

            </tr>
                    <?php
                        }else{

                    }
                    $count2++;
                    ?>
                    @endforeach

            <?php
            $count1++;
            ?>
        @endforeach
    </table>

</div>
