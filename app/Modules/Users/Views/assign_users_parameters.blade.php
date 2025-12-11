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
            <div class="panel-heading"><h5><strong>Assign User Paramerers</strong></h5></div>

            {!! Form::open(array('url' => 'users/assign-parameters-save', 'method' => 'post')) !!}

            <div class="panel-body">
                <div class="panel panel-info">
                    <div class="panel-heading"><h5><strong> User Information</strong></h5>
                    </div>
                    <div class="panel-body">
                        <div class="form-group clearfix">
                            <div class=" col-md-2">
                                <span class="v_label">Email</span>
                                <span class="pull-right">:</span>
                            </div>
                            <div class="col-md-10">
                                {{$user_info->user_email}}
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class=" col-md-2">
                                <span class="v_label">Name</span>
                                <span class="pull-right">:</span>
                            </div>
                            <div class="col-md-10">
                                {{$fullName}}
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class=" col-md-2">
                                <span class="v_label">Moblie</span>
                                <span class="pull-right">:</span>
                            </div>
                            <div class="col-md-10">
                                {{$user_info->user_phone}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><h5><strong> Desk Assign</strong></h5>
                    </div>
                    <div class="panel-body">
                    <div class="form-group col-md-11">
                    {!! Form::label('assign_desk', 'Select desk:', ['class' => 'col-md-3 v_label']) !!}
                    <div class="col-md-9">
                        <select name="user_types[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Desk to assign" style="width: 100%;" multiple="multiple">
                            @foreach($desk_list as $city)
                                @if(in_array( $city->id, $selectDesk))
                                    <option value="{{ $city->id }}" selected="true">{{ $city->desk_name }}</option>
                                @else
                                    <option value="{{ $city->id }}">{{ $city->desk_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        {{--{!! Form::select('user_types[]', $desk_list, $select, ['class' => 'form-control input-sm limitedNumbSelect2','multiple'=>'true', 'placeholder' => 'Select Desk to assign']) !!}--}}
                        {!! $errors->first('user_types','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                    </div>
                </div>


                <div class="panel panel-info">
                    <div class="panel-heading"><h5><strong> Division Assign</strong></h5>
                    </div>
                    <div class="panel-body">
                       <div class="form-group col-md-11">
                    {!! Form::label('assign_division', 'Select division:', ['class' => 'col-md-3 v_label']) !!}
                    <input type="hidden" name="user_id" value="{{Encryption::encodeId($user_info->id)}}">
                    <div class="col-md-9">
                        <select name="division_ids[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Division to assign" style="width: 100%;" multiple="multiple">
                            @foreach($division_list as $division)
                                @if(in_array( $division->id, $selectDivision))
                                    <option value="{{ $division->id }}" selected="true">{{ $division->office_name }}</option>
                                @else
                                    <option value="{{ $division->id }}">{{ $division->office_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        {!! $errors->first('division_ids','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><h5><strong> Department & Sub-department Assign</strong></h5>
                    </div>
                    <div class="panel-body">
                    <div class="form-group col-md-11">
                    {!! Form::label('assign_department', 'Select department:', ['class' => 'col-md-3 v_label required-star']) !!}
                    <div class="col-md-9">
                        <select name="department_name[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Department to assign" style="width: 100%;" multiple="multiple">
                            @foreach($Department_list as $dpt)
                                @if(in_array( $dpt->id, $selectDepartment))
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

                    <div class="form-group col-md-11">
                    {!! Form::label('assign_department', 'Select sub-department:', ['class' => 'col-md-3 v_label required-star']) !!}
                    <div class="col-md-9">
                        <select name="sub_department_name[]" class="city form-control limitedNumbSelect2" data-placeholder="Select Department to assign" style="width: 100%;" multiple="multiple">
                            @foreach($subDepartment_list as $dpt)
                                @if(in_array( $dpt->id, $selectsubDepartment))
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

                <div class="panel panel-info">
                    <div class="panel-heading"><h5><strong> Training Assign </strong></h5>
                    </div>
                    <div class="panel-body">
                        <div class="form-group col-md-11">
                            {!! Form::label('training_assign', 'Select Training Desk:', ['class' => 'col-md-3 v_label']) !!}
                            <div class="col-md-9">
                                {!! Form::select('training_assign', [1 => 'Coordinator', 2 => 'Director'], $user_info->desk_training_id, $attributes = array('class'=>'form-control', 'placeholder' => 'Select Training Desk', 'id'=>"training_assign")) !!}
                                {!! $errors->first('training_assign','<span class="help-block text-red">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="pull-left">
                <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default" style="font-size: 12px;"><i class="fa fa-close"></i> Close</a>
            </div>
            <div class="pull-right">
                @if(ACL::getAccsessRight('user','E'))
                    <button type="submit" class="btn btn-primary" style="font-size: 12px;"><i class="fa fa-check-circle"></i> Save</button>
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