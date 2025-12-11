@extends('layouts.admin')
@section('content')
    <style>
        ul, ol {
            list-style-type: none;
        }
    </style>
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
    ?>
    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>{!! trans('messages.create_service') !!}</strong></h5>
            </div>
            <!-- /.panel-heading -->
            {!! Form::open(array('url' => 'settings/service-details-save', 'method' => 'post','id'=>'reg_form1', 'enctype' => 'multipart/form-data', 'role'=>'form' )) !!}
            <div class="panel-body">
                    <div class="form-group col-md-12 {{$errors->has('process_type_id') ? 'has-error' : ''}} ">
                        {!! Form::label('process_type_id', 'Process Type:', ['class' => 'col-md-2 required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::select('process_type_id', $services, null, ['class' => 'form-control required']) !!}
                            {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name','Description:',['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-9 maxTextCountDown">
                            {!! Form::textarea('description','', ['placeholder'=>'Write Description Here', 'class' => 'form-control input-md',
                                'id'=>'description_editor','maxlength'=>'250']) !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('terms_and_conditions') ? 'has-error' : ''}}">
                        {!! Form::label('Terms and Condition','Terms and Condition : ',['class'=>'col-md-2 required-star']) !!}
                        <div class="col-md-9">
                            <input type="file" name="terms_and_conditions" id="terms_and_conditions" class="form-control required"/>
                            {!! $errors->first('terms_and_conditions','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('is_active','Active Status: ',['class'=>'col-md-4 required-star']) !!}
                        <div class="col-md-8 {{$errors->has('is_active') ? 'has-error' : ''}}">
                            <label>{!! Form::radio('is_active', '1', true, ['class'=>'required', 'id' => 'yes']) !!}
                                Active</label>
                            <label>{!! Form::radio('is_active', '0', false, ['class'=>' required', 'id' => 'no']) !!}
                                Inactive</label>
                            {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/service-info') }}" class="btn btn-default"><i class="fa fa-close"></i> Close</a>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('user','E'))
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            var _token = $('input[name="_token"]').val();
            $("#reg_form1").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

    </script>
    <script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" src="" type="text/javascript"></script>
    <script>
        //textarea count down
        $('.maxTextCountDown').maxlength();

        //   tinymce.init({ selector:'#description_editor' });
        tinymce.init({
            selector: '#description_editor',
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
@endsection