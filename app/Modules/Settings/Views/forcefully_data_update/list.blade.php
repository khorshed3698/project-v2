@extends('layouts.admin')

@section('page_heading',trans('messages.forcefully_data_update'))
@section('style')
    <style>
        body, html {
            overflow-x: unset !important;
        }
    </style>
@endsection
@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
    ?>

    <div class="modal fade" id="forcefullyDataUpdateModal" tabindex="-1" role="dialog" aria-labelledby="forcefullyDataUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>
    
    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> <strong>{!!trans('messages.forcefully_data_update')!!}</strong></strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a data-toggle="modal" data-target="#forcefullyDataUpdateModal" data-backdrop="static" data-keyboard="false"
                           onclick="openForcefullyDataUpdateModal(this)" data-action="{{ url('/settings/create-forcefully-data-update') }}">
                            {!! Form::button('<i class="fa fa-plus"></i><b> ' .trans('messages.new_forcefully_data_update').'</b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Tracking No.</th>
                            <th>Data</th>
                            <th>Update type</th>
                            <th>Table name</th>
                            <th>Status</th>
                            <th>Last modified</th>
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
                responsive:true,
                ajax: {
                    url: '{{url("settings/get-forcefully-data-update-data")}}',
                    method:'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'data', name: 'data'},
                    {data: 'update_type', name: 'update_type'},
                    {data: 'table_name', name: 'table_name'},
                    {data: 'status_id', name: 'status_id'},
                    {data: 'last_modified', name: 'last_modified'},
                    {data: 'action', name: 'action'},
                ],
                "aaSorting": []
            });
        });

        function openForcefullyDataUpdateModal(btn) {
            //e.preventDefault();
            var this_action = btn.getAttribute('data-action');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        $('#forcefullyDataUpdateModal .load_modal').html(data);
                    } else {
                        $('#forcefullyDataUpdateModal .load_modal').html('Unknown Error!');
                    }
                    // $('#forcefullyDataUpdateModal').modal('show', {backdrop: 'static'});
                    $('#forcefullyDataUpdateModal').modal('show', {backdrop: 'static', keyboard: false});
                });
            }
        }


    </script>
@endsection