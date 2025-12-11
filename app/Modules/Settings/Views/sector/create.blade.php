@extends('layouts.admin')
@section('content')
    <div class="col-md-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong><i class="fa fa-list"></i> Create sector </strong></h5>
            </div>

            <div class="panel-body">
                {!! Form::open(array('url' => '/settings/sector/store','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'sector-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                {!! Form::token() !!}
                <div class="form-group col-md-12">
                    {!! Form::label('name','Sector Name: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        {!! Form::text('name', '', ['class'=>'form-control required','placeholder'=>'Sector name']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('status','Section Status: ',['class'=>'col-md-3  required-star']) !!}
                    <div class="col-md-7">
                        <label>{!! Form::radio('status', '1', ['class' => 'form-control']) !!} Active</label>
                        <label style="padding-left: 15px;">{!! Form::radio('status', '0', ['class' => 'form-control']) !!} Inactive</label>
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-md-12">
                    <a href="{{ url('/settings/sector/list') }}">
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
        var _token = $('input[name="_token"]').val();

        $(document).ready(function () {
            $("#sector-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });
    </script>
@endsection <!--- footer script--->

