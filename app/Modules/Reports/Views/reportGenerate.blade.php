@extends('layouts.admin')

@section('page_heading','<i class="fa fa-book fa-fw"></i> '.trans('messages.report_view'))
@section('app_title',$report_data->report_title)

@section('content')
    <?php $accessMode = ACL::getAccsessRight('report');
    if (!ACL::isAllowed($accessMode, 'V')) die('no access right!');
    ?>

    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <?php echo $report_data->report_title . ''; ?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                @include('Reports::input-form')
            </div><!-- /.box-body -->
        </div>
        <div id="report_list_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer table-responsive">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $report = new \App\Modules\Reports\Models\ReportHelperModel();
                    $report->report_gen($report_id, $recordSet, $report_data->report_title, '', '', $report_data->is_column_text_full);
                    //\App\Libraries\CommonFunction::report_gen($report_id, $recordSet, $report_data->report_title, '');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-header">
        <!-- Modal -->
        <div class="modal fade" id="crystalReportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content" id="showCrystalReportTable">
                    <div style="text-align:center; padding: 10px;"><h5 class="text-primary">Loading Form...</h5></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <script src="{{ asset("assets/scripts/dataTables.buttons.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/buttons.flash.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/pdfmake.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/vfs_fonts.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/buttons.html5.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/buttons.print.min.js") }}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset("assets/scripts/datatable/buttons.dataTables.min.css") }}">
    <script>


        $('.showCrystalReport').on('click', function (e) {

            e.preventDefault();

            $("#crystalReportModal").modal();

            $.ajax({
                url: $(this).attr('href'),
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    report_id: "{!! Request::segment(3) !!}",
                    reportsql: "{!! $encode_SQL !!}",
                    search_keys: "{!! $search_keys !!}",
                },

                dataType: "html",

                success: function (response) {
                    $('#showCrystalReportTable').html(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
            });
        });


        $(function () {
            $(".datepicker").datetimepicker({
                viewMode: 'years',
                format: 'YYYY-MM-DD'
            });

        });

        $(document).ready(function () {
            $('.report_data_list').DataTable({
                iDisplayLength: 20,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endsection
