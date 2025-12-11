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
                <b>  {!! trans('Training::messages.evaluation') !!} </b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => url('/training/evaluation-entry-all'),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'marks-info',
               'role' => 'form')) !!}
                <br>
                <div class="col-md-11 col-md-offset-1">

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('evaluation_type', trans('Training::messages.evaluation_type'),
                                ['class'=>'col-md-5 required-star']) !!}
                                <div class="col-md-7 {{$errors->has('evaluation_type') ? 'has-error': ''}}">
                                    {!! Form::select('evaluation_type', $evaluation_type, $data->evaluation_type, ['class' =>'form-control input-md required','placeholder'=> trans("Training::messages.select"),'id'=>'evaluation_type']) !!}
                                    {!! $errors->first('evaluation_type','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8 {{$errors->has('attendance_date') ? 'has-error': ''}}">

                                {!! Form::label('evaluation_date',trans('Training::messages.attendanceDate'), ['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-7">
                                    <div class="datepicker input-group date">
                                    {!! Form::text('evaluation_date', date('d-M-Y', strtotime($data->evaluation_date)), ['class' => 'bigInputField form-control required']) !!}
                                        <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                    {!! $errors->first('evaluation_date','<span class="help-block">:message</span>') !!}
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
                    <a href="{{ url('/training/evaluation') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    @if(TrACL::getAccsessRight('Training','A'))
                        <button type="button" class="btn btn-primary" id="filter">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                    @endif
                    <br><br>
                </div><!-- /.box-footer -->

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
                        <th>{!! trans('Training::messages.marks') !!}</th>
                    </tr>
                    </thead>
                    <tbody id="list1">




                    </tbody>
                </table>
            </div><!-- /.table-responsive -->

           <center>
               <button class="btn btn-primary" type="button" id="markstAll">
                   <i class="fa fa-chevron-circle-right"></i> Submit All Marks
               </button>
           {!! Form::close() !!}<!-- /.form end -->






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
                      ],

                });

            $("#evaluation_type").change(function () {
                $("#classSession").trigger("change");
            });


            // $(".datepicker").change(function () {
            //     $("#classSession").trigger("change");
            // });




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
                        console.log(response.data);

                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            var tr_schedule_session_id = "{{$data->tr_schedule_session_id}}"
                            $.each(response.data, function (id, value) {
                                var selected = "";
                                if(tr_schedule_session_id == id){
                                    selected = "selected";
                                }
                                option += '<option '+selected+' value="' + id + '">' + value + '</option>';
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
                var evaluation_date = $('#evaluation_date').val();
                var trScheduleMasterId = $('#batch').val();
                var classSessionId = $('#classSession').val();
                var evaluationType = $('#evaluation_type').val();

                toastr.options = {"positionClass": "toast-bottom-right"}
                if (courseId == '') {
                    toastr.error('Please fillup required fields');
                    return false;
                }

                if (evaluation_date == '') {
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
                if (evaluationType == '') {
                    toastr.error('Please fillup required fields');
                    return false;
                }


                $('.allperticipant').show();


                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/training/evaluation/get-participants-by-scheduleSessionId'); ?>",
                    data: {
                        evaluation_date: evaluation_date,
                        classSessionId: classSessionId,
                        courseId: courseId,
                        evaluationType: evaluationType,
                        trScheduleMasterId: trScheduleMasterId,
                    },
                    success: function (response) {
                        console.log(response.data);

                        var html = '';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
console.log(id);
                                if (value.marks == null){
                                    value.marks = "";
                                }

                                t.row.add( [
                                    value.user_first_name ,
                                    value.user_mobile ,
                                    value.user_email ,
                                    "<input type='hidden' name='usid[" + value.userid + "]' value='" + value.userid + "' class='usid_" + id + "'/> <input type='text' value='" + value.marks + "' name='marks[" + value.userid + "]' class='form-control input_ban exammarks_" + id + "' incdata=" + id + "/>  <button class='marksupdate btn-sm btn-success' value='Present_" + id + "' incdata=" + id + ">Update</button>",
                                ] ).draw( true );

                                // $('#list').DataTable().row.add($('<tr> <td>' + value.user_first_name + '</td> <td>' + value.user_phone  + '</td><td>' + value.user_email + '</td><td>Action</td></tr>)).draw();
                            });

                        }
                        // $('#list').DataTable().data.reload().draw();
                        self.next().hide();
                    }
                });
            });

            $('#list tbody').on( 'click', '.marksupdate', function () {
                var text = $(this).parent().find('.marksupdate').val();
                var myArray = text.split("_");
                var trid = myArray[ 1 ];


                var self = $(this);


                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");

                var courseId = $('#tr_course_id').val();
                var evaluationDate = $('#evaluation_date').val();
                var trScheduleMasterId = $('#batch').val();
                var classSessionId = $('#classSession').val();
                var evaluationType = $('#evaluation_type').val();
                var marks = $('.exammarks_'+ trid).val();
                var userId = $('.usid_'+ trid).val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/training/evaluation-entry'); ?>",
                    data: {
                        evaluationDate: evaluationDate,
                        marks: marks,
                        evaluationType: evaluationType,
                        classSessionId: classSessionId,
                        courseId: courseId,
                        trScheduleMasterId: trScheduleMasterId,
                        userId: userId,
                    },
                    success: function (response) {
                        if (response.responseCode == 1) {
                            $(".is_present_" + myArray[1]).html("Present");
                        }

                        self.next().hide();
                        $("#filter").trigger("click");
                        // $('.exammarks_'+ trid).prop('readonly', true);
                    }
                });



            } );

            $(document).on( 'click', '#markstAll', function () {

                $("#loaderImg").html("<img style='margin-top: -15px;' src='<?php echo url('/public/assets/images/ajax-loader.gif'); ?>' alt='loading' />");

                var courseId = $('#tr_course_id').val();
                var evaluationDate = $('#evaluation_date').val();
                var trScheduleMasterId = $('#batch').val();
                var classSessionId = $('#classSession').val();
                var evaluationType = $('#evaluation_type').val();


                var givenMarks = $('input[name^=marks]').map(function(idx, elem) {
                    return $(elem).val();
                }).get();
                // console.log(titles);

                var usid = $('input[name^=usid]').map(function(idx, elem) {
                    return $(elem).val();
                }).get();

                // console.log(usid);

                var columns = usid;
                var rows = givenMarks;

                var result =  rows.reduce(function(result, field, index) {
                    result[columns[index]] = field;
                    return result;
                }, {})

                console.log(result);

                $.ajax({
                    type: "POST",
                    url: "<?php echo url('/training/evaluation-entry-all'); ?>",
                    data: {
                        evaluationDate: evaluationDate,
                        evaluationType: evaluationType,
                        classSessionId: classSessionId,
                        courseId: courseId,
                        trScheduleMasterId: trScheduleMasterId,
                        marksAll: result,
                    },
                    success: function (response) {

                        $("#filter").trigger("click");
                        // $('.exammarks_'+ trid).prop('readonly', true);
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
