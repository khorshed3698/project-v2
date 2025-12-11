@extends('layouts.admin')

@section('page_heading',trans('messages.process_category_form'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'A')) die('no access right!');
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {!!trans('messages.new_process_category')!!} </b></h5>
            </div>
        {!! Form::open(array('url' => '/settings/store-process-category','method' => 'post', 'class' => 'form-horizontal',
            'enctype' =>'multipart/form-data', 'id' => 'processCategory', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('organization_id') ? 'has-error' : ''}}">
                    {!! Form::label('organization_id','Organization: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::select('organization_id', $organizations, null, ['class' => 'form-control required','placeholder' => 'Select organization', 'id' => 'organization_id']) !!}
                        {!! $errors->first('organization_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('department_id') ? 'has-error' : ''}}">
                    {!! Form::label('department_id','Department: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::select('department_id', $departments, null, ['class' => 'form-control required','placeholder' => 'Select department', 'id' => 'department_id', 'onchange'=>"getProcessByDepartment('department_id', this.value, 'process_type_id')"]) !!}
                        {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('process_type_id') ? 'has-error' : ''}}">
                    {!! Form::label('process_type_id','Process type: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::select('process_type_id', [], null, ['class' => 'form-control required','placeholder' => 'Select service', 'id' => 'process_type_id']) !!}
                        {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('app_type_id') ? 'has-error' : ''}}">
                    {!! Form::label('app_type_id','Service category/ Application type:',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::select('app_type_id', $appTypes, '', ['class' => 'form-control required', 'placeholder' => 'Select service', 'id' => 'app_type_id']) !!}
                        {!! $errors->first('app_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('certificate_text') ? 'has-error' : ''}}">
                    {!! Form::label('certificate_text','Certificate footer text:',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('certificate_text', '', ['class'=>'form-control bnEng required', 'id'=>'certificate_text']) !!}
                        {!! $errors->first('certificate_text','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('app_instruction') ? 'has-error' : ''}}">
                    {!! Form::label('app_instruction','Service/ Application Instruction:',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('app_instruction', '', ['class'=>'form-control bnEng required', 'id'=>'app_instruction']) !!}
                        {!! $errors->first('app_instruction','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div><!-- /.box -->

            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/process-category') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div><!-- /.box-footer -->
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
        {!! Form::close() !!}<!-- /.form end -->
        </div>
    </div>

@endsection

@section('footer-script')
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $("#processCategory").validate({
                errorPlacement: function () {
                    return false;
                }
            });

            //   tinymce.init({ selector:'#certificate_text' });
            tinymce.init({
                selector: '#certificate_text, #app_instruction',
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
        });
    </script>
    <script>
        $(document).ajaxComplete(function( ) {
            $('form').find('select').each(function() {
                var length = $(this).find('option').length;
                if (length == 1 || length == 2) {
                    var first_value = $(this).find('option:first').val();
                    if (first_value) {
                        $(this)[0].selectedIndex = 0;
                    } else {
                        $(this)[0].selectedIndex = 1;
                    }
                }
            });
        });
    </script>
@endsection <!--- footer script--->