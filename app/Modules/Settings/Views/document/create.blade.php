@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact system admin for more information');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>{!! trans('messages.document_create') !!}</strong></h5>
            </div>

            {!! Form::open(array('url' => '/settings/store-document','method' => 'post', 'class' => 'form-horizontal', 'id' => 'formId',
                                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">
                <div class="col-sm-10">
                    <div class="form-group {{$errors->has('doc_name') ? 'has-error' : ''}}">
                        {!! Form::label('doc_name','Name: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-5">
                            {!! Form::text('doc_name', null, ['class'=>'form-control required input-sm']) !!}
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('short_note:') ? 'has-error' : ''}}">
                        {!! Form::label('short_note','Short note: ',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::text('short_note', null, ['class'=>'form-control input-sm']) !!}
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('process_type_id') ? 'has-error' : ''}}">
                        {!! Form::label('process_type_id','Process type',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5">
                            {!! Form::select('process_type_id', $services, null, ['class' => 'form-control required input-sm', 'placeholder' => 'Select One', 'onchange' => "getAttachmentType(this.value, 'attachment_type_id')"]) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('attachment_type_id') ? 'has-error' : ''}}" id="attachmentTypeId">
                        {!! Form::label('attachment_type_id','Attachment Type',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::select('attachment_type_id', [], null, ['class' => 'form-control input-sm', 'id' => 'attachment_type_id']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('doc_priority') ? 'has-error' : ''}}">
                        {!! Form::label('doc_priority','Priority',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::select('doc_priority', [''=>'Select One','1'=>'Mandatory','0'=>'Not Mandatory'], null, ['class' => 'form-control input-sm']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('order') ? 'has-error' : ''}}">
                        {!! Form::label('order','Order',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::number('order', null, ['class'=>'form-control input-sm', 'placeholder' => 'Numeric number ordering. Ex: 1,2,3']) !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{$errors->has('is_multiple') ? 'has-error' : ''}}">
                        {!! Form::label('is_multiple','Upload Type',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::select('is_multiple', [''=>'Select One','1'=>'Multiple','0'=>'Single'], null, ['class' => 'form-control input-sm']) !!}
                        </div>
                    </div> --}}

                    <div class="form-group {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Business category',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5">
                            <label class="radio-inline">  {!! Form::radio('business_category', '1', true,['class' => 'required', 'id' => 'private']) !!} Private </label>
                            <label class="radio-inline">  {!! Form::radio('business_category', '2', false,['class' => 'required', 'id' => 'government']) !!} Government </label>
                            <label class="radio-inline">  {!! Form::radio('business_category', '3', false,['class' => 'required', 'id' => 'both']) !!} Both </label>
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Status',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            <label class="radio-inline">  {!! Form::radio('status', '1', true,['class' => '', 'id' => 'yes']) !!} Active </label>
                            <label class="radio-inline">  {!! Form::radio('status', '0', false,['class' => '', 'id' => 'no']) !!} Inactive </label>
                        </div>
                    </div>

                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/document') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="pull-right">
                    <button type="submit" class="btn btn-success pull-right">
                        <i class="fa fa-chevron-circle-right"></i> <b>Save</b></button>
                </div>
                <div class="clearfix"></div>
            </div>
        {!! Form::close() !!}<!-- /.form end -->
        </div>

    </div>
@endsection

@section('footer-script')

    <script>
        var _token = $('input[name="_token"]').val();

        $(document).ready(function () {
            $("#formId").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection <!--- footer script--->