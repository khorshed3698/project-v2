
<div class="card text-dark bg-light">
    <div class="card-body">
        <div class="table-responsive">
            <table id="necessaryResourceList" class="table table-bordered " width="100%" aria-label="Detailed Report Data Table">
                <thead>
                    {{--            <tr>--}}
                    {{--                <th></th>--}}
                    {{--            </tr>--}}
                    <tr class="table-success">
                        <td width="80%">Title</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#necessaryResourceList').DataTable({
            iDisplayLength: 10,
            processing: false,
            serverSide: true,
            searching: true,
            order: [0, 'asc'],
            ajax: {
                url: '{{url("web/get-necessary-resources")}}',
                method: 'POST',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'typeName', name: 'typeName'},
                {data: 'action', name: 'action', orderable: true, searchable: true}
            ],
            "aaSorting": []
        });
    });
</script>

