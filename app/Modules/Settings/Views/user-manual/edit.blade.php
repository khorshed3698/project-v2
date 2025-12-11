@extends('layouts.admin')
@section('content')
<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'A'))
    die('no access right!');
?> 
<div class="col-lg-12">

    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

    <div class="panel panel-primary">
        <div class="panel-heading" style="font-size: large;">
            <b> {!! trans('messages.user_manual') !!} </b>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            {!! Form::open(array('url' => '/settings/update-user-manual/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


            <div class="form-group col-md-12 {{$errors->has('typeName') ? 'has-error' : ''}}">
                {!! Form::label('typeName','Type Name: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-9">
                    {!! Form::text('typeName', $data->typeName, ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
                    {!! $errors->first('typeName','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group col-md-12 {{$errors->has('details') ? 'has-error' : ''}}">
                {!! Form::label('details','Details: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('details', $data->details, ['class'=>'form-control bnEng required', 'id'=>'details_editor']) !!}
                    {!! $errors->first('details','<span class="help-block">:message</span>') !!}
                </div>
            </div>


            <div class="form-group col-md-8 {{$errors->has('pdfFile') ? 'has-error' : ''}}">
                {!! Form::label('pdfFile','Pdf File: ',['class'=>'col-md-3  required-star']) !!}
                <div class="col-md-7">
                    <span id="pdf_file_err" class="text-danger" style="font-size: 10px;"></span>
                    {!! Form::file('pdfFile',['class'=> !empty($data->pdfFile)?'':'required', 'id' => 'pdfFile',
                    'data-rule-maxlength'=>'40','onchange'=>'pdfFileFunction(this)'])!!}
                    <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | File size within 2 MB]</span><br/>
                    <div style="position:relative;">

                        

                        <a href="{{ (!empty($data->pdfFile) ? url($data->pdfFile) : '') }}" target="_blank" rel="noopener">{{$filepdf}}</a>
                        <input type="hidden" name="exist_pdf" value="{{$data->pdfFile}}">

                        <!-- <img id="pdfFileFunctionViewer" style="width:110px;height:70px; position:absolute;top:-56px;right:0px;border:1px solid #ddd;padding:2px;background:#a1a1a1;"
                             src="{{ (!empty($logoInfo->logo)? url($logoInfo->logo) : '') }}" alt=""> -->
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12 {{$errors->has('termsCondition') ? 'has-error' : ''}}">
                {!! Form::label('termsCondition','Terms & Condition: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('termsCondition', $data->termsCondition, ['class'=>'form-control bnEng required', 'id'=>'termsCondition_editor', 'rows' => 3, 'cols' => 40]) !!}
                    {!! $errors->first('termsCondition','<span class="help-block">:message</span>') !!}
                </div>
            </div>
            

            <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status','Status: ',['class'=>'col-md-2 required-star']) !!}
                <div class="col-md-7">
                    
                    @if(ACL::getAccsessRight('settings','E'))
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', '1', $data->status  == '1', ['class'=>' required', 'id' => 'yes']) !!} Active</label>
                    &nbsp;&nbsp;
                    <label>{!! Form::radio('status', '0', $data->status == '0', ['class'=>'required', 'id' => 'no']) !!} Inactive</label>
                    @endif
                    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-12">
                <a href="{{ url('/settings/user-manual') }}">
                    {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                </a>
                @if(ACL::getAccsessRight('settings','A'))
                <button type="submit" class="btn btn-primary pull-right">
                    <i class="fa fa-chevron-circle-right"></i> Save</button>
                @endif
            </div><!-- /.box-footer -->

            {!! Form::close() !!}<!-- /.form end -->

            <div class="overlay" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div><!-- /.box -->
    </div>
</div> 

@endsection


@section('footer-script')
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
<script>
    var _token = $('input[name="_token"]').val();

    var age = -1;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $("#notice-info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
    function pdfFileFunction(input) {
        if (input.files && input.files[0]) {
            $("#pdf_file_err").html('');
            var mime_type = input.files[0].type;
            if(!(mime_type=='application/pdf' )){
                alert("Pdf File format is not valid. Only Pdf are allowed.");
                $("#pdfFile").val(''); 

                $("#pdf_file_err").html("Pdf File format is not valid. Only Pdf are allowed.");
                return false;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#companyLogoViewer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    //   tinymce.init({ selector:'#description_editor' });
    tinymce.init({
        selector: '#details_editor',
        height: 150,
        theme: 'modern',
        plugins: [
            'autosave advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        content_css: [
            // '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
</script>

@endsection <!--- footer script--->