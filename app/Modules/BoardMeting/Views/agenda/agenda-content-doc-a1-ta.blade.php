<br style="page-break-before: always">
<div style="text-align: center" class="text-center">
    <span style="font-weight: bold">{{ !empty($dataRow->agenda_name) ? $dataRow->agenda_name : '' }}</span><br>
    <span
        style="font-weight: bold">{{ !empty($dataRow->type) ? $dataRow->type : '' }}.{{ !empty($dataRow->agenda_heading_title) ? $dataRow->agenda_heading_title : '' }}</span>
</div>
<table style="border-collapse: collapse;" aria-label="Detailed Report Agenda 1 Type A">
    <thead>
        <tr>
            <th style="">{{ !empty($getTableHeadingName[1]) ? $getTableHeadingName[1] : '' }}</th>
            <th style="">{{ !empty($getTableHeadingName[2]) ? $getTableHeadingName[2] : '' }}</th>
            <th style="">{{ !empty($getTableHeadingName[3]) ? $getTableHeadingName[3] : '' }}</th>
            <th style="">{{ !empty($getTableHeadingName[4]) ? $getTableHeadingName[4] : '' }}</th>
            <th style="">{{ !empty($getTableHeadingName[5]) ? $getTableHeadingName[5] : '' }}</th>
            <th style="">{{ !empty($getTableHeadingName[6]) ? $getTableHeadingName[6] : '' }}</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $i = 1;
        $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
        ?>
        @if (count($getApplicationData) > 0)
            @foreach ($getApplicationData as $rowData)
                <?php
                    $getProjectOfficeData = \App\Modules\ProjectOfficeNew\Controllers\ProjectOfficeNewController::getProjectOfficeOtherData($dataRow->process_type_id, $rowData->ref_id);
                ?>

                <tr>
                    <td style="">{{ $i++ }}</td>
                    <td style="">
                        @if (isset($getProjectOfficeData['companiesOffice']))
                                @foreach ($getProjectOfficeData['companiesOffice'] as $row)
                                    {{ $row->c_company_name }},
                                @endforeach

                                Project Office
                                <br>
                        @endif
                        <span style="font-weight: bold">Address:</span>

                        @if (isset($getProjectOfficeData['companiesOffice']))
                            @foreach ($getProjectOfficeData['companiesOffice'] as $row)
                                <br>
                                <u><span style="font-weight: bold">{{ $row->c_company_name }}</span></u>
                                <br>
                                {{ !empty($row->ref_app_tracking_no) ? '(ID # ' . $row->ref_app_tracking_no . '),' : '' }}
                                {{ $row->c_flat_apart_floor }} ,
                                {{ $row->c_house_plot_holding }} ,
                                {{ $row->c_street }} ,
                                {{ $row->c_post_zip_code }}
                                <br>
                                Country of
                                origin:{{ !empty($row->c_origin_country_name) ? $row->c_origin_country_name : '' }}
                            @endforeach
                        @endif

                    </td>
                    <td style="">
                        <ol type="i">
                            @if (isset($getProjectOfficeData['companiesOffice']))
                                @foreach ($getProjectOfficeData['companiesOffice'] as $row)
                                    <li>{{ $row->c_major_activity_brief }}</li>
                                @endforeach
                            @endif
                        </ol>
                    </td>
                    <td style="">
                        {{ !empty($rowData->approved_desired_duration) ? $rowData->approved_desired_duration : '' }}<br>
                        e.w.f<br>
                        {{ !empty($rowData->approved_duration_start_date)
                            ? date('Y-m-d', strtotime($rowData->approved_duration_start_date))
                            : '' }}
                    </td>
                    <td style=""></td>
                    <td style="">
                        {{ isset($rowData->process_desc_from_dd) && !empty($rowData->process_desc_from_dd) ? $rowData->process_desc_from_dd : '' }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
