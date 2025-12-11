@extends('layouts.admin')
@section('content')
    <div class="col-md-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong><i class="fa fa-edit"></i> Edit sector </strong></h5>
            </div>

            <div class="panel-body">
                {!! Form::open(array('url' => '/settings/sector/update/'.\App\Libraries\Encryption::encodeId($sectorInfo->id),'method' => 'post', 'id' => 'sector-info')) !!}
                <div class="form-group col-md-12">
                    {!! Form::label('name','Sector Name: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::text('name',$sectorInfo->name, ['class'=>'form-control required']) !!}
                        {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('status','Section Status: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        <label>{!! Form::radio('status', 1,$sectorInfo->status  == 1) !!} Active</label>
                        <label>{!! Form::radio('status', 0,$sectorInfo->status  == 0) !!} Inactive</label>
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    {!! CommonFunction::showAuditLog($sectorInfo->updated_at, $sectorInfo->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('/settings/sector/list') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                    @if(ACL::getAccsessRight('settings','A'))
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-chevron-circle-right"></i> Update</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
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

