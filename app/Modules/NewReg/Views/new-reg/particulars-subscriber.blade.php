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
                                <strong>Office of the Registrar of Joint Stock Companies and Firms</strong>
                            </div>
                            {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> '')) !!}

                            <div class="panel-body">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>B. Particulars of Body Corporate Subscribers ( if any, as of
                                                Memorandum and Aricles of associatio )</strong>
                                        </div>
                                        <div class="panel-body">
                                            <table id="particular" class="table table-bordered table-hover">
                                                <thead>
                                                <tr style="width: 100%;background: #f5f5f7">
                                                    <th width="10%">SL</th>
                                                    <th width="25%">Name (of the corporation body)</th>
                                                    <th width="25%">Represented By (name of the representative)</th>
                                                    <th width="30%">Address (of the body corporate )</th>
                                                    <th width="10%">Number of Subscribed Shares</th>
                                                </tr>
                                                </thead>
                                                <tbody id="particular_body">
                                                <tr>
                                                    <td>
                                                        <input type="checkbox"> &nbsp 1
                                                    </td>
                                                    <td>
                                                        {!! Form::text('','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::textarea('','',['class' => 'col-md-7 form-control input-md required','placeholder' => '','rows' => 2,'cols' => 1]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::text('','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="col-md-4 col-md-offset-4">
                                                <button class="btn btn-info btn-xs col-md-4" id="add_column" type="button">Add Row</button>
                                                <button class="btn btn-danger btn-xs col-md-4" id="remove_column" type="button" style="margin-left:3px ">Remove Row</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <strong>B. Qualification Shares of Each Director (as of Articles of
                                                Association, Form-XI)</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-10 {{$errors->has('') ? 'has-error': ''}}">
                                                        {!! Form::label('liability_type','1. Number of Qualification Shares :',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::number('','',['class' => 'col-md-5 form-control input-md required','placeholder' => '']) !!}
                                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="col-md-10 {{$errors->has('') ? 'has-error': ''}}">
                                                        {!! Form::label('','2. Value of each Share (BDT) :',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::number('','',['class' => 'col-md-5 form-control input-md required','placeholder' => '']) !!}
                                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                    <br>
                                                    <div class="col-md-10 {{$errors->has('') ? 'has-error': ''}}">
                                                        {!! Form::label('','3. Witness to the agreement of taking qualification Shares',['class'=>'col-md-10 text-left required_star']) !!}
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('') ? 'has-error': ''}}">
                                                        {!! Form::label('','a. Name of the Witness :',['class'=>'col-md-5 text-left required_star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::text('','',['class' => 'col-md-5 form-control input-md required','placeholder' => '']) !!}
                                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('') ? 'has-error': ''}}">
                                                        {!! Form::label('','b. Address of Witness :',['class'=>'col-md-5 text-left required_star']) !!}
                                                        <div class="col-md-6">
                                                            {!! Form::textarea('','',['class' => 'col-md-5 form-control input-md required','placeholder' => '', 'rows' => 2, 'cols' => 1]) !!}
                                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                <div class="col-md-4"></div>
                                                                <div class="col-md-4">
                                                                    <div class="row">
                                                                        <div class="col-md-4">

                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            {!! Form::label('','District',['class'=>'col-md-4 text-left']) !!}
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            {!! Form::select('',['' => ''], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
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
                                        </div>

                                        <div class="col-md-2 col-md-offset-5">
                                            <a href="{{ url('/new-reg-page/list-of-subscribers') }}" class="btn btn-info">Continue</a>
                                        </div>
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
    <script>
        $(document).ready(function () {
            $(document).on('click','#add_column',function () {
                var rowCount = $('#particular tr').length;
                $('#particular_body').append('<tr><td><input type="checkbox">&nbsp &nbsp '+ rowCount +' </td> <td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td> <td><textarea class="form-control"></textarea></td> <td><input type="text" class="form-control"></td></tr>')
            })
            $(document).on('click','#remove_column',function () {
                var rowCount = $('#particular tr').length;
                if(rowCount > 2) {
                    $('#particular tr:last').remove();
                }
            })
        })
    </script>
@endsection