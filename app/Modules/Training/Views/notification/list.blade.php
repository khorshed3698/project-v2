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
                        <b> <i class="fa fa-list"></i> {!! trans('Training::messages.notification') !!} </b>
                    </div>
                    <div class="pull-right">
                        @if(TrACL::getAccsessRight('TrainingNotification','A'))
                            <a class="" href="{{ url('/training/notification/add-notification') }}">
                                {!! Form::button('<i class="fa fa-plus"></i> <b> '.trans('Training::messages.create_notification').'  </b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
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
                                <th>{!! trans('Training::messages.tracking_no') !!}</th>
                                <th>{!! trans('Training::messages.course_name') !!}</th>
                                <th>{!! trans('Training::messages.date') !!}</th>
                                <th>{!! trans('Training::messages.notice_subject') !!}</th>
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
                url: '/training/notification/get-notification-list',
                method: 'get',
            },
            columns: [
                {data: 'tracking_no', name: 'tracking_no'},
                {data: 'course_title', name: 'course_title'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'subject', name: 'subject'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            "aaSorting": []
        });
    </script>

@endsection <!--- footer-script--->

