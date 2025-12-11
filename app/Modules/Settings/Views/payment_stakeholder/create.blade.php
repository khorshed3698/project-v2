@extends('layouts.admin')

@section('page_heading',trans('messages.stakeholder_form'))

@section('content')
    <?php $accessMode=ACL::getAccsessRight('settings');
    if(!ACL::isAllowed($accessMode,'A')) die('no access right!');
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {!!trans('messages.new_stakeholder')!!} </b></h5>
            </div>

        {!! Form::open(array('url' => '/settings/store-payment-stakeholder','method' => 'post', 'class' => 'form-horizontal',
            'enctype' =>'multipart/form-data', 'id' => 'payment_stakeholder', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Name: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('name', null, ['class' => 'form-control required', 'id' => 'name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                    {!! Form::label('description','Description: ',['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('description', null, ['class' => 'form-control', 'id' => 'description']) !!}
                        {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                    {!! Form::label('account_name','Account Name: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('account_name', null, ['class' => 'form-control required', 'id' => 'account_name']) !!}
                        {!! $errors->first('account_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                {!! Form::label('account_no','Account No.: ',['class'=>'col-md-3 control-label required-star']) !!}
                <div class="col-md-5">
                    {!! Form::text('account_no', null, ['class' => 'form-control required', 'id' => 'account_no']) !!}
                    {!! $errors->first('account_no','<span class="help-block">:message</span>') !!}
                </div>
            </div>

                <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email','E-mail: ',['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('email', null, ['class' => 'form-control email', 'id' => 'email']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('mobile_no') ? 'has-error' : ''}}">
                    {!! Form::label('mobile_no','Mobile No.: ',['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('mobile_no', null, ['class' => 'form-control number', 'id' => 'mobile_no']) !!}
                        {!! $errors->first('mobile_no','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div><!-- /.box -->

            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/stakeholder') }}">
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
            $("#stakeholder").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection <!--- footer script--->