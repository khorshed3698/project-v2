@extends('layouts.admin')

@section('page_heading','<i class="fa fa-book fa-fw"></i> '.trans('messages.report_view'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('report');
    if(!ACL::isAllowed($accessMode,'V')) die('no access right!');
    ?>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <?php echo  $report_data->report_title . ''; ?>
                </div>
                <div class="pull-right">
                    @if($fav_report_info)
                        @if($fav_report_info->status == 1)
                            <a href="{{ url('reports/remove-from-favourite/'.$report_id) }}" class="btn btn-info">
                                <b>Remove From Favourite</b>
                                &nbsp;<i class="fa fa-remove"></i>
                            </a>
                        @elseif($fav_report_info->status == 0)
                            <a href="{{ url('reports/add-to-favourite/'.$report_id) }}" class="btn btn-default">
                                <b>Add to Favourite</b>
                                &nbsp;<i class="fa fa-check-square-o"></i>
                            </a>
                        @endif
                    @else
                        <a href="{{ url('reports/add-to-favourite/'.$report_id) }}" class="btn btn-default">
                            <b>Add to Favourite</b>
                            &nbsp;<i class="fa fa-check-square-o"></i>
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                @include('Reports::input-form')
            </div><!-- /.box-body -->
        </div>
    </div>




    {{--<div class="modal-header">--}}
        {{--<!-- Modal -->--}}
        {{--<div class="modal fade" id="crystalReportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"--}}
             {{--aria-hidden="true">--}}
            {{--<div class="modal-dialog modal-md">--}}
                {{--<div class="modal-content" id="showCrystalReportTable">--}}
                    {{--<div style="text-align:center; padding: 10px;"><h5 class="text-primary">Loading Form...</h5></div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

@endsection

@section('footer-script')


    @include('partials.datatable-scripts')

    <script>
        // function openCrystalReportModal(link, div) {
        //     $(link).on('click', function (e) {
        //         e.preventDefault();
        //         $(div).load(
        //             $(this).attr('href'),
        //             function (response, status, xhr) {
        //                 if (status === 'error') {
        //                     $(div).html('<p>Sorry, but there was an error:' + xhr.status + ' ' + xhr.statusText + '</p>');
        //                 }
        //                 return this;
        //             }
        //         );
        //     });
        // }
        $(function () {
            // $("#report_list").DataTable();
//            $('#report_data').DataTable({
//                "paging": true,
//                "lengthChange": false,
//                "ordering": true,
//                "info": true,
//                "autoWidth": true,
//                "iDisplayLength": 20
//            });

//        $("#rpt_date").datepicker({
//            maxDate: "+20Y",
//            //showOn: "button",
//            //buttonText: "Select date",
//            buttonText: "Select date",
//            changeMonth: true,
//            changeYear: true,
//            dateFormat: 'yy-mm-dd',
//            showAnim: 'scale',
//            yearRange: "-100:+40",
//            minDate: "-200Y",
//        });
            $(".datepicker").datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD'
            });
        });
    </script>
@endsection
