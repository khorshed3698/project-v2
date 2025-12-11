<style>
    tr.group,
    tr.group:hover {
        background-color: #ddd !important;
    }
    p {
        margin-left: 20px;
    }
</style>
<div class="panel panel-info">
    <div class="panel-heading" style="padding: 10px 15px;">Sector Information</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="list" class="table table-bordered dt-responsive" width="100%" aria-label="Detailed Report Data Table">
                <thead>
                <tr>
                    <th></th>
                </tr>
                <tr>
                    <td width="80%">Class</td>
                    <td>Section (code)</td>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="pull-right">
            <div id="availbe_service_div">
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
    </div>
</div>

@include('partials.datatable-scripts')
<script>
    $(function () {
        var groupColumn = 1;
        var table = $('#list').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: 10,
            columnDefs: [
                { "visible": false, "targets": groupColumn }
            ],
            order: [[ groupColumn, 'asc' ]],
            ajax: {
                url: '{{url("web/get-business-class-list")}}',
                method:'get',
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                }
            },
            columns: [
                {data: 'class', name: 'class'},
                {data: 'section_name_code', name: 'section_name_code'},
            ],
            drawCallback: function ( settings ) {

                $(".paginate_button").css({"display": "inline"});

                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group"><td>'+group+'</td></tr>'
                        );

                        last = group;
                    }
                } );
            },
            "aaSorting": [],
            // "drawCallback": function() {
            //     $(".paginate_button").css({"display": "inline"});
            // }
        });



        // Order by the grouping
        $('#list tbody').on( 'click', 'tr.group', function () {
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
