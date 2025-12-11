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
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; E. declaration on Registration of the
                                Company Signed By (as if Form-I)</strong>
                        </div>

                        {!! Form::open(array('url' => '/new-reg/save-dec-upload-form', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'formId')) !!}

                        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id"/>

                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('declaration_name') ? 'has-error': ''}}">
                                        {!! Form::label('declaration_name','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-4">
                                            {!! Form::text('declaration_name', $appInfo->declaration_name, ['class' => 'form-control required input-md','placeholder' => 'Name','maxlength'=>'200']) !!}
                                            {!! $errors->first('declaration_name','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('declaration_position_id') ? 'has-error': ''}}">
                                        {!! Form::label('declaration_position_id','2. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('declaration_position_id',$rjscCompanyPosition, $appInfo->declaration_position_id,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                            {!! $errors->first('declaration_position_id','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('declaration_organization') ? 'has-error': ''}}">
                                        {!! Form::label('declaration_organization','3. Organization(applicable for advocate only)',['class'=>'col-md-4 text-left']) !!}
                                        <div class="col-md-4">
                                            {!! Form::text('declaration_organization', $appInfo->declaration_organization, ['class' => 'form-control input-md','placeholder' => 'Organization','maxlength'=>'200']) !!}
                                            {!! $errors->first('declaration_organization','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('declaration_address') ? 'has-error': ''}}">
                                        {!! Form::label('declaration_address','4. Address',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-4">
                                            {!! Form::textarea('declaration_address', $appInfo->declaration_address, ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1,'maxlength'=>'400']) !!}
                                            {!! $errors->first('declaration_address','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('declaration_district_id') ? 'has-error': ''}}">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {!! Form::label('declaration_district_id','District',['class'=>'col-md-4 text-left']) !!}
                                                </div>
                                                <div class="col-md-8">
                                                    {!! Form::select('declaration_district_id',$districts, $appInfo->declaration_district_id,['class' => 'form-control input-md required']) !!}
                                                    {!! $errors->first('declaration_district_id','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong> F. Upload Softcopy of Documents</strong>
                            </div>

                            <div class="panel-body">
                                {{--<div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('upload_doc_name_id') ? 'has-error': ''}}">
                                            {!! Form::label('upload_doc_name_id','1. Document Name',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-4">
                                                {!! Form::select('upload_doc_name_id',$rjscNrDocList, $appInfo->upload_doc_name_id,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('upload_doc_name_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>--}}

                                {{--<div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('upload_scaned_copy') ? 'has-error': ''}}">
                                            {!! Form::label('upload_scaned_copy','2. Scaned Copy(.ZIP {max size 200 KB})',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-4">
                                                <input type="file" name="upload_scaned_copy" class="form-control input-md {{ (empty($appInfo->upload_scaned_copy) ? 'required' : '') }}" id="upload_scaned_copy" onchange="readFileURL(this)">
--}}{{--                                                {!! Form::file('upload_scaned_copy', ['class' => 'form-control input-md required', 'onchange' => 'readFileURL(this)','id' => 'upload_scaned_copy']) !!}--}}{{--
                                                {!! $errors->first('upload_scaned_copy','<span class="help-block">:message</span>') !!}
                                                <br>
                                                <button type="button" id="filename" file-name="{{$appInfo->upload_scaned_copy}}" class="btn text-primary btn-xs documentUrl"
                                                        data-toggle="modal" data-target="#viewAttachmetnModal">View Attachment . {{$appInfo->upload_scaned_copy}}
                                                </button>

                                                <a href="/upload_scaned_copy/{{$appInfo->upload_scaned_copy}}" class="btn btn-primary btn-xs" download>Download</a>

                                                <input type="hidden" id="scan_copy" name="scan_copy_base_64">
                                            </div>
                                            <div class="col-md-4">
                                                --}}{{--<a href="/upload_scaned_copy/{{$appInfo->upload_scaned_copy}}" class="btn btn-success btn-xs">View Attach</a>--}}{{--

                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="modal fade" id="viewAttachmetnModal" role="dialog">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">View Attachment</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <object width="100%" height="400" data="{{ url('/upload_scaned_copy/'.$appInfo->upload_scaned_copy) }}"></object>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Modal -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}

                                <strong style="margin-left: 15px;">* Steps :</strong><br/><br/>
                                <strong style="margin-left: 15px;">1. Enter and save all the information of original
                                    registration application page</strong><br/><br/>
                                <strong style="margin-left: 15px;">2. Enter memorandum of Association
                                    (MOA)</strong><br/><br/>
                                <strong style="margin-left: 15px;">3. Enter Articles of association AOA a) First
                                    (part-1) b) Then Part-2</strong><br/><br/>
                                <strong style="margin-left: 15px;">4. Print the subscriber page of MOA as directed and
                                    Form-IX and after signing, upload the signed scanned copy as .ZIP
                                    format.</strong><br/><br/>
                                <strong style="margin-left: 15px;">5. Check and confirm MOA AND AOA by viewing your
                                    entered information.</strong><br/><br/>
                                <strong style="margin-left: 15px;">6. Finally Submit the page and continue to get the
                                    acknowledgement of payment.</strong><br/><br/>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <strong>3. Memorandum of Association (include top cover) pages
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