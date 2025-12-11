@extends('layouts.admin')

@section('page_heading',trans('messages.stakeholder_form'))

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
                <h5><b> Stakeholder info edit </b></h5>
            </div><!-- /.panel-heading -->


            {!! Form::open(array('url' => '/settings/update-payment-stakeholder/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'stakeholder',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Name: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('name', $data->name, ['class' => 'form-control required', 'id' => 'name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                    {!! Form::label('description','Description: ',['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('description', $data->description, ['class' => 'form-control', 'id' => 'description']) !!}
                        {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                    {!! Form::label('account_name','Account Name: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('account_name', $data->account_name, ['class' => 'form-control required', 'id' => 'account_name']) !!}
                        {!! $errors->first('account_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                    {!! Form::label('account_no','Account No.: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('account_no', $data->account_no, ['class' => 'form-control required', 'id' => 'account_no']) !!}
                        {!! $errors->first('account_no','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email','E-mail: ',['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('email', $data->email, ['class' => 'form-control email', 'id' => 'email']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('mobile_no') ? 'has-error' : ''}}">
                    {!! Form::label('mobile_no','Mobile No.: ',['class'=>'col-md-3 control-label']) !!}
                    <div class="col-md-5">
                        {!! Form::text('mobile_no', $data->mobile_no, ['class' => 'form-control number', 'id' => 'mobile_no']) !!}
                        {!! $errors->first('mobile_no','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5" style="margin-top: 7px;">
                        @if(ACL::getAccsessRight('settings','E'))
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '1', $data->status  == '1', ['class'=>' required']) !!} Active</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '0', $data->status == '0', ['class'=>'required']) !!} Inactive</label>
                        @endif
                        {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
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
                    <a href="{{ url('/settings/stakeholder') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','E'))
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

            $("#department").trigger('change');
        });
    </script>

    <style>
        input[type="radio"].error{
            outline: 1px solid red
        }
    </style>
@endsection <!--- footer script--->