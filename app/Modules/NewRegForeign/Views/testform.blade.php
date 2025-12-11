@extends('layouts.admin')
@section('content')
    <div class="panel panel-info">
        <div class="panel-heading"><strong>Test Form</strong></div>
        <div class="panel-body">
    {!! Form::open(['url'=>'new-reg-foreign/test-data/store','method' => 'post']) !!}
    <div class="form-group">
        <div class="row">
            <div class="col-md-8">
                {!! Form::label('name','RJSC Incorporation Number:',['class'=>'col-md-4 text-left']) !!}
                <div class="col-md-8">
                    {!! Form::text('name','',['class'=>'form-control']) !!}

                </div>
            </div>
{{--            <div class="col-md-8">--}}
{{--                {!! Form::label('token','Token:',['class'=>'col-md-4 text-left']) !!}--}}
{{--                <div class="col-md-8">--}}
{{--                    {!! Form::text('token',$token,['class'=>'form-control']) !!}--}}

{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    <div class="">
        <div class="col-md-8 text-right">
            <button class="btn btn-success"  value="submit" type="submit">Submit</button>
        </div>
    </div>
</div>
</div>

    {!! Form::close() !!}
@endsection