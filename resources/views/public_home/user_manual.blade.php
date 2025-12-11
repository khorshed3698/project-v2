<style>
    .dataTables_filter {
        float: right !important;
    }
</style>

    <table id="list" class="table table-responsive table-bordered " width="100%" aria-label="Detailed Report Data Table">
        <thead>
        <tr>
            <th></th>
        </tr>
        <tr>
            <td width="80%">Title</td>
            <td>Action</td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


@include('partials.datatable-scripts')
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<script>
        $(function () {
            $('#list').DataTable({
                iDisplayLength: 10,
                processing: true,
                serverSide: true,
                searching: true,
                order: [0, 'asc'],
                ajax: {
                    url: '{{url("web/get-user-manual")}}',
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

