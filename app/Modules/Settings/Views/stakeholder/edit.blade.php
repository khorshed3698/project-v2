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


            {!! Form::open(array('url' => '/settings/update-stakeholder/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'stakeholder',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">
                <div class="form-group col-md-12 {{$errors->has('department_id') ? 'has-error' : ''}}">
                    {!! Form::label('department','Department: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('department_id', $departments, $data->department_id, ['class' => 'form-control required','placeholder' => 'Select department', 'id' => 'department', 'onchange'=>"getProcessByDepartment('department', this.value, 'service',".$data->process_type_id.")"]) !!}
                        {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('process_type_id') ? 'has-error' : ''}}">
                    {!! Form::label('process_type_id','Service: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('process_type_id', [], $data->process_type_id, ['class' => 'form-control required','placeholder' => 'Select service', 'id' => 'service',]) !!}
                        {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Stakeholder name: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('name', $data->name, ['class' => 'form-control required', 'id' => 'name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                    {!! Form::label('designation','Designation: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('designation', $data->designation, ['class' => 'form-control required', 'id' => 'designation']) !!}
                        {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email','E-mail: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('email', $data->email, ['class' => 'form-control required email', 'id' => 'email']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                    {!! Form::label('phone','Phone number: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('phone', $data->phone, ['class' => 'form-control required number', 'id' => 'phone']) !!}
                        {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                    {!! Form::label('address','Office address: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('address', $data->address, ['class' => 'form-control required', 'id' => 'address']) !!}
                        {!! $errors->first('address','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5" style="margin-top: 7px;">
                        @if(ACL::getAccsessRight('settings','E'))
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '1', $data->status  == '1', ['class'=>' required', 'id' => 'yes']) !!} Active</label>
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', '0', $data->status == '0', ['class'=>'required', 'id' => 'no']) !!} Inactive</label>
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