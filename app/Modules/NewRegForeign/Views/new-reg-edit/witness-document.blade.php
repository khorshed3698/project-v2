@extends('layouts.admin')
@section('content')

<fieldset>

    <div class="col-md-12">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {!!Session::get('success') !!}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">
                {!! Session::get('error') !!}
            </div>
        @endif
    </div>

    {!! Form::open(array('url' => '/rjsc-witness-update/'.Encryption::encodeId($app_id), 'method' => 'put', 'files' => true, 'role' => 'form', 'id'=> 'witness_form_edit')) !!}
    {{ csrf_field() }}
    <div class="panel panel-info">
        <div class="panel-heading"><strong>3. Witness </strong></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-center"><strong>Witness 1</strong></h4>
                    <div class="form-group row">
                        {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-8">
                            {!! Form::text('name[]', (!empty($witnessData[0]['name'])) ?  $witnessData[0]['name']  : '', ['class' => 'form-control input-md required']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('','2. Address',['class'=>'col-md-4 text-left']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('address[]', (!empty($witnessData[0]['address'])) ?  $witnessData[0]['address']  : '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-8">
                            {!! Form::text('phone[]', (!empty($witnessData[0]['phone'])) ?  $witnessData[0]['phone']  : '', ['class' => 'form-control input-md required']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-8">
                            {!! Form::text('national_id[]', (!empty($witnessData[0]['national_id'])) ?  $witnessData[0]['national_id']  : '', ['class' => 'form-control input-md required']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="text-center"><strong>Witness 2</strong></h4>
                    <div class="form-group row">
                        {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-8">
                            {!! Form::text('name[]',(!empty($witnessData[1]['name'])) ?  $witnessData[1]['name']  : '', ['class' => 'form-control input-md required' ,'required' => 'required']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('','2. Address',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-8">
                            {!! Form::textarea('address[]', (!empty($witnessData[1]['address'])) ?  $witnessData[1]['address']  : '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1,'required' => 'required']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-8">
                            {!! Form::text('phone[]', (!empty($witnessData[1]['phone'])) ?  $witnessData[1]['phone']  : '', ['class' => 'form-control input-md required','required' => 'required']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-8">
                            {!! Form::text('national_id[]', (!empty($witnessData[1]['national_id'])) ?  $witnessData[1]['national_id']  : '', ['class' => 'form-control input-md required','required' => 'required']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading"><strong>3. Document Presented for Filing By </strong></div>
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 {{$errors->has('name_document_by') ? 'has-error': ''}}">
                        {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-4">
                            {!! Form::text('name_document_by', (!empty($witnessDataFiled->name)) ? $witnessDataFiled->name : '' , ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}
                            {!! $errors->first('name_document_by','<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 {{$errors->has('position_id') ? 'has-error': ''}}">
                        {!! Form::label('','2. Position',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-4">
                            {!! Form::select('position_id',['Chairman' => 'Chairman','CEO' => 'CEO','MD' => 'MD'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                            {!! $errors->first('position_id','<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 {{$errors->has('address_document_by') ? 'has-error': ''}}">
                        {!! Form::label('','3. Address',['class'=>'col-md-4 text-left ']) !!}
                        <div class="col-md-4">
                            {!! Form::textarea('address_document_by', (!empty($witnessDataFiled->address)) ? $witnessDataFiled->address : '' , ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
                            {!! $errors->first('address_document_by','<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 {{$errors->has('district_id') ? 'has-error': ''}}">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-4">
                                    {!! Form::label('','District',['class'=>'col-md-8 text-left ']) !!}
                                </div>
                                <div class="col-md-8">
                                    {!! Form::select('district_id',$districts,$witnessDataFiled->district_id, ['class' => 'form-control input-md required','placeholder' => '','required' => 'required']) !!}
                                    {!! $errors->first('district_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="">
        <div class="col-md-6">
            <button class="btn btn-info" name="action_btn" value="draft" type="submit">Save as Draft</button>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-success" name="action_btn" value="save" type="submit">Save and Continue</button>
        </div>
    </div>

    {!! Form::close() !!}
</fieldset>

@endsection
<script>
    $(document).ready(function () {
        $("#witness_form_edit").validate();
    })
</script>

