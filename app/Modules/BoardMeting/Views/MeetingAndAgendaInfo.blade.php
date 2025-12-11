<div class="panel panel-info">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4  col-md-offset-1 "
                 style="border-bottom: 1px solid #c7bebe">
                <label style="font-size: 15px;color: #478fca;padding: 0px"
                       for="infrastructureReq" class="text-success col-md-6">Board Meeting
                    Info
                </label>
                <br>
            </div>
            <div class="col-md-4 col-md-offset-1 " style="border-bottom: 1px solid #c7bebe">
                <label style="font-size: 15px;color: #478fca;">Agenda Info</label>
            </div>
        </div>
        <div class="col-md-12">

            <div class="col-md-4  col-md-offset-1">
                <table aria-label="Detailed Report Data Table">
                    <tr>
                        <th aria-hidden="true"  scope="col"></th>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">Board
                            meeting no. :
                        </td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            &nbsp;&nbsp; {{$boardMeetingInfo['board_meeting_info']->meting_number}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">Board
                            Meeting Date:
                        </td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            {{date("d M Y", strtotime($boardMeetingInfo['board_meeting_info']->meting_date))}}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                            Location:
                        </td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            &nbsp;&nbsp;{{$boardMeetingInfo['board_meeting_info']->location}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                            Status:
                        </td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            &nbsp;&nbsp;<button class="btn btn-xs btn-{{$boardMeetingInfo['board_meeting_info']->panel}}">{{$boardMeetingInfo['board_meeting_info']->status_name}}</button>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="col-md-5 col-md-offset-1">
                <table aria-label="Detailed Report Data Table">
                    <tr>
                        <th aria-hidden="true"  scope="col"></th>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #5f5f5f;">Agenda Name: :</td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->name}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #5f5f5f;">Description:</td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->description}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #5f5f5f;">Process Type:</td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            &nbsp;&nbsp; {{$boardMeetingInfo['agenda_info']->process_name}}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #5f5f5f;">Status:</td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            &nbsp;@if($boardMeetingInfo['agenda_info']->status_name == '')
                                <label class="btn btn-warning btn-xs">Pending</label>
                            @else
                                <label class="btn btn-{{$boardMeetingInfo['agenda_info']->panel}} btn-xs">{{$boardMeetingInfo['agenda_info']->status_name}}</label>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #5f5f5f;">Remarks:</td>
                        <td style="font-size: 13px;color: #5f5f5f;">
                            <span>{!! isset($boardMeetingInfo['agenda_info']->remarks)  !!}</span>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>

<div class="panel panel-info">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-9  col-md-offset-1 "
                 style="border-bottom: 1px solid #c7bebe">
                <label
                        for="infrastructureReq" class="text-success col-md-6">
                    <span style="font-size: 16px;color: #478fca;padding: 0px">Chairman Remarks:</span> {{isset($boardMeetingInfo['chairmanRemarks']->bm_remarks)?$boardMeetingInfo['chairmanRemarks']->bm_remarks:''}}
                </label>
                <br>
            </div>
        </div>
    </div>
</div>