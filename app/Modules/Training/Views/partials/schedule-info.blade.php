<?php

use App\Modules\Training\Libraries\TrCommonFunction;$scheduleInfo = TrCommonFunction::getScheduleInfo($sessionId);

$date1 = date_create($scheduleInfo->course_duration_start);
$date2 = date_create($scheduleInfo->course_duration_end);

$difference = date_diff($date1, $date2)->format('%m months and %d days');
$expirationDurationUnit = [
    '' => '',
    'day' => 'দিন',
    'month' => 'মাস',
];
?>
@include('partials.datatable-css')

<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="pull-left" style="padding-top: 7px">
            <b> <i class="fa fa-list"></i> {!! trans('Training::messages.training_schedule') !!} </b>
        </div>
        <div class="clearfix"></div>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="col-lg-12">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('reg_type_id', trans('Training::messages.course_name'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                <span>: {{ $scheduleInfo->course_title }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('reg_type_id', trans('Training::messages.batch_no'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                <span>: {{ $scheduleInfo->batch_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('speaker_email', trans('Training::messages.course_duration'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                :<span class="input_ban"> {{ $scheduleInfo->duration }} </span><span>{{ !empty($scheduleInfo->duration_unit)?$expirationDurationUnit[$scheduleInfo->duration_unit]:"" }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('speaker_mobile', trans('Training::messages.no_of_class/session'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                :<span class="input_ban"> {{ $scheduleInfo->no_of_class  }} টি</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('enrolment_deadline', trans('Training::messages.application_deadline'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                :<span class="input_ban"> {{ date('d-m-Y', strtotime($scheduleInfo->enroll_deadline)) }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('expected_starting_date', trans('Training::messages.course_start_end'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                :<span class="input_ban"> {{ date('d-m-Y', strtotime($scheduleInfo->course_duration_start)) }} থেকে {{ date('d-m-Y', strtotime($scheduleInfo->course_duration_end)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('course_location', trans('Training::messages.course_location'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                <span>: {{ $scheduleInfo->venue }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('course_fee', trans('Training::messages.course_fee2'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7">
                                @if($scheduleInfo->fees_type == 'paid')
                                    :
                                    <span class="input_ban"> {{ ($scheduleInfo->amount != null)?$scheduleInfo->amount:'' }}</span>
                                @else
                                    :<span class="input_ban text-success"> বিনামূল্যে</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('qualifications', trans('Training::messages.qualifications'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7" style="overflow:scroll; height:200px;">
                                <span>: {!! $scheduleInfo->qualifications_exp !!} </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            {!! Form::label('course_outline', trans('Training::messages.course_outline'),
                            ['class'=>'col-md-5 col-xs-5']) !!}
                            <div class="col-md-7 col-xs-7" style="overflow:scroll; height:200px;">
                                <span>: {!! $scheduleInfo->course_contents !!} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(\Illuminate\Support\Facades\Auth::user()->desk_training_ids ==1)
            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4>
                            প্রশিক্ষণের সেশনসমূহ
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="sessionTable" class="table table-striped table-bordered dt-responsive nowrap"
                                   cellspacing="0"
                                   width="100%">
                                <thead style="background: #35AA47">
                                <tr>
                                    <th class="text-center">{!!trans('Training::messages.session_time')!!}</th>
                                    <th class="text-center">{!!trans('Training::messages.day')!!}</th>
                                    <th class="text-center">{!!trans('Training::messages.application')!!}</th>
                                    <th class="text-center">{!!trans('Training::messages.seat_capacity')!!}</th>
                                    <th class="text-center">{!!trans('Training::messages.speaker_name')!!}</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        @endif

        <div class="col-md-12">
            <a class="pull-left" href="{{ url('/training/schedule') }}">

                {!! Form::button('<i class="fa fa-times"></i> '.trans('Training::messages.close'), array('type' => 'button', 'class' => 'btn btn-sm btn-default')) !!}
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @if(TrACL::getAccsessRight('Training','E'))
                @if(\Illuminate\Support\Facades\Auth::user()->desk_training_ids == 2)
                    @if($scheduleInfo->status !='completed')
                        <a class="pull-right"
                           href="/training/schedule/edit/{{\App\Libraries\Encryption::encodeId($scheduleInfo->id)}}">
                            {!! Form::button('<i class="fa fa-edit"></i> '. trans('Training::messages.do_edit'), array('type' => 'button', 'class' => 'btn btn-primary btn-sm')) !!}
                        </a>
                    @endif
                @endif

                @if(\Illuminate\Support\Facades\Auth::user()->desk_training_ids == 1)
                    <div class="pull-right">
                        @if($scheduleInfo->is_publish != 'Publish')
                            <a href="/training/schedule/edit/{{\App\Libraries\Encryption::encodeId($scheduleInfo->id)}}">
                                {!! Form::button('<i class="fa fa-edit"></i> '. trans('Training::messages.do_edit'), array('type' => 'button', 'class' => 'btn btn-primary btn-sm')) !!}
                            </a>

                            <button type="button" class="btn btn-sm btn-success"
                                    value="{{\App\Libraries\Encryption::encodeId($scheduleInfo->id)}}"
                                    onclick="approveSchedule(this.value)"><i
                                        class="fa fa-check"></i> {{trans('Training::messages.approve')}}
                            </button>
                        @endif
                    </div>
                @endif
            @endif
        </div>

    </div><!-- /.box -->
</div>
<script type="text/javascript" src="{{ asset("assets/plugins/jquery/jquery.min.js") }}"></script>
@include('partials.datatable-js')
<script>
    function approveSchedule(value) {
        $.ajax({
            type: "POST",
            url: "<?php echo url('/training/schedule-approve'); ?>",
            data: {
                id: value,
            },
            success: function (response) {
                if (response.responseCode == 1) {
                    sessionStorage.setItem("showmsg", "1");
                    window.location.href = '/training/schedule';
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    }

    $(document).ready(function () {
        $('#sessionTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: '/training/schedule/get-session-list',
                method: 'get',
                data: {scheduleId: '{{$scheduleInfo->id}}'},
            },
            columns: [
                {data: 'time', name: 'time'},
                {data: 'session_days', name: 'session_days'},
                {data: 'applicant_limit', name: 'applicant_limit'},
                {data: 'seat_capacity', name: 'seat_capacity'},
                {data: 'speaker_name', name: 'speaker_name', searchable: false}
            ],
            "aaSorting": []
        });
    })


</script>
