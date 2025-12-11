@extends('layouts.admin')

@section('page_heading','<i class="fa fa-book fa-fw"></i> '.trans('messages.report_list'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('report');
    if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
    ?>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">

            <div class="panel-heading" style="padding-top: 5px; padding-bottom: 5px">
                @if(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '15x151')
                    @if(ACL::getAccsessRight('report','A'))
                        <div class="pull-right">
                            <a class="" href="{{ url('/reports/create') }}">
                                {!! Form::button('<i class="fa fa-plus"></i> Add New Report', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                            </a>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                @endif
            </div>
            <!-- /.panel-heading -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="nav-item active">
                        <a data-toggle="tab" href="#list_1" aria-expanded="true">
                            <b>My Favourite</b>
                        </a>
                    </li>
                    <li class="nav-item all_reports">
                        <a data-toggle="tab" href="#list_2" aria-expanded="false">
                            <b>All Reports</b>
                        </a>
                    </li>
                    @if(in_array(Auth::user()->user_type, ['1x101', '1x102', '15x151']))
                        <li class="nav-item unpublished_reports">
                            <a data-toggle="tab" href="#list_3" aria-expanded="false">
                                <b>Unpublished</b>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="tab-content">
                <div id="list_1" class="tab-pane active">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table aria-label="Detailed Report Data Table" id="fav_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($getFavouriteList['fav_report'] as $row)
                                    <tr>
                                        <td>{!! $row->report_title !!}</td>
                                        <td>{!! $row->status==1? '<span class="text-success">Published</span>':'<span class="text-warning">Un-published</span>' !!}</td>
                                        <td>
                                            @if(\App\Libraries\UtilFunction::isAllowedToViewFvrtReport($row->report_id))
                                                @if(ACL::getAccsessRight('report','V'))
                                                    <a href="{!! url('reports/view/'. Encryption::encodeId($row->report_id)) !!}" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-folder-open-o"></i> Open
                                                    </a>
                                                @endif
                                                @if(ACL::getAccsessRight('report','E'))
                                                    {!! link_to('reports/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <div id="list_2" class="tab-pane all_reports">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table aria-label="Detailed Report Data Table" id="list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($getList['result'] as $row)
                                    <tr>
                                        <td>{!! $row->report_title !!}</td>
                                        <td>{!! $row->status==1? '<span class="text-success">Published</span>':'<span class="text-warning">Un-published</span>' !!}</td>
                                        <td>
                                            @if(ACL::getAccsessRight('report','V'))
                                                <a href="{!! url('reports/view/'. Encryption::encodeId($row->report_id)) !!}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-folder-open-o"></i> Open
                                                </a>
                                            @endif
                                            @if(ACL::getAccsessRight('report','E'))
                                                {!! link_to('reports/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                @if(in_array(Auth::user()->user_type, ['1x101', '1x102', '15x151']))
                    <div id="list_3" class="tab-pane unpublished_reports">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table aria-label="Detailed Report Data Table" id="unpub_list" cellspacing="0" width="100%" class="table table-responsive table-striped table-bordered nowrap">
                                    <thead>
                                    <tr class="d-none">
                                        <th aria-hidden="true"  scope="col"></th>
                                    </tr>
                                    <tr>
                                        <td>Title</td>
                                        <td>Status</td>
                                        <td>Action</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($getUnpublishedList as $row)
                                        <tr>
                                            <td>{!! $row->report_title !!}</td>
                                            <td><span class="text-warning">Un-published</span></td>
                                            <td>
                                                @if(Auth::user()->user_type == '1x101' || Auth::user()->user_type == '15x151')
                                                    <a href="{!! url('reports/view/'. Encryption::encodeId($row->report_id)) !!}" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-folder-open-o"></i> Open
                                                    </a>
                                                    {!! link_to('reports/edit/'. Encryption::encodeId($row->report_id),'Edit',['class' => 'btn btn-default btn-xs']) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->

@endsection
@section('footer-script')
    <script src="{{ asset("assets/scripts/datatable/jquery.dataTables.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/datatable/dataTables.bootstrap.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/datatable/dataTables.responsive.min.js") }}" src="" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.js") }}" src="" type="text/javascript"></script>
    <script>

        $(function () {
            $('#list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength":25
            });
        });

        $(function () {
            $('#fav_list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength":25
            });
        });

        $(function () {
            $('#unpub_list').DataTable({
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "iDisplayLength":25
            });
        });

    </script>
@endsection