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

        {!! Form::open(array('url' => '/settings/store-stakeholder','method' => 'post', 'class' => 'form-horizontal',
            'enctype' =>'multipart/form-data', 'id' => 'stakeholder', 'files' => 'true', 'role' => 'form')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="form-group col-md-12 {{$errors->has('department_id') ? 'has-error' : ''}}">
                    {!! Form::label('department','Department: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('department_id', $departments, null, ['class' => 'form-control required','placeholder' => 'Select department', 'id' => 'department', 'onchange'=>"getProcessByDepartment('department', this.value, 'service')"]) !!}
                        {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('process_type_id') ? 'has-error' : ''}}">
                    {!! Form::label('service','Service: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::select('process_type_id', [], null, ['class' => 'form-control required','placeholder' => 'Select department at first!', 'id' => 'service']) !!}
                        {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                    {!! Form::label('name','Stakeholder name: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('name', null, ['class' => 'form-control required', 'id' => 'name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('designation') ? 'has-error' : ''}}">
                    {!! Form::label('designation','Designation: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('designation', null, ['class' => 'form-control required', 'id' => 'designation']) !!}
                        {!! $errors->first('designation','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('email') ? 'has-error' : ''}}">
                    {!! Form::label('email','E-mail: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('email', null, ['class' => 'form-control required email', 'id' => 'email']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('phone') ? 'has-error' : ''}}">
                    {!! Form::label('phone','Phone number: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('phone', null, ['class' => 'form-control required number', 'id' => 'phone']) !!}
                        {!! $errors->first('phone','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('address') ? 'has-error' : ''}}">
                    {!! Form::label('address','Office address: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('address', null, ['class' => 'form-control required', 'id' => 'address']) !!}
                        {!! $errors->first('address','<span class="help-block">:message</span>') !!}
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