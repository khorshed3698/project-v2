@extends('layouts.admin')

@section('page_heading',trans('messages.holiday_form'))

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
                <h5><b> {!!trans('messages.holiday_edit')!!} </b></h5>
            </div><!-- /.panel-heading -->


            {!! Form::open(array('url' => '/settings/update-holiday/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'holiday',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('title','Title: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('title', $data->title, ['class' => 'form-control required', 'id' => 'phone']) !!}
                        {!! $errors->first('title','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('date') ? 'has-error': ''}}">
                    {!! Form::label('date','Date',['class'=>'control-label required-star col-md-3']) !!}
                    <div class="col-md-5">
                        <div class="datepicker input-group date">
                            {!! Form::text('date', empty($data->holiday_date) ? '' : date('d-M-Y', strtotime($data->holiday_date)), ['class' => 'form-control input-md required', 'placeholder'=>'dd-mm-yyyy']) !!}
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                        {!! $errors->first('date','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('is_active') ? 'has-error' : ''}}">
                    {!! Form::label('is_active','Status: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5" style="margin-top: 7px;">
                        @if(ACL::getAccsessRight('settings','E'))
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('is_active', '1', $data->is_active  == '1', ['class'=>' required', 'id' => 'yes']) !!} Active</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('is_active', '0', $data->is_active == '0', ['class'=>'required', 'id' => 'no']) !!} Inactive</label>
                        @endif
                        {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-12">

                </div><!-- /.box-footer -->

            </div><!-- /.box -->
            <div class="panel-footer">
                <div class="col-md-2">
                    <div class="pull-left">
                        <a href="{{ url('/settings/holiday') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="pull-left">
                        {!! App\Libraries\CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="pull-right">
                        @if(ACL::getAccsessRight('settings','E'))
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-chevron-circle-right"></i> Save</button>
                        @endif
                    </div>
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

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#holiday").validate({
                errorPlacement: function () {
                    return false;
                }
            });


            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
            });
        });
    </script>

    <style>
        input[type="radio"].error{
            outline: 1px solid red
        }
    </style>
@endsection <!--- footer script--->