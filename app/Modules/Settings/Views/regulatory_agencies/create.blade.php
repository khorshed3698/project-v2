@extends('layouts.admin')

@section('page_heading',trans('messages.regulatory_agency_form'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'A')) die('no access right!');
    ?>
    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {!!trans('messages.new_regulatory_agency')!!} </b></h5>
            </div>

        {!! Form::open(array('url' => '/settings/store-regulatory-agency','method' => 'post', 'class' => 'form-horizontal',
            'enctype' =>'multipart/form-data', 'id' => 'regulatory-agency', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::text('name', null, ['class' => 'form-control required', 'id' => 'name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('url') ? 'has-error' : ''}}">
                    {!! Form::label('url','URL: ',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9">
                        {!! Form::url('url', null, ['class' => 'form-control url', 'id' => 'url']) !!}
                        {!! $errors->first('url','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('order') ? 'has-error' : ''}}">
                    {!! Form::label('order','Order: ',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9">
                        {!! Form::number('order', null, ['class' => 'form-control number', 'id' => 'order']) !!}
                        {!! $errors->first('order','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('agency_type') ? 'has-error' : ''}}">
                    {!! Form::label('agency_type','Agency type: ',['class'=>'col-md-2   required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">{!! Form::radio('agency_type', 'ipa', false, ['class'=>'required', 'id' => 'ipa']) !!} IPA</label>
                        <label class="radio-inline">{!! Form::radio('agency_type', 'clp', false, ['class'=>'required', 'id' => 'clp']) !!} CLP</label>
                        <label class="radio-inline">{!! Form::radio('agency_type', 'utility', false, ['class'=>'required', 'id' => 'utility']) !!} Utility</label>
                        {!! $errors->first('agency_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('contact_name') ? 'has-error' : ''}}">
                    {!! Form::label('contact_name','Contract name: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('contact_name', null, ['class' => 'form-control', 'id' => 'contact_name']) !!}
                        {!! $errors->first('contact_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                    {!! Form::label('designation','Designation: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('designation', null, ['class' => 'form-control', 'id' => 'designation']) !!}
                        {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('mobile') ? 'has-error' : ''}}">
                    {!! Form::label('mobile','Mobile: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('mobile', null, ['class' => 'form-control', 'id' => 'mobile']) !!}
                        {!! $errors->first('mobile','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                    {!! Form::label('phone','Phone: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone']) !!}
                        {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email','Email: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>


                <div class="form-group col-md-12 {{$errors->has('description') ? 'has-error' : ''}}">
                    {!! Form::label('description','Description:',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9 maxTextCountDown">
                        {!! Form::textarea('description','', ['placeholder'=>'Write Description Here', 'class' => 'form-control input-md',
                           'maxlength'=>'250', 'id'=>'description_editor']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        <label>{!! Form::radio('is_active', '1', ['class'=>'cursor form-control required', 'id'=>'yes']) !!} Active</label>
                        &nbsp;&nbsp;
                        <label>{!! Form::radio('is_active', '0', ['class'=>'cursor form-control required', 'id'=>'no']) !!} Inactive</label>
                        &nbsp;&nbsp;
                        {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            </div><!-- /.box -->

            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/regulatory-agency') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
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
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#regulatory-agency").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

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
    <script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" src="" type="text/javascript"></script>
    <script>
        //textarea count down
        $('.maxTextCountDown').maxlength();
    </script>
@endsection <!--- footer script--->