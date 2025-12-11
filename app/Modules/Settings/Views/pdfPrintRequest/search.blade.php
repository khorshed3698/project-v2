{!! Form::open(['url' => '#tracking/search','method' => 'GET','id' => ''])!!}
<div class="row">
    <div class="col-md-12">

        <div class="col-md-3"></div>
        <div class="col-md-5">
            <label for="">Search text: </label>
            {!! Form::text('search_text', '', ['class' => 'form-control search_text', 'placeholder'=>'Tracking Number']) !!}
        </div>
        <div class="col-md-1">
            <label for="">&nbsp;</label> <br>
            <input type="button" id="search_process" class="btn btn-primary" value="Search">
        </div>
        <div class="col-md-3">
        </div>
    </div>
</div>
{!! Form::close()!!}


<table aria-label="Detailed Report Data Table" id="table_search" class="table table-striped table-bordered">
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

@section('footer-script2')
    <script language="javascript">
        $(function () {
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
                        url: '{{url("settings/pdf-print-request-search-list")}}',
                        method: 'get',
                        data: function (d) {
                            d.process_search = true;
                            d.search_text = $('.search_text').val();
                            console.log( d.search_text);
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
            }

        })
    </script>
@endsection