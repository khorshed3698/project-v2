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

                </div>

                @if(empty($delegated_desk))
                    <div class="modal fade" id="ProjectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" id="frmAddProject"></div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-12">
                    <div class="panel panel-info" style="">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h5><i class="fa fa-list"></i>  <b>The following applications will be automatically processed immediately <span class="list_name"></span> @if(isset($process_info->name)) for ({{$process_info->name}})</b> @endif</h5>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="nav-tabs-custom" style="padding: 5px;">
                                <ul  class="nav nav-tabs">

                                    {{--@if($user_type != '1x101' && $user_type != '5x505' && $user_type != '14x141')--}}
                                    {{----}}
                                    {{--@else--}}
                                    <li id="tab1" class="active">
                                        <a data-toggle="tab" href="#processByToday" class="today_process_list" aria-expanded="true">
                                            <b>Process by today</b>
                                        </a>
                                    </li>
                                    {{--@endif--}}
                                    <li id="tab4" class="">
                                        <a data-toggle="tab" href="#processByTomorrow" class="tomorrow_process_list" aria-expanded="true">
                                            <b>Process by tomorrow</b>
                                        </a>
                                    </li>

                                    {{--<li class="pull-right process_type_tab">--}}
                                    {{--<div class="form-inline">--}}
                                    {{--<label class="control-label text-primary">Switch to another module's applications:</label>--}}
                                    {{--{!! Form::select('ProcessType', ['0' => 'All'] + $ProcessType, $process_type_id, ['class' => 'form-control ProcessType', 'style'=>'max-width:300px']) !!}--}}
                                    {{--</div>--}}
                                    {{--</li>--}}
                                </ul>

                                <div class="tab-content">
                                    <div id="processByToday" class="tab-pane active" style="margin-top: 20px">
                                        <div class="table-responsive">
                                            <table id="today_process_list" class="table table-striped display" style="width: 100%" aria-label="Detailed Report Data Table">
                                                <thead>
                                                <tr>
                                                    <th style="width: 16%;">Tracking No</th>
                                                    <th>Current Desk</th>
                                                    <th>Process Type</th>
                                                    <th style="width: 35%">Reference Data</th>
                                                    <th>Status</th>
                                                    <th>Modified</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="processByTomorrow" class="tab-pane" style="margin-top: 20px">
                                        <div class="table-responsive">
                                            <table id="tomorrow_process_list" class="table table-striped" style="width: 100%" aria-label="Detailed Report Data Table">
                                                <thead>
                                                <tr>

                                                    <th  style="width: 15%;">Tracking No</th>
                                                    <th>Current Desk</th>
                                                    <th>Process Type</th>
                                                    <th style="width: 35%">Reference Data</th>
                                                    <th>Status</th>
                                                    <th>Modified</th>
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
    <script language="javascript">

        $(function () {
            @if($user_type == "4x404" && Auth::user()->desk_id == 1)
            // $('#list_desk').removeClass('active');
            // $('#tab2').removeClass('active');
            // $('#desk_user_application').addClass('active');
                    @endif

            var table = [];


            /**
             * set selected ProcessType in session
             * load data by ProcessType, on change ProcessType select box
             * @type {jQuery}
             */

//            $('.ProcessType').change(function () {
//                var process_type_id = $(this).val();
//                sessionStorage.setItem("process_type_id",process_type_id);
//            });
//            $('.ProcessType').trigger('change');
            /**
             * on click My Desk tab reload table with application list of current desk
             * @type {jQuery}
             */
            $('.today_process_list').click(function () {
                today_process_list.ajax.reload();
            });

            /**
             * on click Process by tomorrow load table with tomorrow processed application list
             * @type {jQuery}
             */
            $('.tomorrow_process_list').click(function () {
                tomorrow_process_list.ajax.reload();
            });


            /**
             * table desk script
             * @type {jQuery}
             */
            today_process_list = $('#today_process_list').DataTable({
                iDisplayLength: 25,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url:  '{{route("autoProcess.getList",['-1000', 'processByToday'])}}',
                    method:'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.process_type_id = '{{ \App\Libraries\Encryption::encodeId($process_type_id) }}';
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'desk', name: 'desk'},
                    {data: 'process_name', name: 'process_name',searchable: false},
                    {data: 'json_object', name: 'json_object'},
                    {data: 'status_name', name: 'status_name'},
                    {data: 'updated_at', name: 'updated_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });

            /**
             * delegated application list table script
             * @type {jQuery}
             */
            tomorrow_process_list = $('#tomorrow_process_list').DataTable({
                iDisplayLength: 25,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url:  '{{route("autoProcess.getList",['-1000','processByTomorrow'])}}',
                    method:'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.process_type_id = '{{ \App\Libraries\Encryption::encodeId($process_type_id) }}';
                    }
                },
                columns: [
                    {data: 'tracking_no', name: 'tracking_no'},
                    {data: 'desk', name: 'desk'},
                    {data: 'process_name', name: 'process_name',searchable: false},
                    {data: 'json_object', name: 'json_object'},
                    {data: 'status_name', name: 'status_name'},
                    {data: 'updated_at', name: 'updated_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });
        });

    </script>
    @yield('footer-script2')
@endsection