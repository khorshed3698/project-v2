@extends('layouts.admin')
@section('content')
    <div class="col-md-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <b><i class="fa fa-list"></i> Create Airport </b>
            </div>

            <div class="panel-body">
                {!! Form::open(array('url' => '/settings/airport/store','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'airport-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                <div class="form-group col-md-12" {{$errors->has('code') ? 'has-error' : ''}}>
                    {!! Form::label('name','Code: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('code', '', ['class'=>'form-control required','placeholder'=>'Airport code']) !!}
                        {!! $errors->first('code','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12" {{$errors->has('name') ? 'has-error' : ''}}>
                    {!! Form::label('name','Name: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('name', '', ['class'=>'form-control required','placeholder'=>'Airport name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-12" {{$errors->has('email') ? 'has-error' : ''}}>
                    {!! Form::label('email','Email: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('email', '', ['class'=>'form-control required','placeholder'=>'Email']) !!}
                        {!! $errors->first('email','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-12" {{$errors->has('city_name') ? 'has-error' : ''}}>
                    {!! Form::label('city_name','City: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('city_name', '', ['class'=>'form-control required','placeholder'=>'City']) !!}
                        {!! $errors->first('city_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-12" {{$errors->has('country_name') ? 'has-error' : ''}}>
                    {!! Form::label('country_name','Country: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('country_name', '', ['class'=>'form-control required','placeholder'=>'Country']) !!}
                        {!! $errors->first('country_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="col-md-12">
                    <a href="{{ url('/settings/airport/list') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','A'))
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save</button>
                    @endif
                </div>
                <div class="clearfix"></div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
<script>

    $(document).ready(function () {
        $("#airport-info").validate({
            errorPlacement: function () {
                return false;
            }
        });
    });
</script>
@endsection
