@extends('layouts.admin')

@section('page_heading',trans('messages.regulatory_agency_details_form'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'A')) die('no access right!');
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {!!trans('messages.new_regulatory_agency_details')!!} </b></h5>
            </div>

        {!! Form::open(array('url' => '/settings/store-regulatory-agency-details','method' => 'post', 'class' => 'form-horizontal',
            'enctype' =>'multipart/form-data', 'id' => 'regulatory-agency', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group col-md-12 {{$errors->has('regulatory_agencies_id') ? 'has-error' : ''}} ">
                    {!! Form::label('regulatory_agencies_id', 'Regulatory Agency:', ['class' => 'col-md-2 required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('regulatory_agencies_id', $regulatoryAgencies, null, ['class' => 'form-control required']) !!}
                        {!! $errors->first('regulatory_agencies_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('service_name') ? 'has-error' : ''}}">
                    {!! Form::label('service_name','Service Name: ',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9">
                        {!! Form::text('service_name', null, ['class' => 'form-control', 'id' => 'service_name']) !!}
                        {!! $errors->first('service_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('is_online') ? 'has-error' : ''}}">
                    {!! Form::label('is_online','Is Online: ',['class'=>'col-md-2   required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">{!! Form::radio('is_online', '1', false, ['class'=>'required', 'id' => 'is_online_yes']) !!} YES</label>
                        <label class="radio-inline">{!! Form::radio('is_online', '0', false, ['class'=>'required', 'id' => 'is_online_no']) !!} NO</label>
                        {!! $errors->first('is_online','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('method_of_recv_service') ? 'has-error' : ''}}">
                    {!! Form::label('method_of_recv_service','Methods of receiving service:',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9 maxTextCountDown">
                        {!! Form::textarea('method_of_recv_service','', ['placeholder'=>'Write details Here', 'class' => 'form-control input-md',
                           'maxlength'=>'250', 'id'=>'recv_service_editor']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('who_get_service') ? 'has-error' : ''}}">
                    {!! Form::label('who_get_service','Who get service:',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9 maxTextCountDown">
                        {!! Form::textarea('who_get_service','', ['placeholder'=>'Write details Here', 'class' => 'form-control input-md',
                           'maxlength'=>'250', 'id'=>'get_service_editor']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('documents') ? 'has-error' : ''}}">
                    {!! Form::label('documents','Documents:',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9 maxTextCountDown">
                        {!! Form::textarea('documents','', ['placeholder'=>'Write details Here', 'class' => 'form-control input-md',
                           'maxlength'=>'250', 'id'=>'documents_editor']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('fees') ? 'has-error' : ''}}">
                    {!! Form::label('fees','Fees: ',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-9">
                        {!! Form::text('fees', null, ['class' => 'form-control', 'id' => 'fees']) !!}
                        {!! $errors->first('fees','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">{!! Form::radio('status', '1', false, ['class'=>'cursor required', 'id' => 'yes']) !!} Active</label>
                        <label class="radio-inline">{!! Form::radio('status', '0', false, ['class'=>'cursor required', 'id' => 'no']) !!} Inactive</label>
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            </div><!-- /.box -->

            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/regulatory-agency-details') }}">
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

        //   tinymce.init({ selector:'#recv_service_editor' });
        tinymce.init({
            selector: '#recv_service_editor',
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

        //   tinymce.init({ selector:'#get_service_editor' });
        tinymce.init({
            selector: '#get_service_editor',
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

        //   tinymce.init({ selector:'#documents_editor' });
        tinymce.init({
            selector: '#documents_editor',
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