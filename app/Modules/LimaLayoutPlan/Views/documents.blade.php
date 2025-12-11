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
        var _token = $('input[name="_token"]').val();
        var appId = '{{isset($app_info->id) ? $app_info->id : ""}}';
        $.ajax({
            type: "POST",
            url: '{{ url('/licence-applications/lima-factory-layout/get-dynamic-doc') }}',
            dataType: "json",
            data: {
                _token: _token,
                appId: appId
            },
            success: function (response) {
                if(response.responseCode == 1){
                    $("#showDocumentDiv").html(response.data);
                }else{
                    $("#showDocumentDiv").html('<h5 class="text-danger">Something is wrong !</h5>');
                }
            },
            error: function (error) {
                $("#showDocumentDiv").html('<h5 class="text-danger">Something is wrong !</h5>');
            },
        });
    }// end -:- getDoc()


    function uploadDocument(targets, id, vField, isRequired) {
        var allowedExtension = ['pdf', 'jpg', 'jpeg', 'png'];
        var check = document.getElementById(id).getAttribute("flag")
        if (check == "img") {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if ((fileSize >= 149999) || (extension !== "jpg")) {
                alert('File size cannot be over 150 KB and file extension should be only jpg');
                document.getElementById(id).value = "";
                return false;
            }
        }else{
            var fileName = document.getElementById(id).files[0].name;
            var extension = fileName.split('.').pop();
            if (!allowedExtension.includes(extension)) {
                alert('File has to be submitted in only .PDF, .JPG, .JPEG, .PNG format');
                document.getElementById(id).value = "";
                return false;
            }
            if(id == 'f_24'){
                if (extension !== "pdf") {
                    alert('File has to be submitted in only .PDF format');
                    document.getElementById(id).value = "";
                    return false;
                }
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
            var action = "{{URL::to('/licence-applications/lima-factory-layout/upload-document')}}";
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
                    console.log(response);
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
            console.log('Message Error =>'+err);
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }// end -:- uploadDocument();


</script>


