{!! Form::open(['url' => '#support/search-nid','method' => 'GET','id' => 'sb-search'])!!}
<div class="row">
    <div class="col-md-12">

        <div class="col-md-3"></div>
        <div class="col-md-5">
            <label for="">Search NID: </label>
            {!! Form::number('search_nid', '', ['class' => 'form-control search_nid', 'placeholder'=>'Search by National Id']) !!}
        </div>
        <div class="col-md-1">
            <label for="">&nbsp;</label>
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
        <th>Name</th>
        <th>National Id</th>
        <th>Date of Birth</th>
        <th>Verification Flag</th>
        <th>Submitted Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

@section('footer-script2')
    <script language="javascript">
        $(function () {
            $('#table_search').hide();

            $('#search_process').click(function () {

                $('#table_search').show();

                $('#table_search').DataTable({
                    destroy: true,
                    iDisplayLength: 10,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: '{{url("support/search-nid")}}',
                        method: 'get',
                        data: function (d) {
                            d.process_search = true;
                            d.search_nid = $('.search_nid').val();
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'nid', name: 'nid'},
                        {data: 'dob', name: 'dob'},
                        {data: 'verification_flag', name: 'verification_flag'},
                        {data: 'submitted_at', name: 'submitted_at'},
                        {data: 'action', name: 'action', orderable: true, searchable: true}
                    ],
                    "aaSorting": []
                });
            })
        })
    </script>
@endsection