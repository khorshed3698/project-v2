@extends('layouts.admin')

@section('page_heading',trans('messages.email_sms_queue'))

@section('content')

    <?php
        $accessMode = ACL::getAccsessRight('settings');
        if (!ACL::isAllowed($accessMode, 'PPR-ESQ')) {
            die('You have no access right! For more information please contact system admin.');
        }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {{ trans('messages.email_sms_queue') }} </b></h5>
            </div><!-- /.panel-heading -->


            {!! Form::open(array('url' => '/settings/update-email-sms-queue/'.\App\Libraries\Encryption::encodeId($emailSmsInfo->id),'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'emailSmsQueue',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('tracking_no') ? 'has-error' : ''}}">
                    {!! Form::label('tracking_no','Tracking No.: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('tracking_no', $emailSmsInfo->tracking_no, ['class' => 'form-control required', 'id' => 'tracking_no', 'readonly']) !!}
                        {!! $errors->first('tracking_no','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                {{--SMS section start--}}
                <div class="form-group col-md-12 {{$errors->has('sms_to') ? 'has-error' : ''}}">
                    {!! Form::label('sms_to','SMS to: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('sms_to', $emailSmsInfo->sms_to, ['class' => 'form-control required', 'id' => 'sms_to']) !!}
                        {!! $errors->first('sms_to','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('sms_content') ? 'has-error' : ''}}">
                    {!! Form::label('sms_content','SMS content: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::textarea('sms_content', $emailSmsInfo->sms_content, ['class' => 'form-control required', 'size' => '5x3']) !!}
                        {!! $errors->first('sms_content','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('sms_status') ? 'has-error' : ''}}">
                    {!! Form::label('sms_status','SMS sending status: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7" style="margin-top: 7px;">
                        @if(ACL::getAccsessRight('settings','PPR-ESQ'))
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('sms_status', '1', $emailSmsInfo->sms_status  == '1', ['class'=>' required']) !!} Yes</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('sms_status', '0', $emailSmsInfo->sms_status == '0', ['class'=>'required']) !!} No</label>
                        @endif
                        {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                {{--Email section start--}}
                <div class="form-group col-md-12 {{$errors->has('email_to') ? 'has-error' : ''}}">
                    {!! Form::label('email_to','Email to: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('email_to', $emailSmsInfo->email_to, ['class' => 'form-control required']) !!}
                        {!! $errors->first('email_to','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email_cc') ? 'has-error' : ''}}">
                    {!! Form::label('email_cc','Email CC: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('email_cc', $emailSmsInfo->email_cc, ['class' => 'form-control required']) !!}
                        {!! $errors->first('email_cc','<span class="help-block">:message</span>') !!}
                        <p class="help-block">Separate email using (&#44;) comma separator.</p>
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email_subject') ? 'has-error' : ''}}">
                    {!! Form::label('email_subject','Email subject: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('email_subject', $emailSmsInfo->email_subject, ['class' => 'form-control required']) !!}
                        {!! $errors->first('email_subject','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email_content') ? 'has-error' : ''}}">
                    {!! Form::label('email_content','Email content:',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('email_content',  $emailSmsInfo->email_content, ['class'=>'form-control bnEng required', 'id'=>'email_content']) !!}
                        {!! $errors->first('email_content','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('sms_status') ? 'has-error' : ''}}">
                    {!! Form::label('email_status','Email sending status: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-7" style="margin-top: 7px;">
                        @if(ACL::getAccsessRight('settings','PPR-ESQ'))
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('email_status', '1', $emailSmsInfo->email_status  == '1', ['class'=>' required']) !!} Yes</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('email_status', '0', $emailSmsInfo->email_status == '0', ['class'=>'required']) !!} No</label>
                        @endif
                        {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-12">

                </div><!-- /.box-footer -->

            </div><!-- /.box -->
            <div class="panel-footer">
                <div class="pull-left">
                    {!! App\Libraries\CommonFunction::showAuditLog($emailSmsInfo->updated_at, $emailSmsInfo->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('/settings/email-sms-queue') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','PPR-ESQ'))
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
            $("#emailSmsQueue").validate({
                errorPlacement: function () {
                    return false;
                }
            });
            
            tinymce.init({
                selector: '#email_content',
                height: 300,
                theme: 'modern',
                plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern',
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true,
                content_css: [
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
@endsection <!--- footer script--->