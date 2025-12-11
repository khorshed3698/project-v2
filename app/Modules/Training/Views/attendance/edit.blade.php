@extends('layouts.admin')

@section('page_heading',trans('messages.area_form'))
@section('header-resources')
    @include('partials.datatable-css')
@endsection
@section('content')
    <?php $accessMode = TrACL::getAccsessRight('Training');
    if (!ACL::isAllowed($accessMode, 'A')) die('no access right!');
    ?>
    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>  {!! trans('Training::messages.attendance') !!} </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => url('/training/store-course'),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'course-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                <br>
                <div class="col-md-11 col-md-offset-1">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8 {{$errors->has('attendance_date') ? 'has-error': ''}}">

                                {!! Form::label('attendance_date',trans('Training::messages.attendanceDate'), ['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                    {!! Form::text('attendance_date', date('d-M-Y', strtotime($data->class_date)), ['class' => 'bigInputField form-control required']) !!}
                                        <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                    {!! $errors->first('attendance_date','<span class="help-block">:message</span>') !!}
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('tr_course_id', trans('Training::messages.course_title'),
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('tr_course_id') ? 'has-error': ''}}">
                                    {!! Form::select('tr_course_id', $courses, $data->tr_course_id, ['class' =>'form-control input-md required','placeholder'=> trans("Training::messages.select"),'id'=>'tr_course_id']) !!}
                                    {!! $errors->first('tr_course_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('tr_schedule_master_id', trans('Training::messages.batch'),
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('tr_batch_id') ? 'has-error': ''}}">
                                    {!! Form::select('tr_schedule_master_id', [], '', ['class' => 'form-control required', 'placeholder' => 'Select One','id'=>'batch']) !!}

                                    {!! $errors->first('tr_schedule_master_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('tr_schedule_session_id', trans('Training::messages.classSession'),
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('tr_batch_id') ? 'has-error': ''}}">
                                    {!! Form::select('tr_schedule_session_id', [], '', ['class' => 'form-control required', 'placeholder' => 'Select One','id'=>'classSession']) !!}

                                    {!! $errors->first('tr_schedule_session_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>





                </div>

                <div class="col-md-12 col-md-offset-4">
                    <a href="{{ url('/training/attendance') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    @if(TrACL::getAccsessRight('Training','A'))
                        <button type="button" class="btn btn-primary" id="filter">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    @endif
                    <br><br>
                </div><!-- /.box-footer -->



            {!! Form::close() !!}<!-- /.form end -->
            </div><!-- /.box -->
        </div>
        <div class="panel panel-primary allperticipant" style="display: none">
            <div class="panel-heading">
                <b>  {!! trans('Training::messages.listOfPerticipations') !!} </b>
            </div>
            <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="table-responsive">
                <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>{!! trans('Training::messages.name') !!}</th>
                        <th>{!! trans('Training::messages.phone_no') !!}</th>
                        <th>{!! trans('Training::messages.email') !!}</th>
                        <th>{!! trans('Training::messages.status') !!}</th>
                        <th>{!! trans('Training::messages.action') !!}</th>
                    </tr>
                    </thead>
                    <tbody id="list1">




                    </tbody>
                </table>
            </div><!-- /.table-responsive -->

           <center> <button class='present btn btn-success' value='PresentAll' id="presentAll">Present All</button>

           <a href="{{ url('/training/attendance') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>&nbsp;&nbsp;&nbsp; <center>


        </div><!-- /.panel-body -->
        </div>
    </div>

@endsection


@section('footer-script')
    <script src="{{ asset("assets/scripts/moment.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/datepicker-oss/js/bootstrap-datetimepicker.min.js") }}"></script>
    <script src="{{ asset("assets/plugins/bootstrap-daterangepicker/daterangepicker.js") }}"></script>
    @include('partials.datatable-js')

    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var att_status;
        $(document).ready(function () {



            $("#course-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });

            $('.datepicker').datetimepicker({
                format: 'DD-MMM-YYYY',
            });

            $(".datepicker").on("dp.change", function() {
                $("#classSession").trigger("change");
            });

                  var t =  $('#list').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "ordering": false,
                    "info": false,
                    "autoWidth": false,
                    "iDisplayLength": 25,
                      "columnDefs": [ {
                          "targets": -1,
                          // "data": null,
                          // "defaultContent": "<button class='present btn-sm btn-success' value='Present'>Present</button> <button class='absent btn-sm btn-danger' value='Absent'>Absent</button>"
                      },
                          { "data": "user_first_name" },
                          { "data": "user_mobile" },
                          { "data": "user_email" },
                          { "data": "is_present", name: 'my-column' },
                          { "data": "action" },
                      ],

                });


            $("#tr_course_id").change(function () {
                $("#classSession").trigger("change");
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                var courseId = $('#tr_course_id').val();
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/training/get-batch-by-course-id'); ?>",
                    data: {
                        courseId: courseId
                    },
                    success: function (response) {
                        console.log(response.data);

                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            var scheduleId = "{{$data->tr_schedule_master}}"
                            var selected = "";
                            $.each(response.data, function (id, value) {
                                if(scheduleId == value.id){
                                    selected = "selected";
                                }
                                option += '<option '+selected+' value="' + value.id + '">' + value.batch_name + '</option>';
                            });
                        }
                        $("#batch").html(option);
                        self.next().hide();
                        $("#batch").trigger('change');
                    }
                });
            });

            $("#tr_course_id").trigger('change');


            $("#batch").change(function () {
                $("#classSession").trigger("change");
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                var trScheduleMasterId = $('#batch').val();
                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");
                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/training/get-course-by-trScheduleMasterId'); ?>",
                    data: {
                        trScheduleMasterId: trScheduleMasterId
                    },
                    success: function (response) {

                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            var tr_schedule_session_id = "{{$data->tr_schedule_session_id}}"
                            $.each(response.data, function (id, value) {
                                var selected = "";
                                if(tr_schedule_session_id == id){
                                    selected = "selected";
                                }
                                option += '<option  '+selected+' value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#classSession").html(option);
                        self.next().hide();
                        $("#filter").trigger('click');
                    }
                });
            });
                $("#filter").click(function () {

                t.clear().draw();



                var self = $(this);
                var courseId = $('#tr_course_id').val();
                var attendanceDate = $('#attendance_date').val();
                var trScheduleMasterId = $('#batch').val();
                var classSessionId = $('#classSession').val();

                    toastr.options = {"positionClass": "toast-bottom-right"}
                    if (courseId == '') {
                        toastr.error('Please fillup required fields');
                        return false;
                    }

                    if (attendanceDate == '') {
                        toastr.error('Please fillup required fields');
                        return false;
                    }

                    if (trScheduleMasterId == '') {
                        toastr.error('Please fillup required fields');
                        return false;
                    }
                    if (classSessionId == '') {
                        toastr.error('Please fillup required fields');
                        return false;
                    }


                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/training/get-participants-by-scheduleSessionId'); ?>",
                    data: {
                        attendanceDate: attendanceDate,
                        classSessionId: classSessionId,
                        courseId: courseId,
                        trScheduleMasterId: trScheduleMasterId,
                    },
                    success: function (response) {
                        $('.allperticipant').show();
                        console.log(response.data);

                        var html = '';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                var input = $('<input type="button" value="new button" />');
                                if (value.is_present == null){
                                    value.is_present = "";
                                }

                                t.row.add( [
                                    value.user_first_name ,
                                    value.user_mobile ,
                                    value.user_email ,
                                    '<span class="is_present_' + id + '">' + value.is_present + '</span>',
                                    "<button class='present btn-sm btn-success' value='Present_" + id + "' incdata=" + id + ">Present</button> <button class='absent btn-sm btn-danger' value='Absent_" + id + "'  incdata=" + id + ">Absent</button>",
                                ] ).draw( true );

                                // $('#list').DataTable().row.add($('<tr> <td>' + value.user_first_name + '</td> <td>' + value.user_phone  + '</td><td>' + value.user_email + '</td><td>Action</td></tr>)).draw();
                            });

                        }
                        // $('#list').DataTable().data.reload().draw();
                        self.next().hide();
                    }
                });
            });

            $('#list tbody').on( 'click', '.present', function () {
                var data = t.row( $(this).parents('tr') ).data();
                // t.row($(this).closest('tr')).remove().draw();
                $(this).parent().find('.absent').remove();
                // alert( data[0] +"'present for: "+ data[ 2 ] );
                var text = $(this).parent().find('.present').val();
                var myArray = text.split("_");
                var self = $(this);

                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");

                var courseId = $('#tr_course_id').val();
                var attendanceDate = $('#attendance_date').val();
                var trScheduleMasterId = $('#batch').val();
                var classSessionId = $('#classSession').val();
                var attStatus = myArray[0];
                var userEmail = data[ 2 ];

                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/training/attendance-entry'); ?>",
                    data: {
                        attendanceDate: attendanceDate,
                        attStatus: attStatus,
                        classSessionId: classSessionId,
                        courseId: courseId,
                        trScheduleMasterId: trScheduleMasterId,
                        userEmail: userEmail,
                    },
                    success: function (response) {
                        if (response.responseCode == 1) {
                           // alert(response.data);
                            // $(this).parent().find('.is_present').html('Present');
                            // var colIndex = t.cell(this).index().column;
                            var rowIndex = t.row(this).index();
                            console.log(rowIndex);
                            // t.cell(myArray[1], 3).data("Present")
                            $(".is_present_" + myArray[1]).html("Present");

                            // console.log(rowIndex);
                            //
                            // $( cell.node() ).addClass('my-class');

                             att_status = 'Present';

                        }

                        self.next().hide();
                    }
                });


            } );





            $('#list tbody').on( 'click', '.absent', function () {
                var data = t.row( $(this).parents('tr') ).data();
                // t.row($(this).closest('tr')).remove().draw();
                $(this).parent().find('.present').remove();
                // alert( data[0] +"'present for: "+ data[ 3 ] );
                var text = $(this).parent().find('.absent').val();
                var myArray = text.split("_");
                var self = $(this);

                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");

                var courseId = $('#tr_course_id').val();
                var attendanceDate = $('#attendance_date').val();
                var trScheduleMasterId = $('#batch').val();
                var classSessionId = $('#classSession').val();
                var attStatus = myArray[0];
                var userEmail = data[ 2 ];
                // alert(attendanceDate);

                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/training/attendance-entry'); ?>",
                    data: {
                        attendanceDate: attendanceDate,
                        attStatus: attStatus,
                        classSessionId: classSessionId,
                        courseId: courseId,
                        trScheduleMasterId: trScheduleMasterId,
                        userEmail: userEmail,
                    },
                    success: function (response) {
                        if (response.responseCode == 1) {
                            // alert(response.data);
                            // $(this).parent().find('.is_present').html('Absent');
                            var rowIndex = t.cell(this).index();
                            // console.log(rowIndex);

                            // t.cell( myArray[1], 3).data("Absent")
                            $(".is_present_" + myArray[1]).html("Absent");
                        }
                        self.next().hide();
                    }
                });
            } );


        $(document).on( 'click', '#presentAll', function () {
            $('#presentAll').prop('disabled', true);

            $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");
            var self = $(this);
            var courseId = $('#tr_course_id').val();
            var attendanceDate = $('#attendance_date').val();
            var trScheduleMasterId = $('#batch').val();
            var classSessionId = $('#classSession').val();

            $.ajax({
                type: "POST",
                url: "<?php echo url('/training/attendance-entry-all'); ?>",
                data: {
                    attendanceDate: attendanceDate,
                    classSessionId: classSessionId,
                    courseId: courseId,
                    trScheduleMasterId: trScheduleMasterId,
                },
                success: function (response) {
                    if (response.responseCode == 1) {

                    }
                    $('#presentAll').prop('disabled', false);
                    self.next().hide();
                    $("#filter").trigger("click");
                }
            });
        } );

        });
    </script>

    <style>
        input[type="radio"].error {
            outline: 1px solid red
        }
    </style>
@endsection <!--- footer script--->
