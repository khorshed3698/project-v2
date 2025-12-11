<div id="list_1" class="tab-pane {!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">

    <div class="panel-body">
        <div class="row">
            @if($notice)
                <div class="col-md-12">

                    <?php
                    $arr = $noticeall;
                    echo '<table class="table basicDataTable" style="margin-bottom: 0px">';
                    foreach ($arr as $value) {
                        $update_date = App\Libraries\CommonFunction::changeDateFormat(substr($value->update_date, 0, 10));
                        if($value->prefix == 'board-meeting'){
                            echo "<tr><td width='120px'>$update_date</td><td><span class='text-$value->importance'><a href='#' class='notice_heading'> <b>$value->heading</b></a></span><span class='details' style='display: none;'><br/> <a target='_blank' href='$value->details'> $value->details</a></span></td></tr>";
                        }else{
                            echo "<tr><td width='120px'>$update_date</td><td><span class='text-$value->importance'><a href='#' class='notice_heading'> <b>$value->heading</b></a></span><span class='details' style='display: none;'><br/> $value->details</span></td></tr>";
                        }
                    }
                    echo '</tbody></table>';
                    ?>

                </div>
            @endif
        </div>
    </div>


    <script>
        function loadObject(key){
            var obj = $('.'+key);
            obj.after('<span class="loading_data">' +
                '' +
                '</span>');
            $.ajax({
                type: "GET",
                data: {
                    key: key
                },
                url: "{!! env('PROJECT_ROOT') !!}web/get-object",
                success: function (response) {
                    if (response.responseCode == 1) {
                        obj.html(response.data);
                    } else if (response.responseCode == 0) {
                        obj.html(response.data);
                    }

                    $('#'+obj.find('.dataTable').attr('id')).dataTable({
                        "lengthChange": false,
                        'displayLength':10,
                        "dom": '<"top">t<"bottom"ifp><"clear">'
                    });
                    obj.next().hide();
                }
            });
        }
    </script>
</div>


