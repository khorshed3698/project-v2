<div class="card bg-light">
    <div class="card-body">
        <table id="businessSectorList" class="table table-bordered dt-responsive" width="100%" aria-label="Detailed Report Data Table">
            <thead>
            <tr class="table-success">
                <td width="80%">Class</td>
                <td>Section (code)</td>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="sub_agency_div" class="float-end pt-3">
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

<script>
    $(function () {
        let groupColumn = 1;
        let table = $('#businessSectorList').DataTable({
            processing: false,
            serverSide: true,
            iDisplayLength: 10,
            responsive: true,
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

                //$(".paginate_button").css({"display": "inline"});
                $(".paginate_button").css({"display": "inline-block"});

                let api = this.api();
                let rows = api.rows( {page:'current'} ).nodes();
                let last=null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="2" class="fw-bold table-secondary">' + group + '</td></tr>'
                        );
                        last = group;
                    }
                } );
            },
            "aaSorting": []
        });

        // Order by the grouping
        $('#businessSectorList tbody').on( 'click', 'tr.group', function () {
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