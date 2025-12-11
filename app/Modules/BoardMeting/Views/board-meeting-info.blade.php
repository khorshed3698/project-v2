<div class="panel-body">
    <div class="col-md-12" style="background-color: #1abc9c;">
        <br>
        <table aria-label="Detailed Report Data Table">
            <tr>
                <th aria-hidden="true"  scope="col"></th>
            </tr>
            <tr>
                <td style="width:220px; font-weight: bold;font-size: 14px; color: white;">
                    <i  class="fa fa-info-circle" ></i> {!! trans('messages.meeting_no') !!}. :
                </td>
                <td style="width:200px;font-size: 13px;color: white;">
                    &nbsp;&nbsp; {{$board_meeting_data->meting_number}}<sup>th</sup></td>

                <td style="width:250px;font-weight: bold;font-size: 14px; color:  white;">
                    <i  class="fa fa-calendar-o"></i> {!! trans('messages.meeting_date') !!}:
                </td>
                <td style="width:200px;font-size: 13px;color:  white;">
                    {{date("d M Y", strtotime($board_meeting_data->meting_date))}}
                    &nbsp;&nbsp;</td>

                <td style="width:200px;font-weight: bold;font-size: 14px; color:  white;">
                    <i  class="fa fa-map-marker"></i> {!! trans('messages.meeting_places') !!}:
                </td>
                <td style="width:200px;font-size: 13px;color:  white">
                    &nbsp;&nbsp;{{$board_meeting_data->location}}</td>
            </tr>

        </table>
        <br>
    </div>

</div>