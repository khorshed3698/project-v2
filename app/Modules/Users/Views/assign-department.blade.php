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
            <div class="panel-heading"><h5><strong>Assign Department</strong></h5></div>
            {!! Form::open(array('url' => 'users/assign-department-save', 'method' => 'post')) !!}
            <div class="panel-body">
                <div class="col-lg-10">
                    <div class="form-group col-md-12">
                        {!! Form::label('email', 'User email:', ['class' => 'col-md-3']) !!}
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            {!! Form::text('email', $user_exist_dpt->user_email, $attributes = array('class'=>'form-control',
                                 'id'=>"",'readonly', 'data-rule-maxlength'=>'100')) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        {!! Form::label('assign_department', 'Department to assign:', ['class' => 'col-md-3']) !!}
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <div class="col-md-9">
                            <select name="department_name[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Department to assign" style="width: 100%;" multiple="multiple">
                                @foreach($dpt_list as $dpt)
                                    @if(in_array( $dpt->id, $select))
                                        <option value="{{ $dpt->id }}" selected="true">{{ $dpt->name }}</option>
                                    @else
                                        <option value="{{ $dpt->id }}">{{ $dpt->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            {{--{!! Form::select('user_types[]', $desk_list, $select, ['class' => 'form-control input-sm limitedNumbSelect2','multiple'=>'true', 'placeholder' => 'Select Desk to assign']) !!}--}}
                            {!! $errors->first('user_types','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('assign_department', 'Sub-department to assign:', ['class' => 'col-md-3']) !!}
                        {{--<input type="hidden" name="user_id" value="{{ $user_id }}">--}}
                        <div class="col-md-9">
                            <select name="sub_department_name[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Department to assign" style="width: 100%;" multiple="multiple">
                                @foreach($sub_dpt_list as $dpt)
                                    @if(in_array( $dpt->id, $select_sub_dpt))
                                        <option value="{{ $dpt->id }}" selected="true">{{ $dpt->name }}</option>
                                    @else
                                        <option value="{{ $dpt->id }}">{{ $dpt->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            {{--{!! Form::select('user_types[]', $desk_list, $select, ['class' => 'form-control input-sm limitedNumbSelect2','multiple'=>'true', 'placeholder' => 'Select Desk to assign']) !!}--}}
                            {!! $errors->first('user_types','<span class="help-block">:message</span>') !!}
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