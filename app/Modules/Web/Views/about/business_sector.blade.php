@extends('web.layouts.app')

@push('pluginStyles')
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">--}}
{{--    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">--}}
<link rel="stylesheet" href="{{asset('assets/landingV2/assets/plugins/dataTables/datatables.css')}}">
@endpush

@push('customStyles')
    <link rel="stylesheet" href="{{asset('assets/landingV2/assets/frontend/css/pages/inner-page.css')}}">
@endpush

@section('content')

    <section class="bida-page-breadcrumb">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('web.home') }}">Home</a></li>
                    <li class="breadcrumb-item">Business Sector (National Industrial Classification)</li>
                </ol>
            </nav>
        </div>
    </section>
    <section class="inner-page-content bida-section">
        <div class="container">
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
            <div id="sub_agency_div" class="float-end pb-3 pe-4">
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
        </div>
    </section>

@endsection

{{-- Page Style & Script--}}
@push('pluginScripts')
{{--    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>--}}
<script src="{{asset('assets/landingV2/assets/plugins/dataTables/datatables.min.js')}}"></script>
@endpush


@push('customScripts')
    <script>
        $(function () {
            let groupColumn = 0;
            let table = $('#business_sector').DataTable({
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
                let currentOrder = table.order()[0];
                if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                    table.order( [ groupColumn, 'desc' ] ).draw();
                }
                else {
                    table.order( [ groupColumn, 'asc' ] ).draw();
                }
            } );
        });
    </script>
@endpush