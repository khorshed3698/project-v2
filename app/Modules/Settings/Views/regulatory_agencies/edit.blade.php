@extends('layouts.admin')

@section('page_heading',trans('messages.regulatory_agency_form'))

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
                <h5><b>Regulatory agency info edit </b></h5>
            </div><!-- /.panel-heading -->


            {!! Form::open(array('url' => '/settings/update-regulatory-agency/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'stakeholder',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Stakeholder name: ',['class'=>'col-md-2 required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::text('name', $data->name, ['class' => 'form-control required', 'id' => 'name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('url') ? 'has-error' : ''}}">
                    {!! Form::label('url','URL: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::url('url', $data->url, ['class' => 'form-control url', 'id' => 'url']) !!}
                        {!! $errors->first('url','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('order') ? 'has-error' : ''}}">
                    {!! Form::label('order','Order: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::number('order', $data->order, ['class' => 'form-control number', 'id' => 'order']) !!}
                        {!! $errors->first('order','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('agency_type') ? 'has-error' : ''}}">
                    {!! Form::label('agency_type','Agency type: ',['class'=>'col-md-2 required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">{!! Form::radio('agency_type', 'ipa', $data->agency_type == 'ipa', ['class'=>'required', 'id' => 'ipa']) !!}
                            IPA</label>
                        <label class="radio-inline">{!! Form::radio('agency_type', 'clp', $data->agency_type == 'clp', ['class'=>'required', 'id' => 'clp']) !!}
                            CLP</label>
                        <label class="radio-inline">{!! Form::radio('agency_type', 'utility', $data->agency_type == 'utility', ['class'=>'required', 'id' => 'utility']) !!}
                            Utility</label>
                        {!! $errors->first('agency_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('contact_name') ? 'has-error' : ''}}">
                    {!! Form::label('contact_name','Contract name: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('contact_name', $data->contact_name, ['class' => 'form-control', 'id' => 'contact_name']) !!}
                        {!! $errors->first('contact_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                    {!! Form::label('designation','Designation: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('designation', $data->designation, ['class' => 'form-control', 'id' => 'designation']) !!}
                        {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('mobile') ? 'has-error' : ''}}">
                    {!! Form::label('mobile','Mobile: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('mobile', $data->mobile, ['class' => 'form-control', 'id' => 'mobile']) !!}
                        {!! $errors->first('mobile','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                    {!! Form::label('phone','Phone: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::text('phone', $data->phone, ['class' => 'form-control', 'id' => 'phone']) !!}
                        {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email','Email: ',['class'=>'col-md-2']) !!}
                    <div class="col-md-9">
                        {!! Form::email('email', $data->email, ['class' => 'form-control', 'id' => 'email']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('description') ? 'has-error' : ''}}">
                    {!! Form::label('description','Description:',['class'=>'col-md-2']) !!}
                    <div class="col-md-9 maxTextCountDown">
                        {!! Form::textarea('description',$data->description, ['placeholder'=>'Write Description Here', 'class' => 'form-control input-md',
                            'id'=>'description_editor','maxlength'=>'250']) !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-2 required-star']) !!}
                    <div class="col-md-9">
                        @if(ACL::getAccsessRight('settings','E'))
                            <label class="radio-inline">{!! Form::radio('status', '1', $data->status  == '1', ['class'=>' required', 'id' => 'yes']) !!}
                                Active</label>
                            <label class="radio-inline">{!! Form::radio('status', '0', $data->status == '0', ['class'=>'required', 'id' => 'no']) !!}
                                Inactive</label>
                        @endif
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-12">

                </div><!-- /.box-footer -->

            </div><!-- /.box -->
            <div class="panel-footer">
                <div class="pull-left">
                    {!! App\Libraries\CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('/settings/regulatory-agency') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','E'))
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-chevron-circle-right"></i> Save
                        </button>
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
            $("#stakeholder").validate({
                errorPlacement: function () {
                    return false;
                }
            });


            $("#department").trigger('change');
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

    <style>
        input[type="radio"].error {
            outline: 1px solid red
        }
    </style>
@endsection <!--- footer script--->