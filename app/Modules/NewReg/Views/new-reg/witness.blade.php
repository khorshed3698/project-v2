@extends('layouts.admin')
@section('content')
    <style>
        #app-form label.error {
            display: none !important;
        }
    </style>
    <section class="content" id="">
        <div class="col-md-12">
            <div class="col-md-12" style="padding:0px;">
                <div class="box">
                    <div class="box-body">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong>D. Witnesses</strong>
                            </div>
                            {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> '')) !!}
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="text-center"><strong>Witness 1</strong></h4>
                                        <div class="form-group row">
                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('','2. Address',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::number('', '', ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::number('[', '', ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-center"><strong>Witness 2</strong></h4>
                                        <div class="form-group row">
                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            {!! Form::label('','2. Address',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::number('', '', ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-8">
                                                {!! Form::number('[', '', ['class' => 'form-control input-md required']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <strong><i class="fas fa-list"></i>&nbsp;&nbsp; D. Forms/Documents Presented for Filing
                                    By</strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','2. Position',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-4">
                                                {!! Form::select('',['Chairman' => 'Chairman','CEO' => 'CEO','MD' => 'MD'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','3. Address',['class'=>'col-md-4 text-left ']) !!}
                                            <div class="col-md-4">
                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        {!! Form::label('','District',['class'=>'col-md-4 text-left ']) !!}
                                                    </div>
                                                    <div class="col-md-8">
                                                        {!! Form::select('',['Dhaka' => 'Dhaka','Feni' => 'Feni'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-2 col-md-offset-5">
                            <a href="{{ url('/new-reg-page/declaration-upload') }}" class="btn btn-info">Continue</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer-script')

@endsection