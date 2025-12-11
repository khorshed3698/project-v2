<div class="panel panel-primary">
    <div class="panel-heading"><strong>প্রয়োজনীয় কাগজপত্র এখানে সংযুক্ত করতে হবে</strong>
    </div>
    <div class="panel-body">
        <div id="showDocumentDiv">
            <div class="clearfix">

            </div>
        </div>
    </div>
</div>

<script src="{{ asset("assets/scripts/apicall.js") }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(".docloader").on("change", function () {
            var _token = $('input[name="_token"]').val();
            var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';
            var tariff = $("#tariff_name").val()
            var tariff_id = tariff.split('@')[0]
            $.ajax({
                type: "POST",
                url: '/new-connection-breb/get-dynamic-doc',
                dataType: "json",
                data: {
                    _token: _token,
                    tariff_id: tariff_id,
                    appId: appId
                },
                success: function (result) {
                    $("#showDocumentDiv").html(result.data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#showDocumentDiv").html('');
                },
            })
        });

    })

    //Doc and image upload section
    function uploadDocument(targets, id, vField, isRequired) {
        var check = document.getElementById(id).getAttribute("flag")
        if (check == "img") {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop().toLowerCase();
            var allowedSize = 149999;
            var allowedSizeKb = 150;
            if(id == 'input_2'){
                allowedSize = 600000;
                allowedSizeKb = 600;
            }
            if ((fileSize >= allowedSize) || (extension != "jpg")) {
                alert('File size cannot be over '+allowedSizeKb+' KB and File has to be submitted  in only .JPG format');
                document.getElementById(id).value = "";
                return false;
            }
        } else {
            var fileName = document.getElementById(id).files[0].name;
            var fileSize = document.getElementById(id).files[0].size;
            var extension = fileName.split('.').pop();
            if (fileSize >= 3000000 || extension !== "pdf") {
                alert('File has to be submitted  in only .PDF format');
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
            var action = "{{URL::to('/new-connection-breb/upload-document')}}";
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

    $(document).unbind("click").on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = ''
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
            $('#span_' + abc).show()
            let img = $('#old_image_' + abc).val()
            let old_img = $('#old_image_' + abc).attr('data-img')
            $('#validate_field_' + abc).val(img)
            $('#photo_viewer_' + abc).attr('src', old_img)
        } else {
            return false;
        }
    });

</script>


