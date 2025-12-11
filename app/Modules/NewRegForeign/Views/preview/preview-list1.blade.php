@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="panel panel-info">
            <div class="panel-heading">
                <span>Submission Details</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead class="bg-primary">
                            <tr>
                                <th width="25%" rowspan="2" class="text-center">Title</th>
                                <th width="10%" class="text-center">Upload</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Form-I</td>
                                <td>
                                    <input type="file" data-ref_id="1" data-form_name="Form-I"
                                           onchange="readFiles(this)" name="">
                                </td>
                            </tr>
                            <tr>
                                <td>Form-II</td>
                                <td>
                                    <input type="file" data-ref_id="2" data-form_name="Form-II" onchange="readFiles(this)">
                                </td>
                            </tr>

                            <tr>
                                <td>Form-III</td>
                                <td>
                                    <input type="file" data-ref_id="3" data-form_name="Form-II" onchange="readFiles(this)">
                                </td>
                            </tr>
                            <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        function readFiles(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    uploadFilesUpload(e.target.result, input);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // to save uploaded files in DB
        function uploadFilesUpload(base64_img, input, target) {

            var _token = $('input[name="_token"]').val();
            var ref_id = $(input).data("ref_id");
            var form_name = $(input).data("form_name");
            $("#upload_progress").removeClass('hidden');

            $.ajax({
                url: '{{url('files/new-store/upload')}}',
                type: 'post',
                data: {
                    _token: _token,
                    ref_id: ref_id,
                    form_name: form_name,
                    base64_img:base64_img
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.responseCode === 1) {

                    } else {
                        // $("#" + type + "_err").html(response.data);
                        // $('button[type="submit"]').addClass('disabled');
                        // $(input).parent().find('.upload_flags').val(0);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);

                },
                beforeSend: function (xhr) {
                    console.log('before send');
                },
                complete: function () {
                    // $("#upload_progress").addClass('hidden');
                }
            });
        }

    </script>
@endsection