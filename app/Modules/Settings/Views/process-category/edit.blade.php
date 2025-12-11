@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'E')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {!!trans('messages.process_category_edit')!!} </b></h5>
            </div><!-- /.panel-heading -->

            {!! Form::open(array('url' => '/settings/update-process-category/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'processCategory',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">
                <div class="form-group col-md-12 {{$errors->has('organization_id') ? 'has-error' : ''}}">
                    {!! Form::label('organization_id','Organization: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('organization_id', $organizations, $data->organization_id, ['class' => 'form-control required','placeholder' => 'Select organization', 'id' => 'organization_id']) !!}
                        {!! $errors->first('organization_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('department_id') ? 'has-error' : ''}}">
                    {!! Form::label('department_id','Department: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('department_id', $departments, $data->department_id, ['class' => 'form-control required','placeholder' => 'Select department', 'id' => 'department_id', 'onchange'=>"getProcessByDepartment('department_id', this.value, 'process_type_id',".$data->process_type_id.")"]) !!}
                        {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('process_type_id') ? 'has-error' : ''}}">
                    {!! Form::label('process_type_id','Process type: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('process_type_id', [], $data->process_type_id, ['class' => 'form-control required','placeholder' => 'Select service', 'id' => 'process_type_id']) !!}
                        {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('app_type_id') ? 'has-error' : ''}}">
                    {!! Form::label('app_type_id','Service category/ Application type: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('app_type_id', $appTypes, $data->app_type_id, ['class' => 'form-control required', 'placeholder' => 'Select service', 'id' => 'app_type_id']) !!}
                        {!! $errors->first('app_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('certificate_text') ? 'has-error' : ''}}">
                    {!! Form::label('certificate_text','Certificate footer text:',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('certificate_text',  stripslashes($data->certificate_text), ['class'=>'form-control bnEng required', 'id'=>'certificate_text']) !!}
                        {!! $errors->first('certificate_text','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('app_instruction') ? 'has-error' : ''}}">
                    {!! Form::label('app_instruction','Service/ Application Instruction:',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('app_instruction',  $data->app_instruction, ['class'=>'form-control bnEng required', 'id'=>'app_instruction']) !!}
                        {!! $errors->first('app_instruction','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5" style="margin-top: 7px;">
                        @if(ACL::getAccsessRight('settings','E'))
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '1', $data->status  == '1', ['class'=>' required']) !!} Active</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '0', $data->status == '0', ['class'=>'required']) !!} Inactive</label>
                        @endif
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div><!-- /.box -->
            <div class="panel-footer">
                <div class="pull-left">
                    {!! App\Libraries\CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('/settings/process-category') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','E'))
                        <button type="submit" class="btn btn-primary">
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

            $("#department_id").trigger('change');

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

    <style>
        input[type="radio"].error{
            outline: 1px solid red
        }
        .error {
            border: 1px solid red !important;
        }
    </style>
@endsection