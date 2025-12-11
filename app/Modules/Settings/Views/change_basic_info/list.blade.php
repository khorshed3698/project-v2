@extends('layouts.admin')

@section('content')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>Change Basic Information</strong></strong></h5>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table aria-label="Change Basic Information" id="list" class="table table-striped table-bordered dt-responsive" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Tracking No.</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Last modified</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                responsive:true,
                ajax: {
                    url: '{{url("settings/get-change-basic-info-list-data")}}',
                    method:'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'data', name: 'data'},
                    {data: 'status_id', name: 'status_id'},
                    {data: 'last_modified', name: 'last_modified'},
                    {data: 'action', name: 'action'},
                ],
                "aaSorting": []
            });
        });
    </script>
@endsection