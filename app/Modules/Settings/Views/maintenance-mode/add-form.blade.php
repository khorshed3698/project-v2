<?php
$accessMode = ACL::getAccsessRight('settings');
if (!ACL::isAllowed($accessMode, 'V'))
    die('no access right!');
?>

@extends('layouts.admin')
@section('content')

    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">
            <div class="panel-heading"><h5><strong>Operational Mode</strong></h5></div>
            {!! Form::open(array('url' => 'settings/maintenance-mode/store', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'maintenance_mode_form')) !!}
            <div class="panel-body">
                <div class="col-md-12">

                    <div class="form-group {{$errors->has('operation_mode') ? 'has-error' : ''}}">
                        <label class="col-sm-3 required-star">Operation mode:
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                               title="The Maintenance mode option will take the system under maintenance, and general mode option will revert the system from maintenance."></i></label>
                        <div class="col-md-9">
                            <label class="radio-inline">{!! Form::radio('operation_mode', '1', $maintenance_data->operation_mode == 1, ['class'=>' required', 'onclick' => 'selectOperationMode(this.value)']) !!}
                                General mode</label>
                            <label class="radio-inline">{!! Form::radio('operation_mode', '2', $maintenance_data->operation_mode == 2, ['class'=>'required', 'onclick' => 'selectOperationMode(this.value)']) !!}
                                Maintenance mode</label>
                            {!! $errors->first('operation_mode','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                    <br/>

                    <div class="maintenance_area">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Allowed user types
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                   title="You may select user types to be allowed into the system while the maintenance period."></i>
                            </legend>
                            <div class="form-group {{$errors->has('user_types') ? 'has-error' : ''}}">
                                <label for="user_types" class="col-sm-3">User types:</label>
                                <div class="col-md-9">
                                    <select name="user_types[]"
                                            class="maintenance_field form-control limitedNumbSelect2"
                                            data-placeholder="Select user type" style="width: 100%;"
                                            multiple="multiple">
                                        @foreach($user_types as $type)
                                            @if(in_array( $type->id, explode(',', $maintenance_data->allowed_user_types)))
                                                <option value="{{ $type->id }}"
                                                        selected="true">{{ $type->type_name }}</option>
                                            @else
                                                <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    {!! $errors->first('user_types','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </fieldset>
                        <br/>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">
                                Allowed users
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                   title="Similar to user type"></i>
                            </legend>
                            <div class="form-group {{$errors->has('user_email') ? 'has-error' : ''}}">
                                <label class="col-sm-3">User email id:</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::email('user_email', '', ['class'=>'form-control email maintenance_field', 'placeholder' => 'Enter user\'s email']) !!}
                                        <span class="input-group-btn">
                                <button class="btn btn-info maintenance_btn" type="submit" value="add_user"
                                        name="submit_btn"> Add</button>
                            </span>
                                    </div>
                                    {!! $errors->first('user_email','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <br/>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table aria-label="Detailed Report Data Table" id="user_list1" class="table table-striped table-bordered dt-responsive"
                                           cellspacing="0"
                                           width="100%" style="margin: 0">
                                        <thead>
                                        <tr>
                                            <th>SN#</th>
                                            <th>Email</th>
                                            <th>Name</th>
                                            <th>User type</th>
                                            <th>Contact number</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($users) > 0)
                                            @foreach($users as $key => $user)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $user->user_email }}</td>
                                                    <td>{{ $user->user_first_name . ' ' . $user->user_middle_name . ' ' . $user->user_last_name }}</td>
                                                    <td>{{ $user->type_name }}</td>
                                                    <td>{{ $user->user_number }}</td>
                                                    <td>
                                                        <a class="btn btn-danger btn-xs maintenance_btn"
                                                           href="{{ url('settings/maintenance-mode/remove-user/'. Encryption::encodeId($user->id)) }}">Remove</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" style="text-align: center">
                                                    <span class="text-danger">No user in allowed list</span>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>

                        <br/>
                        <div class="form-group {{$errors->has('alert_message') ? 'has-error' : ''}}">
                            <label class="col-sm-3 required-star">Alert message:
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                                   title="Alert message will be shown to user"></i></label>
                            <div class="col-md-9">
                                {!! Form::textarea('alert_message', $maintenance_data->alert_message, ['class'=>'form-control required maintenance_field', 'placeholder' => 'alert message', 'size' => '3x4']) !!}
                                {!! $errors->first('alert_message','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>


                    <hr/>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="pull-left">
                                <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default"><i
                                            class="fa fa-close"></i> Close</a>
                            </div>
                            <div class="pull-right">
                                @if(ACL::getAccsessRight('settings','E'))
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i>
                                        Save
                                    </button>
                                @endif
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('footer-script')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>

    @include('partials.datatable-scripts')

    <script>
        $(document).ready(function () {

            $('.user_email').select2();

            //Select2
            $(".limitedNumbSelect2").select2({
                //maximumSelectionLength: 1
            });

            $("#maintenance_mode_form").validate({
                errorPlacement: function () {
                    return false;
                }
            });


            // Trigger selected operation mode
            var element = document.querySelector('input[name="operation_mode"]:checked');
            element.dispatchEvent(new Event('click'));


            $('#user_list').DataTable({
                processing: true,
                serverSide: true,
                aaSorting: [],
                ajax: {
                    url: '{{url("settings/maintenance-mode/get-users-list")}}',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                    }
                },
                columns: [
                    {data: 'sl_no', name: 'sl_no'},
                    {data: 'email', name: 'email'},
                    {data: 'name', name: 'name'},
                    {data: 'user_type', name: 'user_type'},
                    {data: 'contact_number', name: 'contact_number'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });


        function selectOperationMode(operation_mode) {
            var changeable_elements = document.querySelectorAll('.maintenance_area select, .maintenance_area input, .maintenance_area .btn, .maintenance_area textarea');

            if (operation_mode == 1) {
                changeable_elements.forEach(function (value, key) {
                    value.disabled = true;
                });
            } else {
                changeable_elements.forEach(function (value, key) {
                    value.disabled = false;
                });
            }
        }
    </script>
@endsection