@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'PPR-ESQ')) {
        die('You have no access right! Please contact system admin for more information');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>Edit pdf print request</strong></h5>
            </div>

            {!! Form::open(array('url' => '/settings/update-pdf-print-requests','method' => 'post', 'class' => 'form-horizontal', 'id' => 'formId',
                                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <input type="hidden" name="id" value="{{ Encryption::encodeId($pdf_print_request->id) }}">
            <div class="panel-body">
                <div class="col-sm-10">
                    <div class="form-group {{$errors->has('tracking_no') ? 'has-error' : ''}}">
                        {!! Form::label('tracking_no','Tracking no : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('tracking_no', $pdf_print_request->tracking_no, ['class'=>'form-control required input-sm', 'readonly']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('job_sending_response') ? 'has-error' : ''}}">
                        {!! Form::label('job_sending_response','Job sending response : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('job_sending_response', $pdf_print_request->job_sending_response, ['class'=>'form-control input-sm', 'readonly', 'size' => '5x3']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('job_receiving_response') ? 'has-error' : ''}}">
                        {!! Form::label('job_receiving_response','Job receiving response : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('job_receiving_response', $pdf_print_request->job_receiving_response, ['class'=>'form-control input-sm', 'readonly', 'size' => '5x3']) !!}
                        </div>
                    </div>

                    {{--<div class="form-group {{$errors->has('reg_key') ? 'has-error' : ''}}">--}}
                        {{--{!! Form::label('reg_key','Reg Key : ',['class'=>'col-md-3']) !!}--}}
                        {{--<div class="col-md-8">--}}
                            {{--{!! Form::text('reg_key', $pdf_print_request->reg_key, ['class'=>'form-control required input-sm']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group {{$errors->has('url_requests') ? 'has-error' : ''}}">--}}
                        {{--{!! Form::label('url_requests','URL Requests : ',['class'=>'col-md-3']) !!}--}}
                        {{--<div class="col-md-8">--}}
                            {{--{!! Form::textarea('url_requests', $pdf_print_request->url_requests, ['class'=>'form-control required input-sm', 'rows' => 4, 'cols' => 54]) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group {{$errors->has('pdf_type') ? 'has-error' : ''}}">--}}
                        {{--{!! Form::label('pdf_type','Pdf Type : ',['class'=>'col-md-3']) !!}--}}
                        {{--<div class="col-md-8">--}}
                            {{--{!! Form::text('pdf_type', $pdf_print_request->pdf_type, ['class'=>'form-control required input-sm']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group {{$errors->has('table_name') ? 'has-error' : ''}}">--}}
                        {{--{!! Form::label('table_name','Table Name : ',['class'=>'col-md-3']) !!}--}}
                        {{--<div class="col-md-8">--}}
                            {{--{!! Form::text('table_name', $pdf_print_request->table_name, ['class'=>'form-control required input-sm', 'rows' => 4, 'cols' => 54]) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group {{$errors->has('certificate_name') ? 'has-error' : ''}}">--}}
                         {{--{!! Form::label('certificate_name','Certificate Name : ',['class'=>'col-md-3']) !!}--}}
                        {{--<div class="col-md-8">--}}
                              {{--{!! Form::text('certificate_name', $pdf_print_request->certificate_name, ['class'=>'form-control required input-sm']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="form-group {{$errors->has('pdf_server_url') ? 'has-error' : ''}}">--}}
                         {{--{!! Form::label('pdf_server_url','PDF server URL : ',['class'=>'col-md-3']) !!}--}}
                        {{--<div class="col-md-8">--}}
                              {{--{!! Form::text('pdf_server_url', $pdf_print_request->pdf_server_url, ['class'=>'form-control required input-sm']) !!}--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="form-group {{$errors->has('job_sending_status') ? 'has-error' : ''}}">
                        {!! Form::label('job_sending_status','Sending status : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('job_sending_status', $pdf_print_request->job_sending_status, ['class'=>'form-control required input-sm']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('no_of_try_job_sending') ? 'has-error' : ''}}">
                        {!! Form::label('no_of_try_job_sending','Sending no of try : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('no_of_try_job_sending', $pdf_print_request->no_of_try_job_sending, ['class'=>'form-control required input-sm']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('job_receiving_status') ? 'has-error' : ''}}">
                        {!! Form::label('job_receiving_status','Receiving status : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('job_receiving_status', $pdf_print_request->job_receiving_status, ['class'=>'form-control required input-sm']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('no_of_try_job_receving') ? 'has-error' : ''}}">
                        {!! Form::label('no_of_try_job_receving','Receiving no of try : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('no_of_try_job_receving', $pdf_print_request->no_of_try_job_receving, ['class'=>'form-control required input-sm']) !!}
                        </div>
                    </div>

                    <div class="form-group {{$errors->has('prepared_json') ? 'has-error' : ''}}">
                        {!! Form::label('prepared_json',' Prepared JSON : ',['class'=>'col-md-3']) !!}
                        <div class="col-md-8">
                            {!! Form::text('prepared_json', $pdf_print_request->prepared_json, ['class'=>'form-control required input-sm']) !!}
                        </div>
                    </div>

                    <div class="overlay" style="display: none;">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('settings/pdf-print-requests') }}">
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