@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'V')) {
        die('You have no access right! Please contact system admin for more information');
    }
    ?>
    @include('partials.messages')

    <div class="col-lg-12">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> {!! trans('messages.doc_list') !!}</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="" href="{{ url('/settings/create-document') }}">
                            {!! Form::button('<i class="fa fa-plus"></i> <b>'.trans('messages.new_document').' </b>', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="panel-body">
                <div class="table-responsive">
                    <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th>Document Name</th>
                            <th width="10%">Process Name</th>
                            <th width="15%">Attachment Type</th>
                            <th width="15%">Business Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th width="5">Order</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        @endsection

        @section('footer-script')
            @include('partials.datatable-scripts')
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <script>
                $(function () {
                    var t = $('#list').DataTable({
                        iDisplayLength: 10,
                        processing: true,
                        serverSide: true,
                        searching: true,
                        order: [0, 'desc'],
                        ajax: {
                            url: '{{url("settings/get-document-data")}}',
                            method: 'POST',
                            data: function (d) {
                                d._token = $('input[name="_token"]').val();
                            }
                        },
                        columns: [
                            {data: 'id', name: 'id' },
                            {data: 'doc_name', name: 'doc_name'},
                            {data: 'process_name', name: 'process_name'},
                            {data: 'attachment_type', name: 'attachment_type'},
                            {data: 'business_category', name: 'business_category'},
                            {data: 'doc_priority', name: 'doc_priority'},
                            {data: 'status', name: 'status'},
                            {data: 'order', name: 'order'},
                            {data: 'action', name: 'action', orderable: true, searchable: true}
                        ],
                        "aaSorting": []
                    });
                    t.on('order.dt search.dt', function () {
                        t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();
                });
            </script>
@endsection

