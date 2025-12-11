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
                                <strong>A. General Information ( as of Memorandum and Articles o Association, Form - VI)</strong>
                            </div>
                            {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> '')) !!}

                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('entry_name','1. Name of the Entity',['class'=>'col-md-4 text-left']) !!}
                                            <div class="col-md-8">
                                                <span>Code Orbit Engineering Limited</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! Form::label('entry_type','2. Entity Type',['class'=>'col-md-4 text-left']) !!}
                                            <div class="col-md-8">
                                                <span>Private Company</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('liability_type','3. Liability Type',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-3">
                                                {!! Form::select('',['Limited by Gruadate' => 'Limited by Gruadate'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-5"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','4. Address of the Entity',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
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
                                                    <div class="col-md-5">
                                                        {!! Form::label('','District',['class'=>'col-md-8 text-left required-star']) !!}
                                                    </div>
                                                    <div class="col-md-7">
                                                        {!! Form::select('',['' => ''], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','4.1 . Entity Email Address',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::email('', '', ['class' => 'form-control input-md required','placeholder' => 'Entity Email']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','5. Main Business objective',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Business Objective']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','6. Business Sector',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::select('',['Local' => 'Local','Foregin' => 'Foregin','Others' => 'Others'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','7. Business Sub-Sector',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('',['Local Sub' => 'Local Sub','Foregin Sub' => 'Foregin Sub','Others Sub' => 'Others Sub'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','8. Authorized Capital (BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'BDT']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4">
                                                <span>{Authorized Capital =<br/> {Shares No.} X {Value of each Share}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','9. Number of shares',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Number of Share']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','10. Value of each share(BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Value of each share(BDT)']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','11. Minimum No of Directors',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4">
                                                <span>(Minimum Two{2})</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','12. ',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4">
                                                <span>(Maximum fifty{50})</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','13. Quorum of AGM/EGM',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <span>(Maximum tow{2})</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Three']) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>In word</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','14. Quorum of Board of Directors Meeting',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <span>(Maximum tow{2})</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Three']) !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <span>In word</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','15. Duration for Chairmanship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                            {!! Form::label('','16. Duration for Managing Directorship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                            <div class="col-md-4"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-2 col-md-offset-5">
                                    <a href="{{ url('/new-reg-page/particulars-subscriber') }}" class="btn btn-info">Continue</a>
                                </div>

                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
@endsection
@section('footer-script')

@endsection