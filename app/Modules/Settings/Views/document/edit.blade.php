@extends('layouts.admin')

@section('page_heading','Document Edit')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'E')) {
        die('You have no access right! Please contact system admin for more information');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>Details of {!! $data->doc_name !!}</strong></h5>
            </div>
            {!! Form::open(array('url' => '/settings/update-document/','method' => 'patch', 'class' => 'form-horizontal', 'id' => 'info',
                                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <input type="hidden" name="id" value="{{ $data->id }}">
            <input type="hidden" name="page" value="edit">

            <div class="panel-body">
                <div class="col-sm-12">
                    <div class="form-group {{$errors->has('doc_name') ? 'has-error' : ''}}">
                        {!! Form::label('doc_name','Name: ',['class'=>'col-md-3  required-star']) !!}
                        <div class="col-md-5">
                            {!! Form::text('doc_name',$data->doc_name, ['class'=>'form-control required input-sm']) !!}
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('short_note') ? 'has-error' : ''}}">
                        {!! Form::label('short_note','Short Note:',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::text('short_note',$data->short_note, ['class'=>'form-control input-sm']) !!}
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('process_type_id') ? 'has-error' : ''}}">
                        {!! Form::label('process_type_id','Process type',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5">
                            {!! Form::select('process_type_id', $processType, $data->process_type_id, ['class' => 'form-control required input-sm', 'placeholder' => 'Select One', 'onchange' => "getAttachmentType(this.value, 'attachment_type_id', ". $data->attachment_type_id .")"]) !!}
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('attachment_type_id') ? 'has-error' : ''}}" id="catIdDiv">
                        {!! Form::label('attachment_type_id','Attachment Type',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::select('attachment_type_id', [], null, ['class' => 'form-control input-sm', 'id' => 'attachment_type_id']) !!}
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('doc_priority') ? 'has-error' : ''}}">
                        {!! Form::label('doc_priority','Priority',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::select('doc_priority', [''=>'Select One','1'=>'Mandatory','0'=>'Not Mandatory'], $data->doc_priority, ['class' => 'form-control input-sm']) !!}
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('order') ? 'has-error' : ''}}">
                        {!! Form::label('order','Order',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::number('order', $data->order, ['class'=>'form-control input-sm', 'placeholder' => 'Numeric number ordering. Ex: 1,2,3']) !!}
                        </div>
                    </div>
                    {{-- <div class="form-group {{$errors->has('is_multiple') ? 'has-error' : ''}}">
                        {!! Form::label('is_multiple','Upload Type',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            {!! Form::select('is_multiple', [''=>'Select One','1'=>'Multiple','0'=>'Single'], $data->is_multiple, ['class' => 'form-control input-sm']) !!}
                        </div>
                    </div> --}}

                    <div class="form-group {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Business category',['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5">
                            <label class="radio-inline">  {!! Form::radio('business_category', '1', ($data->business_category == 1) ? true : false,['class' => 'required', 'id' => 'private']) !!} Private </label>
                            <label class="radio-inline">  {!! Form::radio('business_category', '2', ($data->business_category == 2) ? true : false,['class' => 'required', 'id' => 'government']) !!} Government </label>
                            <label class="radio-inline">  {!! Form::radio('business_category', '3', ($data->business_category == 3) ? true : false,['class' => 'required', 'id' => 'both']) !!} Both </label>
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status','Status',['class'=>'col-md-3']) !!}
                        <div class="col-md-5">
                            <label class="radio-inline">  {!! Form::radio('status', '1', ($data->status == 1)?true:false,['class' => '', 'id' => 'yes']) !!} Active </label>
                            <label class="radio-inline">  {!! Form::radio('status', '0', ($data->status == 0)?true:false,['class' => '', 'id' => 'no']) !!} Inactive </label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    {!! CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('/settings/document') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    <button type="submit" class="btn btn-success"><i class="fa fa-chevron-circle-right"></i> <b>Save</b></button>
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
            $("#info").validate({
                errorPlacement: function () {
                    return false;
                }
            });

            $("#process_type_id").trigger('change');
        });
    </script>
@endsection <!--- footer script--->