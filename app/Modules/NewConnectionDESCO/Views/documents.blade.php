<div class="panel panel-primary">
    <div class="panel-heading"><strong>Description of Connection</strong>
    </div>
    <div class="panel-body">
        <div id="showDocumentDiv">
        </div>
    </div>
</div>

<script src="{{ asset("assets/scripts/apicall.js") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {

        $(".docloader").on("change", function () {
            var _token = $('input[name="_token"]').val()
            var tariff_category = $("#tariff_category").val()
            var tariff = $("#tariff").val()

            if (tariff_category != '' && tariff != '') {
                var tariff_category_id = tariff_category.split('@')[0]
                var tariff_id = tariff.split('@')[0]
                var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}'
                $.ajax({
                    type: "POST",
                    url: '/new-connection-desco/get-dynamic-doc',
                    dataType: "json",
                    data: {
                        _token: _token,
                        tariff_category_id: tariff_category_id,
                        tariff_id: tariff_id,
                        appId: appId
                    },
                    success: function (result) {
                        $("#showDocumentDiv").html(result.data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#showDocumentDiv").html('');
                    },
                });
            }
        })


        // $(".docloader").on("change", function (e) {
        //     $("#showDocumentDiv").html('')
        // });


    })


</script>


