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
                            <div class="panel-heading text-center" style="padding: 13px 3px;">
                                <strong class="text-center">Office of the Registrar of Joint Stock Companies and Firms</strong>
                            </div>
                            {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> '')) !!}

                            <div class="panel-body">
                                <h3 class="text-center"><b>Registration Application</b></h3>
                                <p class="text-center"><b> Control Page</b></p>
                                <div class="col-md-8 col-md-offset-2">
                                    <p>You are about to apply for registration online.Click Here to see the <br>guidelines
                                        for online application
                                    </p> <br>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>Select Entity Type</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('liability_type') ? 'has-error': ''}}">
                                                        {!! Form::label('liability_type','Entity Type :',['class'=>'col-md-4 text-left']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::select('',['' => '','' => ''], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                            {!! $errors->first('liability_type','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>Enter Name Clearance Information</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('liability_type') ? 'has-error': ''}}">
                                                        {!! Form::label('liability_type','Submission No :',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                                            {!! $errors->first('liability_type','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('liability_type') ? 'has-error': ''}}">
                                                        {!! Form::label('liability_type','Clearance Letter No :',['class'=>'col-md-5 text-left']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                                            {!! $errors->first('liability_type','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="col-md-2 col-md-offset-5">
                                        <a href="{{ url('/new-reg-page/general-information') }}" class="btn btn-info">Continue</a>
                                    </div>

                                </div>


                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer-script')

@endsection