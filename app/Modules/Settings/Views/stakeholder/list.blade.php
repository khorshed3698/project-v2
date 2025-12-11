@extends('layouts.admin')

@section('page_heading',trans('messages.stakeholder_list'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.stakeholder_list')!!}</strong></strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-stakeholder') }}">
                            {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_stakeholder').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}

                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Department</th>
                            <th>Service</th>
                            <th>Stakeholder name & designation</th>
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
                    url: '{{url("settings/get-stakeholder-data")}}',
                    method:'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'department_name', name: 'department_name'},
                    {data: 'service_name', name: 'service_name'},
                    {data: 'name_designation', name: 'name_designation'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });

        });
    </script>
@endsection