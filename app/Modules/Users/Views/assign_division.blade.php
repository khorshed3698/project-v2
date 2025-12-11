@extends('layouts.admin')
@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
    ?>
    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading"><h5><strong>Assign User Division</strong></h5></div>

            {!! Form::open(array('url' => 'users/assign-division-save', 'method' => 'post')) !!}

            <div class="panel-body">
                <div class="col-lg-10">
                    <div class="form-group col-md-4">
                        <span>User email</span>
                        <span class="pull-right">:</span>
                    </div>
                        <div class="col-md-8">

                             {{$user_info->user_email}}

                        </div>
                    </div>

                    <div class="col-lg-10">
                        <div class="form-group col-md-4">
                            <span>User Department</span>
                            <span class="pull-right">:</span>
                        </div>
                        <div class="col-md-8">

                            {{$AssignDepartmentName}}

                        </div>
                    </div>

                    <div class="col-lg-10">
                        <div class="form-group col-md-4">
                            <span>User Sub-Department</span>
                            <span class="pull-right">:</span>
                        </div>
                        <div class="col-md-8">

                            {{$AssignSubDepartmentName}}

                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('assign_division', 'Select division to assign:', ['class' => 'col-md-3']) !!}
                        <input type="hidden" name="user_id" value="{{Encryption::encodeId($user_info->id)}}">
                        <div class="col-md-9">
                            <select name="division_ids[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Division to assign" style="width: 100%;" multiple="multiple">
                                @foreach($division_list as $division)
                                    @if(in_array( $division->id, $select))
                                        <option value="{{ $division->id }}" selected="true">{{ $division->division_name }}</option>
                                    @else
                                        <option value="{{ $division->id }}">{{ $division->division_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            {!! $errors->first('division_ids','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default"><i class="fa fa-close"></i> Close</a>
                </div>
                <div class="pull-right">
                    @if(ACL::getAccsessRight('user','E'))
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('footer-script')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script>
        $(document).ready(function(){
            //Select2
            $(".limitedNumbSelect2").select2({
                //maximumSelectionLength: 1
            });
        });
    </script>
@endsection