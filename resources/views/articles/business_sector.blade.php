@extends('layouts.front')

@section('style')
    <style>
        #business_sector thead tr th {
            background-color: rgba(0, 104, 72, 0.8) !important;;
            color: #fff;
            border-color: #006848 !important;
        }
        tr.group,
        tr.group:hover {
            background-color: #ddd !important;
        }
        p {
            margin-left: 20px;
        }
        #business_sector_filter {
            text-align: right;
        }

        @media only screen and (max-width: 768px) {
            #business_sector_filter {
                text-align: left;
            }
            #business_sector_length label, #business_sector_filter label {
                display: block;
            }
        }
    </style>
@endsection

@section('content')
    @include('articles.top-navbar')
    <div class="row">
        <div class="col-md-8">
            <div class="box-div">
                <h3>Business Sector/  National Industrial Classification</h3>

                <div class="panel panel-default table-responsive" style="padding: 15px;">
                    <table id="business_sector" class="table table-bordered dt-responsive" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th>Class</th>
                            <th>Section (code)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($business_sectors as $business_sector)
                            <tr>
                                <td>{{ $business_sector->section_name_code }}</td>
                                <td>{!! $business_sector->class !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pull-right">
                    <label class="radio-inline">Is this article helpful?</label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('yes', '', 3)">
                        Yes
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="is_helpful" id="is_helpful" value="" onclick="isHelpFulArticle('no', '', 3)">
                        No
                    </label>
                </div>
                <div class="clearfix"></div>

            </div>

        </div>
        <div class="col-md-4 hidden-sm hidden-xs">
            @include('public_home.login_panel')
        </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <script>
        $(function () {
            var groupColumn = 0;
            var table = $('#business_sector').DataTable({
                "columnDefs": [
                    { "visible": false, "targets": groupColumn }
                ],
                "order": [[ groupColumn, 'asc' ]],
                "displayLength": 10,
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last = null;

                    api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                        if ( last !== group ) {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td>'+group+'</td></tr>'
                            );

                            last = group;
                        }
                    } );
                }
            } );

            // Order by the grouping
            $('#business_sector tbody').on( 'click', 'tr.group', function () {
                var currentOrder = table.order()[0];
                if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                    table.order( [ groupColumn, 'desc' ] ).draw();
                }
                else {
                    table.order( [ groupColumn, 'asc' ] ).draw();
                }
            } );
        });
    </script>
@endsection