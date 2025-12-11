<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        <span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel">Request List</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table aria-label="Detailed Report Data Table" id="crystal_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Keys</th>
                        <th>Download</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div><!-- /.table-responsive -->
        </div>
    </div>
</div>
<div class="modal-footer">
    <span id="report_action_box">
        <button type="button" id="crystal_gen_btn" search_keys="{!! $search_keys !!}"  reportsql="{!! $reportsql !!}" report_id="{!! $report_id !!}" class="btn btn-primary pull-left">Generate Report</button>
    </span>
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
<input type="hidden" name="pdfurl" value="<?php echo env('PDF_API_BASE_URL'); ?>">


<script type="text/javascript">

    var search_keys = '';
    var flag = false;
    $(document).on('click','#crystal_gen_btn',function(e){

        if(flag == true){
            return false;
        }

        flag = true;


        btn = $(this);
        btn_content = btn.html();
        btn.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;'+btn_content);
        report_id = btn.attr('report_id');
        reportsql = btn.attr('reportsql');
        search_keys = btn.attr('search_keys');
        pdfurl = $('input[name="pdfurl"]').val();
        btn.prop('disabled', true);

        $.ajax({
            url: '/reports/generate-crystal-report',
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data:{
                report_id: report_id,
                reportsql: reportsql,
                pdfurl: pdfurl
            },
            success: function (response) {
                checkgenerator(report_id,'crystal_report_paper_request');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
                console.log(errorThrown);
            },
            beforeSend: function(xhr) {

            }
        });
        return false; // keeps the page from not refreshing
    });

    function checkgenerator(id,request_id)
    {
        pdfurl = $('input[name="pdfurl"]').val();
        $.ajax({
            url: '/reports/ajax-crystal-report-feedback',
            type: "POST",
            data:
                {
                    report_id: id,
                    search_keys: search_keys,
                    pdfurl: pdfurl
                },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                console.log(response);
                if (response.responseCode === 1) {
                    if (response.data === 1) {
                        // Need to show download & regenerate link
                        showDownloadPanel(id,request_id,response.ref_id);
                    } else if (response.data === -1) {
                        alert('Information not eligible!');
                        return false;
                    } else if (response.data === 2) {
                        myVar = setTimeout(checkgenerator,5000,id,request_id);
                    }
                } else {
                    alert('Whoops there was some problem please contact with system admin.');
                    ///////////////window.location.reload();
                }
            },error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
                console.log(errorThrown);
            },
            beforeSend: function(xhr) {

            }
        });
        return false; // keeps the page from not refreshing
    }

    function showDownloadPanel(id,request_id,ref_id)
    {
        pdfurl = $('input[name="pdfurl"]').val();
        reportsql = $('input[name="reportsql"]').val();
        $.ajax({
            url: '/reports/update-download-panel',
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data:
                {
                    ref_id: ref_id,
                    reportsql: reportsql
                },
            success: function (response) {

                if (response.responseCode == 1){
                    if(request_id == "crystal_report_paper_request"){
                        $('#report_action_box').html(response.data);
                       // $('#crystal_gen_btn').prop('disabled', false);
                    }
                    else{
                        window.location.reload();
                    }
                }
                else{
                    window.location.reload();
                }
            }
        });
        return false; // keeps the page from not refreshing
    }





    $(document).ready(function () {
        var report_id = "{{$report_id}}";
        $('#crystal_list').DataTable({
            processing: true,
            serverSide: true,
            pageLength : 5,
            ajax: {
                url: '{{url("reports/show-crystal-report-data")}}',
                type: "POST",
                data: function (d) {
                    d._token = $('input[name="_token"]').val();
                    d.report_id = report_id;
                }
            },
            columns: [

                {data: 'created_at', name: 'created_at'},
                {data: 'search_keys', name: 'search_keys'},
                {data: 'pdf_download_link', name: 'pdf_download_link',orderable: false, searchable: false}
            ],
            "aaSorting": []
        });
    });
</script>
