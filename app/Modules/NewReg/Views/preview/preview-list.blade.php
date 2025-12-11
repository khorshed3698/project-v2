@extends('layouts.admin')
@section('content')
    <section class="content">

        <div class="row">
            {!!session()->has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('error') .'</div>' : '' !!}
        </div>

        <div class="row">
            {!!session()->has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('success') .'</div>' : '' !!}
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <span>Documents Details</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" width="100%">
                            <thead class="bg-primary">
                                <tr>
                                    <th scope="col" width="15%" rowspan="2" class="text-center">Form name</th>
                                    <th scope="col" width="35%" rowspan="2" class="text-center">Description</th>
                                    <th scope="col" width="40%" colspan="3" class="text-center">Action</th>
                                </tr>
                                <tr>
                                    <th scope="col" width="10%" class="text-center"></th>
                                    <th scope="col" width="22%" class="text-center">Upload</th>
                                    <th scope="col" width="8%" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php $i=0;?>

                            @foreach($appInfo as $info)
                                @if($info->is_extra == 0)

                                <tr>
                                    <td>{!! $info->name !!}</td>
                                    <td>{!! $info->description !!}</td>
                                    <td style="">
                                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                            @if(\App\Libraries\CommonFunction::getFileStatus($info->id, $applicationId))
                                                <a form_id="{{ Encryption::encodeId($info->id)}}"
                                                   href="/new-reg/get-pdf/test/{{ Encryption::encodeId($info->id)}}/{{ Encryption::encodeId($applicationId)}}"
                                                   class="btn btn-danger btn-xs documentUrl">
                                                    <i class="fa fa-genderless"></i> <strong>Regenerate</strong>
                                                </a>
                                            @else
                                                <a form_id="{{ Encryption::encodeId($info->id)}}"
                                                   href="/new-reg/get-pdf/test/{{ Encryption::encodeId($info->id)}}/{{ Encryption::encodeId($applicationId)}}"
                                                   class="btn btn-danger btn-xs documentUrl">
                                                    <i class="fa fa-genderless"></i> <strong>Generate</strong>
                                                </a>

                                            @endif
                                            @else
                                        @endif
                                    </td>
                                    <td>
                                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                            @if(\App\Libraries\CommonFunction::getFileStatus($info->id, $applicationId))
                                                <input type="file" data-app_id="{{Encryption::encodeId($applicationId)}}"
                                                       data-ref_id="{{Encryption::encodeId($info->id)}}"
                                                       data-form_name="{{$info->name}}"
                                                       onchange="readFiles(this)" style="">
                                            @else
                                            @endif
                                            @else
                                            <center><b>Uploaded and saved successfully</b></center>
                                        @endif
                                    </td>
                                    <td>
                                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                            @if(\App\Libraries\CommonFunction::getFileUploadedStatus($info->id, $applicationId))
                                                <center>
                                                    <b> Uploaded</b>
                                                </center>
                                            @endif
                                        @endif
                                        <center><b class="upload_status"></b></center>
                                    </td>
                                </tr>

                                @endif

                                <?php $i++;?>

                            @endforeach

                            @foreach($appInfo as $info)
                                @if($info->is_extra == 1)

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td style="">

                                        </td>
                                        <td>
                                            <input type="file" data-app_id="{{Encryption::encodeId($applicationId)}}"
                                                   data-ref_id="{{Encryption::encodeId($info->id)}}"
                                                   data-form_name="{{$info->name}}"
                                                   onchange="readFiles(this)" style="">
                                        </td>
                                        <td>
                                            @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                                @if(\App\Libraries\CommonFunction::getFileUploadedStatus($info->id, $applicationId))
                                                    <center>
                                                        <b> Uploaded</b>
                                                    </center>
                                                @endif
                                            @endif
                                            <center><b class="upload_status"></b></center>
                                        </td>
                                    </tr>

                                @endif

                                <?php $i++;?>

                            @endforeach


                            </tbody>
                        </table>


                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))


                          <a style="margin-right: 120px; " href="/new-reg/get-pdf/check-all-pdf-files-up-test/{{ Encryption::encodeId($applicationId)}}" class="pull-right btn btn-success btn-sm documentUrl">
                            <i class="fa fa-save"></i> <strong>Save</strong>
                          </a><br><br>
                        @endif

                          @include('NewReg::preview.doc-tab')
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
            var app_id = $(input).data("app_id");


            $("#upload_progress").removeClass('hidden');

            $.ajax({
                url: '{{url('new-reg/new-store/upload')}}',
                type: 'post',
                data: {
                    _token: _token,
                    app_id:app_id,
                    ref_id: ref_id,
                    form_name: form_name,
                    base64_img: base64_img
                },
                dataType: 'json',
                success: function (response) {
                    // success
                    if (response.responseCode === 1) {

                        alert('File Upload successfully');
                        // input.remove();
                        //$(input).after(" Uploaded");
                        $(input).parent().parent().find('.upload_status').empty().html('<b>Uploaded</b>');
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