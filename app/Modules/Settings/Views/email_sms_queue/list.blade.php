@extends('layouts.admin')

@section('page_heading',trans('messages.email_sms_queue'))
@section('style')
    <style>
        body, html {
            overflow-x: unset;
        }
    </style>
@endsection

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'PPR-ESQ')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><i class="fa fa-list"></i> <strong>{!! trans('messages.email_sms_queue') !!}</strong></h5>
                </div>

                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0px 5px;">
                    <ul  class="nav nav-tabs">
                        <li id="tab1" class="active">
                            <a data-toggle="tab" href="#list_table" class="list" aria-expanded="true">
                                <b>List</b>
                            </a>
                        </li>

                        <li id="tab2" class="">
                            <a data-toggle="tab"  href="#list_search" aria-expanded="false">
                                <b>Search</b>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">

                    <div id="list_table" class="table-responsive tab-pane active" style="margin-top: 20px;">
                        <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Tracking No.</th>
                                <th>Caption</th>
                                <th>Email Status</th>
                                <th>SMS Status</th>
                                <th>Sent On</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div id="list_search" class="tab-pane" style="margin-top: 20px">
                        {{--                        //include a search page--}}
                        @include('Settings::email_sms_queue.search')
                    </div>
                </div>
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div>

@endsection

@section('footer-script')

    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                iDisplayLength: 25,
                pageLength: 25,
                ajax: {
                    url: '{{url("settings/email-sms-queue/list")}}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'caption', name: 'caption'},
                    {data: 'email_status', name: 'email_status'},
                    {data: 'sms_status', name: 'sms_status'},
                    {data: 'sent_on', name: 'sent_on'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });

            $('#table_search').hide();
            var search_list = '';

            $('#search_process').click(function () {
                getPrintRequestsData();
            });

            $("form input").keydown(function (e) {
                if(e.keyCode == 13) {
                    e.preventDefault();
                    getPrintRequestsData();
                }
            });

            function getPrintRequestsData() {
                $('#table_search').show();

                $('#table_search').DataTable({
                    destroy: true,
                    iDisplayLength: 10,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: '{{url("settings/email-sms-search-list")}}',
                        method: 'get',
                        data: {
                            'search_text': $('.search_text').val()
                        }
                    },
                    columns: [
                        {data: 'tracking_no', name: 'tracking_no'},
                        {data: 'caption', name: 'caption'},
                        {data: 'email_status', name: 'email_status'},
                        {data: 'sms_status', name: 'sms_status'},
                        {data: 'sent_on', name: 'sent_on'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    "aaSorting": []
                });
            }
        });
    </script>
@endsection
