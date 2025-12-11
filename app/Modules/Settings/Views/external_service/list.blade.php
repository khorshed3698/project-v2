@extends('layouts.admin')

@section('page_heading',trans('messages.stakeholder_list'))

@section('content')
    <?php $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'V')) die('no access right!');
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>External Service List</strong></strong></h5>
                </div>
                <div class="pull-right">
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type Key</th>
                            <th>ACL Name</th>
                            <th>Process Key</th>
                            <th>Table Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
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
                    url: '{{ url("/settings/get-external-service-list") }}',
                    method: 'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'type_key', name: 'type_key'},
                    {data: 'acl_name', name: 'acl_name'},
                    {data: 'process_key', name: 'process_key'},
                    {data: 'table_name', name: 'table_name'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });

        });
    </script>
@endsection