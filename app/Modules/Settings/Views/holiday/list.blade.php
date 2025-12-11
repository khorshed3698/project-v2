@extends('layouts.admin')

@section('page_heading',trans('messages.holiday_list'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
    ?>
    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.holiday_list')!!}</strong></strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-holiday') }}">
                            {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_holiday').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}

                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')
    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("settings/get-holiday-data")}}',
                    method:'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'holiday_date', name: 'holiday_date'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });

        });
    </script>
@endsection