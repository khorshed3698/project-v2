<div style="page-break-after:always;">
    <div class="text-center">
        <h5>{{ !empty($dataRow->agenda_name) ? $dataRow->agenda_name : '' }}</h5>
        <h6>{{ !empty($dataRow->type) ? $dataRow->type : '' }}.
            {{ !empty($dataRow->agenda_heading_title) ? $dataRow->agenda_heading_title : '' }}</h6>
    </div>
    <table class="table table-bordered" aria-label="Detailed Report Data Table">
        <thead>
            <tr>
                <th width="3%">{{ !empty($getTableHeadingName[1]) ? $getTableHeadingName[1] : '' }}</th>
                <th width="31%">{{ !empty($getTableHeadingName[2]) ? $getTableHeadingName[2] : '' }}</th>
                <th width="23%">{{ !empty($getTableHeadingName[3]) ? $getTableHeadingName[3] : '' }}</th>
                <th width="10%">{{ !empty($getTableHeadingName[4]) ? $getTableHeadingName[4] : '' }}</th>
                <th width="10%">{{ !empty($getTableHeadingName[5]) ? $getTableHeadingName[5] : '' }}</th>
                <th width="23%">{{ !empty($getTableHeadingName[6]) ? $getTableHeadingName[6] : '' }}</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $i = 1;
            $getApplicationData = \App\Libraries\CommonFunction::getAgendaWiseApplicationData($dataRow->agenda_name, $dataRow->type, $meeting_id, $dataRow->process_type_id);
            ?>

            @if (count($getApplicationData) > 0)

                @foreach ($getApplicationData as $rowAppData)
                    <?php
                    $getProjectOfficeData = \App\Modules\ProjectOfficeNew\Controllers\ProjectOfficeNewController::getProjectOfficeOtherData($dataRow->process_type_id, $rowAppData->ref_id);
                    ?>

                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>
                            @if (isset($getProjectOfficeData['companiesOffice']))
                                @foreach ($getProjectOfficeData['companiesOffice'] as $row)
                                    {{ $row->c_company_name }},
                                @endforeach

                                Project Office
                                <br>
                            @endif
                            <b>Address:</b>

                            @if (isset($getProjectOfficeData['companiesOffice']))
                                @foreach ($getProjectOfficeData['companiesOffice'] as $row)
                                    <br>
                                    <u><b>{{ $row->c_company_name }}<b /></u>
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
                        <td>
                            <ol type="i">
                                @if (isset($getProjectOfficeData['companiesOffice']))
                                    @foreach ($getProjectOfficeData['companiesOffice'] as $row)
                                        <li>{{ $row->c_major_activity_brief }}</li>
                                    @endforeach
                                @endif
                            </ol>
                        </td>
                        <td>
                            {{ !empty($rowAppData->approved_desired_duration) ? $rowAppData->approved_desired_duration : '' }}<br>
                            e.w.f<br>
                            {{ !empty($rowAppData->approved_duration_start_date)
                                ? date('Y-m-d', strtotime($rowAppData->approved_duration_start_date))
                                : '' }}
                        </td>
                        <td></td>
                        <td>{{ isset($rowAppData->process_desc_from_dd) && !empty($rowAppData->process_desc_from_dd)
                            ? $rowAppData->process_desc_from_dd
                            : '' }}
                        </td>
                    </tr>
                @endforeach

            @endif
        </tbody>
    </table>
</div>
