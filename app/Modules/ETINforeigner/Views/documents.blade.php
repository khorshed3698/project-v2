<div class="panel panel-primary">
    <div class="panel-heading"><strong>Attachments</strong>
    </div>
    <div class="panel-body">
        <div id="showDocumentDiv">
        </div>
    </div>
</div>

<script src="{{ asset("assets/scripts/apicall.js") }}" type="text/javascript"></script>
<script>

    function getDoc() {
        var _token = $('input[name="_token"]').val()
        var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}'
        // alert('ok')
        $.ajax({
            type: "POST",
            url: '/e-tin-foreigner/get-dynamic-doc',
            dataType: "json",
            data: {
                _token: _token,
                appId: appId
            },
            success: function (result) {
                $("#showDocumentDiv").html(result.data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#showDocumentDiv").html('');
            },

        })

    }


    function uploadDocument(targets, id, vField, isRequired) {
        var check = document.getElementById(id).getAttribute("flag")
        if (check != "img") {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if (extension !== "pdf") {
                alert('File  extension should be only pdf');
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
            var action = "{{URL::to('/e-tin-foreigner/upload-document')}}";
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
                    $('#' + id).removeClass('required');
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
    $(document).on('click', '.filedelete', function () {
        var abc_id = $(this).attr('docid');
        var abc = abc_id.split('_')[1]
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc_id).value = '';
            $('.saved_file_' + abc_id).html('');
            $('.span_validate_field_' + abc).html('');
        } else {
            return false;
        }
    });


</script>


