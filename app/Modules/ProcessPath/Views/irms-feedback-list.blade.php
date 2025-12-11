@extends('layouts.admin')
@section('content')
    <?php
        $moduleName = Request::segment(1);
        $user_type = CommonFunction::getUserType();
        $desk_id_array = explode(',', \Session::get('user_desk_ids'));
        $accessMode = "V";
        if (!ACL::isAllowed($accessMode, 'V'))
            die('no access right!');

    ?>
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="col-lg-12">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
                    
                    @if(Session::has('irms_feedback_tracking_number') && !empty(Session::get('irms_feedback_tracking_number')))
                      <div class="alert alert-danger" role="alert">
                      You have an investment support form. Please submit your updated investment information. Unless submitting the form within the deadline, you may unable to submit others services.
                      </div>
                    @endif

                </div>
                <div class="col-lg-12">
                    <div class="panel panel-info" style="">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><i class="fa fa-list"></i> <strong>List of Applications</strong></h5>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0px 5px;">
                                <ul class="nav nav-tabs">

                                    <li id="tab1" class="active">
                                        <a data-toggle="tab" href="#list_desk" class="mydesk" aria-expanded="true">
                                            <b>List</b>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">

                                    <div id="list_desk" class="tab-pane active" style="margin-top: 20px">
                                        <table aria-label="Detailed Report IRMS" id="table_desk" class=" table table-striped table-bordered display" style="width: 100%">
                                            <thead>
                                            <tr>
                                                <th style="width: 15%;" >Tracking ID</th>
                                                <th style="width: 15%;">Service</th>
                                                <th style="width: 15%;">Submit Before</th>
                                                <th style="width: 15%;">Last Status</th>
                                                <th style="width: 35%">Application Info</th>
                                                <th>Remarks</th>
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
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    @include('partials.datatable-scripts')
    <script language="javascript">
        $(function () {

            /**
             * Disable Datatable warning thrown
             * this code will disable the warning message of all datatable's from current page.
             * N.B : For debugging purposes, need to do a console log for each data table since warning will disable.
             */
            $.fn.dataTable.ext.errMode = 'none';

            $('.ProcessType').change(function () {
                var process_type_id = $(this).val();
                sessionStorage.setItem("process_type_id", process_type_id);
            });
            $('.ProcessType').trigger('change');
            

            /**
             * table desk script
             * @type {jQuery}
             */
            table_desk = $('#table_desk').DataTable({
                iDisplayLength: 50,
                processing: true,
                serverSide: true,
                // responsive: true,
                ajax: {
                    url: '{{route("process.getIrmsList")}}',
                    method: 'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.companyIds = '{{ $companyIds }}';
                        d.userType = '{{ $userType }}';
                    }
                },
                columns: [

                    {data: 'tracking_no', name: 'tracking_no', searchable: true},
                    {data: 'Service', name: 'Service', searchable: false},
                    {data: 'feedback_deadline', name: 'feedback_deadline', searchable: true},
                    {data: 'irms_status_id', name: 'irms_status_id', searchable: true},
                    {data: 'json_data', name: 'json_data', searchable: true},
                    {data: 'remarks', name: 'remarks', searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": [],
            }).on('error.dt', function (e, settings, techNote, message) {
                console.log('An error has been reported by DataTables: ', message);
            });

        });

    </script>
    <script>
        function checkPosition() {
            if(window.innerWidth < 768){
                $('.table').addClass("dt-responsive");
            }
        }
        checkPosition();
    </script>
    @yield('footer-script2')
@endsection
