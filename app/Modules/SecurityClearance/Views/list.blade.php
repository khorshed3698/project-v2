@extends('layouts.admin')
@section('content')
    <style>
        * {
            font-weight: normal;
        }
        .statusBox{
            float: left;
            width: 120px;
            margin: 5px 3px;
            height: 80px;
        }
        .statusBox-inner {
            padding: 3px !important;
            font-weight: bold !important;
            height: 100%;
        }
        .dataerror{
            border: 1px solid #ebccd1;
            padding: 10px;
            border-radius: 5px;
            color: #a94442;
            background-color: #f2dede;
            margin-bottom: 10px !important;
        }
        .datasuccess{
            border: 1px solid #d6e9c6;
            padding: 10px;
            border-radius: 8px;
            color: #3c763d;
            background-color: #dff0d8;
            margin-bottom: 10px !important;
        }
        .unreadMessage td {
            font-weight: bold;
        }
        .m-3{
            margin: 3px;
        }
    </style>
    <section class="content">
        <div class="box">
            <div class="box-body">
                <div class="col-lg-12">

                    <div id ="response"> </div>

                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

                </div>

                <div class="col-lg-12">
                    <div class="panel panel-info" style="">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><i class="fa fa-list"></i> <b>Application list </b></h5>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">

                            <div class="clearfix">
                                @if($application_by_status) {{-- Desk Officers --}}
                                <div class="" id="statuswiseAppsDiv">

                                    @foreach($application_by_status as $row)
                                        <div class="statusBox">
                                            <div class="panel panel-success statusBox-inner" style="border-color: #347ab6">
                                                <a href="javascript:void(0)" class="statusWiseList" data-id="{{$row['process_type_id'].','.$row['status_id']}}" style="background: #008000">
                                                    <div class="panel-heading" style="background:{{ $row['color'] }};color: white; padding: 10px 5px !important; height: 100%"
                                                         title="{{ $row['status_name']}}">

                                                        <div class="row">
                                                            <div class="col-xs-12 text-center">
                                                                <div class="h3" style="margin-top:0;margin-bottom:3px;font-size:20px;" id="{{ $row['status_name']}}">
                                                                    {{ $row['no_of_application'] }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row" style=" text-decoration: none !important">
                                                            <div class="col-xs-12 text-center">
                                                                <div class="h3" style="margin-top:0;margin-bottom:0;font-size:13px; font-weight: bold">
                                                                    {{ $row['status_name']}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif {{--checking not empty $appsInDesk --}}

                            <div class="nav-tabs-custom" style="margin-top: 15px;padding: 0 5px;">
                                <ul class="nav nav-tabs">

                                    <li id="tab1" class="active">
                                        <a data-toggle="tab" href="#list_desk" class="my_desk" aria-expanded="true">
                                            <strong>My Desk</strong>
                                        </a>
                                    </li>

                                    <li id="tab2" class="">
                                        <a data-toggle="tab" href="#list_search" class="feedback_list"
                                           aria-expanded="false">
                                            <strong>Search</strong>
                                        </a>
                                    </li>

                                    <div class="pull-right" style="display: flex;">
                                        <input type="text" id="trackingNo" class="form-control" placeholder="Tracking No">
                                        <input type="submit"class="btn btn-info" id="searchBtn" value="Global Search">
                                    </div>
                                </ul>

                                <div class="tab-content">
                                    <div id="list_desk" class="tab-pane active" style="margin-top: 20px">
                                        <div class="table-responsive">

                                            <table aria-label="Detailed Report Data Table" id="my_desk" class="table table-striped display" style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th style="width: 20%;">Tracking ID</th>
                                                    <th style="width: 15%;">Service</th>
                                                    <th style="width: 20%;">Last Status</th>
                                                    <th style="width: 20%;">Queue Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="list_search" class="tab-pane" style="margin-top: 20px">
                                        <div class="table-responsive">
                                            <table aria-label="Detailed Report Data Table" id="table_search" class="table table-striped"
                                                   style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th style="width: 20%;">Tracking ID</th>
                                                    <th style="width: 15%;">Service</th>
                                                    <th style="width: 20%;">Last Status</th>
                                                    <th style="width: 20%;">Queue Status</th>
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
        </div>
    </section>

@endsection
@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    @include('partials.datatable-scripts')
    <script>
        $(function () {
            $('.my_desk').click(function () {
                $('#my_desk').DataTable().ajax.reload();
            });

            $('#my_desk').DataTable({
                iDisplayLength: 50,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '{{route("security.getSecurityClearanceList",['-1000', 'my-desk'])}}',
                    method: 'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.process_type_id = parseInt(sessionStorage.getItem("process_type_id"));
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'process_name', name: 'process_name', searchable: false},
                    {data: 'last_status', name: 'last_status'},
                    {data: 'queue_status', name: 'queue_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

        $('.statusWiseList').click(function () {
            $('#list_desk').removeClass('active');
            $('#tab1').removeClass('active');
            $('#tab2').addClass('active');
            $('#list_search').addClass('active');

            let data = $(this).attr("data-id");
            let typeAndStatus = data.split(",");
            let process_type_id = typeAndStatus[0];
            let statusId = typeAndStatus[1];

            $('#table_search').DataTable({
                destroy: true,
                iDisplayLength: 50,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '/security-clearance/status-wise-list',
                    method: 'get',
                    data: function (d) {
                        d.status = statusId;
                        d.process_type_id = process_type_id;
                        d.applications_by_status = 'applications_by_status';
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'process_name', name: 'process_name', searchable: false},
                    {data: 'last_status', name: 'last_status'},
                    {data: 'queue_status', name: 'queue_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

        $('#searchBtn').click(function () {
            $('#list_desk').removeClass('active');
            $('#tab1').removeClass('active');
            $('#tab2').addClass('active');
            $('#list_search').addClass('active');
            let trackingNo = $("#trackingNo").val();

            $('#table_search').DataTable({
                destroy: true,
                iDisplayLength: 50,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '/security-clearance/search-tracking-no',
                    method: 'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.tracking_no = trackingNo;
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'process_name', name: 'process_name', searchable: false},
                    {data: 'last_status', name: 'last_status', orderable: false, searchable: false},
                    {data: 'queue_status', name: 'queue_status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

        function statusCheck(id) {
            $.ajax({
                type: "GET",
                url: "<?php echo url('/security-clearance/check-status') ?>"+'/'+id,
                data: {},
                success: function (response) {
                    if(response.responseCode == 1) {
                        $('#response').addClass('datasuccess');
                        $('#response').removeClass('dataerror');
                    } else {
                        $('#response').addClass('dataerror');
                        $('#response').removeClass('datasuccess');
                    }
                    $("#response").html(response.data);
                }
            });
        }

        function send(id) {
            $.ajax({
                type: "GET",
                url: "<?php echo url('/security-clearance/send') ?>"+'/'+id,
                data: {},
                success: function (response) {
                    if (response.responseCode == 1) {
                        $('#my_desk').DataTable().ajax.reload();
                        $('#response').addClass('datasuccess');
                        $('#response').removeClass('dataerror');
                    } else {
                        $('#response').addClass('dataerror');
                        $('#response').removeClass('datasuccess');
                    }
                    $("#response").html(response.data);
                }
            });
        }
    </script>
@endsection