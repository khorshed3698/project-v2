
@if($ms == 1)
    <style type="text/css">
        body {
            font-family: "Times New Roman", Times, serif;
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
    <h4>{{$board_meeting_data->meting_number}}<sup>th</sup> Executive Council Meeting</h4>
    <h5 style="font-weight: bold">Agenda <b>3: Consideration of remittance Proposals</b></h5>
</div>



<div class="container" >


    <div class="row"><br></div>
    <?php
    $i = 1;
    ?>
    @foreach ($totalApplication as $appInfo)
        <?php
        $getJsonObjectData   = $arrayData[0]->table_heading_json_format;
        $getTableHeadingName = json_decode($getJsonObjectData, TRUE);
        $totalRow = array_keys($getTableHeadingName);
        $applicatonData =  \App\Libraries\CommonFunction::getRemittanceData($appInfo->process_id);
        $StatementOfRemittance =  \App\Libraries\CommonFunction::StatementOfRemittance($appInfo->process_id);

        ?>
        <br>
        <div style="page-break-after: auto">
            <div style="font-weight: bold; text-align: center;">
                Proposal {{$i++}} of {{$totalApplication->count()}} {{$applicatonData->company_name}}
            </div>

            <table cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                <thead>
                <tr>

                    <th style="width: 10px"></th>
                    <th style="width: 45%"></th>
                    <th style="width: 5px"></th>
                    <th style="" ></th>

                </tr>
                </thead>
                <tbody>

                <tr>
                    <td style="width: 25px;text-align: center; ">1</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[1]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        {{--                        {{dd($applicatonData)}}--}}
                        {{$applicatonData->company_name}}.<br>
                        {{$applicatonData->office_address}},
                        {{$applicatonData->office_post_office}},
                        {{$applicatonData-> office_post_code}},
                        @if(!empty($applicatonData->office_thana_id))
                            {{$thana[$applicatonData->office_thana_id]}},
                        @endif
                        @if(!empty($applicatonData->office_district_id))
                            {{$districts[$applicatonData->office_district_id]}},
                        @endif
                        @if(!empty($applicatonData->office_division_id))
                            {{$divisions[$applicatonData->office_division_id]}},
                        @endif
                        @if(!empty($applicatonData->office_mobile_no))
                            {{$applicatonData->office_mobile_no}},
                        @endif
                        @if(!empty($applicatonData->office_email))
                            {{$applicatonData->office_email}},
                        @endif

                        @if(!empty($applicatonData->office_telephone_no))
                            {{$applicatonData->office_telephone_no}},
                        @endif
                        @if(!empty($applicatonData->office_fax_no))
                            {{$applicatonData->office_fax_no}}<br>
                        @endif

                    </td>
                </tr>
                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">2</td>
                    <td style="font-size: 15px" >{{$getTableHeadingName[2]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        {{$applicatonData->organization_name}},
                        {{$applicatonData->organization_address}},
                        {{$applicatonData->property_city}},
                        {{$applicatonData->property_post_code}},
                        @if(!empty($applicatonData->property_country_id))
                            {{$countries[$applicatonData->property_country_id]}},
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">3</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[3]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->business_sub_sector_id))
                            {{$SubSector[$applicatonData->business_sub_sector_id]}}
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px"></td>
                    <td style="font-size: 15px">{{$getTableHeadingName[4]}}</td>
                    <td style="width: 10px;text-align: center" >:</td>
                    <td style="font-size: 15px">
                        <?php
                        if (!isset($applicatonData->reg_info_reg_no)) {
                            echo 'Not Found!';
                        } else {
                            $reg_info_reg_no = explode('@@', $applicatonData->reg_info_reg_no);
                            $reg_info_date = explode('@@', $applicatonData->reg_info_date);
                            $sl      = 1;
                            foreach ($reg_info_reg_no as $key=>$dn) {
                                if(isset($reg_info_date[$key])){
                                    echo $dn . " dated. ".$reg_info_date[$key]."<br>";
                                    ++$sl;
                                }

                            }
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">4</td>
                    <td  style="font-size: 15px">{{$getTableHeadingName[5]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->total_investment_bdt)) Tk. {{$applicatonData->total_investment_bdt}}
                        @else N/A
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">5</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[6]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        <?php
                        echo number_format((int)$applicatonData->product_name_capacity);
                        ?>


                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">6</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[7]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px">:</td>
                    <td style="font-size: 15px">

                        @if(!empty($applicatonData->organization_status_id))
                            {{$EA_OrganizationStatus[$applicatonData->organization_status_id]}}
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">7</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[8]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->marketing_of_products_local))
                            Local: ({{$applicatonData->marketing_of_products_local}}%)
                        @else N/A
                        @endif
                        @if(!empty($applicatonData->marketing_of_products_foreign))
                            Foreign: ({{$applicatonData->marketing_of_products_foreign}}%)
                        @else N/A
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">8</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[9]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->present_status_id))
                            @if($applicatonData->present_status_id == 1)
                                In
                            @endif
                            {{$remittancePresentStatus[$applicatonData->present_status_id]}}
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">9</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[10]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->remittance_type_id))
                            {{$remittanceType[$applicatonData->remittance_type_id]}}
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">10</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[11]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="text-align: justify;font-size: 15px">
                        <?php
                        if (!isset($applicatonData->brief_description)) {
                            echo 'N/A';
                        } else {
                            $brief_description = explode('@@', $applicatonData->brief_description);
                            $sl1      = 1;
                            foreach ($brief_description as $key=>$brief_desc) {
                                echo $sl1 . '. '. $brief_desc ."<br>";
                                ++$sl1;
                            }
                        }
                        ?>

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">11</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[12]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        <?php
                        //                    if (!isset($applicatonData->cnf_value)) {
                        //                        echo 'Not Found!';
                        //                    } else {
                        //                        $cnf_value = explode('@@', $applicatonData->cnf_value);
                        //                        $sl1      = 1;
                        //                        foreach ($cnf_value as $key=>$cnf_values) {
                        //                            echo $cnf_values ."<br>";
                        //                            ++$sl1;
                        //                        }
                        //                    }
                        ?>
                        @if(!empty($applicatonData->proposed_sub_total_bdt))
                            <?php
                            echo number_format((int)$applicatonData->proposed_sub_total_bdt);
                            ?>
                            TK
                        @else N/A
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px"></td>
                    <td style="font-size: 15px">(b){{$getTableHeadingName[13]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px" >
                        <?php
                        //                    if (!isset($applicatonData->cnf_value)) {
                        //                        echo 'Not Found!';
                        //                    } else {
                        //                        $import_year_from = explode('@@', $applicatonData->import_year_from);
                        //                        $import_year_to = explode('@@', $applicatonData->import_year_to);
                        //                        $sl1      = 1;
                        //                        foreach ($import_year_from as $key=>$cnf_values) {
                        //                            echo $cnf_values . " dt. ".$import_year_to[$key]."<br>";
                        //                            ++$sl1;
                        //                        }
                        //                    }
                        ?>
                        @if(!empty($applicatonData->period_from))
                            {{date('F/Y', strtotime($applicatonData->period_from))}}
                        @endif
                        @if(!empty($applicatonData->period_to))
                            -{{date('F/Y', strtotime($applicatonData->period_to))}}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center;font-size: 15px">12</td>
                    <td style="width: 25px;font-size: 15px">{{$getTableHeadingName[14]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->sales_value_bdt))
                            Tk.{{$applicatonData->sales_value_bdt}}, <br>Tax paid {{$applicatonData->tax_amount_bdt}}
                        @else N/A
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">13</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[15]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->proposed_sub_total_exp_percentage))
                            <b>{{$applicatonData->proposed_sub_total_exp_percentage}}%</b>
                        @else N/A
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">14</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[16]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($StatementOfRemittance))
                            @foreach($StatementOfRemittance as $row)

                                Tk. @if(!empty($row->amount)) {{$row->amount}}, {{$row->percentage}}% @else N/A @endif
                                <br>
                                Sales Year-@if(!empty($row->remittance_year)) <?php echo date('Y',strtotime($row->remittance_year))?>,@else N/A @endif
                                <br>
                                Memo no.@if(!empty($row->bida_ref_no)){{$row->bida_ref_no}} @else N/A @endif
                            @endforeach
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">15</td>
                    <td style="font-size: 15px">{{$getTableHeadingName[17]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->total_remittance_percentage))
                            The Proposal=@if(!empty($row->total_remittance_percentage)) {{$applicatonData->total_remittance_percentage}}% @else N/A @endif
                        @endif
                    </td>
                </tr>


                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">16</td>
                    <td style="font-size: 15px">
                        Silent features of agreement
                    </td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td >

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px"></td>
                    <td style="font-size: 15px">
                        a) Terms and condition of the agreement
                    </td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        <?php
                        //                    if (!empty($applicatonData->brief_statement)) {
                        //                        $brief_statement = explode('@@', $applicatonData->brief_statement);
                        //                        foreach ($brief_statement as $key=>$brief_statements) {
                        //                            echo $brief_statements."<br>";
                        //                        }
                        //                    }

                        ?>
                        @if(!empty($row->total_remittance_percentage))
                            {{$applicatonData->total_fee_percentage}}
                        @else N/A
                        @endif

                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px"></td>
                    <td style="font-size: 15px">
                        b)Duration of agreement effective date
                    </td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->agreement_duration_from))
                            {{$applicatonData->agreement_duration_from}}-{{$applicatonData->agreement_duration_to}}
                        @else N/A
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px"></td>
                    <td style="font-size: 15px">
                        c) Mode(rate or lamp sum basic) and schedule of payment</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->schedule_of_payment)) {{$applicatonData->schedule_of_payment}}

                        @else
                            N/A
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px"></td>
                    <td style="font-size: 15px">
                        d) Rate of payment on the last year sales</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        @if(!empty($applicatonData->proposed_sub_total_exp_percentage)) {{$applicatonData->proposed_sub_total_exp_percentage}}%

                        @else
                            N/A
                        @endif
                    </td>
                </tr>



                <tr>
                    <td style="width: 25px;text-align: center;font-size: 15px">17</td>
                    <td style="width: 25px;font-size: 15px">{{$getTableHeadingName[18]}}</td>
                    <td style="width: 10px;text-align: center;font-size: 15px" >:</td>
                    <td style="font-size: 15px">
                        <?php
//                        if (!isset($applicatonData->DocInfo)) {
//                            echo 'Not Found!';
//                        } else {
//                            $docName = explode('@@', $applicatonData->DocInfo);
//                            $sl      = 1;
//                            foreach ($docName as $dns) {
//                                echo $sl . '. ' . $dns . "<p></p>";
//                                ++$sl;
//                            }
//                        }

                        if (!isset($applicatonData->DocInfo)) {
                            echo 'Not Found!';
                        } else {
                            $docNameFull = explode('@@', $applicatonData->DocInfo);
                            $docName = explode('@@', $applicatonData->DocInfoShortName);
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
                </tr>




                </tbody>
            </table>
            <div class="col-md-12">
                <h5 style="font-weight: bold">The proposal may be considered on the following terms and conditions:</h5>

            </div>

            <table width="100%" cellspacing="0" style="text-align: left;" aria-label="Detailed Report Data Table">
                <thead>
                <tr>
                    <th scope="col" >1</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        The company will remit the proposed amount of money from its own source which is {{$applicatonData->proposed_sub_total_exp_percentage}}%
                        on the net sales value for the last 2017
                    </th>
                </tr>

                <tr>
                    <th scope="col" style="font-size: 15px">2</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        The application company shall have to inform BIDA with authenticated banking documents as and when payments as made.
                    </th>
                </tr>

                <tr>
                    <th scope="col" style="font-size: 15px">3</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        The Government shall neither be involved nor liable in any way for the remittance of sad amount.
                    </th>
                </tr>

                <tr>
                    <th scope="col" style="font-size: 15px">4</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        The company shall inform the Bangladesh Investment development authority in writing immediately on transmittal of the approved remittance with details.
                    </th>
                </tr>

                <tr>
                    <th scope="col" style="font-size: 15px">5</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        While effecting the remittance the nominated bank (Authorize dealer) must follow the relevent chapter of
                        guidelines for foreign exchange transaction along with foreign exchange circulars issued subsequently
                        by the Bangladesh Bank.
                    </th>
                </tr>

                <tr>
                    <th scope="col" style="font-size: 15px">6</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        The company shall comply with foreign exchange regulations while effecting the sad remittance.
                    </th>
                </tr>

                <tr>
                    <th scope="col" style="font-size: 15px">7</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        The sad remittance shall be remitted against the invoice(s) received/to be received from the service provider.
                    </th>
                </tr>



                <tr>
                    <th scope="col" style="font-size: 15px">8</th>
                    <th scope="col" style="text-align:left;font-size: 15px">
                        The company shall comply with the Tax policy of the Govt.
                    </th>
                </tr>


                </thead>
                <tbody>
                </tbody>


            </table>
            <div class="col-md-12">
                Comment: <span style="text-align: center">{{$appInfo->process_desc_from_dd}}</span>

            </div>
        </div>


    @endforeach
</div>