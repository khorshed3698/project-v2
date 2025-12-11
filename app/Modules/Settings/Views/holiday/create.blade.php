@extends('layouts.admin')

@section('page_heading',trans('messages.holiday_form'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'A')) die('no access right!');
    ?>
    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {!!trans('messages.new_holiday')!!} </b></h5>
            </div>

        {!! Form::open(array('url' => '/settings/store-holiday','method' => 'post', 'class' => 'form-horizontal',
            'enctype' =>'multipart/form-data', 'id' => 'holiday', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('title') ? 'has-error' : ''}}">
                    {!! Form::label('title','Title: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('title', null, ['class' => 'form-control required', 'id' => 'title']) !!}
                        {!! $errors->first('title','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('date') ? 'has-error': ''}}">
                    {!! Form::label('date','Date',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        <div class="datepicker input-group date" data-provide="datepicker">
                            {!! Form::text('date', '', ['class' => 'form-control required', 'placeholder'=>'dd-mm-yyyy']) !!}
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                        {!! $errors->first('date','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            </div><!-- /.box -->

            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/holiday') }}">
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
@endsection <!--- footer script--->