@extends('layouts.admin')

@section('page_heading',trans('messages.forcible_data_update_form'))

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
                <h5><strong> {!!trans('messages.forcible_data_update_edit')!!} </strong></h5>
            </div>


            {!! Form::open(array('url' => '/settings/update-forcible-data-update/'.$encrypted_id,'method' => 'patch', 'class' => 'form-horizontal', 'id' => 'forcible_data_update',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('table_name') ? 'has-error' : ''}}">
                    {!! Form::label('table_name','Table name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-10">
                        {!! Form::text('table_name', $data->table_name, ['class' => 'form-control required', 'id' => 'table_name']) !!}
                        {!! $errors->first('table_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('column_name') ? 'has-error' : ''}}">
                    {!! Form::label('column_name','Column name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-10">
                        {!! Form::text('column_name', $data->column_name, ['class' => 'form-control required', 'id' => 'column_name']) !!}
                        {!! $errors->first('column_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('column_type') ? 'has-error' : ''}}">
                    {!! Form::label('column_type','Column type: ',['class'=>'col-md-2 required-star']) !!}
                    <div class="col-md-10">
                        <label class="radio-inline">{!! Form::radio('column_type', 'text', $data->column_type == 'text', ['class'=>'required', 'onchange' => "TypeWiseColumnValue(this.value)", 'id' => 'text']) !!} Text</label>
                        <label class="radio-inline">{!! Form::radio('column_type', 'textarea', $data->column_type == 'textarea', ['class'=>'required', 'onchange' => "TypeWiseColumnValue(this.value)", 'id' => 'textarea']) !!} Textarea</label>
                        <label class="radio-inline">{!! Form::radio('column_type', 'radio', $data->column_type == 'radio', ['class'=>'required', 'onchange' => "TypeWiseColumnValue(this.value)", 'id' => 'radio']) !!} Radio</label>
                        <label class="radio-inline">{!! Form::radio('column_type', 'select', $data->column_type == 'select', ['class'=>'required', 'onchange' => "TypeWiseColumnValue(this.value)", 'id' => 'select']) !!} Select</label>
                        {!! $errors->first('column_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div id="column_radio_value_div" style="display: none;" class="form-group col-md-12 {{$errors->has('column_radio_value') ? 'has-error' : ''}}">
                    {!! Form::label('column_radio_value','Column radio value:',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-10">
                        {!! Form::textarea('column_radio_value',$data->column_radio_value, ['placeholder'=>'Use comma for separate the value', 'class' => 'form-control input-md', 'id'=>'column_radio_value']) !!}
                        <p class="help-block">Use comma (,) for separate the value</p>
                    </div>
                </div>

                <div id="column_select_table_div" style="display: none;" class="form-group col-md-12 {{$errors->has('column_select_table') ? 'has-error' : ''}}">
                    {!! Form::label('column_select_table','Column select table name:',['class'=>'col-md-2 ']) !!}
                    <div class="col-md-10">
                        {!! Form::text('column_select_table', $data->column_select_table, ['class' => 'form-control', 'id' => 'column_select_table']) !!}
                        {!! $errors->first('column_select_table','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('label_name') ? 'has-error' : ''}}">
                    {!! Form::label('label_name','Label name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-10">
                        {!! Form::text('label_name', $data->label_name, ['class' => 'form-control required', 'id' => 'label_name']) !!}
                        {!! $errors->first('label_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('update_type') ? 'has-error' : ''}}">
                    {!! Form::label('update_type','Update type: ',['class'=>'col-md-2   required-star']) !!}
                    <div class="col-md-10">
                        <label class="radio-inline">{!! Form::radio('update_type', 'user', $data->update_type == 'user', ['class'=>'required', 'id' => 'user']) !!} User</label>
                        <label class="radio-inline">{!! Form::radio('update_type', 'company', $data->update_type == 'company', ['class'=>'required', 'id' => 'company']) !!} Company</label>
                        {!! $errors->first('update_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-10">
                        <label class="radio-inline">{!! Form::radio('is_active', '1', $data->status  == '1', ['class'=>'required', 'id' => 'yes']) !!} Active</label>
                        <label class="radio-inline">{!! Form::radio('is_active', '0', $data->status  == '0', ['class'=>'required', 'id' => 'no']) !!} Inactive</label>
                        {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    {!! App\Libraries\CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('/settings/forcible-data-update') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','E'))
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-chevron-circle-right"></i> Save
                        </button>
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
        function TypeWiseColumnValue(column_type) {
            // Column type
            if (column_type == 'radio') {
                $("#column_radio_value_div").show();
                $("#column_select_table_div").hide();
            } else if (column_type == 'select') {
                $("#column_select_table_div").show();
                $("#column_radio_value_div").hide();
            } else {
                $("#column_radio_value_div").hide();
                $("#column_select_table_div").hide();
            }
        }

        $(function () {
            TypeWiseColumnValue('{{$data->column_type}}');
        });
    </script>
@endsection