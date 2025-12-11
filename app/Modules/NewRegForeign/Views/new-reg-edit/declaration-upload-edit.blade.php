<style>
    #app-form label.error {
        display: none !important;
    }
</style>
<section class="content" id="">
    <div class="col-md-12">
        <div class="col-md-12" style="padding:0px;">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Foregin E. declaration on Registration of the
                                Company Signed By (as if Form-I)</strong>
                        </div>

                        {!! Form::open(array('url' => '/new-reg-foreign/save-dec-upload-form', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'formId')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id"/>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong> D. Upload Softcopy of Documents</strong>
                            </div>

                            <div class="panel-body">


                                <strong style="margin-left: 15px;">01.Softcopy ( Word document in English)</strong><br/><br/>
                                <strong style="margin-left: 20px;">Uploading document shall be of following formats:</strong><br/>
                                <strong style="margin-left: 20px;">(i) Paper size = A4 </strong><br/>
                                <strong style="margin-left: 20px;">(ii) Left margin = 0.75" </strong><br/>
                                <strong style="margin-left: 20px;">(iii) Top margin = 0.75"</strong><br/>
                                <strong style="margin-left: 20px;">(iv) Font Name = Arial , Times New Roman</strong><br/>
                                <strong style="margin-left: 20px;">(v) Font Size = 11-12"</strong><br/><br/>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <strong>3. Charter/Memorandum of Association/Statutes of company/Other Document (include top cover) pages
                                                (no.)</strong>
                                        </div>
                                        <div class="col-md-2">
                                            {!! Form::number('memorandum_asso_no', $appInfo->memorandum_asso_no, ['class' => 'form-control required input-md']) !!}
                                        </div>
                                        <div class="col-md-5"></div>
                                    </div>
                                </div>
                                <br/>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <strong>4. Article of Association (include top cover) pages (no.)</strong>
                                        </div>
                                        <div class="col-md-2">
                                            {!! Form::number('article_asso_no', $appInfo->article_asso_no, ['class' => 'form-control required input-md']) !!}
                                        </div>
                                        <div class="col-md-5"></div>
                                    </div>
                                </div>
                                <br/>

                                {{--<div class="row text-center">
                                    <div class="col-md-12">
                                        <button class="btn btn-info btn-sm">Upload</button>
                                    </div>
                                </div>--}}
                                <br/>

                                <div class="row text-center">
                                    <div class="col-md-12">

                                    </div>
                                </div>
                                <br/>

                                {{--<div class="row text-center">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary btn-sm">Submit</button>
                                    </div>
                                </div>--}}
                            </div>


                        </div>

                        @if(ACL::getAccsessRight('NewReg','-E-'))
                            <div class="">
                                <div class="col-md-6">
                                    <button class="btn btn-info" value="draft" name="actionBtn" id="draft" type="submit">Save as Draft</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="btn btn-success" value="save" name="actionBtn" id="save" type="submit">Save and continue</button>
                                </div>
                            </div>
                        @endif

                        {!! Form::close() !!}

                        {{--<div class="col-md-2 col-md-offset-5">
                            <a href="{{ url('/new-reg-page/declaration') }}" class="btn btn-info">Continue</a>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    $(document).ready(function () {
        $(document).on('click','#draft',function () {
            $('#formId').validate().cancelSubmit = true;;
        });
        $(document).on('click','#save',function () {
            $('#formId').validate();
        });
    });

    /*$(document).on('click','#save',function () {
        var file=$('#upload_scaned_copy').val();
        var filecheck=$('#filename').attr('file-name');
        if(file=="" && filecheck=="")
        {
            alert('success');
        }

        $('#formId').validate();
    });*/

    function readFileURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var size = input.files[0].size / 1024;
                var mime_type = input.files[0].type;
                var name = input.files[0].name;
                var lastFour = name.substr(name.length - 4);
                // alert(lastFour);
                // console.log(input.files[0]);
                // if (mime_type != 'application/zip' && mime_type != 'application/x-rar'  && mime_type != 'application/x-rar-compressed' && mime_type != 'application/octet-stream' && mime_type != 'application/x-zip-compressed' && mime_type != 'multipart/x-zip') {
                // if (lastFour.toString() == ".zip") {
                //     document.getElementById('upload_scaned_copy').value = '';
                //     alert('File must be zip or rar file');
                //     return false;
                // }
                if(size > 200){
                    document.getElementById('upload_scaned_copy').value = '';
                    alert('File size must be lower than 200 kb');
                    return false;
                }
                document.getElementById('scan_copy').value = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>