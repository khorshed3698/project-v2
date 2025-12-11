@extends('layouts.admin')

@section('header-resources')
    @include('partials.datatable-css')
@endsection

@section('content')
    @include('partials.messages')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="pull-left">
                        <b> <i class="fa fa-list"></i> {!! trans('Training::messages.trainingAttendance') !!} </b>
                    </div>
                    <div class="pull-right">
                        @if(TrACL::getAccsessRight('TrainingAttendance','A'))
                            <a class="" href="{{ url('training/attendance/create') }}">
                                {!! Form::button('<i class="fa fa-plus"></i> <b>'.trans('Training::messages.attendance').' </b> </b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>{!! trans('Training::messages.schedule_id') !!}</th>
                                <th>{!! trans('Training::messages.course_id') !!}</th>
                                <th>{!! trans('Training::messages.course_title') !!}</th>
                                <th>{!! trans('Training::messages.day') !!}</th>
                                <th>{!! trans('Training::messages.time') !!}</th>
                                <th>{!! trans('Training::messages.place') !!}</th>
                                <th>{!! trans('Training::messages.status') !!}</th>
                                <th>{!! trans('Training::messages.action') !!}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div><!-- /.table-responsive -->
                </div><!-- /.panel-body -->
            </div>

        </div><!-- /.col-lg-12 -->
    </div>


@endsection <!--content section-->

@section('footer-script')
    @include('partials.datatable-js')
    <script>


        $('#list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: '/training/attendance/get-schedule-list',
                method: 'get',
            },
            columns: [
                {data: 'master_tracking_no', name: 'master_tracking_no'},
                {data: 'course_tracking_no', name: 'course_tracking_no'},
                {data: 'course_title', name: 'course_title'},
                {data: 'session_days', name: 'session_days'},
                {data: 'time', name: 'time'},
                {data: 'venue', name: 'venue'},
                {data: 'is_publish', name: 'is_publish', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });
    </script>

@endsection <!--- footer-script--->

