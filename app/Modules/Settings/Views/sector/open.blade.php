@extends('layouts.admin')
@section('content')
    {{--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
    {{--<div class="modal-dialog" role="document">--}}
    {{--<div class="modal-content">--}}
    {{--<div class="modal-header">--}}
    {{--<h6 class="modal-title" id="exampleModalLabel"><strong>Create Sub-sector</strong></h6>--}}
    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
    {{--<span aria-hidden="true">&times;</span>--}}
    {{--</button>--}}
    {{--</div>--}}
    {{--<div class="modal-body">--}}
    {{--{!! Form::open(array('url' => '/settings/sub-sector/store','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info','enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}--}}
    {{--{!! Form::token() !!}--}}
    {{--<div class="row">--}}
    {{--<div class="form-group col-md-12">--}}
    {{--{!! Form::label('name','Sub-sector name: ',['class'=>'col-md-4  required-star']) !!}--}}
    {{--<div class="col-md-8">--}}
    {{--{!! Form::text('name', '', ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}--}}
    {{--{!! $errors->first('name','<span class="help-block">:message</span>') !!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="form-group col-md-12">--}}
    {{--{!! Form::label('doc_required','Sector status: ',['class'=>'col-md-4  required-star']) !!}--}}
    {{--<div class="col-md-8">--}}
    {{--{!! Form::radio('status', 'yes', ['class' => 'form-control']) !!} Yes--}}
    {{--{!! Form::radio('status', 'no', ['class' => 'form-control']) !!} No--}}
    {{--{!! $errors->first('status','<span class="help-block">:message</span>') !!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="modal-footer">--}}
    {{--<button type="button" class="btn btn-secondary pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>--}}
    {{--<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-chevron-circle-right"></i> Save</button>--}}
    {{--</div>--}}
    {{--{!! Form::close() !!}--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    <div class="col-md-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong><i class="fa fa-list"></i> Sector Information </strong></h5>
            </div>

            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-md-12 {{$errors->has('code') ? 'has-error' : ''}}">
                        {!! Form::label('name','Name: ',['class'=>'col-sm-1']) !!}
                        <div class="col-sm-8">{{$sectorInfo->name}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12 {{$errors->has('code') ? 'has-error' : ''}}">
                        {!! Form::label('status','Status: ',['class'=>'col-sm-1']) !!}
                        <div class="col-sm-8">{{($sectorInfo->status == 1)? 'Active':'Inactive'}}</div>
                    </div>
                </div>
                <br>

            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('/settings/sector/list') }}">
                        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>



        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left">
                    <h5><strong><i class="fa fa-list"></i> Sub sectors</strong></h5>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('settings','A'))
                        <a class="addSubSector" data-toggle="modal" data-target="#myModal" onclick="openModal(this)" data-action="{{ url('/settings/sectors/create-sub-sector/'.Encryption::encodeId($sectorInfo->id)) }}">
                            {!! Form::button('<i class="fa fa-plus"></i> <b>New Sub Sector </b>', array('type' => 'button', 'class' => 'pull-right btn btn-default')) !!}
                        </a>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <table aria-label="Detailed Report Data Table" class="table table-bordered" id="list">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="40%">Name</th>
                                <th width="20%">Division</th>
                                <th width="20%">Status</th>
                                <th width="15%">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('footer-script')
    @include('partials.datatable-scripts')

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <script>
        $(function () {
            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{url("settings/sector/get-sub-sector-list")}}',
                    method:'post',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.sector_id = "{{ \App\Libraries\Encryption::encodeId($sectorInfo->id) }}";
                    }
                },
                columns: [
                    {data: 'sl', name: 'sl'},
                    {data: 'name', name: 'name'},
                    {data: 'division', name: 'division'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: true, searchable: true}
                ],
                "aaSorting": []
            });
        });

        function openModal(btn) {
            var this_action = btn.getAttribute('data-action');
            if(this_action != ''){
                $.get(this_action, function(data, success) {
                    if(success === 'success'){
                        $('#myModal .load_modal').html(data);
                    }else{
                        $('#myModal .load_modal').html('Unknown Error!');
                    }
                    $('#myModal').modal('show', {backdrop: 'static'});
                });
            }
        }
    </script>
@endsection