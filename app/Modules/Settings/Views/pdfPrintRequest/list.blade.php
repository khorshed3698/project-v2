@extends('layouts.admin')

@section('page_heading',trans('messages.pdf-print-requests'))

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'PPR-ESQ')) {
        die('You have no access right! Please contact system admin for more information');
    }
    ?>

    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">

                <div class="pull-left">
                    <h5>
                        <strong>
                            <i class="fa fa-list"></i>
                            <strong>{!!trans('messages.pdf-print-requests')!!}</strong>
                        </strong>
                    </h5>
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
                                <th style="width: 20%">Tracking No.</th>
                                <th>Certificate</th>
                                <th style="width: 12%">Sending status</th>
                                <th style="width: 13%">Sending no of try</th>
                                <th style="width: 12%">Receiving status</th>
                                <th style="width: 13%">Receiving no of try</th>
                                <th style="width: 10%">Prepared JSON</th>
                                <th style="width: 20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div id="list_search" class="tab-pane" style="margin-top: 20px">
                        @include('Settings::pdfPrintRequest.search')
                    </div>
                </div>

            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->

@endsection

@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

    @include('partials.datatable-scripts')
    <script language="javascript">

        $('.list').click(function () {
            listTable.ajax.reload();
        });

        $(function () {
            listTable = $('#list').DataTable({
                iDisplayLength: 10,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '{{url("settings/get-pdf-print-requests")}}',
                    method: 'POST',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'certificate_link', name: 'certificate_link'},
                    {data: 'job_sending_status', name: 'job_sending_status'},
                    {data: 'no_of_try_job_sending', name: 'no_of_try_job_sending'},
                    {data: 'job_receiving_status', name: 'job_receiving_status'},
                    {data: 'no_of_try_job_receving', name: 'no_of_try_job_receving'},
                    {data: 'prepared_json', name: 'prepared_json'},
                    {data: 'action', name: 'action', orderable: true, searchable: true}
                ],
                "aaSorting": []
            });
        });
    </script>
    @yield('footer-script2')
@endsection
