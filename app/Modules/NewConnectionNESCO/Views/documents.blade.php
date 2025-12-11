<div class="panel panel-primary">
    <div class="panel-heading"><strong>Necessary documents to be attached here</strong>
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
            var conn_type = $("#connection_type").val()
            var phase = $("#phase").val()
            var tariff = $("#tariff").val()
            if (conn_type != null && phase != null && tariff != null) {
                var conn_type_id = conn_type.split('@')[0]
                var phase_id = phase.split('@')[0]
                var tariff_id = tariff.split('@')[0]
                var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}'
                $.ajax({
                    type: "POST",
                    url: '/new-connection-nesco/get-dynamic-doc',
                    dataType: "json",
                    data: {
                        _token: _token,
                        conn_type_id: conn_type_id,
                        phase_id: phase_id,
                        tariff_id: tariff_id,
                        appId: appId
                    },
                    success: function (result) {
                        $("#showDocumentDiv").html(result.data);
                        $("#category").next().hide();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#category").next().hide();
                        $("#showDocumentDiv").html('');
                    },
                });
            }
        })


        // $(".docloader").on("change", function (e) {
        //     $("#showDocumentDiv").html('')
        // });


    })

    function uploadDocument(targets, id, vField, isRequired) {
        var check = document.getElementById(id).getAttribute("flag");
        if (check == "img"){
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 149999) && (extension !== "jpg") && (extension !== "jpeg") && (extension !== "png") ){
                alert('File size cannot be over 150 KB and file extension should be only jpg, jpeg and png');
                document.getElementById(id).value = "";
                return false;
            }
        }
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/new-connection-nesco/upload-document')}}";
            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url: action,
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    if(response.includes("Error")== false){
                        $('#' + id).removeClass('required');
                    }

                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }


</script>


