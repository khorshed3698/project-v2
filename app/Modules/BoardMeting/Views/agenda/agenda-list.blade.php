@extends('layouts.admin')
@section('content')
    <?php $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('no access right!');
    }
    ?>

    {{--@include('BoardMeting::progress-bar')--}}

    <div class="col-lg-12">
        @include('message.message')

        {{--board meeting info--}}
        <div class="panel panel-info">
            <div class="panel-heading" id="meetingBoard" style="display: none; margin-bottom: 20px;">
                <div class="row">
                    @include('BoardMeting::board-meeting-info')

                    <div class="col-md-12">
                        <div class="panel panel-info">


                            <div class="panel-heading">
                                <div class="pull-left" style="line-height: 35px;">
                                    <strong><i class="fa fa-list"></i> {{ trans('messages.committeemembers') }}</strong>
                                </div>
                                <div class="pull-right">
                                    <!--1x101 is Sys Admin, 7x712 is SB Admin, 11x422 is Bank Admin-->
                                    @if(!in_array($board_meeting_data->status,[5,10]))
                                        @if(ACL::getAccsessRight('BoardMeting','A'))
                                            <a class="" href="{{ url('/board-meting/committee/'.$board_meeting_id) }}">
                                                {!! Form::button('<i class="fa fa-plus"></i> <b> ' .trans('messages.new_committee').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                                            </a>
                                        @endif
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="committeeList" class="table table-striped table-bordered dt-responsive "
                                           cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
                                        <thead>
                                        <tr>
                                            <th>{!! trans('messages.meeting_member_name') !!}</th>
                                            <th>{!! trans('messages.member_designation') !!}</th>
                                            <th>{!! trans('messages.member_email') !!}</th>
                                            <th>{!! trans('messages.member_mobile') !!}</th>
                                            <th>{!! trans('messages.member_type') !!}</th>
                                            <th>{!! trans('messages.created_at') !!}</th>
                                            <th>{!! trans('messages.action') !!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->
                                <a href="{{ url('board-meting/lists') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-circle-left"></i>
                                    Back to meeting list
                                </a>
                            </div><!-- /.panel-body -->
                        </div><!--/col-md-12-->
                    </div>
                </div>
            </div>
            {{--board meeting info Start--}}
            <div id="meetingBoard" style="display: none">
                @include('BoardMeting::board-meeting-info')

            </div>
            {{--board meeting info end--}}
            {{--list of committee end--}}
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="pull-left" style="line-height: 35px;">
                        <strong><i class="fa fa-list"></i> {{ trans('messages.agenda_list') }}</strong>
                    </div>
                    <div class="pull-right">

                        @if(in_array($board_meeting_data->status,[10]))
                            {{-- <a href="/{{$board_meeting_data->meeting_minutes_path}}" download=""
                               class="btn btn-info btn-sm"><i class="fa fa-download"></i> Meeting minutes PDF </a> --}}
                            <a href="/board-meting/minutes/pdf-download/{{Encryption::encodeId($board_meeting_data->id)}}"
                               class="btn btn-info btn-sm"><i class="fa fa-download"></i> Meeting minutes PDF </a>
                            <a href="/board-meting/minutes/doc-download/{{Encryption::encodeId($board_meeting_data->id)}}"
                               class="btn btn-md btn-success btn-sm"><i class="fa fa-download"></i> Meeting minutes DOC </a>
                        @endif
                        {{--<a class="" href="{{ url('/board-meting/agenda/download/'.$board_meeting_id) }}">--}}
                        {{--{!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.agenda_download').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}--}}
                        {{--</a>--}}
                        <a href="{{ url('/board-meting/agenda/download/'.$board_meeting_id) }}">
                            {!! Form::button('<i class="fa fa-download"></i> Agenda PDF', array('type' => 'button', 'class' => 'btn btn-default btn-sm')) !!}
                        </a>
                        <a href="{{ url('/board-meting/agenda/doc-download/'.$board_meeting_id) }}">
                            {!! Form::button('<i class="fa fa-download"></i> Agenda DOC', array('type' => 'button', 'class' => 'btn btn-warning btn-sm')) !!}
                        </a>
                        <a href="{{ url('/board-meting/agenda/excel-download/'.$board_meeting_id) }}">
                            {!! Form::button('<i class="fa fa-download"></i> Agenda Excel', array('type' => 'button', 'class' => 'btn btn-info btn-sm')) !!}
                        </a>

                        <button type="button" class="btn btn-primary btn-sm meeting boardmeet">
                            <i class="fa fa-arrow-up" aria-hidden="true"></i> Meeting Member
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                {!! Form::open(array('url' => '/board-meting/agenda/update-remarks','method' => 'post', 'class' => 'form-horizontal', 'id'=>'update_remarks', 'role' => 'form')) !!}
                <div class="panel-body">
                    <div class="panel panel-info " id="commonProcessBM" style="display:none; background: #bce8f1" >

                        <div class="panel-body">
                            <div class="col-md-1">

                            </div>
                            @if (count($chairmen) > 0)
                                @if (Auth::user()->user_email == $chairmen->user_email)
                                    <div class="col-md-3">
                                        <label>Apply Status</label>
                                        {!! Form::select('bm_status_id', $status, null, array('class'=>'form-control',
                                        'placeholder' => 'Select Status', 'id'=>"bm_status_id")) !!}
                                        @if($errors->first('bm_status_id'))
                                            <span class="control-label">
                                                                        <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('bm_status_id','') }}</em>
                                                                    </span>
                                        @endif
                                    </div>
                                @endif
                            @endif
                            <div class="col-md-4">
                                <label>Remarks</label>
                                <textarea style="height: 50px;" id="remarksAll"
                                          placeholder="Write your remark here for all selected process..."
                                          class="form-control" rows="7"
                                          name="remarks"></textarea>
                            </div>


                            <div class="col-md-4">

                                {!! Form::button('<i class="fa fa-save"></i> Process', array('type' => 'submit', 'value'=> 'Process', 'class' => 'btn save_remarks  btn-primary','style'=>'padding: 9px 20px;margin-top:25px')) !!}
                            </div>
                        </div>

                    </div>
                    <div class="clearfix">
                        {{--@if(!empty($desk_id_array[0]) || $user_type=="1x101" || $user_type=="4x404")--}}
                        <div class="" id="statuswiseAppsDiv">
                            @include('BoardMeting::agenda.service-wise-app')
                        </div>
                        {{--@endif--}}
                    </div>
                    <div>


                        <div class="table-responsive">
                            <br>
                            <table id="board_meting" class="table table-striped table-bordered "
                                   cellspacing="0"
                                   width="100%" aria-label="Detailed Report Data Table">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" class="select_all"></th>
                                    <th >Tracking No.</th>
                                    <th >Organization</th>
                                    <th>Service</th>
                                    <th style="width: 45%">Decision</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div>

                </div><!-- /.panel -->
                {!! Form::close() !!}

            </div>



        </div>

        {{--        <div class="col-md-12"><br><br></div>--}}

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{!! trans('messages.all_remarks') !!}</h4>
                    </div>
                    <div class="modal-body">


                        <div class="table-responsive">
                            <table class="table table-striped display"
                                   style="width: 100%" aria-label="Detailed Report Data Table">
                                <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>{!! trans('messages.remarks') !!}</th>
                                </tr>
                                </thead>
                                <tbody class="remarkView">
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
        @endsection <!--content section-->
        @section('footer-script')
            @include('Users::partials.datatable')

            <script type="text/javascript">

                $(document).ready(function () {
                    process_list_array = [];
                    board_meeting_array = [];

                    //select all checkboxes
                    $(".select_all").click(function () {  //"select all" change
                        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
                        if ($(this).is(":checked")) {
                            $('.checkbox:checked').each(function (i, obj) {
                                board_meeting_array.push(this.value);
                            });
                        } else {
                            board_meeting_array = [];
                        }

                        if (board_meeting_array.length > 0) {
                            $("#commonProcessBM").toggle('250');
                        } else {
                            $("#commonProcessBM").slideUp();
                        }
                        // console.log(board_meeting_array)
                    });

                    $('body').on('click', '.checkbox', function (e) {
                        var process_id = $(this).val();
                        if ($(this).is(":checked")) {
                            board_meeting_array.push(process_id);
                            console.log(board_meeting_array);
                        } else {
                            board_meeting_array = jQuery.grep(board_meeting_array, function (value) {
                                return value != process_id;
                            });
                            // console.log(board_meeting_array);
                        }
                        if (board_meeting_array.length > 0) {
                            // $("#commonProcessBM").toggle(250);
                            $("#commonProcessBM").slideDown();
                            // $('#commonProcessBM').removeClass('hidden');
                        } else {
                            $("#commonProcessBM").slideUp();
                        }
                    });

                    $('.save_remarks').on('click', function () {
                        var status = $('#bm_status_id').val();
                        if (status == '') {
                            toastr.error('Please select a status !!');
                            return false;
                        }

                        $("#update_remarks").submit(function () {
                            // console.log(board_meeting_array);
                            if (board_meeting_array.length === 0) {
                                toastr.error('<b>Please select a process from process list</b>');
                                return false
                            }
                            var status = $('#bm_status_id').val();
                            var remarksAll = $('#remarksAll').val();
                            if (status == 8) {
                                if (remarksAll == '') {
                                    toastr.error('Please enter your remarks !!');
                                    return false;
                                }

                            }
                            if (status == 13) {
                                if (remarksAll == '') {
                                    toastr.error('Please enter your remarks !!');
                                    return false;
                                }
                            }
                            if (status == 17) { //Conditional Approved
                                if (remarksAll == '') {
                                    toastr.error('Please enter your remarks !!');
                                    return false;
                                }
                            }
                        });


                    });

                })


                $('body').on('click', '.individual_action_save', function () {

                    var process_list_id = $(this).val();
                    var find_remarks_class = 'remark_' + process_list_id;
                    var remarks = $('.' + find_remarks_class).val();

                    //new code 26 august 2019
                    var find_start_date_class = 'approved_duration_start_date_' + process_list_id;
                    var approved_duration_start_date = $('.' + find_start_date_class).val();
                    var is_approved_duration = $('.' + find_start_date_class).attr('data-id');
                    // console.log(approved_duration_start_date);


                    var find_end_date_class = 'approved_duration_end_date_' + process_list_id;
                    var approved_duration_end_date = $('.' + find_end_date_class).val();
                    var is_duration_end_date = $('.' + find_end_date_class).attr('data-id');
                    // console.log(approved_duration_end_date);

                    var desired_duration_class = 'approved_desired_duration_' + process_list_id;
                    var approved_desired_duration = $('.' + desired_duration_class).val();
                    var is_desired_duration = $('.' + desired_duration_class).attr('data-id');
                    // console.log(approved_desired_duration);

                    var duration_amount_class = 'approved_duration_amount_' + process_list_id;
                    var approved_duration_amount = $('.' + duration_amount_class).val();
                    var is_duration_amount = $('.' + duration_amount_class).attr('data-id');
                    // console.log(approved_duration_amount);


                    if(is_approved_duration == 'hidden'){
                        approved_duration_start_date = ''
                    }
                    if(is_duration_end_date == 'hidden'){
                        approved_duration_end_date = ''
                    }

                    if(is_desired_duration == 'hidden'){
                        approved_desired_duration = ''
                    }
                    if(is_duration_amount == 'hidden'){
                        approved_duration_amount = ''
                    }

                    //end new code

                    var find_status_class = 'status_for_' + process_list_id;
                    var status = $('.' + find_status_class).val();
                    if (status == 8) {
                        if (remarks == '') {
                            toastr.error('Please enter your remarks !!');
                            return false;
                        }
                    }
                    if (status == 13) {
                        if (remarks == '') {
                            toastr.error('Please enter your remarks !!');
                            return false;
                        }
                    }
                    if (status == 17) { //Conditional Approved
                        if (remarks == '') {
                            toastr.error('Please enter your remarks !!');
                            return false;
                        }
                    }
                    // if (remarks == '') {
                    //     toastr.error('Please enter your remarks !!');
                    //     return false;
                    // }

                    var _token = $('input[name="_token"]').val();
                    btn = $(this);
                    btn_content = btn.html();
                    btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('/'); ?>/board-meting/agenda/process/save-individual-action",
                        data: {
                            _token: _token,
                            process_list_id: process_list_id,
                            remarks: remarks,
                            bm_status_id: status,
                            board_meeting_id: '{{$board_meeting_id}}',

                            approved_duration_start_date: approved_duration_start_date,
                            approved_duration_end_date: approved_duration_end_date,
                            approved_desired_duration: approved_desired_duration,
                            approved_duration_amount: approved_duration_amount,
                        },
                        success: function (response) {
                            btn.html(btn_content);
                            if (response.responseCode == 1 && response.is_complete == 0) {
                                toastr.success('Your remarks updated Successfully!!');
                                board_meting.ajax.reload();
                            }
                            if (response.is_complete == 1 && response.is_final == 1) {
                                window.setTimeout(function () {
                                    toastr.success('Your Meeting approved successfully!!');
                                    location.reload(true);
                                }, 300);
                            }
                            if (response.is_complete == 1) {
                                toastr.success('Your agenda approved successfully!!');
                                board_meting.ajax.reload();
                            }

                            if (response.is_complete == 2) {
                                toastr.success('Someting want to wrong!!    !!');
                            }
                        }
                    });
                });

                function viewRemarks(bm_process_id) {

                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        type: "post",
                        url: "<?php echo url(); ?>/board-meting/agenda-process-remarks",
                        data: {
                            _token: _token,
                            bm_process_id: bm_process_id
                        },
                        success: function (response) {
                            if (response.responseCode == 1) {
                                var html = '';
                                if (response.data.length > 0) {
                                    $.each(response.data, function (id, value) {
                                        var UserType = '';
                                        if (value.chairman == 1) {
                                            UserType = 'Chairman';
                                        } else {
                                            UserType = 'Member';
                                        }
                                        html += '<div class="row">' +
                                            '<div class="col-md-3">' +
                                            '<center class="m10">' +
                                            '<img src="/users/upload/' + value.user_pic + '" class="img-circle img-responsive" alt="User Image">' +
                                            '<h6 class="label label-danger">' + UserType + '</h6><br>' +
                                            '<h6 class="label label-success">' + value.user_full_name + '</h6>' +
                                            '</center></div>' +
                                            '<div class="col-md-9"><blockquote>' +
                                            '<span>' + value.remarks + '</span>' +
                                            '<footer>' + value.user_email + '</footer></blockquote>' +
                                            '</div>' +
                                            '</div>';
                                    });
                                } else {
                                    html += "<center>No Remarks Found</center>";
                                }

                                $('.modal-body').html(html);
                            }
                        }
                    });
                }

                function confirmFixed() {
                    toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure you want to be fixed the meeting?',
                        {
                            closeButton: true,
                            allowHtml: true,
                            timeOut: 0,
                            extendedTimeOut: 0,
                            positionClass: "toast-top-center",
                            onShown: function (toast) {
                                $("#confirmationRevertYes").click(function () {

                                    var _token = $('input[name="_token"]').val();
                                    var board_meeting_id = '{{$board_meeting_id}}';
                                    btn = $('.fixed_meeting');
                                    btn_content = btn.html();
                                    btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

                                    $.ajax({
                                        type: "get",
                                        url: "<?php echo url(); ?>/board-meting/fixed-meeting",
                                        data: {
                                            _token: _token,
                                            board_meeting_id: board_meeting_id
                                        },
                                        success: function (response) {
                                            if (response.responseCode == 1) {
                                                btn.html(btn_content);
                                                toastr.success('Meeting fixed successfully!!');
                                                window.setTimeout(function () {
                                                    location.reload();
                                                }, 800);

                                            }
                                        }
                                    });
                                });
                            }
                        });
                }

                $('.processWiseApplication').on("click",function (e,auto) {

                    if(auto == 'auto'){
                        var process_type_id = null;
                    }else{
                        var process_type_id = $(this).attr("data-id");
                    }
                    board_meting = $('#board_meting').DataTable({
                        "fnDrawCallback": function() {

                            var dd_startDateDivID = 'datetimepicker6';
                            var dd_startDateValID = 'approved_duration_start_date';
                            var dd_endDateDivID = 'datetimepicker7';
                            var dd_endDateValID = 'approved_duration_end_date';
                            var dd_show_durationID = 'approved_desired_duration';
                            var dd_show_amountID = 'approved_duration_amount';
                            var dd_show_yearID = 'approved_duration_year';


                            $("."+dd_endDateDivID).datetimepicker({
                                viewMode: 'days',
                                format: 'DD-MMM-YYYY',
                            });

                            $("."+dd_startDateDivID).datetimepicker({
                                viewMode: 'days',
                                format: 'DD-MMM-YYYY',
                            });
                            $("."+dd_startDateDivID).on("dp.change", function (e) {
                                var startDateVal = $( this ).children("#approved_duration_start_date").val();
                                obj = $(this).parent().parent().parent();

                                var process_id = obj.find('#process_type_id').val();
                                var approved_desired_duration = $(this).parent().parent().parent().next().find('#approved_desired_duration');
                                var approved_duration_amount = $(this).parent().parent().parent().next().find('#approved_duration_amount');
                                var approved_duration_year = $(this).parent().parent().parent().parent().find('#approved_duration_year');
                                // console.log(startDateVal)

                                if (startDateVal != '') {
                                    // Min value set for end date
                                    // $("."+dd_endDateDivID).data("DateTimePicker").minDate(e.date);
                                    // var endDateVal = $("#"+dd_endDateValID).val();
                                    obj3 = $(this).parent().parent().parent();
                                    obj3.find('#datetimepicker7').data("DateTimePicker").minDate(e.date);
                                    // return false
                                    var endDateVal = obj3.find('#approved_duration_end_date').val();
                                    if (endDateVal != '') {
                                        var  is_from_board_meeting = 1;
                                        getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID,
                                            dd_show_amountID, dd_show_yearID, is_from_board_meeting, approved_desired_duration, approved_duration_amount, approved_duration_year);
                                    } else {
                                        $("#"+dd_endDateValID).addClass('error');
                                    }
                                } else {
                                    // $("#"+dd_show_durationID).val('');
                                    // $("#"+dd_show_amountID).val('');
                                    // $("#"+dd_show_yearID).text('');
                                }
                            });

                            $("."+dd_endDateDivID).on("dp.change", function (e) {
                                obj = $(this).parent().parent().parent();
                                var process_id = obj.find('#process_type_id').val();
                                // Max value set for start date
                                obj3 = $(this).parent().parent().parent().parent();
                                // $("."+dd_startDateDivID).data("DateTimePicker").maxDate(e.date);
                                obj3.find('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
                                var startDateVal = obj3.find('#approved_duration_start_date').val();

                                // var approved_desired_duration = $(this).parent().parent().parent();
                                var approved_desired_duration = $(this).parent().parent().parent().next().find('#approved_desired_duration');
                                var approved_duration_amount = $(this).parent().parent().parent().next().find('#approved_duration_amount');
                                var approved_duration_year = $(this).parent().parent().parent().next().find('#approved_duration_year');
                                if (startDateVal === '') {
                                    $("."+dd_startDateValID).addClass('error');
                                } else {
                                    var day = moment(startDateVal, ['DD-MMM-YYYY']);
                                    //var minStartDate = moment(day).add(1, 'day');
                                    $("."+dd_endDateDivID).data("DateTimePicker").minDate(day);
                                }
                                var endDateVal = $( this ).children("#approved_duration_end_date").val();

                                if (startDateVal != '' && endDateVal != '') {
                                    var  is_from_board_meeting = 1;
                                    getDesiredDurationAmount(process_id, startDateVal, endDateVal, dd_show_durationID,
                                        dd_show_amountID, dd_show_yearID,is_from_board_meeting, approved_desired_duration, approved_duration_amount, approved_duration_year);
                                }else{
                                    // $("#"+dd_show_durationID).val('');
                                    // $("#"+dd_show_amountID).val('');
                                    // $("#"+dd_show_yearID).text('');
                                }
                            });
                        },
                        destroy: true,
                        iDisplayLength: 25,
                        lengthMenu: [ 10, 25, 50 ],
                        processing: true,
                        serverSide: true,
                        async: false,

                        ajax: {
                            {{--url: '{{route("process.getStatusList")}}',--}}
                            url: '{{route("board-meting.agendaWiseBoardMetingNew",['-1000','boardMeting'])}}',
                            method: 'get',
                            data: function (d) {
                                d._token = $('input[name="_token"]').val();
                                d.board_meeting_id = '{{Encryption::encodeId($board_meeting_data->id)}}',
                                    d.process_type_id = process_type_id
                            }
                        },
                        columns: [
                            {data: 'select_btn', name: 'select_btn', orderable: false, searchable: false},
                            {data: 'tracking_no', name: 'tracking_no', searchable: true},
                            {data: 'company_name', name: 'company_name', searchable: true},
                            {data: 'process_name', name: 'process_name', searchable: false},
                            {data: 'decision', name: 'decision', searchable: false},
                        ],
                        "aaSorting": []
                    });




                });

                $('.processWiseApplication').trigger('click',['auto']);

            </script>

            <script>
                $(function () {
                    var board_id = '{{$board_meeting_id}}';
                    committeeList = $('#committeeList').DataTable({
                        processing: true,
                        serverSide: true,

                        ajax: {
                            url: '{{url("board-meting/committee/get-data")}}',
                            method: 'post',
                            data: function (d) {
                                d.board_meting_id = board_id;
                                d._token = $('input[name="_token"]').val();

                            }
                        },
                        columns: [
                            {data: 'user_name', name: 'user_name'},
                            {data: 'designation', name: 'designation'},
                            {data: 'user_email', name: 'user_email'},
                            {data: 'user_mobile', name: 'user_mobile'},
                            {data: 'type', name: 'type'},
                            {data: 'created_at', name: 'created_at'},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
                        ],
                        "aaSorting": []
                    });
                });

                function deleteMember(member_id) {
                    toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure you want to delete?',
                        {
                            closeButton: true,
                            allowHtml: true,
                            timeOut: 0,
                            extendedTimeOut: 0,
                            positionClass: "toast-top-center",
                            onShown: function (toast) {
                                $("#confirmationRevertYes").click(function () {
                                    var _token = $('input[name="_token"]').val();
                                    $.ajax({
                                        type: "post",
                                        url: "<?php echo url(); ?>/board-meting/committee/deleteMember",
                                        data: {
                                            _token: _token,
                                            member_id: member_id
                                        },
                                        success: function (response) {
                                            if (response.responseCode == 1) {
                                                committeeList.ajax.reload();
                                            }
                                        }
                                    });
                                });
                            }
                        });
                }


                /*code hasan*/
                $(document).ready(function () {
                    $('.boardmeet').on('click', function (e) {
                        if ($('#meetingBoard').is(":visible")) {

                            $('.boardmeet').find('i').removeClass("fa-arrow-down fa");
                            $('.boardmeet').find('i').addClass("fa fa-arrow-up");
                            $(".boardmeet").css("background-color", "");
                            $(".boardmeet").css("color", "");
                        } else {
                            $(this).find('i').removeClass("fa fa-arrow-up");
                            $(this).find('i').addClass("fa fa-arrow-down");
                            $(".boardmeet").css("background-color", "#1abc9c");
                            $(".boardmeet").css("color", "white");
                        }
                        $('#meetingBoard').slideToggle();
                    });
                });
                /*code hasan*/
            </script>

    @endsection <!--- footer-script--->

