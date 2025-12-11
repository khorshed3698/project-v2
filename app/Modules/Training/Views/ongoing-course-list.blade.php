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
                        <b> <i class="fa fa-list"></i> {!! trans('Training::messages.list_of_ongoing_course') !!} </b>
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
                                <th>{!! trans('Training::messages.course_title') !!}</th>
                                <th>{!! trans('Training::messages.batch_no') !!}</th>
                                <th>{!! trans('Training::messages.course_coordinator') !!}</th>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_type == '10x112'
                                        || Illuminate\Support\Facades\Auth::user()->user_type == '4x404'
                                        || Illuminate\Support\Facades\Auth::user()->user_type == '5x505')
                                <th>{!! trans('Training::messages.status') !!}</th>
                                <th>{!! trans('Training::messages.action') !!}</th>
                                @endif
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


        if ( '{{ \Illuminate\Support\Facades\Auth::user()->user_type }}' == '10x112' || '{{ \Illuminate\Support\Facades\Auth::user()->user_type }}' == '5x505'|| '{{ \Illuminate\Support\Facades\Auth::user()->user_type }}' == '4x404'){
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                responsive: true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: '/training/ongoing-course/get-ongoing-course-list',
                    method: 'get',
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'course_title', name: 'course_title'},
                    {data: 'batch_name', name: 'batch_name'},
                    {data: 'user_first_name', name: 'user_first_name'},
                    {data: 'status', name: 'status', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        }else {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                responsive: true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: '/training/ongoing-course/get-ongoing-course-list',
                    method: 'get',
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'course_title', name: 'course_title'},
                    {data: 'batch_name', name: 'batch_name'},
                    {data: 'user_first_name', name: 'user_first_name'}
                ],
                "aaSorting": []
            });
        }

    </script>

@endsection <!--- footer-script--->

